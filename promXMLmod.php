<?php
//header('Content-Type: text/html; charset=utf-8');

class testXML
{
    //private $baseXML;
    
    private function readFile()
    {
        //$xml=file_get_contents('test.xml');
        $xml=file_get_contents('prom_ua.xml');
        //$this->baseXML=file_get_contents('prom_ua.xml');
        //var_dump ($xml);
        return $xml;
    }

    /*private function stripCats($txt)
    {
        $new_txt=preg_replace("#</categories>(.*?)</categories>#","");
        return $new_txt;
    }
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

    private function getItemsArr ($txt)
    {
        $arr=explode("</item>",$txt);
        foreach ($arr as $pos)
        {
            $arr1[]=$pos."</item>";
        }
        return $arr1;
    }

    private function delSpaces($txt)
    {
        $new_txt=str_replace("> ",">",$txt);
        $new_txt=str_replace(" >",">",$new_txt);
        $new_txt=str_replace("  "," ",$new_txt);
        return $new_txt;
    }

    private function getCatId($item)
    {
        //var_dump ($item);
        /*if (preg_match("<categoryId>(.*?)<\/categoryId>",$item,$matches)==1)
        {
            $id=$matches[1];
        }
        else
        {
            echo "No catId find for item:".$item."<br>";
            return null;
        }*/
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
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
            echo "No params found<br>";
        }
        //var_dump ($params);
        return $params;
    }

    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    public function parseXML()
    {
        $xml=$this->readFile();
        //echo $this->$baseXML;
        //return null;
        //сохраняем начало ХМЛ
        $xmlHead=$this->getXMLhead($xml);
        $xml_new=$this->stripHead($xml);
        //var_dump ($xml_new); echo "<br>";
        $items=$this->getItemsArr($xml_new);
        //var_dump ($items);
        //return null;
        foreach($items as $item)
        {
            //обнуляем новую позицию перед созданием
            $new_item=null;
            $catId=$this->getCatId($item);
            //это массив параметров айтема. Их мы как раз и будем менять, при чем как имя параметра, так и его значение
            $params=$this->getParams($item);
            //var_dump ($params);
            //это айтем до параметров. Мы его трогать вообще никогда не будем
            $itemHead=$this->getItemHead($item);
            //пошли по доке по разделам
            if ($catId==169)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                    }
                    if (strcmp($paramName,"Тип")==0)
                    {
                        $param_new=str_ireplace("Тип","Тип средства",$param);
                        $param_new=str_ireplace("Гель, мазь","Гель",$param_new);
                        $param_new=str_ireplace("Крема","Крем",$param_new);
                    }
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $params_new[]="<param name=\"Возраст\">18+</param>";
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }
            //new test
            if ($catId==160)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Женский;Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской;Женский","Унисекс",$param_new);
                    }
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }
            //тут будем сорбиратьвсе позиции
            $items_new.=$new_item;
        }
        //обрамляем айтемсы нужным тегом
        $items_new="<items>".$items_new."</items>";
        //начинаем собирать финальную ХМЛку
        $XMLnew=$xmlHead.$items_new;
        $XMLnew=$this->delSpaces($XMLnew);
        var_dump($XMLnew);
        file_put_contents("new_test.xml",$XMLnew);

    }
    
}

$test=new testXML();
$test->parseXML();
