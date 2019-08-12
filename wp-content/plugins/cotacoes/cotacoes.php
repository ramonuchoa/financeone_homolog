<?php
/*
Plugin Name: Cotações
Version: 0.1
Plugin URI:
Description: Cotações do euro, dolar e da bolsa de valores
Author: Luã de Souza
Author URI: http://www.lsouza.pro.br
*/

// Widget
require(dirname(__FILE__).'/widget.php');

// Templates
require(dirname(__FILE__).'/../template.class.php');

// Init hook
add_filter('init', array('Cotacoes','init'));

if (!class_exists('Cotacoes')) :

class Cotacoes {
        private static $wpdb;

        public static function init() {
                global $wpdb;
                self::$wpdb = $wpdb;
                add_filter('the_content', array('Cotacoes', 'pages'));
        }

        public static function moedas($data = null) {
                if (!$data) {
                        return self::$wpdb->get_results("SELECT DISTINCT n.nome, n.pais, d.* FROM moedas_dado d INNER JOIN moedas_nome n ON n.code = d.code WHERE d.data = (SELECT MAX(data) from moedas_dado) AND n.pais NOT LIKE 'EURO' ORDER BY pais ASC");
                } else {
                        return self::$wpdb->get_results("SELECT DISTINCT n.nome, n.pais, d.* FROM moedas_dado d INNER JOIN moedas_nome n ON n.code = d.code WHERE d.data = (SELECT data FROM moedas_dado WHERE data >= '{$data}' LIMIT 1) AND n.pais NOT LIKE 'EURO' ORDER BY pais ASC");
                }
        }

        public static function cotacaoDoDia() {
                $html = '';
                $data_dolar = '';
                $dolar ="<table class=\"table\">";

                foreach (self::dolar() as $cota) {
                        $dolar .= '<tr>';
                        $dolar .= '<td class="tipo">'.$cota->tipo.'</td>';
                        $dolar .= '<td class="'.(substr($cota->var, 0, 1) == '-' ? 'negativo' : 'positivo').'">'.$cota->var.'  %</td>';
                        $dolar .= '<td>R$ '. number_format($cota->compra, 2, ',', '.') .'</td>';
                        $dolar .= '<td>R$ '. number_format($cota->venda, 2, ',', '.') .'</td>';
                        $dolar .= '</tr>';
                }

                $dolar .= "</table>";
                return $dolar;
        }

        public static function dolar($data = null, $data_fim = null, $limit = 365) {
                if (!$data) {
                        // $tipos  = array('Comercial', 'Turismo', 'Ptax');
                        $tipos  = array('Comercial');
                        $cota   = array();

                        foreach ($tipos as $tipo) {
                                $cota[strtolower($tipo)] = self::$wpdb->get_row("SELECT * FROM dados_dolar WHERE tipo = '{$tipo}' OR tipo LIKE 'Dólar ${tipo}'  ORDER BY data DESC LIMIT 0,1");
                        }
                } else {
                        if (strlen($data) < 6) //If a string has less then 6 characters probbly it is not a date
                                $cota = self::$wpdb->get_row("SELECT * FROM dados_dolar ORDER BY data DESC LIMIT 0,1");
                        elseif (!$data_fim) {
                                $cota = self::$wpdb->get_row("SELECT * FROM dados_dolar WHERE `data` >= '{$data}' ORDER BY data ASC LIMIT 0,1");
                        } else {
                                $cota = self::$wpdb->get_results("SELECT * FROM dados_dolar WHERE `data` >= '{$data}' AND `data` <= '{$data_fim}' ORDER BY data DESC LIMIT $limit");
                        }
                }

                return $cota;
        }

