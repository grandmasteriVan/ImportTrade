<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * Gufo
 */
class Gufo
{    
    /**
     * readFile
     *
     * @return void
     */
    private function readFile()
    {
        $xml=file_get_contents('index.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    /**
     * getCatId
     * пролучаем АйДи категории для конкретного айтема
     * @param  mixed $item - айтем
     * @return void - айди категории 
     */
    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    /**
     * getItemCode
     * получаем айди айтема
     * @param  mixed $item - айтем
     * @return void - айди айтема
     */
    private function getItemCode($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getItemId($item)
    {
        preg_match("#<item id=\"(.*?)\"#",$item,$matches);
        $id=$matches[1];
        return $id;
    }
    
    /**
     * delDescription
     *
     * @param  mixed $item
     * @return void
     */
    private function delDescription ($item)
    {
        $item=str_ireplace("&","",$item);
        $item=preg_replace("#<description>(.*?)<\/description>#s","<description></description>",$item);
        //удяляем лишние пробелы
        //$xml = preg_replace('/\s+/', ' ', $xml);
        $item=str_ireplace(" unit=\"\"","",$item);
        return $item;
    }
    
    /**
     * setDescription
     *
     * @param  mixed $item
     * @return void
     */
    private function setDescription($item)
    {
        $name=$this->getItemName($item);
        $item=str_ireplace("<description></description>","<description>$name</description>",$item);
    }
    
    /**
     * getItemName
     *
     * @param  mixed $item
     * @return void
     */
    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    /**
     * stripHead
     * удаляем из ХМЛки все статические элементы, оставляя толлько айтемы
     * @param  mixed $txt - начальная ХМЛка
     * @return void - только айтемы
     */
    private function stripHead($txt)
    {
        //var_dump ($txt);
        $pos=strpos($txt,"<items>");
        //var_dump ($pos);
        $new_txt=substr($txt,$pos);
        $new_txt=str_ireplace("<items>","",$new_txt);
        $new_txt=str_ireplace("</items>","",$new_txt);
        return $new_txt;
    }
    
    /**
     * getItemsArr
     * Вормируем массив айтемов из их списка
     * @param  mixed $txt - список айтемов
     * @return array - массив, где каждый айтем - отдельный элемент.
     */
    private function getItemsArr ($txt)
    {
        $arr=explode("</item>",$txt);
        foreach ($arr as $pos)
        {
            $arr1[]=$pos."</item>";
        }
        //последий элемент полученнного макссива всегда пуст, удаляем его
        array_pop($arr1);
        return $arr1;
    }

    /**
     * getParams
     * Получаем список параметров конкретного айтема
     * @param  mixed $item - айтем
     * @return void - список параметров айтема
     */
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
        else
        {
            $id=$this->getItemId($item);
            echo "No params found for $id<br>";
        }
        //var_dump ($params);
        return $params;
    }

    /**
     * getParamName
     * получаем имя конкретного параметра
     * @param  mixed $param - параметр
     * @return void - имя параметра
     */
    private function getParamName($param)
    {
        if (preg_match("#\"(.+?)\"#",$param,$matches))
        {
            $paramName=$matches[1];
        }
        return $paramName;
    }

    /**
     * getParamVal
     * Получаем значение параметра
     * @param  mixed $param - параметр
     * @return void - значение параметра
     */
    private function getParamVal($param)
    {
        //var_dump ($param);
        if (preg_match("#>(.+?)<#",$param,$matches))
        {
            $paramVal=$matches[1];
        }
        return $paramVal;
    }

    /**
     * getXMLhead
     * Получаем часть ХМЛ, которая идет до списка айтемов
     * @param  mixed $txt - ХМЛ
     * @return void - часть ХМЛ, которая идет до списка айтемов
     */
    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }
    
    /**
     * getParamsList
     *
     * @param  mixed $params
     * @return void
     */
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
        return $s;
    }
    
