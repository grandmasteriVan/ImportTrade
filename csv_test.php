<?php
header('Content-Type: text/html; charset=utf-8');
include_once "hotline-test.php";
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

    protected $hl;
    public function __construct(Hotline $HL)
    {
        $this->hl=$HL;
    }
    
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
        $name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)\&\-\.\%]/ui","",$name_new);
        $name_new=str_replace("()","",$name_new);
        $name_new=str_replace(" , ","",$name_new);
        $name_new=str_replace("(, )","",$name_new);
        $name_new=str_replace("( ),","",$name_new);
        
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
        if (strripos($name,"лубрикант")||strripos($name,"Лубрикант")||strripos($name,"Возбуждающая смазка")||strripos($name,"Гель Erotist для женщин")||strripos($name,"Крем-смазка")||strripos($name,"крем-смазка")||strripos($name,"Пробник смазки")||strripos($name,"Смазка пробник")||strripos($name,"Смазка на водной основе")||strripos($name,"Спрей для усиления слюноотделения")||strripos($name,"Пролонгатор")||strripos($name,"пролонгатор")||strripos($name,"гибридный гель")||strripos($name,"масло для женщин")||strripos($name,"Возбуждающий Гель")||strripos($name,"Возбуждающий гель")||strripos($name,"Гель для сужения")||strripos($name,"Гель для усиления")||strripos($name,"Клиторальный гель")||strripos($name,"Спрей для глубокого минета")) 
        {
            $name_new=$vendor." Лубрикант $propName".$name_new;
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
        if (strripos($name,"Возбуждающие капли")||strripos($name,"Возбуждающий бальзам")||strripos($name,"Капли для возбуждения"))
        {
            $name_new=$vendor." Возбуждающие капли $propName".$name_new;
        }
        if (strripos($name,"Клизма"))
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
        if (strripos($name,"Салфетки для интимной гигиены"))
        {
            $name_new=$vendor." Салфетки для интимной гигиены $propName".$name_new;
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

        //$test = preg_replace('~\[.*?\]~','[]',$test);
        $name_new=preg_replace('~\(.*?\)~','',$name_new);
        $color=$this->hl->getColor($name);
        $tmp=explode(";",$color);
        $color=$tmp[0];
        $code=$this->hl->getCode($name);
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
                $name_old=$item[2];
                $vendor=$item[1];
                $name=$this->stripName($name_old,$vendor);
                echo "нашли товар с проблемным именем - $name<br><br>";
                //$name_new=preg_replace("/[^,\p{Latin}\d\s\/\(\)\&]/ui","",$name);
                //echo "$name_new<br><br>";
                //break;

            }
            
        }
    }
}
set_time_limit(30000);
$test = new csvTest(new Hotline);
$test->test();
