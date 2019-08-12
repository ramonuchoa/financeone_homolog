<?php
ini_set('user_agent','Mozilla/5.0(X11; Ubuntu; Linux x86_64; rv:58.0)'); 
ini_set('error_reporting', E_ALL);
date_default_timezone_set('America/Sao_Paulo');

require_once('library/mysql.php');
require_once('library/util.php');
require_once('library/logger.php');
require_once('library/Zend/Dom/Query.php');

$db       = new Bot_MySQL();
$logger   = Bot_Logger::getInstance('dolar');
$url      = 'https://api.bitvalor.com/v1/ticker.json';
$contents = Bot_Util::get_url($url, $logger);
// $dom      = new Zend_Dom_Query($contents);

if ($contents) {
	$data = json_decode($contents);

	$exchanges = $data->ticker_24h->exchanges;
	$rates = $data->rates;

	$db->handleBitcoin($exchanges,$rates);
} else {
	die("No return from address");
}
