<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/classes/FOCrawler.class.php';
require __DIR__ . '/models/PostsModel.php';
require __DIR__ . '/models/CrawlersModel.php';
require __DIR__ . '/database/config.php';

function debug($content){
  print_r('<pre>');
  print_r($content);
  print_r('</pre>');
}


function toMobile($url,$regex,$replacement){
    return preg_replace($regex, $replacement, $url);
}