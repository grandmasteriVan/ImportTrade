<?php
header('Content-Type: text/html; charset=utf-8');
//v2 - заменили наш каталог на хотлайновсктий
//v3 - проставили хотлайновские параметры, где это возможно
//v4 - заменили имена нераспознанных товаров на основе списка нераспознанных от хотлайна


/**
 * MakeTree
 */
class MakeTree
{
    
    const prom_cats="<categories>
    <category>
        <id>1</id>
        <name>Товары для взрослых</name>
    </category>
    <category>
        <id>2</id>
        <parentId>1</parentId>
        <name>Интим</name>
    </category>
    <category>
        <id>3</id>
        <parentId>2</parentId>
        <name>Анальные вибраторы</name>
    </category>
    <category>
        <id>4</id>
        <parentId>2</parentId>
        <name>Анальные пробки</name>
    </category>
    <category>
        <id>5</id>
        <parentId>2</parentId>
        <name>Анальные стимуляторы</name>
    </category>
    <category>
        <id>6</id>
        <parentId>2</parentId>
        <name>Анальные шарики</name>
    </category>
    <category>
        <id>7</id>
        <parentId>2</parentId>
        <name>Вагины</name>
    </category>
    <category>
        <id>8</id>
        <parentId>2</parentId>
        <name>Вагинальные шарики</name>
    </category>
    <category>
        <id>9</id>
        <parentId>2</parentId>
        <name>Вакуумные помпы</name>
    </category>
    <category>
        <id>10</id>
        <parentId>2</parentId>
        <name>Вибраторы</name>
    </category>
    <category>
        <id>11</id>
        <parentId>2</parentId>
        <name>Контрацептивы</name>
    </category>
    <category>
        <id>12</id>
        <parentId>2</parentId>
        <name>Лубриканты</name>
    </category>
    <category>
        <id>13</id>
        <parentId>2</parentId>
        <name>Маски и кляпы</name>
    </category>
    <category>
        <id>14</id>
        <parentId>2</parentId>
        <name>Массажеры простаты</name>
    </category>
    <category>
        <id>15</id>
        <parentId>2</parentId>
        <name>Мастурбаторы</name>
    </category>
    <category>
        <id>16</id>
        <parentId>2</parentId>
        <name>Наручники, ошейники, фиксаторы</name>
    </category>
    <category>
        <id>17</id>
        <parentId>2</parentId>
        <name>Насадки на половой орган</name>
    </category>
    <category>
        <id>18</id>
        <parentId>2</parentId>
        <name>Секс-куклы</name>
    </category>
    <category>
        <id>19</id>
        <parentId>2</parentId>
        <name>Страпоны</name>
    </category>
    <category>
        <id>20</id>
        <parentId>2</parentId>
        <name>Фаллоимитаторы</name>
    </category>
    <category>
        <id>21</id>
        <parentId>2</parentId>
        <name>Эрекционные кольца</name>
    </category>
    <category>
        <id>22</id>
        <parentId>2</parentId>
        <name>Эротические приколы</name>
    </category>
    <category>
        <id>23</id>
        <parentId>2</parentId>
        <name>Эротические игры</name>
    </category>
    <category>
        <id>24</id>
        <parentId>2</parentId>
        <name>Подарочные эротические наборы</name>
    </category>
    <category>
        <id>25</id>
        <parentId>1</parentId>
        <name>Эротическая одежда</name>
    </category>
    <category>
        <id>26</id>
        <parentId>25</parentId>
        <name>Винил, кожа, латекс</name>
    </category>
    <category>
        <id>27</id>
        <parentId>25</parentId>
        <name>Эротические парики</name>
    </category>
    <category>
        <id>28</id>
        <parentId>25</parentId>
        <name>Эротические платья, пеньюары</name>
    </category>
    <category>
        <id>29</id>
        <parentId>25</parentId>
        <name>Эротические боди и корсеты</name>
    </category>
    <category>
        <id>30</id>
        <parentId>25</parentId>
        <name>Эротические костюмы</name>
    </category>
    <category>
        <id>31</id>
        <parentId>25</parentId>
        <name>Эротическое нижнее белье</name>
    </category>
    <category>
        <id>32</id>
        <parentId>25</parentId>
        <name>Эротическая обувь</name>
    </category>
    <category>
        <id>33</id>
        <parentId>25</parentId>
        <name>Эротические чулки и колготки</name>
    </category>
    <category>
        <id>34</id>
        <parentId>1</parentId>
        <name>Возбуждающие средства, контрацепция, лубриканты</name>
    </category>
    <category>
        <id>35</id>
        <parentId>34</parentId>
        <name>Возбуждающие средства</name>
    </category>
    <category>
        <id>36</id>
        <name>Парфюм и Косметика</name>
    </category>
    <category>
        <id>37</id>
        <parentId>36</parentId>
        <name>Косметика по уходу</name>
    </category>
    <category>
        <id>38</id>
        <parentId>37</parentId>
        <name>Для тела</name>
    </category>
    <category>
        <id>39</id>
        <parentId>38</parentId>
        <name>Масло для массажа</name>
    </category>
</categories>";
        
