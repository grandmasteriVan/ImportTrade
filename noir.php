<?php
header('Content-Type: text/html; charset=utf-8');

class Noir
{
    private function readFile($fileName)
    {
        if (($handle = fopen("$fileName", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {
                //$num = count($data);
                //$
                $canon=$data[4];
                $quantity=$data[5];
                $model=$data[0];
                $url=$data[8];
                //echo "$url - $ean<br>";
                //echo "<pre>";print_r($data);echo"</pre>";
                $newArr[]=array('canon'=>$canon,'quantity'=>$quantity,'model'=>$model,'url'=>$url);
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

    private function del_from_array($needle, &$array, $all = true){
        if(!$all){
            if(FALSE !== $key = array_search($needle,$array)) unset($array[$key]);
            return;
        }
        foreach(array_keys($array,$needle) as $key){
            unset($array[$key]);
        }
}

    public function test()
    {
        $products=$this->readFile('products2.csv');
        //находим группы товаров
        foreach ($products as $product)
        {
            $canon=$product['canon'];
            $group=null;
            $doneCanonical[]='EEE';
            if (!in_array($canon,$doneCanonical))
            {
                foreach ($products as $tmp)
                {
                    if ($tmp['canon']==$canon)
                    {
                        $group[]=$tmp;
                    }
                }
            }
            
            $doneCanonical[]=$canon;
            //массив групп товаров под ордним каноникалом
            if (!is_null($group))
            {
                $groups[]=$group;
            }
            
            //echo "group<br>";
            //echo "<pre>";print_r($group);echo"</pre>";
            //break;
        }
        //$groups=array_unique($groups);
        //echo"<pre>";print_r($groups);echo"</pre>";

        //отсекаем группы, в которых нет всех товаров
        foreach ($groups as $group)
        {
            $quantityArr=null;
            $q=null;
            foreach ($group as $item)
            {
                $quantity=$item['quantity'];
                $quantityArr[]=$quantity;
          
            }
            $this->del_from_array(0,$quantityArr);
            //echo count($quantityArr)."<br>";
            //echo"<pre>";print_r($quantityArr);echo"</pre>";
            if (count($quantityArr)>0)
            {
                $newGroups[]=$group;
            }
            
        }
        //вот тут список групп, в которых есть хоть одна позиция
        echo"<pre>";print_r($newGroups);echo"</pre>";
        //выводим урл первой ненулевой позиции в группе
        foreach ($newGroups as $group)
        {
            foreach ($group as $item)
            {
                $quantity=$item['quantity'];
                //echo $quantity."<br>";
                if ($quantity!=0)
                {
                    echo "https://sexgood.com.ua/".$item['url']."/<br>";
                    break;
                }
                
            }
        }
    }
}

$test=new Noir();
$test->test();