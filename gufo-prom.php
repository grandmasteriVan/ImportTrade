<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * Gufo
 */
class Gufo
{    
    /**
     * readFile
     *
     * @return void
     */
    private function readFile()
    {
        //$xml=file_get_contents('index.xml');
        $xml=file_get_contents('test.xml');
        return $xml;
    }

    /**
     * getItemCode
     * получаем айди айтема
     * @param  mixed $item - айтем
     * @return void - айди айтема
     */
    private function getItemCode($item)
    {
        preg_match("#<vendorCode>(.*?)<\/vendorCode>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getItemId($item)
    {
        preg_match("#<item id=\"(.*?)\"#",$item,$matches);
        $id=$matches[1];
        return $id;
    }
    
    /**
     * delDescription
     *
     * @param  mixed $item
     * @return void
     */
    private function delDescription ($item)
    {
        $item=str_ireplace("&","",$item);
        $item=preg_replace("#<description>(.*?)<\/description>#s","<description></description>",$item);
        //удяляем лишние пробелы
        //$xml = preg_replace('/\s+/', ' ', $xml);
        $item=str_ireplace(" unit=\"\"","",$item);
        return $item;
    }
    
    /**
     * setDescription
     *
     * @param  mixed $item
     * @return void
     */
    private function setDescription($item)
    {
        $name=$this->getItemName($item);
        $item=str_ireplace("<description></description>","<description>$name</description>",$item);
    }
    
    /**
     * getItemName
     *
     * @param  mixed $item
     * @return void
     */
    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

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
     * getParamsList
     *
     * @param  mixed $params
     * @return void
     */
    private function getParamsList($params)
    {
        foreach ($params as $param)
        {
            $paramName=$this->getParamName($param);
            $paramNames[]=$paramName;
        }
        //echo "<pre>".print_r($paramNames)."</pre>";
        $countParams=array_count_values($paramNames);
        //echo "<pre>".print_r($countParams)."</pre>";

        foreach ($countParams as $key => $value)
        {
            if ($value>1)
            {
                $s[$key]=$value;
                //echo "$key - $value<br>";
            }
        }
        echo "<pre>".print_r($s)."</pre>";
        return $s;
    }
    
    /**
     * find1
     *
     * @param  mixed $s
     * @param  mixed $item
     * @return void
     */
    private function find1($s,$item)
    {
        //все параметры айтема
        $params=$this->getParams($item);
        //отсекаем ненужные параметры
        foreach ($s as $key=>$value)
        {
            if ((strcmp($key,"Цвет")==0)||(strcmp($key,"Размер")==0))
            {
                $new_s[$key]=$value;
            }
        }
        //echo "<pre>".print_r($new_s)."</pre>";
        if (count($new_s)==1)
        {
            //echo "Связь по одному параметру<br>";
            foreach($new_s as $key=>$value)
            {
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //для каждого параметра в айтеме сроавниваем его имя с тем, по которому будем делать связь. Если находим такой параметр - то получаем новый айтем, у которого остается только одна пара Параметр-Значение, которая соответствует текущему найденному параметру
                    //
                    if ($key==$paramName)
                    {
                        $newItems.=$this->makeNewItem($paramName,$paramVal,$item);
                    }
                }
            }
        }
        if (count($new_s)==2)
        {
            //echo "Связь по двум параметрам<br>";
            //for ($i=1;$i<=$new_s['Цвет'];$i++)
            //{
            foreach ($params as $param)
            {
                $paramName=$this->getParamName($param);
                $paramVal=$this->getParamVal($param);
                if (strcmp('Цвет',$paramName)==0)
                {
                    $newItems.=$this->makeNewItem($paramName,$paramVal,$item);
                }
            }
                
            //}
            //var_dump($newItems);
            $newItemsArr=$this->getItemsArr($newItems);

            $mas['Размер']=$new_s['Размер'];
            foreach ($newItemsArr as $item1)
            {
                $newItems1.=$this->find1($mas,$item1);

            }

        }
        //var_dump($newItems1);
        /*
        foreach($s as $key=>$value)
        {
            //$count
            if ((strcmp($key,"Цвет")==0)||(strcmp($key,"Размер")==0))
            {
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    if ($key==$paramName)
                    {
                        $newItems.=$this->makeNewItem($paramName,$paramVal,$item);
                    }
                }
            }
            
        }
        */
        //var_dump ($newItems);
        if (!is_null($newItems1))
        {
            return $newItems1;
        }
        else
        {
            return $newItems;
        }
        
    }    
    
        
    
    
    /**
     * makeNewItem
     * получаем айтем и Параметр и его значение которое нужно оставить
     * На выходе получаем айтем, в котором остается только однин Параметр с выбранным именем и значенмием
     * при этом параметры с другим именем не трогаем
     * @param  mixed $paramName
     * @param  mixed $paramVal
     * @param  mixed $item
     * @return void
     */
    private function makeNewItem($paramName,$paramVal,$item)
    {
        $params=$this->getParams($item);
        foreach ($params as $param)
        {
            $parName=$this->getParamName($param);
            $parVal=$this->getParamVal($param);
            if ((strcmp ($parName,$paramName)==0)&&(strcmp ($parVal,$paramVal)!=0))
            {
                //echo "<br>YAY!<br>";
                //echo 
                $item=str_ireplace("<param name=\"$paramName\">$parVal</param>","",$item);
            }
        }
        return $item;
    }

    public function test()
    {
        $xml=$this->readFile();
        $XMLnew=$this->delDescription($xml);
        $XMLnew=$this->stripHead($XMLnew);
        $items=$this->getItemsArr($XMLnew);
        if (is_array($items))
        {
            foreach ($items as $item)
            {
                //var_dump($item);
                $params=$this->getParams($item);
                //echo "<pre>".print_r($params)."</pre>";
                $list=$this->getParamsList($params);
                $newItems=$this->find1($list,$item);
                var_dump($newItems);
                $id=$this->getItemId($item);
                //echo "id=$id<br>";
                break;
            }
        }
        else
        {
            echo "No items find in XML<br>";
        }
        //file_put_contents("gufo_new.xml",$XMLnew);
    }

}

$test=new Gufo();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
