<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * Gufo
 */
class Gufo_v2
{    
    /**
     * sexArray
     * массив кодов товаров подходящих (и имеющих соответствующие фото) как для девочек, так и для мальчиков
     * @var array
     */
    private $sexArray=array("431373","10435453","200123","200118","200119","200115","10411171","20010101","200112","10411186","200103","200102","200120","10415481","10435480","10415468","10415465","200106","19414430","413205","19414478","1119015","19414465","19434481","19434446","18424416","1119010","115368","18434433","18434414","T3556","737737","424158","18421208","T3569","18414272","414324","424082","424379","T3639","T3555","T3532","T3550","18414432","413260","T3560","1119021","1119002","422153","413200","434365","10311350","T3205","19311324","18421209","19331323","19333380","19322296","MT032","231152","19112286","112207","10112308","235252","T3528","T3232","211170","209898","19332307","10112302","10211291","10221306","10111276","MT225","19311290","10421136","10333331","10323228","431248","423058","424094","19322242","737737","413305","19321292","19421224","10111299","10122296","10112305","19312306","19333293","19312329","19322168","19322249","19323263","T3201","19312203","18422291","T3192","19122295","10221294","19122299","10122320","10221352","10221337","10122336","10413073","10314115","10314136","10313163","20269","10314167","10311140","20224","10312171","T3657","10313166","233939","239999","250000","129191","19312191","18311193","T3211","10321119","T3196","10121077","91919","T3090","T2975","209696","10122052","MT062","T3212","T3085","10112002","10121048","T3210","T2726","10311128","208383","T3097","10111028","10112005","122222","312099","MT217","T3010","T3075","T3011","10112004","10331184","10321161","MT032","10111078","10421136","10324274","10332294","10313281","T3658","413052","19333113","T3660","T3668","19323130","T3661","T3665","T3667","T3655","T3663","T3669","423282","423185","333272","422083","412138","T3659","256262","255959","18414313","10111078","10111038","10111251","19411252","19411251","18311034","10121293","18322311","19311225","202020","10311308","18312253","10211267","111279","121229","211286","101313","213120","221262","10314364","10321358","19332313","10321332","10121295","10112275","10112277","211116","252336","T05710","211146","10211034","10212033","10211246","10211023","10211086","T03610","T09710","10221041","10122037","105959","T01910","10121063","10213012","10211035","T3268","T3506","T05310","T06710","T01310","T04110","T06910","T02110","T06510","T08010","218787","211055","211341","T15910","10221337","10221083","211275","223274","T06310","T05510","T07010","221616","214330","108989","222307","212225","214246","92828","222222","58383","219999","225050","213185","98989","10122336","220000","11077","10588","T07810","92222","222828","112020","211281","109191","736736","331238","10421037","10421023","10314075","10423016","20147","18423020","10314013","10421019","20134","414376","163030","434375","18424024","414163","414223","413387","18414037","18414058","18411046","18422047","19421160","19411193","411240","20707","423214","171313","18411019","411258","412041","19411346","411247","99393","18422016","170606","413220","18412003","111247","19323059","19112025","165252","19122067","414049","10111128","18313111","19121059","19411156","19312074","19313060","431131","10331009","112200","19322058","19332072","19321046","112152","95656","18424011","81515","18321022","19323032","KZ2080","813813","19322047","858585","19321096","18412355","312110","413226","414063","311072","19333006","170909-1","10313040","18421341","321041","169292","19332048","169191","311036","18414031","19221016","171010-1","KZ2070","19332027","KZ2033","19313010","KZ2030","19332071","MK191","168989","165555","18414358","KZ2079","10112129","KZ2173","18411018","152323","KZ1996","KZ2179","19321035","MK0222","KZ1963","MK0228","KZ2017","KZ1991","18411036","18413002","19312057","10321026","312131","18412053","19321015","122006","10111139","KZ1926","21717","10122170","122204","10121163","1414","10112148","10121244","10111160","10112127","10112126","10212186","19122056","10121155","51818","KZ1928","10221171","10221172","K09910","10212151","K00510","10211141","K16510","K14410","K16410","150202","10211143","10211190","10212215","10211131","K03110","89090","111248","223228","10132125","KZ2063","KZ1965","10231180","213160","10212123","10222184","10211133","221014","221299","K07110","211339","211184","K07510","K11610","K12010","83939","224020","213156","214088","K14710","K11510","54444","159393","K00210","K00310","188787","213041","K06010","87777","160505","86262","89696","83030","88989","87979","157777","160808-1","151212","818818","878878","82727","K11810","K08510","10212154","K08210","K11310","K08910","155353","10431220","10311405","10312404","10311413","18323286","18411068","10111403","18311270","10112390","10121117","19312372","10111395","10221392","10222406","TZ12210","10211411","TZ15610","19221341","TZ14210","TZ16010","10122169","TZ10210","10215384","19212320","10214397","TZ15710","TZ12710","196060","195656","321082","161818","56060-1","223196","211159","211255","10221399");
        
