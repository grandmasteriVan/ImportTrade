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


                $params_new=array_unique($params_new);
                $tmp[]=null;
                $params_new=array_diff($params_new,$tmp);
                $params_new=$this->makeUniqeParams($params_new);
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
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
                    }
                    if (strcmp($paramName,"Диаметр")==0)
                    {
                        $param_new=str_ireplace("Диаметр","Диаметр, см",$param);
                    }
                    $params_new[]=$param_new;
                }
                $params_new[]="<param name=\"Тип товара\">Мастурбатор</param>";
                $params_new=array_unique($params_new);
                $tmp[]=null;
                $params_new=array_diff($params_new,$tmp);
                $params_new=$this->makeUniqeParams($params_new);
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
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
                    

                }
                $params_new=array_unique($params_new);
                $tmp[]=null;
                $params_new=array_diff($params_new,$tmp);
                $params_new=$this->makeUniqeParams($params_new);
                /*echo "<b>$itemName</b><br>";
                echo "<pre>";
                print_r($params_new);
                echo "</pre>";*/
                foreach ($params_new as $new_param)
                {
                    //отсекаем страну, которая у нас пустая (NULL)
                    if ($new_param!=null)
                    {
                        $new_params.=$new_param.PHP_EOL;
                    }
                    
                }
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
            Анал.
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

            echo "$name_old - $name<br>";
            //break;
        }
        $xmlHead=$this->getXMLhead($oldXML);
        $XML_new=$xmlHead.PHP_EOL."</categories>".PHP_EOL.'<items>'.PHP_EOL.$items_new.PHP_EOL.'</items>'.PHP_EOL."</price>";
        /*$XML_new=str_ireplace("<categoryId>53</categoryId>","<categoryId>20</categoryId>",$XML_new);
        $XML_new=str_ireplace("<categoryId>168</categoryId>","<categoryId>25</categoryId>",$XML_new);
        $XML_new=str_ireplace("<categoryId>166</categoryId>","<categoryId>10</categoryId>",$XML_new);
        $XML_new=str_ireplace("<categoryId>169</categoryId>","<categoryId>35</categoryId>",$XML_new);*/
        file_put_contents("new_hotline-v4.xml",$XML_new);

    }


    
}


/**
 * csvTest
 */
class csvTest
{
    /*public function test()
    {
        $file=fopen("hotline_tree.csv","r");
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE)
        {
            $csv[]=$data;
        }
        echo "<pre>".print_r($csv)."</pre>";
    }*/

    public $XML;

    //protected $hl;    
    /**
     * __construct
     *
     * @param  mixed $HL
     * @return void
     */
    /*public function __construct(Hotline $HL)
    {
        $this->hl=$HL;
    }*/
        
    /**
     * readXML
     *
     * @return void
     */
    private function readXML()
    {
        $csv=file_get_contents('hotline_ua-v1.csv');
        return $csv;
    }
    
