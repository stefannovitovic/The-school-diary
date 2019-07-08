<?php
ob_start();
define("DB_HOST", "localhost");
define("DB_NAME", "e_dnevnik_");
define("DB_USER", "root");
define("DB_PASS", "");
require_once __DIR__ . '/vendor/autoload.php';

include ("../../private/classes/Mapper.php");
include ("../../private/classes/Database.php");
session_start();
$results = Mapper::getFinalGrades();
$result2 = Mapper::getAverage();
$average = array_shift($result2);
$name = array_shift($results);
$date = date("Y");
$date2 = date("d-m-Y");
    $mpdf = new \Mpdf\Mpdf();
    
    $data = '';
    $data.= "<p style='text-align:center;'>Osnovna škola 'Velikomučenik Stefan Novitović', u Beogradu, dodeljuje";
    $data.= "<h1 style='text-align:center;'>Svedočanstvo</h1>";
  
    $data.= "<h1 style='text-align:center; text-decoration:underline;'>" . $name['firstName'] . " " . $name['lastName'] . "</h1>";
    
    $data.= "<p style='text-align:center;text-decoration:underline;'>Opština: Beograd, država: Srbija,</p>";
    $data.= "<p style='text-align:center;'>položio-la je ispite školske " . $date . " i pokazao-la sledeći uspeh:</p>";
    
    foreach($results as $result){
        $data.= "<p style='text-align:left; font-size:16px;font-style:italic; '>" . $result['name'] . " "  . $result['value'] . "</p>";
    }

    $data.= "<p style='text-align:left; font-size:16px;'> Učenik-ca je sa ";
    switch ($average['average']){
        case ($average['average'] >= 1.5000 && $average['average'] < 2.5000):
            $data.= "dovoljnim uspehom (" ;
            break;
        case ($average['average'] >= 2.5000 && $average['average'] < 3.5000):
            $data.= "dobrim uspehom (";
            break;  
        case ($average['average'] >= 3.5000  && $average['average'] < 4.5000):
            $data.= "vrlo dobrim uspehom (";
            break;    
        case ($average['average'] >= 4.5000  && $average['average'] < 5.0000):
            $data.= "odlicnim uspehom (";
            break; 
    }
    $data.= $average['average'].") završio-la razred osnovne škole. <p>";
    $data.=" ";
    $data.="<div style='float:left; width:20%'>";
    $data.="<p style='font-style:italic;  text-decoration:underline;text-align:left;'>Vladimir Petrovic</p>";
    $data.="<p style='text-align:left;'>direktor škole</p>";
    $data.="</div>";
    $data.="<div style='float:right; width:20%'>";
    $data.="<p style='text-align:right;'>U Beogradu, " . $date2 . "</p>";
    $data.="</div>";
    


    $mpdf->writeHTML($data);
    $mpdf->output();

 ?>