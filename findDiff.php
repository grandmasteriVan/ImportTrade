<?php
header('Content-Type: text/html; charset=utf-8');

class FindDiff
{
    private function readFile($fileName)
    {
        //$xml=file_get_contents('test.xml');
        $xml=file_get_contents("$fileName");
        //$this->baseXML=file_get_contents('prom_ua.xml');
        //var_dump ($xml);
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
    private function getItemId($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }
    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    public function test()
    {
        $myXML=$this->readFile('new_test.xml');
        $XML=$this->readFile('prom_ua.xml');
        $myXML=$this->stripHead($myXML);
        //var_dump ($xml_new); echo "<br>";
        $itemsMy=$this->getItemsArr($myXML);
        $XML=$this->stripHead($XML);
        //var_dump ($xml_new); echo "<br>";
        
        $items=$this->getItemsArr($XML);
        foreach ($itemsMy as $item)
        {
            $id=$this->getItemId($item);
            $myIds[]=$id;
        }
        foreach ($items as $item)
        {
            $id=$this->getItemId($item);
            $Ids[]=$id;
        }
        echo "my id=".count($myIds)."<br>";
        echo "site id=".count($Ids)."<br>";
        $diff=array_diff($Ids,$myIds);
        echo "<pre>".print_r($diff)."</pre>";

    }
}
$test=new FindDiff();
$test->test();