    /**
     * readCsv
     *
     * @return void
     */
    private function readCsv()
    {
        $file=fopen("price.csv","r");
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE)
        {
            $csv[]=$data;
        }
        array_shift($csv);
        //echo "<pre>".print_r($csv)."</pre>";
        return $csv;
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
     * getProperName
     *
     * @param  mixed $name
     * @return void
     */
    private function getProperName($name)
    {
        if (preg_match("#\"(.+?)\"#",$name,$matches))
        {
            $propName=$matches[1];
        }
        return "\"".$propName."\" ";
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
        echo $name."<br>";
        $name_new=str_replace("50 оттенков серого","",$name);
        
        $name_new=str_replace("15 см диаметр 5","7.5",$name_new);
        $name_new=str_replace("мл","ml",$name_new);
        $name_new=str_replace("грамм","gr",$name_new);
        $name_new=str_replace($vendor,"",$name_new);
        $name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)\&\-\.\%]/ui","",$name_new);
        $name_new=str_replace("()","",$name_new);
        $name_new=str_replace(" , ","",$name_new);
        $name_new=str_replace("(, )","",$name_new);
        $name_new=str_replace("( ),","",$name_new);

        
        $name_new=str_replace("L/XL L/XL","L/XL",$name_new);
        $name_new=str_replace("XL XL","XL",$name_new);
        $name_new=str_replace("XXL XXL","XXL",$name_new);
        $name_new=str_replace("S/M S/M","S/M",$name_new);
        $name_new=str_replace("M M","M",$name_new);
        $name_new=str_replace("S S","S",$name_new);
        $name_new=str_replace("L L","L",$name_new);
        $name_new=str_replace("XL/XXL XL/XXL","XL/XXL",$name_new);
        $name_new=str_replace("O/S One size","One size",$name_new);
        $name_new=str_replace("One Size One size","One size",$name_new);
        $name_new=str_replace("XL/2XL XL/XXL","XL/XXL",$name_new);
        $name_new=str_replace("S/L One size","One size",$name_new);
        $name_new=str_replace("&","and",$name_new);
        
        $propName="";
        if (strripos($name,"\""))
        {
            $propName=$this->getProperName($name);
        }
        if(stripos($name,"40167 / 763010 /"))
        {
            $name_new="\"We aim to please\" ".$name_new;
        }
        if(stripos($name,"54811 T/"))
        {
            $name_new="\"Greedy girl\" ".$name_new;
        }
        if(stripos($name,"40168 / 576310 /"))
        {
            $name_new="\"Insatiable Desire\" ".$name_new;
        }
        if(stripos($name,"48293 T/811940 /"))
        {
            $name_new="\"Charlie tango\" ".$name_new;
        }
        if(stripos($name,"74956"))
        {
            $name_new="\"Greedy girl\" ".$name_new;
        }
        if(stripos($name,"74947"))
        {
            $name_new="\"Delicious tingles\" ".$name_new;
        }
        if(stripos($name,"74970"))
        {
            $name_new="\"Wicked weekend tingles\" ".$name_new;
        }
        if(stripos($name,"74791 /74971"))
        {
            $name_new="\"Greedy girl play box\" ".$name_new;
        }
        if(stripos($name,"59953"))
        {
            $name_new="\"A perfrct O\" ".$name_new;
        }
        if(stripos($name,"40170 / 576336 Ю"))
        {
            $name_new="\"Yours and mine\" ".$name_new;
        }
        if(stripos($name,"74968"))
        {
            $name_new="Pleasure overload \"Sweet sensations\" ".$name_new;
        }
        if(stripos($name,"73341"))
        {
            $name_new="\"There's only sensation\" ".$name_new;
        }
        if(stripos($name,"63954"))
        {
            $name_new="\"Adrenaline Spikes\" ".$name_new;
        }
        if(stripos($name,"40190 T/ 45599 /"))
        {
            $name_new="\"Silky caress\" ".$name_new;
        }
        if(stripos($name,"40179 / 530522 Ю/"))
        {
            $name_new="\"Soft Limits\" ".$name_new;
        }
        if(stripos($name,"41764 T/ 45602 /"))
        {
            $name_new="\"Cleansing\" ".$name_new;
        }
        

        if (strripos($name,"Вибрат")||strripos($name,"вибрат"))
        {
            $name_new=$vendor." Вибратор $propName".$name_new;
        }
        if (strripos($name,"Виброяйцо"))
        {
            $name_new=$vendor." Виброяйцо $propName".$name_new;
        }
        if (strripos($name,"страпон")||strripos($name,"Страпон")||strripos($name,"фаллопротез"))
        {
            $name_new=$vendor." Cтрапон $propName".$name_new;
        }
        if (strripos($name,"Вакуумный стимулятор")||strripos($name,"клиторальный стимулятор"))
        {
            $name_new=$vendor." Стимулятор клитора $propName".$name_new;
        }
        if (strripos($name,"Фаллоимитатор")||strripos($name,"фаллоимитатор"))
        {
            $name_new=$vendor." Фаллоимитатор $propName".$name_new;
        }
        if (strripos($name,"Бодистокинг"))
        {
            $name_new=$vendor." Комбинезон $propName".$name_new;
        } 
        if (strripos($name,"Комбинезон")||strripos($name,"комбинезон"))
        {
            $name_new=$vendor." Комбинезон $propName".$name_new;
        }
        if (strripos($name,"Эротический костюм-сетка"))
        {
            $name_new=$vendor." Комбинезон $propName".$name_new;
        }
        if (strripos($name,"Анальная пробка")||strripos($name,"анальная пробка")||strripos($name,"Анальный расширитель")||strripos($name,"Анальная металлическая пробка")||strripos($name,"Набор металлических анальных пробок")||strripos($name,"Анальный плаг")||strripos($name,"Пробка анальная"))
        {
            $name_new=$vendor." Анальная пробка $propName".$name_new;
        }
        if (strripos($name,"Набор секс-игрушек"))
        {
            $name_new=$vendor." Набор секс игрушек $propName".$name_new;
        }
        if (strripos($name,"Подарочный набор"))
        {
            $name_new=$vendor." Набор секс игрушек $propName".$name_new;
        }
        if (strripos($name,"Клиторальный вибромассажер"))
        {
            $name_new=$vendor." Стимулятор клитора $propName".$name_new;
        }
        if (strripos($name,"вибропуля"))
        {
            $name_new=$vendor." Вибратор $propName".$name_new;
        }
        if (strripos($name,"Анальные бусы")||strripos($name,"анальная ёлочка"))
        {
            $name_new=$vendor." Анальные шарики $propName".$name_new;
        }
        if (strripos($name,"Анальный стимулятор"))
        {
            $name_new=$vendor." Анальный вибратор $propName".$name_new;
        }
        if (strripos($name,"Массажер простаты")||strripos($name,"ассажер простаты"))
        {
            $name_new=$vendor." Массажер простаты $propName".$name_new;
        }
        if (strripos($name,"Вагинальные шарики")||strripos($name,"вагинальные шарики"))
        {
            $name_new=$vendor." Вагинальные шарики $propName".$name_new;
        }
        if (strripos($name,"мастурбатор")||strripos($name,"Мастурбатор"))
        {
            $name_new=$vendor." Мастурбатор $propName".$name_new;
        }
        if (strripos($name,"Вагина")||strripos($name,"Мастурбатор-вагина")||strripos($name,"вагина"))
        {
            $name_new=$vendor." Вагина-мастурбатор $propName".$name_new;
        }
        if (strripos($name,"Эрекционное")||strripos($name,"Вибро-кольцо")||strripos($name,"эрекционное")||strripos($name,"лассо")||strripos($name,"Лассо")||strripos($name,"эрекционных колец")) 
        {
            $name_new=$vendor." Эрекционное кольцо $propName".$name_new;
        }
        if (strripos($name,"насадка")||strripos($name,"Насадка"))
        {
            $name_new=$vendor." Насадка на член $propName".$name_new;
        }
        if (strripos($name,"Присоски для сосков"))
        {
            $name_new=$vendor." Присоски для сосков $propName".$name_new;
        }
        if (strripos($name,"помпа")||strripos($name,"Помпа"))
        {
            $name_new=$vendor." Вакуумная помпа $propName".$name_new;
        }
        if (strripos($name,"Кукла")||strripos($name,"кукла"))
        {
            $name_new=$vendor." Секс-кукла $propName".$name_new;
        }
        if (strripos($name,"Зажимы на соски и анальная пробка с перышками из серии"))
        {
            $name_new=$vendor." Набор Feather nipple clamps & butt plug $propName".$name_new;
        }
        if (strripos($name,"Набор Nuru")||strripos($name,"Набор для влюбленных")||strripos($name,"Набор для интригующего вечера")||strripos($name,"Набор для чувственных ласк")||strripos($name,"Набор удовольствий")||strripos($name,"Набор Юбилейный")||strripos($name,"Органический набор")||strripos($name,"Горячее сердце для массажа")||strripos($name,"Набор к спонтанному роману")||strripos($name,"Набор массажных масел")||strripos($name,"Подарочная открытка с набором")) 
        {
            $name_new=$vendor." Набор для эротического массажа $propName".$name_new;
        }
        if (strripos($name,"Набор для жемчужного массажа"))
        {
            $name_new=$vendor." Набор для массажа Pearls lust massage $propName".$name_new;
        }
        if (strripos($name,"Набор для прелюдии Перегрузка удовольствия")||strripos($name,"Сексуальный адвент-календарь")||strripos($name,"Чемодан ваших тайных"))
        {
            $name_new=$vendor." Набор для секс игр $propName".$name_new;
        }
        if (strripos($name,"Набор Очки + браслет"))
        {
            $name_new=$vendor." VR набор $propName".$name_new;
        }
        if (strripos($name,"Набор чувственной косметики для тела"))
        {
            $name_new=$vendor." Набор чувственной косметики для тела Gateway Kit $propName".$name_new;
        }
        if (strripos($name,"PHEROMON")||strripos($name,"феромон")||strripos($name,"Феромон")||strripos($name,"Духи")||strripos($name,"духи"))
        {
            $name_new=$vendor." Духи с феромонами $propName".$name_new;
        }
        if (strripos($name,"Бомбочка для ванны с феромонами Sexy"))
        {
            $name_new=$vendor." Бомбочка для ванны с феромонами Sexylicius $propName".$name_new;
        }
        if (strripos($name,"Духи для Женщин и Мужчин"))
        {
            $name_new=$vendor." Набор духов с феромонами $propName".$name_new;
        }
        if (strripos($name,"Массажное масло")||strripos($name,"массажное масло")||strripos($name,"Масло для массажа")||strripos($name,"Масло для поцелуев")||strripos($name,"Массажный гель")||strripos($name,"массажная пена")||strripos($name,"пенка для массажа")||strripos($name,"Гель для массажа")) 
        {
            $name_new=$vendor." Массажное масло $propName".$name_new;
        }
        if (strripos($name,"Вибромассажер для головы"))
        {
            $name_new=$vendor." Вибромассажер для головы $propName".$name_new;
        }
        if (strripos($name,"Колесо Вартенберга"))
        {
            $name_new=$vendor." Колесо Вартенберга $propName".$name_new;
        }
        if (strripos($name,"лубрикант")||strripos($name,"Лубрикант")||strripos($name,"Возбуждающая смазка")||strripos($name,"Гель Erotist для женщин")||strripos($name,"Крем-смазка")||strripos($name,"крем-смазка")||strripos($name,"Пробник смазки")||strripos($name,"Смазка пробник")||strripos($name,"Смазка на водной основе")||strripos($name,"Спрей для усиления слюноотделения")||strripos($name,"Пролонгатор")||strripos($name,"пролонгатор")||strripos($name,"гибридный гель")||strripos($name,"масло для женщин")||strripos($name,"Возбуждающий Гель")||strripos($name,"Возбуждающий гель")||strripos($name,"Гель для сужения")||strripos($name,"Гель для усиления")||strripos($name,"Клиторальный гель")||strripos($name,"Спрей для глубокого минета")||strripos($name,"Усилитель оргазма")) 
        {
            $name_new=$vendor." Лубрикант $propName".$name_new;
        }
        if (strripos($name,"Стимулирующий крем")||strripos($name,"Согревающий стимулирующий гель")||strripos($name,"Крем для клитора")||strripos($name,"Крем возбуждающий")||strripos($name,"Женский стимулирующий гель")||strripos($name,"Гель для стимуляции сосков"))
        {
            $name_new=$vendor." Возбуждающий крем $propName".$name_new;
        }
        if (strripos($name,"Анальная смазка")||strripos($name,"Анальная крем-смазка")||strripos($name,"Анальный гель")||strripos($name,"Гель анальный")||strripos($name,"Смазка для анального")||strripos($name,"Спрей для анального"))
        {
            $name_new=$vendor." Анальный лубрикант $propName".$name_new;
        }
        if (strripos($name,"Масло для массажа АФРОДИЗИАК"))
        {
            $name_new=$vendor." Массажное масло Sexy therapy APHRODISIAC $propName".$name_new;
        }
        if (strripos($name,"Масло для массажа ТАЙНА"))
        {
            $name_new=$vendor." Массажное масло Sexy therapy The Secret $propName".$name_new;
        }
        if (strripos($name,"Масло для чувственного массажа ЛЮБОВЬ"))
        {
            $name_new=$vendor." Массажное масло Sexy therapy Amor $propName".$name_new;
        }
        if (strripos($name,"Набор съедобное массажное масло и перо, вкус Сахарной ваты"))
        {
            $name_new=$vendor." Набор для эротического массажа Sexy therapy Cotton candy $propName".$name_new;
        }
        if (strripos($name,"Набор съедобное массажное масло с пером, Клубничка"))
        {
            $name_new=$vendor." Набор для эротического массажа Sexy therapy Strawberry $propName".$name_new;
        }
        if (strripos($name,"Набор съедобное массажное масло с пером, Яблоко"))
        {
            $name_new=$vendor." Набор для эротического массажа Sexy therapy Apple $propName".$name_new;
        }
        if (strripos($name,"Масло массажное с ароматом Жасмина"))
        {
            $name_new=$vendor." Массажное масло Jasmin $propName".$name_new;
        }
        if (strripos($name,"Для настоящего ПрофессиАНАЛА"))
        {
            $name_new=$vendor." Набор лубрикантов \"Для настоящего ПрофессиАНАЛА\" $propName".$name_new;
        }
        if (strripos($name,"Набор Для 100%-го Секс-эксперта"))
        {
            $name_new=$vendor." Набор лубрикантов \"Набор Для 100%-го Секс-эксперта\" $propName".$name_new;
        }
        if (strripos($name,"Для Секс Богинь и Обольстительниц"))
        {
            $name_new=$vendor." Набор лубрикантов \"Для Секс Богинь и Обольстительниц\" $propName".$name_new;
        }
        if (strripos($name,"Набор смазок тем, кто любит погорячее"))
        {
            $name_new=$vendor." Набор лубрикантов \"Тем, кто любит погорячее\" $propName".$name_new;
        }
        if (strripos($name,"Набор эротической косметики КОРОБОЧКА ЛЮБВИ"))
        {
            $name_new=$vendor." Набор лубрикантов Love box Passion night $propName".$name_new;
        }
        if (strripos($name,"бюстгальтер"))
        {
            $name_new=$vendor." Бюстгальтер $propName".$name_new;
        }
        if (strripos($name,"боди")||strripos($name,"Боди"))
        {
            $name_new=$vendor." Боди $propName".$name_new;
        }
        if (strripos($name,"Корсет"))
        {
            $name_new=$vendor." Корсет $propName".$name_new;
        }
        if (strripos($name,"платье")||strripos($name,"Платье"))
        {
            $name_new=$vendor." Сексуальное платье $propName".$name_new;
        }
        if (strripos($name,"Пояс для чулок"))
        {
            $name_new=$vendor." Пояс для чулок $propName".$name_new;
        }
        if (strripos($name,"Трусики")||strripos($name,"Трусы")||strripos($name,"стринги")||strripos($name,"трусики"))
        {
            $name_new=$vendor." Трусики $propName".$name_new;
        }
        if (strripos($name,"Мужские виниловые трусы"))
        {
            $name_new=$vendor." Мужские виниловые трусы $propName".$name_new;
        }
        if (strripos($name,"Шорты")||strripos($name,"шорты"))
        {
            $name_new=$vendor." Шорты $propName".$name_new;
        }
        if (strripos($name,"Чулки"))
        {
            $name_new=$vendor." Чулки $propName".$name_new;
        }
        if (strripos($name,"Юбка"))
        {
            $name_new=$vendor." Юбка $propName".$name_new;
        }
        if (strripos($name,"Бебидолл"))
        {
            $name_new=$vendor." Бэби-долл $propName".$name_new;
        }
        if (strripos($name,"Костюм горничной")||strripos($name,"остюм сексуальной горничной")||strripos($name,"Костюм Горничной"))
        {
            $name_new=$vendor." Костюм горничной $propName".$name_new;
        }
        if (strripos($name,"сорочка")||strripos($name,"Сорочка"))
        {
            $name_new=$vendor." Сорочка $propName".$name_new;
        }
        if (strripos($name,"Халат"))
        {
            $name_new=$vendor." Эротический пеньюар $propName".$name_new;
        }
        if (strripos($name,"футболка")||strripos($name,"Футболка"))
        {
            $name_new=$vendor." Футболка $propName".$name_new;
        }
        if (strripos($name,"Штаны")||strripos($name,"штаны"))
        {
            $name_new=$vendor." Штаны $propName".$name_new;
        }
        if (strripos($name,"перчатки")||strripos($name,"Перчатки"))
        {
            $name_new=$vendor." Перчатки $propName".$name_new;
        }
        if (strripos($name,"Маска"))
        {
            $name_new=$vendor." Маска $propName".$name_new;
        }
        if (strripos($name,"пэстисы")||strripos($name,"Пестисы"))
        {
            $name_new=$vendor." Пэстисы $propName".$name_new;
        }
        if (strripos($name,"Секси-ученицы")||strripos($name,"студентки"))
        {
            $name_new=$vendor." Костюм школьницы $propName".$name_new;
        }
        if (strripos($name,"костюм стюардессы"))
        {
            $name_new=$vendor." Костюм стюардессы $propName".$name_new;
        }
        if (strripos($name,"Костюм кош"))
        {
            $name_new=$vendor." Костюм кошечки $propName".$name_new;
        }
        if (strripos($name,"учительницы"))
        {
            $name_new=$vendor." Костюм учительницы $propName".$name_new;
        }
        if (strripos($name,"бондажный набор")||strripos($name,"Набор для бондажа"))
        {
            $name_new=$vendor." Бондажный набор $propName".$name_new;
        }
        if (strripos($name,"Лента для фиксации"))
        {
            $name_new=$vendor." Бондажная лента $propName".$name_new;
        }
        if (strripos($name,"Кляп"))
        {
            $name_new=$vendor." Кляп $propName".$name_new;
        }
        if (strripos($name,"Массажная свеча")||strripos($name,"Свеча для массажа")||strripos($name,"Свеча массажная"))
        {
            $name_new=$vendor." Массажная свеча $propName".$name_new;
        } 
        if (strripos($name,"Наручники"))
        {
            $name_new=$vendor." Наручники $propName".$name_new;
        }
        if (strripos($name,"Ошейник"))
        {
            $name_new=$vendor." Ошейник $propName".$name_new;
        }
        if (strripos($name,"Парик"))
        {
            $name_new=$vendor." Парик $propName".$name_new;
        }
        if (strripos($name,"Комплект"))
        {
            $name_new=$vendor." Комплект $propName".$name_new;
        } 
        if (strripos($name,"портупея")||strripos($name,"Портупея"))
        {
            $name_new=$vendor." Портупея $propName".$name_new;
        }
        if (strripos($name,"Возбуждающие капли")||strripos($name,"Возбуждающий бальзам")||strripos($name,"Капли для возбуждения")||strripos($name,"Капли возбуждающие"))
        {
            $name_new=$vendor." Возбуждающие капли $propName".$name_new;
        }
        if (strripos($name,"Клизма")||strripos($name,"Анальный душ"))
        {
            $name_new=$vendor." Клизма $propName".$name_new;
        }
        if (strripos($name,"Очиститель")||strripos($name,"очиститель")||strripos($name,"Очищающее")||strripos($name,"Очищающий")||strripos($name,"Спрей для ухода за секс игрушками"))
        {
            $name_new=$vendor." Очиститель для игрушек $propName".$name_new;
        }
        if (strripos($name,"Салфетки для интимной гигиены"))
        {
            $name_new=$vendor." Салфетки для интимной гигиены $propName".$name_new;
        }
        if (strripos($name,"Антибактериальное средство"))
        {
            $name_new=$vendor." Антибактериальное средство для интимных игрушек $propName".$name_new;
        }
        if (strripos($name,"Эротическая майка"))
        {
            $name_new=$vendor." Майка эротическая $propName".$name_new;
        }

        
        if (strripos($name,"О-кей для двоих"))
        {
            $name_new=$vendor." О-кей для двоих $propName".$name_new;
        }
        if (strripos($name,"(21596)"))
        {
            $name_new=$vendor." Warming desserts Fresh delicious donuts lubricant $propName".$name_new;
        }
        if (strripos($name,"(10023 / 66875)"))
        {
            $name_new=$vendor." GO 50 ml. $propName".$name_new;
        }
        if (strripos($name,"(10030 / 66876)"))
        {
            $name_new=$vendor." GO 100 ml. $propName".$name_new;
        }
        if (strripos($name,"(623822 Ю/)"))
        {
            $name_new=$vendor." Relax fisting gel $propName".$name_new;
        }
        if (strripos($name,"(11704 Ю/)"))
        {
            $name_new=$vendor." AQUAglide $propName".$name_new;
        }
        if (strripos($name,"(11010 Ю/)"))
        {
            $name_new=$vendor." BIOglide anal $propName".$name_new;
        }
        if (strripos($name,"(73022 B/20014 /)"))
        {
            $name_new=$vendor." О-кей anal $propName".$name_new;
        }
        if (strripos($name,"(20463)"))
        {
            $name_new=$vendor." Fan flavors Sexy strawberry warming lubricant $propName".$name_new;
        }
        if (strripos($name,"(20466)"))
        {
            $name_new=$vendor." Fan flavors Popp'n cherry lubricant $propName".$name_new;
        }
        if (strripos($name,"(10061 / 66881)"))
        {
            $name_new=$vendor." YES 50 ml. $propName".$name_new;
        }
        if (strripos($name,"(10078 / 66882)"))
        {
            $name_new=$vendor." YES 100 ml. $propName".$name_new;
        }
        if (strripos($name,"УСЛАДА")||strripos($name,"Услада"))
        {
            $name_new=$vendor." Услада $propName".$name_new;
        }
        if (strripos($name,"(86895)"))
        {
            $name_new=$vendor." Warming water-based lubricant $propName".$name_new;
        }
        if (strripos($name,"(220161)"))
        {
            $name_new=$vendor." Prolong man for longer pleasure $propName".$name_new;
        }
        if (strripos($name,"(21357)")||strripos($name,"(321357)"))
        {
            $name_new=$vendor." Orgasm drops clitoral arousal $propName".$name_new;
        }
        if (strripos($name,"(21340)"))
        {
            $name_new=$vendor." Electric Fellatio $propName".$name_new;
        }
        if (strripos($name,"ОЩУЩЕНИЯ И ТРИУМФ"))
        {
            $name_new=$vendor." Sensations & prowess $propName".$name_new;
        }
        if (strripos($name,"(21180)"))
        {
            $name_new=$vendor." Xtra hard power gel for him $propName".$name_new;
        }
        if (strripos($name,"(27479)"))
        {
            $name_new=$vendor." Vivify $propName".$name_new;
        }
        if (strripos($name,"(3101 Ю/)"))
        {
            $name_new=$vendor." Extasia $propName".$name_new;
        }
        if (strripos($name,"(15325)"))
        {
            $name_new=$vendor." Vibration! strawberry $propName".$name_new;
        }
        if (strripos($name,"(21197)"))
        {
            $name_new=$vendor." Sexy vibe! $propName".$name_new;
        }
        if (strripos($name,"(21210)"))
        {
            $name_new=$vendor." Sexy vibe! hot $propName".$name_new;
        }
        if (strripos($name,"(121265)"))
        {
            $name_new=$vendor." Touro $propName".$name_new;
        }
        if (strripos($name,"(20634)"))
        {
            $name_new=$vendor." Бомбочка для ванн Funbulous bath bomb with pheromones ";
        }
        if (strripos($name,"(20627)"))
        {
            $name_new=$vendor." Бомбочка для ванн Spicyness bath bomb with pheromones (20627)";
        }
        if (strripos($name,"(54726 /)"))
        {
            $name_new=$vendor." Black mont $propName".$name_new;
        }
        if (strripos($name,"(86888)"))
        {
            $name_new=$vendor." Cleaning spray $propName".$name_new;
        }
        if (strripos($name,"(000109)"))
        {
            $name_new=$vendor." Соль для ванн Treasures of sea (000109)";
        }
        if (strripos($name,"(117022)"))
        {
            $name_new=$vendor." Секс-кукла Jennifer (117022)";
        }
        if (strripos($name,"(20503 Ю/)"))
        {
            $name_new=$vendor." Возбуждающий крем  Casanova cream (20503 Ю/)";
        }

        //$test = preg_replace('~\[.*?\]~','[]',$test);
        $name_new=preg_replace('~\(.*?\)~','',$name_new);
        $color=$this->getColorAndCode($name);
        //echo "<pre>".print_r($color)."</pre>";
        $tmp=explode(";",$color[1]);
        $code=$color[0];
        $color=$tmp[0];
        //$code=$this->getCode($name);
        //echo "$name_new, $code, $color<br>";
        $name_new=$name_new." $color ($code)";
        $name_new=str_replace($vendor,"",$name_new);
        $name_new=$vendor." $name_new";
        
        //$name_new=$vendor." ".$name_new;
        //можно сделатьб так. Во многих товарах у нас есть куча лишнего в описаннию. Но тут есть ньюанс - у нас есть позиции, где модель не указана в названии. Такие позиции как раз не распознаются
        //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)]/ui","",$name_new);
        //$name_new=str_replace("quot","",$name_new);
        //$name_new=str_replace("(, ","(",$name_new);
        //$name_new=str_replace("(, ,",",",$name_new);
        return $name_new;
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
     * getColorAndCode
     *
     * @param  mixed $itemName
     * @return void
     */
    public function getColorAndCode($itemName)
    {
        $xml=$this->XML;
        $xml_new=$this->stripHead($xml);
        $items=$this->getItemsArr($xml_new);
        //echo "<b>$itemName</b><br>";
        foreach ($items as $item)
        {
            $name=$this->getItemName($item);
            //echo "$name<br>";
            $name = preg_replace('/\s+/', '', $name);
            $itemName = preg_replace('/\s+/', '', $itemName);
            //echo "res=".strcasecmp($name,$itemName)." - $name<br>";

            if (strcasecmp($name,$itemName)==0)
            {
                //echo "lkjhkj<br>";
                $code=$this->getItemId($item);
                //echo "$code<br>";
                $params=$this->getParams($item);
                foreach ($params as $param)
                {
                    $paramName=$this->getParamName($param);
                    if (strcmp($paramName,"Доступные цвета")==0||strcmp($paramName,"Цвет")==0)
                    {
                        $paramVal=$this->getParamVal($param);
                        //echo $paramVal."<br>";
                        $arr[0]=$code;
                        $arr[1]=$paramVal;
                        //var_dump($arr);
                        return $arr;
                    }
                }
            }
        }
    }

    /**
     * getCode
     *
     * @param  mixed $itemName
     * @return void
     */
    public function getCode($itemName)
    {
        $xml=$this->XML;
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
     * test
     *
     * @return void
     */
    public function test ()
    {
        $csv=$this->readCsv();
        copy ("new_hotline-v3.xml","new_hotline-v4.xml");
        $this->XML=file_get_contents('new_hotline-v4.xml');

        //не распознан
        //не размещается
        foreach ($csv as $item)
        {
            //echo "<pre>".print_r($item)."</pre>";
            if (array_search ("Товар не распознан",$item))
            {  
                $name_old=$item[2];
                $vendor=$item[1];
                $name=$this->stripName($name_old,$vendor);
                echo "нашли товар с проблемным именем - $name<br><br>";
                //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)\&]/ui","",$name);
                //echo "$name_new<br><br>";
                //break;
                //$this->changeName($name_old,$name);
                $this->XML=str_ireplace($name_old,$name,$this->XML);
                //break;
            }
        }
        $this->XML=str_ireplace("&quot;","\"",$this->XML);
        file_put_contents("new_hotline-v4.xml",$this->XML);
    }
}

$test = new MakeTree();
$test->catReplace();
echo "<b>Done tree</b><br>";

$test=new Hotline;
$test->parseXML();
echo "<b>Parse Done</b>";

$test = new CleanName();
$test->test();
echo "Names v2 Done<br>";