    /**
     * readFile
     *
     * @return void
     */
    private function readFile()
    {
        $xml=file_get_contents('hotline_ua-v2.xml');
        return $xml;
    }
    
    /**
     * stripHead
     *
     * @param  mixed $txt
     * @return void
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
     *
     * @param  mixed $txt
     * @return void
     */
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
    
    /**
     * getCatId
     *
     * @param  mixed $item
     * @return void
     */
    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
    }
    
    /**
     * FindPromCat
     *
     * @param  mixed $item
     * @return void
     */
    private function FindPromCat($item)
    {
        $newCat=null;
        $catIdOld=$this->getCatId($item);
        if ($catIdOld==18)
        {
            $newCat=3;
        }
        if ($catIdOld==7||$catIdOld==1)
        {
            $newCat=4;
        }
        if ($catIdOld==30||$catIdOld==266||$catIdOld==35)
        {
            $newCat=5;
        }
        if ($catIdOld==45)
        {
            $newCat=6;
        }
        if ($catIdOld==6||$catIdOld==192)
        {
            $newCat=8;
        }
        if ($catIdOld==128||$catIdOld==127||$catIdOld==9)
        {
            $newCat=9;
        }
        if ($catIdOld==264||$catIdOld==190||$catIdOld==10||$catIdOld==187||$catIdOld==191||$catIdOld==256||$catIdOld==240||$catIdOld==250||$catIdOld==271||$catIdOld==272||$catIdOld==273||$catIdOld==124||$catIdOld==241||$catIdOld==106||$catIdOld==50||$catIdOld==204||$catIdOld==205||$catIdOld==43||$catIdOld==225||$catIdOld==206||$catIdOld==186||$catIdOld==209||$catIdOld==166)
        {
            $newCat=10;
        }
        if ($catIdOld==270)
        {
            $newCat=11;
        }
        if ($catIdOld==85||$catIdOld==86||$catIdOld==5||$catIdOld==83||$catIdOld==84||$catIdOld==232||$catIdOld==233||$catIdOld==239||$catIdOld==19||$catIdOld==130||$catIdOld==129)
        {
            $newCat=12;
        }
        if ($catIdOld==118||$catIdOld==117)
        {
            $newCat=13;
        }
        if ($catIdOld==44)
        {
            $newCat=14;
        }
        if ($catIdOld==8)
        {
            $newCat=15;
        }
        if ($catIdOld==174||$catIdOld==258||$catIdOld==116||$catIdOld==261||$catIdOld==214||$catIdOld==81||$catIdOld==115||$catIdOld==79||$catIdOld==259||$catIdOld==260||$catIdOld==262||$catIdOld==215||$catIdOld==249||$catIdOld==216||$catIdOld==26||$catIdOld==48)
        {
            $newCat=16;
        }
        if ($catIdOld==148||$catIdOld==126)
        {
            $newCat=17;
        }
        if ($catIdOld==20)
        {
            $newCat=18;
        }
        if ($catIdOld==21||$catIdOld==256||$catIdOld==255||$catIdOld==143)
        {
            $newCat=19;
        }
        if ($catIdOld==274||$catIdOld==275||$catIdOld==276||$catIdOld==133||$catIdOld==53)
        {
            $newCat=20;
        }
        if ($catIdOld==88||$catIdOld==147||$catIdOld==16)
        {
            $newCat=21;
        }
        if ($catIdOld==22||$catIdOld==141)
        {
            $newCat=22;
        }
        if ($catIdOld==278||$catIdOld==107||$catIdOld==277)
        {
            $newCat=23;
        }
        if ($catIdOld==257||$catIdOld==113||$catIdOld==150||$catIdOld==145||$catIdOld==199||$catIdOld==37||$catIdOld==223||$catIdOld==222||$catIdOld==221||$catIdOld==208)
        {
            $newCat=24;
        }
        if ($catIdOld==89||$catIdOld==246||$catIdOld==168)
        {
            $newCat=25;
        }
        if ($catIdOld==65||$catIdOld==140||$catIdOld==282||$catIdOld==229)
        {
            $newCat=26;
        }
        if ($catIdOld==200)
        {
            $newCat=27;
        }
        if ($catIdOld==263||$catIdOld==188||$catIdOld==62)
        {
            $newCat=28;
        }
        if ($catIdOld==279||$catIdOld==153||$catIdOld==119)
        {
            $newCat=29;
        }
        if ($catIdOld==96||$catIdOld==99||$catIdOld==100||$catIdOld==101||$catIdOld==189||$catIdOld==138||$catIdOld==95||$catIdOld==248||$catIdOld==92||$catIdOld==94||$catIdOld==93||$catIdOld==3||$catIdOld==97||$catIdOld==163||$catIdOld==283)
        {
            $newCat=30;
        }
        if ($catIdOld==180||$catIdOld==24||$catIdOld==25||$catIdOld==68||$catIdOld==228||$catIdOld==219||$catIdOld==72||$catIdOld==60||$catIdOld==175||$catIdOld==14||$catIdOld==70)
        {
            $newCat=31;
        }
        if ($catIdOld==27||$catIdOld==213)
        {
            $newCat=32;
        }
        if ($catIdOld==280||$catIdOld==281||$catIdOld==69||$catIdOld==201||$catIdOld==161)
        {
            $newCat=33;
        }
        if ($catIdOld==11||$catIdOld==59||$catIdOld==12||$catIdOld==251||$catIdOld==252||$catIdOld==253||$catIdOld==54||$catIdOld==160||$catIdOld==28||$catIdOld==55||$catIdOld==169)
        {
            $newCat=35;
        }
        if ($catIdOld==15||$catIdOld==237||$catIdOld==236)
        {
            $newCat=39;
        }
        return $newCat;
    }
    
    /**
     * setItemCat
     *
     * @param  mixed $item
     * @param  mixed $catalog
     * @return void
     */
    private function setItemCat($item,$catalog)
    {
        $old_cat=$this->getCatId($item);
        //$new_item=str_ireplace("<categoryId>$old_cat</categoryId>","<categoryId>$catalog</categoryId>",$item);
        $new_item=preg_replace("#<categoryId>(.*?)<\/categoryId>#","<categoryId>$catalog</categoryId>",$item);
        return $new_item;
    }
    
    /**
     * makeHead
     *
     * @return void
     */
    private function makeHead()
    {
        $date=date("Y-m-d H:i");
        //print_r ($date);
        $head="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <price>
        <date>$date</date>
        <firmName>Мой магазин</firmName>
        <firmId>Идентификатор магазина</firmId>".PHP_EOL;
        return $head;
    }
    
    /**
     * catReplace
     *
     * @return void
     */
    public function catReplace()
    {
        $oldXML=$this->readFile();
        $xml_new=$this->stripHead($oldXML);
        $items=$this->getItemsArr($xml_new);
        foreach ($items as $item)
        {
            $newCat=$this->FindPromCat($item);
            $oldCat=$this->getCatId($item);
            //echo "$oldCat-$newCat<br>";
            $new_item=$this->setItemCat($item,$newCat);
            $items_new.=$new_item;
        }
        //echo "<pre>".print_r($items_new)."</pre>";
        //var_dump($items_new);
        $head=$this->makeHead();
        //echo $head;
        //собираем конечную ХЬЛку
        $xml_new=$head.self::prom_cats.PHP_EOL."<items>".$items_new.PHP_EOL."<items>".PHP_EOL."</price>";
        //echo $xml_new;
       
        file_put_contents("new_hotline-v2.xml",$xml_new);
    }

}

