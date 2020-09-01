<?php
header('Content-Type: text/html; charset=utf-8');

class Hotline
{
    private function readFile()
    {
        $xml=file_get_contents('hotline_ua-v1.xml');
        return $xml;
    }

    private function stripHead($txt)
    {
        //var_dump ($txt);
        $pos=strpos($txt,"<items>");
        //var_dump ($pos);
        $new_txt=substr($txt,$pos);
        $new_txt=str_ireplace("<items>","",$new_txt);
        $new_txt=str_ireplace("</items>","",$new_txt);
        return $new_txt;
    }

    private function getItemsArr ($txt)
    {
        $arr=explode("</item>",$txt);
        foreach ($arr as $pos)
        {
            $arr1[]=$pos."</item>";
        }
        array_pop($arr1);
        return $arr1;
    }

    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
    }

    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getVendor($item)
    {
        preg_match("#<vendor>(.*?)<\/vendor>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function stripName ($name, $vendor)
    {
        $name_new=str_ireplace($vendor,"",$name);
        $name_new=$vendor." ".$name_new;
        //можно сделатьб так. Во многих товарах у нас есть куча лишнего в описаннию. Но тут есть ньюанс - у нас есть позиции, где модель не указана в названии. Такие позиции как раз не распознаются
        //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)]/ui","",$name_new);
        $name_new=str_replace("quot","",$name_new);
        
        
        return $name_new;
    }

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    private function getItemId($item)
    {
        preg_match("#<code>(.*?)<\/code>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getParams($item)
    {
        if (preg_match_all("#<param name(.*?)<\/param>#",$item,$matches))
        {
            $params=$matches[0];
        }
        else
        {
            $id=$this->getItemId($item);
            echo "No params found for $id<br>";
        }
        return $params;
    }

    private function getParamName($param)
    {
        if (preg_match("#\"(.+?)\"#",$param,$matches))
        {
            $paramName=$matches[1];
        }
        return $paramName;
    }

    public function parseXML()
    {
        $xml=$this->readFile();
        $xmlHead=$this->getXMLhead($xml);
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        foreach ($items as $item)
        {
            $vendor=$this->getVendor($item);
            $itemName=$this->getItemName($item);
            //echo $itemName;
            $itemName=$this->stripName($itemName,$vendor);
            //echo " <b>-</b> $itemName<br>";
            $catId=$this->getCatId($item);

            $params=$this->getParams($item);
            $params_new=null;
            if ($catId==3048)
            {
                /*echo "<pre>";
                print_r($params);
                echo "</pre>";*/
                //break;
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    //обнуляем список новых параметров для каждого айтема
                    $param_new=null;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        $param_new=str_ireplace("Страна","Страна производства",$param);
                    }
                    if (strcmp($paramName,"Длина")==0)
                    {
                        $param_new=str_ireplace("Длина","Длина, см",$param);
                    }
                    if (strcmp($paramName,"Диаметр")==0)
                    {
                        $param_new=str_ireplace("Диаметр","Диаметр, см",$param);
                    }
                    if (strcmp($paramName,"Пульт")==0)
                    {
                        if (strcmp($param,"Встроенный"==0)||strcmp($param,"Дистанционный"==0)||strcmp($param,"Проводной"==0))
                        {
                            $param_new="<param name=\"Пульт ДУ\">+</param>";
                        }
                    }
                    if (strcmp($paramName,"Материал")==0)
                    {
                        $param_new=str_ireplace("Мед. силикон","Медицинский силикон",$param);
                        $param_new=str_ireplace("Поливинилхлорид (PVC, ПВХ)","ПВХ",$param_new);
                        $param_new=str_ireplace("TPR(термопластичная резина)","Термопластический эластомер (TPE)",$param_new);
                        $param_new=str_ireplace("Латекс (резина)","Латекс",$param_new);
                    }
                    if (strcmp($paramName,"Цвет")==0)
                    {
                        $param=str_ireplace(";",", ",$param);
                        $param_new=str_ireplace("Цвет","Доступные цвета",$param);
                    }
                    if (strcmp($paramName,"Особенности")==0)
                    {
                        if (strcmp($param,"Смарт игрушки"==0))
                        {
                            $param_new="<param name=\"Управление со смартфона\">+</param>";
                        }
                    }
                    if (strcmp($paramName,"Функции")==0)
                    {
                        if (strcmp($param,"С вибрацией"==0))
                        {
                            $param_new="<param name=\"Вибрация\">+</param>";
                        }
                    }
                    //для каждогго прараметра добавляем его в массив параметров
                    $params_new[]=$param_new;
                }
                $gsoptVibr=false;
                $clitVibr=false;
                $analVibr=false;
                $notSpecial=false;
                if (strripos($itemName,"Виброяйцо"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор-яйцо</param>";
                }
                if (strripos($itemName,"точки G"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор точки G</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"точки-G"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор точки G</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"зоны G"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор точки G</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"клитора"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор клитора</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"Клиторальный"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор клитора</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"Вакуумный"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор клитора</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"анальный"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор анальный</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"Анальная"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор анальный</param>";
                    $notSpecial=true;
                }               
                if (strripos($itemName,"простат"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор анальный</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"двойно"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор двойной</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"вибростимулятор для пар"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор двойной</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"трусиков"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор клитора</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"зайчик"))
                {
                    $params_new[]="<param name=\"Тип\">Rabbit-вибратор</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"кролик"))
                {
                    $params_new[]="<param name=\"Тип\">Rabbit-вибратор</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"микрофон"))
                {
                    $params_new[]="<param name=\"Тип\">Вибромассажер</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"Микрофон"))
                {
                    $params_new[]="<param name=\"Тип\">Вибромассажер</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"Вибромассажер"))
                {
                    $params_new[]="<param name=\"Тип\">Вибромассажер</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"присоске"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор на присоске</param>";
                    $notSpecial=true;
                }
                if (strripos($itemName,"Подарочный набор"))
                {
                    $params_new[]="<param name=\"Тип\">Набор вибраторов и товаров</param>";
                    $notSpecial=true;
                }

                
                if (strripos($itemName,"Вибро-шарик")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }
                if (strripos($itemName,"Вибропул")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }
                /*if (strripos($itemName,"Классический"))
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }*/
                if (strripos($itemName,"вибратор")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }
                if (strripos($itemName," вибрац")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }
                if (strripos($itemName,"Вибратор")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }
                if (strripos($itemName,"Фаллоимитатор")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }
                if (strripos($itemName,"Набор скульптора")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }
                if (strripos($itemName,"вибро")&&$notSpecial==false)
                {
                    $params_new[]="<param name=\"Тип\">Вибратор классический</param>";
                }               
                


                $params_new=array_unique($params_new);
                $tmp[]=null;
                $params_new=array_diff($params_new,$tmp);
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";

            }
        }
    }
}

$test=new Hotline;
$test->parseXML();
echo "Done";
