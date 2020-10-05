<?php
header('Content-Type: text/html; charset=utf-8');

class Gufo
{
    private function readFile()
    {
        $xml=file_get_contents('index.xml');
        return $xml;
    }

    private function delDescription ($item)
    {
        $item=str_ireplace("&","",$item);
        $item=preg_replace("#<description>(.*?)<\/description>#s","<description></description>",$item);
        //удяляем лишние пробелы
        //$xml = preg_replace('/\s+/', ' ', $xml);
        $item=str_ireplace(" unit=\"\"","",$item);
        return $item;
    }

    private function setDescription($item)
    {
        $name=$this->getItemName($item);
        $item=str_ireplace("<description></description>","<description>$name</description>",$item);
    }

    private function getItemName($item)
    {
        preg_match("#<name>(.*?)<\/name>#",$item,$matches);
        $name=$matches[1];
        return $name;
    }

    public function test()
    {
        $xml=$this->readFile();
        $XMLnew=$this->delDescription($xml);
        file_put_contents("gufo_new.xml",$XMLnew);
    }

}

$test=new Gufo();
$test->test();
echo "<b>Done</b> ".date("Y-m-d H:i:s");
