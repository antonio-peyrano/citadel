<?php
//require __DIR__.'/../vendor/autoload.php';
include_once($_SERVER['DOCUMENT_ROOT'].'/citadel/php/backend/bl/utilidades/vendor/autoload.php');
use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new HTML2PDF();//new Html2Pdf();
$html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
$html2pdf->output();

//echo __DIR__;
?>