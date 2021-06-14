<?php
header('Content-Type: text/html; charset=utf-8');

class Tonga
{
    private $pathOrig="29.xml";

    private $pathFull="full.xml";
    private $pathPharm="pharm.xml";
    private $pathUnderwear="underwear.xml";

    private $pathFullCSV="full.csv";
    private $pathPharmCSV="pharm.csv";
    private $pathUnderwearCSV="underwear.csv";

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

    private function getItemArticle($item)
    {
        if (preg_match_all("#<vendorCode>(.+?)<\/vendorCode>#",$item,$matches))
        {
            $article=$matches[1][0];
        }
        return $article;
    }

    private function getItemName($item)
    {
        if (preg_match_all("#<name>(.+?)<\/name>#",$item,$matches))
        {
            $name=$matches[1][0];
        }
        $name=str_ireplace("<![CDATA[","",$name);
        $name=str_ireplace("]]>","",$name);
        return $name;
    }

    private function getItemDescription($item)
    {
        if (preg_match_all("#<description>(.+?)<\/description>#",$item,$matches))
        {
            $desc=$matches[1][0];
        }
        $desc=str_ireplace("<![CDATA[","",$desc);
        $desc=str_ireplace("]]>","",$desc);
        $desc=strip_tags($desc);
        return $desc;
    }

    private function getItemVendor($item)
    {
        if (preg_match_all("#<vendor>(.+?)<\/vendor>#",$item,$matches))
        {
            $vendor=$matches[1][0];
        }
        return $vendor;
    }

    private function getItemPrice($item)
    {
        if (preg_match_all("#<price>(.+?)<\/price>#",$item,$matches))
        {
            $price=$matches[1][0];
        }
        //echo "<pre>";print_r($matches);echo "</pre>";
        //echo "<br>price=".$price."<br>";
        return $price;
    }

    private function getItemPictures($item)
    {
        /*if (preg_match("<vendor>(.+?)<\/vendor>",$param,$matches))
        {
            $vendor=$matches[1];
        }
        return $vendor;*/
        preg_match_all("#<picture>(.+?)<\/picture>#",$item,$matches);
        //echo "<pre>";print_r($matches);echo "</pre>";
        $pics=$matches[1];
        //echo "<pre>";print_r($pics);echo "</pre>";
        $pictures="";
        if (is_array($pics))
        {
            foreach ($pics as $pic)
            {
                $pictures.=",$pic";
            }
        }
        $pictures=ltrim($pictures,",");
        //echo $pictures;
        return $pictures;
    }

    private function getId($cat)
    {
        preg_match("#category id=\"(.*?)\"#",$cat,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getParrentId($cat)
    {
        preg_match("#parentId=\"(.*?)\"#",$cat,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getName($cat)
    {
        preg_match("#\">(.*?)<\/category>#",$cat,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getCats($xml)
    {
        preg_match("#<categories>(.*?)</categories>#s",$xml,$matches);
        $cats=$matches[1];
        //echo "<pre>";print_r($cats);echo"</pre>";
        $arr=explode("</category>",$cats);
        //echo "<pre>";print_r($arr);echo"</pre>";
        array_pop($arr);
        foreach($arr as $cat)
        {
            $cat=$cat."</category>";
            $id=$this->getId($cat);
            $parrent=$this->getParrentId($cat);
            $name=$this->getName($cat);
            //echo "$id-$parrent-$name<br>";
            $catArr[]=array('id'=>$id,'parrent'=>$parrent,'name'=>$name);
        }
        //echo "<pre>";print_r($catArr);echo"</pre>";
        return $catArr;
    }

    private function catString($catId,$catArr)
    {
        
    }

    private function getCatString($catId,$catArr,$catStr)
    {
        //$catString="";
        if ($catId!=1068&&$catId!=1069&&$catId!=1062&&$catId!=1061&&$catId!=1150&&$catId!=1156)
        {
            foreach ($catArr as $cat)
            {
                if ($catId==$cat['id'])
                {
                    $name=$cat['name'];
                    //echo "$name;";
                    $catStr=$name."/".$catStr;
                    $catStr=$this->getCatString($cat['parrent'],$catArr,$catStr);
                    //echo "$name/";
                    //$catStr.=$name."/";
                }
            }
            //echo $catStr."<br>";
            
        }
        else 
        {
            foreach ($catArr as $cat)
            {
                if ($catId==$cat['id'])
                {
                    $name=$cat['name'];
                    //echo "$name<br>";
                    $catStr=$name."/".$catStr;
                    $catStr=substr($catStr,0,-1);
                    //echo $catStr."<br>";
                    //return $catStr;
                }
            }
            //echo $catStr."<br>";
            
        }
        //$catString=rtrim($catString,"/");
        //$catString=substr($catString,0,-1);
        //echo $catString;
        //return $catString;
        //echo "<br>";
        //echo $catStr;
        return $catStr;
    }

    private function getItemCat($item)
    {
        preg_match("#<categoryId>(.*?)</categoryId>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    public function makeCSV()
    {
        file_put_contents($this->pathFullCSV, '');
        file_put_contents($this->pathPharmCSV, '');
        file_put_contents($this->pathUnderwearCSV, '');
        $xml=$this->readFile();
        $categories=$this->getCats($xml);
        $xmlHead=$this->getXMLhead($xml);
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        
        $handle1=fopen($this->pathFullCSV, 'w+');
        $handle2=fopen($this->pathPharmCSV, 'w+');
        $handle3=fopen($this->pathUnderwearCSV, 'w+');
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                $item=$this->setPrice($item);
                //var_dump($item);
                
                $article=$this->getItemArticle($item);
                //echo "article=".$article." ";
                $name=$this->getItemName($item);
                $description=$this->getItemDescription($item);
                $vendor=$this->getItemVendor($item);
                $pictures=$this->getItemPictures($item);
                $price=$this->getItemPrice($item);
                $catId=$this->getItemCat($item);
                $catStr=$this->getCatString($catId,$categories,"");
                //echo $catStr."<br>";
                $arr=array($article,$name,$description,$catStr,$vendor,$pictures,$price);
                //echo "<pre>";print_r($arr);echo"</pre>";
                //пишем все 
                fputcsv($handle1, $arr);

                if ($catId==1069||$catId==1007||$catId==1088||$catId==1089||$catId==1145||$catId==1146||$catId==11147||$catId==1090||$catId==1091||$catId==1092||$catId==1093||$catId==1094||$catId==1095||$catId==1055||$catId==1096||$catId==1097||$catId==1098||$catId==1099||$catId==1100||$catId==1008||$catId==1084||$catId==1085||$catId==1060)
                {
                    //аптека
                    fputcsv($handle2, $arr);   
                }
                if ($catId==1062||$catId==1005||$catId==1128||$catId==1129||$catId==1130||$catId==1131||$catId==1132||$catId==1133||$catId==1134||$catId==1136||$catId==1137||$catId==1138||$catId==1139||$catId==1140||$catId==1141||$catId==1142||$catId==1143)
                {
                    //белье
                    fputcsv($handle3, $arr);
                }
                //break;
            }
        }
        fclose($handle1);
        fclose($handle2);
        fclose($handle3);
    }


}
echo "<b>Start</b> ".date("Y-m-d H:i:s")."<br>";
$test=new Tonga();
$test->parseXML();
$test->makeCSV();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
