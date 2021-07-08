<?php
header('Content-Type: text/html; charset=utf-8');

require_once 'PHPExcel.php';
require_once 'PHPExcel/Writer/Excel5.php';

class MakeMeta
{
    private function readExelFile($filepath)
    {
        //require_once 'PHPExcel.php'; //подключаем наш фреймворк
        $ar=array(); //инициализируем массив
        $inputFileType = PHPExcel_IOFactory::identify($filepath);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
        $objPHPExcel = $objReader->load($filepath); // загружаем данные файла в объект
        $ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив
        return $ar; //возвращаем массив
    }

    public function test()
    {
        file_put_contents('new_meta.csv', '');
        $handle1=fopen('new_meta.csv', 'w+');
        $products=$this->readExelFile('products-all.xlsx');
        $attributes=$this->readExelFile('attributes-all.xlsx');
        if (is_array($products)&&is_array($attributes))
        {
            foreach ($products as $product)
            {
                $product=array_slice($product,0,20);
                //echo "<pre>";print_r($product);echo "</pre>";
                $id=$product[0];
                $name_ru=$product[1];
                $name_ua=$product[2];
                $color_ru=null;
                $color_ua=null;
                $h1_ru=$product[18];
                $h1_ua=$product[19];
                $title_ru=$product[14];
                $title_ua=$product[15];
                $descr_ru=$product[16];
                $descr_ru=$product[17];
                foreach ($attributes as $atribute)
                {
                    //echo "<pre>";print_r($atribute);echo "</pre>";
                    if ($atribute[0]==$id&&$atribute[2]==29)
                    {
                        $color_ru=$atribute[3];
                        $color_ua=$atribute[4];
                        break;
                    }
                   
                }
                //echo "id=$id name=$name_ru color=$color_ru<br>";

                //проверяем есть ли уже цвет в названии, если есть - не трогаем
                $f=false;
                $tmp_name=" ".mb_strtolower($name_ru);
                $tmp_color=mb_strtolower($color_ru);
                //echo "$tmp_name - $tmp_color<br>";
                if (strripos($tmp_name,$tmp_color)==false)
                {
                    $f=true;
                }

                if (!is_null($color_ru)&&$f)
                {
                    $color_ru=str_ireplace(";","/",$color_ru);
                    $color_ru=mb_strtolower($color_ru);
                    $color_ua=str_ireplace(";","/",$color_ua);
                    $color_ua=mb_strtolower($color_ua);

                    $h1_ru_new=$name_ru." - $color_ru";
                    $h1_ua_new=$name_ua." - $color_ua";

                    $title_ru_new="$name_ru ($color_ru) – купить в Киеве, цена в Украине | Sex is Good";
                    $title_ua_new="$name_ua ($color_ua) – купить в Киеве, цена в Украине | Sex is Good";

                    $descr_ru="$name_ru, цвет - $color_ru ➨ Купить оптом и в розницу. Только у нас Лучшая ЦЕНА ✅Заказать $name_ru с быстрой доставкой по Киеву и всей Украине ✅Большой выбор товаров ✅Лидер в сегменте товаров для взрослых ✅Акции и скидки ✅Опт и розница.";
                    $descr_ua="$name_ua, колір - $color_ua ➨ Купити оптом і в роздріб. Тільки у нас Краща ЦІНА ✅Замовити $name_ua з швидкою доставкою по Києву і всій Україні ✅Великий вибір товарів ✅Лідер в сегменті товарів для дорослих ✅Акціі і знижки ✅Опт і роздріб.";
                    $new=array($id,$h1_ru_new,$h1_ua_new,$title_ru_new,$title_ua_new,$descr_ru,$descr_ua);
                    $new_meta[]=$new;
                    fputcsv($handle1, $new);
                }
                
                //break;
            }
            echo "<pre>";print_r($new_meta);echo "</pre>";
            fclose($handle1);
        }
    }
}

echo "<b>Start</b> ".date("Y-m-d H:i:s")."<br>";
set_time_limit (30000);
$test=new MakeMeta();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
