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
        //echo $query."<br>";
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
                            $catText.="Женские средства;";
                            $catIds.="1287;";
                        }
                        //Возбудители-
                        //Духи с феромонами
                        if ($cat==251)
                        {
                            $catText.="Духи для женщин с феромонами;";
                            $catIds.="1290;";
                        }
                        if ($cat==252)
                        {
                            $catText.="Духи для мужчин с феромонами;";
                            $catIds.="1291;";
                        }
                        if ($cat==253)
                        {
                            $catText.="Духи унисекс с феромонами;";
                            $catIds.="1292;";
                        }
                        //Духи с феромонами-
                        //Интимная гигиена
                        if ($cat==242)
                        {
                            $catText.="Body Art;";
                            $catIds.="1293;";
                        }
                        if ($cat==243)
                        {
                            $catText.="Антисептики, очистители;";
                            $catIds.="1294;";
                        }
                        if ($cat==244)
                        {
                            $catText.="Средства интимной гигиены;";
                            $catIds.="1295;";
                        }
                        //Интимная гигиена-
                        //Массажные масла, массажные свечи
                        if ($cat==293)
                        {
                            $catText.="Аксессуары для массажа;";
                            $catIds.="1436;";
                        }
                        if ($cat==236)
                        {
                            $catText.="Массажные масла;";
                            $catIds.="1296;";
                        }
                        if ($cat==237)
                        {
                            $catText.="Массажные свечи;";
                            $catIds.="1297;";
                        }
                        //Массажные масла, массажные свечи-
                        //Продление полового акта, пролонгаторы
                        if ($cat==130)
                        {
                            $catText.="Крема;";
                            $catIds.="1298;";
                        }
                        if ($cat==129)
                        {
                            $catText.="Спреи;";
                            $catIds.="1299;";
                        }
                        //Продление полового акта, пролонгаторы-
                        //Смазки, лубриканты
                        if ($cat==83)
                        {
                            $catText.="Анальные смазки лубриканты;";
                            $catIds.="1300;";
                        }
                        if ($cat==84)
                        {
                            $catText.="Вагинальные смазки лубриканты;";
                            $catIds.="1301;";
                        }
                        if ($cat==86)
                        {
                            $catText.="Возбуждающие смазки лубриканты;";
                            $catIds.="1302;";
                        }
                        if ($cat==85)
                        {
                            $catText.="Съедобные смазки лубриканты;";
                            $catIds.="1303;";
                        }
                        //Смазки, лубриканты-
                        //Наручники, бондаж
                        if ($cat==262)
                        {
                            $catText.="Бондажные наборы;";
                            $catIds.="1304;";
                        }
                        if ($cat==259)
                        {
                            $catText.="Веревки, ленты;";
                            $catIds.="1305;";
                        }
                        if ($cat==258)
                        {
                            $catText.="Наручники, поножи;";
                            $catIds.="1306;";
                        }
                        if ($cat==260)
                        {
                            $catText.="Распорки;";
                            $catIds.="1307;";
                        }
                        if ($cat==261)
                        {
                            $catText.="Фиксаторы БДСМ разные;";
                            $catIds.="1308;";
                        }
                        //Наручники, бондаж-
                        //Ударные девайсы
                        if ($cat==249)
                        {
                            $catText.="Кнуты;";
                            $catIds.="1309;";
                        }
                        if ($cat==214)
                        {
                            $catText.="Плетки;";
                            $catIds.="1310;";
                        }
                        if ($cat==217)
                        {
                            $catText.="Разное;";
                            $catIds.="1311;";
                        }
                        if ($cat==215)
                        {
                            $catText.="Стеки;";
                            $catIds.="1437;";
                        }
                        if ($cat==216)
                        {
                            $catText.="Шлепалки;";
                            $catIds.="1312;";
                        }
                        //Ударные девайсы-
                        //Женское секси белье
                        if ($cat==70)
                        {
                            $catText.="Аксессуары, наклейки на соски;";
                            $catIds.="1313;";
                        }
                        if ($cat==279)
                        {
                            $catText.="Боди эротические;";
                            $catIds.="1314;";
                        }
                        if ($cat==14)
                        {
                            $catText.="Большие королевские размеры;";
                            $catIds.="1315;";
                        }
                        if ($cat==228)
                        {
                            $catText.="Бюстгальтеры, топы;";
                            $catIds.="1316;";
                        }
                        if ($cat==161)
                        {
                            $catText.="Колготы эротические;";
                            $catIds.="1317;";
                        }
                        if ($cat==119)
                        {
                            $catText.="Комбинезоны;";
                            $catIds.="1318;";
                        }
                        if ($cat==153)
                        {
                            $catText.="Корсеты эротические;";
                            $catIds.="1319;";
                        }
                        if ($cat==153)
                        {
                            $catText.="Корсеты эротические;";
                            $catIds.="1319;";
                        }
                        if ($cat==65)
                        {
                            $catText.="Латекс, кожа, винил;";
                            $catIds.="1320;";
                        }
                        if ($cat==200)
                        {
                            $catText.="Парики;";
                            $catIds.="1321;";
                        }
                        if ($cat==62)
                        {
                            $catText.="Пеньюары, сорочки эротические;";
                            $catIds.="1322;";
                        }
                        if ($cat==283)
                        {
                            $catText.="Перчатки сексуальные;";
                            $catIds.="1323;";
                        }
                        if ($cat==263)
                        {
                            $catText.="Платья сексуальные;";
                            $catIds.="1324;";
                        }
                        if ($cat==219)
                        {
                            $catText.="Портупеи;";
                            $catIds.="1325;";
                        }
                        if ($cat==201)
                        {
                            $catText.="Пояса для чулок;";
                            $catIds.="1326;";
                        }
                        if ($cat==60)
                        {
                            $catText.="Секси комплекты;";
                            $catIds.="1327;";
                        }
                        if ($cat==68)
                        {
                            $catText.="Трусики секси, стринги;";
                            $catIds.="1328;";
                        }
                        if ($cat==188)
                        {
                            $catText.="Халатики секси;";
                            $catIds.="1329;";
                        }
                        if ($cat==188)
                        {
                            $catText.="Халатики секси;";
                            $catIds.="1329;";
                        }
                        if ($cat==69)
                        {
                            $catText.="Чулки секси;";
                            $catIds.="1330;";
                        }
                        if ($cat==246)
                        {
                            $catText.="Юбки, брюки секси;";
                            $catIds.="1331;";
                        }
                        //Женское секси белье-
                        //Чулки секси
                        if ($cat==281)
                        {
                            $catText.="Кружевные чулки;";
                            $catIds.="1332;";
                        }
                        if ($cat==282)
                        {
                            $catText.="Латексные и виниловые чулки;";
                            $catIds.="1333;";
                        }
                        if ($cat==280)
                        {
                            $catText.="Чулки в сетку;";
                            $catIds.="1334;";
                        }
                        //Чулки секси-
                        //Мужское сексуальное белье
                        if ($cat==71)
                        {
                            $catText.="Комплекты;";
                            $catIds.="1335;";
                        }
                        if ($cat==290)
                        {
                            $catText.="Мужские костюмы;";
                            $catIds.="1337;";
                        }
                        if ($cat==285)
                        {
                            $catText.="Футболки, майки;";
                            $catIds.="1339;";
                        }
                        if ($cat==140)
                        {
                            $catText.="Латекс;";
                            $catIds.="1336;";
                        }
                        if ($cat==72)
                        {
                            $catText.="Стринги, шорты;";
                            $catIds.="1338;";
                        }
                        //Мужское сексуальное белье-
                        //Костюмы для ролевых игр
                        if ($cat==138)
                        {
                            $catText.="Аксессуары к секси костюмам;";
                            $catIds.="1340;";
                        }
                        if ($cat==248)
                        {
                            $catText.="Стюардессы секси костюм;";
                            $catIds.="1350;";
                        }
                        if ($cat==94)
                        {
                            $catText.="Горничные секси костюм;";
                            $catIds.="1341;";
                        }
                        if ($cat==92)
                        {
                            $catText.="Медсестры секси костюм;";
                            $catIds.="1344;";
                        }
                        if ($cat==100)
                        {
                            $catText.="Школьница/Учительница секси костюм;";
                            $catIds.="1351;";
                        }
                        if ($cat==93)
                        {
                            $catText.="Полицейские секси костюмы;";
                            $catIds.="1346;";
                        }
                        if ($cat==189)
                        {
                            $catText.="Кошечка секси костюм;";
                            $catIds.="1343;";
                        }
                        if ($cat==101)
                        {
                            $catText.="Монашки секси костюм;";
                            $catIds.="1345;";
                        }
                        if ($cat==96)
                        {
                            $catText.="Зайчики плейбой секси костюм;";
                            $catIds.="1342;";
                        }
                        if ($cat==97)
                        {
                            $catText.="Сказочные герои;";
                            $catIds.="1348;";
                        }
                        if ($cat==95)
                        {
                            $catText.="Разные секси костюмы;";
                            $catIds.="1347;";
                        }
                        if ($cat==99)
                        {
                            $catText.="Снегурочки секси костюм;";
                            $catIds.="1349;";
                        }
                        //Костюмы для ролевых игр-
                        //Обувь для стрипа
                        if ($cat==27)
                        {
                            $catText.="Обувь;";
                            $catIds.="1352;";
                        }
                        //Обувь для стрипа-
                        //Секс игры
                        if ($cat==278)
                        {
                            $catText.="Эротические игры для двоих;";
                            $catIds.="1353;";
                        }
                        if ($cat==277)
                        {
                            $catText.="Эротические игры для компании;";
                            $catIds.="1354;";
                        }
                        //Секс игры-
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
