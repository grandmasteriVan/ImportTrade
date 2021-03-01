<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * Gufo
 */
class Gufo_v2
{
    private function readSiteFile()
    {
        $xml=file_get_contents('index.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    private function readOffersFile()
    {
        $xml=file_get_contents('offers.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    private function readImportFile()
    {
        $xml=file_get_contents('import.xml');
        //$xml=file_get_contents('test.xml');
        return $xml;
    }

    private function getItemCode($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $itemCode=$matches[1];
        return $itemCode;
    }

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
            echo ($import);
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

    

    public function test()
    {
        //выгрузка сайта - файл выгрузки
        //импорт - файл сот всеми товарами и их размерами с параметрами
        //офферы - файл где гранятся только те товары, которые естиь в наличии и их цена
        $siteXML=$this->readSiteFile();
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
                $itemCode=$this->getItemCode($item);
                $params=$this->getParams($item);
                if (is_array($params))
                {
                    //echo "<pre>";print_r($params);echo "</pre>";
                    $sex=null;$season=null;$type=null;
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
                                                    $color=$this->getCharVal($lining);
                                                }
                                            }
                                        }
                                        $offers[]=array('barcode'=>$offerBarCode,'quantity'=>$quantity, 'material'=>$material,'lining'=>$lining,'age'=>$age,'size'=>$size,'height'=>$height,'color'=>$color,'sex'=>$sex,'season'=>$season,'type'=>$type);
                                        //$characteristics=
                                        
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
                echo "item code=".$itemCode."<br>";
                echo "<pre>";print_r($offers);echo "</pre>";
                break; //по айтемам
            }
        }
        else
        {
            echo "No item arr<br>";
        }
    }
}

echo "Start ".date("Y-m-d H:i:s")."<br>";
$test=new Gufo_v2();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
