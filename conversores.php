<?php
$data               = array();
$data['valor']      = $_POST['quanto'];
$data['conv_from']  = $_POST['paisf'];
$data['conv_to']    = $_POST['paist'];

$vars = http_build_query($data);
$url  = '/moedas/conversor-de-moedas';

header("Location: $url?$vars");
?>