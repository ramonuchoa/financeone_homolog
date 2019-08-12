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
$url      = 'https://www.cma.com.br/informacaomercado/Indicadores.aspx?parceiro=CMA';
$contents = Bot_Util::get_url($url, $logger);
$dom      = new Zend_Dom_Query($contents);

$papeis       =  $dom->query('#lblPapel');
$descricoes   =  $dom->query('#lblDescricao');
$fechamentos  =  $dom->query('#lblFechamento');
$horas        =  $dom->query('#lblHora');
$datas        =  $dom->query('#lblData');



$indicadoresArr  = array();
$indicador = array();

foreach ($papeis as $papel) {
  $indicadoresArr[] = utf8_decode($papel->nodeValue);
}


foreach ($descricoes as $key => $descricao) {
    $indicador[$indicadoresArr[$key]]['descricao'] = $descricao->nodeValue;
}

foreach ($fechamentos as $key => $fechamento) {
    $indicador[$indicadoresArr[$key]]['fechamento'] = $fechamento->nodeValue;
}

foreach ($horas as $key => $hora) {
    $indicador[$indicadoresArr[$key]]['hora'] = $hora->nodeValue;
}

foreach ($datas as $key => $data) {
    $indicador[$indicadoresArr[$key]]['data'] = formatData($data->nodeValue,$indicador[$indicadoresArr[$key]]['hora']);
}


function limparChar($valor){
  $valor = preg_replace('/R\$/',"", utf8_decode($valor));
  $valor = preg_replace('/,/',".", $valor);
  $valor = preg_replace('/%/',"", $valor);
  $valor = preg_replace('/\s+/',"", $valor);
  return $valor;
}

function formatData($data,$hora){
  $ano = date("Y");
  $data = explode("/",$data);
  return $ano."-".$data[1]."-".$data[0]." ".$hora.":00";
}


print_r('<pre>');
print_r($indicador);
print_r('</pre>');


$db->salvarIndicadores($indicador);

// echo $db->dolar_novo_jef_site_valor($cotacao);
//
// $logger->log('Dados capturados: ' . serialize($cotacao));
//
//
//
// $logger->close();



?>
