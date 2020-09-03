<?php
header('Content-Type: text/html; charset=utf-8');

class FindRealistic
{
    private function readFile()
    {
        //$xml=file_get_contents('test.xml');
        $xml=file_get_contents('prom_ua.xml');
        //$this->baseXML=file_get_contents('prom_ua.xml');
        //var_dump ($xml);
        return $xml;
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
    private function getItemId($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $name=$matches[1];
        return $name;
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


    public function test()
    {
        $xml=$this->readFile();
        $xml=$this->stripHead($xml);
        $items=$this->getItemsArr($xml);
        foreach ($items as $item)
        {
            if (strripos($item,"реали")||strripos($item,"Реали"))
            {
                $id=$this->getItemId($item);
                echo "$id<br>";
                /*echo "<pre>";
                print_r ($item);
                echo "</pre>";*/
            }
        }
    }
}

$test = new FindRealistic();
$test->test();