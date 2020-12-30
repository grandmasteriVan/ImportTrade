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

    private function getPrice($item)
    {
        preg_match("#<price>(.*?)<\/price>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getOldPrice ($item)
    {
        preg_match("#<oldprice>(.*?)<\/oldprice>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function setPrice($item, $price, $oldPrice, $promo=null)
    {
        $item=preg_replace("#<price>(.*?)<\/price>#s","<price>$price</price>",$item);
        $item=preg_replace("#<oldprice>(.*?)<\/oldprice>#s","<oldprice>$oldPrice</oldprice>",$item);
        if (strripos($item,"<oldprice>")===false)
        {
            $item=str_ireplace("</price>","</price>".PHP_EOL."<oldprice>$oldPrice</oldprice>",$item);
        }
        /*if ($promo<$price)
        {
            $item=preg_replace("#<oldprice>(.*?)<\/oldprice>#s","<price_promo>$promo</price_promo>",$item);
        }*/
        return $item;
    }

    private function addDisc($items,$markup=1,$discount=1.1)
    {
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                $price=null;
                $oldPrice=null;
                $proimo=null;
                //достаем оригинальную цену товара
                $price=$this->getPrice($item);
                //достаем оригинальную старую цену товара (емли она есть - значит у нас была скидка)
                $oldPrice=$this->getOldPrice($item);
                //получаем новую цену товара - умножаем цену товара на наценку
                $price_new=round($price*$markup);
                //получаем виртуальную старую цену - умножаем полученную ранее виртуальлую цену на товар на скидку.
                $oldPriceNew=round($price_new*$discount, -1);
                //echo "$price-$oldPrice<br>$price_new-$oldPriceNew<br>";
                //break;
                //если раньтше скидки не было, то просто записывваем новые виртуальные цены в позицию
                //если старая цена была то
                if (!empty($oldPrice))
                {
                    //если старая цена больше виртуальной старой цены, то 
                    if ($oldPrice>$oldPriceNew)
                    {
                        $oldPriceNew=$oldPrice;
                    }
                    
                    $promo=$price;
                    //
                }
                //echo "promo=$promo<br>";
                //echo "Price=$price_new, oldPrice=$oldPriceNew, promo=$promo<br>";
            //break;
                if (!empty($price))
                {
                    $item=$this->setPrice($item,$price_new,$oldPriceNew,$promo);

                    //echo $item;
                }
                //break;
                $items_new.=$item.PHP_EOL;
            }
            
        }
        return $items_new;
    }

    public function test()
    {
        $xml=$this->readFile();
        $xmlhead=$this->getXMLhead($xml);
        $XMLnew=$this->stripHead($xml);
        //echo $XMLnew;
        $items=$this->getItemsArr($XMLnew);
        if (is_array($items))
        {
            //echo count($items);
            //Отфильтровываем товары по производителю
            /*
            foreach ($items as $item)
            {
                $vendor=$this->getItemVendor($item);
                //echo "vendor=$vendor<br>";
                if (strcmp($vendor,"Le Frivole")==0)
                {
                    $newItems.=$item;
                }
            }*/
            //добавляем виртуальную скидку
            $newItems=$this->addDisc($items);
            $newXml=$xmlhead.PHP_EOL."</categories>".PHP_EOL."<offers>".PHP_EOL.$newItems.PHP_EOL."</offers>".PHP_EOL."</shop>".PHP_EOL."</yml_catalog>";
            $newXml=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $newXml);
            file_put_contents("rozetka_disc.xml",$newXml);

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
