<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * testXML
 */
class testXML
{
    private $pathOrig="prom_ua.xml";

    private function readFile()
    {
        //$xml=file_get_contents('test.xml');
        $xml=file_get_contents($this->pathOrig);
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

    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    private function getItemId($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getDescription($item)
    {
        preg_match("#<description>(.*?)<\/description>#",$item,$matches);
        $descr=$matches[1];
        return $descr;
    }

    private function hasDescr($item)
    {
        $desc=trim($this->getDescription($item));
                
        //$desc=str_replace('&lt;p&gt;&lt;br&gt;&lt;/p&gt;',"",$desc);
        $desc=html_entity_decode($desc);
        //$desc=strip_tags($desc);
        //echo $desc."<br>";
        $desc=str_replace("<p><br></p>","",$desc);
        if (empty($desc))
        {
            return false;
        }
        return true;
    }

    private function getCategoryId ($cat)
    {
        preg_match("#id=\"(.*?)\"#",$cat,$matches);
        $cat_id=$matches[1];
        return $cat_id;
    }
    
    private function getCategoryName ($cat)
    {
        preg_match("#>(.*?)<\/category>#",$cat,$matches);
        $cat_id=$matches[1];
        return $cat_id;
    }

    private function getCatArr($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        $xmlhead.="</categories>";
        //echo "$xmlhead<br>";
        preg_match('#<categories>(.+?)<\/categories>#is',$xmlhead,$matches);
        //echo "<pre>";print_r($matches);echo"</pre>";
        $cat_list=$matches[1];

        $arr=explode("</category>",$cat_list);
        
        foreach ($arr as $cat)
        {
            $cat_arr[]=$cat."</category>";
        }
        //echo "<pre>";print_r($cat_arr);echo"</pre>";
        if (is_array($cat_arr))
        {
            foreach ($cat_arr as $cat)
            {
                $cat_id=$this->getCategoryId($cat);
                $cat_name=$this->getCategoryName($cat);
                //echo "$cat<br>";
                //echo "$cat_id-$cat_name<br>";
                $arr[$cat_id]=$cat_name;
            }
        }

        return $arr;
    }

    public function test()
    {
        $xml=$this->readFile();
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        $cat_arr=$this->getCatArr($xml);
        foreach ($items as $item)
        {
            if (!$this->hasDescr)
            {
                $id=$this->getItemId($item);
                $cat_id=$this->getCatId($item);
                foreach ($cat_arr as $key => $value)
                {
                    if ($cat_id==$key)
                    {
                        echo $id.";$cat_id;$value<br>";
                    }
                }
                //echo $id.";$cat_id<br>";
            }
        }
    }

}

$test=new testXML();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");