/**
 * Hotline
 */
class Hotline
{    
    /**
     * readFile
     *
     * @return void
     */
    private function readFile()
    {
        $xml=file_get_contents('new_hotline-v2.xml');
        return $xml;
    }
    
    /**
     * stripHead
     *
     * @param  mixed $txt
     * @return void
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
     *
     * @param  mixed $txt
     * @return void
     */
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
    
    /**
     * getCatId
     *
     * @param  mixed $item
     * @return void
     */
    private function getCatId($item)
    {
        preg_match("#<categoryId>(.*?)<\/categoryId>#",$item,$matches);
        $id=$matches[1];
        return $id;
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
     * getVendor
     *
     * @param  mixed $item
     * @return void
     */
    private function getVendor($item)
    {
        preg_match("#<vendor>(.*?)<\/vendor>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }
    
    /**
     * stripName
     *
     * @param  mixed $name
     * @param  mixed $vendor
     * @return void
     */
    private function stripName ($name, $vendor)
    {
        $name_new=str_ireplace($vendor,"",$name);
        $name_new=$vendor." ".$name_new;
        //можно сделатьб так. Во многих товарах у нас есть куча лишнего в описаннию. Но тут есть ньюанс - у нас есть позиции, где модель не указана в названии. Такие позиции как раз не распознаются
        //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)]/ui","",$name_new);
        //$name_new=str_replace("quot","",$name_new);
        $name_new=str_replace("(, ","(",$name_new);
        $name_new=str_replace("(, ,",",",$name_new);
        return $name_new;
    }
    
    /**
     * getXMLhead
     *
     * @param  mixed $txt
     * @return void
     */
    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }
    
    /**
     * getItemId
     *
     * @param  mixed $item
     * @return void
     */
    private function getItemId($item)
    {
        preg_match("#<code>(.*?)<\/code>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }
    
    /**
     * getParams
     *
     * @param  mixed $item
     * @return void
     */
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
    
    /**
     * getParamName
     *
     * @param  mixed $param
     * @return void
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
     *
     * @param  mixed $param
     * @return void
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
     * setItemName
     *
     * @param  mixed $item
     * @param  mixed $name
     * @return void
     */
    private function setItemName($item,$name)
    {
        $old_name=$this->getItemName($item);
        $new_item=str_ireplace($old_name,$name,$item);
        return $new_item;
    }
    
    /**
     * setItemCat
     *
     * @param  mixed $item
     * @param  mixed $catalog
     * @return void
     */
    private function setItemCat($item,$catalog)
    {
        $old_cat=$this->getCatId($item);
        $new_item=str_ireplace("<categoryId>$old_cat</categoryId>","<categoryId>$catalog</categoryId>",$item);
        return $new_item;
    }
    
    /**
     * makeUniqeParams
     *
     * @param  mixed $params
     * @return void
     */
    private function makeUniqeParams($params)
    {
        $params_new=null;
        $param_names=null;
        foreach ($params as $param)
        {
            $isUniq=true;
            $paramName=$this->getParamName($param);
            if (!is_null($param_names))
            {
                
                foreach ($param_names as $param_name)
                {
                    if (strcmp($param_name,$paramName)==0)
                    {
                        $isUniq=false;
                    }
                }
                if ($isUniq)
                {
                    $param_names[]=$paramName;
                    $params_new[]=$param;
                }
            }
            else
            {
                $param_names[]=$paramName;
                $params_new[]=$param;
            }
        }
        return $params_new;
    }
    
    /**
     * getCode
     *
     * @param  mixed $itemName
     * @return void
     */
    public function getCode($itemName)
    {
        $xml=$this->readFile();
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        foreach ($items as $item)
        {
            $name=$this->getItemName($item);
            if ($name==$itemName)
            {
                $code=$this->getItemId($item);
                return $code;
            }
        }
    }
    
    /**
     * getColor
     *
     * @param  mixed $itemName
     * @return void
     */
    public function getColor($itemName)
    {
        $xml=$this->readFile();
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        foreach ($items as $item)
        {
            $name=$this->getItemName($item);
            if ($name==$itemName)
            {
                $params=$this->getParams($item);
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    if (strcmp($paramName,"Цвет")==0)
                    {
                        $paramVal=$this->getParamVal($param);
                        return $paramVal;
                    }
                }
            }
        }

    }
    
    /**
     * getItemHead
     *
     * @param  mixed $item
     * @return void
     */
    private function getItemHead($item)
    {
        $itemHead=explode("<param name",$item);
        return $itemHead[0];
    }
    
    /**
     * parseXML
     *
     * @return void
     */
    public function parseXML()
    {
        $xml=$this->readFile();
        $xmlHead=$this->getXMLhead($xml);
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        foreach ($items as $item)
        {
            //чистим описание
            $item=$this->delDescription($item);
            //обнуляем новую позицию перед созданием
            $new_item=null;
            $itemId=$this->getItemId($item);
            $item=str_ireplace("<name></name>","<name>blank_name</name>",$item);
            if (strcmp($itemId,"9727")==0)
            {
                $item=$this->setItemName($item,"Эротический костюм горничной Старательная Бекки S/M");
            }
            if (strcmp($itemId,"514659")==0)
            {
                $item=$this->setItemName($item,"Эрекционное кольцо Cock Loop");
            }
            
            if (strcmp($itemId,"514888 Э/")==0)
            {
                $item=$this->setItemName($item,"Анальная пробка Key to your Butt");
            } 
            if (strcmp($itemId,"757083")==0)
            {
                //echo "Нашли!<br>";
                $item=$this->setItemName($item,"Платье Rene Rofe Open Season S/M");
            }
            
            if (strcmp($itemId,"00206 F/")==0)
            {
                $item=$this->setItemName($item,"Вибратор ZALO Rosalie");
            }
            if (strcmp($itemId,"645256 /LVTOY056")==0)
            {
                $item=$this->setItemName($item,"Насадка на пенис Pleasure Extender Sleeve Vibro Flesh");
            }
            /*if ($itemId==900650)
            {
                $item=$this->setItemCat($item,"3048");
            }*/
            if ($itemId==52423)
            {
                $item=$this->setItemName($item,"Fifty Shades of Grey Виброяйцо \"Неутомимые вибрации\" (FS52423)");
            }
            //$vendor=$this->getVendor($item);
            $itemName=$this->getItemName($item);
            //echo $itemName;
            //$itemName=$this->stripName($itemName,$vendor);
            //echo " <b>-</b> $itemName<br>";
            $catId=$this->getCatId($item);

            $params=$this->getParams($item);
            //это айтем до параметров. Мы его трогать вообще никогда не будем
            $itemHead=$this->getItemHead($item);
            $params_new=null;
            $new_params=null;
            $specialCat=false;
            //if ($catId==3048)
            if ($catId==10)
            {
                $specialCat=true;
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
                        if (strcmp($param,"Встроенный")==0||strcmp($param,"Дистанционный")==0||strcmp($param,"Проводной")==0)
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
                        if (strcmp($param,"Смарт игрушки")==0)
                        {
                            $param_new="<param name=\"Управление со смартфона\">+</param>";
                        }
                    }
                    if (strcmp($paramName,"Функции")==0)
                    {
                        if (strcmp($param,"С вибрацией")==0)
                        {
                            $param_new="<param name=\"Вибрация\">+</param>";
                        }
                    }
                    //для каждогго прараметра добавляем его в массив параметров
                    $params_new[]=$param_new;
                }
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
                
                if (is_array($params_new))
                {
                    $params_new=array_unique($params_new);
                    $tmp[]=null;
                    $params_new=array_diff($params_new,$tmp);
                    $params_new=$this->makeUniqeParams($params_new);
                    foreach ($params_new as $new_param)
                    {
                        //отсекаем страну, которая у нас пустая (NULL)
                        if ($new_param!=null)
                        {
                            $new_params.=$new_param.PHP_EOL;
                        }
                    }
                }
                else
                {
                    echo "No new params for $itemId<br>";
                }
                
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$new_params."</item>";
                //var_dump ($new_item);
                //break;

            }

            //if ($catId==3050)
            if ($catId==12)
            {
                $specialCat=true;
                /*echo "<pre>";
                print_r($params);
                echo "</pre>";*/
                //break;
                //лубрикант или увлажняющий, имеет специализацию. Специалитзацию я пытаюсь отловить по атрибутам. И если не указано особо считаю лкубриканит просто увлажняющим
                $specialLube=false;
                $edible=false;
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //обнуляем список новых параметров для каждого айтема
                    $param_new=null;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        $param_new=str_ireplace("Страна","Страна производства",$param);
                    }
                    if (strcmp($paramName,"Объем")==0)
                    {
                        $param_new="<param name=\"Вес, г / Объем, мл\">".$paramVal." мл</param>";
                    }
                    if (strcmp($paramName,"Назначение")==0)
                    {
                        //echo "$param<br>";
                        if (strripos($param,"Анальная"))
                        {
                            $param_new="<param name=\"Тип\">Анальный лубрикант</param>";
                        }
                        if (strripos($param,"Вагинальная"))
                        {
                            $param_new="<param name=\"Тип\">Вагинальный лубрикант</param>";
                        }
                        if (strripos($param,"Оральная"))
                        {
                            $param_new="<param name=\"Тип\">Оральный лубрикант</param>";
                        }
                        if (strripos($param,"Очиститель для игрушек"))
                        {
                            $param_new="<param name=\"Тип\">Средство для ухода за секс-игрушками</param>";
                        }
                    }
                    if (strcmp($paramName,"Свойства")==0||strcmp($paramName,"Особенности")==0||strcmp($paramName,"Функции")==0)
                    {
                        
                        //Антибактериальный (21)
                        if (strripos($param,"Антисептическа"))
                        {
                            $param_new="<param name=\"Эффект\">Антибактериальный</param>";
                            $specialLube=true;
                        }
                        ///Обезболивающий (46)
                        if (strripos($param,"Обезболивающая/Охлаждающая"))
                        {
                            $param_new="<param name=\"Эффект\">Обезболивающий</param>";
                            $specialLube=true;
                        }
                        //Охлаждающий (91)
                        if (strripos($param,"Охлаждающие"))
                        {
                            $param_new="<param name=\"Эффект\">Охлаждающий</param>";
                            $specialLube=true;
                        }
                        //Согревающий (93)
                        if (strripos($param,"Возбуждающая, согревающая")==0||strripos($param,"Согревающие")==0)
                        {
                            $param_new="<param name=\"Эффект\">Согревающий</param>";
                            $specialLube=true;
                        }
                        if (strripos($param,"Съедобный/ C ароматом")||strripos($param,"Съедобный"))
                        {
                            $edible=true;
                            //echo "Нашли сьедобный $itemName - $param<br>";
                            //break;
                        }
                        //Сужение влагалища (7)
                        //Увлажняющий (732)
                    }
                    if (strcmp($paramName,"Основа")==0)
                    {
                        // Водная (843)
                        // Силиконовая (232)
                        // Масляная (15)
                        $param_new=str_ireplace("На водной","Водная",$param);
                        $param_new=str_ireplace("Водно-силиконовая","Силиконовая",$param_new);
                        //$param_new=str_ireplace("Силиконовая","Силиконовая",$param_new);
                        $param_new=str_ireplace("На масляной","Масляная",$param_new);
                        $param_new=str_ireplace("Силиконовая;Силиконовая","Силиконовая",$param_new);
                        $param_new=str_ireplace("Силиконовая;Водная","Силиконовая",$param_new);
                        $param_new=str_ireplace("Водная;Силиконовая","Силиконовая",$param_new);
                        $param_new=str_ireplace("Силиконовая;Силиконовая","Силиконовая",$param_new);
                    }
                    if (strcmp($paramName,"Тип")==0)
                    {
                        $param_new=str_ireplace("Тип","Консистенция",$param);
                        $param_new=str_ireplace("Гель для массажа","Гель",$param_new);
                        $param_new=str_ireplace("Гель, мазь","Гель",$param_new);
                        $param_new=str_ireplace("Крема","Крем",$param_new);
                    }


                    //для каждогго прараметра добавляем его в массив параметров
                    $params_new[]=$param_new;
                }
                //если лубрикант не специальный - он увлажняющий
                if (!$specialLube)
                {
                    $params_new[]="<param name=\"Эффект\">Увлажняющий</param>";
                }
                // вкусы
                // Банан (5)
                // Вишня (17)
                // Клубника (36)
                // Малина (14)
                // Мята (16)
                // Фруктовый (29)
                // Шоколад (14)
                // Нейтральный (780)
                // Другой (80)
                if ($edible)
                {
                    $specialTaste=false;
                    if (strripos($itemName,"банан"))
                    {
                        $params_new[]="<param name=\"Вкус\">Банан</param>";
                        $params_new[]="<param name=\"Запах\">Банан</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"Банан"))
                    {
                        $params_new[]="<param name=\"Вкус\">Банан</param>";
                        $params_new[]="<param name=\"Запах\">Банан</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"виш"))
                    {
                        $params_new[]="<param name=\"Вкус\">Вишня</param>";
                        $params_new[]="<param name=\"Запах\">Вишня</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"клубн"))
                    {
                        $params_new[]="<param name=\"Вкус\">Клубника</param>";
                        $params_new[]="<param name=\"Запах\">Клубника</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"Клубн"))
                    {
                        $params_new[]="<param name=\"Вкус\">Клубника</param>";
                        $params_new[]="<param name=\"Запах\">Клубника</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"малин"))
                    {
                        $params_new[]="<param name=\"Вкус\">Малина</param>";
                        $params_new[]="<param name=\"Запах\">Малина</param>";
                        $specialTaste=true;
                    }
                    
                    if (strripos($itemName,"Яблочн"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"арбуз"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"лимон"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"манго"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"персик"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"тропи"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"фрук"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"Фрук"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"tropical"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"aperol"))
                    {
                        $params_new[]="<param name=\"Вкус\">Фруктовый</param>";
                        $params_new[]="<param name=\"Запах\">Фруктовый</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"шоколад"))
                    {
                        $params_new[]="<param name=\"Вкус\">Шоколад</param>";
                        $params_new[]="<param name=\"Запах\">Шоколад</param>";
                        $specialTaste=true;
                    }
                    if (strripos($itemName,"Шоколад"))
                    {
                        $params_new[]="<param name=\"Вкус\">Шоколад</param>";
                        $params_new[]="<param name=\"Запах\">Шоколад</param>";
                        $specialTaste=true;
                    }
                    if (!$specialTaste)
                    {
                        $params_new[]="<param name=\"Вкус\">Другой</param>";
                        $params_new[]="<param name=\"Запах\">Другой</param>";
                    }
                }


                if (is_array($params_new))
                {
                    $params_new=array_unique($params_new);
                    $tmp[]=null;
                    $params_new=array_diff($params_new,$tmp);
                    $params_new=$this->makeUniqeParams($params_new);

                    foreach ($params_new as $new_param)
                    {
                        //отсекаем страну, которая у нас пустая (NULL)
                        if ($new_param!=null)
                        {
                            $new_params.=$new_param.PHP_EOL;
                        }
                    }
                }
                else
                {
                    echo "No new params for $itemId<br>";
                }
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/
                
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$new_params."</item>";
                //var_dump ($new_item);

            }

            //if ($catId==3045)
            if ($catId==15)
            {
                $specialCat=true;
                //echo "<b>$itemName</b><br>";
                //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)\&]/ui","",$itemName);
                //$name_new=str_replace("quot","",$name_new);
                //echo "$name_new<br>";
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //обнуляем список новых параметров для каждого айтема
                    $param_new=null;
                    if (strcmp($paramName,"Страна")==0)
                    {
                        $param_new=str_ireplace("Страна","Страна производства",$param);
                    }
                    if (strcmp($paramName,"Функции")==0)
                    {
                        if (strripos($param,"С вибрацией"))
                        {
                            $param_new="<param name=\"Наличие вибрации\">+</param>";
                        }
                        else
                        {
                            $param_new="<param name=\"Наличие вибрации\">-</param>";
                        }
                    }
                    if (strcmp($paramName,"Вид")==0)
                    {
                        if (strripos($param,"Реалистичные"))
                        {
                            $param_new="<param name=\"Текстура\">Реалистик</param>";
                        }
                    }
                    if (strcmp($paramName,"Материал")==0)
                    {
                        $param_new=$param;
                    }
                    
                    if (strcmp($paramName,"Диаметр")==0)
                    {
                        $param_new=str_ireplace("Диаметр","Диаметр, см",$param);
                    }
                    if (strcmp($paramName,"Длина")==0)
                    {
                        $param_new=str_ireplace("Длина","Глубина, см",$param);
                    }

                    $params_new[]=$param_new;
                }
                $params_new[]="<param name=\"Тип товара\">Мастурбатор</param>";
                if (is_array($params_new))
                {
                    $params_new=array_unique($params_new);
                    $tmp[]=null;
                    $params_new=array_diff($params_new,$tmp);
                    $params_new=$this->makeUniqeParams($params_new);
                    foreach ($params_new as $new_param)
                    {
                        //отсекаем страну, которая у нас пустая (NULL)
                        if ($new_param!=null)
                        {
                            $new_params.=$new_param.PHP_EOL;
                        }
                    }
                }
                else
                {
                    echo "No new params for $itemId<br>";
                }
               
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/
                
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$new_params."</item>";
                //var_dump ($new_item);
            }
            //if ($catId==3056)
            if ($catId==18)
            {
                $specialCat=true;
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    $paramVal=$this->getParamVal($param);
                    //обнуляем список новых параметров для каждого айтема
                    $param_new=null;
                    if (strcmp($paramName,"Пол")==0)
                    {
                        if (strripos($param,"Для женщин"))
                        {
                            $param_new="<param name=\"Пол куклы\">Мужской</param>";  
                        }
                        if (strripos($param,"Для мужчин"))
                        {
                            $param_new="<param name=\"Пол куклы\">Женский</param>";  
                        }
                        if (strripos($param,"Другое"))
                        {
                            $param_new="<param name=\"Пол куклы\">Мужской</param>";  
                        }
                    }
                    if (strcmp($paramName,"Материал")==0)
                    {
                        $param_new=str_ireplace("Материал","Материал куклы",$param);
                        
                        $param_new=str_ireplace("Резина","Латекс",$param);
                    }
                    if (strcmp($paramName,"Вид")==0)
                    {
                        /*$isVag=false;
                        $isAnus=false;
                        $isMouth=false;*/
                        if (strripos($param,"Рот"))
                        {
                            echo "4555";
                            $param_new.="<param name=\"Рот\">+</param>";
                        }
                        if (strripos($param,"Вагина"))
                        {
                            $param_new.="<param name=\"Вагина\">+</param>";
                        }
                        if (strripos($param,"Анус"))
                        {
                            $param_new.="<param name=\"Анус\">+</param>";
                        }
                        
                    }
                    $params_new[]=$param_new;
                    

                }
                if (is_array($params_new))
                {
                    $params_new=array_unique($params_new);
                    $tmp[]=null;
                    $params_new=array_diff($params_new,$tmp);
                    $params_new=$this->makeUniqeParams($params_new);
                    foreach ($params_new as $new_param)
                    {
                        //отсекаем страну, которая у нас пустая (NULL)
                        if ($new_param!=null)
                        {
                            $new_params.=$new_param.PHP_EOL;
                        }
                    }
                }
                else
                {
                    echo "No new params for $itemId<br>";
                }
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/
                
                //бывает случай, когда позиция у нас не имеет ни одного парамептра. Тогда у нее появляется лишний тег </item>. На всякий случай убираем его
                $itemHead=str_ireplace("</item>","",$itemHead);
                //получаем новый айтем (не забываем закрывающий тег)
                $new_item=$itemHead.$new_params."</item>";
                //var_dump ($new_item);
            }
            
            if (!$specialCat)
            {
                $new_item=$item;
                //echo htmlspecialchars($item);
                //echo "<br>";

            }
            //чистим описание\
            //$new_item=$this->delDescription($new_item);
            //тут будем сорбирать все позиции
            $items_new.=$new_item.PHP_EOL;
            //echo "kjuhkjhhkjhjk";
        }
        //var_dump($items_new);
        $items_new="<items>".$items_new.PHP_EOL."</items>";
        //начинаем собирать финальную ХМЛку
        $XMLnew=$xmlHead.PHP_EOL."</categories>".PHP_EOL.$items_new.PHP_EOL."</price>";
        //var_dump($XMLnew);
        //$XMLnew=str_ireplace(">",">".PHP_EOL,$XMLnew);

        file_put_contents("new_hotline-v3.xml",$XMLnew);
    }

    private function delDescription($item)
    {
        $item=preg_replace("#<description>(.*?)<\/description>#","<description></description>",$item);
        //удяляем лишние пробелы
        $item = preg_replace('/\s+/', ' ', $item);
        return $item;
    }

}


class CleanName
{
    private function stripName($item)
    {
        $name=$this->getItemName($item);
        $id=$this->getItemId($item);
        $name=str_ireplace($id,"",$name);
        return $name;
    }

    private function cleanUpper($name)
    {
        $name = preg_replace('/\s+/', ' ', $name);
        $words=explode(" ",$name);
        //echo "<pre>".print_r($words)."</pre>";
        foreach ($words as $word)
        {
            //echo "$word<br>";
            if (ctype_upper($word))
            {
                //echo "find upper - $word<br>";
                $word=strtolower($word);
                $word=ucwords($word);
            }
            $new_name.=" $word";
        }
        $new_name=trim($new_name);
        return $new_name;
    }

    private function readFile()
    {
        $xml=file_get_contents('new_hotline-v3.xml');
        return $xml;
    }

    private function setItemName($item,$name)
    {
        $old_name=$this->getItemName($item);
        $new_item=str_ireplace($old_name,$name,$item);
        return $new_item;
    }

    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    private function getItemId($item)
    {
        preg_match("#<code>(.*?)<\/code>#",$item,$matches);
        $name=$matches[1];
        return $name;
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

    private function getXMLhead($txt)
    {
        $pos=strpos($txt,"</categories>");
        $xmlhead=substr($txt,0,$pos);
        return $xmlhead;
    }

    private function getColor($item)
    {
        preg_match("#<param name=\"Цвет\">(.*?)</param>#",$item,$matches);
        $color=$matches[1];
        return $color;
    }

    public function test ()
    {
        $oldXML=$this->readFile();
        $xml_new=$this->stripHead($oldXML);
        $items=$this->getItemsArr($xml_new);
        foreach($items as $item)
        {
            $name_old=$this->getItemName($item);
            $item=str_ireplace("ВИТ","",$item);
            $name=$this->stripName($item);
            
            $name=$this->cleanUpper($name);

            $name=str_ireplace("самоуд ","",$name); 
            $name=str_ireplace("круж ","",$name);
            $name=str_ireplace("туб.пласт ","",$name);
            $name=str_ireplace("мужск.","мужской ",$name);
            $name=str_ireplace(";",",",$name);
            
            $name=str_ireplace("туб пластик ","",$name);
            $name=str_ireplace("Sml ","S/M/L",$name);
            $name=str_ireplace("Массаж ","Массажное",$name);
            $name=str_ireplace("эр. кольцом","эрекционным кольцом",$name);
            
            $name=str_ireplace("50 оттен.","50 оттенков",$name);
            $name=str_ireplace("Эрекц ","Эрекционное",$name);
            $name=str_ireplace(" кибркоже ребристый гнущийся "," ",$name);
            $name=str_ireplace(" ж. "," ",$name);
            $name=str_ireplace(" беж."," ",$name);
            $name=str_ireplace(" усиленная стимуляция возбуждения","",$name);
            $name=str_ireplace("любрикант","лубрикант",$name);
            $name=str_ireplace("ppy","Трусы латекс Happy",$name);
            $name=str_ireplace("вагин лело","вагинальные",$name);
            //Анал.
            $name_new=str_ireplace("Womanizer Premium White/Chrome", "Womanizer Premium White",$name_new);
            $name=str_ireplace("Анал.","Анальная ",$name);
            $name=str_ireplace("Насадка очень длинная обрезать ножницами","Насадка на половой член",$name);

            if (strcmp($name_old,$name)==0)
            {
                $item=str_ireplace($name_old,$name,$item);
                $old_name=$name;
            }
            $color=$this->getColor($item);
            $name=$name." ($color)";
            $name=str_ireplace(" ()","",$name);
            $item=str_ireplace($name_old,$name,$item);
            $items_new.=$item;

            //echo "$name_old - $name<br>";
            //break;
        }
        $xmlHead=$this->getXMLhead($oldXML);
        $XML_new=$xmlHead.PHP_EOL."</categories>".PHP_EOL.'<items>'.PHP_EOL.$items_new.PHP_EOL.'</items>'.PHP_EOL."</price>";
        /*$XML_new=str_ireplace("<categoryId>53</categoryId>","<categoryId>20</categoryId>",$XML_new);
        $XML_new=str_ireplace("<categoryId>168</categoryId>","<categoryId>25</categoryId>",$XML_new);
        $XML_new=str_ireplace("<categoryId>166</categoryId>","<categoryId>10</categoryId>",$XML_new);
        $XML_new=str_ireplace("<categoryId>169</categoryId>","<categoryId>35</categoryId>",$XML_new);*/
        $XML_new=str_ireplace("&quot;","\"",$XML_new);
        $XML_new=str_ireplace("&quot,","\"",$XML_new);
        $XML_new=str_ireplace("&amp,","and",$XML_new);
        $XML_new=str_ireplace("&apos,","'",$XML_new);
        $XML_new=str_ireplace("&lt,","-",$XML_new);
        $XML_new=str_ireplace("<vendor>Fifty Shades of Grey</vendor>","<vendor>Lovehoney</vendor>",$XML_new);
        file_put_contents("new_hotline-v4.xml",$XML_new);
    }
}

$test = new MakeTree();
$test->catReplace();
echo "<b>Done tree</b><br>";

$test=new Hotline;
$test->parseXML();
echo "<b>Parse Done</b><br>";

$test = new CleanName();
$test->test();
echo "<b>Names v2 Done</b><br>";
