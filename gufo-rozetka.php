<?php
header('Content-Type: text/html; charset=utf-8');
//план такой - сначала мы разбиваем айтемы по параметрам
//потом для кажждого айтема формируем имя на основе параметров айтема
class GufoRozetka
{
    private function readFile()
    {
        $xml=file_get_contents('rozetka.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    private function getItemVendor($item)
    {
        preg_match("#<vendor>(.*?)<\/vendor>#",$item,$matches);
        $vendor=$matches[1];
        return $vendor;
    }

    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function stripHead($txt)
    {
        //var_dump ($txt);
        $pos=strpos($txt,"<offers>");
        //var_dump ($pos);
        $new_txt=substr($txt,$pos);
        $new_txt=str_ireplace("<offers>","",$new_txt);
        $new_txt=str_ireplace("</offers>","",$new_txt);
        return $new_txt;
    }

    private function getParams($item)
    {
        //var_dump ($item);
        if (preg_match_all("#<param name(.*?)<\/param>#",$item,$matches))
        {
            //var_dump ($matches);
            $params=$matches[0];
            /*foreach($matches as $param)
            {
                $params[]="<param name".$param[1]."</param>";
            }*/
        }
        //var_dump ($params);
        return $params;
    }

    private function getParamName($param)
    {
        if (preg_match("#\"(.+?)\"#",$param,$matches))
        {
            $paramName=$matches[1];
        }
        return $paramName;
    }

    private function getParamVal($param)
    {
        //var_dump ($param);
        if (preg_match("#>(.+?)<#",$param,$matches))
        {
            $paramVal=$matches[1];
        }
        return $paramVal;
    }

    private function getItemsArr ($txt)
    {
        $arr=explode("</offer>",$txt);
        foreach ($arr as $pos)
        {
            $arr1[]=$pos."</offer>";
        }
        //последий элемент полученнного макссива всегда пуст, удаляем его
        array_pop($arr1);
        return $arr1;
    }

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    private function findColors($params)
    {
        foreach ($params as $param)
        {
            $name=$this->getParamName($param);
            if (strcmp($name,"Цвет")==0)
            {
                if (!strripos($param,"-"))
                {
                    $val=$this->getParamVal($param);
                    //если цвет через слеш, то разбиваем его и в качестве цвета используем последнее значение
                    $val=explode("/",$val);
                    $n=count($val)-1;
                    $val=$val[$n];
                    $new_params[]="<param name=\"Цвет\">$val</param>";
                }
            }
            else
            {
                $new_params[]=$param;
            }
        }
        return $new_params;
    }

    private function getParamsList($params)
    {
        foreach ($params as $param)
        {
            $paramName=$this->getParamName($param);
            $paramNames[]=$paramName;
        }
        //echo "<pre>".print_r($paramNames)."</pre>";
        $countParams=array_count_values($paramNames);
        //echo "<pre>".print_r($countParams)."</pre>";

        foreach ($countParams as $key => $value)
        {
            if ($value>1)
            {
                $s[$key]=$value;
                //echo "$key - $value<br>";
            }
        }
        //echo "<pre>".print_r($s)."</pre>";
        //
        
        return $s;
    }

    private function find1($s,$item)
    {
        //все параметры айтема
        $params=$this->getParams($item);
        //отсекаем ненужные параметры
        foreach ($s as $key=>$value)
        {
            if ((strcmp($key,"Цвет")==0)||(strcmp($key,"Размер")==0))
            {
                $new_s[$key]=$value;
            }
        }
        //echo "<pre>".print_r($new_s)."</pre>";
        if (count($new_s)==1)
        {
            //echo "Связь по одному параметру<br>";
            foreach($new_s as $key=>$value)
            {
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //для каждого параметра в айтеме сроавниваем его имя с тем, по которому будем делать связь. Если находим такой параметр - то получаем новый айтем, у которого остается только одна пара Параметр-Значение, которая соответствует текущему найденному параметру
                    //
                    if ($key==$paramName)
                    {
                        $newItems.=$this->makeNewItem($paramName,$paramVal,$item);
                    }
                }
            }
        }
        if (count($new_s)==2)
        {
            //echo "Связь по двум параметрам<br>";
            //for ($i=1;$i<=$new_s['Цвет'];$i++)
            //{
            foreach ($params as $param)
            {
                $paramName=$this->getParamName($param);
                $paramVal=$this->getParamVal($param);
                if (strcmp('Цвет',$paramName)==0)
                {
                    $newItems.=$this->makeNewItem($paramName,$paramVal,$item);
                }
            }
                
            //}
            //var_dump($newItems);
            $newItemsArr=$this->getItemsArr($newItems);

            $mas['Размер']=$new_s['Размер'];
            foreach ($newItemsArr as $item1)
            {
                $newItems1.=$this->find1($mas,$item1);

            }

        }
        
        if (!is_null($newItems1))
        {
            return $newItems1;
        }
        else
        {
            return $newItems;
        }
        
    }

    private function makeNewItem($paramName,$paramVal,$item)
    {
        $params=$this->getParams($item);
        foreach ($params as $param)
        {
            $parName=$this->getParamName($param);
            $parVal=$this->getParamVal($param);
            if ((strcmp ($parName,$paramName)==0)&&(strcmp ($parVal,$paramVal)!=0))
            {
                //echo "<br>YAY!<br>";
                //echo 
                $item=str_ireplace("<param name=\"$paramName\">$parVal</param>","",$item);
            }
        }
        return $item;
    }

    private function getItemId($item)
    {
        preg_match("#id=\"(.*?)\"#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    private function addGroupToItems($items)
    {
        $id=$this->getItemId($items);
        //echo "<br>addGroupToItems = $id<br>";
        $ItemsArr=$this->getItemsArr($items);
        $n=1;
        foreach ($ItemsArr as $item)
        {
            $newItem=str_ireplace(" id=\"$id\""," id=\"$id$n\"",$item);
            //у айтемов, которые идут за первым не должно быть никаких параметров кроме тех, по которым идет связь
            //if ($n>1)
            //{
            //    $newItem=$this->delParamsForSubItems($newItem);
            //}
            $n++;
            //echo "$newItem<br>";
            $newItems.=$newItem;
        }
        return $newItems;
    }
    
    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }

    private function cleanParams($params)
    {
        $new_params=preg_replace("#<param name=\"Возраст\">(.*?)\/(.*?)<\/param>#","",$params);
        $new_params=preg_replace("#<param name=\"Рост\">(.*?)\/(.*?)<\/param>#","",$params);
        return $new_params;
    }

    /*private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }*/
    
    private function getItemColour($item)
    {
        preg_match("#<param name=\"Цвет\">(.*?)<\/param>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getItemSize($item)
    {
        preg_match("#<param name=\"Размер\">(.*?)<\/param>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getItemArticle($item)
    {
        preg_match("#<article>(.*?)<\/article>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function setName($item)
    {
        $name=$this->getItemName($item);
        //к имени добавляем цвет, размер, артикул
        $color=$this->getItemColour($item);
        $size=$this->getItemSize($item);
        $article=$this->getItemArticle($item);
        $nameNew=$name." $color размер $size ($article)";
        //echo "$name-$nameNew<br>";
        $item=str_ireplace("<name>$name</name>","<name>$nameNew</name>",$item);
        return $item;

    }

    private function getSeason($paramVal)
    {
        //echo $paramVal."<br>";
        $value=str_ireplace("/"," ",$paramVal);
        $value=str_ireplace("-"," ",$value);
        $value=str_ireplace("Все сезоны","",$value);
        $value=str_ireplace("< param>","",$value);
        $value=trim(preg_replace('/\s+/', ' ', $value));
        //$value=str_ireplace(" ","}",$value);
        //$value=ucwords($value);
        //Ставим первую букву сезона всегда заглавной
        $value=mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
        //echo $value."<br>";
        $valueArr=explode(" ",$value);
        $countVal=array_count_values($valueArr);
        //var_dump($countVal);
        //echo "<pre>".print_r($countVal),"</pre>";
        $maxVal=0;
        foreach ($countVal as $key=>$value)
        {
            if($value>$maxVal)
            {
                $maxVal=$value;
            }
        }
        //echo "max=$maxVal<br>";
        $season=array_search($maxVal,$countVal);
        //echo "$season<br>";
        return $season;
    }

    public function test()
    {
        $xml=$this->readFile();
        //базовая очистка
        $xml=str_ireplace(" unit=\"\"","",$xml);
        $xml=str_ireplace("<param name=\"Размер\">one size</param>","",$xml);
        $XMLnew=preg_replace("#<description>(.*?)<\/description>#s","<description></description>",$xml);
        $XMLnew=preg_replace("#<param name=\"Возраст\">(.*?)\/(.*?)<\/param>#","",$XMLnew);
        $XMLnew=preg_replace("#<param name=\"Рост\">(.*?)\/(.*?)<\/param>#","",$XMLnew);
        $XMLnew=preg_replace("# в стиле(.*?)<\/name>#","</name>",$XMLnew);

        $xmlhead=$this->getXMLhead($xml);
        $XMLnew=$this->stripHead($XMLnew);
        //var_dump($XMLnew);
        $items=$this->getItemsArr($XMLnew);
        //echo "<pre>".print_r($items)."</pre>";
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                //echo "gg<br>";
                $params=$this->getParams($item);
                $itemHead=$this->getItemHead($item);
                $itemHead=str_ireplace("</item>","",$itemHead);
                $params=$this->findColors($params);
                //удалить параметрв возраст, рост где есть "/"
                //$params=$this->cleanParams($params);

                $list=$this->getParamsList($params);
                //echo "<pre>".print_r($list)."</pre>";
                if (is_array($list))
                {
                    $newItems=$this->find1($list,$item);
                    //var_dump($newItems);
                    $newItems=$this->addGroupToItems($newItems);
                    //$XMLBodyNew.=$newItems;    
                    //break;
                }
                else
                {
                    $newItems=$item;
                }
                
                $XMLBodyNew.=$newItems;
            }
            $XMLBodyNew=preg_replace("#<param name=\"Возраст\">(.*?)<\/param>#","",$XMLBodyNew);
            $XMLBodyNew=preg_replace("#<param name=\"Рост\">(.*?)<\/param>#","",$XMLBodyNew);
            $XMLBodyNew=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $XMLBodyNew);
            //на данный мормент у нас есть разбитые оферы
            //надо подчистить параметры
            //потом - сформировать правильное имя
            //echo $XMLBodyNew;
            $newItems=null;
            $items=$this->getItemsArr($XMLBodyNew);
            foreach ($items as $item)
            {
                $item=$this->setName($item);
                //echo "$item<br>";
                $params=$this->getParams($item);
                //echo "<pre>".print_r($params)."</pre>";
                //break;
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    if (strcmp($paramName,"Сезон")==0)
                    {
                        $val=$this->getParamVal($param);
                        $season=$this->getSeason($val);
                        $newParam="<param name=\"Сезон\">$season</param>";
                        $item=str_ireplace("<param name=\"Сезон\">$val</param>","<param name=\"Сезон\">$season</param>",$item);
                    }
                    
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $paramVal=$this->getParamVal($param);
                        $val=explode("/",$paramVal);
                        $val=$val[0];
                        $item=str_ireplace("<param name=\"Пол\">$paramVal</param>","<param name=\"Пол\">$val</param>",$item);
                    }
                    if (strcmp($paramName,"Стиль")==0)
                    {
                        $paramVal=$this->getParamVal($param);
                        $val=explode("/",$paramVal);
                        $val=$val[0];
                        $item=str_ireplace("<param name=\"Стиль\">$paramVal</param>","<param name=\"Стиль\">$val</param>",$item);
                    }
                }
                $newItems.=$item;
            }
            $newXml=$xmlhead.PHP_EOL."</categories>".PHP_EOL."<offers>".PHP_EOL.$newItems.PHP_EOL."</offers>".PHP_EOL."</shop>".PHP_EOL."</yml_catalog>";
            file_put_contents("gufo_rozetka.xml",$newXml);
        }
        else
        {
            echo "No items";
        }
    } 
}

echo "Start ".date("Y-m-d H:i:s")."<br>";
$test=new GufoRozetka();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");