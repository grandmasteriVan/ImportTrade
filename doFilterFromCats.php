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
                        //Анальные игрушки
                        if ($cat==286)
                        {
                            $catText.="Анальные хвостики;";
                            $catIds.="1266;";
                        }
                        if ($cat==207)
                        {
                            $catText.="Бижутерия анальная;";
                            $catIds.="1267;";
                        }
                        if ($cat==45)
                        {
                            $catText.="Бусы, шарики анальные;";
                            $catIds.="1268;";
                        }
                        if ($cat==18)
                        {
                            $catText.="Анальные вибраторы;";
                            $catIds.="1264;";
                        }
                        if ($cat==44)
                        {
                            $catText.="Массажеры простаты;";
                            $catIds.="1269;";
                        }
                        if ($cat==113)
                        {
                            $catText.="Наборы;";
                            $catIds.="1270;";
                        }
                        if ($cat==7)
                        {
                            $catText.="Пробки, плуги анальные;";
                            $catIds.="1271;";
                        }
                        if ($cat==266)
                        {
                            $catText.="Анальные души (клизмы);";
                            $catIds.="1265;";
                        }
                        //Анальные игрушки-
                        //Страпоны, фаллопротезы
                        if ($cat==291)
                        {
                            $catText.="Страпоны;";
                            $catIds.="1273;";
                        }
                        if ($cat==133)
                        {
                            $catText.="Фаллопротезы;";
                            $catIds.="1275;";
                        }
                        if ($cat==256)
                        {
                            $catText.="Трусики для страпона;";
                            $catIds.="1274;";
                        }
                        if ($cat==255)
                        {
                            $catText.="Насадки для страпонов;";
                            $catIds.="1272;";
                        }
                        //Страпоны, фаллопротезы-
                        //Насадки, кольца
                        if ($cat==126)
                        {
                            $catText.="Вибро-насадки;";
                            $catIds.="1276;";
                        }
                        if ($cat==147)
                        {
                            $catText.="Вибро-эрекционные кольца;";
                            $catIds.="1277;";
                        }
                        if ($cat==148)
                        {
                            $catText.="Насадки;";
                            $catIds.="1279;";
                        }
                        if ($cat==87)
                        {
                            $catText.="Удлиняющие;";
                            $catIds.="1280;";
                        }
                        if ($cat==88)
                        {
                            $catText.="Эрекционные кольца;";
                            $catIds.="1281;";
                        }
                        if ($cat==203)
                        {
                            $catText.="Комплектующие;";
                            $catIds.="1278;";
                        }
                        //Насадки, кольца-
                        //Помпы вакуумные
                        if ($cat==127)
                        {
                            $catText.="Мужские помпы;";
                            $catIds.="1283;";
                        }
                        if ($cat==128)
                        {
                            $catText.="Женские помпы;";
                            $catIds.="1282;";
                        }
                        //Помпы вакуумные-
                        //Фаллоимитаторы
                        if ($cat==275)
                        {
                            $catText.="Двойные фаллоимитаторы;";
                            $catIds.="1284;";
                        }
                        if ($cat==274)
                        {
                            $catText.="Реалистичные фаллоимитаторы;";
                            $catIds.="1285;";
                        }
                        if ($cat==276)
                        {
                            $catText.="Фаллоимитаторы на присоске;";
                            $catIds.="1286;";
                        }
                        //Фаллоимитаторы-
                        //Возбудители
                        if ($cat==59)
                        {
                            $catText.="Возбудители для двоих;";
                            $catIds.="1287;";
                        }
                        if ($cat==59)
                        {
                            $catText.="женские средства;";
                            $catIds.="1287;";
                        }
                        //Возбудители-
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