        public static function dolarHoje() {
                //return self::$wpdb->get_row("SELECT * FROM moedas_dado WHERE code = 'USD' ORDER BY data DESC LIMIT 0,1");
                return self::$wpdb->get_row("SELECT venda, tipo FROM dados_dolar WHERE tipo = 'Comercial' ORDER BY data DESC LIMIT 0,1");
        }

public static function pageDolar() {
                $html = '';
                $dolar = '';

                // template
                $cotacoes = new Template(dirname(__FILE__) . '/cotacao-dolar.tpl.php');
                $action = get_bloginfo('url').'/moedas/cotacoes-do-dolar';

                // Gráficos
                $cotacoes->addVar('src', get_bloginfo('template_url'));
                $cotacoes->addVar('grafico7', self::grafico('USD', 7));
                $cotacoes->addVar('grafico30', self::grafico('USD', 30));
                $cotacoes->addVar('grafico100', self::grafico('USD', 100));
                $cotacoes->addVar('grafico365', self::grafico('USD', 365));

                // Tabela de cotação mais recente
                $data_dolar = '';

                foreach (self::dolar() as $cota) {
                        $dolar .= '<tr>';
                        $dolar .= '<td class="tipo">'.$cota->tipo.'</td>';
                        $dolar .= '<td class="'.(substr($cota->var, 0, 1) == '-' ? 'negativo' : 'positivo').'">'.substr($cota->var, 0, 5).' %</td>';
                        $dolar .= '<td>R$ '.number_format($cota->compra, 2, ',', '.').'</td>';
                        $dolar .= '<td>R$ '.number_format($cota->venda, 2, ',', '.').'</td>';
                        $dolar .= '</tr>';

                        if ($data_dolar == '') $data_dolar = $cota->data;
                }

                $data_dolar = new DateTime($data_dolar);
                $cotacoes->addVar('dolar', $dolar);
                $cotacoes->addVar('data_dolar', $data_dolar->format('d/m/Y \à\s H:i'));

                // Resultados
                if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'data') {
                        $data = self::$wpdb->escape($_POST['ano']) .'-'. self::$wpdb->escape($_POST['mes']) .'-'. self::$wpdb->escape($_POST['dia']);
                        $cota = self::dolar($data);
                        $data = new DateTime($cota->data);
                        $cotaCompra = str_replace('.', ',', number_format($cota->compra, 2));
                        $cotaVenda = str_replace('.', ',', number_format($cota->venda, 2));
                        $box = new Template(dirname(__FILE__) . '/cotacao-dolar-data.tpl.php');
                        $box->addVar('data', $data->format('d/m/Y'));
                        $box->addVar('cota-venda', $cotaVenda);
                        $box->addVar('cota-compra', $cotaCompra);
                        $box->addVar('action', $action);
                        $cotacoes->addVar('box', $box->render());
                } else if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'periodo') {
                        $data = self::$wpdb->escape($_POST['ano_de']) .'-'. self::$wpdb->escape($_POST['mes_de']) .'-'. self::$wpdb->escape($_POST['dia_de']);
                        $data_fim = self::$wpdb->escape($_POST['ano_ate']) .'-'. self::$wpdb->escape($_POST['mes_ate']) .'-'. self::$wpdb->escape($_POST['dia_ate']);
                        $cotas = self::dolar($data, $data_fim, is_user_logged_in() ? 365 : 90);
                        $box = new Template(dirname(__FILE__) . '/cotacao-dolar-periodo.tpl.php');
                        $box->addVar('action', $action);
                        $box->addVar('src', get_bloginfo('template_url'));
                        $lista = '';

                        foreach ($cotas as $cota) {
                                $data = new DateTime($cota->data);
                                $lista .= '<tr>';
                                $lista .= '<td>'.$data->format('d/m/Y').'</td>';
                                $cotaCompra = str_replace(',', '.', $cota->compra);
                                $lista .= '<td>R$ '.number_format($cotaCompra, 2, ',', '.').'</td>';
                                $cotaVenda = str_replace(',', '.', $cota->venda);
                                $lista .= '<td>R$ '.number_format($cotaVenda, 2, ',', '.').'</td>';
                                $lista .= '</tr>';
                        }

                        $box->addVar('lista', $lista);
                        $cotacoes->addVar('box', $box->render());
                } else {
                        $box = new Template(dirname(__FILE__) . '/cotacao-dolar.form.tpl.php');
                        $box->addVar('action', $action);
                        $ultima_data = explode('-', self::ultimaData('dolar'));
                        $box->addVar('cotas', array('dia' => $ultima_data[2], 'mes' => $ultima_data[1], 'ano' => $ultima_data[0]));
                        $ultima_data_periodo = strtotime('-10 days');
                        $ultima_data_periodo = explode('-', date('Y-m-d', $ultima_data_periodo));
                        $box->addVar('cotas_de', array('dia_de' => $ultima_data_periodo[2], 'mes_de' => $ultima_data_periodo[1], 'ano_de' => $ultima_data_periodo[0]));
                        $cotacoes->addVar('box', $box->render());
                }

                $html .= $cotacoes->render();
                return $html;
        }

        public static function historicoDolar() {
                $html = '';

                // template
                $cotacoes = new Template(dirname(__FILE__) . '/cotacao-dolar.tpl.php');
                $action = get_bloginfo('url').'/moedas/cotacoes-do-dolar';
                $data = date('Y-m-d', strtotime('-30 days'));
                $data_fim = date('Y-m-d');
                $cotas = self::$wpdb->get_results("SELECT * FROM dados_dolar WHERE `data` >= '{$data}' AND `data` < '{$data_fim}' GROUP BY data ORDER BY data DESC");
                $box = new Template(dirname(__FILE__) . '/cotacao-dolar-periodo.tpl.php');
                $lista = '';

                foreach ($cotas as $cota) {
                        $data = new DateTime($cota->data);
                        $lista .= '<tr>';
                        $lista .= '<td>'.$data->format('d/m/Y').'</td>';
                        $lista .= '<td>R$ '. number_format($cota->compra, 2, ',', '.') .'</td>';
                        $lista .= '<td>R$ '. number_format($cota->venda, 2, ',', '.') .'</td>';
                        $lista .= '</tr>';
                }

                $html .= $lista;
                return $html;
        }

        public static function euro($data = null, $data_fim = null, $limit = 365) {
                if (!$data) {
                        return self::$wpdb->get_row("SELECT * FROM dados_euro ORDER BY data DESC LIMIT 0,1");
                } else {
                        if (!$data_fim) {
                                return self::$wpdb->get_row("SELECT * FROM dados_euro WHERE `data` >= '{$data}' ORDER BY data ASC LIMIT 1");
                        } else {
                                return self::$wpdb->get_results("SELECT * FROM dados_euro WHERE `data` >= '{$data}' AND `data` <= '{$data_fim}' ORDER BY data ASC LIMIT $limit");
                        }
		}
        }

        public static function euroHoje() {
                return self::$wpdb->get_row("SELECT venda, tipo FROM dados_euro WHERE tipo = 'Comercial' ORDER BY data DESC LIMIT 0,1");
        }

        public static function pageEuro() {
                $html = '';

                // template
                $cotacoes = new Template(dirname(__FILE__) . '/cotacao-euro.tpl.php');
                $action = get_bloginfo('url').'/moedas/cotacoes-do-euro';
                $cotacoes->addVar('src', get_bloginfo('template_url'));

                // Gráficos
                $cotacoes->addVar('grafico7', self::grafico('EUR', 7));
                $cotacoes->addVar('grafico30', self::grafico('EUR', 30));
                $cotacoes->addVar('grafico100', self::grafico('EUR', 100));
                $cotacoes->addVar('grafico365', self::grafico('EUR', 365));

                // Resultados
                if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'data') {
                        $data = self::$wpdb->escape($_POST['ano']) .'-'. self::$wpdb->escape($_POST['mes']) .'-'. self::$wpdb->escape($_POST['dia']);

                        if (strlen($data) < 6) //If a string has less then 6 characters probbly it is not a date
                        $data = '';
                        $cota = self::euro($data);
                        $data = new DateTime($cota->data);
                        $box = new Template(dirname(__FILE__) . '/cotacao-euro-data.tpl.php');
                        $box->addVar('data', $data->format('d/m/Y'));
                        $box->addVar('cota-compra', number_format($cota->compra, 2));
                        $box->addVar('cota-venda', number_format($cota->compra, 2));
                        $box->addVar('action', $action);
                        $box->addVar('dia', $_POST['dia']);
                        $box->addVar('mes', $_POST['mes']);
                        $box->addVar('ano', $_POST['ano']);
                        $cotacoes->addVar('box', $box->render());
                } else if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'periodo') {
                        $box = new Template(dirname(__FILE__) . '/cotacao-euro-periodo.tpl.php');
                        $box->addVar('action', $action);
                        $box->addVar('src', get_bloginfo('template_url'));
                        $box->addVar('dia_de', $_POST['dia_de']);
                        $box->addVar('mes_de', $_POST['mes_de']);
                        $box->addVar('ano_de', $_POST['ano_de']);
                        $box->addVar('dia_ate', $_POST['dia_ate']);
                        $box->addVar('mes_ate', $_POST['mes_ate']);
                        $box->addVar('ano_ate', $_POST['ano_ate']);

                        $data = self::$wpdb->escape($_POST['ano_de']) .'-'. self::$wpdb->escape($_POST['mes_de']) .'-'. self::$wpdb->escape($_POST['dia_de']);
                        $data_fim = self::$wpdb->escape($_POST['ano_ate']) .'-'. self::$wpdb->escape($_POST['mes_ate']) .'-'. self::$wpdb->escape($_POST['dia_ate']);

                        $cotas = self::euro($data, $data_fim, is_user_logged_in() ? 365 : 90);

                        $lista = '';

                        foreach ($cotas as $cota) {
                                $data = new DateTime($cota->data);
                                $lista .= '<tr>';
                                $lista .= '<td>'.$data->format('d/m/Y').'</td>';
                                $lista .= '<td>R$ '. number_format($cota->compra, 2, ',', '.') .'</td>';
                                $lista .= '<td>R$ '. number_format($cota->venda, 2, ',', '.') .'</td>';
                                $lista .= '</tr>';
                        }

$box->addVar('lista', $lista);
                        $cotacoes->addVar('box', $box->render());
                } else {
                        $box = new Template(dirname(__FILE__) . '/cotacao-euro.form.tpl.php');
                        $box->addVar('action', $action);
                        $ultima_data = explode('-', self::ultimaData('euro'));
                        $box->addVar('cotas', array('dia' => $ultima_data[2], 'mes' => $ultima_data[1], 'ano' => $ultima_data[0]));
                        $ultima_data_periodo = strtotime('-1 month');
                        $ultima_data_periodo = explode('-', date('Y-m-d', $ultima_data_periodo));
                        $box->addVar('cotas_de', array('dia_de' => $ultima_data_periodo[2], 'mes_de' => $ultima_data_periodo[1], 'ano_de' => $ultima_data_periodo[0]));

                        // Tabela de cotação mais recente
                        $euro_hoje  = self::euro();
                        $data = new DateTime($euro_hoje->data);
                        $box->addVar('euro_compra', number_format($euro_hoje->compra, 2, ',', '.'));
                        $box->addVar('euro_venda', number_format($euro_hoje->compra, 2, ',', '.'));
                        $box->addVar('data_euro', $data->format('d/m/Y'));
                        $cotacoes->addVar('box', $box->render());
                }

                $html .= $cotacoes->render();
                return $html;
        }

        public static function historicoEuro() {
                $html = '';

                // template
                $cotacoes = new Template(dirname(__FILE__) . '/cotacao-dolar.tpl.php');
                $action = get_bloginfo('url').'/moedas/cotacoes-do-dolar';
                $data = date('Y-m-d', strtotime('-30 days'));
                $data_fim = date('Y-m-d',strtotime('+1 days'));
                $cotas =  self::$wpdb->get_results("SELECT * FROM dados_euro WHERE `data` >= '{$data}' AND `data` < '{$data_fim}' ORDER BY data DESC");
                $lista = '';

                foreach ($cotas as $cota) {
                        $data = new DateTime($cota->data);
                        $lista .= '<tr>';
                        $lista .= '<td>'.$data->format('d/m/Y').'</td>';
                        $lista .= '<td>R$ '. number_format($cota->compra, 2, ',', '.') .'</td>';
                        $lista .= '<td>R$ '. number_format($cota->valor, 2, ',', '.') .'</td>';
                        $lista .= '</tr>';
                }

                $html .= $lista;
                return $html;
        }

        public static function poupanca($data = null, $data_fim = null, $limit = 365) {
                if (!$data) {
                        return self::$wpdb->get_results("SELECT data, rend FROM poupanca WHERE data >= NOW() ORDER BY data ASC LIMIT 5");
                } else {
                        if (!$data_fim) {
                                return self::$wpdb->get_row("SELECT data, rend FROM poupanca WHERE `data` >= '{$data}' ORDER BY data ASC LIMIT 1");
                        } else {
                                return self::$wpdb->get_results("SELECT data, rend FROM poupanca WHERE `data` >= '{$data}' AND `data` <= '{$data_fim}' ORDER BY data DESC LIMIT $limit");
                        }
                }
        }

        public static function poupancaHoje() {
                $poupanca = self::$wpdb->get_row("SELECT rend as rendimento FROM poupanca WHERE data >= NOW() ORDER BY data DESC LIMIT 1");
                $poupanca = $poupanca->rendimento;
                return $poupanca;
        }

        public static function pagePoupanca() {
                $html = '';
                $poupanca = '';

                // template
                $cotacoes = new Template(dirname(__FILE__) . '/cotacao-poupanca.tpl.php');
                $action = get_bloginfo('url').'/moedas/rendimento-e-historico-da-poupanca';

                // Gráficos
                $cotacoes->addVar('src', get_bloginfo('template_url'));
                $cotacoes->addVar('grafico7', self::grafico('poupanca', 7));
                $cotacoes->addVar('grafico30', self::grafico('poupanca', 30));
                $cotacoes->addVar('grafico100', self::grafico('poupanca', 100));
                $cotacoes->addVar('grafico365', self::grafico('poupanca', 365));

                // Tabela de cotação mais recente
                foreach (self::poupanca() as $cota) {
                        $data = new DateTime($cota->data);
                        $poupanca .= '<tr>';
                        $poupanca .= '<td class="tipo">'.$data->format('d/m/Y').'</td>';
                        $poupanca .= '<td>'.$cota->rend.'</td>';
                        $poupanca .= '</tr>';
                }

                $cotacoes->addVar('poupanca', $poupanca);

                // Resultados
                if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'data') {
                        $data = self::$wpdb->escape($_POST['ano']) .'-'. self::$wpdb->escape($_POST['mes']) .'-'. self::$wpdb->escape($_POST['dia']);
                        $cota = self::poupanca($data);
                        $data = new DateTime($cota->data);

                        if (count($cota) < 1)
                                $box = new Template(dirname(__FILE__) . '/cotacao-empty.tpl.php');
                        else
                                $box = new Template(dirname(__FILE__) . '/cotacao-poupanca-data.tpl.php');
                                $box->addVar('data', $data->format('d/m/Y'));
                                $box->addVar('rend', $cota->rend);
                                $box->addVar('action', $action);

                        $cotacoes->addVar('box', $box->render());
                } else if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'periodo') {
                        // Periodo
                        $data = self::$wpdb->escape($_POST['ano_de']) .'-'. self::$wpdb->escape($_POST['mes_de']) .'-'. self::$wpdb->escape($_POST['dia_de']);
                        $data_fim = self::$wpdb->escape($_POST['ano_ate']) .'-'. self::$wpdb->escape($_POST['mes_ate']) .'-'. self::$wpdb->escape($_POST['dia_ate']);
                        $cotas = self::poupanca($data, $data_fim, is_user_logged_in() ? 365 : 90);

                        if (count($cotas) < 1)
                                $box = new Template(dirname(__FILE__) . '/cotacao-empty.tpl.php');
                        else
                                $box = new Template(dirname(__FILE__) . '/cotacao-poupanca-periodo.tpl.php');
                                $box->addVar('action', $action);
                                $box->addVar('src', get_bloginfo('template_url'));

                        $lista = '';

                        foreach ($cotas as $cota) {
                                $data = new DateTime($cota->data);
                                $lista .= '<tr>';
                                $lista .= '<td>'.$data->format('d/m/Y').'</td>';
                                $lista .= '<td>'.$cota->rend.'</td>';
                                $lista .= '</tr>';
                        }

                        $box->addVar('lista', $lista);
                        $cotacoes->addVar('box', $box->render());
                } else {
                        $box = new Template(dirname(__FILE__) . '/cotacao-poupanca.form.tpl.php');
                        $box->addVar('action', $action);
                        $cotacoes->addVar('box', $box->render());
                }

                $html .= $cotacoes->render();
                return $html;
        }

        public static function pageReal() {
                $html = '';
                $data = '';

                // template
                $cotacoes = new Template(dirname(__FILE__) . '/cotacao-moedas.tpl.php');
                $cotacoes->addVar('action', get_bloginfo('url').'/moedas/cotacoes-do-real-e-outras-moedas');
                $cotacoes->addVar('src', get_bloginfo('template_url'));

                // Gráficos
                $moeda = (isset($_POST['moeda'])) ? $_POST['moeda'] : 'USD';
                $cotacoes->addVar('graficos', self::grafico($moeda));
                $cotacoes->addVar('moeda', $moeda);

                // Resultados
                if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'data') {
                        #$data = self::$wpdb->escape($_POST['ano']) .'-'. self::$wpdb->escape($_POST['mes']) .'-'. self::$wpdb->escape($_POST['dia']);
                        $data = str_replace('/', '-', self::$wpdb->escape($_POST['date']));
                        $data = date('Y-m-d', strtotime($data));
                }

                $lista = '';
                $cotas = self::moedas($data);

                foreach ($cotas as $cota) {
                        $lista .= '<tr>';
                        $lista .= '<td class="tipo">'.ucwords(strtolower($cota->pais)).'</td>';
                        $lista .= '<td>'.ucwords(strtolower($cota->nome)).'</td>';
                        $lista .= '<td>R$ '. number_format($cota->compra, 4, ',', '.') .'</td>';
                        $lista .= '<td>R$ '. number_format($cota->valor, 4, ',', '.') .'</td>';
                        $lista .= '</tr>';

                        if ($data == '') $data = $cota->data;
                }

                $data = new DateTime($data);
                $cotacoes->addVar('lista', $lista);
                $cotacoes->addVar('data', $data->format('d/m/Y'));
                $html .= $cotacoes->render();
                return $html;
        }

        public static function bitcoin($data = null, $data_fim = null, $exchange = null, $limit = 365) {
                $where = "";

                if ($exchange !== null) {
                        $where .= " AND exchange = '$exchange'";
                }

                if ($data !== null && $data_fim !== null) {
                        $where .= " AND data BETWEEN '$data' AND $data_fim";
                } else if ($data !== null) {
                        $where .= " AND data = '$data'";
                } else {
                        $where .= " AND data = (SELECT MAX(data) FROM cotacao_bitcoin GROUP BY data ORDER BY data DESC LIMIT 1)";
                }

                $query = "SELECT cotacao, usd_comercial, usd_turismo, data, exchange FROM cotacao_bitcoin WHERE 1 $where ORDER BY data DESC LIMIT $limit";

                return self::$wpdb->get_results($query);
        }

        public static function bitcoinHoje() {
                return self::$wpdb->get_row("SELECT cotacao FROM cotacao_bitcoin WHERE exchange = 'FOX' ORDER BY data DESC LIMIT 1");
        }

        public static function pageBitcoin() {
                $html = '';
                $bitcoin = '';
                $content = '';

                // template
                $cotacoes = new Template(dirname(__FILE__) . '/cotacao-bitcoin.tpl.php');
                $action = get_bloginfo('url').'/moedas/cotacoes-do-bitcoin';

                // Gráficos
                $cotacoes->addVar('src', get_bloginfo('template_url'));

                $data_cotacao = '';

                foreach (self::bitcoin() as $cota) {
                        $content .= '<tr>';
                        $content .= '<td>'.$cota->exchange.'</td>';
                        $content .= '<td>R$'.number_format($cota->cotacao, 2, ',', '.').'</td>';
                        $content .= '<td>US$ '.number_format($cota->usd_comercial, 4, ',', '.').'</td>';
                        $content .= '<td>US$ '.number_format($cota->usd_turismo, 4, ',', '.').'</td>';
                        $content .= '</tr>';

                        if ($data_cotacao == '') $data_cotacao = $cota->data;
                }

                $data_cotacao = new DateTime($data_cotacao);

                $cotacoes->addVar('ultima_cotacao', $content);
                $cotacoes->addVar('data_cotacao', $data_cotacao->format('d/m/Y'));

                // Resultados
/*                if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'data') {
                        $data = self::$wpdb->escape($_POST['ano']) .'-'. self::$wpdb->escape($_POST['mes']) .'-'. self::$wpdb->escape($_POST['dia']);
                        $cota = self::dolar($data);

                        $data = new DateTime($cota->data);
                        $cotaCompra = str_replace('.', ',', $cota->compra);
                        $cotaVenda = str_replace('.', ',', $cota->venda);

                        $box = new Template(dirname(__FILE__) . '/cotacao-dolar-data.tpl.php');
                        $box->addVar('data', $data->format('d/m/Y'));
                        $box->addVar('cota-venda', $cotaVenda);
                        $box->addVar('cota-compra', $cotaCompra);
                        $box->addVar('action', $action);

                        $cotacoes->addVar('box', $box->render());

                } else if (isset($_POST['cotacao']) && $_POST['cotacao'] == 'periodo') {
                        $data = self::$wpdb->escape($_POST['ano_de']) .'-'. self::$wpdb->escape($_POST['mes_de']) .'-'. self::$wpdb->escape($_POST['dia_de']);
                        $data_fim = self::$wpdb->escape($_POST['ano_ate']) .'-'. self::$wpdb->escape($_POST['mes_ate']) .'-'. self::$wpdb->escape($_POST['dia_ate']);
                        $cotas = self::dolar($data, $data_fim, is_user_logged_in() ? 365 : 90);

                        $box = new Template(dirname(__FILE__) . '/cotacao-dolar-periodo.tpl.php');
                        $box->addVar('action', $action);
                        $box->addVar('src', get_bloginfo('template_url'));

                        $lista = '';

                        foreach ($cotas as $cota) {
                                $data = new DateTime($cota->data);
                                $lista .= '<tr>';
                                $lista .= '<td>'.$data->format('d/m/Y').'</td>';
                                $cotaCompra = str_replace(',', '.', $cota->compra);
                                $lista .= '<td>R$ '.number_format($cotaCompra, 4, ',', '.').'</td>';
                                $cotaVenda = str_replace(',', '.', $cota->venda);
                                $lista .= '<td>R$ '.number_format($cotaVenda, 4, ',', '.').'</td>';
                                $lista .= '</tr>';
                        }

                        $box->addVar('lista', $lista);
                        $cotacoes->addVar('box', $box->render());
                } else {
                        $box = new Template(dirname(__FILE__) . '/cotacao-dolar.form.tpl.php');
                        $box->addVar('action', $action);
                        $ultima_data = explode('-', self::ultimaData('dolar'));
                        $box->addVar('cotas', array('dia' => $ultima_data[2], 'mes' => $ultima_data[1], 'ano' => $ultima_data[0]));
                        $ultima_data_periodo = strtotime('-1 month', mktime(0, 0, 0, $ultima_data[1], $ultima_data[2], $ultima_data[0]));
                        $ultima_data_periodo = explode('-', date('Y-m-d', $ultima_data_periodo));
                        $box->addVar('cotas_de', array('dia_de' => $ultima_data_periodo[2], 'mes_de' => $ultima_data_periodo[1], 'ano_de' => $ultima_data_periodo[0]));

                        $cotacoes->addVar('box', $box->render());
                }
*/
                $html .= $cotacoes->render();
                return $html;
        }

        public static function pages($content) {
                if (strpos($content, "<p><!-- cotacao-dolar -->") !== FALSE) {
                        $content = str_replace("<p><!-- cotacao-dolar -->", Cotacoes::pageDolar(), $content);
                } else {
                        if (strpos($content, "<!-- cotacao-dolar -->") !== FALSE) {
                                $content = str_replace("<!-- cotacao-dolar -->", Cotacoes::pageDolar(), $content);
                        }
                }

                if (strpos($content, "<p><!-- cotacao-euro -->") !== FALSE) {
                        $content = str_replace("<p><!-- cotacao-euro -->", Cotacoes::pageEuro(), $content);
                } else {
                        if (strpos($content, "<!-- cotacao-euro -->") !== FALSE) {
                                $content = str_replace("<!-- cotacao-euro -->", Cotacoes::pageEuro(), $content);
                        }
                }

                if (strpos($content, "<p><!-- cotacao-poupanca -->") !== FALSE) {
                        $content = str_replace("<p><!-- cotacao-poupanca -->", Cotacoes::pagePoupanca(), $content);
                } else {
                        if (strpos($content, "<!-- cotacao-poupanca -->") !== FALSE) {
                                $content = str_replace("<!-- cotacao-poupanca -->", Cotacoes::pagePoupanca(), $content);
                        }
                }

                if (strpos($content, "<p><!-- cotacao-real -->") !== FALSE) {
                        $content = str_replace("<p><!-- cotacao-real -->", Cotacoes::pageReal(), $content);
                } else {
                        if (strpos($content, "<!-- cotacao-real -->") !== FALSE) {
                                $content = str_replace("<!-- cotacao-real -->", Cotacoes::pageReal(), $content);
                        }
                }

                if (strpos($content, "<p><!-- cotacao-bitcoin -->") !== FALSE) {
                        $content = str_replace("<p><!-- cotacao-bitcoin -->", Cotacoes::pageBitcoin(), $content);
                } else {
                        if (strpos($content, "<!-- cotacao-bitcoin -->") !== FALSE) {
                                $content = str_replace("<!-- cotacao-bitcoin -->", Cotacoes::pageBitcoin(), $content);
                        }
                }

                return $content;
        }

        public static function grafico($moeda) {
                $periodos = array(7, 30, 100, 365);

                foreach ($periodos as $dias) {
                        if ($moeda == 'poupanca') {
                                $sql = "SELECT rend FROM poupanca ORDER BY data DESC LIMIT 0, {$dias}";
                                $sql_max = "SELECT MAX(rendimento) as max FROM (SELECT REPLACE(SUBSTR(rend, 1, 4), ',', '.') as rendimento FROM poupanca ORDER BY data DESC LIMIT 0, $dias) p";
                                $sql_min = "SELECT MIN(rendimento) as min FROM (SELECT REPLACE(SUBSTR(rend, 1, 4), ',', '.') as rendimento FROM poupanca ORDER BY data DESC LIMIT 0, $dias) p";
			} else if ( $moeda == 'USD' ) {
                                $sql = "SELECT compra as valor FROM dados_dolar order by data DESC LIMIT 0, {$dias}";
                                $sql_max = "SELECT MAX(compra) as max FROM (SELECT compra FROM dados_dolar order by data DESC LIMIT 0, $dias) d";                            
                                $sql_min = "SELECT MIN(compra) as min FROM (SELECT compra FROM dados_dolar order by data DESC LIMIT 0, $dias) d";                            
                        } else if ( $moeda == 'EUR' ) {
                                $sql = "SELECT compra as valor FROM dados_euro order by data DESC LIMIT 0, {$dias}";
                                $sql_max = "SELECT MAX(compra) as max FROM (SELECT compra FROM dados_euro order by data DESC LIMIT 0, $dias) d";                             
                                $sql_min = "SELECT MIN(compra) as min FROM (SELECT compra FROM dados_euro order by data DESC LIMIT 0, $dias) d";
                        } else {
                                $sql = "SELECT valor FROM moedas_dado where code='{$moeda}' order by data DESC LIMIT 0, {$dias}";
                                $sql_max = "SELECT MAX(valor) as max FROM (SELECT valor FROM moedas_dado where code='{$moeda}' order by data DESC LIMIT 0, $dias) d";
                                $sql_min = "SELECT MIN(valor) as min FROM (SELECT valor FROM moedas_dado where code='{$moeda}' order by data DESC LIMIT 0, $dias) d";
                        }

                        $busca = self::$wpdb->get_results($sql);
                        $dados_values = array();

                        foreach ($busca as $item) {
                                $dados_values[] = $item;
                        }

                        $dados_values = array_reverse($dados_values);
                        $_dados = '';

                        foreach ($dados_values as $dado) {
                                if ($moeda == 'poupanca') {
                                        $_dados .= trim(str_replace(array(',', '%'), array('.', ''), $dado->rend)) .',';
                                } else {
                                        $_dados .= $dado->valor .',';
                                }
                        }
                        $max = self::$wpdb->get_results($sql_max);

                        if(isset($max[0]->max)){
                                $max = number_format($max[0]->max, 2, ',', '.');
                        }

                        $min = self::$wpdb->get_results($sql_min);

                        if(isset($min[0]->min)) {
                                $min = number_format($min[0]->min, 2, ',', '.');
                        }

                        $dados['grafico'.$dias.'dados'] = substr($_dados, 0, -1);
                        $dados['grafico'.$dias.'alta']  = $max;
                        $dados['grafico'.$dias.'baixa'] = $min;
                }

                return $dados;
        }

        public static function bolsa() {
                $bolsas = array(
                        'Merval' => 'ar',
                        'Dow Jones' => 'us',
                        'Nasdaq' => 'us',
                        'S&P500' => 'us',
                        'IPC' => 'mx',
                        'Nikkei' => 'jp',
                        'data' => '',
                );

                $code = implode("','", array_keys($bolsas));
                $cotas = self::$wpdb->get_results("SELECT code, tipo, var, pontos, data FROM dados_bolsas WHERE code IN ('{$code}')");

                foreach($cotas as $cota) {
                        $cota->flag = $bolsas[$cota->code];
                }

                return $cotas;
        }

        public static function libraHoje() {
                return self::$wpdb->get_row("SELECT venda, tipo FROM dados_libra WHERE tipo = 'Comercial' ORDER BY data DESC LIMIT 0,1");
        }

        public static function pesoHoje($data = null) {
                return self::$wpdb->get_row("SELECT DISTINCT n.nome, n.pais, d.* FROM moedas_dado d INNER JOIN moedas_nome n ON n.code = d.code WHERE d.data = (SELECT MAX(data) from moedas_dado) and d.code = 'ARS' and n.pais = 'ARGENTINA' ORDER BY pais ASC");
        }

        public static function ultimaData($moeda) {
                if ($moeda == 'dolar') {
                        return self::$wpdb->get_var("SELECT data FROM dados_dolar ORDER BY data DESC LIMIT 1");
                }
                if ($moeda == 'euro') {
                        return self::$wpdb->get_var("SELECT data FROM dados_euro ORDER BY data DESC LIMIT 1");
                }
        }
}

endif;

?>
