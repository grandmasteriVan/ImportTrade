<?php
header('Content-Type: text/html; charset=utf-8');
//выбираем все товары, которые лежат в определенной категории.
//Выбираем все ИД товываров (для каждого уже заполненого товара находим его каноникал и формируем группу всех товаров под одним каноникалом)
//потом проверяем не входят ли товары из категори в группы уже обработаных и выводим список тех, которые не входят
//в итоге получеам список продуктов в категории, для которых еще нет описания.
class GetGoods
{
    private function readFile($fileName)
    {
        if (($handle = fopen("$fileName", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) 
            {
                //$num = count($data);
                //$
                $canon=$data[6];
                $quantity=$data[7];
                $id=$data[0];
                $cats=$data[1];
                $url=$data[10];
                $desc=$data[11];
                //echo "$url - $ean<br>";
                //echo "<pre>";print_r($data);echo"</pre>";
                $newArr[]=array('canon'=>$canon,'quantity'=>$quantity,'id'=>$id,'url'=>$url, 'cats'=>$cats, 'desc'=>$desc);
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

    private function getIdByURL($url, &$csv)
    {
        foreach ($csv as $item)
        {
            if (strcmp($url,$item['url'])==0)
            {
                return $item['id'];
            }
        }
    }

    private function getURLbyId($id, &$csv)
    {
        foreach ($csv as $item)
        {
            if ($id==$item['id'])
            {
                return $item['url'];
            }
        }
    }

    private function getCats($item)
    {
        
        $cat=str_ireplace('"',"",$item['cats']);
        //echo "$cat<br>";
        $cats=explode(",",$cat);
        //var_dump($cats);
        return $cats;
    }

    private function getBDSMgoods($items)
    {
        foreach ($items as $item)
        {
            //var_dump ($item);
            $cats=$this->getCats($item);
            //var_dump ($cats);
            //break;
            //бдсм
            //if (in_array("399",$cats)||in_array("416",$cats)||in_array("412",$cats)||in_array("406",$cats)||in_array("404",$cats)||in_array("441",$cats)||in_array("400",$cats)||in_array("401",$cats)||in_array("414",$cats)||in_array("415",$cats)||in_array("444",$cats)||in_array("445",$cats)||in_array("446",$cats)||in_array("403",$cats)||in_array("405",$cats)||in_array("442",$cats)||in_array("407",$cats)||in_array("409",$cats)||in_array("408",$cats)||in_array("447",$cats)||in_array("410",$cats)||in_array("411",$cats)||in_array("443",$cats))
            //вибраторы
            if (in_array("315",$cats)||in_array("318",$cats)||in_array("328",$cats)||in_array("329",$cats)||in_array("327",$cats)||in_array("316",$cats)||in_array("319",$cats)||in_array("324",$cats)||in_array("312",$cats)||in_array("325",$cats)||in_array("320",$cats)||in_array("322",$cats)||in_array("317",$cats)||in_array("330",$cats)||in_array("326",$cats))
            {
               if ($item['quantity']>0)
               {
                //echo "Yay!<br>";
                $bdsmItems[]=$item;
                //break;
               } 
               
            }
        }
        return $bdsmItems;
    }

    private function readReadyFile($fileName)
    {
        if (($handle = fopen("$fileName", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) 
            {
                //$num = count($data);
                //$
                $url=$data[0];
                $url=str_ireplace("https://sexgood.com.ua/","",$url);
                $url=rtrim($url,"/");
                //echo "$url - $ean<br>";
                //echo "<pre>";print_r($data);echo"</pre>";
                $newArr[]=array('url'=>$url);
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

    private function addMissingCanon($items)
    {
        foreach ($items as $item)
        {
            if (strcmp($item['canon'],"")==0)
            {
                $item['canon']=$item['id'];
            }
            $newItems[]=$item;
        }
        return $newItems;
    }


    private function getAllOldIds(&$csv)
    {
        $urls=$this->readReadyFile('ready.csv');
        if (is_array($urls))
        {
            foreach ($urls as $url)
            {
                $id=$this->getIdByURL($url['url'],$csv);
                $tmp=$url['url'];
                //$canon=$url['canon'];
                //echo "canon=$canon<br>";
                foreach ($csv as $tmp)
                {
                    if ($tmp['canon']==$id)
                    {
                        $ids[]=$tmp['id'];
                    }
                }
                //echo "$id $tmp<br> ";
                //$ids[]=$id;
                //echo "<pre>";print_r($ids);echo"</pre>";
                //break;
            }
            //break;
        }
        echo "<pre>";print_r($ids);echo"</pre>";
        //$ids=array_pop($ids);
        //echo "<pre>";print_r($ids);echo"</pre>";
        //$ids=$this->
        return $ids;
    }

    public function test()
    {
        $items=$this->readFile('products-all_1.csv');
        $items=$this->addMissingCanon($items);
        //echo "<pre>";print_r($newItems);echo"</pre>";
        if (is_array($items))
        {
            //если надо выбрать только товары, которые лежат в поределенной категории
            $bdsmItems=$this->getBDSMgoods($items);
            //$bdsmItems=$items;

            echo count($bdsmItems)."<br>";
            //echo "<pre>";print_r($bdsmItems);echo"</pre>";
            //break;
            $readyId=$this->getAllOldIds($items);
            foreach ($bdsmItems as $bdsmItem)
            {
                $id=$bdsmItem['id'];
                //$url=
                echo "id=$id<br>";
                if (!in_array($id,$readyId)&&strcmp($bdsmItem['desc'],"")==0&&$bdsmItem['quantity']>0)
                {
                    $newBDSMitems[]=$bdsmItem;
                }
            }
            echo count($newBDSMitems)."<br>";
            foreach ($newBDSMitems as $item)
            {
                $canonsArr[]=$item['canon'];
            }
            $canonsArr=array_unique($canonsArr);
            echo "canons ".count($canonsArr)."<br>";

            foreach ($canonsArr as $canon)
            {
                $url=$this->getURLbyId($canon,$items);
                echo "https://sexgood.com.ua/$url/<br>";
            }
            /*
            foreach ($newBDSMitems as $newBDSMitem)
            {
                $url=$newBDSMitem['url'];
                echo "https://sexgood.com.ua/$url/<br>";
            }
            */

        }
    }

}

echo "Start ".date("Y-m-d H:i:s")."<br>";
$test=new GetGoods();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");