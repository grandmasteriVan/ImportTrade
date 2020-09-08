<?php
header('Content-Type: text/html; charset=utf-8');

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

    private function readXML()
    {
        $csv=file_get_contents('hotline_ua-v1.csv');
        return $csv;
    }

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

    private function getProperName($name)
    {
        if (preg_match("#\"(.+?)\"#",$name,$matches))
        {
            $propName=$matches[1];
        }
        return "\"".$propName."\" ";
    }

    private function stripName ($name, $vendor)
    {
        echo $name."<br>";
        $name_new=str_replace("50 оттенков серого","",$name);
        
        $name_new=str_replace("15 см диаметр 5","7.5",$name_new);
        $name_new=str_replace($vendor,"",$name_new);
        $name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)\&\-\.]/ui","",$name_new);
        $name_new=str_replace("()","",$name_new);
        $name_new=str_replace(" , ","",$name_new);
        
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
        if (strripos($name,"Массажер простаты"))
        {
            $name_new=$vendor." Массажер простаты $propName".$name_new;
        }
        if (strripos($name,"Вагинальные шарики")||strripos($name,"вагинальные шарики"))
        {
            $name_new=$vendor." Анальные шарики $propName".$name_new;
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
        
        if (strripos($name,"Набор Nuru")||strripos($name,"Набор для влюбленных")||strripos($name,"Набор для интригующего вечера")||strripos($name,"Набор для чувственных ласк")||strripos($name,"Набор удовольствий")||strripos($name,"Набор Юбилейный")||strripos($name,"Органический набор")) 
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
        
        //(13016 /)

        //$name_new=$vendor." ".$name_new;
        //можно сделатьб так. Во многих товарах у нас есть куча лишнего в описаннию. Но тут есть ньюанс - у нас есть позиции, где модель не указана в названии. Такие позиции как раз не распознаются
        //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)]/ui","",$name_new);
        //$name_new=str_replace("quot","",$name_new);
        //$name_new=str_replace("(, ","(",$name_new);
        //$name_new=str_replace("(, ,",",",$name_new);
        return $name_new;
    }

    public function test ()
    {
        $csv=$this->readCsv();
        //не распознан
        //не размещается
        foreach ($csv as $item)
        {
            
            //echo "<pre>".print_r($item)."</pre>";
            if (array_search ("Товар не распознан",$item))
            {  
                $name=$item[2];
                $vendor=$item[1];
                $name=$this->stripName($name,$vendor);
                echo "нашли товар с проблемным именем - $name<br><br>";
                //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)\&]/ui","",$name);
                //echo "$name_new<br><br>";
                //break;
            }
            
        }
    }
}

$test = new csvTest();
$test->test();
