<?php
header('Content-Type: text/html; charset=utf-8');

class Rozetka
{
    private function readFile()
    {
        $xml=file_get_contents('rozetkaua.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    private function getItemVendor($item)
    {
        preg_match("#<vendor>(.*?)<\/vendor>#",$item,$matches);
        $vendor=$matches[1];
        return $vendor;
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
        //var_dump($arr1);
        return $arr1;
    }

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }

    public function test()
    {
        $xml=$this->readFile();
        $xmlhead=$this->getXMLhead($xml);
        $XMLnew=$this->stripHead($xml);
        $items=$this->getItemsArr($XMLnew);
        if (is_array($items))
        {
            //echo count($items);
            foreach ($items as $item)
            {
                $vendor=$this->getItemVendor($item);
                //echo "vendor=$vendor<br>";
                if (strcmp($vendor,"Baci Lingerie")==0)
                {
                    $newItems.=$item;
                }
            }
            $newXml=$xmlhead.PHP_EOL."</categories>".PHP_EOL."<offers>".PHP_EOL.$newItems.PHP_EOL."</offers>".PHP_EOL."</shop>".PHP_EOL."</yml_catalog>";
            $newXml=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $newXml);
            file_put_contents("rozetka_new.xml",$newXml);

        }
        else
        {
            echo "no array<br>";
        }
    }
}

echo "Start ".date("Y-m-d H:i:s")."<br>";
$test=new Rozetka();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");