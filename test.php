<?php
//header('Content-Type: text/html; charset=utf-8');
define ("host","localhost");
define ("user", "root");
define ("pass", "root");
define ("db", "test");

class test
{
    public function test1()
    {
        if (($handle = fopen("test.csv", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {
                $num = count($data);
                $url=$data[0];
                $url=explode("/",$url);
                $url=array_pop($url);
                $ean=$data[1];
                //echo "$url - $ean<br>";
                $newArr[]=array($url,$ean);
            }
            fclose($handle);
        }
        else
        {
            echo "No file<br>";
        }
        //echo "<pre>";print_r($newArr);echo"</pre>";
        $urlsTable=$this->getUrls();
        $idsTable=$this->getIDs();
        //var_dump($idsTable);
        //echo "<pre>";print_r($idsTable);echo"</pre>";
        foreach ($newArr as $arr)
        {
            $url1=$arr[0];
            $eanCan=$arr[1];
            //echo $eanCan."<br>";
            //echo "$url1<br>";
            foreach ($urlsTable as $arr)
            {
                $url2=$arr['keyword'];
                if (strcmp($url1,$url2)==0)
                {
                    $id=$arr['query'];
                    $id=str_ireplace("product_id=","",$id);
                    break;
                    //echo "$id<br>";
                }
                
            }
            $arrNew[]=array($id,$eanCan);

        }
        //echo "<pre>";print_r($arrNew);echo"</pre>";

        foreach ($arrNew as $arr)
        {
            $eanCan=$arr[1];
            //echo $eanCan."<br>";
            foreach ($idsTable as $table)
            {
                $id=$table['product_id'];
                $ean=$table['ean'];
                if ($eanCan==$ean)
                {
                    $id1=$id;
                }
            }
            $arrNew1[]=array($arr[0],$id1);
        }
        echo "<pre>";print_r($arrNew1);echo"</pre>";
        $db_connect=mysqli_connect(host,user,pass,db);
        foreach($arrNew1 as $arr)
        {
            $id=$arr[0];
            $idCan=$arr[1];
            $query="INSERT INTO canonical (id, idCanonical) VALUES ($id,$idCan)";
            mysqli_query($db_connect,$query);
        }


        
    }


    private function getUrls()
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $query="SELECT DISTINCT keyword, query FROM oc_url_alias";
        if ($res=mysqli_query($db_connect,$query))
        {
            //var_dump ($query);
            while ($row = mysqli_fetch_assoc($res))
            {
                $urls[]=$row;
            }
        }
        else
        {
            echo "error in SQL ddn $query<br>";
        }
        mysqli_close($db_connect);
        if (is_array($urls))
        {
            return $urls;
        }
        else
        {
            return null;
        }
    }

    private function getIDs()
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $query="SELECT DISTINCT ean, product_id FROM oc_product";
        if ($res=mysqli_query($db_connect,$query))
        {
            //var_dump ($query);
            while ($row = mysqli_fetch_assoc($res))
            {
                $ids[]=$row;
            }
        }
        else
        {
            echo "error in SQL ddn $query<br>";
        }
        mysqli_close($db_connect);
        if (is_array($ids))
        {
            return $ids;
        }
        else
        {
            return null;
        }
    }
}

$test=new test();
$test->test1();