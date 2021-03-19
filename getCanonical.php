<?php

define ("host","localhost");
define ("user", "root");
define ("pass", "root");
define ("db", "sex");

class Sex
{
    private function getTable()
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $query="SELECT product_id, model, upc FROM oc_product";
        if ($res=mysqli_query($db_connect,$query))
        {
            //var_dump ($query);
            while ($row = mysqli_fetch_assoc($res))
            {
                $table[]=$row;
            }
        }
        else
        {
            echo "error in SQL $query<br>";
        }
        mysqli_close($db_connect);
        return $table;
    }

    private function writeTable($id,$upc,$idCan)
    {
        if ($upc!=0&&!is_null($idCan))
        {
            $db_connect=mysqli_connect(host,user,pass,db);
            $query="UPDATE oc_product SET jan=$idCan WHERE product_id=$id";
            mysqli_query($db_connect,$query);
            //echo "$query<br>";
            mysqli_close($db_connect);
        }
        
    }

    public function test()
    {
        $products=$this->getTable();
        if (is_array($products))
        {
            foreach($products as $product)
            {
                $upc=$product['upc'];
                $id=$product['product_id'];
                $idCan=null;
                foreach ($products as $tmp)
                {
                    $model=$tmp['model'];
                    if ($upc==$model&&$upc!=0)
                    {
                        $idCan=$tmp['product_id'];
                        break;
                    }
                }
                //echo "id=$id upc=$upc jan=$idCan<br>";
                $this->writeTable($id,$upc,$idCan);
            }
        }
    }
}
echo "<b>Start</b> ".date("Y-m-d H:i:s")."<br>";
$test=new Sex();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");