<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * testXML
 */
class testXML
{    
    /**
     * pathOrig
     * путь к оригинальному файлу выгрузки
     * @var string - путь к оригинальной ХМЛ
     */
    //private $pathOrig="prom_ua.xml";
    private $pathOrig="/home/yc395735/aaaa.in.ua/www/system/storage/download/prom_ua.xml";    
    /**
     * pathMod
     * путь к модифицированной выгрузке
     *
     * @var string - путь к модифицированному ХМЛ
     */
    //private $pathMod="new_test.xml";
    private $pathMod="/home/yc395735/aaaa.in.ua/www/system/storage/download/prom_ua1.xml";
           
    /**
     * pathSatisfyer
     *
     * @var string
     */
    private $pathSatisfyer="https://sexgood.com.ua/system/storage/download/satisfyer.xml";

    /**
     * readFile
     * Получаем оригинальную ХМЛ
     * @return void - прочитаная оригинальная ХМЛка
     */
    private function readFile()
    {
        //$xml=file_get_contents('test.xml');
        $xml=file_get_contents($this->pathOrig);
        //$this->baseXML=file_get_contents('prom_ua.xml');
        //var_dump ($xml);
        return $xml;
    }

    private function readSatisfyerFile()
    {
        //$xml=file_get_contents('test.xml');
        $xml=file_get_contents($this->pathSatisfyer);
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
    
    /**
     * stripHead
     * удаляем из ХМЛки все статические элементы, оставляя толлько айтемы
     * @param  mixed $txt - начальная ХМЛка
     * @return void - только айтемы
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
    
    /**
     * getItemsArr
     * Вормируем массив айтемов из их списка
     * @param  mixed $txt - список айтемов
     * @return array - массив, где каждый айтем - отдельный элемент.
     */
    private function getItemsArr ($txt)
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
    
    /**
     * delSpaces
     * удаляем лишние пробелы
     * @param  mixed $txt
     * @return void
     */
    private function delSpaces($txt)
    {
        $new_txt=str_replace("> ",">",$txt);
        $new_txt=str_replace(" >",">",$new_txt);
        $new_txt = preg_replace('/\s+/', ' ', $new_txt);
        return $new_txt;
    }
    
    /**
     * getCatId
     * пролучаем АйДи категории для конкретного айтема
     * @param  mixed $item - айтем
     * @return void - айди категории 
     */
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
    
    /**
     * getItemName
     * Получаем имя конкретного айтема
     * @param  mixed $item - айтем
     * @return void - имя айтема
     */
    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
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
    
    /**
     * getFirstParamVal
     * Получаем перврое значение параметра 
     * (если их - несколько. Если значение одно - то получаем его)
     * @param  mixed $param
     * @return void
     */
    private function getFirstParamVal($param)
    {
        if (preg_match("#>(.+?)<#",$param,$matches))
        {
            $paramVal=$matches[1];
        }
        $firstParamVal=explode("|",$paramVal);
        return $firstParamVal[0];
    }
    
    /**
     * getParams
     * Получаем список параметров конкретного айтема
     * @param  mixed $item - айтем
     * @return void - список параметров айтема
     */
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
     * getItemId
     * получаем айди айтема
     * @param  mixed $item - айтем
     * @return void - айди айтема
     */
    private function getItemId($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }
    
    /**
     * getItemHead
     * получаем статическую часть айтема (все, что до параметров, то, что мы не меняем)
     * @param  mixed $item - айтем
     * @return void - часть айтема до параметров
     */
    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }
    
    /**
     * getXMLhead
     * Получаем часть ХМЛ, которая идет до списка айтемов
     * @param  mixed $txt - ХМЛ
     * @return void - часть ХМЛ, которая идет до списка айтемов
     */
    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    private function getDescription($item)
    {
        preg_match("#<description>(.*?)<\/description>#",$item,$matches);
        $descr=$matches[1];
        return $descr;
    }
    
    private function setDescr($item)
    {
        $const="<p>&nbsp;</p><hr />
        <p>Вас приветствует NO TABOO - самая крутая и большая сеть секс-шопов в Украине!</p><p>Мы находимся в городах: Киев, Львов, Одесса, Винница, Черкассы, Запорожье, Днепр, Чернигов, Полтава (в этих городах есть наша курьерская доставка).&nbsp;Доставка курьером ночью осуществляется в Киеве, Львове, Одессе, Днепре.</p><p>Высокий сервис обслуживания и все товары в наличии - это очень важно для современного человека покупающего удовольствие... и это все у нас есть!</p>";
        $desc=trim($this->getDescription($item));
        
        
        //$desc=str_replace('&lt;p&gt;&lt;br&gt;&lt;/p&gt;',"",$desc);
        $desc=html_entity_decode($desc);
        //$desc=strip_tags($desc);
        //echo $desc."<br>";
        $desc=str_replace("<p><br></p>","",$desc);
        if (empty($desc))
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
            }

