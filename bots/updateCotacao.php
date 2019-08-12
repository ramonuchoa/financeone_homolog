<?php 

require_once "libra.php";
require_once "euro.php";
require_once "dolar.php";


$fl = new FinanceOneLibra();
$fd = new FinanceOneEuro();
$fe = new FinanceOneDolar();

$intervalo = null;



$fd->insertCotacao($intervalo);
$fl->insertCotacao($intervalo);
$fe->insertCotacao($intervalo);
