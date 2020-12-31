<?php
header('Content-Type: text/html; charset=utf-8');

class RozetkaDiff
{
    private function readSiteFile()
    {
        $xml=file_get_contents('rozetkaua.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    private function readRozFile()
    {
        $xml=file_get_contents('rozetkaua_roz.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    private function getItemVendor($item)
    {
        preg_match("#<vendor>(.*?)<\/vendor>#",$item,$matches);
        $vendor=$matches[1];
        return $vendor;
    }

    private function getItemsArr($txt)
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

    private function getItemId($item)
    {
        preg_match("#<article>(.*?)<\/article>#",$item,$matches);
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

    public function test()
    {
        $site=$this->readSiteFile();
        $rozetka=$this->readRozFile();
        $site=$this->stripHead($site);
        $rozetka=$this->stripHead($rozetka);

        $site_arr=$this->getItemsArr($site);
        $roz_arr=$this->getItemsArr($rozetka);

        foreach ($roz_arr as $item)
        {
            $roz_id[]=$this->getItemId($item);
        }
        foreach ($site_arr as $item)
        {
            $site_id[]=$this->getItemId($item);
        }

        $diff=array_diff($site_id,$roz_id);
        //echo "<pre>"; print_r(array_diff($site_id,$roz_id));echo "</pre>";
        foreach ($site_arr as $item)
        {
            $item_id=$this->getItemId($item);
            foreach($diff as $id)
            {
                if ($item_id==$id)
                {
                    $diff_items.=$item.PHP_EOL;
                }
            }
        }
        echo $diff_items;

    }

}

$test=new RozetkaDiff();
$test->test();