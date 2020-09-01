<?php
header('Content-Type: text/html; charset=utf-8');

class Hotline
{
    private function readFile()
    {
        $xml=file_get_contents('hotline_ua-v1.xml');
        return $xml;
    }

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
        array_pop($arr1);
        return $arr1;
    }

    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getVendor($item)
    {
        preg_match("#<vendor>(.*?)<\/vendor>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function stripName ($name, $vendor)
    {
        $name_new=str_ireplace($vendor,"",$name);
        $name_new=$vendor." ".$name_new;
        //можно сделатьб так. Во многих товарах у нас есть куча лишнего в описаннию. Но тут есть ньюанс - у нас есть позиции, где модель не указана в названии. Такие позиции как раз не распознаются
        //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)]/ui","",$name_new);
        $name_new=str_replace("quot","",$name_new);
        
        
        return $name_new;
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
        $xmlHead=$this->getXMLhead($xml);
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        foreach ($items as $item)
        {
            $vendor=$this->getVendor($item);
            $itemName=$this->getItemName($item);
            echo $itemName;
            $itemName=$this->stripName($itemName,$vendor);
            echo " <b>-</b> $itemName<br>";
            $catId=$this->getCatId($item);
        }
    }
}

$test=new Hotline;
$test->parseXML();
echo "Done";