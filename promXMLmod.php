<?php
//header('Content-Type: text/html; charset=utf-8');

class testXML
{
    //private $baseXML;
    
    private function readFile()
    {
        //$xml=file_get_contents('test.xml');
        $xml=file_get_contents('prom_ua.xml');
        //$this->baseXML=file_get_contents('prom_ua.xml');
        //var_dump ($xml);
        return $xml;
    }

    /*private function stripCats($txt)
    {
        $new_txt=preg_replace("#</categories>(.*?)</categories>#","");
        return $new_txt;
    }
    */

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
        return $arr1;
    }

    private function delSpaces($txt)
    {
        $new_txt=str_replace("> ",">",$txt);
        $new_txt=str_replace(" >",">",$new_txt);
        $new_txt=str_replace("  "," ",$new_txt);
        return $new_txt;
    }

    private function getCatId($item)
    {
        //var_dump ($item);
        /*if (preg_match("<categoryId>(.*?)<\/categoryId>",$item,$matches)==1)
        {
            $id=$matches[1];
        }
        else
        {
            echo "No catId find for item:".$item."<br>";
            return null;
        }*/
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

    private function getParamName($param)
    {
        if (preg_match("#\"(.+?)\"#",$param,$matches))
        {
            $paramName=$matches[1];
        }
        return $paramName;
    }

    private function getParamVal($param)
    {
        //var_dump ($param);
        if (preg_match("#>(.+?)<#",$param,$matches))
        {
            $paramVal=$matches[1];
        }
        return $paramVal;
    }

    private function getFirstParamVal($param)
    {
        if (preg_match("#>(.+?)<#",$param,$matches))
        {
            $paramVal=$matches[1];
        }
        $firstParamVal=explode("|",$paramVal);
        return $firstParamVal[0];
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

    private function getItemId($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    public function parseXML()
    {
        $xml=$this->readFile();
        //echo $this->$baseXML;
        //return null;
        //сохраняем начало ХМЛ
        $xmlHead=$this->getXMLhead($xml);
        $xml_new=$this->stripHead($xml);
        //var_dump ($xml_new); echo "<br>";
        $items=$this->getItemsArr($xml_new);
        //var_dump ($items);
        //return null;
        foreach($items as $item)
        {
            //обнуляем новую позицию перед созданием
            $new_item=null;
            $catId=$this->getCatId($item);
            //это массив параметров айтема. Их мы как раз и будем менять, при чем как имя параметра, так и его значение
            $params=$this->getParams($item);
            //var_dump ($params);
            //это айтем до параметров. Мы его трогать вообще никогда не будем
            $itemHead=$this->getItemHead($item);
            //пошли по доке по разделам
            if ($catId==169)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    $param=str_ireplace(";","|",$param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                    }
                    if (strcmp($paramName,"Тип")==0)
                    {
                        $param_new=str_ireplace("Тип","Тип средства",$param);
                        $param_new=str_ireplace("Гель, мазь","Гель",$param_new);
                        $param_new=str_ireplace("Крема","Крем",$param_new);
                    }
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $params_new[]="<param name=\"Возраст\">18+</param>";
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }
            
            if ($catId==160)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //идем по списку старых параметров
                if (is_array($params))
                {
                    foreach ($params as $param)
                    {
                        $paramName=$this->getParamName($param);
                        $paramVal=$this->getParamVal($param);
                        $param=str_ireplace(";","|",$param);
                        //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                        $param_new=$param;
                        if (strcmp($paramName,"Страна")==0)
                        {
                            //$param_new=str_ireplace("Страна","Страна производитель",$param);
                            //тут вообще надо параметр менять на <country>Страна_производитель</country>
                            $country=$paramVal;
                            $param_new=null;
                        }
                        if (strcmp($paramName,"Объем")==0)
                        {
                            $param_new=str_ireplace("Объем","Объем (мл)",$param);
                        }
                        if (strcmp($paramName,"Пол")==0)
                        {
                            $param_new=str_ireplace("Для женщин","Женский",$param);
                            $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                            $param_new=str_ireplace("Женский|Мужской","Унисекс",$param_new);
                            $param_new=str_ireplace("Мужской|Женский","Унисекс",$param_new);
                        }
                        $params_new[]=$param_new;
                    }
                }
                
                //а тут мы будем прописывать захардкодженные параметры
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }
            if ($catId==138||$catId==100||$catId==94||$catId==92||$catId==3)
            {
                //находим имя айтема для поиска тематики костюма
                $itemName=$this->getItemName($item);
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //идем по списку старых параметров
                if (is_array($params))
                {
                    foreach ($params as $param)
                    {
                        $paramName=$this->getParamName($param);
                        $paramVal=$this->getParamVal($param);
                        $param=str_ireplace(";","|",$param);
                        //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                        $param_new=$param;
                        if (strcmp($paramName,"Страна")==0)
                        {
                            //$param_new=str_ireplace("Страна","Страна производитель",$param);
                            //тут вообще надо параметр менять на <country>Страна_производитель</country>
                            $country=$paramVal;
                            $param_new=null;
                        }
                        if (strcmp($paramName,"Пол")==0)
                        {
                            $param_new=str_ireplace("Для женщин","Женский",$param);
                            $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                            $param_new=str_ireplace("Женский|Мужской","Унисекс",$param_new);
                            $param_new=str_ireplace("Мужской|Женский","Унисекс",$param_new);
                            $param_new=str_ireplace("Унисекс|Для пары","Унисекс",$param_new);
                        }

                        if (strcmp($paramName,"Цвет")==0)
                        {
                            //echo "вошли в цвет<br>$paramVal<br>";
                            $firstParamVal=$this->getFirstParamVal($param);
                            $param="<param name=\"Цвет\">".$firstParamVal."</param>";
                            $param_new=str_ireplace("Золотой","Золотистый",$param);
                            $param_new=str_ireplace("Салатовый","Зеленый",$param_new);
                        }
                        if (strcmp($paramName,"Материал")==0)
                        {
                            
                            $param_new=str_ireplace("Материал","Тип ткани",$param);
                            $firstParamVal=$this->getFirstParamVal($param);
                            $param_new="<param name=\"Тип ткани\">".$firstParamVal."</param>";
                            $param_new=str_ireplace("Кожзаменитель","Искусственная кожа",$param_new);
                        }
                        if (strcmp($paramName,"Размер")==0)
                        {
                            
                            $param_new=str_ireplace("Размер","Международный размер",$param);
                            $param_new=str_ireplace("One size","S/M/L",$param_new);
                            $param_new=str_ireplace("|","/",$param_new);
                        }
                        $params_new[]=$param_new;
                    }
                }
                
                //а тут мы будем прописывать захардкодженные параметры
                //вот тут надо из названия позиции вытащить тематику
                $itemName=" ".mb_strtolower($itemName);
                 
                if (strripos($itemName,"ангел"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Ангелы, Демоны</param>";
                }
                if (strripos($itemName,"дьявол"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Ангелы, Демоны</param>";
                }
                if (strripos($itemName,"черт"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Ангелы, Демоны</param>";
                }
                //Гангстеры, Заключенные, Кабаре
                if (strripos($itemName,"заключ"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Гангстеры, Заключенные, Кабаре</param>";
                }
                if (strripos($itemName,"заключ"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Гангстеры, Заключенные, Кабаре</param>";
                }
                if (strripos($itemName,"мафи"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Гангстеры, Заключенные, Кабаре</param>";
                }
                if (strripos($itemName,"танцовщ"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Гангстеры, Заключенные, Кабаре</param>";
                }
                if (strripos($itemName,"боди из чёрно белых полосок"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Гангстеры, Заключенные, Кабаре</param>";
                }
                //Горничные
                if (strripos($itemName,"горничн"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Горничные</param>";
                }
                if (strripos($itemName,"гуверн"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Горничные</param>";
                }
                if (strripos($itemName,"чистоты"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Горничные</param>";
                }
                if (strripos($itemName,"дворецк"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Горничные</param>";
                }
                if (strripos($itemName,"домраб"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Горничные</param>";
                }
                if (strripos($itemName,"служан"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Горничные</param>";
                }
                if (strripos($itemName,"домохоз"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Горничные</param>";
                }
                //Дикий Запад
                if (strripos($itemName,"ковбой"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Дикий Запад</param>";
                }
                //Другие образы
                if (strripos($itemName,"маска"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Другие образы</param>";
                }
                if (strripos($itemName,"укротительница"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Другие образы</param>";
                }

                //Животные
                if (strripos($itemName,"кошк"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                if (strripos($itemName,"кошеч"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                if (strripos($itemName,"леопар"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                if (strripos($itemName,"зайч"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                if (strripos($itemName,"зайк"))
                {                 
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                if (strripos($itemName,"плейбой"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                if (strripos($itemName,"крол"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                //Исторические
                if (strripos($itemName,"нила"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Животные</param>";
                }
                //Медперсонал
                if (strripos($itemName,"медсес"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Медперсонал</param>";
                }
                if (strripos($itemName,"доктор"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Медперсонал</param>";
                }
                //Монашки, Священники
                if (strripos($itemName,"монаш"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Монашки, Священники</param>";
                }
                //Морские образы
                if (strripos($itemName,"моря"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Морские образы</param>";
                }
                if (strripos($itemName,"морск"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Морские образы</param>";
                }
                //Национальные
                //Новый Год
                if (strripos($itemName,"новогод"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Новый Год</param>";
                }
                if (strripos($itemName,"снегур"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Новый Год</param>";
                }
                if (strripos($itemName,"сант"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Новый Год</param>";
                }
                //Пилоты, Стюардессы
                if (strripos($itemName,"стюард"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Пилоты, Стюардессы</param>";
                }
                if (strripos($itemName,"полет"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Пилоты, Стюардессы</param>";
                }
                if (strripos($itemName,"борт-пров"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Пилоты, Стюардессы</param>";
                }
                if (strripos($itemName,"пилот"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Пилоты, Стюардессы</param>";
                }
                //Пираты
                if (strripos($itemName,"пират"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Пираты</param>";
                }
                //Пожарные
                if (strripos($itemName,"пожар"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Пожарные</param>";
                }
                //Полиция, военные
                if (strripos($itemName,"полиц"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"policem"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"сержант"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"шериф"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"военн"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"камуфл"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"в бой"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"милитари"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                if (strripos($itemName,"гусар"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Полиция, военные</param>";
                }
                //Рабочая униформа
                if (strripos($itemName,"официан"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Рабочая униформа</param>";
                }
                if (strripos($itemName,"повар"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Рабочая униформа</param>";
                }
                if (strripos($itemName,"механик"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Рабочая униформа</param>";
                }
                if (strripos($itemName,"секретар"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Рабочая униформа</param>";
                }
                //Свадьба
                if (strripos($itemName,"невест"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Свадьба</param>";
                }
                //Сказочные герои, Киногерои
                if (strripos($itemName,"белосн"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Сказочные герои, Киногерои</param>";
                }
                if (strripos($itemName,"восточная"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Сказочные герои, Киногерои</param>";
                }
                if (strripos($itemName,"королев"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Сказочные герои, Киногерои</param>";
                }
                //Спорт
                if (strripos($itemName,"гонщиц"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Спорт</param>";
                }
                if (strripos($itemName,"гоночн"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Спорт</param>";
                }
                //Супергерои
                if (strripos($itemName,"супер"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Супергерои</param>";
                }
                if (strripos($itemName,"super"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Супергерои</param>";
                }
                if (strripos($itemName,"бетг"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Супергерои</param>";
                }
                if (strripos($itemName,"amazon"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Супергерои</param>";
                }
                //Хеллоуин
                //Школа
                if (strripos($itemName,"школьн"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Школа</param>";
                }
                if (strripos($itemName,"студент"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Школа</param>";
                }
                if (strripos($itemName,"учитель"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Школа</param>";
                }
                if (strripos($itemName,"учениц"))
                {
                    $params_new[]="<param name=\"Тематика костюма\">Школа</param>";
                }
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/

                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==83||$catId==5)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //находим имя айтема для поиска вкуса
                $itemName=$this->getItemName($item);
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param=str_ireplace(";","|",$param);
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                    }
                    if (strcmp($paramName,"Назначение")==0)
                    {
                        $param_new=str_ireplace("Вагинальная","Для вагинального секса",$param);
                        $param_new=str_ireplace("Оральная","Для орального секса",$param_new);
                        $param_new=str_ireplace("Анальная","Для анального секса",$param_new);
                        $param_new=str_ireplace("Для анального секса|Для вагинального секса","Универсальное",$param_new);
                        $param_new=str_ireplace("Для вагинального секса|Для орального секса","Для орального/вагинального секса",$param_new);
                    }
                    if (strcmp($paramName,"Основа")==0)
                    {
                        $param_new=str_ireplace("На водной","Водная",$param);
                        $param_new=str_ireplace("На масляной","Масляная",$param_new);
                    }
                    if (strcmp($paramName,"Свойства")==0)
                    {
                        $param_new=str_ireplace("Свойства","Дополнительный эффект",$param);
                        $param_new=str_ireplace("Возбуждающая, согревающая","Возбуждающий",$param_new);
                        $param_new=str_ireplace("Обезболивающая/Охлаждающая","Охлаждающий",$param_new);
                        //отсекаем лишние значения параметров. Иначе у нас не подтяется то, что точно должно подтянутся
                        /*$param_new=str_ireplace("Съедобный/ C ароматом","",$param_new);
                        if(strcmp (" ".$param_new,"<param name=\"Свойства\"></param>"))
                        {
                            $param_new="<param name=\"Свойства\">Съедобный/ C ароматом</param>";
                        }*/
                        

                    }
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $itemName=" ".mb_strtolower($itemName);
                 
                if (strripos($itemName,"арбуз"))
                {
                    $params_new[]="<param name=\"Вкус\">Арбуз</param>";
                }
                if (strripos($itemName,"банан"))
                {
                    $params_new[]="<param name=\"Вкус\">Банан</param>";
                }
                if (strripos($itemName,"ванил"))
                {
                    $params_new[]="<param name=\"Вкус\">Ваниль</param>";
                }
                if (strripos($itemName,"виш"))
                {
                    $params_new[]="<param name=\"Вкус\">Вишня</param>";
                }
                if (strripos($itemName,"гранат"))
                {
                    $params_new[]="<param name=\"Вкус\">Гранат</param>";
                }
                if (strripos($itemName,"pomegranate"))
                {
                    $params_new[]="<param name=\"Вкус\">Гранат</param>";
                }
                if (strripos($itemName,"дыня"))
                {
                    $params_new[]="<param name=\"Вкус\">Дыня</param>";
                }
                if (strripos($itemName,"жвачк"))
                {
                    $params_new[]="<param name=\"Вкус\">Жвачка</param>";
                }
                if (strripos($itemName,"карам"))
                {
                    $params_new[]="<param name=\"Вкус\">Карамель</param>";
                }
                if (strripos($itemName,"клубн"))
                {
                    $params_new[]="<param name=\"Вкус\">Клубника</param>";
                }
                if (strripos($itemName,"лимон"))
                {
                    $params_new[]="<param name=\"Вкус\">Лимон</param>";
                }
                if (strripos($itemName,"малин"))
                {
                    $params_new[]="<param name=\"Вкус\">Малина</param>";
                }
                if (strripos($itemName,"манго"))
                {
                    $params_new[]="<param name=\"Вкус\">Манго</param>";
                }
                if (strripos($itemName,"персик"))
                {
                    $params_new[]="<param name=\"Вкус\">Персик</param>";
                }
                if (strripos($itemName,"тропи"))
                {
                    $params_new[]="<param name=\"Вкус\">Тропический</param>";
                }
                if (strripos($itemName,"фрук"))
                {
                    $params_new[]="<param name=\"Вкус\">Фруктово-ягодный</param>";
                }
                if (strripos($itemName,"tropical"))
                {
                    $params_new[]="<param name=\"Вкус\">Фруктово-ягодный</param>";
                }
                if (strripos($itemName,"aperol"))
                {
                    $params_new[]="<param name=\"Вкус\">Цитрусовый</param>";
                }
                if (strripos($itemName,"шоколад"))
                {
                    $params_new[]="<param name=\"Вкус\">Шоколад</param>";
                }
                if (strripos($itemName,"ябло"))
                {
                    $params_new[]="<param name=\"Вкус\">Яблоко</param>";
                }

                if (strripos($itemName,"cupcake"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"сливк"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"брюле"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"булоч"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"понч"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"donut"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"суфл"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"ваты"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"вата"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"тирамис"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==55||$catId==54||$catId==28||$catId==19||$catId==11)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //находим имя айтема для поиска вкуса
                $itemName=$this->getItemName($item);
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param=str_ireplace(";","|",$param);
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Женский|Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской|Женский","Унисекс",$param_new);
                        $param_new=str_ireplace("Унисекс|Для пары","Унисекс",$param_new);
                        $param_new=str_ireplace("Унисекс|Унисекс","Унисекс",$param_new);
                    }
                    if (strcmp($paramName,"Тип")==0)
                    {
                        $param_new=str_ireplace("Тип","Форма выпуска",$param);
                        $param_new=str_ireplace("Гель, мазь","Гель",$param_new);
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                    }
                    /*if (strcmp($paramName,"Свойства")==0)
                    {
                        $param_new=str_ireplace("Свойства","Дополнительный эффект",$param);
                        $param_new=str_ireplace("Возбуждающая, согревающая","Возбуждающий",$param_new);
                        $param_new=str_ireplace("Обезболивающая/Охлаждающая","Охлаждающий",$param_new);
                        
                    }*/
                    if (strcmp($paramName,"Функции")==0)
                    {
                        $param_new=str_ireplace("Функции","Дополнительный эффект",$param);
                        $param_new=str_ireplace("Возбуждающие, стимулирующие","Возбуждающий",$param_new);
                        $param_new=str_ireplace("Продлевающие","Пролонгирующий эффект",$param_new);
                    }
                    
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $params_new[]="<param name=\"Количество в упаковке (шт.)\">1</param>";
                $params_new[]="<param name=\"Возраст\">18+</param>";
                
                $itemName=" ".mb_strtolower($itemName);
                 
                if (strripos($itemName,"арбуз"))
                {
                    $params_new[]="<param name=\"Вкус\">Арбуз</param>";
                }
                if (strripos($itemName,"банан"))
                {
                    $params_new[]="<param name=\"Вкус\">Банан</param>";
                }
                if (strripos($itemName,"ванил"))
                {
                    $params_new[]="<param name=\"Вкус\">Ваниль</param>";
                }
                if (strripos($itemName,"виш"))
                {
                    $params_new[]="<param name=\"Вкус\">Вишня</param>";
                }
                if (strripos($itemName,"гранат"))
                {
                    $params_new[]="<param name=\"Вкус\">Гранат</param>";
                }
                if (strripos($itemName,"pomegranate"))
                {
                    $params_new[]="<param name=\"Вкус\">Гранат</param>";
                }
                if (strripos($itemName,"дыня"))
                {
                    $params_new[]="<param name=\"Вкус\">Дыня</param>";
                }
                if (strripos($itemName,"жвачк"))
                {
                    $params_new[]="<param name=\"Вкус\">Жвачка</param>";
                }
                if (strripos($itemName,"карам"))
                {
                    $params_new[]="<param name=\"Вкус\">Карамель</param>";
                }
                if (strripos($itemName,"клубн"))
                {
                    $params_new[]="<param name=\"Вкус\">Клубника</param>";
                }
                if (strripos($itemName,"лимон"))
                {
                    $params_new[]="<param name=\"Вкус\">Лимон</param>";
                }
                if (strripos($itemName,"малин"))
                {
                    $params_new[]="<param name=\"Вкус\">Малина</param>";
                }
                if (strripos($itemName,"манго"))
                {
                    $params_new[]="<param name=\"Вкус\">Манго</param>";
                }
                if (strripos($itemName,"персик"))
                {
                    $params_new[]="<param name=\"Вкус\">Персик</param>";
                }
                if (strripos($itemName,"тропи"))
                {
                    $params_new[]="<param name=\"Вкус\">Тропический</param>";
                }
                if (strripos($itemName,"фрук"))
                {
                    $params_new[]="<param name=\"Вкус\">Фруктово-ягодный</param>";
                }
                if (strripos($itemName,"tropical"))
                {
                    $params_new[]="<param name=\"Вкус\">Фруктово-ягодный</param>";
                }
                if (strripos($itemName,"aperol"))
                {
                    $params_new[]="<param name=\"Вкус\">Цитрусовый</param>";
                }
                if (strripos($itemName,"шоколад"))
                {
                    $params_new[]="<param name=\"Вкус\">Шоколад</param>";
                }
                if (strripos($itemName,"ябло"))
                {
                    $params_new[]="<param name=\"Вкус\">Яблоко</param>";
                }

                if (strripos($itemName,"cupcake"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"сливк"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"брюле"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"булоч"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"понч"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"donut"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"суфл"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"ваты"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"вата"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                if (strripos($itemName,"тирамис"))
                {
                    $params_new[]="<param name=\"Вкус\">Десерт</param>";
                }
                
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==12)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $itemName=$this->getItemName($item);
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param=str_ireplace(";","|",$param);
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                        //echo "$paramName=$param_new<br>";
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Женский;Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской;Женский","Унисекс",$param_new);
                        $param_new=str_ireplace("Унисекс;Для пары","Унисекс",$param_new);
                    }
                    
                                    
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $itemName=" ".mb_strtolower($itemName);
                $params_new[]="<param name=\"Вид парфюмерной продукции\">Духи</param>"; 
                if (strripos($itemName,"50%"))
                {
                    $params_new[]="<param name=\"Процент содержания феромонов (%)\">50</param>";
                }
                
                
                
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==15)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $itemName=$this->getItemName($item);
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param=str_ireplace(";","|",$param);
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                        //echo "$paramName=$param_new<br>";
                    }
                    if (strcmp($paramName,"Свойства")==0)
                    {
                        if (strcmp($param,"Органические"==0))
                        {
                            $param_new="<param name=\"Классификация косметического средства\">Органическая</param>";
                        }
                    }
                    if (strcmp($paramName,"Тип")==0)
                    {
                        if (strcmp($param,"Масло для массажа"==0))
                        {
                            $param_new="<param name=\"Действие\">Массажное</param>";
                        }
                    }
                    
                    
                                    
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $itemName=" ".mb_strtolower($itemName);

                if (strripos($itemName,"массажн"))
                {
                    $params_new[]="<param name=\"Действие\">Массажное</param>";
                }
                
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==22)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $itemName=$this->getItemName($item);
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param=str_ireplace(";","|",$param);
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
 
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                
                
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==14||$catId==119||$catId==70||$catId==69||$catId==68||$catId==65||$catId==62||$catId==60||$catId==24)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $itemName=$this->getItemName($item);
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //кружева
                $hasLace=false;
                $hasRhinestone=false;
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $param=str_ireplace(";","|",$param);
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Вид")==0)
                    {
                        $param_new=str_ireplace("Вид","Тип трусов",$param);
                        $param_new=str_ireplace("С подтяжками|","",$param_new);

                    }
                    if (strcmp($paramName,"Цвет")==0)
                    {
                        //echo "вошли в цвет<br>$paramVal<br>";
                        $firstParamVal=$this->getFirstParamVal($param);
                        $param="<param name=\"Цвет\">".$firstParamVal."</param>";
                        $param_new=str_ireplace("Золотой","Золотистый",$param);
                        $param_new=str_ireplace("Салатовый","Зеленый",$param_new);
                        $param_new=str_ireplace("Мулат","Терракота",$param_new);
                        $param_new=str_ireplace("Леопардовый","Бежевый",$param_new);
                    }

                    if (strcmp($paramName,"Материал")==0)
                    {        
                        $param_new=str_ireplace("Материал","Тип ткани",$param);
                        //$firstParamVal=$this->getFirstParamVal($param);
                        $param_new="<param name=\"Тип ткани\">".$paramVal."</param>";
                        $param_new=str_ireplace("Кожзаменитель","Искусственная кожа",$param_new);
                        if (strripos($param,"Кружево"))
                        {
                            //echo "$param<br>";
                            $param_new=str_ireplace("Кружево|","",$param_new);
                            $param_new=str_ireplace("Кружево","",$param_new);
                            //$param_new.=PHP_EOL."<param name=\"Отделка и украшения\">Кружева</param>";
                            $hasLace=true;
                        }
                        if (strripos($param,"Камни, стразы"))
                        {
                            //echo "$param<br>";
                            $param_new=str_ireplace("Камни, стразы|","",$param_new);
                            $param_new=str_ireplace("Камни, стразы","",$param_new);
                            //$param_new.=PHP_EOL."<param name=\"Отделка и украшения\">Кружева</param>";
                            $hasRhinestone=true;
                        }
                    }
                    if (strcmp($paramName,"Размер")==0)
                    {
                        
                        $param_new=str_ireplace("Размер","Международный размер",$param);
                        $param_new=str_ireplace("One size","S/M/L",$param_new);
                        $param_new=str_ireplace("|","/",$param_new);
                        $param_new=str_ireplace("Plus Size/","",$param_new);
                    }
                    
 
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                
                //вид изделия
                $itemName=" ".mb_strtolower($itemName);
                 
                if (strripos($itemName,"штаны"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Брюки</param>";
                }
                if (strripos($itemName,"брюки"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Брюки</param>";
                }
                if (strripos($itemName,"бебидол"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Бэби-долл</param>";
                }
                if (strripos($itemName,"Беби долл"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Бэби-долл</param>";
                }
                if (strripos($itemName,"колгот"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Колготки</param>";
                }
                if (strripos($itemName,"боди "))
                {
                    $params_new[]="<param name=\"Вид изделия\">Комбидресс</param>";
                }
                if (strripos($itemName,"боди- "))
                {
                    $params_new[]="<param name=\"Вид изделия\">Комбидресс</param>";
                }
                if (strripos($itemName,"боди-сетка"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Комбидресс</param>";
                }
                if (strripos($itemName,"комбинация"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Комбинация</param>";
                }
                if (strripos($itemName,"комбинезон"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Комбинезон</param>";
                }
                if (strripos($itemName,"бодистокинг"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Комбинезон</param>";
                }
                if (strripos($itemName,"комплект"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Комплект белья</param>";
                }
                if (strripos($itemName,"корсаж"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Корсаж</param>";
                }
                if (strripos($itemName,"корсет"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Корсет</param>";
                }
                if (strripos($itemName,"пеньюар"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Пеньюар</param>";
                }
                if (strripos($itemName,"платье"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Платье</param>";
                }
                if (strripos($itemName,"портупея"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Портупея</param>";
                }
                if (strripos($itemName,"сорочка"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Сорочка</param>";
                }
                if (strripos($itemName,"ночнушка"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Сорочка</param>";
                }
                if (strripos($itemName,"трусики"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Трусики</param>";
                }
                if (strripos($itemName,"стринги"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Трусики</param>";
                }
                if (strripos($itemName,"шорты"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Трусики</param>";
                }
                if (strripos($itemName,"шортики"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Трусики</param>";
                }
                if (strripos($itemName,"халат"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Халат</param>";
                }
                if (strripos($itemName,"чулк"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Чулки</param>";
                }
                if (strripos($itemName,"чуло"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Чулки</param>";
                }
                if (strripos($itemName,"юбка"))
                {
                    $params_new[]="<param name=\"Вид изделия\">Юбка</param>";
                }

                //трусики в комплекте - по названию "комплект"
                if (strripos($itemName,"комплект"))
                {
                    $params_new[]="<param name=\"Трусики в комплекте\">Да</param>";
                }
                
                //отделка: тут хитро. У прома это мультиселект. У нас это или в названии, или в параметрах. Если мы нашли в параметрах - то это там и отлавливается и ставится соответствующий флаг. Дальше проверяем есть ли либо флаг либо упоминание в названии. Если есть - записываем значение параметра
                $finishing="";
                $finishingCount=0;
                if (strripos($itemName,"кружев")||$hasLace==true)
                {
                    //$params_new[]="<param name=\"Отделка и украшения\">Кружева</param>";
                    //$hasLace=true;
                    $finishing.="Кружева";
                    $finishingCount++;
                }
                if (strripos($itemName,"страз")||$hasRhinestone==true)
                {
                    //$params_new[]="<param name=\"Отделка и украшения\">Кружева</param>";
                    //$hasLace=true;
                    $finishing.="Стразы";
                    $finishingCount++;
                }
                if ($finishingCount>0)
                {
                    $finishing=str_ireplace("КружеваСтразы","Кружева|Стразы",$finishing);
                    $params_new[]="<param name=\"Отделка и украшения\">".$finishing."</param>";
                }
                //
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==72||$catId==25)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $itemName=$this->getItemName($item);
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $param=str_ireplace(";","|",$param);
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    
                    if (strcmp($paramName,"Цвет")==0)
                    {
                        //echo "вошли в цвет<br>$paramVal<br>";
                        $firstParamVal=$this->getFirstParamVal($param);
                        $param="<param name=\"Цвет\">".$firstParamVal."</param>";
                        $param_new=str_ireplace("Золотой","Золотистый",$param);
                        $param_new=str_ireplace("Салатовый","Зеленый",$param_new);
                        $param_new=str_ireplace("Мулат","Терракота",$param_new);
                        $param_new=str_ireplace("Леопардовый","Бежевый",$param_new);
                    }

                    if (strcmp($paramName,"Материал")==0)
                    {        
                        $param_new=str_ireplace("Материал","Тип ткани",$param);
                        //$firstParamVal=$this->getFirstParamVal($param);
                        $param_new="<param name=\"Тип ткани\">".$paramVal."</param>";
                        $param_new=str_ireplace("Кожзаменитель","Искусственная кожа",$param_new);
                        
                    }
                    if (strcmp($paramName,"Размер")==0)
                    {
                        
                        $param_new=str_ireplace("Размер","Международный размер",$param);
                        $param_new=str_ireplace("One size","S/M/L",$param_new);
                        $param_new=str_ireplace("|","/",$param_new);
                        $param_new=str_ireplace("Plus Size/","",$param_new);
                    }
                    
 
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                //модель-майка
                
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            //BDSM
            if ($catId==118||$catId==117||$catId==89||$catId==26)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $itemName=$this->getItemName($item);
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //кружева
                $hasLace=false;
                $hasRhinestone=false;
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $param=str_ireplace(";","|",$param);
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }

                    if (strcmp($paramName,"Материал")==0)
                    {        
                        $param_new=str_ireplace("Кожа Италия","Натуральная кожа",$param);
                        $param_new=str_ireplace("Кожзаменитель","Искусственная кожа",$param_new);
                        $param_new=str_ireplace("Хлопок","Хлопковая ткань",$param_new);
                        $param_new=str_ireplace("Хлопок","Хлопковая ткань",$param_new);
                        $param_new=str_replace("Кожа","Натуральная кожа",$param_new);
                        $param_new=str_ireplace("Поливинилхлорид (PVC, ПВХ)","ПВХ",$param_new);
                        $param_new=str_ireplace("Спандекс","Полиуретан",$param_new);
                        $param_new=str_replace("Натуральная кожа|Натуральная кожа","Натуральная кожа",$param_new);
                        if (strripos($param,"Кружево"))
                        {
                            //echo "$param<br>";
                            $param_new=str_ireplace("Кружево|","",$param_new);
                            $param_new=str_ireplace("Кружево","",$param_new);
                            //$param_new.=PHP_EOL."<param name=\"Отделка и украшения\">Кружева</param>";
                            $hasLace=true;
                        }
                        if (strripos($param,"Камни, стразы"))
                        {
                            //echo "$param<br>";
                            $param_new=str_ireplace("Камни, стразы|","",$param_new);
                            $param_new=str_ireplace("Камни, стразы","",$param_new);
                            //$param_new.=PHP_EOL."<param name=\"Отделка и украшения\">Кружева</param>";
                            $hasRhinestone=true;
                        }
                    }
                    if (strcmp($paramName,"Цвет")==0)
                    {
                        //echo "вошли в цвет<br>$paramVal<br>";
                        $firstParamVal=$this->getFirstParamVal($param);
                        $param="<param name=\"Цвет\">".$firstParamVal."</param>";
                        $param_new=str_ireplace("Золотой","Золотистый",$param);
                        $param_new=str_ireplace("Салатовый","Зеленый",$param_new);
                        $param_new=str_ireplace("Лиловый","Фиолетовый",$param_new);
                        $param_new=str_ireplace("Разные","Разные цвета",$param_new);
                        $param_new=str_ireplace("Леопардовый","Бежевый",$param_new);
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Для пары","Унисекс",$param_new);
                        $param_new=str_ireplace("Женский|Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской|Женский","Унисекс",$param_new);
                        $param_new=str_ireplace("Унисекс|Унисекс","Унисекс",$param_new);
                    }
                    
                    if (strcmp($paramName,"Размер")==0)
                    {
                        
                        $param_new=str_ireplace("Размер","Международный размер",$param);
                        $param_new=str_ireplace("One size","S/M/L",$param_new);
                        $param_new=str_ireplace("|","/",$param_new);
                        $param_new=str_ireplace("Plus Size/","",$param_new);
                    }
                    
 
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $itemName=" ".mb_strtolower($itemName);
                 
                if (strripos($itemName,"вибр"))
                {
                    $params_new[]="<param name=\"Функция вибрации\">Да</param>";
                }
                else
                {
                    $params_new[]="<param name=\"Функция вибрации\">Нет</param>";
                }
                
                //Тип интимной игрушки
                if (strripos($itemName,"зажимы на соски"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Зажимы для сосков</param>";
                }
                if (strripos($itemName,"зажимы для соск"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Зажимы для сосков</param>";
                }
                if (strripos($itemName,"кляп"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Кляп</param>";
                }
                if (strripos($itemName,"маск"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Маска</param>";
                }
                if (strripos($itemName,"набор"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Набор Госпожи</param>";
                }
                if (strripos($itemName,"наруч"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Наручники</param>";
                }
                if (strripos($itemName,"ошей"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Ошейник</param>";
                }
                if (strripos($itemName,"паддл")||strripos($itemName,"шлеп"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Паддл</param>";
                }
                if (strripos($itemName,"плет"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Плетка</param>";
                }
                if (strripos($itemName,"пояс верн"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Пояс верности</param>";
                }
                if (strripos($itemName,"стек"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Стек</param>";
                }
                if (strripos($itemName,"фикс"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Фиксатор</param>";
                }
                if (strripos($itemName,"флог"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Флоггер</param>";
                }
                if (strripos($itemName,"портуп"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Сбруи для тела</param>";
                }

                
                //отделка: тут хитро. У прома это мультиселект. У нас это или в названии, или в параметрах. Если мы нашли в параметрах - то это там и отлавливается и ставится соответствующий флаг. Дальше проверяем есть ли либо флаг либо упоминание в названии. Если есть - записываем значение параметра
                $finishing="";
                $finishingCount=0;
                if (strripos($itemName,"кружев")||$hasLace==true)
                {
                    //$params_new[]="<param name=\"Отделка и украшения\">Кружева</param>";
                    //$hasLace=true;
                    $finishing.="Кружева";
                    $finishingCount++;
                }
                if (strripos($itemName,"страз")||$hasRhinestone==true)
                {
                    //$params_new[]="<param name=\"Отделка и украшения\">Кружева</param>";
                    //$hasLace=true;
                    $finishing.="Стразы";
                    $finishingCount++;
                }
                if ($finishingCount>0)
                {
                    $finishing=str_ireplace("КружеваСтразы","Кружева|Стразы",$finishing);
                    $params_new[]="<param name=\"Отделка и украшения\">".$finishing."</param>";
                }

                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==35||$catId==30||$catId==27)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param=str_ireplace(";","|",$param);
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Для пары","Унисекс",$param_new);
                        $param_new=str_ireplace("Женский|Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской|Женский","Унисекс",$param_new);
                        $param_new=str_ireplace("Унисекс|Унисекс","Унисекс",$param_new);
                    }
 
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                
                
                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                /*
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                */
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            if ($catId==147||$catId==127||$catId==106||$catId==88||$catId==53||$catId==50||$catId==48||$catId==43||$catId==21||$catId==20||$catId==18||$catId==16||$catId==10||$catId==9||$catId==8||$catId==7||$catId==6||$catId==1||$catId==10)
            {
                //echo "нашли позицию с нужным ИД<br>";
                //обнуляем список новых параметров для каждого айтема
                $param_new=null;
                $params_new=null;
                $new_params=null;
                $country=null;
                $itemName=$this->getItemName($item);
                $hasVibro=false;
                $hasWaterRes=false;
                //var_dump ($item);
                //идем по списку старых параметров
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //если параметр нам не нужен для Прома - мы его все равно оставим как Пользовательскую характеристику. Если нам это не надо - то строчку можно закоментить
                    $param=str_ireplace(";","|",$param);
                    $param_new=$param;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        //$param_new=str_ireplace("Страна","Страна производитель",$param);
                        //тут вообще надо параметр менять на <country>Страна_производитель</country>
                        $country=$paramVal;
                        $param_new=null;
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Для пары","Унисекс",$param_new);
                        $param_new=str_ireplace("Женский|Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской|Женский","Унисекс",$param_new);
                        $param_new=str_ireplace("Унисекс|Унисекс","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской|Унисекс","Мужской",$param_new);
                        $param_new=str_ireplace("Женский|Унисекс","Женский",$param_new);
                    }
                    if (strcmp($paramName,"Вид")==0)
                    {
                        $param_new=str_ireplace("Вид","Тип вибратора",$param);
                        $param_new=str_ireplace("Анальные","Анальный",$param_new);
                        $param_new=str_ireplace("Жидкий вибратор","Комбинированный",$param_new);
                        $param_new=str_ireplace("Вагинальные","Вагинальный",$param_new);
                        $param_new=str_ireplace("Клиторальный","Клиторальный",$param_new);
                        $param_new=str_ireplace("Компьютерные","Компьютерный",$param_new);
                        $param_new=str_ireplace("Для точки G","G-точки",$param_new);
                    }

                    if (strcmp($paramName,"Материал")==0)
                    {        
                        $param_new=str_ireplace("TPR(термопластичная резина)","ТПЭ (термопластичный эластомер)",$param);
                        $param_new=str_ireplace("Кожзаменитель","Искусственная кожа",$param_new);
                        $param_new=str_ireplace("Латекс (резина)","Латекс",$param_new);
                        $param_new=str_ireplace("Поливинилхлорид (PVC, ПВХ)","Поливинилхлорид",$param_new);
                        $param_new=str_replace("Кожа","Натуральная кожа",$param_new);
                        $param_new=str_ireplace("Полиуретан (без латекса)","Полиуретан",$param_new);
                        $param_new=str_ireplace("Спандекс","Полиуретан",$param_new);
                        $param_new=str_ireplace("Мед. силикон","Силикон",$param_new);
                        $param_new=str_ireplace("Медицинский пластик","Пластик",$param_new);
                        $param_new=str_ireplace("Полиамид","Пластик",$param_new);
                        $param_new=str_ireplace("Эластан","Полиуретан",$param_new);
                    }
                    if (strcmp($paramName,"Функции")==0)
                    {
                        if (strripos($param,"С вибрацией"))
                        {
                            //echo "$param<br>";
                            //$param_new="<param name=\"Функция вибрации\">Да</param>";
                            $hasVibro=true;
                        }
                        else
                        {
                            //$param_new="<param name=\"Функция вибрации\">Нет</param>";
                            $hasVibro=false;
                        }
                        if (strripos($param,"Водонепроницаемые"))
                        {
                            //echo "$param<br>";
                            //$param_new="<param name=\"Функция вибрации\">Да</param>";
                            $hasWaterRes=true;
                        }
                    }
                    if(strcmp($paramName,"Длина")==0)
                    {
                        //$param_new=str_ireplace("Длина","Длина (мм)",$param);
                        $paramVal=$paramVal*10;
                        //echo "Длина=$paramVal<br>";
                        $param_new="<param name=\"Длина (мм)\">".$paramVal."</param>";
                    }
                    if(strcmp($paramName,"Диаметр")==0)
                    {
                        //$param_new=str_ireplace("Длина","Длина (мм)",$param);
                        $paramVal=$paramVal*10;
                        //echo "Диаметр=$paramVal<br>";
                        $param_new="<param name=\"Диаметр (мм)\">".$paramVal."</param>";
                    }
                    if (strcmp($paramName,"Цвет")==0)
                    {
                        //echo "вошли в цвет<br>$paramVal<br>";
                        $firstParamVal=$this->getFirstParamVal($param);
                        $param="<param name=\"Цвет\">".$firstParamVal."</param>";
                        $param_new=str_ireplace("Золотой","Золотистый",$param);
                        $param_new=str_ireplace("Салатовый","Зеленый",$param_new);
                        $param_new=str_ireplace("Лиловый","Фиолетовый",$param_new);
                        $param_new=str_ireplace("Разные","Разные цвета",$param_new);
                        $param_new=str_ireplace("Леопардовый","Бежевый",$param_new);
                    }
 
                    $params_new[]=$param_new;
                }
                //а тут мы будем прописывать захардкодженные параметры
                $itemName=" ".mb_strtolower($itemName);
                if ($hasVibro||strripos($itemName,"вибр"))
                {
                    $params_new[]="<param name=\"Функция вибрации\">Да</param>";
                }
                else
                {
                    $params_new[]="<param name=\"Функция вибрации\">Нет</param>";
                }
                if ($hasWaterRes)
                {
                    $params_new[]="<param name=\"Водостойкость\">Да</param>";
                }

                //Тип интимной игрушки

                if (strripos($itemName,"проб"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Анальная пробка</param>";
                }
                if (strripos($itemName,"елочка"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Анальная пробка</param>";
                }
                if (strripos($itemName,"плаг"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Анальная пробка</param>";
                }
                if (strripos($itemName,"анал")&&strripos($itemName,"шар"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Анальные шарики</param>";
                }
                if (strripos($itemName,"анал")&&strripos($itemName,"бус"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Анальные шарики</param>";
                }
                if (strripos($itemName,"анал")&&strripos($itemName,"стим"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Анальный стимулятор</param>";
                }
                if ((strripos($itemName,"вагин")&&strripos($itemName,"шар"))||strripos($itemName,"lelo"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вагинальные шарики</param>";
                }
                if (strripos($itemName,"вагин")&&strripos($itemName,"трен"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вагинальный тренажер</param>";
                }
                if (strripos($itemName,"помп"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вакуумная помпа</param>";
                }
                if (strripos($itemName,"вибра")&&!strripos($itemName,"яйцо "))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вибратор</param>";
                }
                if (strripos($itemName,"яйцо"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вибро яйцо</param>";
                }
                if (strripos($itemName,"egg"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вибро яйцо</param>";
                }
                if (strripos($itemName,"простат"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Массажер простаты</param>";
                }
                if (strripos($itemName,"маструб"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Мастурбатор</param>";
                }

                if (strripos($itemName,"кукл"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Надувная кукла</param>";
                }
                if (strripos($itemName,"насад"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Насадка на половой член</param>";
                }
                if (strripos($itemName,"страп"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Страпон</param>";
                }
                if (strripos($itemName,"фалло"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Фаллоимитатор</param>";
                }
                if (strripos($itemName,"эрекционн"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Эрекционное кольцо</param>";
                }
                if (strripos($itemName,"лассо"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Эрекционное кольцо</param>";
                }
                if (strripos($itemName,"реалистичная вагина"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вагина</param>";
                }


                //на всякий случай удаляем возможные дубли
                $params_new=array_unique($params_new);
                
                echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";
                
                //а теперь собираем айтем (старую шапку+новые параметры)
                //сначала склеиваем параметры
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }

            //тут будем сорбирать все позиции
            $items_new.=$new_item;
        }
        //обрамляем айтемсы нужным тегом
        $items_new="<items>".$items_new."</items>";
        //начинаем собирать финальную ХМЛку
        $XMLnew=$xmlHead.PHP_EOL."</categories>".PHP_EOL.$items_new.PHP_EOL."</price>";
        $XMLnew=$this->delSpaces($XMLnew);
        $XMLnew=str_ireplace("Sunspice","Sunspice Lingerie",$XMLnew);
        //var_dump($XMLnew);
        file_put_contents("new_test.xml",$XMLnew);
        echo "<b>Done</b>";

    }
    
}

$test=new testXML();
$test->parseXML();
