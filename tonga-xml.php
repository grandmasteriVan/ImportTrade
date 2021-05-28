<?php
header('Content-Type: text/html; charset=utf-8');

class Tonga
{
    private $pathOrig="29.xml";
    private $pathFull="full.xml";
    private $pathPharm="pharm.xml";
    private $pathUnderwear="underwear.xml";

    private function readFile()
    {
        $xml=file_get_contents($this->pathOrig);
        return $xml;
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
        /*else
        {
            $id=$this->getItemId($item);
            echo "No params found for $id<br>";
        }*/
        //var_dump ($params);
        return $params;
    }

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    private function setPrice($item)
    {
        $params=$this->getParams($item);
        $price="0";
        if (is_array($params))
        {
            foreach ($params as $param)
            {
                $paramName=$this->getParamName($param);
                if (strcmp($paramName,"РРЦ (грн)")==0)
                {
                    $price=$this->getParamVal($param);
                }
            }
        }
        $item=preg_replace("#<price>(.*?)</price>#s","<price>$price</price>",$item);
        $item=preg_replace("#<param name=\"РРЦ \(грн\)\">(.*?)<\/param>#s","",$item);
        return $item;
    }

    public function parseXML()
    {
        $xml=$this->readFile();
        $xmlHead=$this->getXMLhead($xml);
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                //var_dump ($item);
                $item=$this->setPrice($item);
                //полная выгрузка
                $allItems.=$item.PHP_EOL;
                $catId=$this->getCatId($item);
                if ($catId==1069||$catId==1007||$catId==1088||$catId==1089||$catId==1145||$catId==1146||$catId==11147||$catId==1090||$catId==1091||$catId==1092||$catId==1093||$catId==1094||$catId==1095||$catId==1055||$catId==1096||$catId==1097||$catId==1098||$catId==1099||$catId==1100||$catId==1008||$catId==1084||$catId==1085||$catId==1060)
                {
                    //аптека
                    $pharmItems.=$item.PHP_EOL;   
                }
                if ($catId==1062||$catId==1005||$catId==1128||$catId==1129||$catId==1130||$catId==1131||$catId==1132||$catId==1133||$catId==1134||$catId==1136||$catId==1137||$catId==1138||$catId==1139||$catId==1140||$catId==1141||$catId==1142||$catId==1143)
                {
                    //белье
                    $underwearItems.=$item.PHP_EOL;
                }
                //var_dump ($item);
                
                //break;
            }
            //echo "<pre>";print_r($underwearItems);echo "</pre>";
            $allItems=$xmlHead.PHP_EOL."</categories>".PHP_EOL."<offers>".$allItems."</offers>".PHP_EOL."</shop>".PHP_EOL."</yml_catalog>";
            file_put_contents($this->pathFull,$allItems);
            //пишем аптеку
            $pharmItems=$xmlHead.PHP_EOL."</categories>".PHP_EOL."<offers>".$pharmItems."</offers>".PHP_EOL."</shop>".PHP_EOL."</yml_catalog>";
            file_put_contents($this->pathPharm,$pharmItems);
            //пишем белье
            $underwearItems=$xmlHead.PHP_EOL."</categories>".PHP_EOL."<offers>".$underwearItems."</offers>".PHP_EOL."</shop>".PHP_EOL."</yml_catalog>";
            file_put_contents($this->pathUnderwear,$underwearItems);
        }
    }


}
echo "<b>Start</b> ".date("Y-m-d H:i:s")."<br>";
$test=new Tonga();
$test->parseXML();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