    /**
     * find1
     *
     * @param  mixed $s
     * @param  mixed $item
     * @return void
     */
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
        //var_dump($newItems1);
        /*
        foreach($s as $key=>$value)
        {
            //$count
            if ((strcmp($key,"Цвет")==0)||(strcmp($key,"Размер")==0))
            {
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    if ($key==$paramName)
                    {
                        $newItems.=$this->makeNewItem($paramName,$paramVal,$item);
                    }
                }
            }
            
        }
        */
        //var_dump ($newItems);
        if (!is_null($newItems1))
        {
            return $newItems1;
        }
        else
        {
            return $newItems;
        }
        
    }    
    
    
    /**
     * makeNewItem
     * получаем айтем и Параметр и его значение которое нужно оставить
     * На выходе получаем айтем, в котором остается только однин Параметр с выбранным именем и значенмием
     * при этом параметры с другим именем не трогаем
     * @param  mixed $paramName
     * @param  mixed $paramVal
     * @param  mixed $item
     * @return void
     */
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
    
    /**
     * addGroupToItems
     * Меняем ай ди группы айтеом на правильный и добавляем айди группы
     * (старый айди айтема делаем айди группы, айди айтема формируем следующим образом: старый айди+номер по порядку)
     * @param  mixed $items
     * @return void
     */
    private function addGroupToItems($items)
    {
        $id=$this->getItemId($items);
        //echo "<br>addGroupToItems = $id<br>";
        $ItemsArr=$this->getItemsArr($items);
        $n=1;
        foreach ($ItemsArr as $item)
        {
            $newItem=str_ireplace("<item id=\"$id\"","<item id=\"$id-$n\" group_id=\"$id\"",$item);
            //у айтемов, которые идут за первым не должно быть никаких параметров кроме тех, по которым идет связь
            if ($n>1)
            {
                $newItem=$this->delParamsForSubItems($newItem);
            }
            $n++;
            //echo "$newItem<br>";
            $newItems.=$newItem;
        }
        return $newItems;
    }

    /**
     * getItemHead
     * получаем статическую часть айтема (все, что до параметров, то, что мы не меняем)
     * @param  mixed $item - айтем
     * @return void - часть айтема до параметров
     */
    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }
    
    /**
     * delParamsForSubItems
     * Во всех связанных товаров, кроме первого, надо оставлять только те параметры, по которым идет связь
     * 
     * @param  mixed $item
     * @return void
     */
    private function delParamsForSubItems($item)
    {
        $itemHead=$this->getItemHead($item);
        $params=$this->getParams($item);
        //var_dump ($item);
        //var_dump($params);
        if (is_array($params))
        {
            foreach ($params as $param)
            {
                $paramName=$this->getParamName($param);
                if ((strcmp($paramName,"Цвет")==0)||(strcmp($paramName,"Размер")==0))
                {
                    $newParams.=$param.PHP_EOL;
                }
            }
        }
        $newItem=$itemHead.$newParams."</item>";
        return $newItem;
    }
    
        
    /**
     * getSeason
     * 
     * @param  mixed $paramVal
     * @return void
     */
    private function getSeason($paramVal)
    {
        echo $paramVal."<br>";
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
        echo "$season<br>";
        return $season;
    }
    
    /**
     * getType
     * В обуви (возможно и с другими товарами) пкевым
     * @param  mixed $item
     * @return void
     */
    private function getType($item)
    {
        $name=$this->getItemName($item);
        $type=explode(' ',trim($my_value))[0];
        return $type;
    }
    
    /**
     * BaseClean
     *
     * @return void
     */
    public function BaseClean()
    {
        $xml=$this->readFile();
        $XMLnew=$this->delDescription($xml);
        //убираем названия брендов из имени
        $XMLnew=preg_replace("# в стиле(.*?)<\/name>#","</name>",$XMLnew);
        file_put_contents("gufo_new-clean.xml",$XMLnew);
    }
    
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $xml=$this->readFile();
        $XMLnew=$this->delDescription($xml);
        $XMLnew=$this->stripHead($XMLnew);
        $items=$this->getItemsArr($XMLnew);
        $XMLHead=$this->getXMLhead($xml);
        
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                //var_dump($item);
                $catId=$this->getCatId($item);
                $params=$this->getParams($item);
                //это айтем до параметров. Мы его трогать вообще никогда не будем
                $itemHead=$this->getItemHead($item);
                $itemHead=str_ireplace("</item>","",$itemHead);

                foreach($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    if (strcmp($paramName,"Сезон")==0)
                    {
                        $val=$this->getParamVal($param);
                        $season=$this->getSeason($val);
                        $newParam="<param name=\"Сезон\">$season</param>";
                    }
                }
                //echo "<pre>".print_r($params)."</pre>";
                $id=$this->getItemId($item);
                if ($catId==2060||$catId==2068||$catId==2069||$catId==2070||$catId==2071||$catId==2072||$catId==2084||$catId==2092||$catId==2115||$catId==2118||$catId==2122||$catId==2124||$catId==2125||$catId==2169||$catId==2172||$catId==2173||$catId==2176||$catId==2180)
                {
                    $list=$this->getParamsList($params);
                    if (is_array($list))
                    {
                        $newItems=$this->find1($list,$item);
                        //var_dump($newItems);
                        $newItems=$this->addGroupToItems($newItems);
                    }
                    else
                    {
                        $newItems=$item;
                    }
                }
                else
                {
                    $newItems=$item;
                }
                //обувь
                if ($catId==2022||$catId==2047||$catId==2048||$catId==2049||$catId==2050||$catId==2051||$catId==2179)
                {
                    $country="Китай";
                    $param_new=null;
                    if (is_array($params))
                    {
                        foreach ($params as $param)
                        {
                            $paramName=$this->getParamName($param);
                            $paramVal=$this->getParamVal($param);
                            if(strcmp($paramName,"Пол")==0)
                            {

                            }
                        }
                    }
                }
                
                //$id=$this->getItemId($item);
                //echo "id=$id<br>";
                //break;
                //echo gettype($newItems);
                $XMLBodyNew.=$newItems;
                
            }
        }
        else
        {
            echo "No items find in XML<br>";
        }

        //удаляем пустые строки
        $XMLBodyNew=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $XMLBodyNew);
        $newXml=$XMLHead.PHP_EOL."</categories>".PHP_EOL."<items>".PHP_EOL.$XMLBodyNew.PHP_EOL."</items>".PHP_EOL."</price>";
        file_put_contents("gufo_new.xml",$newXml);
        //file_put_contents("gufo_new.xml",$XMLnew);
    }

    public function test2()
    {
        $xml=$this->readFile();
        $XMLnew=$this->delDescription($xml);
        $XMLnew=$this->stripHead($XMLnew);
        $items=$this->getItemsArr($XMLnew);
        $XMLHead=$this->getXMLhead($xml);
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                $catId=$this->getCatId($item);
                $params=$this->getParams($item);
                //это айтем до параметров. Мы его трогать вообще никогда не будем
                $itemHead=$this->getItemHead($item);
                $itemHead=str_ireplace("</item>","",$itemHead);
                if ($catId==2022||$catId==2047||$catId==2048||$catId==2049||$catId==2050||$catId==2051||$catId==2179)
                {
                    $country="Китай";
                    $param_new=null;
                    foreach($params as $param)
                    {
                        $paramName=$this->getParamName($param);
                        $paramVal=$this->getParamVal($param);
                        if (strcmp($paramName,"Сезон")==0)
                        {
                            $val=$this->getParamVal($param);
                            $season=$this->getSeason($val);
                            $newParam="<param name=\"Сезон\">$season</param>";
                        }
                        if (strcmp($paramName,"Пол")==0)
                        {
                            $param_new=str_ireplace("Девочкам","Для девочек",$param);
                            $param_new=str_ireplace("Мальчикам","Для мальчиков",$param_new);
                        }
                        
                        if (strcmp($paramName,"Размер")==0)
                        {
                            $param_new=str_ireplace("Размер","Размер детской обуви",$param);
                        }
                        
                        if (strcmp($paramName,"Материал верха")==0)
                        {
                            $param_new=str_ireplace("Экокожа","Искусственная кожа",$param);
                            $param_new=str_ireplace("ПУ кожа","Искусственная кожа",$param_new);
                            $param_new=str_ireplace("Комбинированные материалы","Комбинированный",$param_new);
                            $param_new=str_ireplace("Микрофибра","Комбинированный",$param_new);
                            $param_new=str_ireplace("Стрейч","Комбинированный",$param_new);
                            $param_new=str_ireplace("Джинс","Хлопок",$param_new);
                        }
                        if (strcmp($paramName,"Материал подкладки")==0)
                        {
                            $param_new=str_ireplace("Натуральная кожа","Кожа",$param);
                        }
                        
                        if (strcmp($paramName,"Особенности обуви")==0)
                        {
                            $param_new=str_ireplace("Особенности обуви","Застежка",$param);
                            $param_new=str_ireplace("Шнурки","Шнуровка",$param_new);
                            $param_new=str_ireplace("шнурки-резинка","Шнуровка",$param_new);
                        }
                        if (strcmp($paramName,"Особенности обуви")==0)
                        {
                            if (strripos($paramVal,"С бисером"))
                            {
                                $param_new="<param name=\"Отделка и украшения\">Бисер</param>";
                            }
                            if (strripos($paramVal,"с мехом"))
                            {
                                $param_new="<param name=\"Отделка и украшения\">Мех</param>";
                            }
                            if (strripos($paramVal,"Кристаллы"))
                            {
                                $param_new="<param name=\"Отделка и украшения\">Стеклярус</param>";
                            }
                            if (strripos($paramVal,"С пайетками"))
                            {
                                $param_new="<param name=\"Отделка и украшения\">Пайетки</param>";
                            }
                        }
                        if (strcmp($paramName,"Цвет")==0)
                        {
                            $param_new=str_ireplace("черный","Черный",$param);
                            $param_new=str_ireplace("бежевый","Бежевый",$param_new);
                            $param_new=str_ireplace("белый","Белый",$param_new);
                            $param_new=str_ireplace("бордовый","Бордовый",$param_new);
                            $param_new=str_ireplace("бронзовый","Бронзовый",$param_new);
                            $param_new=str_ireplace("желтый","Желтый",$param_new);
                            $param_new=str_ireplace("зеленый","Зелёный",$param_new);
                            $param_new=str_ireplace("золотой","Золотистый",$param_new);
                            $param_new=str_ireplace("коралловый","Коралловый",$param_new);
                            $param_new=str_ireplace("коричневый","Коричневый",$param_new);
                            $param_new=str_ireplace("молочный","Кофе с молоком",$param_new);
                            $param_new=str_ireplace("красный","Красный",$param_new);
                            $param_new=str_ireplace("малиновый","Малиновый",$param_new);
                            $param_new=str_ireplace("оливковый","Оливковый",$param_new);
                            $param_new=str_ireplace("оранжевый","Оранжевый",$param_new);
                            $param_new=str_ireplace("розовый","Розовый",$param_new);
                            $param_new=str_ireplace("салатовый","Салатовый",$param_new);
                            $param_new=str_ireplace("серый","Серый",$param_new);
                            $param_new=str_ireplace("серебро","Серебристый",$param_new);
                            $param_new=str_ireplace("серебристый","Серебристый",$param_new);
                            $param_new=str_ireplace("синий","Синий",$param_new);
                            $param_new=str_ireplace("сиреневый","Сиреневый",$param_new);
                            $param_new=str_ireplace("фиолетовый","Фиолетовый",$param_new);
                            $param_new=str_ireplace("хаки","Хаки",$param_new);
                            if(strripos($paramVal,"-"))
                            {
                                $param_new="<param name=\"Цвет\">Разные цвета</param>";
                            }

                        }
                        $params_new[]=$param_new;
                    }
                    //а тут мы будем прописывать захардкодженные параметры
                    $params_new[]="<param name=\"Состояние\">Новое</param>";
                    
                }
            }
        }
    }

}
echo "Start ".date("Y-m-d H:i:s")."<br>";
$test=new Gufo();
$test->test();
$test->BaseClean();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
