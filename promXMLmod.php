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
        $firstParamVal=explode(";",$paramVal);
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
            echo "No params found<br>";
        }
        //var_dump ($params);
        return $params;
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
                //идем по списку старых параметров
                foreach ($params as $param)
                {
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
                //идем по списку старых параметров
                foreach ($params as $param)
                {
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
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new=str_ireplace("Объем","Объем (мл)",$param);
                    }
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Женский;Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской;Женский","Унисекс",$param_new);
                    }
                    $params_new[]=$param_new;
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
                //идем по списку старых параметров
                foreach ($params as $param)
                {
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
                    if (strcmp($paramName,"Пол")==0)
                    {
                        $param_new=str_ireplace("Для женщин","Женский",$param);
                        $param_new=str_ireplace("Для мужчин","Мужской",$param_new);
                        $param_new=str_ireplace("Женский;Мужской","Унисекс",$param_new);
                        $param_new=str_ireplace("Мужской;Женский","Унисекс",$param_new);
                        $param_new=str_ireplace("Унисекс;Для пары","Унисекс",$param_new);
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
                        $param_new=str_ireplace(";","/",$param_new);
                    }
                    $params_new[]=$param_new;
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
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>".PHP_EOL;
                //break;
                //var_dump ($new_item);
            }
            //тут будем сорбиратьвсе позиции
            $items_new.=$new_item;
        }
        //обрамляем айтемсы нужным тегом
        $items_new="<items>".$items_new."</items>";
        //начинаем собирать финальную ХМЛку
        $XMLnew=$xmlHead.$items_new;
        $XMLnew=$this->delSpaces($XMLnew);
        //var_dump($XMLnew);
        file_put_contents("new_test.xml",$XMLnew);
        echo "<b>Done</b>";

    }
    
}

$test=new testXML();
$test->parseXML();
