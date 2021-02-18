<?php
//header('Content-Type: text/html; charset=utf-8');
define ("host","localhost");
define ("user", "root");
define ("pass", "root");
define ("db", "test");

class test2
{
    function test()
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $query="SELECT DISTINCT product_id, ean, testuncle FROM oc_product1";
        if ($res=mysqli_query($db_connect,$query))
        {
            //var_dump ($query);
            while ($row = mysqli_fetch_assoc($res))
            {
                $arr[]=$row;
            }
        }
        //var_dump($arr);
        foreach ($arr as $elem)
        {
            $arr_uniq[]=$elem['testuncle'];
        }
        $arr_uniq=array_unique($arr_uniq);
        //echo "<pre>";print_r($arr_uniq);echo "</pre>";
        //$arr_uniq=array_unique($arr_uniq);
        foreach($arr_uniq as $uniq)
        {
            $uniq_ean=$uniq;
            $new_arr=null;
            foreach ($arr as $elem)
            {
                $id=$elem['product_id'];
                $ean=$elem['ean'];
                $testuncle=$elem['testuncle'];
                if ($testuncle==$uniq_ean&&$uniq_ean!=0)
                {
                    $new_arr[]=array('product_id'=>$id,'ean'=>$ean,'testuncle'=>$testuncle,'uniq'=>$uniq_ean);
                }
            
            }
            //echo "<pre>";print_r($new_arr);echo "</pre>";
            //break;
            $id=null;
            foreach ($new_arr as $tmp)
            {
                
                if ($tmp['testuncle']==$tmp['ean'])
                {
                    $id=$tmp['product_id'];
                }

            }
            //echo "id=$id<br>";
            foreach ($new_arr as $tmp)
            {
                $ean=$tmp['ean'];
                $testuncle=$tmp['testuncle'];
                $query="UPDATE oc_product1 SET testuncle=$id WHERE ean LIKE '$ean'";
                echo "$query;<br>";
                mysqli_query($db_connect,$query);
            }

        }
        //$new_arr содержит пары элементов
        //array_shift($)
        
        
        
        
        /*if (is_array ($arr))
        {
            foreach ($arr as $elem)
            {
                $id=$elem['product_id'];
                $ean=$elem['ean'];
                $query="UPDATE oc_product1 SET testuncle=$id WHERE product_id=$id AND ean=$ean";
                mysqli_query($db_connect,$query);
            }
        }*/
    }
}

$test=new test2();
$test->test();