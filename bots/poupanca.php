<?php
ini_set('user_agent','Mozilla/5.0(X11; Ubuntu; Linux x86_64; rv:58.0)'); 
require_once('library/mysql.php');
require_once('library/util.php');
require_once('library/logger.php');
require_once('library/Zend/Dom/Query.php');

$s    = date('m') < 6 ? 1 : 2; // Semestre	
if(isset($_GET["semestre"]))
	$s = $_GET["semestre"];
	
$ano  = date('Y');
if(isset($_GET["ano"]))
	$ano = $_GET["ano"];
	
$db       = new Bot_MySQL();
$logger   = Bot_Logger::getInstance('poupanca');
$url      = "http://www.itauinvestnet.com.br/itauinvestnet/poupanca/simule/tab_ind.asp?semestre=$s&ano=$ano&versao=T";
$contents = Bot_Util::get_url($url, $logger);
$dom      = new Zend_Dom_Query($contents);

$contents = str_replace("<font size='1'>&nbsp;",'', $contents);			 
//echo $contents;
$table_lines  = $dom->query('td');
$dados        = array();

foreach ($table_lines as $line) {
  $dados[] = utf8_decode($line->nodeValue);
  //echo utf8_decode($line->nodeValue) . "<BR/>";
}

$dados = Bot_Util::strip_poupanca($dados, $s);

if(isset($_GET["debug"]))
{
    foreach ($dados as $d) {
		foreach ($d as $i) {
			echo $i . "<BR/>";
		}
	}
}
	
$db->poupanca($dados, $s, $ano);

$logger->close();
?>