    /**
     * readSiteFile
     * читаем файл выгрузки, который формирует сайт
     * @return string - файл выгрузки сайта
     */
    private function readSiteFile()
    {
        $xml=file_get_contents('index.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }
    
    /**
     * readOffersFile
     * читаем файл офферов, котрый формиркет 1С (назввние, цена и количество)
     * @return string - файл офферов
     */
    private function readOffersFile()
    {
        $xml=file_get_contents('offers.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }
    
    /**
     * readImportFile
     * читаем файл импорта, котрый формиркет 1С (характеристики товаров)
     * @return void
     */
    private function readImportFile()
    {
        $xml=file_get_contents('import.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }
    
    /**
     * getItemCode
     * Получаем код товара
     * @param  mixed $item - товар
     * @return string - код товара
     */
    private function getItemCode($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $itemCode=$matches[1];
        return $itemCode;
    }
    
    /**
     * getBarCode
     * получаем штрихкод позиции
     * @param  mixed $item - позиция
     * @return string - штрихкод
     */
    private function getBarCode($item)
    {
        preg_match("#<Штрихкод>(.*?)<\/Штрихкод>#",$item,$matches);
        $barCode=$matches[1];
        return $barCode;
    }

    private function getArticleCode($item)
    {
        preg_match("#<Артикул>(.*?)<\/Артикул>#",$item,$matches);
        $articleCode=$matches[1];
        return $articleCode;
    }

    private function getQuantity($item)
    {
        preg_match("#<Количество>(.*?)<\/Количество>#",$item,$matches);
        $quantity=$matches[1];
        return $quantity;
    }

    private function stripSiteHead($txt)
    {
        //var_dump ($txt);
        $pos=strpos($txt,"<items>");
        //var_dump ($pos);
        $new_txt=substr($txt,$pos);
        $new_txt=str_ireplace("<items>","",$new_txt);
        $new_txt=str_ireplace("</items>","",$new_txt);
        return $new_txt;
    }

    private function stripOffersHead($txt)
    {
        //var_dump ($txt);
        $pos=strpos($txt,"<Предложения>");
        //var_dump ($pos);
        $new_txt=substr($txt,$pos);
        $new_txt=str_ireplace("<Предложения>","",$new_txt);
        $new_txt=str_ireplace("</Предложения>","",$new_txt);
        return $new_txt;
    }

    private function stripImportHead($txt)
    {
        //var_dump ($txt);
        $pos=strpos($txt,"<Товары>");
        //var_dump ($pos);
        $new_txt=substr($txt,$pos);
        $new_txt=str_ireplace("<Товары>","",$new_txt);
        $new_txt=str_ireplace("</Товары>","",$new_txt);
        return $new_txt;
    }

    private function getSiteItemsArr ($txt)
    {
        $arr=explode("</item>",$txt);
        foreach ($arr as $pos)
        {
            $arr1[]=$pos."</item>";
        }
        //последий элемент полученнного макссива всегда пуст, удаляем его
        array_pop($arr1);
        return $arr1;
    }

    private function getOffersItemsArr ($txt)
    {
        $arr=explode("</Предложение>",$txt);
        foreach ($arr as $pos)
        {
            $arr1[]=$pos."</Предложение>";
        }
        //последий элемент полученнного макссива всегда пуст, удаляем его
        array_pop($arr1);
        return $arr1;
    }

    private function getImportItemsArr ($txt)
    {
        $arr=explode("</Товар>",$txt);
        foreach ($arr as $pos)
        {
            $arr1[]=$pos."</Товар>";
        }
        //последий элемент полученнного макссива всегда пуст, удаляем его
        array_pop($arr1);
        return $arr1;
    }

    private function getCharactList($import)
    {
        if (preg_match_all("#<ХарактеристикаТовара>(.*?)<\/ХарактеристикаТовара>#s",$import,$matches))
        {
            //var_dump ($matches);
            //echo "<pre>";print_r($matches[1]);echo "</pre>";
            $params=$matches[1];
            /*foreach($matches as $param)
            {
                $params[]="<param name".$param[1]."</param>";
            }*/
        }
        else
        {
            //echo ($import);
            //$id=$this->getItemId($item);
            //echo "No params found for $id<br>";
        }
        //var_dump ($params);
        return $params;
    }

    private function getCharName($param)
    {
        if (preg_match("#<Наименование>(.+?)<\/Наименование>#",$param,$matches))
        {
            $paramName=$matches[1];
        }
        return $paramName;
    }

    private function getCharVal($param)
    {
        if (preg_match("#<Значение>(.+?)<\/Значение>#",$param,$matches))
        {
            $paramName=$matches[1];
        }
        return $paramName;
    }

    private function getParams($item)
    {
        //var_dump ($item);
        if (preg_match_all("#<param name(.*?)<\/param>#",$item,$matches))
        {
            //var_dump ($matches);
            $params=$matches[0];
            /*foreach($matches as $param)
            {
                $params[]="<param name".$param[1]."</param>";
            }*/
        }
        else
        {
            $id=$this->getItemId($item);
            echo "No params found for $id<br>";
        }
        //var_dump ($params);
        return $params;
    }

    /**
     * getParamName
     * получаем имя конкретного параметра
     * @param  mixed $param - параметр
     * @return void - имя параметра
     */
    private function getParamName($param)
    {
        if (preg_match("#\"(.+?)\"#",$param,$matches))
        {
            $paramName=$matches[1];
        }
        return $paramName;
    }

    /**
     * getParamVal
     * Получаем значение параметра
     * @param  mixed $param - параметр
     * @return void - значение параметра
     */
    private function getParamVal($param)
    {
        //var_dump ($param);
        if (preg_match("#>(.+?)<#",$param,$matches))
        {
            $paramVal=$matches[1];
        }
        return $paramVal;
    }

    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }

    private function getItemId($item)
    {
        preg_match("#<item id=\"(.*?)\"#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    private function getSeason($paramVal)
    {
        //echo $paramVal."<br>";
        $value=str_ireplace("/"," ",$paramVal);
        $value=str_ireplace("-"," ",$value);
        $value=str_ireplace("Все сезоны","",$value);
        $value=str_ireplace("< param>","",$value);
        $value=trim(preg_replace('/\s+/', ' ', $value));
        //$value=str_ireplace(" ","}",$value);
        //$value=ucwords($value);
        //Ставим первую букву сезона всегда заглавной
        $value=mb_convert_case($value, MB_CASE_TITLE, "UTF-8");
        //echo $value."<br>";
        $valueArr=explode(" ",$value);
        $countVal=array_count_values($valueArr);
        //var_dump($countVal);
        //echo "<pre>".print_r($countVal),"</pre>";
        $maxVal=0;
        foreach ($countVal as $key=>$value)
        {
            if($value>$maxVal)
            {
                $maxVal=$value;
            }
        }
        //echo "max=$maxVal<br>";
        $season=array_search($maxVal,$countVal);
        //echo "$season<br>";
        return $season;
    }

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    private function addKeyWords($item)
    {
        $cat=$this->getCatId($item);
        if ($cat!=2179&&$cat!=2180)
        {
            $key1="Детская одежда, Одежда для подростков, Детские вещи";
            $key2="Модная детская одежда, Брендовая детская одежда, Стильная одежда";
            $key3="Стильные ".$this->getType($item);
            $cat=$this->getCatId($item);
            $keywords="<keywords>$key1, $key2, $key3</keywords>";
            $item=str_ireplace("</item>",$keywords.PHP_EOL."</item>",$item);
        }    
        return $item;
    }

    private function getType($item)
    {
        $name=$this->getItemName($item);
        $type=explode(' ',trim($name))[0];
        return $type;
    }

    private function setDescr($item)
    {
        $name=$this->getItemName($item);
        $params=$this->getParams($item);
        if (is_array($params))
        {
            foreach($params as $param)
            {
                $parName=$this->getParamName($param);
                $paramVal=$this->getParamVal($param);
                $desc=$name." ".$parName." ".$paramVal.".";
                break;
            }
            $item=str_ireplace("<description></description>","<description>$desc</description>",$item);
            $item=str_ireplace("<description/>","<description>$desc</description>",$item);
            //echo "$desc<br>";
        }
        return $item;
    }
    
    
    /**
     * test
     * функция считывает 3 файла (файл выгрузки сайта и два файла, полученные из 1С), обьеденяет их в один, делает разновидности товара и обьеденяет иг в группы. Если товар есть в списке тех, кто подходит и мальчиками и девочкам - то создаются 2 копии по одной на каждый пол
     * @return void
     */
    public function test()
    {
        //выгрузка сайта - файл выгрузки
        //импорт - файл сот всеми товарами и их размерами с параметрами
        //офферы - файл где гранятся только те товары, которые естиь в наличии и их цена
        $siteXML=$this->readSiteFile();
        $XMLHead=$this->getXMLhead($siteXML);
        $siteXML=$this->stripSiteHead($siteXML);
        //var_dump($siteXML);
        $offersXML=$this->readOffersFile();
        $offersXML=$this->stripOffersHead($offersXML);
        $importXML=$this->readImportFile();
        $importXML=$this->stripImportHead($importXML);
        $itemArr=$this->getSiteItemsArr($siteXML);
        $offersArr=$this->getOffersItemsArr($offersXML);
        $importArr=$this->getImportItemsArr($importXML);
        //идея такая: идем по выгрузке сайта. Для каждого товара находми все его разновидности в офферах. Потом от туда по штрихкоду находим нужную информацию для каждого офера в импорте
        if (is_array($itemArr))
        {
            foreach ($itemArr as $item)
            {
                $itemHead=$this->getItemHead($item);
                $itemHead=preg_replace('/\s+/', ' ', $itemHead);
                $itemHead=preg_replace("#<description>(.*?)<\/description>#s","<description></description>",$itemHead);
                $itemCode=$this->getItemCode($item);
                $params=$this->getParams($item);
                if (is_array($params))
                {
                    //echo "<pre>";print_r($params);echo "</pre>";
                    $sex=null;$season=null;$type=null;$style=null;
                    foreach ($params as $param)
                    {
                        $paramName=$this->getParamName($param);
                        //echo "$paramName<br>";
                        if (strcmp($paramName,"Пол")==0)
                        {
                            $sex=$this->getParamVal($param);
                            //echo "$sex<br>";
                        }
                        if (strcmp($paramName,"Сезон")==0)
                        {
                            $season=$this->getParamVal($param);
                        }
                        if (strcmp($paramName,"Тип товара")==0)
                        {
                            $type=$this->getParamVal($param);
                        }
                        if (strcmp($paramName,"Стиль")==0)
                        {
                            $style=$this->getParamVal($param);
                        }
                    }
                }
                else 
                {
                    echo "No params<br>";
                }
                $offers=null;
                
                if (is_array($offersArr))
                {
                    foreach ($offersArr as $offer)
                    {
                        $offerArticle=$this->getArticleCode($offer);
                        if ($offerArticle==$itemCode)
                        {
                            $offerBarCode=$this->getBarCode($offer);
                            $quantity=$this->getQuantity($offer);
                            //$offers[]=array('barcode'=>$offerBarCode,'quantity'=>$quantity);
                            //$offers[]['barcode']=$offerBarCode;
                            //$offers[]['quantity']=$quantity;
                            if (is_array($importArr))
                            {
                                foreach ($importArr as $import)
                                {
                                    $importBarCode=$this->getBarCode($import);
                                    if ($offerBarCode==$importBarCode)
                                    {
                                        //echo "Yay<br>";
                                        $characteristics=$this->getCharactList($import);
                                        $color=null;$material=null;$size=null;$height=null;$age=null;$lining=null;
                                        //echo "<pre>";print_r($characteristics);echo "</pre>";
                                        if (is_array($characteristics))
                                        {
                                            foreach ($characteristics as $char)
                                            {
                                                $charName=$this->getCharName($char);
                                                //echo "name=$charName val=$charVal<br>";
                                                if (strcmp($charName,"Возраст")==0)
                                                {
                                                    $age=$this->getCharVal($char);
                                                }
                                                if (strcmp($charName,"Материал")==0)
                                                {
                                                    $material=$this->getCharVal($char);
                                                }
                                                if (strcmp($charName,"Размер")==0)
                                                {
                                                    $size=$this->getCharVal($char);
                                                }
                                                if (strcmp($charName,"Рост")==0)
                                                {
                                                    $height=$this->getCharVal($char);
                                                }
                                                if (strcmp($charName,"Цвет")==0)
                                                {
                                                    $color=$this->getCharVal($char);
                                                }
                                                if (strcmp($charName,"Подклад")==0)
                                                {
                                                    $lining=$this->getCharVal($lining);
                                                }
                                            }
                                        }
                                        $offers[]=array('barcode'=>$offerBarCode,'quantity'=>$quantity,'style'=>$style,'material'=>$material,'lining'=>$lining,'age'=>$age,'size'=>$size,'height'=>$height,'color'=>$color,'sex'=>$sex,'season'=>$season,'type'=>$type);
                                        //$characteristics=
                                        break;
                                    }
                                    
                                }
                            }
                            else
                            {
                                echo "no import arr<br>";
                            }
                            
                        }
                        

                    }
                }
                //echo "item code=".$itemCode."<br>";
                //echo "<pre>";print_r($offers);echo "</pre>";
                $new_offers[]=array ('code'=>$itemCode,'head'=>$itemHead,$offers);
                //echo "<pre>";print_r($new_offers);echo "</pre>";
                //break; //по айтемам
                
                //if (count ($new_offers)>2)
                //{
                //    break;
                //}
            }
        }
        else
        {
            echo "No item arr<br>";
        }
        //echo "<pre>";print_r($new_offers);echo "</pre>";
        echo count ($new_offers);
        $new_items=null;
        if (is_array($new_offers))
        {

            foreach ($new_offers as $offer)
            {
                $n=1;
                $head=$offer['head'];
                //echo "<pre>";print_r($offer);echo "</pre>";
                $positions=$offer[0];
                //break;
                if (is_array($positions))
                {
                    foreach ($positions as $position)
                    {
                        $params=null;
                        $id=$this->getItemId($head);
                        //echo "<pre>";print_r($position);echo "</pre>";
                        $new_item=$head."<country>Китай</country>";
                        if (count($positions)>1)
                        {
                            //$new_item=str_ireplace("<item id=\"$id\"","<item id=\"$id-$n\" group_id=\"$id\"",$new_item);
                            if (!is_null($position['size'])&&(strcmp($position['size'],"one size")!=0))
                            {
                                $size1=$position['size'];
                                $newId=$id.$size1;
                                //$new_item=str_ireplace("<item id=\"$id\"","<item id=\"$id-$size1\" group_id=\"$id\"",$new_item);
                                $new_item=str_ireplace("<item id=\"$id\"","<item id=\"$newId\" group_id=\"$id\"",$new_item);
                            }
                            else
                            {
                                //$new_item=str_ireplace("<item id=\"$id\"","<item id=\"$id-$n\" group_id=\"$id\"",$new_item);
                                $newId=$id.$n;
                                $new_item=str_ireplace("<item id=\"$id\"","<item id=\"$newId\" group_id=\"$id\"",$new_item);
                            }
                        
                        }
                        
                        $quantity=$position['quantity'];
                        $new_item=preg_replace("#<quantity_in_stock>(.*?)<\/quantity_in_stock>#s","<quantity_in_stock>$quantity</quantity_in_stock>",$new_item);
                        //вот тут у нас есть сформированное заглавие позиции
                        //var_dump($new_item);
                        //все параметры имеет только первый айтем группы. Остальные имеют только те параметры, по которым идет различие
                        if ($n==1)
                        {
                            if (!is_null($position['material']))
                            {
                                $material=$position['material'];
                                $params.="<param name=\"Материал\">$material</param>".PHP_EOL;
                            }
                            if (!is_null($position['lining']))
                            {
                                $lining=$position['lining'];
                                $params.="<param name=\"Подкладка\">$lining</param>".PHP_EOL;
                            }
                            if (!is_null($position['sex']))
                            {
                                $sex=$position['sex'];
                                $params.="<param name=\"Пол\">$sex</param>".PHP_EOL;
                            }
                            if (!is_null($position['season']))
                            {
                                $season=$position['season'];
                                $season=$this->getSeason($season);
                                $params.="<param name=\"Сезон\">$season</param>".PHP_EOL;
                            }
                            if (!is_null($position['type']))
                            {
                                $type=$position['type'];
                                $params.="<param name=\"Тип\">$type</param>".PHP_EOL;
                            }
                            if (!is_null($position['style']))
                            {
                                $style=$position['style'];
                                $params.="<param name=\"Стиль\">$style</param>".PHP_EOL;
                            }
                        }
                        if (!is_null($position['size']))
                        {
                            $size=$position['size'];
                            $params.="<param name=\"Размер\">$size</param>".PHP_EOL;
                        }
                        if (!is_null($position['color']))
                        {
                            $color=$position['color'];
                            $params.="<param name=\"Цвет\">$color</param>".PHP_EOL;
                        }
                        //echo "$params".PHP_EOL;
                        $new_item.=$params."</item>";
                        $new_items[]=$new_item;
                        $n++;
                        //break;
                    }
                }
                
                //break;
                //$offer=str_ireplace("<item id=\"$id\"","<item id=\"$id-$n\" group_id=\"$id\"",$item);
            }
        }
        //в new_items у нас лежит массив позиций с правильными параметрами и уже разбитых по разновидностям.
        //осталось каждую позицию проверить на вхождение в массив полов, и если она туда входит - создать ее копию дописав в ее названии Для девочек, а в оригинальной позиции - Для мальчиков.
        $newer_items=null;
        if (is_array($new_items))
        {
            foreach ($new_items as $item)
            {
                $itemCode=$this->getItemCode($item);
                //если вешь есть в списке тех, которые подхоодят и для мальчиков и для девочек - то делаем из одного айтема - два
                if (in_array($itemCode,$this->sexArray))
                {
                    $tmp=$this->makeDubbleItem($item);
                    $newer_items[]=$tmp[0];
                    $newer_items[]=$tmp[1];
                }
                else
                {
                    $newer_items[]=$item;
                }
            }
        }

        if (is_array($newer_items))
        {
            foreach ($newer_items as $item)
            {
                $item=$this->addKeyWords($item);
                $item=$this->setDescr($item);
                $xmlOut.=$item;
                //echo $xmlOut;
                //break;
            }
        }
        $newXML1=$XMLHead."</categories><items>".PHP_EOL.$xmlOut."</items></price>";
        $newXML1=preg_replace("# в стиле(.*?)<\/name>#","</name>",$newXML1);
        $newXML1=preg_replace("# в стиле(.*?)<\/description>#","</description>",$newXML1);
        $newXML1=str_ireplace("&","&amp;",$newXML1);
        file_put_contents("gufo_v2.xml",$newXML1);
    }

    private function makeDubbleItem($item)
    {
        $name=$this->getItemName($item);
        $id=$this->getItemId($item);
        $groupId=$this->getGroupId($item);
        $name_male="$name для мальчиков";
        $name_female="$name для девочек";
        $item_male=preg_replace("#<name>(.*?)<\/name>#","<name>$name_male</name>",$item);
        $item_male=preg_replace("#<param name=\"Пол\">(.*?)<\/param>#","<param name=\"Пол\">Мальчикам</param>",$item_male);
        //$item_male=str_ireplace("<item id=\"$id\"","<item id=\"$id-m\"",$item_male);
        //$item_male=str_ireplace("group_id=\"$groupId\"","group_id=\"$groupId-m\"",$item_male);
        $idM=$id."1";
        $item_male=str_ireplace("<item id=\"$id\"","<item id=\"$idM\"",$item_male);
        $idM=$id."1";
        $groupIdM=$groupId."1";
        $item_male=str_ireplace("group_id=\"$groupId\"","group_id=\"$groupIdM\"",$item_male);

        $item_female=preg_replace("#<name>(.*?)<\/name>#","<name>$name_female</name>",$item);
        $item_female=preg_replace("#<param name=\"Пол\">(.*?)<\/param>#","<param name=\"Пол\">Девочкам</param>",$item_female);
        //$item_female=str_ireplace("<item id=\"$id\"","<item id=\"$id-f\"",$item_female);
        //$item_female=str_ireplace("group_id=\"$groupId\"","group_id=\"$groupId-f\"",$item_female);
        $idF=$id."2";
        $item_female=str_ireplace("<item id=\"$id\"","<item id=\"$idF\"",$item_female);
        $groupIdF=$groupId."2";
        $item_female=str_ireplace("group_id=\"$groupId\"","group_id=\"$groupIdF\"",$item_female);

        return array($item_male,$item_female);

    }

    private function getGroupId($item)
    {
        preg_match("#group_id=\"(.*?)\"#",$item,$matches);
        $id=$matches[1];
        return $id;
    }


    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }
}

set_time_limit (30000);
echo "Start ".date("Y-m-d H:i:s")."<br>";
$test=new Gufo_v2();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
