<?php
header('Content-Type: text/html; charset=utf-8');
define ("host","localhost");
define ("user", "root");
define ("pass", "root");
define ("db", "test");

class AddText
{
    private function readTextFile($fileName)
    {
        if (($handle = fopen("$fileName", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) 
            {
                //$num = count($data);
                //$
                $url=$data[0];
                $text=$data[1];
                $text=ltrim($text,'"');
                $text=rtrim($text,'"');
                             //echo "$url - $ean<br>";
                //echo "<pre>";print_r($data);echo"</pre>";
                $newArr[]=array('url'=>$url,'text'=>$text);
                //break;
            }
            fclose($handle);
        }
        else
        {
            echo "No file<br>";
        }
        //echo "<pre>";print_r($newArr);echo"</pre>";
        return $newArr;
    }

    private function readBaseFile($fileName)
    {
        if (($handle = fopen("$fileName", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) 
            {
                //$num = count($data);
                //$
                $id=$data[0];
                $url=$data[9];
                $canon=$data[3];
                $newArr[]=array('id'=>$id,'url'=>$url,'canon'=>$canon);
                //break;
            }
            fclose($handle);
        }
        else
        {
            echo "No file<br>";
        }
        //echo "<pre>";print_r($newArr);echo"</pre>";
        return $newArr;
    }

    private function getIdByUrl($url,&$urlTable)
    {
        $url=rtrim($url,"/");
        $url=str_replace("https://sexgood.com.ua/","",$url);
        if (is_array($urlTable))
        {
            foreach ($urlTable as $item)
            {
                if (strcmp($item['url'],$url)==0)
                {
                    return $item['id'];
                }
                
            }
        }

    }

    private function getItemsForCanonical($id,&$urlTable)
    {
        if (is_array($urlTable))
        {
            $ids=null;
            foreach ($urlTable as $item)
            {
                //сначала ищем айди каноникала
                if($item['id']==$id)
                {
                    //
                    $idCan=$item['canon'];
                    if (strcmp($idCan,"")!=0)
                    {
                        foreach ($urlTable as $tmp)
                        {
                            if ($idCan==$tmp['canon'])
                            {
                                $ids[]=$tmp['id'];
                            }
                        }
                    }
                    else
                    {
                        $ids[]=$id;
                    }
                    

                }
            }
            return $ids;
        }
    }

    private function addLinks($text)
    {
        if (preg_match_all("#одеж\w+#sui",$text,$matches)>0)
        //if (is_array($matches)/*&&count($matches)>1*/)
        {
            echo"<pre>";print_r($matches);echo"</pre>";
            foreach ($matches as $match)
            {
                echo $match[0]."<br>";
                $txt=str_ireplace ($match[0],"<a href=\"/eroticheskoe-bele/\" target=\"_blank\">$match[0]</a>",$text);
            }
            echo $txt."<br>";
        }
        if (preg_match_all("#боди#sui",$text,$matches)>0)
        //if (is_array($matches)/*&&count($matches)>1*/)
        {
            echo"<pre>";print_r($matches);echo"</pre>";
            foreach ($matches as $match)
            {
                echo $match[0]."<br>";
                $txt=str_ireplace ($match[0],"<a href=\"/eroticheskoe-bele/\" target=\"_blank\">$match[0]</a>",$text);
            }
            echo $txt."<br>";
        }

    }

    public function test()
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $texts=$this->readTextFile('Noir.csv');
        $baseFile=$this->readBaseFile('products-all.csv');
        if (is_array($texts))
        {
            foreach ($texts as $text)
            {
                $url=$text['url'];
                $txt=$text['text'];
                $txt=$this->addLinks($txt);
                $id=$this->getIdByUrl($url,$baseFile);
                //echo "$url = $id<br>";
                $group=$this->getItemsForCanonical($id,$baseFile);
                //echo "<pre>";print_r($group);echo"</pre>";
                if (is_array($group))
                {
                    foreach ($group as $tmp)
                    {
                        
                        $query="INSERT INTO texts (product_id,text) VALUES ($tmp,'$txt')";
                        //echo $query."<br>";
                        //mysqli_query($db_connect,$query);
                    
                    } 
                }
                else
                {
                    $query="INSERT INTO texts (product_id,text) VALUES ($id,'$txt')";
                    //mysqli_query($db_connect,$query);
                    //echo $query."<br>";
                }
                
                //break;
            }
        }
        mysqli_close($db_connect);
    }
}
$test=new AddText();
$test->test();

//https://sexgood.com.ua/product-plate-iz-vinila-noir-handmade-s-kapleobraznym-dekolte-chernoe-m-39373/	"Платье из винила с каплеобразным декольте поможет создать идеальный образ. Оно изящно и элегантно смотрится на женском теле. Изготовлено платье из полиэстера и эластана, а в дополнении идет лишь одна молния. 
//Важно использовать в сексуальных играх что-то новое и необычное, это может стать любое эротическое белье, платье, ролевой костюм. Эти товары можно купить у нас в секс-шопе, который обеспечит быструю и бесплатную доставку по Ивано-Франковску, Тернополю, Одессе и остальным городам Украины.
//"

//одежд[*] - https://sexgood.com.ua/eroticheskoe-bele/
//костюм - https://sexgood.com.ua/eroticheskoe-bele/kostyumy-dlya-rolevyh-igr/
//доставк[*] - https://sexgood.com.ua/delivery/
//бель - https://sexgood.com.ua/eroticheskoe-bele/
//боди - https://sexgood.com.ua/eroticheskoe-bele/zhenskoe-eroticheskoe-bele/bodi-eroticheskie/
//