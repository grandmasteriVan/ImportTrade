<?php
header('Content-Type: text/html; charset=utf-8');

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

    private function FindPromCat($item)
    {
        $newCat=null;
        $catIdOld=$this->getCatId($item);
        if ($catIdOld==18)
        {
            $newCat=3;
        }
        if ($catIdOld==7)
        {
            $newCat=4;
        }
        if ($catIdOld==30||$catIdOld==266||$catIdOld==1)
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
        if ($catIdOld==264||$catIdOld==190||$catIdOld==10||$catIdOld==187||$catIdOld==191||$catIdOld==256||$catIdOld==240||$catIdOld==250||$catIdOld==271||$catIdOld==272||$catIdOld==273||$catIdOld==124||$catIdOld==241||$catIdOld==106||$catIdOld==50||$catIdOld==204||$catIdOld==205||$catIdOld==43||$catIdOld==225||$catIdOld==206)
        {
            $newCat=10;
        }
        if ($catIdOld==270)
        {
            $newCat=11;
        }
        if ($catIdOld==85||$catIdOld==86||$catIdOld==5||$catIdOld==83||$catIdOld==84||$catIdOld==232||$catIdOld==233||$catIdOld==239)
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
        if ($catIdOld==174||$catIdOld==258||$catIdOld==116||$catIdOld==261||$catIdOld==214||$catIdOld==81||$catIdOld==115||$catIdOld==79||$catIdOld==259||$catIdOld==260||$catIdOld==262||$catIdOld==215||$catIdOld==249||$catIdOld==216)
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
        if ($catIdOld==89||$catIdOld==246)
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
        if ($catIdOld==180||$catIdOld==24||$catIdOld==25||$catIdOld==68||$catIdOld==228||$catIdOld==219||$catIdOld==72||$catIdOld==60||$catIdOld==175)
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
        if ($catIdOld==11||$catIdOld==59||$catIdOld==12||$catIdOld==251||$catIdOld==252||$catIdOld==253)
        {
            $newCat=35;
        }
        if ($catIdOld==15||$catIdOld==237||$catIdOld==236)
        {
            $newCat=39;
        }
        return $newCat;
    }

    private function setItemCat($item,$catalog)
    {
        $old_cat=$this->getCatId($item);
        $new_item=str_ireplace($old_cat,$catalog,$item);
        return $new_item;
    }

    private function makeHead()
    {
        $date=date("Y-m-d H:i");
        //print_r ($date);
        $head="<?xml version\"1.0\" encoding=\"UTF-8\"?>
        <price>
        <date>$date</date>
        <firmName>Мой магазин</firmName>
        <firmId>Идентификатор магазина</firmId>".PHP_EOL;
        return $head;
    }

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
        $xml_new=$head.self::prom_cats.PHP_EOL."<items>".$items_new.PHP_EOL."<items>".PHP_EOL."</price>";
        echo $xml_new;
        
    }

}

$test = new MakeTree();
$test->catReplace();