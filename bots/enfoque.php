<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
use himidia\financeone\enfoquecrawler\enfoquecontents\Http;
use himidia\financeone\enfoquecrawler\EnfoqueCrawler;
use Sunra\PhpSimple\HtmlDomParser;

require_once('library/enfoque_mysql.php');
require_once('library/util.php');
require_once(__DIR__ . '/../vendor/autoload.php');

$db = new Enfoque_MySQL();
$enfoque_crawler = new EnfoqueCrawler(new HtmlDomParser(), new Http());

$news_links = $enfoque_crawler->getNewsLinks();

print_r('<pre>');
print_r($news_links);
print_r('</pre>');

foreach ($news_links as $news_link) {
    $id_pagina = md5($news_link->time . $news_link->title);
    print_r('<pre>');
    print_r($id_pagina);
    print_r('</pre>');
    if (!$db->verificaId($id_pagina)) {
        $news = $enfoque_crawler->getNews($news_link->href);
        print_r('<pre>');
        print_r($news);
        print_r('</pre>');

        $data = date('Y-m-d') . ' ' . $news_link->time;
        $titulo = $news_link->title;
        $url = $news_link->href;
        $conteudo = $news->content;
        $categoria = $news->category;

        $encoding = mb_detect_encoding($conteudo, array('UTF-8', 'ASCII', 'ISO-8859-1'));
        if ($encoding == 'UTF-8') {
            $conteudo = utf8_decode($conteudo);
        }

        $conteudo = preg_replace("/\n/", ' ', tidy_repair_string($conteudo));
        $conteudo = preg_replace('/<html><head><title></title></head><body>/', '', $conteudo);
        $conteudo = preg_replace('/</body></html>/', '', $conteudo);
        $conteudo = preg_replace('/\<\!\-\-.*\-\-\>/', '', $conteudo);

        $dados['id_pagina']     = $id_pagina;
        $dados['data']          = $data.':00';
        $dados['titulo']        = $titulo;
        $dados['url']           = $url;
        $dados['conteudo']      = $conteudo;
        $dados['categoria']     = $categoria;
        $dados['arrCategoria']  = array($categoria);
        print_r('<pre>');
        print_r($dados);
        print_r('</pre>');

        $db->post($dados);

    }
}

$db->atualizaTotalPosts();