            $desc.=$const;
            $item=preg_replace("#<description>(.*?)</description>#s","<description>$desc</description>",$item);
            //echo "$desc<br>";
        }
        else
        {
            $desc=htmlspecialchars_decode($desc);
            $desc.=$const;
            $item=preg_replace("#<description>(.*?)</description>#s","<description>$desc</description>",$item);
        }
        return $item;
    }
    
    /**
     * parseXML
     * Разбираем ХМЛ
     * (проставляем страны для поставщиков, делаем соответствие наших параметров параметрам Прома, меняем наши имена поставщиков на те, которые уже существуют на Проме)
     * @return void
     */
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
            $item=$this->setDescr($item);
            //обнуляем новую позицию перед созданием
            $new_item=null;
            $catId=$this->getCatId($item);
            //это массив параметров айтема. Их мы как раз и будем менять, при чем как имя параметра, так и его значение

            //
            if (strripos($item,"<vendor>Le Frivole"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">Эстония</param>",$item);
                $item=str_replace("<param name=\"Страна\">Европа;Китай</param>","<param name=\"Страна\">Эстония</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Blush"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">США</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Baile"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">Китай</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Toyfa"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">Китай</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Envy"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">Китай</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Be Wicked"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">США</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Obsessive"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">Польша</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Pipedream"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Европа</param>","<param name=\"Страна\">США</param>",$item);
                //var_dump($item);
                //break;               
            }
            if (strripos($item,"<vendor>Mystim"))
            {
                //var_dump($item);
                $item=str_replace("<param name=\"Страна\">Германия;Европа</param>","<param name=\"Страна\">США</param>",$item);
                //var_dump($item);
                //break;               
            }
            
            

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
                        }
                        if (strcmp($paramName,"Объем")==0)
                        {
                            //$param_new=str_ireplace("Объем","Объем (мл)",$param);
                            $param_new="<param name=\"Объем\" unit=\"мл\">".$paramVal."</param>";
                        }
                        if (strcmp($paramName,"Тип")==0)
                        {
                            $param_new=str_ireplace("Тип","Тип средства",$param);
                            $param_new=str_ireplace("Гель, мазь","Гель",$param_new);
                            $param_new=str_ireplace("Крема","Крем",$param_new);
                        }
                        $params_new[]=$param_new;
                    }
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
                $new_item=$itemHead.$country.$new_params."</item>";
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
                            //$param_new=str_ireplace("Объем","Объем (мл)",$param);
                            $param_new="<param name=\"Объем\" unit=\"мл\">".$paramVal."</param>";
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
                if (is_array($params_new))
                {
                    foreach ($params_new as $new_param)
                    {
                        //отсекаем страну, которая у нас пустая (NULL)
                        if ($new_param!=null)
                        {
                            $new_params.=$new_param.PHP_EOL;
                        }
                        
                    }
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                            $param_new=str_ireplace("L;XL;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("L;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("L;M;S","S/M/L",$param_new);
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

                if (is_array($params_new))
                {
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
                }
                
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                if (is_array($params))
                {
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
                            //$param_new=str_ireplace("Объем","Объем (мл)",$param);
                            $param_new="<param name=\"Объем\" unit=\"мл\">".$paramVal."</param>";
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
                if (strripos($itemName,"Мята"))
                {
                    $params_new[]="<param name=\"Вкус\">Мята</param>";
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
                
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                if (is_array($params))
                {
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
                            //$param_new=str_ireplace("Объем","Объем (мл)",$param);
                            $param_new="<param name=\"Объем\" unit=\"мл\">".$paramVal."</param>";
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
                if (strripos($itemName,"Мята"))
                {
                    $params_new[]="<param name=\"Вкус\">Мята</param>";
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
                
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                if(is_array($params))
                {
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
                            //$param_new=str_ireplace("Объем","Объем (мл)",$param);
                            $param_new="<param name=\"Объем\" unit=\"мл\">".$paramVal."</param>";
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
                }
                
                //а тут мы будем прописывать захардкодженные параметры
                $itemName=" ".mb_strtolower($itemName);
                $params_new[]="<param name=\"Вид парфюмерной продукции\">Духи</param>"; 
                if (strripos($itemName,"50%"))
                {
                    $params_new[]="<param name=\"Процент содержания феромонов (%)\">50</param>";
                }
                
                
                
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                if (is_array($params))
                {
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
                            //$param_new=str_ireplace("Объем","Объем (мл)",$param);
                            $param_new="<param name=\"Объем\" unit=\"мл\">".$paramVal."</param>";
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
                }
                
                //а тут мы будем прописывать захардкодженные параметры
                $itemName=" ".mb_strtolower($itemName);

                if (strripos($itemName,"массажн"))
                {
                    $params_new[]="<param name=\"Действие\">Массажное</param>";
                }
                
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
                //break;
                //var_dump ($new_item);
            }

            if ($catId==22||$catId==107)
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
                if (is_array($params))
                {
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
                }
                
                //а тут мы будем прописывать захардкодженные параметры
                
                
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
                //break;
                //var_dump ($new_item);
            }

            if ($catId==14||$catId==119||$catId==70||$catId==69||$catId==68||$catId==65||$catId==62||$catId==60||$catId==24||$catId==168)
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
                if (is_array($params))
                {
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
                            $firstParamVal=$this->getFirstParamVal($param);
                            $param_new="<param name=\"Тип ткани\">".$firstParamVal."</param>";
                            //$param_new="<param name=\"Тип ткани\">".$paramVal."</param>";
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
                            $param_new=str_ireplace("L;XL;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("L;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("|","/",$param_new);
                            $param_new=str_ireplace("Plus Size/","",$param_new);
                            $param_new=str_ireplace("/Plus Size","",$param_new);
                        }
                        
    
                        $params_new[]=$param_new;
                    }
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
                if (strripos($itemName,"Бюстгальтер"))
                {
                    $params_new[]="<param name=\"Бюстгальтер\">Юбка</param>";
                }

                //трусики в комплекте - по названию "комплект"
                if (strripos($itemName,"комплект")||strripos($itemName,"со стрингами")||strripos($itemName,"с трусиками"))
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
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                if (is_array($params))
                {
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
                            $firstParamVal=$this->getFirstParamVal($param);
                            $param_new="<param name=\"Тип ткани\">".$firstParamVal."</param>";
                            //$param_new="<param name=\"Тип ткани\">".$paramVal."</param>";
                            $param_new=str_ireplace("Кожзаменитель","Искусственная кожа",$param_new);
                            
                        }
                        if (strcmp($paramName,"Размер")==0)
                        {
                            
                            $param_new=str_ireplace("Размер","Международный размер",$param);
                            $param_new=str_ireplace("One size","S/M/L",$param_new);
                            $param_new=str_ireplace("L;XL;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("L;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("|","/",$param_new);
                            $param_new=str_ireplace("Plus Size/","",$param_new);
                            $param_new=str_ireplace("/Plus Size","",$param_new);
                        }
                        
    
                        $params_new[]=$param_new;
                    }
                }
                
                //а тут мы будем прописывать захардкодженные параметры
                //модель-майка
                
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                if (is_array($params))
                {
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
                            $param_new=str_ireplace("L;XL;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("L;M;S","S/M/L",$param_new);
                            $param_new=str_ireplace("|","/",$param_new);
                            $param_new=str_ireplace("Plus Size/","",$param_new);
                            $param_new=str_ireplace("/Plus Size","",$param_new);
                        }
                        
    
                        $params_new[]=$param_new;
                    }
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

                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
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
                if (is_array($params))
                {
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
                }
                
                //а тут мы будем прописывать захардкодженные параметры
                
                
                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
                //break;
                //var_dump ($new_item);
            }

            if ($catId==147||$catId==127||$catId==106||$catId==88||$catId==53||$catId==50||$catId==48||$catId==43||$catId==21||$catId==20||$catId==18||$catId==16||$catId==10||$catId==9||$catId==8||$catId==7||$catId==6||$catId==1||$catId==10||$catId==166)
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
                if (is_array($params))
                {
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
                            if (strripos($param,"|"))
                            {
                                $param_new="<param name=\"Тип вибратора\">Комбинированный</param>";
                            }
                        }

                        if (strcmp($paramName,"Материал")==0)
                        {        
                            $param_new=str_ireplace("TPR(термопластичная резина)","ТПЭ (термопластичный эластомер)",$param);
                            $param_new=str_ireplace("TPE (термопластичный эластомер)","ТПЭ (термопластичный эластомер)",$param);
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
                            $param_new=str_ireplace("Камни, стразы|","",$param_new);
                            if (strripos($param,"Силикон"))
                            {
                                $param_new="<param name=\"Материал\">Силикон</param>";
                            }
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
                            //все фалоимитаторы без вибрации - водостойкие
                            if (strripos($param,"Без вибрации"))
                            {
                                $hasWaterRes=true;
                            }
                        }
                        if(strcmp($paramName,"Длина")==0)
                        {
                            //$param_new=str_ireplace("Длина","Длина (мм)",$param);
                            $paramVal=$paramVal*10;
                            //echo "Длина=$paramVal<br>";
                            $param_new="<param name=\"Длина\" unit=\"мм\">".$paramVal."</param>";
                        }
                        if(strcmp($paramName,"Диаметр")==0)
                        {
                            //$param_new=str_ireplace("Длина","Длина (мм)",$param);
                            $paramVal=$paramVal*10;
                            //echo "Диаметр=$paramVal<br>";
                            $param_new="<param name=\"Диаметр\" unit=\"мм\">".$paramVal."</param>";
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
                            $param_new=str_ireplace("Металлик","Серый",$param_new);
                            $param_new=str_ireplace("Голубой, синий","Синий",$param_new);
                        }
    
                        $params_new[]=$param_new;
                    }
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
                if (strripos($itemName,"Клиторальный стимулятор"))
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
                if (strripos($itemName,"мастурб"))
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
                if (strripos($itemName,"tight puss"))
                {
                    $params_new[]="<param name=\"Тип интимной игрушки\">Вагина</param>";
                }

                //реалистик
                if (strripos($itemName,"реалист")||strripos($itemName,"Реалист"))
                {
                    $params_new[]="<param name=\"Тип фаллоимитатора\">Реалистик</param>";
                }


                if (is_array($params_new))
                {
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
                }
                
                //записываем страну как отдельный параметр
                $country="<country>".$country."</country>".PHP_EOL;
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$country.$new_params."</item>";
                //break;
                //var_dump ($new_item);
            }
            //девичник
            if ($catId==179)
            {
                $itemId=$this->getItemId($item);
                $params_new=null;
                //билет на toy party
                if ($itemId==37100)
                {
                    
                    $country="Украина";
                    $country="<country>".$country."</country>".PHP_EOL;
                    $itemHead=str_ireplace("<vendor></vendor>","<vendor>Собственное производство</vendor>",$itemHead);
                    $new_params="<param name=\"Назначение\">Универсальный</param>";
                    //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                    $itemHead=str_ireplace("</item>","",$itemHead);
                    //получаем новый айтем (не забываем закрывающий тег)
                    $new_item=$itemHead.$country.$new_params."</item>";

                }
            }

            //https://aaaa.in.ua/image/catalog/products/inet-magaz/Ira/2020/october/05-10-20/330435-128.jpg
            //https://aaaa.in.ua/image/catalog/promspecial/

            //тут будем сорбирать все позиции
            $items_new.=$new_item;
        }
        //обрамляем айтемсы нужным тегом
        //$items_new="<items>".$items_new."</items>";
        //скидка на черную пятниу
        //$tmp=$this->getItemsArr($items_new);
        //$items_new=$this->addDiscounts($tmp);
        //делаем фейковую ссылку
        $tmp=$this->getItemsArr($items_new);
        $items_new=$this->addFakeDisc($tmp);

        //скидка на сваком до НГ
        //$tmp=$this->getItemsArr($items_new);
        //$items_new=$this->setDiscSvacom($tmp);
        //////////////////////////////////////
        //добавляем в выгрузку сатисфаеры
        //$tmp=$this->getItemsArr($items_new);
        //$items_new.=$this->addSatsisfyer();
        $items_new=$this->addSatsisfyer().$items_new;
        //var_dump($items_new);



        $items_new="<items>".$items_new."</items>";
        //начинаем собирать финальную ХМЛку
        $XMLnew=$xmlHead.PHP_EOL."</categories>".PHP_EOL.$items_new.PHP_EOL."</price>";
        $XMLnew=$this->delSpaces($XMLnew);
        //подменяем путь к фото (на черную пятницу добавили на все фото стикер, и положили их в отделшьную папку)
        //$XMLnew=str_replace("image/catalog/products","image/catalog/promspecial/products",$XMLnew);

        $XMLnew=str_replace("<description>","<description><![CDATA[",$XMLnew);
        $XMLnew=str_replace("</description>","]]></description>",$XMLnew);
        $XMLnew=str_replace("(на удаление) ","",$XMLnew);
        //меняем имя производителей на верное
        $XMLnew=str_replace("Sunspice","Sunspice Lingerie",$XMLnew);
        $XMLnew=str_replace("Me-Seduce","Me Seduce",$XMLnew);
        $XMLnew=str_replace("Pink Lipstick","Pink Lipstick Lingerie",$XMLnew);
        $XMLnew=str_replace("COBECO","Cobeco Pharma",$XMLnew);
        $XMLnew=str_replace("Be Wicked","Wicked",$XMLnew);
        $XMLnew=str_replace("Soft Line","Softline",$XMLnew);
        $XMLnew=str_replace("Noir Handmade","NOIR",$XMLnew);
        $XMLnew=str_replace("EDC Collections","EDC",$XMLnew);
        $XMLnew=str_replace("Pretty Love","Baile",$XMLnew);
        $XMLnew=str_replace("MyMagicWand","EDC",$XMLnew);
        $XMLnew=str_replace("Master Series","XR Brands",$XMLnew);
        $XMLnew=str_replace("Tom of Finland","XR Brands",$XMLnew);
        $XMLnew=str_replace("London Coco De Mer","Lovehoney",$XMLnew);
        $XMLnew=str_replace("<vendor>Sensuva","<vendor>ON by Sensuva",$XMLnew);
        $XMLnew=str_replace("<vendor>UPKO","<vendor>Zalo",$XMLnew);
        $XMLnew=str_replace("<vendor>Runyu","<vendor>Foshan Jiaguan Metalwork",$XMLnew);
        //добавляем суперналичие
        $XMLnew=str_replace("available=\"true\"","available=\"true\" presence_sure=\"true\"",$XMLnew);
        //var_dump($XMLnew);
        file_put_contents($this->pathMod,$XMLnew);
        
    }

    private function getVendor($item)
    {
        preg_match("#<vendor>(.*?)<\/vendor>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getPrice($item)
    {
        preg_match("#<price>(.*?)<\/price>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getOldPrice ($item)
    {
        preg_match("#<oldprice>(.*?)<\/oldprice>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function setPrice($item, $price, $oldPrice)
    {
        $item=preg_replace("#<price>(.*?)<\/price>#s","<price>$price</price>",$item);
        $item=preg_replace("#<oldprice>(.*?)<\/oldprice>#s","<oldprice>$oldPrice</oldprice>",$item);
        if (strripos($item,"<oldprice>")===false)
        {
            $item=str_ireplace("</price>","</price>".PHP_EOL."<oldprice>$oldPrice</oldprice>",$item);
        }
        $item=str_ireplace("<oldprice></oldprice>","",$item);
        return $item;
    }

    public function addSatsisfyer()
    {
        $xml=$this->readSatisfyerFile();
        //var_dump($xml);
        $xml=$this->stripHead($xml);
        $itemsArr=$this->getItemsArr($xml);
        $new_items=$this->setPriceSatisfyer($itemsArr);
        $itemsArr=$this->getItemsArr($new_items);
        //echo "<pre>";print_r($itemsArr);echo"</pre>";
   
        if (is_array($itemsArr))
        {
            foreach ($itemsArr as $item)
            {
                $itemHead=$this->getItemHead($item);
                //var_dump($itemHead);echo"<br>";
                $id=$this->getItemId($item);
                $params=$this->getSatisfyerParams($id);
                //echo $params;
                $itemHead=$this->setSatsisfyerDesc($itemHead,$id);
                $item=$itemHead.$params;
                $item=$this->setSatsisfyerCategory($item,$id);
                //var_dump($item);echo"<br>";
                //break;
                $items_new.=$item."</item>".PHP_EOL;
            }
            return $items_new;
        }
    }

    private function setSatsisfyerDesc($itemHead,$id)
    {
        if ($id==33861)
        {
            $desc='<p>Набор ярких анальных елочек BEADS COLOUR от немецкого бренда&nbsp;SATISFYER станут прекрасным дополнением для анальной стимуляции. Набор прекрасно подходит как для новичков так и для продвинутых пользователей. Благодаря своей форме елочки будут осуществлять интенсивную стимуляцию анального отверстия, доставляя фантастическое удовольствие! Каждая анальная елочка будет приносить разные ощущения, круглые шарики будут обеспечивать нежную стимуляцию, а пирамидальные шарики подарят более яркие ощущения.</p> <p>Елочки выполнены из качественного и гладкого на ощупь силикона, абсолютно безопасного для человеческого тела.</p> <p>Каждая анальная елка оснащена удобными кольцами для извлечения.</p> <p>Материал:&nbsp;&nbsp; &nbsp;Силикон<br /> Цвет:&nbsp;&nbsp; &nbsp;Розовый и синий<br /> Длина:&nbsp;20, 5 см<br /> Вес:&nbsp; &nbsp; 118 г<br /> Ширина:&nbsp; &nbsp;2,8 - 3,3 см<br /> Стимуляция:&nbsp;&nbsp; &nbsp;Анальная<br /> Подходит для:&nbsp;&nbsp; &nbsp;Мужчины&nbsp;и женщины</p>';
        }
        if ($id==27877)
        {
            $desc='<p>В стильном смокинге Penguin сочетаются революционная технология Air-Pulse от Satisfyer и очаровательный игривый дизайн. <font>Вероятно, самая шикарная жемчужина в семье Satisfyer - Satisfyer Penguin. </font></p> <p>Вакуумный стимулятор клитора Пингвин выполнен в эргономичной форме,&nbsp;идеально подходит для новичков и является прекрасным спутником в путешествиях.</p> <p><font>Он хорошо выглядит в элегантном смокинге и просто ждет, когда вы приведете его на свидание на двоих - и позволите ему порадовать вас вакуумными волнами и взрывным оргазмом. Клиторальный стимулятор имеет&nbsp;</font><font>11 уровней интенсивности, от самой мягкой и нежной до самой интенсивной, которая&nbsp;заставит вас дрожать от наслаждения.&nbsp;</font></p> <p><font>Благодаря бесшумному двигателю вы также можете использовать его, не беспокоясь о том, что другие вас услышат.&nbsp;</font><font>Satisfyer Penguin водонепроницаем, поэтому вы можете наслаждаться им в ванне или душе.&nbsp;</font></p> <p><font>Усовершенствованные кнопки позволяют легко переключаться между уровнями интенсивности, даря вам горячие волны возбуждения и удовольствия независимо от того, где вы находитесь и в каком настроении.</font></p> <p><font><font>Тонкая, эргономичная форма&nbsp;Penguin обеспечивает удобную транспортировку во время путешествий.&nbsp;</font></font></p> <p><font><font>Благодаря простоте использования и инновационной технологии Satisfyer-Airpulse он отлично подходит для начинающих.</font></font></p> <p><font><font>&nbsp;</font><font>Penguin легко заряжать с помощью магнитного USB-кабеля для зарядки, что делает его идеальным компаньоном в путешествии или дома.&nbsp;</font></font></p> <p><font><font>После того, как последние волны вашего оргазма утихнут, сменная силиконовая насадка позволяет легко очистить эту модель Satisfyer с помощью нескольких брызг дезинфицирующего средства для сексуальных устройств или воды с мылом.</font></font></p> <p><font><font>Хотите сделать крутой подарок подруге?&nbsp;</font><font>Шикарного Пингвина&nbsp;не нужно прятать в подарочной упаковке и множестве пакетов&nbsp;- это отличный стильный подарок для современной женщины.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушки&nbsp;обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам окажут гарантийное обслуживание.</strong></p> <p><font><font>Материал:&nbsp;&nbsp; &nbsp;АБС-пластик, Силикон<br /> Водонепроницаемый:&nbsp;&nbsp; &nbsp;да<br /> Цвет:&nbsp;&nbsp; &nbsp;Черно-белый<br /> Вес:&nbsp; &nbsp; 101 г<br /> Длина:&nbsp;&nbsp; &nbsp;10,8 см<br /> Ширина:&nbsp; &nbsp;5,8 см<br /> Высота:&nbsp; &nbsp; 5,5 см<br /> С волновой стимуляцией<br /> Подходит для:&nbsp;&nbsp;&nbsp;Женщин</font></font></p>';
        }
        if ($id==33860)
        {
            $desc='<p><font><font>Желаете получать во время анальной стимуляции яркие ощущения приводящие к оргазмам? Тогда вам точно не обойтись без набора из анальных елочек Love Beads от популярного бренда секс игрушек&nbsp;Satisfyer.</font></font></p> <p><font><font>Набор состоит из 2х анальных елочек выполненных из мягкого и гладкого силикона.</font></font></p> <p>Каждая елочка имеет индивидуальную форму, которая будет доставлять незабываемые ощущения во время анальной стимуляции.</p> <p><font><font>Обе анальные цепочки чрезвычайно гибкие и идеально адаптируются к контурам вашего тела.&nbsp;</font></font></p> <p><font><font>Практичные петли на концах&nbsp;упрощают использование и гарантируют, что вы сможете безопасно использовать игрушки.</font></font></p> <p><font><font>Материал:&nbsp;&nbsp; &nbsp;Силикон<br /> Цвет:&nbsp;&nbsp; &nbsp;Черный</font></font><br /> Длина:&nbsp;20, 5 см<br /> Вес:&nbsp; &nbsp; 118 г<br /> Ширина:&nbsp; &nbsp;2,8 - 3,3 см<br /> <font><font>Стимуляция:&nbsp;&nbsp; &nbsp;Анальный<br /> Подходит для:&nbsp;&nbsp; &nbsp;Мужчина и женщина</font></font></p>';
        }
        if ($id==39492)
        {
            $desc='<p><font><font>Satisfyer Sparkling Darling - тайный, сексуальный и соблазнительный.&nbsp;</font><font>В дополнение к высокому качеству изготовления и исключительному дизайну, этот вибратор является невероятно удобным в использовании.&nbsp;</font></font></p> <p><font><font>Благодаря гигиеническому колпачку он незаметно скрывается в сумочке и выглядит так же хорошо, как ваша любимая помада.</font></font></p> <p><font><font>Компактный размер этого мини-вибратора скрывает большую мощность: 10 различных моделей вибрации и 5 скоростей приведут в блаженство ваш клитор и подарит вам взрывной оргазм. </font></font></p> <p><font><font>Гладкая поверхность из нежного силикона приятна на ощупь и становится еще более гладкой с добавлением лубриканта.&nbsp;</font><font>Вибратор для клитора также можно использовать с надетым колпачком, что приведет к более острым ощущениям.</font></font></p> <p><font><font>Удобный размер Sparkling Darling делает его идеальным спутником в путешествиях - в отпуске, деловой поездке или поездке в город.&nbsp;</font></font></p> <p><font><font>Он будет прекрасным компаньоном в любое время и в любом месте - даже в душе или ванне.&nbsp;</font></font></p> <p><font><font>Благодаря водонепроницаемой (IPX7) отделке он также порадует вас в душе или ванне.&nbsp;</font></font></p> <p><font><font>После использования вы можете легко очистить мини вибратор с помощью мыла, теплой воды или средства для чистки секс игрушек.&nbsp;</font></font></p> <p><font><font>Аккумулятор мощного вибратора можно заряжать экологически безопасным способом с помощью прилагаемого USB-кабеля для зарядки.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушки&nbsp;обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам окажут гарантийное обслуживание.</strong></p> <p>Материал:&nbsp;&nbsp; &nbsp;АБС-пластик, Силикон<br /> Водонепроницаемый:&nbsp;&nbsp; &nbsp;да<br /> Аккумулятор:&nbsp;&nbsp; &nbsp;<font><font>литий-ионные батареи</font></font><br /> Ширина:&nbsp;&nbsp; &nbsp;3 см<br /> С вибрацией:&nbsp;&nbsp; &nbsp;да<br /> Стимуляция:&nbsp;&nbsp; &nbsp;Клиторальный, Вагинальный<br /> Подходит для:&nbsp;&nbsp; &nbsp;Женщин<br /> Цвет:&nbsp;&nbsp; &nbsp;Черный<br /> Длина:&nbsp;&nbsp; &nbsp;11,4 см<br /> Вес:&nbsp; &nbsp;100 г</p>';
        }
        if ($id==39493)
        {
            $desc='<p><font><font>Побалуйте себя и своего любовника новой игрушкой для усиления ваших оргазмов&mdash; Satisfyer Royal One.&nbsp;Эрекционное кольцо для члена со стимулятором клитора изготовлено из приятного для тела силикона и возбуждает обоих партнеров благодаря ограниченному кровотоку и мощным вибрациям.&nbsp;</font></font></p> <p><font><font>Когда кольцо надевают на половой член, оно усиливает эрекцию, а также задерживает эякуляцию - для максимального удовольствия.&nbsp;</font><font>Нежный стимулятор клитора при каждом толчке будет нажимать на&nbsp; клитор, обеспечивая дополнительную стимуляцию.&nbsp;</font></font></p> <p><font><font>А с приложением Satisfyer Connect у вас есть еще больше возможностей: с помощью приложения вы можете создавать новые программы вибрации, обмениваться ими с другими пользователями или управлять Royal One в любой точке мира через Интернет - или позволить кому-то другому управлять им!&nbsp;Он также делает гораздо больше.</font></font></p> <p><font><font>Royal One также можно управлять без приложения с помощью интуитивно понятной панели управления One Button.&nbsp;</font></font></p> <p><font><font>Благодаря водонепроницаемой (IPX7) отделке вы можете порадовать своего любовника, даже когда он немного намокнет - кольцо для члена со стимулятором клитора также можно использовать в душе, ванной или частной гидромассажной ванне.</font></font></p> <p><font><font>&nbsp;</font><font>А если у вашего эрекционного кольца закончился заряд, то&nbsp;вы можете зарядить встроенные батареи с помощью прилагаемого USB-кабеля для зарядки.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушки&nbsp;обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам окажут гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Синий</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,2 см</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>3,2 см</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>7,5 см</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Совместимость с приложением Satisfyer Connect:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Пенис</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Мужчина</font></font></td> </tr> </tbody> </table> <p>&nbsp;</p>';
        }
        if ($id==39498)
        {
            $desc='<p><font>Strong One от Satisfyer повысит выносливость и задержит эякуляцию во время секса!&nbsp;</font></p> <p><font>Кольцо для члена состоит из мягкого, приятного для тела силикона, который плотно прилегает к пенису.&nbsp;</font></p> <p><font>Ограничение оттока крови в половом члене усиливает вашу эрекцию, чтобы вы могли получить максимум удовольствия от занятий любовью.&nbsp;</font></p> <p><font>Strong One возбуждает обоих партнером во время секса интенсивными вибрациями, которыми можно управлять с помощью кнопки One Touch или бесплатного приложения Satisfyer Connect.&nbsp;</font></p> <p><font>Помимо возможности управлять своим устройством для поддержания сексуального здоровья с помощью дистанционного управления, существует множество других функций для изучения, таких как создание собственных программ вибрации на основе окружающего шума или списка воспроизведения Spotify.&nbsp;</font></p> <p><font><font>Благодаря водонепроницаемому (IPX7) покрытию Satisfyer Strong One также можно использовать в воде.&nbsp;</font><font>Используете ли вы его в душе или в ванне, это кольцо для члена обогатит ваши занятия любовью стимулирующими вибрациями.&nbsp;</font></font></p> <p><font><font>А если оно когда-нибудь разрядится, встроенные аккумуляторы можно будет зарядить с помощью прилагаемого USB-кабеля для зарядки.&nbsp;</font></font></p> <p><font><font>Виброкольцо можно легко очистить теплой водой с мягким мылом.&nbsp;</font><font>После этого обеспечьте идеальную гигиену с помощью средства для чистки секс-игрушек.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушки&nbsp;обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам окажут гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Синий</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,2 см</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>3,4 см</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Совместимость с приложением Satisfyer Connect:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Пенис</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Мужчины</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>7,4 см</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39508)
        {
            $desc='<p><font><font>Satisfyer Power Balls созданы для интенсивной тренировки мышцы тазового дна, чтобы вы могли не только держать свое интимное здоровье в тонусе, но и&nbsp;наслаждались опьяняющими оргазмами!&nbsp;</font></font></p> <p><font><font>Вагинальные шарики составляют набор из трех пар&nbsp;с разным весом, обеспечивают интенсивную&nbsp;тренировку&nbsp;тазовое дно с долгосрочным эффектом. При регулярном использовании, как&nbsp;и вы, и ваш партнер можете наслаждаться более интенсивными ощущениями во время занятий любовью.&nbsp;</font></font></p> <p><font><font>Достаточно всего 15 минут в день - вы можете комфортно носить шарики во время занятий спортом, дома или во время похода за покупками.&nbsp;</font></font></p> <p><font><font>Satisfyer Power Balls также идеально подходят для женщин после беременности.</font></font></p> <p><font>Шарики выполнены в форме песочных часов изготовлены из приятного для тела силикона, который очень гигиеничен и мягок.&nbsp;</font></p> <p><font>Практичная петля обеспечивает легкое введение и извлечение шариков.&nbsp;</font></p> <p><font>Во время тренировок обязательно используйте небольшое количеством лубриканта.&nbsp;</font></p> <p><font>Перед и после каждого использования мы также рекомендуем тщательную очистку теплой водой с мягким мылом и дезинфекцию с помощью средства для чистки для секс-игрушек.</font></p> <p><font><font>Шарики имеют вес от 60 г до 75,6 г и до 91,6 г, благодаря чему можно их использовать для постепенного увеличения нагрузки. Они прекрасно подходят&nbsp;как для начинающих, так и для опытных пользователей.&nbsp;</font></font></p> <p><font><font>Благодаря смещенному центру тяжести, шарики создают приятные стимулирующие вибрации, которые еще больше усиливают эффект от тренировки.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>&nbsp;</p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>Силиконовый</font></font></td> </tr> <tr> <td>&nbsp;</td> <td>&nbsp;</td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>3,4 см</font></font></td> </tr> <tr> <td> <p><font><font>Цвет:</font></font></p> </td> <td><font><font>Розовый, красный, бирюзовый</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>17,6 см</font></font></td> </tr> <tr> <td> <p><font><font>Вес:</font></font></p> </td> <td><font><font>349 г</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>3,3 см</font></font></td> </tr> <tr> <td> <p><font><font>Стимуляция:</font></font></p> </td> <td><font><font>Вагинальный</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Женщин</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39512)
        {
            $desc='<p><font><font>Приготовьтесь к двойному удовольствию с Satisfyer Dual Love!&nbsp;</font><font>Эта уникальная, мощная игрушка с двойной стимуляцией, для&nbsp;влагалища и клитора, поэтому вы можете наслаждаться как внутренне, так и внешне, когда захотите.</font></font></p> <p><font><font>&nbsp;Как и остальные новинки от Satisfyer, Dual Love&nbsp; также синхронизируется с&nbsp;бесплатным приложением Satisfyer Connect.</font></font></p> <p><font><font>Для всех тех, кто хочет получить непревзойденное удовольствие без усилий. Дизайн&nbsp;Satisfyer Dual Love отличается закругленной ручкой, 100% безопасным для тела силиконом и тонким корпусом с металлическими деталями.&nbsp;С двумя независимыми двигателями у вас есть возможность подключиться к удобному приложению, чтобы исследовать мир безграничного удовольствия.&nbsp;</font></font></p> <p><font><font>Пусть гладкий и закругленный носик перенесет вас в место страсти, объедините функцию вибрации с отсосом Pulsed Air или используйте тот, который вам больше всего нравится.</font></font></p> <p><font><font>Стимулятор&nbsp;</font></font>имеет 11 функций воздушной стимуляции и 10 функций вибрации.</p> <p><font><font>Приложение доступно для Apple, Android, планшетов и часов Apple и получает ежемесячные обновления и постоянный набор новых функций.</font></font></p> <p><font><font>Революционное приложение поддерживает множество функций, таких как сенсорное программирование, датчики движения, одновременное управление несколькими игрушками и интерактивную платформу, благодаря которой расстояние больше не является препятствием.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Особенности:</font></font></p> <ul> <li><font><font>Независимые моторы</font></font></li> <li><font><font>Импульсный воздушный стимулятор</font></font></li> <li><font><font>С вибрацией</font></font></li> <li><font><font>Мощный и бесшумный мотор</font></font></li> <li><font><font>С мобильным приложением (совместимо с любым смартфоном, планшетом, Apple Watch и системами Android)</font></font></li> <li><font><font>Водонепроницаемый IPX7</font></font></li> <li><font><font>Металлический USB аккумулятор</font></font></li> <li><font><font>Безопасный для тела силикон</font></font></li> </ul> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td>&nbsp;</td> <td>&nbsp;</td> </tr> <tr> <td>Длина (см)</td> <td>16.5</td> </tr> <tr> <td>Цвет</td> <td>Красный</td> </tr> <tr> <td>Диаметр (см)</td> <td>3.7</td> </tr> <tr> <td>Материал</td> <td>Силикон, Пластик</td> </tr> <tr> <td>Питание</td> <td>Встроенная литиевая батарея</td> </tr> </tbody> </table>';
        }
        if ($id==39516)
        {
            $desc='<p><font>Satisfyer Curvy 2+ - это вибратор с волновой стимуляцией, который нежно окружает ваш клитор своей мягкой силиконовой головкой и погружает вас в захватывающие дух оргазмы с помощью бесконтактной стимуляции волнами давления и мощной вибрации.&nbsp;</font><font>Выбирайте между захватывающими уровнями интенсивности волн давления и комбинируйте их с программами вибрации - так легко позволить этому милому стимулятору удовольствия затопить вас волнами наслаждения.&nbsp;</font></p> <p><font>Вам нравится получать удовольствие от сексуального благополучия в душе или в ванне с успокаивающей пеной?&nbsp;</font><font>Отлично!&nbsp;</font><font>Curvy 2+ водонепроницаем (IPX7) и может сопровождать вас в вашем маленьком приключении.&nbsp;Если он теряет мощность, вы можете перезарядить литий-ионные батареи простым и экологически чистым способом с помощью прилагаемого магнитного USB-кабеля для зарядки.</font></p> <p><font><font>Особенностью этого компактного стимулятора является не только его эргономичная форма и впечатляющие характеристики, но и его способность вдохнуть новую жизнь в ваши занятия любовью с помощью приложения.&nbsp;</font></font></p> <p><font><font>Приложение Satisfyer Connect, которое доступно бесплатно для Android и iOS, позволяет подключить Satisfyer Curvy 2+ через Интернет или Bluetooth к вашему смартфону, который затем можно использовать в качестве пульта дистанционного управления.&nbsp;</font><font>Даже часы Apple Watch или планшет могут управлять стимуляцией клитора.&nbsp;</font><font>Вы можете преобразовать окружающие звуки в захватывающие вибрации, которые вы испытываете непосредственно через Satisfyer с помощью приложения.&nbsp;</font><font>Можно даже составлять целые плейлисты из Spotify в ритмы для вашего вибратора.&nbsp;</font></font></p> <p><font><font>Конечно, вы также можете передать управление своему партнеру или другим пользователям и позволить им стимулировать вас через приложение - в прямом эфире, удаленно или в видеочате.</font></font><br /> <font><font>Приложение Satisfyer Connect, конечно же, соответствует требованиям GDPR и немецким и европейским правилам защиты данных.&nbsp;</font><font>Мы используем непрерывное шифрование, поэтому ваши занятия любовью остаются между вами и вашим партнером.</font></font><br /> <font><font>Попробуйте Satisfyer Curvy 2+ с инновационным управлением через приложение и придайте занятиям любовью совершенно новое измерение!</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Особенности:</font></font></p> <ul> <li><font><font>Перезаряжаемый</font></font></li> <li><font><font>Водонепроницаемый IPX7</font></font></li> <li><font><font>Безопасный для тела силикон</font></font></li> <li>Тихий</li> <li><font><font>Совместимость с любым смартфоном, планшетом и Apple Watch на базе Android или Apple</font></font></li> <li><font><font>Бесконечный набор программ с приложением Satisfyer. Также можно использовать без приложения</font></font></li> </ul> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td>Длина (см)</td> <td>14,8</td> </tr> <tr> <td>Цвет</td> <td>розовый</td> </tr> <tr> <td>Диаметр (см)</td> <td>4,8</td> </tr> <tr> <td>Материал</td> <td>Силикон, Пластик</td> </tr> <tr> <td>Питание</td> <td>Встроенная литиевая батарея</td> </tr> </tbody> </table>';
        }
        if ($id==39510)
        {
            $desc='<p><font><font>&nbsp;С&nbsp;Satisfyer Dual Love и приготовьтесь к двойному удовольствию. Эта уникальная, мощная игрушка с двойной стимуляцией влагалища и клитора, это значит что Вы можете наслаждаться глубокими вибрациями внутри, и идеальными стимуляциями клитора, когда захотите. А главное</font><font>, Вы можете пользоваться бесплатным приложением Satisfyer Connect.</font></font></p> <p><font><font>Его современный&nbsp; дизайн отличается закругленными кончиками, 100% безопасным для тела силиконом и тонким корпусом с металлическими деталями. Стимулятор имеет</font><font>&nbsp;два независимых мотора, которые поспособствуют исследовать мир безграничного удовольствия. Г</font><font>ладкий и закругленный кончик перенесет Вас в мир безграничной&nbsp;страсти, объединив функцию вибрации с сосателем&nbsp;Pulsed Air или используйте тот, который Вам хочется именно сегодня.</font></font></p> <p><font><font>Приложение доступно для Apple, Android, планшетов, а также&nbsp;часов Apple и которое ежемесячно&nbsp;обновляется с&nbsp;постоянным набором новых функций.&nbsp;</font><font>Революционное приложение поддерживает множество функций, таких как сенсорное программирование, датчики движения, одновременное управление несколькими игрушками и интерактивную платформу, благодаря которой расстояние больше не является препятствием.</font></font></p> <p><font><font>Особенности:</font></font></p> <ul> <li><font><font>2 независимых мощных и бесшумных&nbsp;мотора</font></font></li> <li><font><font>Импульсный воздушный стимулятор</font></font></li> <li><font><font>Мобильное&nbsp;приложение&nbsp;(совместимо с любым смартфоном, планшетом, Apple Watch и системами Android)</font></font></li> <li><font><font>Водонепроницаемый IPX7</font></font></li> <li><font><font>Металлический USB аккумулятор</font></font></li> <li><font><font>Безопасный для тела силикон</font></font></li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39517)
        {
            $desc='<p><font><font>Satisfyer Curvy 1+ - это революция среди вибраторов Satisfyer.&nbsp;</font><font>Этот маленький стимулятор клитора имеет не только&nbsp;захватывающую комбинацию интенсивных волн давления и разнообразных программ вибрации, но также может управляться с помощью приложения, соответствующего GDPR, с высокой степенью конфиденциальности!</font></font><br /> <font><font>Благодаря эргономичной форме Satisfyer Curvy 1+ удобно лежит в руке.&nbsp;</font><font>Он также водонепроницаем (IPX7), так что он может стимулировать вас волнами давления и вибрациями своей бархатистой мягкой головкой в ​​воде.&nbsp;</font><font>Элегантный дизайн и изысканный вид в бордовом цвете также делают его спутником, который вы захотите показать каждому.</font></font></p> <p>&nbsp;</p> <p><font><font>Новое приложение Satisfyer Connect доступно для всех операционных систем - как Android, так и iOS, включая Apple Watch.&nbsp;</font><font>Это бесплатно и каждый месяц будет удивлять вас своими захватывающими функциями.</font></font><br /> <font><font>Приложение подключается через Bluetooth или через Интернет и управляет вибратором с помощью захватывающих комбинаций вибрации.&nbsp;</font><font>Он работает как пульт дистанционного управления и может использовать микрофон вашего мобильного телефона для преобразования окружающего шума в вибрацию или передачи целых списков воспроизведения Spotify на ваш клитор с захватывающими ритмами.</font></font></p> <p><font><font>Приложение соответствует строгим требованиям немецких и европейских законов о защите данных.&nbsp;</font><font>Это означает, что только у вас есть доступ к приложению и вы можете наслаждаться им без помех.&nbsp;</font><font>Конечно, вы также можете позволить своему партнеру управлять приложением для получения дополнительных впечатлений или позволить другим пользователям управлять вашим Satsifyer в видеочатах.</font></font><br /> <font><font>Когда ваш Satisfyer Curvy 1+ разрядится, вы можете легко зарядить его с помощью прилагаемого магнитного USB-кабеля для зарядки.</font><font>&nbsp;</font><font>Этот доставляющий удовольствие вибратор подарит вам уникально увлекательное времяпрепровождение - в одиночестве, с партнером или в видеочате.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Особенности:</font></font></p> <ul> <li><font><font>Перезаряжаемый</font></font></li> <li><font><font>Водонепроницаемый IPX7</font></font></li> <li><font><font>Безопасный для тела силикон</font></font></li> <li>Тихий</li> <li><font><font>Совместимость с любым смартфоном, планшетом и Apple Watch на базе Android или Apple</font></font></li> <li><font><font>Бесконечный набор программ с приложением Satisfyer. Также можно использовать без приложения</font></font></li> </ul> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td>Длина (см)</td> <td>13,4</td> </tr> <tr> <td>Цвет</td> <td>бордовый</td> </tr> <tr> <td>Диаметр (см)</td> <td>4,8</td> </tr> <tr> <td>Материал</td> <td>Силикон, Пластик</td> </tr> <tr> <td>Питание</td> <td>Встроенная литиевая батарея</td> </tr> </tbody> </table>';
        }
        if ($id==39509)
        {
            $desc='<p><font>Шарики в форме песочных часов изготовлены из приятного для тела медицинского силикона, который очень нежный&nbsp; и мягкий.&nbsp;</font><font>Комфортная&nbsp;петля у шариков&nbsp;помогает легко вводить и вынимать.&nbsp;</font><font>Шарики нужно вводить с небольшим количеством лубриканта, обязательно на водной основе.</font></p> <p><font>C Satisfyer V Balls Вы будете иметь&nbsp;хорошо тренированные мышцы тазового дна, и будете&nbsp;наслаждаться новыми опьяняющими оргазмами. В набор&nbsp;</font><font>входят три шарика&nbsp;с разным весом.&nbsp;Тренируясь с ними каждый день, вы очень скоро почувствуете результат, с долгосрочным эффектом,&nbsp;ведь&nbsp;вы, и ваш партнер сможете наслаждаться более интенсивными ощущениями во время занятий любовью.&nbsp;</font><font>Достаточно всего 15 минут в день,&nbsp;во время занятий спортом, дома или на прогулке по парку.&nbsp;</font><font>Satisfyer V Balls также идеально подходят для женщин, после родов, но только после консультации с доктором.</font></p> <p><font>Характеристики:</font></p> <p>&nbsp;</p> <ul> <li>Все шарики разного цвета;</li> <li>Без смещенного центра тяжести;</li> <li>Шарики разного веса 79,3 гр, 114,1 гр, 150,3 гр;</li> <li>Мягкий и нежный силикон;</li> <li>Водонепроницаемые;</li> <li>Яркий дизайн.</li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39538)
        {
            $desc='<p><font><font>Все мы знаем, что менструальный цикл обычно не самое веселое событие в жизни женщин, которое может сопровождаться гормональным хаосом, болью и перепадами настроения, и если мы добавим к этому стресс, то тампона явн будет недостаточно.&nbsp;</font><font>Satisfyer предлагает решение всех этих проблем с этими менструальными чашами, которые являются не только экологической альтернативой тампонам или подобным им, но и упрощают эти дни месяца.</font></font></p> <p><font><font>Набор Feel Good Set состоит из 2 мягких и приятных на ощупь силиконовых менструальных чашек медицинского класса емкостью 15 и 20 мл.&nbsp;</font><font>Бесшовная конструкция чашек делает их очень простым в использовании, а закругленный шнур позволяет удобно вводить и извлекать чаши.&nbsp;</font><font>Медицинский силикон является гибким и идеально адаптируется к вашему телу и его изгибам, обеспечивая безопасную и гигиеническую защиту до 12 часов.&nbsp;</font><font>Еще одно преимущество - отказ от тампонов, которые могут повлиять на вашу флору и вызвать небольшие разрывы на стенке влагалища.</font></font></p> <p><font><font>Чашечки изготовлены из медицинского силикона.&nbsp;</font><font>Они не вредят коже, обладают большой скользящей способностью и большой гибкостью.&nbsp;</font></font></p> <p><font><font>Используйте хорошую смазку на водной основе (N-206) независимо от того, используете ли вы ее впервые или если она плохо смазана.&nbsp;</font><font>Не забывайте мыть чашку перед первым использованием, по окончании менструации и каждый раз, когда вы ее используете.&nbsp;</font><font>Один из возможных способов очистки - кипячение, но помните, что пока вы используете его с чистой водой, этого будет достаточно.</font></font></p> <p><font><font>Особенности:</font></font></p> <ul> <li><font><font>Упаковка из двух чашек - 15 мл - 20 мл</font></font></li> <li><font><font>Медицинский силикон</font></font></li> <li><font><font>Подходят для начального и продвинутого уровней</font></font></li> <li><font><font>Удлиненный шнур</font></font></li> <li><font><font>Эластичные&nbsp;и гибкие</font></font></li> <li><font><font>Легко очистить</font></font></li> <li><font><font>Время использования: до 12 часов</font></font></li> <li><font><font>Подходит для водных смазок</font></font></li> <li><font><font>Экологическая альтернатива</font></font></li> </ul>';
        }
        if ($id==39539)
        {
            $desc='<p>Менструальные чаши Feel Good Transparent от&nbsp;Satisfyer это экологичная и прекрасная альтернатива тампонам и другим одноразовым средствам гигиены, во&nbsp;время менструаций!</p> <p>Набор&nbsp;Satisfyer Feel Good Transparent состоит из двух мягких и гибких силиконовых чаш, выполненных из медицинского силикона.</p> <p>Благодаря своей мягкости и податливости материала чаши прекрасно и комфортно размещаются внутри влагалища, гарантируя надежную защиту во время менструации.</p> <p>Гигиеничные материал позволяет носить чашу до 12-ти часов.</p> <p>Чаши легко обрабатываются, с помощью простого мыться с мылом, или кипячением.</p> <p><font><font>Особенности:</font></font></p> <ul> <li><font><font>Упаковка из двух чашек - 15 мл - 20 мл</font></font></li> <li><font><font>Медицинский силикон</font></font></li> <li><font><font>Подходят для начального и продвинутого уровней</font></font></li> <li><font><font>Удлиненный шнур</font></font></li> <li><font><font>Эластичные&nbsp;и гибкие</font></font></li> <li><font><font>Легко очистить</font></font></li> <li><font><font>Время использования: до 12 часов</font></font></li> <li><font><font>Подходит для водных смазок</font></font></li> <li><font><font>Экологическая альтернатива</font></font></li> </ul>';
        }
        if ($id==39532)
        {
            $desc='<p><font>Satisfyer Love Triangle - это комфортный вибратор для клитора с утонченным дизайном, который нежно обхватывает Ваш клитор своей мягкой головкой и приводит Вас в невероятным оргазмам. Благодаря чувственной комбинации волн давления и вибраций. С ним&nbsp;</font><font>Вы легко можете выбирать, где и как хотите испытать новые удовольствия, потому что этот маленький драгоценный стимулятор полностью водонепроницаем (IPX7), и его можно использовать в воде.&nbsp;</font></p> <p>Satisfyer Love Triangle, новейшая версия линии Satisfyer.&nbsp;Love Triangle оснащен крышкой, которая одновременно является футляром. Благодаря своему компактному размеру, его легко разместить в&nbsp;сумочке. &laquo;Мощный двигатель&raquo; тем не&nbsp;менее очень тихий, гарантирует незаметное достижение оргазма.</p> <p>Love Triangle синхронизируется с Вашим телефоном, через приложение &mdash; Satisfyer Connect!&nbsp;С&nbsp;Satisfyer Connect вы&nbsp;можете расширять и&nbsp;персонализировать вибрационные программы в&nbsp;соответствии с Вашими&nbsp; потребностями. Вы&nbsp;можете свободно пользоваться разнообразным и&nbsp;растущим ассортиментом Bluetooth-устройств Satisfyer. Смешивайте и&nbsp;подбирайте программы, используя наши интуитивно понятные сенсорные элементы управления, или загружайте что-то свежее из&nbsp;нашей постоянно расширяющейся онлайн-библиотеки программ стимуляции.</p> <p><font>Нежный силикон очень легкий в уходе, достаточно очистить после использования теплой водой, мылом или средством для чистки интимных игрушек.&nbsp;</font><font>Если ваш маленький друг начинает замедляться, вы можете быстро зарядить аккумулятор&nbsp;с помощью прилагаемого магнитного USB-кабеля для зарядки, который входит в комплектацию.</font></p> <p><font>Характеристики:</font></p> <p><font>- 2 сверхмощных силовых двигателя </font></p> <p><font>- Медицинский силикон &nbsp;</font></p> <p><font>- Водонепроницаемый (IPX7) &nbsp;</font></p> <p><font>- Магнитный USB-кабель для зарядки &nbsp;</font></p> <p><font>- Тихий&nbsp;&nbsp;</font></p> <p><font>Длина:&nbsp;&nbsp; &nbsp; 106 мм </font></p> <p><font>Высота:&nbsp;&nbsp; &nbsp;145 мм</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39537)
        {
            $desc='<p><font>Все женщины знают, что менструальный цикл не является развлечением, часто сопровождается&nbsp;болью и перепадами настроения, а также дискомфорт при использовании прокладок или тампонов.&nbsp;</font><font>Satisfyer предлагает решение всех этих проблем с этими менструальными чашами.</font></p> <p><font>Feel Secure Set состоит из 2 мягких и приятных на ощупь силиконовых менструальных чаш&nbsp;емкостью 15 и 20 мл. Их</font><font>&nbsp;бесшовная конструкция делает их очень простым в использовании, а закругленный шнур позволяет удобно вводить и извлекать. Их</font><font>&nbsp;медицинский силикон является гибким и идеально адаптируется к вашему телу и его изгибам, обеспечивая безопасную и гигиеническую защиту до 12 часов.&nbsp;</font><font>Еще одно преимущество - отказ от тампонов и прокладок, которые могут повлиять на вашу флору.</font></p> <p><font><font>Характеристики:</font></font></p> <ul> <li><font><font>Упаковка из двух чашек - 15 мл - 20 мл</font></font></li> <li><font><font>Медицинский силикон</font></font></li> <li><font><font>Подходит для начального и продвинутого уровней</font></font></li> <li><font><font>Удлиненный шнур</font></font></li> <li><font><font>Эластичны</font></font></li> <li><font><font>Легко очистить</font></font></li> <li><font><font>Время использования: до 12 часов</font></font></li> <li><font><font>Экологическая альтернатива</font></font></li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39545)
        {
            $desc='<p><font><font>Удвойте ваше удовольствие - совершенно новый&nbsp;Satisfyer DUAL PLEASURE&nbsp;- это стимулятор клитора с поддержкой&nbsp;Bluetooth, а также вибратор точки G. </font></font></p> <p><font><font>В сочетании с&nbsp;отмеченным наградами приложением Satisfyer Connect,&nbsp;DUAL PLEASURE поднимите ваши сексуальные игры на совершенно новый уровень!&nbsp;</font></font></p> <p><font><font>Со вкусом оформленный минималистский дизайн DUAL PLEASURE отличается многофункциональностью с возможностью независимого управления волнами давления Air Pulse или вибрационной стимуляцией точки G. </font></font></p> <p><font><font>Вибратор ​​изготовлен из 100% силикона, который безопасен для тела и приятен на ощупь. Изогнутая конструкция DUAL PLEASURE обеспечивает идеальный угол для стимуляции точки G, а гибкая головка возбуждает ваш клитор в точном направлении.</font></font></p> <p><font><font>Благодаря водонепроницаемому оснащению он удовлетворит все ваши желания.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td>Материал</td> <td>Силикон, Пластик</td> </tr> <tr> <td>Диаметр (см)</td> <td>4.9</td> </tr> <tr> <td>Длина (см)</td> <td>17.9</td> </tr> <tr> <td>Марка/Линия</td> <td>Satisfyer</td> </tr> <tr> <td>Питание</td> <td>Встроенная литиевая батарея</td> </tr> <tr> <td>Страна</td> <td>Германия</td> </tr> <tr> <td>Цвет</td> <td>Белый</td> </tr> <tr> <td>Штрих-код</td> <td>4061504003092</td> </tr> </tbody> </table>';
        }
        if ($id==39546)
        {
            $desc='<p>Обтекаемая форма, роскошное цветовое решение &laquo;розовое золото&raquo;, простое управление режимами, исключительная бесшумность, даже на&nbsp;полной мощности&nbsp;&mdash; это&nbsp;SATISFYER Pro 2+ Vibration. Он&nbsp;объединил в&nbsp;себе все самое лучшее, что может быть в&nbsp;индустрии пикантных девайсов. Если вы&nbsp;желаете достигнуть мощного&nbsp;оргазма, значит, вам&nbsp;следует приобрести Pro 2+ Vibration для идеальной разрядки. Силиконовая насадка&nbsp;деликатно обхватит Ваш клитор&nbsp;и&nbsp;заставит Вас дрожать от&nbsp;удовольствия. Попробуйте, не&nbsp;пожалеете!</p> <p>Стимулятор нежно присасывается к&nbsp;области вокруг клитора, но&nbsp;при этом не&nbsp;прикасается к&nbsp;нему. Такая стимуляция называется бесконтактной, является самой безопасной и&nbsp;считается максимально действенной. Вакуум и&nbsp;воздух создают целенаправленные, но&nbsp;деликатные ритмичные волны.</p> <p>Это искусный куннилингус или нежное посасывание клитора, которое за&nbsp;считанные минуты доводит до&nbsp;оргазма. Satisfyer не&nbsp;боится воды и&nbsp;имеет магнитное зарядное устройство, позволяющее не&nbsp;глядя подсоединять игрушку к&nbsp;источнику питания.</p> <p>Характеристики:</p> <p>- Встроенный аккумулятор</p> <p>- Медицинский силикон&nbsp;</p> <p>- 11 режимов стимуляции</p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>&nbsp;</p> <p>&nbsp;</p>';
        }
        if ($id==39552)
        {
            $desc='<p><font>Удобная форма Satisfyer Love Breeze делает его идеальным воздушно-импульсным вибратором для путешествий: чувственная комбинация воздушных импульсов и покалывающего отрицательного давления, этот вибратор стимулирует клитор без контакта с 11 уровнями интенсивности, которыми можно легко управлять. Через интуитивно понятную панель управления.&nbsp;</font></p> <p><font>Компактная форма означает, что вы можете легко положить его в сумочку, а благодаря перезаряжаемым батареям он всегда готов, когда вам понадобится чувственная стимуляция клитора.</font></p> <p><font>Аппликационная головка Love Breeze изготовлена ​​из безопасного для тела гигиеничного силикона и плотно прилегает к клитору.&nbsp;</font></p> <p><font>Материал особенно мягкий и покорит вас своей нежностью.&nbsp;</font><font>Благодаря водонепроницаемому (IPX7) покрытию вы также можете использовать Satisfyer Love Breeze в душе или ванне и легко очищать его теплой водой с мягким мылом.&nbsp;</font></p> <p><font>Если ваш Love Breeze разрядится, вы можете зарядить встроенные батареи с помощью прилагаемого USB-кабеля для зарядки.</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font>Характеристики:</font></p> <ul> <li><font><font>2 мотора</font></font></li> <li><font><font>11 функций всасывания Air Pulse</font></font></li> <li><font><font>10 функций вибрации</font></font></li> <li><font><font>Независимые моторы</font></font></li> <li><font><font>Заряжается от USB</font></font></li> <li><font><font>Водонепроницаемый IPX7</font></font></li> <li><font><font>Безопасный для тела силикон</font></font></li> <li>Длина (см)&nbsp;&nbsp; &nbsp;14.5</li> <li>Диаметр (см)&nbsp;&nbsp; &nbsp;5.5</li> <li>Цвет - розовое&nbsp; золото</li> </ul>';
        }
        if ($id==39548)
        {
            $desc='<p>Набор менструальных чаш&nbsp;Satisfyer Feel Confident -&nbsp; многоразовое средство интимной гигиены, современная, комфортная и безопасная альтернатива одноразовым&nbsp;прокладкам и тампонам.</p> <p>Feel Secure Set состоит из 2 мягких&nbsp;силиконовых менструальных чаш&nbsp;емкостью 15 и 20 мл. Их комфортная бесшовная конструкция делает их очень удобніми в использовании, а&nbsp;шнур на пальчик позволяет удобно вводить и извлекать чашу. Их&nbsp;медицинский силикон является гибким и идеально адаптируется к Вашему телу и его изгибам, обеспечивая безопасную и гигиеническую защиту до 12 часов, без обязательной замены каждые 2-4 часа.&nbsp;Еще одно преимущество - отказ от тампонов и прокладок, которые могут повлиять на вашу флору.</p> <p>Характеристики:</p> <ul> <li>Упаковка из двух чашек - 15 мл - 20 мл</li> <li>Медицинский силикон</li> <li>Подходит для начального и продвинутого уровней</li> <li>Удлиненный шнур, закругленный кончик</li> <li>Эластичны</li> <li>Легко очистить</li> <li>Время использования:8-12 часов</li> <li>Экологическая альтернатива</li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39555)
        {
            $desc='<p><font>Компактный и очень удобный, клиторальный, воздушно-волновой стимулятор Satisfyer Love Breeze&nbsp;идеально подходит&nbsp;для путешествий: идеальная&nbsp;комбинация воздушных импульсов и покалывающего&nbsp; давления.&nbsp;Этот вибратор стимулирует клитор без контакта с 11 уровнями интенсивности, которыми можно легко управлять,&nbsp;через&nbsp;понятную панель управления.&nbsp;</font><font>Компактная форма, Вы можете легко положить его в сумочку, а благодаря перезаряжаемой батарее он всегда готов к использованию, как только вам захочется&nbsp;чувственной стимуляции клитора.</font></p> <p><font>Головка Love Breeze изготовлена ​​из безопасного для тела&nbsp;силикона и плотно прилегает к клитору.&nbsp;</font><font>Материал особенно мягкий и покорит Вас своей нежностью.&nbsp;</font><font>Благодаря водонепроницаемому (IPX7) покрытию вы также можете использовать Satisfyer Love Breeze в душе или ванне и легко очищать его теплой водой с мягким мылом</font></p> <p><font><font>Характеристики:</font></font></p> <ul> <li><font><font>2 независимых мотора</font></font></li> <li><font><font>11 функций всасывания Air Pulse</font></font></li> <li><font><font>10 функций вибрации</font></font></li> <li><font><font>Заряжается от USB</font></font></li> <li><font><font>Водонепроницаемый IPX7</font></font></li> <li><font><font>Безопасный для тела силикон</font></font></li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39559)
        {
            $desc='<p><font>Вы любите немного экстравагантности и не хотите обходиться без роскоши во время использования секс-игрушек?&nbsp;</font></p> <p><font>Satisfyer Haute Couture из роскошной коллекции Satisfyer&nbsp;не только выглядит качественно и элегантно.&nbsp;Вибратор с функцией воздушно-волновой стимуляции будет стимулировать клитор чувственным объединением&nbsp;пульсирующих волн давления и интенсивных вибраций.&nbsp;</font></p> <p><font>Круглая головка с насадкой из гладкого жидкого силикона нежно окружает клитор, стимулируя область клитора ласковыми прикосновениями.&nbsp;</font><font>Эргономичная ручка со вставкой&nbsp;из натуральной кожи и металла также удобно лежит в руке.</font></p> <p><font>11 уровней интенсивностей&nbsp;воздушно-волновой&nbsp;стимуляции и 10 программ вибрации Satisfyer Haute Couture можно легко контролировать с интуитивно понятной панели управления, поэтому вы можете быстро найти нужную программу для своих нужд.&nbsp;</font></p> <p><font>Модель Haute Couture водонепроницаема (IPX7), поэтому вы можете наслаждаться ею в душе или ванне, но из-за вставки из натуральной кожи ваша&nbsp;игрушка не должна&nbsp;оставаться под водой слишком долго.&nbsp;</font></p> <p><font>Встроенные батареи можно легко зарядить с помощью прилагаемого USB-кабеля для зарядки.</font></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>4,0 см</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>С волнами давления:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>144 г</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>19,2 см</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,3 см</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Черно-белый</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Женщин</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39558)
        {
            $desc='<p><font>Вибромассажер Satisfyer Wand-er Woman приглашает Вас в мир удовольствия всего тела.&nbsp;Его обтекаемая форма и&nbsp;размер специально разработаны для целевого давления, которое снимает напряжение и снимает стресс со всего тела.&nbsp;</font><font>Wand-er Woman соблазнит Вас и&nbsp;Вашего любовника захватывающей дух стимуляцией ваших самых интимных зон.</font></p> <p><em>Мотор&nbsp;</em>расположен в&nbsp;головке массажёра,&nbsp;очень мощный, и сможет довести до&nbsp;оргазма без особой прелюдии,&nbsp;и даже через одежду.&nbsp;</p> <p><font>Satisfyer Wand-er Woman идеально подходит для игры соло и с партнером. Р</font><font>аботает&nbsp;очень тихо, а благодаря водонепроницаемой конструкции его можно использовать даже в ванне.&nbsp;</font></p> <p><em>Габариты:</em> длина 34&nbsp;см, ширина 5,7&nbsp;см.</p> <p>Характеристики:</p> <ul> <li>стимуляция клитора и других эрогенных зон;</li> <li>силиконовое покрытие;</li> <li>10 режимов вибрации + 5 уровней интенсивности;</li> <li>мощный мотор для интенсивного воздействия;</li> <li>полная водонепроницаемость;</li> <li>перезаряжаемый.</li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39561)
        {
            $desc='<p><font>Воздушно-волновая стимуляция&nbsp;и роскошный дизайн?&nbsp;</font><font>Satisfyer High Fashion имеет и то, и другое!&nbsp;</font><font>Высококачественный вибратор с волновой стимуляцией, изготовленный из алюминия, отполированного вручную, очаровывает своим элегантным минималистичным внешним видом и стимулирует клитор чувственным сочетанием пульсирующих волн давления и мощной вибрации.&nbsp;</font></p> <p><font>Круглая аппликационная головка из сверхмягкого жидкого силикона идеально окружает клитор, возбуждая вас ласковой нежностью.&nbsp;</font><font>Эргономичный корпус High Fashion также удобно лежит в руке.</font></p> <p><font>Satisfyer High Fashion из коллекции Luxury можно легко контролировать с помощью интуитивно понятной панели управления - так что вы можете управлять и комбинировать 11 уровней интенсивности волн давления и 10 программ вибрации по отдельности.&nbsp;</font></p> <p><font>Среди всевозможных комбинаций вы всегда найдете подходящую для себя программу.&nbsp;</font><font>Вибратор с волнами давления может присоединиться к вам даже под душем или в ванне благодаря своей водонепроницаемой (IPX7) отделке.&nbsp;</font></p> <p><font>Если ваш&nbsp;High Fashion разрядится, его&nbsp;встроенные батареи можно зарядить с помощью прилагаемого USB-кабеля для зарядки.</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font>Характеристики:</font></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,9 см</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>4,2 см</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>17,2 см</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>165 г</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Серебряный</font></font></td> </tr> <tr> <td><font><font>С волнами давления:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Женщина</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39560)
        {
            $desc='<p><font><font>Вакуумный стимулятор Pret-a-Porter изготовлен из сверхмягкого&nbsp; силикона с использованием натуральной кожи, с великолепными металлическими вставками.&nbsp;Прос ]]>
            <![CDATA[ то побалуйте себя, потому что вы этого достойны!&nbsp;</font><font>Этот изысканный драгоценный камень из новой коллекции Satisfyer Luxury Collection включает в себя дополнительный захватывающий режим вибрации, который станет идеальной изюминкой для вашей коллекции игрушек.&nbsp;</font><font>Усовершенствованная бесконтактная технология волн сконцентрирует на Вас все внимание.&nbsp;</font><font>Для Вашего нового сексуального приключения выбирайте только самое лучшее - Satisfyer Luxury.</font></font></p> <p>Характеристики:<br /> <font><font>- натуральная кожа и металл</font></font><br /> <font><font>- Сверхмягкий жидкий силикон</font></font><br /> <font><font>- 11 программ волн&nbsp;и 10 программ вибрации</font></font><br /> <font><font>- Водонепроницаемость для Вашего удовольствия под водой&nbsp;</font></font>(IPX7)<br /> <font><font>- 2 отдельно управляемых двигателя</font></font><br /> <font><font>- Режим Whisper</font></font><br /> <font><font>- Стимуляция клитора с помощью волн давления и вибрации</font></font><br /> <font><font>- Материал: силикон, кожа, алюминий, медь, АБС</font></font></p> <p><font><font>Размер:</font></font></p> <p><font><font>Общая длина - 19,3 см;</font></font></p> <p><font><font>Диаметр - 5,4 см.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39565)
        {
            $desc='<p><font>Вы делитесь всем со своим партнером - почему бы и не сексуальными вибрациями?&nbsp;</font></p> <p><font>Тонкий стержень вибратора вводится во влагалище во время занятий любовью и стимулирует точку G и пенис, при этом сужая пространство во влагалище, благодаря чему образовывается максимально плотный контакт.&nbsp;</font><font>Возбуждающие вибрации широкой контактной поверхности также мягко или дико стимулируют клитор - пока вы, наконец, не испытаете взрывной кульминационный момент вместе.&nbsp;</font><font>Когда мужчина вставит свой пенис, ваши занятия любовью обогатятся нежным давлением на ее точку G и сильным напряжением.&nbsp;</font></p> <p><font>Мощный мотор заставляет Double Classic вибрировать, обеспечивая захватывающую стимуляцию для обоих партнеров. Этот в</font><font>ибратор&nbsp;просто необходим в каждом прикроватном ящике!&nbsp;</font><font>И, конечно же, женщина может наслаждаться этим одна.</font></p> <p><font><font>Этот мультивибратор, оснащенный 3 уровнями стимулирующей вибрации и 7 режимами горячей вибрации, может катапультировать вас в беспрецедентную кульминацию.&nbsp;</font></font></p> <p><font><font>Наслаждайтесь сексуальными приключениями даже в душе или ванне.&nbsp;</font><font>Благодаря водонепроницаемой конструкции (IPX7) вы можете использовать свою компактный вибратор где угодно.&nbsp;</font><font>Double Classic изготовлен из сверхмягкого, удобного для тела силикона, который кажется гладким и эластичным на вашей коже, а также его легко и гигиенично чистить водой с мылом или средством для чистки устройств для сексуального здоровья.&nbsp;</font><font>Прилагаемый магнитный USB-кабель и встроенный аккумулятор гарантируют, что ваше устройство быстро снова будет готово к работе.</font></font><br /> <font><font>Вместе или в одиночку - Satisfyer Double Classic доставит вам удовольствие, которое вы никогда не забудете!</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>3,5 см</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>52 г</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Женщина</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный, Вагинальный</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>6,7 см</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>9,5 см</font></font></td> </tr> <tr> <td><font><font>Длина вала:</font></font></td> <td><font><font>7 см</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>фиолетовый</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39567)
        {
            $desc='<p><font><font>Satisfyer Double Plus Remote - это вибратор для пары? для нее и для него.&nbsp;</font><font>Его особая форма возбуждает обоих партнеров головокружительными вибрациями во время занятий любовью.&nbsp;</font><font>Женщина надевает вибратор во время секса, поэтому стимулируются не только ее точка G и клитор, но и пенис ее партнера.&nbsp;</font><font>Маленький конец вибратора вводится во влагалище, а большой конец помещается на клитор.&nbsp;</font><font>Два мощных мотора одновременно стимулируют клитор, влагалище и пенис, придавая вашему сексу невероятный импульс.&nbsp;</font><font>Плотно прилегающая форма подковы обеспечивает комфорт, дополнительную герметичность и прекрасное давление на самые чувствительные места.&nbsp;</font><font>Конечно, женщины могут использовать эту игрушку в одиночку.</font></font></p> <p><font><font>Вибратор изготовлен из гибкого медицинского силикона и приятен на ощупь и бархатисто ложится на кожу.&nbsp;</font><font>По-настоящему особенной изюминкой этого устройства для секса является пульт дистанционного управления, с помощью которого вы можете с комфортом контролировать свое удовольствие, не нарушая потока возбуждения.&nbsp;</font><font>Выбирайте из 10 увлекательных программ вибрации.</font></font></p> <p><font>Satisfyer Double Plus Remote, конечно же, водонепроницаем, поэтому вы можете использовать его для романтического времяпрепровождения в гидромассажной ванне или на удобных простынях дома.&nbsp;</font><font>Многие пары говорят, что вибрации особенно сильны под водой.</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>63 г</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Женщина</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный, Вагинальный</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>2,8 см</font></font></td> </tr> <tr> <td><font><font>Длина вала:</font></font></td> <td><font><font>7 см</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,3 см</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>8,8 см</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Пурпурный</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> </tbody> </table> </td> </tr> </tbody> </table>';
        }
        if ($id==39562)
        {
            $desc='<h4><strong><strong><font><font>Satisfyer Endless Joy</font></font></strong><font><font>, новая&nbsp;игрушка&nbsp;для пар, которые можно использовать соло и вместе.</font></font></strong></h4> <p><font><font>Испытайте новый насыщенный сексуальный опыт!</font></font><br /> <font><font>Игрушка с тремя мощными двигателями, а значит с&nbsp;более чем 100 возможных комбинаций вибрации на выбор, его трудно превзойти.</font></font><br /> <font><font>Эта игрушка была специально разработана для удовлетворения потребностей каждого, даже самого искушенного клиента.&nbsp;</font></font><font><font>Вы можете использовать его как с партнером, так и соло.&nbsp;</font></font><font><font>Эргономичная U-образная форма&nbsp;&nbsp;</font></font><strong><font><font>Endless Joy&nbsp;</font></font></strong><font><font>&nbsp;очень подходит для использования в качестве кольца для массажа мошонки: два датчика мягко охватывают стержень и яички мужчины, доставляя им удовольствие от глубоких вибраций.&nbsp;</font></font></p> <p><font><font>А те, кто хочет включить&nbsp;&nbsp;</font></font><strong><font><font>Endless Joy</font></font></strong><font><font>&nbsp;в свои предварительные ласки, могут использовать его&nbsp;для стимуляции всех эрогенных зон.&nbsp;</font></font>14 различных режимов вибраций&nbsp;доставят множество возможностей для многочасовой, разносторонней и увлекательной игры.</p> <p><font><font>Его очень гибкие материалы с &laquo;шелковистым ощущением&raquo; предлагают вам сенсационный комфорт.</font></font></p> <p><font><font>Он полностью водонепроницаем и очень прост в уходе.</font></font></p> <p>Характеристики:</p> <p>-водонепроницаемый (IPX7)</p> <p>- перезаряжаемый</p> <p>-&nbsp;10 различных&nbsp;уровней вибраций</p> <p>- 3 мощных мотора</p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>&nbsp;</p>';
        }
        if ($id==39569)
        {
            $desc='<p><font><font>Satisfyer Double Joy - это вибратор для пар, сделанный из приятного для тела силикона.&nbsp;</font><font>Благодаря своей эргономичной и гибкой форме, он элегантно скользит между обоими партнерами и доставляет фантастическое наслаждение. Просто вставьте меньший отросток U-образного вибратора во время занятий любовью и наслаждайтесь одновременной стимуляцией клитора и пениса.&nbsp;</font><font>Когда мужчина вводит свой пенис, образовывается максимально плотный контакт, поэтому внутренний отросток этого компактного вибратора умело стимулирует точку G и клитор.&nbsp;</font><font>Два двигателя чувственно и мощно вибрируют на обоих концах, пока они не приведут вас обоих к экстатическому взаимному оргазму.</font></font></p> <p><font><font>Вибратор для пар также является водонепроницаемым (IPX7) и может использоваться&nbsp;в гидромассажной ванне или ванне или душе.&nbsp;</font><font>Когда аккумулятор разряжен, его можно подзарядить простым и экологически чистым способом с помощью прилагаемого магнитного USB-кабеля для зарядки.&nbsp;</font><font>Благодаря шелковистой гладкой силиконовой поверхности вибратор этой пары приятно ощущается на вашей коже, и его можно легко очистить после использования теплой водой с мылом или специальным очистителем для устройств для сексуального здоровья.</font></font></p> <p><font><font>Приложение Satisfyer Connect предлагает вам совершенно новые увлекательные приключения.&nbsp;</font><font>Просто скачайте бесплатное приложение для iOS и Android на свой смартфон, планшет или Apple Watch.&nbsp;</font><font>После этого вы можете легко подключить Satisfyer Double Joy через Bluetooth или Интернет к своему устройству и выпустить пар - нет никаких ограничений для ваших фантазий.&nbsp;</font><font>Он может передавать окружающие звуки в виде вибрации на вибратор или преобразовывать ваш любимый плейлист Spotify в чувственные вибрации, которыми вы можете наслаждаться в качестве захватывающих ритмов между вами.</font></font><br /> <font><font>Вы хотите, чтобы другие наслаждались вашими страстными творениями?&nbsp;</font><font>Отлично!&nbsp;</font><font>Приложение Satisfyer Connect позволяет вам делиться своими шаблонами вибрации с другими пользователями по всему миру.&nbsp;</font><font>И для этого дополнительного удовольствия вы также можете позволить другим управлять Double Joy или даже взять под контроль Satisfyers других пользователей, управляемых приложением, через видеочат.&nbsp;</font><font>Приложение соответствует требованиям GDPR и требованиям немецкой и европейской защиты данных.</font></font><br /> <font><font>Погрузитесь в совершенно новый мир эротических приключений и насладитесь захватывающими часами для двоих, как никогда раньше!</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td> <table> <tbody> <tr> <td> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный, Пенис, Вагинальный</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>4,2 см</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>79,7 г</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>9,1 см</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>Да</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары</font></font></td> </tr> <tr> <td><font><font>Совместимость с приложением Satisfyer Connect:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Белый</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>5,7 мм</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>';
        }
        if ($id==39564)
        {
            $desc='<strong><strong>Satisfyer Endless Joy</strong>, новая&nbsp;игрушка&nbsp;для пар, которые можно использовать соло и вместе.</strong> <p>Испытайте новые&nbsp;краски в своих сексуальных играх!<br /> Игрушка с тремя мощными моторами, а это с&nbsp;более чем 100 разных комбинаций вибраций в одной игрушке.<br /> Она была создана для новых ощущений и стимуляций в Вашей постели.&nbsp; Вы можете использовать его как с партнером, так и соло.&nbsp;Эргономичная&nbsp;форма&nbsp;&nbsp;<strong>Endless Joy&nbsp;</strong>&nbsp;идеально подходит&nbsp;для использования в качестве кольца, для клитора, сосков -&nbsp;&nbsp;доставляя им удовольствие от глубоких вибраций.&nbsp;</p> <p>Добавьте&nbsp;&nbsp;<strong>Endless Joy</strong>&nbsp;в свои предварительные ласки, могут использовать его&nbsp;для стимуляции всех эрогенных зон.&nbsp;14 различных режимов вибраций&nbsp;доставят множество возможностей для многочасовой, разносторонней и увлекательной игры.</p> <p>Он полностью водонепроницаем и очень прост в уходе.</p> <p>Рекомендуется использовать со смазкой на водной основе.</p> <p>Характеристики:</p> <p>-водонепроницаемый (IPX7)</p> <p>- перезаряжаемый</p> <p>-&nbsp;10 различных&nbsp;уровней вибраций</p> <p>- 3 мощных мотора</p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя (&nbsp;<a href="https://us.satisfyer.com/" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39571)
        {
            $desc='<p><font><font>Satisfyer Double Joy - незаменимое дополнение к сексу, которое будет усиливать удовольствие обоих партнеров во&nbsp;время близости.&nbsp;</font><font>Благодаря своей продуманной, анатомической и гибкой форме, вибратор будет плотно прилегать к телу и стимулировать самые важные зоны. Более тонкая часть вибратора вводится во влагалище, а большая накрывает клитор, такая конструкция обеспечивает объемную и интенсивную стимуляцию самых чувствительных&nbsp;зон женщины. Помимо этого, к</font><font>огда мужчина вводит свой пенис во влагалище,&nbsp;образовывается максимально плотный контакт, поэтому внутренний отросток этого компактного вибратора умело стимулирует точку G и клитор.&nbsp;</font><font>Два двигателя чувственно и мощно вибрируют на обоих концах, пока они не приведут вас обоих к экстатическому взаимному оргазму.</font></font></p> <p><font><font>Вибратор для пар имеет уровень водонепроницаемости&nbsp;IPX7, это значит, что он&nbsp;может использоваться&nbsp;в ванне или душе. Вибратор работает от перезаряжаемых литий-ионных батарей, которые заряжаются с помощью&nbsp;</font><font>прилагаемого магнитного USB-кабеля для зарядки.&nbsp;</font><font>Благодаря шелковистой, силиконовой поверхности вибратор очень приятен к телу, а также легко&nbsp;очищается после использования теплой водой с мылом или специальным очистителем для секс-игрушек.</font></font></p> <p><font><font>Приложение Satisfyer Connect предлагает вам совершенно новые увлекательные приключения.&nbsp;</font><font>Просто скачайте бесплатное приложение для iOS и Android на свой смартфон, планшет или Apple Watch.&nbsp;</font><font>После этого вы можете легко подключить Satisfyer Double Joy через Bluetooth или Интернет к своему устройству и выпустить пар - нет никаких ограничений для ваших фантазий.&nbsp;</font><font>Он может передавать окружающие звуки в виде вибрации на вибратор или преобразовывать ваш любимый плейлист Spotify в чувственные вибрации, которыми вы можете наслаждаться в качестве захватывающих ритмов между вами.</font></font><br /> <font><font>Вы хотите, чтобы другие наслаждались вашими страстными творениями?&nbsp;</font><font>Отлично!&nbsp;</font><font>Приложение Satisfyer Connect позволяет вам делиться своими шаблонами вибрации с другими пользователями по всему миру.&nbsp;</font><font>И для этого дополнительного удовольствия вы также можете позволить другим управлять Double Joy или даже взять под контроль Satisfyers других пользователей, управляемых приложением, через видеочат.&nbsp;</font><font>Приложение соответствует требованиям GDPR и требованиям немецкой и европейской защиты данных.</font></font><br /> <font><font>Погрузитесь в совершенно новый мир эротических приключений и насладитесь захватывающими часами для двоих, как никогда раньше!</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td> <table> <tbody> <tr> <td> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный, Пенис, Вагинальный</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>4,2 см</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>79,7 г</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>9,1 см</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>Да</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары</font></font></td> </tr> <tr> <td><font><font>Совместимость с приложением Satisfyer Connect:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Черный</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>5,7 мм</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>';
        }
        if ($id==39573)
        {
            $desc='<p>Благодаря Satisfyer Men Classic с структурированным внутренним рукавом и революционным регулятором внутреннего давления , вы будете испытывать самую фантастическую стимуляцию члена. Рукав изготовлен из сверхмягкого материала Cyberskin, который невероятно реалистичный на ощупь, благодаря чему подарит вам невероятное наслаждение. Киберкожа упругая и при этом достаточно эластичная, чтобы удовлетворить пенис любого размера.</p> <p>Вход в&nbsp;мастурбатора тугой, что значительно усилит удовольствие во время фрикционных движений. Рукав имеет рельефную текстуру внутри, которая будет мягко и интенсивно стимулировать весь ствол пениса. Глубина мастурбатора позволяет получить самые &quot;глубокие&quot; переживания, внутрь может погружаться член размером до 21 см!</p> <p>В верхней части мастурбатора вы найдете инновационный регулятор внутреннего давления. В закрытом состоянии эффект всасывания усиливается, или вы можете открыть его для плавной езды. Обязательно используйте достаточное количество смазки на водной основе для наилучшего результата. Ни в коем случае не применяйте лубриканты на масляной или силиконовой основе.</p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>Особенности классического мастурбатора Satisfyer Men:</p> <ul> <li>Водонепроницаемый</li> <li>Стимуляция всего пениса</li> <li>Инновационный регулятор внутреннего давления</li> <li>Сдержанный и элегантный дизайн</li> <li>Рукав из мягкого материала Cyberskin (TPE) для плавной и чувственной стимуляции</li> <li>Съемный рукав для легкой очистки</li> <li>Колба для гигиенического хранения мастурбатора</li> <li>Материал: АБС-пластик, ТПЭ</li> </ul> <p>Характеристики:</p> <p>Размеры: длина -&nbsp;25,9&nbsp;см, диаметр 6 - 9,6&nbsp;см, длина&nbsp;рукава - 21&nbsp;см.</p> <table border="0" cellpadding="0" width="789"> <tbody> <tr> <td width="392"> <p>Цвет:</p> </td> <td width="391"> <p>Черный с серебристым</p> </td> </tr> <tr> <td width="392"> <p>Длина:</p> </td> <td width="391"> <p>25,9 см</p> </td> </tr> <tr> <td width="392"> <p>Стимуляция</p> </td> <td width="391"> <p>Пенис</p> </td> </tr> <tr> <td width="392"> <p>Подходит для:</p> </td> <td width="391"> <p>мужчин</p> </td> </tr> <tr> <td width="392"> <p>Вес:</p> </td> <td width="391"> <p>699 г</p> </td> </tr> <tr> <td width="392"> <p>Ширина:</p> </td> <td width="391"> <p>9,6 см</p> </td> </tr> </tbody> </table> <ul> </ul>';
        }
        if ($id==39576)
        {
            $desc='<p><font>Satisfyer Endless Fun предлагает вам более 33 захватывающих методов его использования - будь то в одиночку или вдвоем, этот вибратор сделает все, чтобы доставить максимальное удовольствие!&nbsp;</font><font>Внутри изогнутого корпуса находятся 3 мощных вибромотора, которые приведут ваше тело в захватывающий экстаз.&nbsp;</font><font>Первый двигатель расположен на нижнем конце объемной ручки, которая&nbsp;также может использоватся для внутренней стимуляции влагалища или ануса.&nbsp;</font><font>И на каждом из подвижных рычагов этого устройства есть по одному мотору.&nbsp;</font><font>Вы можете выбрать один из 10 захватывающих уровней вибрации, которые можно регулировать отдельно для обеих рук.&nbsp;</font><font>Это позволяет использовать 100 различных комбинаций вибраций, которые откроют невероятное разнообразие в вашей любовной игре.&nbsp;</font><font>Головку Satisfyer Endless Fun можно поворачивать на 180 градусов, так что вы можете использовать ее в любом положении.</font></p> <p>&nbsp;</p> <p><font><font>Имея более 33 возможных применений, это чувственное устройство может мягко стимулировать соски, клитор&nbsp;или половые&nbsp;губы, например, непрерывно стимулируя их к интенсивным оргазмам.&nbsp;</font><font>Бархатистый мягкий стержень с изогнутой формой можно удобно ввести во влагалище, искусно стимулируя точку G.&nbsp;</font><font>У мужчин он своими мощными вибрациями вызывает интенсивные волны возбуждения на головке и основании полового члена.&nbsp;</font><font>Его также можно использовать в различных сексуальных позах, включая позы doggy или миссионерские.</font></font></p> <p><font><font>Satisfyer Endless Fun изготовлен из бархатистого мягкого гигиенического силикона, приятного на ощупь и легко очищаемого стерильным чистящим средством для секс игрушек или теплой водой с мылом.&nbsp;</font><font>Он также водонепроницаем, поэтому идеально подходит для душа или романтического времяпрепровождения в гидромассажной ванне!</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>156 г</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Мужчина, Женщина</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Анальный, Клиторальный, Пенис, Вагинальный</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,4-7,2 см</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>21 см</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Белый</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39579)
        {
            $desc='<p><font><font>Satisfyer уже произвел ряд моделей, специально разработанных для стимуляции полового члена с помощью вибрации, поэтому мы не можем назвать эту модель Satisfyer Partner Multifun 3 шокирующей новинкой, но мы находим в ней некоторые интересные нововведения.&nbsp;</font></font></p> <p><font><font>U-образный конец в верхней части вибратора, который почти полностью сделан из силикона, используется для прилегания к пенису, а два мощных вибромотора внутри него обеспечивают эффективную стимуляцию. Разветвленные о</font><font>тростки гибкие, но имеют хорошую опору, хорошо приспосабливаются к разным анатомическим особенностям.&nbsp;</font><font>Для удобства всю U-образную стимулирующую часть можно повернуть вокруг оси устройства в зоне декоративного кольца золотого цвета, чтобы не возникало дискомфортных ситуаций во время использования.&nbsp;</font></font></p> <p><font><font>В вибраторе есть третий мотор, поэтому он может функционировать как почти классический вибратор, доставляя удовольствие партнеру, поэтому, если в пылу использования партнер захочет получить внутреннюю стимуляцию, нет необходимости искать другое устройство.</font></font></p> <p><font><font>Два верхних мотора и один в ручке могут включаться независимо, каждый с очень интенсивной и сильной вибрацией, в зависимости от настройки.</font></font></p> <p><font><font>Вибратором легко управлять,&nbsp;на нем расположены две кнопки, верхняя часть включает двойную моторную часть при нажатии, а первая управляет той, которая находится в ручке.&nbsp;После включения можно перемещать программу вибрации кратковременным нажатием на нее, соответствующая функция отключается при длительном удерживании.</font></font></p> <p><font><font>Multifun&nbsp;полностью водонепроницаем и, конечно же, оснащен аккумулятором.&nbsp;Зарядка осуществляется с помощью USB-кабеля с магнитным подключением на другом конце.</font></font></p> <p><font><font>В коробке вы даже найдете руководство по пиктограммам, которое предлагает ряд идей для использования.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>156 г</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Мужчина, Женщина</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Анальный, Клиторальный, Пенис, Вагинальный</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,4-7,2 см</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>21 см</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Черный</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39580)
        {
            $desc='<p>&laquo;Multifun&raquo; &mdash; Многофункцональная игрушка-вибратор, которая подходит и для мужчин и для женщин, а самое главное, она является идеальным дополнением к парным секс играм. Благодаря мягкому силиконовому кончику, в виде раздвоенных отростков, поворачивающемуся на 180&deg; эту многофункциональную игрушку удобно применять для разных поз.&nbsp;<font><font>&nbsp;</font></font></p> <p><font><font>В вибраторе есть третий мотор, поэтому он может функционировать как почти классический вибратор, доставляя удовольствие партнеру, поэтому, если в пылу использования партнер захочет получить внутреннюю стимуляцию, нет необходимости искать другое устройство.</font></font></p> <p><font><font>Два верхних мотора и один в ручке могут включаться независимо, каждый с очень интенсивной и сильной вибрацией, в зависимости от настройки.</font></font></p> <p><font><font>Вибратором легко управлять,&nbsp;на нем расположены две кнопки, верхняя часть включает двойную моторную часть при нажатии, а первая управляет той, которая находится в ручке.&nbsp;После включения можно перемещать программу вибрации кратковременным нажатием на нее, соответствующая функция отключается при длительном удерживании.</font></font></p> <p><font><font>Multifun&nbsp;полностью водонепроницаем и, конечно же, оснащен аккумулятором.&nbsp;Зарядка осуществляется с помощью USB-кабеля с магнитным подключением на другом конце.</font></font></p> <p><font><font>В коробке вы даже найдете руководство по пиктограммам, которое предлагает ряд идей для использования.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>156 г</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Мужчина, Женщина</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Анальный, Клиторальный, Пенис, Вагинальный</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>5,4-7,2 см</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>21 см</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Синий</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==39581)
        {
            $desc='<p><font><font>Satisfyer Mono Flex стимулирует как клитор, так и точку G чувственными вибрациями - в том числе через приложение!&nbsp;</font><font>Вибратор-кролик изготовлен из высококачественного гибкого силикона, который плавно адаптируется к вашим контурам и передает интенсивные вибрации в чувствительные точки.&nbsp;</font><font>Вибрациями можно интуитивно управлять с помощью панели управления или бесплатного приложения Satisfyer Connect.&nbsp;</font><font>Вы можете управлять Mono Flex удаленно через приложение, а также создавать новые программы вибрации или связывать вибратор со своим любимым списком воспроизведения на Spotify.&nbsp;</font><font>Возможности безграничны!</font></font><br /> <br /> <font><font>Утонченный дизайн делает Satisfyer Mono Flex по-настоящему притягательным: он слишком красив, чтобы спрятаться в прикроватном ящике!&nbsp;</font><font>Вам нравится проводить время в одиночестве в душе или ванне?&nbsp;</font><font>Satisfyer Mono Flex является водонепроницаемым (IPX7), поэтому вы можете использовать его,&nbsp;и его легко чистить теплой водой с мягким мылом.&nbsp;</font><font>Если ваш&nbsp;вибратор-кролик разрядился, встроенные батареи можно заряжать с помощью прилагаемого USB-кабеля для зарядки.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>Характеристики:</p> <ul> <li><font><font>Высокая гибкость.</font></font></li> <li><font><font>Совместимость с бесплатным приложением Satisfyer - доступно для iOS и Android.</font></font></li> <li><font><font>2 мощных двигателя передают интенсивные ритмы вибрации по всей игрушке</font></font></li> <li><font><font>Стимуляция клитора и точки G</font></font></li> <li><font><font>2 двигателя с независимым управлением</font></font></li> <li><font><font>Приятный для тела силикон</font></font></li> <li><font><font>Водонепроницаемый (IPX7)</font></font></li> <li><font><font>Также можно использовать без приложения</font></font></li> <li><font><font>Предустановленные программы можно редактировать</font></font></li> <li><font><font>Приложение предлагает бесконечный выбор программ</font></font></li> <li><font><font>Литий-ионный аккумулятор</font></font></li> <li><font><font>Магнитный USB-кабель для зарядки в комплекте</font></font></li> <li><font><font>Легко очистить</font></font></li> <li><font><font>Силиконовая технология Flex</font></font></li> <li>Длина общая - 20,4<font><font>&nbsp;см</font></font></li> <li><font><font>Рабочая длина - 12&nbsp;см</font></font></li> <li><font><font>Диаметр</font></font>&nbsp;-&nbsp;<font><font>3,5 см</font></font></li> </ul>';
        }
        if ($id==39583)
        {
            $desc='<p><font>Satisfyer Mono Flex одновременно стимулирует и точку G&nbsp;<strong>и&nbsp;</strong>клитор с помощью чувственных вибраций!&nbsp;Вибратор-кролик изготовлен из высококачественного гибкого силикона, который прекрасно адаптируется анатомическим особенностям и передает интенсивные вибрации глубоко в самые чувственные зоны.&nbsp;Вибрациями можно интуитивно управлять с помощью панели управления или бесплатного приложения Satisfyer Connect.&nbsp;</font><font>Вы можете управлять Mono Flex удаленно через приложение, а также создавать новые программы вибрации или связывать вибратор со своим любимым списком воспроизведения на Spotify.&nbsp;</font><font>Возможности безграничны!</font></p> <p><font>Утонченный дизайн делает Satisfyer Mono Flex по-настоящему притягательным: он слишком красив, чтобы спрятаться в прикроватном ящике!&nbsp;</font><font>Вам нравится проводить время в одиночестве в душе или ванне?&nbsp;</font><font>Satisfyer Mono Flex является водонепроницаемым (IPX7), поэтому вы можете использовать его, когда вещи немного намокнут, и его легко чистить теплой водой с мягким мылом.&nbsp;</font><font>Если ваш кроличий вибратор испускает пар, встроенные батареи можно заряжать с помощью прилагаемого USB-кабеля для зарядки.</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>Характеристики:</p> <ul> <li><font><font>Высокая гибкость.</font></font></li> <li><font><font>Совместимость с бесплатным приложением Satisfyer - доступно для iOS и Android.</font></font></li> <li><font><font>2 мощных двигателя передают интенсивные ритмы вибрации по всей игрушке</font></font></li> <li><font><font>Стимуляция клитора и точки G</font></font></li> <li><font><font>2 двигателя с независимым управлением</font></font></li> <li><font><font>Приятный для тела силикон</font></font></li> <li><font><font>Водонепроницаемый (IPX7)</font></font></li> <li><font><font>Также можно использовать без приложения</font></font></li> <li><font><font>Предустановленные программы можно редактировать</font></font></li> <li><font><font>Приложение предлагает бесконечный выбор программ</font></font></li> <li><font><font>Литий-ионный аккумулятор</font></font></li> <li><font><font>Магнитный USB-кабель для зарядки в комплекте</font></font></li> <li><font><font>Легко очистить</font></font></li> <li><font><font>Силиконовая технология Flex</font></font></li> <li>Длина общая - 20,4<font><font>&nbsp;см</font></font></li> <li><font><font>Рабочая длина - 12&nbsp;см</font></font></li> <li><font><font>Диаметр</font></font>&nbsp;-&nbsp;<font><font>3,5 см</font></font></li> </ul>';
        }
        if ($id==39584)
        {
            $desc='<p><font><font>Этот мастурбатор для мужчин действительно вас удивит.&nbsp;</font><font>Гибкие крылышки из приятного для кожи бархатистого силикона искусно охватывают вашу головку, а мощные бороздки заставляют ваш член дрожать до самых яичек.&nbsp;</font><font>Вы можете выбирать между 10 различными ритмами и 5 уровнями интенсивности в зависимости от вашего настроения.&nbsp;</font><font>Это составляет 50 захватывающих дух комбинаций вибраций, которые дадут вам сольные кульминации, о которых вы даже не догадывались.</font></font></p> <p><font><font>Мужской вибратор-мастурбатор Satisfyer, конечно же, водонепроницаем, поэтому его&nbsp;можно использовать как в ванне, так и в душе.&nbsp;</font><font>Эргономичная форма и приятный материал позволяют ему идеально ложиться в руку.&nbsp;</font><font>Когда последние волны оргазма утихнут, вы можете легко очистить его водой с мылом или одним из наших очистителей для секс-игрушек.</font></font></p> <p><font>Satisfyer Men Wand можно использовать не только для удовольствия в одиночестве: вы также можете использовать ее по-разному, занимаясь любовью со своим партнером.&nbsp;</font><font>Например, вы можете приложить его к члену во время минета и насладиться захватывающим ощущением глубокого минета.&nbsp;</font><font>Вы также можете интегрировать его в различные сексуальные позы - просто поместите его на стержень вашего пениса, и ваш партнер сможет наслаждаться чудесными вибрациями вместе с вами во время занятий любовью.&nbsp;</font><font>Как бы вы ни использовали его, Satisfyer Men Wand подарит вам удивительные моменты волнения и невероятные оргазмы.</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><strong><font>Характеристики:</font></strong></p> <ul> <li><font><font>Материал:&nbsp;</font></font><font><font>АБС-пластик, Силикон</font></font></li> <li><font><font>Водонепроницаемый:&nbsp;</font></font><font><font>да</font></font></li> <li><font><font>Цвет:&nbsp;</font></font><font><font>Черный</font></font></li> <li><font><font>Аккумулятор:&nbsp;</font></font><font><font>Литий-ионный</font></font></li> <li><font><font>Длина:&nbsp;</font></font><font><font>20 см</font></font></li> <li><font><font>Ширина:&nbsp;</font></font><font><font>5,7 - 7,5 см</font></font></li> <li><font><font>С вибрацией:&nbsp;</font></font><font><font>да</font></font></li> <li><font><font>Стимуляция:&nbsp;</font></font><font><font>Пенис</font></font></li> <li><font><font>Подходит для:&nbsp;</font></font><font><font>мужчин</font></font></li> </ul>';
        }
        if ($id==39587)
        {
            $desc='<strong><strong>Satisfyer Endless Love&nbsp;</strong>, новая&nbsp;игрушка&nbsp;для пар, которые можно использовать соло и вместе.</strong> <p>Испытайте новые&nbsp;краски в своих сексуальных играх!<br /> Игрушка с тремя мощными моторами, а это с&nbsp;более чем 100 разных комбинаций вибраций в одной игрушке.<br /> Она была создана для новых ощущений и стимуляций в Вашей постели.&nbsp; Вы можете использовать его как с партнером, так и соло.&nbsp;Эргономичная&nbsp;форма&nbsp;&nbsp;<strong>Endless Joy&nbsp;</strong>&nbsp;идеально подходит&nbsp;для использования в качестве кольца, для клитора, сосков -&nbsp;&nbsp;доставляя им удовольствие от глубоких вибраций.&nbsp;</p> <p>Добавьте&nbsp;&nbsp;<strong>Endless Love&nbsp;</strong>в свои предварительные ласки, могут использовать его&nbsp;для стимуляции всех эрогенных зон.&nbsp;14 различных режимов вибраций&nbsp;доставят множество возможностей для многочасовой, разносторонней и увлекательной игры.</p> <p>Он полностью водонепроницаем и очень прост в уходе.</p> <p>Рекомендуется использовать со смазкой на водной основе.</p> <p>Характеристики:</p> <p>-водонепроницаемый (IPX7)</p> <p>- перезаряжаемый</p> <p>-&nbsp;10 различных&nbsp;уровней вибраций</p> <p>- 3 мощных мотора</p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя (&nbsp;<a href="https://us.satisfyer.com/" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39514)
        {
            $desc='<p><font><font>Satisfyer Curvy 3+ позволяет вам испытать незабываемые волны давления в сочетании с захватывающими дух вибрациями, которые подарят вам взрывной клиторальный оргазм.&nbsp;</font><font>Головка, сделанная из нежного силикона, стимулирует ваш клитор, не касаясь его, а различные ритмические вибрации вознесут вас на пик блаженства.&nbsp;</font><font>Этот симпатичный маленький аксессуар идеально ложится в вашу руку благодаря своей эргономичной форме и может заряжаться простым и экологически чистым способом с помощью литий-ионных аккумуляторов через магнитный USB-кабель для зарядки.</font></font><br /> <font><font>Вам нравится принимать ванну или душ?&nbsp;</font><font>Отлично!&nbsp;</font><font>Satisfyer Curvy 3+ водонепроницаем (IPX7) и будет радовать вас везде, где вы жаждете горячих волн возбуждения.&nbsp;</font><font>Его также легко очистить водой с мылом или дезинфицирующим средством для чистки устройств для сексуального здоровья.</font></font></p> <p><font><font>Особенностью этого компактного стимулятора является не только его эргономичная форма и впечатляющие характеристики, но и его способность вдохнуть новую жизнь в ваши занятия любовью с помощью приложения.&nbsp;</font></font></p> <p><font><font>Приложение Satisfyer Connect, которое доступно бесплатно для Android и iOS, позволяет подключить Satisfyer Curvy 3+ через Интернет или Bluetooth к вашему смартфону, который затем можно использовать в качестве пульта дистанционного управления.&nbsp;</font><font>Даже часы Apple Watch или планшет могут управлять стимуляцией клитора.&nbsp;</font><font>Вы можете преобразовать окружающие звуки в захватывающие вибрации, которые вы испытываете непосредственно через Satisfyer с помощью приложения.&nbsp;</font><font>Можно даже составлять целые плейлисты из Spotify в ритмы для вашего вибратора.&nbsp;</font></font></p> <p><font><font>Конечно, вы также можете передать управление своему партнеру или другим пользователям и позволить им стимулировать вас через приложение - в прямом эфире, удаленно или в видеочате.</font></font><br /> <font><font>Приложение Satisfyer Connect, конечно же, соответствует требованиям GDPR и немецким и европейским правилам защиты данных.&nbsp;</font><font>Мы используем непрерывное шифрование, поэтому ваши занятия любовью остаются между вами и вашим партнером.</font></font><br /> <font><font>Попробуйте Satisfyer Curvy 3+ с инновационным управлением через приложение и придайте занятиям любовью совершенно новое измерение!</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p><font><font>Особенности:</font></font></p> <ul> <li><font><font>Перезаряжаемый</font></font></li> <li><font><font>Водонепроницаемый IPX7</font></font></li> <li><font><font>Безопасный для тела силикон</font></font></li> <li>Тихий</li> <li><font><font>Совместимость с любым смартфоном, планшетом и Apple Watch на базе Android или Apple</font></font></li> <li><font><font>Бесконечный набор программ с приложением Satisfyer. Также можно использовать без приложения</font></font></li> </ul> <p><font><font>Характеристики:</font></font></p> <table> <tbody> <tr> <td>Длина (см)</td> <td>14,8</td> </tr> <tr> <td>Цвет</td> <td>розовый</td> </tr> <tr> <td>Диаметр (см)</td> <td>4,8</td> </tr> <tr> <td>Материал</td> <td>Силикон, Пластик</td> </tr> <tr> <td>Питание</td> <td>Встроенная литиевая батарея</td> </tr> </tbody> </table>';
        }
        if ($id==39542)
        {
            $desc='<p><font>Все женщины знают, что менструальный цикл не является развлечением, часто сопровождается&nbsp;болью и перепадами настроения, а также дискомфорт при использовании прокладок или тампонов.&nbsp;</font><font>Satisfyer предлагает решение всех этих проблем с этими менструальными чашами.</font></p> <p><font>Feel Secure Set состоит из 2 мягких и приятных на ощупь силиконовых менструальных чаш&nbsp;емкостью 15 и 20 мл. Их</font><font>&nbsp;бесшовная конструкция делает их очень простым в использовании, а закругленный шнур позволяет удобно вводить и извлекать. Их</font><font>&nbsp;медицинский силикон является гибким и идеально адаптируется к вашему телу и его изгибам, обеспечивая безопасную и гигиеническую защиту до 12 часов.&nbsp;</font><font>Еще одно преимущество - отказ от тампонов и прокладок, которые могут повлиять на вашу флору.</font></p> <p><font><font>Характеристики:</font></font></p> <ul> <li><font><font>Упаковка из двух чашек - 15 мл - 20 мл</font></font></li> <li><font><font>Медицинский силикон</font></font></li> <li><font><font>Подходит для начального и продвинутого уровней</font></font></li> <li><font><font>Удлиненный шнур</font></font></li> <li><font><font>Эластичны</font></font></li> <li><font><font>Легко очистить</font></font></li> <li><font><font>Время использования: до 12 часов</font></font></li> <li><font><font>Экологическая альтернатива</font></font></li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==39491)
        {
            $desc='<p><font><font>Satisfyer Mini Secret Affair - тайный, сексуальный и соблазнительный.&nbsp;В дополнение к высокому качеству изготовления и исключительному дизайну, этот вибратор является невероятно удобным в использовании.&nbsp;</font></font></p> <p><font><font>Благодаря гигиеническому колпачку он незаметно скрывается в сумочке и выглядит так же хорошо, как ваша любимая помада.</font></font></p> <p><font><font>Компактный размер этого мини-вибратора скрывает большую мощность: 10 различных моделей вибрации и 5 скоростей приведут в блаженство ваш клитор и подарит вам взрывной оргазм. </font></font></p> <p><font><font>Гладкая поверхность из нежного силикона приятна на ощупь и становится еще более гладкой с добавлением лубриканта.&nbsp;</font><font>Вибратор для клитора также можно использовать с надетым колпачком, что приведет к более острым ощущениям.</font></font></p> <p><font><font>Удобный размер Secret Affair делает его идеальным спутником в путешествиях - в отпуске, деловой поездке или поездке в город.&nbsp;</font></font></p> <p><font><font>Он будет прекрасным компаньоном в любое время и в любом месте - даже в душе или ванне.&nbsp;</font></font></p> <p><font><font>Благодаря водонепроницаемой (IPX7) отделке он также порадует вас в душе или ванне.&nbsp;</font></font></p> <p><font><font>После использования вы можете легко очистить мини вибратор с помощью мыла, теплой воды или средства для чистки секс игрушек.&nbsp;</font></font></p> <p><font><font>Аккумулятор мощного вибратора можно заряжать экологически безопасным способом с помощью прилагаемого USB-кабеля для зарядки.</font></font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушки&nbsp;обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам окажут гарантийное обслуживание.</strong></p> <p>Материал:&nbsp;&nbsp; &nbsp;АБС-пластик, Силикон<br /> Водонепроницаемый:&nbsp;&nbsp; &nbsp;да<br /> Аккумулятор:&nbsp;&nbsp; &nbsp;<font><font>литий-ионные батареи</font></font><br /> Ширина:&nbsp;&nbsp; &nbsp;3 см<br /> С вибрацией:&nbsp;&nbsp; &nbsp;да<br /> Стимуляция:&nbsp;&nbsp; &nbsp;Клиторальный, Вагинальный<br /> Подходит для:&nbsp;&nbsp; &nbsp;Женщин<br /> Цвет:&nbsp;&nbsp; &nbsp;Белый<br /> Длина:&nbsp;&nbsp; &nbsp;11,4 см<br /> Вес:&nbsp; &nbsp;100 г</p>';
        }
        if ($id==39494)
        {
            $desc='<p><font>Кольцо Signet Ring&nbsp;ограничивает кровоток и продлевает занятия любовью с партнером.&nbsp;Кольцо для члена сделано из силикона, поэтому оно плавно и гибко прилегает к пенису.&nbsp;Ограничение кровотока от полового члена задерживает эякуляцию и усиливает эрекцию, в то время как ваш партнер наслаждается сильной вибрацией устройства.&nbsp;</font><font>Рифленая структура обеспечивает дополнительную стимуляцию клитора.&nbsp;</font><font>Вибрациями Signet Ring можно интуитивно управлять с помощью кнопки One Touch или приложения Satisfyer Connect.&nbsp;</font><font>Приложение предлагает безграничные возможности для занятий любовью - вы можете управлять Signet Ring со своего смартфона или создавать свои собственные программы вибрации.</font></p> <p><font>Кольцо Signet Ring является водонепроницаемым (IPX7), поэтому оно также может обогатить ваши занятия любовью в душе или ванне своими мощными вибрациями.&nbsp;</font><font>Его можно легко очистить теплой водой с мягким мылом - для тщательной гигиены используйте дезинфицирующий спрей, который идеально подходит для удобного для тела материала кольца.&nbsp;</font><font>Вы можете зарядить батареи, встроенные в кольцо для петуха, с помощью прилагаемого USB-кабеля для зарядки.</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушки&nbsp;обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам окажут гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Черный</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>4,5 см</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>8,3 см</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Совместимость с приложением Satisfyer Connect:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Пенис</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Пары, Мужчина</font></font></td> </tr> </tbody> </table> <p>&nbsp;</p>';
        }
        if ($id==39543)
        {
            $desc='<p><font>Все женщины знают, что менструальный цикл не является развлечением, часто сопровождается&nbsp;болью и перепадами настроения, а также дискомфорт при использовании прокладок или тампонов.&nbsp;</font><font>Satisfyer предлагает решение всех этих проблем с этими менструальными чашами.</font></p> <p><font>Feel Secure Set состоит из 2 мягких и приятных на ощупь силиконовых менструальных чаш&nbsp;емкостью 15 и 20 мл. Их</font><font>&nbsp;бесшовная конструкция делает их очень простым в использовании, а закругленный шнур позволяет удобно вводить и извлекать. Их</font><font>&nbsp;медицинский силикон является гибким и идеально адаптируется к вашему телу и его изгибам, обеспечивая безопасную и гигиеническую защиту до 12 часов.&nbsp;</font><font>Еще одно преимущество - отказ от тампонов и прокладок, которые могут повлиять на вашу флору.</font></p> <p><font><font>Характеристики:</font></font></p> <ul> <li><font><font>Упаковка из двух чашек - 15 мл - 20 мл</font></font></li> <li><font><font>Медицинский силикон</font></font></li> <li><font><font>Подходит для начального и продвинутого уровней</font></font></li> <li><font><font>Удлиненный шнур</font></font></li> <li><font><font>Эластичны</font></font></li> <li><font><font>Легко очистить</font></font></li> <li><font><font>Время использования: до 12 часов</font></font></li> <li><font><font>Экологическая альтернатива</font></font></li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==26392)
        {
            $desc='<p><font>Satisfyer Pro 2 оснащен нашей инновационной технологией Satisfyer Airpulse, которая возбуждает ваш клитор нежными или интенсивными волнами давления.&nbsp;</font><font>Этот бестселлер подарит вам такие взрывные оргазмы, которые вы не сможете получить в достаточной мере.&nbsp;</font><font>Никогда еще не было так легко поддерживать баланс своего сексуального здоровья.</font></p> <p><font><font>Наряду с невиданными ранее особенностями Satisfyer Pro 2 следующего поколения имеет оптимизированный дизайн кнопок, который позволяет вам перемещаться по 11 захватывающим уровням волн давления.&nbsp;</font><font>Он также водонепроницаем и может незаметно использоваться в ванне или между простынями благодаря бесшумному двигателю.&nbsp;</font><font>Удобное зарядное устройство USB легко заряжает аккумулятор, сохраняя Satisfyer Pro 2 готовым к следующему сеансу.</font></font></p> <p><font><font>Мягкая головка из гигиенического силикона больше и шире, поэтому она еще лучше обхватывает клитор, даря неповторимые ощущения.&nbsp;</font><font>Его также легко мыть водой с мылом или стерильным очистителем, предназначенным для секс-игрушек.&nbsp;</font></font></p> <table> <tbody> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>6.5 &Prime;, 165 мм</font></font></td> </tr> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Розовое золото</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>248 г</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>1,8 &Prime;, 46 мм</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>2,6 &Prime;, 65 мм</font></font></td> </tr> <tr> <td><font><font>С волнами давления:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Женщин</font></font></td> </tr> </tbody> </table> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>&nbsp;</p> <p>&nbsp;</p>';
        }
        if ($id==29375)
        {
            $desc='<p><font><font>Как и его старшие братья и сестры Satisfyer Pro 2 и Satisfyer Penguin, этот вибратор с волнами давления доставит вам захватывающие оргазмы, но в удобной форме для путешествий.&nbsp;</font><font>Если вы в командировке или в отпуске, Satisfyer Traveler легко поместится в вашей ручной клади, а стильный дизайн означает, что вы можете оставаться незаметным.&nbsp;</font><font>Не было никаких компромиссов в отношении потрясающей технологии внутри, что сделало Satisfyer Traveler настоящим продуктом для сексуального образа жизни.&nbsp;</font><font>Так что вы можете быть уверены в своем сексуальном благополучии, куда бы вы ни пошли.</font></font></p> <p><font><font>Независимо от того, часто ли вы летаете или предпочитаете автомобильные путешествия, стильная магнитная крышка&nbsp;закрывает ваш Satisfyer во время путешествия, делая его незаметным и гигиеничным аксессуаром.&nbsp;</font><font>Этот удобный вибратор с волнами давления с 11 программами заставит вашу кровь работать быстрее.&nbsp;</font><font>А благодаря водонепроницаемой конструкции вы даже можете наслаждаться пульсирующим оргазмом даже в ванной в отеле.&nbsp;</font><font>Встроенные аккумуляторные батареи легко заряжать с помощью магнитного зарядного USB-кабеля, поэтому он всегда готов к вашей следующей захватывающей поездке.</font></font></p> <p><font><font>Мягкая насадка, которая укрывается вокруг вашей маленькой жемчужины, сделана из медицинского силикона и обеспечивает гладкую и гладкую поверхность.&nbsp;</font><font>Смешайте этот приятный материал с несколькими каплями лубриканта на водной основе, чтобы усилить захватывающие впечатления от Satisfyer Traveler.&nbsp;</font><font>Приятный для кожи силикон можно легко очистить с помощью небольшого количества воды с мылом.&nbsp;</font><font>Или вы также можете использовать дезинфицирующее средство для секс-игрушек.</font></font></p> <p><font>Характеристики:</font></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>192 г</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>3,9 &Prime;, 100 мм</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>1,4 &Prime;, 35 мм</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Фиолетовый/ розовое золото</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>С волнами давления:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Женщин</font></font></td> </tr> </tbody> </table> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==29177)
        {
            $desc='<p>Крайне необычное, но весьма эффективное решение для стимуляции женщины, элегантность которого прекрасные дамы несомненно оценят по достоинству.</p> <p>Вибромассажер Satisfyer Pro G-Spot Rabbit совмещает в себе возможности классического вагинального вибратора и вакуумного клиторального стимулятора.</p> <p>Ствол вводится в вагину таким образом, чтобы вакуумная манжета накрыла клитор, -&nbsp;&nbsp;теперь остается поудобнее расположить девайс и погрузиться в бесконечный поток наслаждения.</p> <p>Вагинальная часть устроена таким образом, чтобы лучше стимулировать точку G, что еще больше усиливает производимый эффект. Девайс содержит 2 независимых мотора, способных работать в различных режимах и комбинациях, поэтому устройство поддерживает 7 настроек вибрации и 3 скоростных режима.</p> <p>Корпус полностью водонепроницаем, что делает игрушку идеальным вариантом для использования в ванне или душе. Длина аксессуара 22 см., диаметр 4,3 см. Корпус изготовлен из&nbsp;ABS-пластика, уплотнительная насадка из гипоаллергенного силикона.</p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>Характеристики:</p> <ul> <li><font><font>Высокая гибкость.</font></font></li> <li>Длина общая - 22<font><font>&nbsp;см</font></font></li> <li><font><font>Диаметр</font></font>&nbsp;-&nbsp;<font><font>4,3&nbsp;см</font></font></li> </ul>';
        }
        if ($id==27876)
        {
            $desc='<p><font>Вибратор для клитора имеет изысканный дизайн из розового золота.&nbsp;</font><font>Благодаря эргономичной форме Number One удобно лежит в руке и бесконтактно стимулирует клитор, используя 11 захватывающих дух интенсивностей, которые вы можете легко регулировать вверх и вниз с помощью кнопок +/-.&nbsp;</font><font>Нежный силикон медицинского качества и приятный для кожи силикон делает этот драгоценный камень чрезвычайно приятным для вашей кожи и доставит вам часы удовольствия.</font></p> <p><font>Вибратор для клитора является брызгоотталкивающим (IPX7), поэтому вы можете наслаждаться им не только на диване или в постели, но и в душе (но не следует погружать стимулятор в воду).&nbsp;</font><font>Мотор работает бесшумно, поэтому вы можете доставить себе удовольствие, не отвлекаясь.&nbsp;</font><font>Вы можете легко заменить батарею по мере необходимости, чтобы взять Satisfyer Number One в дорогу и быть готовым к эротическому сеансу в любое время.&nbsp;</font><font>Когда дрожь во время кульминации утихнет, ее можно будет легко очистить теплой водой с мылом или средством для чистки устройств для сексуального здоровья.&nbsp;</font></p> <p><font><font>Характеристики:</font></font></p> <ul> <li> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Розовое золото</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>1,8 &Prime;, 46 мм</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>3,1 унции, 88 г</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>2 батарейки AAA (в комплект не входят)</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>5,6 &Prime;, 143 мм</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>2,1 &Prime;, 54 мм</font></font></td> </tr> <tr> <td><font><font>С волнами давления:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Женщина</font></font></td> </tr> </tbody> </table> </li> </ul> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==31524)
        {
            $desc='<p><font>Этот элегантный черный вибратор для клитора стимулирует вас 11 уровнями волн давления и сочетает в себе 10 захватывающих ритмов вибрации.&nbsp;</font><font>Благодаря бархатистой насадке из мягкого силикона он нежно окружает ваш клитор и бесконтактно.&nbsp;</font><font>Волнами давления и ритмами вибрации можно управлять по отдельности, предлагая 110 захватывающих возможных комбинаций, поэтому с этим чудотворцем никогда не бывает скучно.&nbsp;</font><font>Высокая производительность Satisfyer Pro 3+ отправит вас в подергивающий экстаз, даря вам оргазм, которых вы никогда раньше не испытывали.&nbsp;</font><font>Элегантный дизайн и акценты из розового золота этого вибратора для клитора также делают его идеальным подарком для вашего лучшего друга.</font></p> <p><font>Satisfyer Pro 3+ не только дает вам свободный выбор сочетания волн давления и вибрации, вы можете наслаждаться им где угодно благодаря водонепроницаемой (IPX7) отделке.&nbsp;</font><font>Будь то в ванной, душе или между удобными простынями - это устройство для удовольствия всегда готово катапультировать вас к счастью.&nbsp;</font><font>После использования вы можете легко очистить свое маленькое украшение с помощью небольшого количества мыла и теплой воды или очистителя для устройств для сексуального здоровья.&nbsp;</font><font>Встроенные литий-ионные батареи можно заряжать экологически безопасным способом с помощью прилагаемого USB-кабеля для зарядки.&nbsp;</font><font>Для еще большего удовольствия используйте немного лубриканта на водной основе Satisfyer от - вы определенно получите удовольствие от влажных и приятных ощущений.</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Клиторальный</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>С волнами давления:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>113 г</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>5,7 &Prime;, 145 мм</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>1,7 &Prime;, 43 мм</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>1,9 &Prime;, 48 мм</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Черный, розовое золото</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>Женщин</font></font></td> </tr> </tbody> </table>';
        }
        if ($id==31523)
        {
            $desc='<p><font><font>&nbsp;</font><font>Satisfyer Men Heat Vibration исполнит ваши желания, когда и где вы хотите!&nbsp;</font><font>Этот вибрационный мастурбатор для современного мужчины оснащен инновационной функцией нагрева и делает ваше времяпрепровождение в одиночестве захватывающе реалистичным.&nbsp;</font><font>Наслаждайтесь уютным теплом этого мягкого мастурбатора и отправляйтесь во взрывные кульминационные оргазмы при температуре до 40 градусов.&nbsp;</font><font>Взаимодействие захватывающей функции нагрева и возбуждающих мощных вибраций обеспечивает возбуждающую стимуляцию головки полового члена.</font></font></p> <p><font><font>Men Heat Vibration сочетает в себе мужской дизайн с простым, интуитивно понятным управлением - с помощью кнопок включения / выключения и +/- вы можете удобно управлять им одной рукой.&nbsp;</font><font>Благодаря широкому отверстию вы можете использовать мастурбатор, даже когда ваш пенис не находится в состоянии эрекции, и мгновенно согреться от 0 до 100.</font></font><br /> <font><font>Погрузитесь в свои фантазии и получите удовольствие от 11 различных уровней вибрации.</font></font></p> <p><font>Men Heat Vibration водонепроницаем (IPX7) и может подарить вам такой теплый экстаз, который вы хотите даже в душе или ванне.&nbsp;</font><font>После использования материал из нежного силикона и АБС-пластика можно легко очистить теплой водой с мылом или дезинфицирующим средством для чистки секс-игрушек.&nbsp;</font><font>Встроенные аккумуляторы можно заряжать с помощью прилагаемого магнитного USB-кабеля для зарядки.&nbsp;</font><font>Сексуальное благополучие для мужчин еще никогда не было таким простым - попробуйте сейчас и получите горячее удовольствие, которого вы заслуживаете!&nbsp;</font></p> <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя (<a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p> <p>Характеристики:</p> <table> <tbody> <tr> <td><font><font>Материал:</font></font></td> <td><font><font>АБС-пластик, Силикон</font></font></td> </tr> <tr> <td><font><font>Водонепроницаемый:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Цвет:</font></font></td> <td><font><font>Черный</font></font></td> </tr> <tr> <td><font><font>Ширина:</font></font></td> <td><font><font>3,1 &Prime;, 78 мм</font></font></td> </tr> <tr> <td><font><font>Вес:</font></font></td> <td><font><font>6,8 унций, 193 г</font></font></td> </tr> <tr> <td><font><font>Аккумулятор:</font></font></td> <td><font><font>Литий-ионный</font></font></td> </tr> <tr> <td><font><font>Длина:</font></font></td> <td><font><font>3,3 &Prime;, 83 мм</font></font></td> </tr> <tr> <td><font><font>Высота:</font></font></td> <td><font><font>5,5 &Prime;, 140 мм</font></font></td> </tr> <tr> <td><font><font>С вибрацией:</font></font></td> <td><font><font>да</font></font></td> </tr> <tr> <td><font><font>Стимуляция:</font></font></td> <td><font><font>Пенис</font></font></td> </tr> <tr> <td><font><font>Подходит для:</font></font></td> <td><font><font>мужчин</font></font></td> </tr> </tbody> </table> <ul> </ul>';
        }
        if ($id==39556)
        {
            $desc='<p>Вибромассажер Satisfyer Wand-er Woman приглашает Вас в мир удовольствия всего тела.&nbsp;Его обтекаемая форма и&nbsp;размер специально разработаны для целевого давления, которое снимает напряжение и снимает стресс со всего тела.&nbsp;Wand-er Woman соблазнит Вас и&nbsp;Вашего любовника захватывающей дух стимуляцией ваших самых интимных зон.</p>
            <p>Мотор&nbsp;расположен в&nbsp;головке массажёра,&nbsp;очень мощный, и сможет довести до&nbsp;оргазма без особой прелюдии,&nbsp;и даже через одежду.&nbsp;</p>
            <p>Satisfyer Wand-er Woman идеально подходит для игры соло и с партнером. Работает&nbsp;очень тихо, а благодаря водонепроницаемой конструкции его можно использовать даже в ванне.&nbsp;</p>
            <p>Габариты<em>:</em> длина 34&nbsp;см, ширина 5,7&nbsp;см.</p>
            <p>Характеристики:</p>
            <ul>
            <li>стимуляция клитора и других эрогенных зон;</li>
            <li>силиконовое покрытие;</li>
            <li>10 режимов вибрации + 5 уровней интенсивности;</li>
            <li>мощный мотор для интенсивного воздействия;</li>
            <li>полная водонепроницаемость;</li>
            <li>перезаряжаемый.</li>
            </ul>
            <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a data-cke-saved-href="https://us.satisfyer.com/" href="https://us.satisfyer.com/" rel="noopener noreferrer" target="_blank">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>';
        }
        if ($id==40875)
        {
            $desc='<p>Satisfyer Yummy Sunshine может многое предложить: ребристая структура, сильные вибрации и гладкий стержень из твердого силикона - все это в игривом и веселом дизайне. Изогнутый вибратор непосредственно воздействует на вашу точку G, стимулируя вас своей чувственной ребристой текстурой. Вы можете выбрать из 12 программ вибрации, состоящих из 6 уровней интенсивности и 6 моделей вибрации.</p>
            <p>Благодаря стержню из высококачественного твердого силикона Yummy Sunshine чрезвычайно гибок и точно адаптируется к контурам вашего тела. Благодаря инновационной технологии Silicone Flex он передает вибрации на точку G с потрясающей интенсивностью, а благодаря практичному кольцу на ручке у вас всегда все под контролем.<br />
            Водонепроницаемая (IPX7) отделка означает, что он также может присоединиться к вам во влажных помещениях, таких как душ или ванна.</p>     
            <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя (<a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>            
            <p><b>Характеристики:</b></p>            
            <table>
                <tbody>
                    <tr>
                        <td><font><font>Материал:</font></font></td>
                        <td><font><font>АБС-пластик, Силикон</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Водонепроницаемый:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Высота:</font></font></td>
                        <td><font><font>2,2 &Prime;, 57,27 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Аккумулятор:</font></font></td>
                        <td><font><font>Литий-ионный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Масса:</font></font></td>
                        <td><font><font>9,6 унций, 272 г</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Длина:</font></font></td>
                        <td><font><font>8,9 &Prime;, 225,69 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Ширина:</font></font></td>
                        <td><font><font>1,7 &Prime;, 41,77 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Цвет:</font></font></td>
                        <td><font><font>Желтый</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>С вибрацией:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Стимуляция:</font></font></td>
                        <td><font><font>Вагинальный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Подходит для:</font></font></td>
                        <td><font><font>Женщин</font></font></td>
                    </tr>
                </tbody>
            </table>';
        }
        if ($id==40876)
        {
            $desc='<p>Мистер Кролик легко сочетает вагинальную и клиторальную стимуляцию с дополнительной вибрацией. Его шелковисто-матовая поверхность, гибкий стержень и стимулятор клитора элегантно прилегают к вашим изгибам, обеспечивая максимальную стимуляцию и бесконечное удовольствие.&nbsp;</p>
            <p>Этот прекрасный вибратор будет&nbsp;стимулировать ваше влагалище и клитор с помощью 2 двигателей. Более крупный, слегка изогнутый стержень обеспечивает искусную стимуляцию точки G, а меньший и более узкий стимулятор идеально подходит для стимуляции клитора. Весь корпус устройства сделан из качественного, мягкого, прочного силикона, который передает мощные и интенсивные вибрации на ваши чувственные зоны!</p>            
            <p>Даже когда вы будете достигать пика удовольствия, вибратор всегда будет под контролем благодаря практичному кольцу на ручке. Вибратором можно легко управлять с помощью интуитивно понятной панели управления, которая предлагает 12 программ вибрации. Программы состоят из 6 уровней интенсивности и 6 ритмов!&nbsp;</p>            
            <p>Mr.Rabbit водонепроницаем (IPX7), поэтому он может доставить вам удовольствие в душе или ванне. В случае разрядки вибратора, встроенные аккумуляторы можно зарядить с помощью прилагаемого USB-кабеля для зарядки. Гигиенический силикон можно легко очистить с помощью дезинфицирующего средства для чистки устройств для сексуального здоровья.</p>            
            <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>            
            <p><b>Характеристики:</b></p>            
            <table>
                <tbody>
                    <tr>
                        <td><font><font>Материал:</font></font></td>
                        <td><font><font>АБС-пластик, Силикон</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Водонепроницаемый:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Аккумулятор:</font></font></td>
                        <td><font><font>Литий-ионный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Цвет:</font></font></td>
                        <td><font><font>Розовый</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Высота:</font></font></td>
                        <td><font><font>1,6 &Prime;, 41,57 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Длина:</font></font></td>
                        <td><font><font>8,7 &Prime;, 221,48 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Масса:</font></font></td>
                        <td><font><font>272 г</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Ширина:</font></font></td>
                        <td><font><font>3,2 &Prime;, 81,41 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>С вибрацией:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Стимуляция:</font></font></td>
                        <td><font><font>Вагинальная</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Подходит для:</font></font></td>
                        <td><font><font>Женщин</font></font></td>
                    </tr>
                </tbody>
            </table>';
        }
        if ($id==40874)
        {
            $desc='<p><font>Игривый дизайн Petting Hippo предлагает самую глубокую стимуляцию точки G, о которой вы всегда мечтали.&nbsp;</font><font>Благодаря глубокой вибрации, проходящей через объемный наконечник и гибкий стержень, эта вибрация точки G доводит вас до экстаза.</font></p>            
            <p><font><font>Satisfyer Petting Hippo стимулирует точку G&nbsp;изогнутым наконечником и в дизайне&nbsp;бегемота.&nbsp;</font><font>Гладкий стержень из высококачественного силикона и технология Silicone Flex делают его особенно гибким, передавая сильные вибрации прямо на горячую точку.&nbsp;</font></font></p>            
            <p><font><font>Благодаря 12 программам вибрации он удовлетворит все ваши желания!</font></font></p>            
            <p><font><font>Практичное кольцо на ручке упрощает обращение с ним: так что вы всегда контролируете свой вибратор.</font></font></p>            
            <p><font><font>Благодаря водонепроницаемости (IPX7) Petting Hippo&nbsp;может присоединиться к вам во влажных помещениях, таких как душ или ванна.&nbsp;</font><font>Для очистки вашего Petting Hippo, помимо очистки поверхности водой с мягким мылом, мы рекомендуем тщательную дезинфекцию с помощью дезинфицирующего спрея Satisfyer.&nbsp;</font><font>Спрей идеально сочетается с мягким материалом и делает силиконовую поверхность особенно эластичной.</font></font></p>            
            <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя ( <a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>            
            <p><b>Характеристики:</b></p>    
            <table>
                <tbody>
                    <tr>
                        <td><font><font>Материал:</font></font></td>
                        <td><font><font>АБС-пластик, Силикон</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Водонепроницаемый:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Ширина:</font></font></td>
                        <td><font><font>2,3 &Prime;, 58,24 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Аккумулятор:</font></font></td>
                        <td><font><font>Литий-ионный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Высота:</font></font></td>
                        <td><font><font>1,6 &Prime;, 40,13 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Длина:</font></font></td>
                        <td><font><font>9 &Prime;, 229,19 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Масса:</font></font></td>
                        <td><font><font>&nbsp;285 г</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Цвет:</font></font></td>
                        <td><font><font>Розовый</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>С вибрацией:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Стимуляция:</font></font></td>
                        <td><font><font>Вагинальный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Подходит для:</font></font></td>
                        <td><font><font>Женщин</font></font></td>
                    </tr>
                </tbody>
            </table>';
        }
        if ($id==30622)
        {
            $desc='<p><font>Позвольте мягким лепесткам Power Flower танцевать вокруг ваших чувствительных зон, пока вибрации шелковистого силиконового вибратора погружают вас в экстаз.&nbsp;</font><font>Он идеально подходит для&nbsp;пробуждения эрогенных зон!</font></p>
            <p><font>Power Flower от Satisfyer вдохновит вас своими игривыми &quot;язычками&quot;, напоминающими романтические лепестки, которые возбуждают клитор трепещущими вибрациями. Благодаря выбору из 12 программ вибрации, вибратор особенно мощный, стимулируя вас от мягких до интенсивных вибраций - с выбором между 6 уровнями интенсивности и 6 моделями вибрации.<br />
            Поскольку Power Flower изготовлен из высококачественного силикона, он особенно универсален в использовании и точно адаптируется к контурам вашего тела. Технология Silicone Flex с особой интенсивностью передает вибрации к вашим чувственным зонам.</font></p>            
            <p><font>Практичное кольцо на ручке позволяет держать вибратор под контролем, даже когда вы достигаете пиков наслаждения. Благодаря водонепроницаемости (IPX7) Power Flower может доставить вам удовольствие в душе или ванне, а также его легко чистить.&nbsp;</font></p>            
            <p><strong>Компания Satisfyer предоставляет на свои товары 15 лет гарантии. В случае неисправности игрушка обращайтесь на сайт производителя (<a href="https://us.satisfyer.com/">https://us.satisfyer.com/</a>) и вам обеспечат гарантийное обслуживание.</strong></p>            
            <p><b>Характеристики:</b></p>
            
            <table>
                <tbody>
                    <tr>
                        <td><font><font>Материал:</font></font></td>
                        <td><font><font>АБС-пластик, Силикон</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>С вибрацией:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Ширина:</font></font></td>
                        <td><font><font>2 &Prime;, 52,51 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Длина:</font></font></td>
                        <td><font><font>7,4 &Prime;, 188,46 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Масса:</font></font></td>
                        <td><font><font>7,3 унции, 206,5 г</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Подходит для:</font></font></td>
                        <td><font><font>Женщин</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Стимуляция:</font></font></td>
                        <td><font><font>Клиторальный, Вагинальный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Цвет:</font></font></td>
                        <td><font><font>красный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Высота:</font></font></td>
                        <td><font><font>1,6 &Prime;, 39,71 мм</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Аккумулятор:</font></font></td>
                        <td><font><font>Литий-ионный</font></font></td>
                    </tr>
                    <tr>
                        <td><font><font>Водонепроницаемый:</font></font></td>
                        <td><font><font>да</font></font></td>
                    </tr>
                </tbody>
            </table>';
        }
        $itemHead=preg_replace("#<description>(.*?)<\/description>#s","<description>$desc</description>",$itemHead);
        return $itemHead;
        
    }

    private function setSatsisfyerCategory($item,$id)
    {
        if ($id==39543||$id==39543||$id==39542||$id==39548||$id==39537||$id==39539||$id==39538)
        {
            $item=preg_replace("#<categoryId>(.*?)<\/categoryId>#s","<categoryId>244</categoryId>",$item);
        }
        else
        {
            $item=preg_replace("#<categoryId>(.*?)<\/categoryId>#s","<categoryId>166</categoryId>",$item);
        }
        return $item;
    }

    private function getSatisfyerParams($id)
    {
        if ($id==33861)
        {
            $params='<param name="Тип интимной игрушки">Анальные шарики</param>
            <param name="Пол">Унисекс</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Нет</param>
            <param name="Водостойкость">Да</param>
            <param name="Цвет">Разные цвета</param>
            <param name="Длина" unit="мм">205</param>
            <param name="Диаметр" unit="мм">33</param>
            <param name="Вес" unit="г">118</param>';
        }
        if ($id==27877)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Нет</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Пульсация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">108</param>
            <param name="Ширина" unit="мм">58</param>
            <param name="Высота" unit="мм">55</param>
            <param name="Вес" unit="г">101</param>';
        }
        if ($id==33860)
        {
            $params='<param name="Тип интимной игрушки">Анальные шарики</param>
            <param name="Пол">Унисекс</param>
            <param name="Материал">Силикон</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">205</param>
            <param name="Диаметр" unit="мм">33</param>
            <param name="Вес" unit="г">118</param>';
        }
        if ($id==39492)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">114</param>
            <param name="Диаметр" unit="мм">30</param>
            <param name="Вес" unit="г">100</param>';
        }
        if ($id==39493)
        {
            $params='<param name="Тип интимной игрушки">Эрекционное кольцо</param>
            <param name="Пол">Мужской</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Синий</param>
            <param name="Длина" unit="мм">75</param>
            <param name="Ширина" unit="мм">52</param>
            <param name="Высота" unit="мм">32</param>';
        }
        if ($id==39498)
        {
            $params='<param name="Тип интимной игрушки">Эрекционное кольцо</param>
            <param name="Пол">Мужской</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">74</param>
            <param name="Ширина" unit="мм">52</param>
            <param name="Высота" unit="мм">34</param>';
        }
        if ($id==39508)
        {
            $params='<param name="Тип интимной игрушки">Вагинальные шарики</param>
            <param name="Пол">Женский</param>
            <param name="Материал">Силикон</param>
            <param name="Водостойкость">Да</param>
            <param name="Цвет">Разные цвета</param>
            <param name="Длина" unit="мм">176</param>
            <param name="Диаметр" unit="мм">34</param>
            <param name="Вес" unit="г">349</param>';
        }
        if ($id==39512)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Красный</param>
            <param name="Длина" unit="мм">165</param>
            <param name="Диаметр" unit="мм">37</param>';
        }
        if ($id==39516)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Розовый</param>
            <param name="Длина" unit="мм">148</param>
            <param name="Диаметр" unit="мм">48</param>';
        }
        if ($id==39510)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Желтый</param>
            <param name="Длина" unit="мм">165</param>
            <param name="Диаметр" unit="мм">37</param>';
        }
        if ($id==39517)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Бордовый</param>
            <param name="Длина" unit="мм">134</param>
            <param name="Диаметр" unit="мм">48</param>';
        }
        if ($id==39509)
        {
            $params='<param name="Тип интимной игрушки">Вагинальные шарики</param>
            <param name="Пол">Женский</param>
            <param name="Материал">Силикон</param>
            <param name="Водостойкость">Да</param>
            <param name="Цвет">Разные цвета</param>
            <param name="Длина" unit="мм">176</param>
            <param name="Диаметр" unit="мм">33</param>
            <param name="Вес" unit="г">150</param>';
        }
        if ($id==39538)
        {
            $params='<param name="Размер">S</param>
            <param name="Материал">Силикон</param>
            <param name="Цвет">Зелёный</param>
            <param name="Диаметр" unit="мм">38</param>
            <param name="Высота" unit="мм">70</param>
            <param name="Длина хвостика" unit="мм">15</param>
            <param name="Полезный объем" unit="мл">20</param>
            <param name="Мягкость">Мягкая</param>
            <param name="Чехол для хранения и транспортировки">нет</param>';
        }
        if ($id==39539)
        {
            $params='<param name="Размер">S</param>
            <param name="Материал">Силикон</param>
            <param name="Цвет">Белый</param>
            <param name="Диаметр" unit="мм">38</param>
            <param name="Высота" unit="мм">70</param>
            <param name="Длина хвостика" unit="мм">15</param>
            <param name="Полезный объем" unit="мл">20</param>
            <param name="Мягкость">Мягкая</param>
            <param name="Чехол для хранения и транспортировки">нет</param>';
        }
        if ($id==39532)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">106</param>
            <param name="Ширина" unit="мм">145</param>';
        }
        if ($id==39537)
        {
            $params='<country_of_origin>Германия</country_of_origin>
            <param name="Размер">S</param>
            <param name="Материал">Силикон</param>
            <param name="Цвет">Синий</param>
            <param name="Диаметр" unit="мм">25</param>
            <param name="Высота" unit="мм">30</param>
            <param name="Полезный объем" unit="мл">15</param>
            <param name="Мягкость">Мягкая</param>';
        }
        if ($id==39545)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Белый</param>
            <param name="Длина" unit="мм">179</param>
            <param name="Диаметр" unit="мм">49</param>';
        }
        if ($id==39546)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Пластик</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Пульсация</param>
            <param name="Цвет">Золотистый</param>
            <param name="Длина" unit="мм">165</param>
            <param name="Ширина" unit="мм">46</param>
            <param name="Вес" unit="г">248</param>';
        }
        if ($id==39552)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Нет</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Золотистый</param>
            <param name="Длина" unit="мм">145</param>
            <param name="Ширина" unit="мм">55</param>';
        }
        if ($id==39552)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Нет</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Золотистый</param>
            <param name="Длина" unit="мм">145</param>
            <param name="Ширина" unit="мм">55</param>';
        }
        if ($id==39548)
        {
            $params='<param name="Размер">S</param>
            <param name="Материал">Силикон</param>
            <param name="Цвет">Фиолетовый</param>
            <param name="Мягкость">Мягкая</param>
            <param name="Чехол для хранения и транспортировки">нет</param>
            <param name="Пол" unit="">Женский</param>
            <param name="Объем" unit="">20.0</param>
            <param name="Возрастная группа" unit="">Без ограничений</param>';
        }
        if ($id==39555)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Голубой</param>
            <param name="Длина" unit="мм">145</param>
            <param name="Ширина" unit="мм">106</param>';
        }
        if ($id==39559)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">192</param>
            <param name="Ширина" unit="мм">53</param>
            <param name="Высота" unit="мм">40</param>';
        }
        if ($id==39558)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Унисекс</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">340</param>
            <param name="Ширина" unit="мм">37</param>';
        }
        if ($id==39561)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Алюминий</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Металлик</param>
            <param name="Длина" unit="мм">172</param>
            <param name="Ширина" unit="мм">59</param>
            <param name="Высота" unit="мм">42</param>
            <param name="Вес" unit="г">165</param>';
        }
        if ($id==39560)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Белый</param>
            <param name="Длина" unit="мм">193</param>
            <param name="Ширина" unit="мм">54</param>';
        }
        if ($id==39565)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">7</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Фиолетовый</param>
            <param name="Длина" unit="мм">95</param>
            <param name="Ширина" unit="мм">67</param>
            <param name="Высота" unit="мм">35</param>
            <param name="Вес" unit="г">52</param>';
        }
        if ($id==39567)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Фиолетовый</param>
            <param name="Длина" unit="мм">88</param>
            <param name="Ширина" unit="мм">53</param>
            <param name="Высота" unit="мм">28</param>
            <param name="Вес" unit="г">63</param>';
        }
        if ($id==39562)
        {
            $params='<param name="Пол">Унисекс</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">14</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Малиновый</param>
            <param name="Длина" unit="мм">130</param>
            <param name="Ширина" unit="мм">65</param>';
        }
        if ($id==39569)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Белый</param>
            <param name="Длина" unit="мм">91</param>
            <param name="Ширина" unit="мм">42</param>
            <param name="Высота" unit="мм">57</param>
            <param name="Вес" unit="г">79</param>';
        }
        if ($id==39564)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Унисекс</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Количество скоростей вибрации">14</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">130</param>
            <param name="Ширина" unit="мм">65</param>';
        }
        if ($id==39571)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">91</param>
            <param name="Ширина" unit="мм">42</param>
            <param name="Высота" unit="мм">57</param>
            <param name="Вес" unit="г">79</param>';
        }
        if ($id==39573)
        {
            $params='<param name="Тип интимной игрушки">Мастурбатор</param>
            <param name="Пол">Мужской</param>
            <param name="Материал">Киберкожа</param>
            <param name="Функция вибрации">Нет</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Водостойкость">Да</param>
            <param name="Цвет">Черный</param>
            <param name="Тип маструбатора">Вагина</param>
            <param name="Длина" unit="мм">259</param>
            <param name="Ширина" unit="мм">96</param>
            <param name="Вес" unit="г">699</param>';
        }
        if ($id==39576)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Унисекс</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Тип фаллоимитатора">Гладкий</param>
            <param name="Цвет">Белый</param>
            <param name="Длина" unit="мм">210</param>
            <param name="Вес" unit="г">156</param>';
        }
        if ($id==39579)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Унисекс</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Тип фаллоимитатора">Гладкий</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">210</param>
            <param name="Вес" unit="г">156</param>';
        }
        if ($id==39580)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Унисекс</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Тип фаллоимитатора">Гладкий</param>
            <param name="Цвет">Синий</param>
            <param name="Длина" unit="мм">210</param>
            <param name="Вес" unit="г">156</param>';
        }
        if ($id==39581)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Цвет">Розовый</param>
            <param name="Длина" unit="мм">204</param>
            <param name="Диаметр" unit="мм">35</param>';
        }
        if ($id==39583)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Цвет">Белый</param>
            <param name="Длина" unit="мм">204</param>
            <param name="Диаметр" unit="мм">35</param>';
        }
        if ($id==39584)
        {
            $params='<param name="Тип интимной игрушки">Мастурбатор</param>
            <param name="Пол">Мужской</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">200</param>
            <param name="Диаметр" unit="мм">57</param>';
        }
        if ($id==39587)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Унисекс</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Количество скоростей вибрации">14</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Фиолетовый</param>
            <param name="Длина" unit="мм">130</param>
            <param name="Ширина" unit="мм">65</param>';
        }
        if ($id==39514)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Розовый</param>
            <param name="Длина" unit="мм">148</param>
            <param name="Диаметр" unit="мм">48</param>';
        }
        if ($id==39542)
        {
            $params='<param name="Размер">S</param>
            <param name="Материал">Силикон</param>
            <param name="Цвет">Голубой</param>
            <param name="Диаметр" unit="мм">25</param>
            <param name="Высота" unit="мм">30</param>
            <param name="Полезный объем" unit="мл">15</param>
            <param name="Мягкость">Мягкая</param>';
        }
        if ($id==39491)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Белый</param>
            <param name="Длина" unit="мм">114</param>
            <param name="Диаметр" unit="мм">30</param>
            <param name="Вес" unit="г">100</param>';
        }
        if ($id==39494)
        {
            $params='<param name="Тип интимной игрушки">Эрекционное кольцо</param>
            <param name="Пол">Мужской</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">83</param>
            <param name="Ширина" unit="мм">45</param>';
        }
        if ($id==39543)
        {
            $params='<param name="Размер">S</param>
            <param name="Материал">Силикон</param>
            <param name="Цвет">Оранжевый</param>
            <param name="Диаметр" unit="мм">25</param>
            <param name="Высота" unit="мм">30</param>
            <param name="Полезный объем" unit="мл">15</param>
            <param name="Мягкость">Мягкая</param>';
        }
        if ($id==26392)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Пластик</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Пульсация</param>
            <param name="Цвет">Золотистый</param>
            <param name="Длина" unit="мм">165</param>
            <param name="Ширина" unit="мм">46</param>
            <param name="Вес" unit="г">248</param>';
        }
        if ($id==29375)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Фиолетовый</param>
            <param name="Длина" unit="мм">100</param>
            <param name="Ширина" unit="мм">35</param>
            <param name="Вес" unit="г">192</param>';
        }
        if ($id==29177)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">10</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Белый</param>
            <param name="Длина" unit="мм">220</param>
            <param name="Диаметр" unit="мм">43</param>';
        }
        if ($id==27876)
        {
            $params='<param name="Тип интимной игрушки">Клиторальный стимулятор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Пластик</param>
            <param name="Функция вибрации">Да</param>
            <param name="Количество скоростей вибрации">11</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Пульсация</param>
            <param name="Цвет">Золотистый</param>
            <param name="Длина" unit="мм">143</param>
            <param name="Ширина" unit="мм">48</param>
            <param name="Вес" unit="г">88</param>';
        }
        if ($id==31524)
        {
            $params='<param name="Тип интимной игрушки">Вакуумный вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Клиторальный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация, Пульсация</param>
            <param name="Цвет">Черный</param>
            <param name="Длина" unit="мм">145</param>
            <param name="Диаметр" unit="мм">48</param>';
        }
        if ($id==31523)
        {
            $params='<param name="Тип интимной игрушки">Мастурбатор</param>
            <param name="Пол">Мужской</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Черный</param>
            <param name="Тип маструбатора">Вагина</param>
            <param name="Длина" unit="мм">140</param>
            <param name="Ширина" unit="мм">78</param>
            <param name="Вес" unit="г">193</param>';
        }
        if ($id==39556)
        {
            $params='<param name="Тип интимной игрушки" unit="">Вибратор</param>
            <param name="Пол" unit="">Унисекс</param>
            <param name="Тип вибратора" unit="">Клиторальный</param>
            <param name="Функция вибрации" unit="">Да</param>
            <param name="Тип элементов питания" unit="">Li-Ion</param>
            <param name="Водостойкость" unit="">Да</param>
            <param name="Режим работы" unit="">Вибрация</param>
            <param name="Цвет" unit="">Белый</param>';
        }
        if ($id==40875)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">G-точки</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">12</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Желтый</param>
            <param name="Длина" unit="мм">225</param>
            <param name="Диаметр" unit="мм">41</param>';
        }
        if ($id==40876)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">6</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Розовый</param>
            <param name="Длина" unit="мм">221</param>
            <param name="Диаметр" unit="мм">41</param>';
        }
        if ($id==40874)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">G-точки</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">12</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Розовый</param>
            <param name="Длина" unit="мм">229</param>
            <param name="Диаметр" unit="мм">40</param>';
        }
        if ($id==30622)
        {
            $params='<param name="Тип интимной игрушки">Вибратор</param>
            <param name="Пол">Женский</param>
            <param name="Тип вибратора">Комбинированный</param>
            <param name="Материал">Силикон</param>
            <param name="Функция вибрации">Да</param>
            <param name="Регулировка размера">Нет</param>
            <param name="Количество скоростей вибрации">6</param>
            <param name="Регулятор уровня вибрации">Ступенчатый</param>
            <param name="Тип элементов питания">Li-Ion</param>
            <param name="Водостойкость">Да</param>
            <param name="Режим работы">Вибрация</param>
            <param name="Цвет">Красный</param>
            <param name="Длина" unit="мм">188</param>
            <param name="Диаметр" unit="мм">45</param>';
        }

        $params='<country>Германия</country>'.PHP_EOL.$params;
        return $params;
    }

    private function setPriceSatisfyer($items)
    {
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                $id=$this->getItemId($item);
                if ($id==31523)
                {
                    $item=$this->setPrice($item,1420,1580);
                }
                if ($id==31524)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==27876)
                {
                    $item=$this->setPrice($item,450,500);
                }
                if ($id==29177)
                {
                    $item=$this->setPrice($item,1620,1810);
                }
                if ($id==29375)
                {
                    $item=$this->setPrice($item,850,940);
                }
                if ($id==26392)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==39543)
                {
                    $item=$this->setPrice($item,300,335);
                }
                if ($id==39494)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==39491)
                {
                    $item=$this->setPrice($item,720,850);
                }
                if ($id==39542)
                {
                    $item=$this->setPrice($item,300,335);
                }
                if ($id==39514)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==39587)
                {
                    $item=$this->setPrice($item,1300,1500);
                }
                if ($id==39584)
                {
                    $item=$this->setPrice($item,1550,1640);
                }
                if ($id==39583)
                {
                    $item=$this->setPrice($item,1400,1550);
                }
                if ($id==39581)
                {
                    $item=$this->setPrice($item,1400,1550);
                }
                if ($id==39580)
                {
                    $item=$this->setPrice($item,1400,1550);
                }
                if ($id==39579)
                {
                    $item=$this->setPrice($item,1400,1550);
                }
                if ($id==39576)
                {
                    $item=$this->setPrice($item,1400,1550);
                }
                if ($id==39573)
                {
                    $item=$this->setPrice($item,895,995);
                }
                if ($id==39571)
                {
                    $item=$this->setPrice($item,1295,1360);
                }
                if ($id==39564)
                {
                    $item=$this->setPrice($item,1540,1720);
                }
                if ($id==39569)
                {
                    $item=$this->setPrice($item,1295,1360);
                }
                if ($id==39562)
                {
                    $item=$this->setPrice($item,1540,1720);
                }
                if ($id==39567)
                {
                    $item=$this->setPrice($item,1200,1330);
                }
                if ($id==39565)
                {
                    $item=$this->setPrice($item,585,615);
                }
                if ($id==39560)
                {
                    $item=$this->setPrice($item,2550,2690);
                }
                if ($id==39561)
                {
                    $item=$this->setPrice($item,3400,3770);
                }
                if ($id==39558)
                {
                    $item=$this->setPrice($item,1450,1615);
                }
                if ($id==39559)
                {
                    $item=$this->setPrice($item,2550,2690);
                }
                if ($id==39555)
                {
                    $item=$this->setPrice($item,900,1000);
                }
                if ($id==39548)
                {
                    $item=$this->setPrice($item,300,335);
                }
                if ($id==39552)
                {
                    $item=$this->setPrice($item,900,1000);
                }
                if ($id==39546)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==39545)
                {
                    $item=$this->setPrice($item,1200,1330);
                }
                if ($id==39537)
                {
                    $item=$this->setPrice($item,300,335);
                }
                if ($id==39532)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==39539)
                {
                    $item=$this->setPrice($item,300,335);
                }
                if ($id==39538)
                {
                    $item=$this->setPrice($item,300,335);
                }
                if ($id==39509)
                {
                    $item=$this->setPrice($item,600,665);
                }
                if ($id==39517)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==39510)
                {
                    $item=$this->setPrice($item,1450,1615);
                }
                if ($id==39516)
                {
                    $item=$this->setPrice($item,1300,1450);
                }
                if ($id==39512)
                {
                    $item=$this->setPrice($item,1600,1780);
                }
                if ($id==39508)
                {
                    $item=$this->setPrice($item,680,755);
                }
                if ($id==39498)
                {
                    $item=$this->setPrice($item,950,1050);
                }
                if ($id==39493)
                {
                    $item=$this->setPrice($item,720,800);
                }
                if ($id==39492)
                {
                    $item=$this->setPrice($item,855,955);
                }
                if ($id==33860)
                {
                    $item=$this->setPrice($item,580,610);
                }
                if ($id==27877)
                {
                    $item=$this->setPrice($item,1550,1730);
                }
                if ($id==33861)
                {
                    $item=$this->setPrice($item,550,610);
                }
                if ($id==39556)
                {
                    $item=$this->setPrice($item,1450,1615);
                }
                $items_new.=$item.PHP_EOL;
            }
            return $items_new;
        }
    }
    
    private function setDiscSvacom($items)
    {
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                $id=$this->getItemId($item);
                if ($id==26212||$id==26211)
                {
                    $item=$this->setPrice($item,2900,3650);
                }
                if ($id==25000||$id==27685)
                {
                    $item=$this->setPrice($item,2150,2700);
                }
                if ($id==25007||$id==27686)
                {
                    $item=$this->setPrice($item,1750,1950);
                }
                if ($id==24949||$id==24948)
                {
                    $item=$this->setPrice($item,1750,1995);
                }
                if ($id==24908||$id==24907||$id==24900)
                {
                    $item=$this->setPrice($item,1119,1550);
                }
                if ($id==24902)
                {
                    $item=$this->setPrice($item,1550,1850);
                }
                if ($id==24898||$id==24903)
                {
                    $item=$this->setPrice($item,1550,2150);
                }
                if ($id==31069||$id==27681)
                {
                    $item=$this->setPrice($item,1850,2450);
                }
                if ($id==27676)
                {
                    $item=$this->setPrice($item,2300,2680);
                }
                if ($id==27678)
                {
                    $item=$this->setPrice($item,2170,2495);
                }
                if ($id==30300||$id==30039)
                {
                    $item=$this->setPrice($item,1550,2150);
                }
                if ($id==30042)
                {
                    $item=$this->setPrice($item,2300,3500);
                }
                if ($id==31073)
                {
                    $item=$this->setPrice($item,2300,3100);
                }
                if ($id==38793)
                {
                    $item=$this->setPrice($item,2900,3800);
                }
                if ($id==38793)
                {
                    $item=$this->setPrice($item,2900,3800);
                }
                if ($id==38792)
                {
                    $item=$this->setPrice($item,2550,3450);
                }
                if ($id==38795)
                {
                    $item=$this->setPrice($item,2300,3500);
                }
                if ($id==33558)
                {
                    $item=$this->setPrice($item,2100,2450);
                }
                if ($id==33559)
                {
                    $item=$this->setPrice($item,2395,2950);
                }
                if ($id==37330)
                {
                    $item=$this->setPrice($item,4300,5670);
                }
                if ($id==38790)
                {
                    $item=$this->setPrice($item,4600,6900);
                }
                if ($id==37331)
                {
                    $item=$this->setPrice($item,520,675);
                }
                if ($id==38794)
                {
                    $item=$this->setPrice($item,2750,3995);
                }
                if ($id==38791)
                {
                    $item=$this->setPrice($item,3095,3995);
                }
                $items_new.=$item.PHP_EOL;
            }
            return $items_new;
        }
    }

    private function addFakeDisc($items)
    {
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                $price=$this->getPrice($item);
                $oldPrice=$this->getOldPrice($item);
                $vendor=$this->getVendor($item);
                if (strcmp($vendor, "Zalo")!=0)
                {
                    //$newOldPrice=round($price+($price/100*rand(10,20)));
                    $newOldPrice=round($price*1.15,-1);
                    if ($oldPrice>$newOldPrice)
                    {
                        $newOldPrice=$oldPrice;
                    }
                    $item=$this->setPrice($item,$price,$newOldPrice);
                    $items_new.=$item.PHP_EOL;
                }
            }
        }
        return $items_new;
    }

    private function addDiscounts($items)
    {
        if (is_array ($items))
        {
            foreach ($items as $item)
            {
                $price=null;
                $oldPrice=null;
                $vendor=$this->getVendor($item);
                if ((strcmp($vendor, "We-Vibe")!=0)&&(strcmp($vendor, "Womanizer")!=0)&&(strcmp($vendor, "Svakom")!=0))
                {
                    $price=$this->getPrice($item);
                    $oldPrice=$this->getOldPrice($item);
                    if ((strcmp($vendor, "Fifty Shades of Grey")==0)||(strcmp($vendor, "Orgie")==0)||(strcmp($vendor, "Bijoux Indiscrets")==0))
                    {
                        if (!empty($oldPrice))
                        {
                            $price_tmp=round($oldPrice*0.7);
                            if ($price_tmp>=$price)
                            {
                                $price=$price_tmp;
                            }
                        }
                        else
                        {
                            $oldPrice=$price;
                            $price=round($price*0.7);
                        }
                    }
                    else
                    {
                        if (!empty($oldPrice))
                        {
                            $price_tmp=round($oldPrice*0.8);
                            if ($price_tmp>=$price)
                            {
                                $price=$price_tmp;
                            }
                        }
                        else
                        {
                            $oldPrice=$price;
                            $price=round($price*0.8);
                        }
                    }
                }
                $name=$this->getItemName($item);
                //echo "$vendor: $name $price-$oldPrice<br>";
                if (!empty($price))
                {
                    $item=$this->setPrice($item,$price,$oldPrice);

                    //echo $item;
                }
                //break;
                $items_new.=$item.PHP_EOL;

            }
        }
        return $items_new;
    }
    
}
echo "<b>Start</b> ".date("Y-m-d H:i:s")."<br>";
$test=new testXML();
$test->parseXML();
//$test->addSatsisfyer();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
