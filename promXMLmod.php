<?php
header('Content-Type: text/html; charset=utf-8');

class ParseXML
{
    $baseXML;
    
    private function readFile()
    {
        $this->baseXML=file_get_contents("https://aaaa.in.ua/system/storage/download/prom_ua.xml");
    }

    /*private function stripCats($txt)
    {
        $new_txt=preg_replace("#</categories>(.*?)</categories>#","");
        return $new_txt;
    }
    */

    private function stripHead($txt)
    {
        $pos=strpos($txt,"<items>");
        $new_txt=substr($txt,$pos);
        $new_txt=str_ireplace("<items>","");
        $new_txt=str_ireplace("</items>","");
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

    private function getCatId($item)
    {
        if (preg_match("#</categoryId>(.*?)</categoryId>#",$item,$matches))
        {
            $id=$matches[1];
        }
        else
        {
            echo "No catId find for item:".$item."<br>";
            return null;
        }
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
        if (preg_match("",$param,$matches))
        {
            $paramVal=$matches[1];
        }
        return $paramVal;
    }

    private function getParams($item)
    {
        if (preg_match_all("#<param name(.*?)</param>#",$item,$matches))
        {
            foreach($matches as $param)
            {
                $params[]="<param name".$param."</param>";
            }
        }
        else
        {
            echo "No params found<br>";
        }
        return $params;
    }

    private function getItemHead($item)
    {

    }

    public function parseXML($xml)
    {
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        foreach($items as $item)
        {
            $catId=$this->getCatId($item);
            //это массив параметров айтема. Их мы как раз и будем менять, при чем как имя параметра, так и его значение
            $params=$this->getParams($item);
            //это айтем до параметров. Мы его трогать вообще никогда не будем
            $itemHead=$this->getItemHead($item);
            //пошли по доке по разделам
            if ($catId==169)
            {
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        $param_new=str_ireplace("Страна","Страна производитель",$param);
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
                    }
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $params_new[]="<param name=\"Возраст\">18+</param>";
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    $new_params.=$new_param.PHP_EOL;
                }
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.PHP_EOL.$new_params.PHP_EOL."</item>".PHP_EOL;
            }
        }
    }
    
}
