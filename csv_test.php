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
        if(stripos($name,"40167 / 763010 /")
        {
            $name_new="We aim to please ".$name_new;
        }
        if(stripos($name,"54811 T/")
        {
            $name_new="Greedy girl ".$name_new;
        }
        if(stripos($name,"40168 / 576310 /")
        {
            $name_new="Insatiable Desire ".$name_new;
        }
        if(stripos($name,"48293 T/811940 /")
        {
            $name_new="Charlie tango ".$name_new;
        }
        if(stripos($name,"74956")
        {
            $name_new="Greedy girl ".$name_new;
        }
        if(stripos($name,"74947")
        {
            $name_new="Delicious tingles ".$name_new;
        }
        if(stripos($name,"74970")
        {
            $name_new="Wicked weekend tingles ".$name_new;
        }
        if(stripos($name,"74791 /74971")
        {
            $name_new="Greedy girl play box ".$name_new;
        }
        if(stripos($name,"59953")
        {
            $name_new="A perfrct O ".$name_new;
        }
        if(stripos($name,"40170 / 576336 Ю")
        {
            $name_new="Yours and mine ".$name_new;
        }

        if (strripos($name,"Вибрат")||strripos($name,"вибрат"))
        {
            $name_new=$vendor." Вибратор $propName".$name_new;
        }
        if (strripos($name,"Виброяйцо"))
        {
            $name_new=$vendor." Виброяйцо $propName".$name_new;
        }
        if (strripos($name,"страпон"))
        {
            $name_new=$vendor." Cтрапон $propName".$name_new;
        }
        
        if (strripos($name,"Вакуумный стимулятор"))
        {
            $name_new=$vendor." Стимулятор клитора $propName".$name_new;
        }
        if (strripos($name,"Фаллоимитатор"))
        {
            $name_new=$vendor." Фаллоимитатор $propName".$name_new;
        }
        if (strripos($name,"Бодистокинг"))
        {
            $name_new=$vendor." Комбинезон $propName".$name_new;
        } 
        if (strripos($name,"Комбинезон"))
        {
            $name_new=$vendor." Комбинезон $propName".$name_new;
        }
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
