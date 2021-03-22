<?php
//header('Content-Type: text/html; charset=utf-8');
define ("host","localhost");
define ("user", "root");
define ("pass", "root");
define ("db", "test");

class doFilterFromCats
{
    private function getProductCats ($id)
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $query="SELECT category_id FROM oc_product_to_category WHERE product_id=$id";
        if ($res=mysqli_query($db_connect,$query))
        {
            //var_dump ($query);
            while ($row = mysqli_fetch_assoc($res))
            {
                $cats[]=$row;
            }
        }
        else
        {
            echo "error in SQL $query<br>";
        }
        mysqli_close($db_connect);
        if (is_array($cats))
        {
            return $cats;
        }
        else
        {
            return null;
        }
    }

    private function getProducts()
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $query="SELECT DISTINCT product_id FROM oc_product_to_category";
        if ($res=mysqli_query($db_connect,$query))
        {
            //var_dump ($query);
            while ($row = mysqli_fetch_assoc($res))
            {
                $products[]=$row;
            }
        }
        else
        {
            echo "error in SQL $query<br>";
        }
        mysqli_close($db_connect);
        if (is_array($products))
        {
            return $products;
        }
        else
        {
            return null;
        }
    }

    private function setAttr($id,$atrIds,$atrTxt)
    {
        $db_connect=mysqli_connect(host,user,pass,db);
        $query="INSERT INTO oc_product_attribute (product_id,attribute_id,language_id,text,value_id) VALUES ($id,94,1,'$atrTxt','$atrIds')";
        echo $query."<br>";
        mysqli_query($db_connect,$query);
        mysqli_close($db_connect);
    }

    public function test()
    {
        $products=$this->getProducts();
        //var_dump ($products);
        if (is_array($products))
        {
            foreach ($products as $product)
            {
                $id=$product['product_id'];
                $categories=$this->getProductCats($id);
                $catText="";
                $catIds="";
                if (is_array($categories))
                {
                    foreach ($categories as $category)
                    {
                        $cat=$category['category_id'];
                        //вибраторы
                        if ($cat==265)
                        {
                            $catText.="Cмарт вибраторы;";
                            $catIds.="1252;";
                        }
                        if ($cat==106)
                        {
                            $catText.="Вагинально-анальные;";
                            $catIds.="1250;";
                        }
                        if ($cat==235)
                        {
                            $catText.="Вибромассажеры (Микрофоны);";
                            $catIds.="1251;";
                        }
                        if ($cat==271)
                        {
                            $catText.="Большие вибраторы;";
                            $catIds.="1253;";
                        }
                        if ($cat==206)
                        {
                            $catText.="Вакуумные стимуляторы клитора;";
                            $catIds.="1254;";
                        }
                        if ($cat==190)
                        {
                            $catText.="Вибраторы для пар;";
                            $catIds.="1255;";
                        }
                        if ($cat==273)
                        {
                            $catText.="Вибраторы на присоске;";
                            $catIds.="1256;";
                        }
                        if ($cat==264)
                        {
                            $catText.="Вибраторы с пультом;";
                            $catIds.="1257;";
                        }
                        if ($cat==240)
                        {
                            $catText.="Вибраторы-кролики;";
                            $catIds.="1258;";
                        }
                        if ($cat==272)
                        {
                            $catText.="Виброяйца;";
                            $catIds.="1259;";
                        }
                        if ($cat==232)
                        {
                            $catText.="Жидкие вибраторы;";
                            $catIds.="1431;";
                        }
                        if ($cat==124)
                        {
                            $catText.="Вибраторы для клитора;";
                            $catIds.="1260;";
                        }
                        if ($cat==43)
                        {
                            $catText.="Реалистичные;";
                            $catIds.="1261;";
                        }
                        if ($cat==50)
                        {
                            $catText.="G точка;";
                            $catIds.="1262;";
                        }
                        if ($cat==250)
                        {
                            $catText.="Вибропули, мини-вибраторы;";
                            $catIds.="1263;";
                        }
                        //вибраторы-
                    }
                }
                /*if ($id==36624)
                {
                    echo "id=$id";
                    echo "<pre>";print_r($categories);echo"</pre>";
                    echo "cat text=$catText<br>";
                    echo "cat id=$catIds<br>";
                    $this->setAttr($id,$catIds,$catText);
                }*/
                if (strcmp($catText,"")!=0&&strcmp($catIds,"")!=0)
                {
                    $catText=rtrim($catText, ";");
                    $catIds=rtrim($catIds, ";");
                    $this->setAttr($id,$catIds,$catText);
                }
            }
        }
    }
}
set_time_limit (30000);
echo "Start ".date("Y-m-d H:i:s")."<br>";
$test=new doFilterFromCats();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");