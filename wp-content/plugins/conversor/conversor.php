<?php
/*Plugin Name: Conversor de Moedas
 *Version: 0.1
 *Plugin URI:
 *Description: Conversor de moedas do Finance One
 *Author: Luã de Souza
 *Author URI: http://www.lsouza.pro.br
 */
	require(dirname(__FILE__) . '/conversor_page.php');
	require(dirname(__FILE__) . '/conversor_moedas.php');
	require(dirname(__FILE__) . '/conversor_select_list.php');
	require(dirname(__FILE__) . '/conversor_form.php');

	// Hooks
	add_filter('the_content', 'conv_page');
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	function conv_page_build() {
		global $wpdb;

		$html = '';
		$from = (isset($_POST['conv_from'])?$_POST['conv_from']:(isset($_GET['conv_from'])?$_GET['conv_from']:''));
		$to = (isset($_POST['conv_to'])?$_POST['conv_to']:(isset($_GET['conv_to'])?$_GET['conv_to']:''));
		$valor = (isset($_POST['valor'])?$_POST['valor']:(isset($_GET['valor'])?$_GET['valor']:''));

		if ($from && $to && $valor) {
			$origem['pais'] = $from;
			$destino['pais'] = $to;

			$moeda_from = $wpdb->get_row( "SELECT MN.code, MN.pais, MN.nome, MD.compra, MD.data FROM moedas_nome MN LEFT JOIN moedas_dado MD on MD.code = MN.code WHERE MN.pais LIKE '".$origem['pais']."' ORDER BY MD.data DESC LIMIT 0 , 1" );

			$origem['pais'] = ucwords(strtolower($origem['pais']));
			$origem['code'] = $moeda_from->code;
			$origem['nome'] = ucwords(strtolower($moeda_from->nome));
			$origem['cota'] = $moeda_from->compra;

			switch ($from) {
				case 'ESTADOS UNIDOS':
					$origem['cota'] = clQuote('USD');
					break;
				case 'REINO UNIDO':
					$origem['cota'] = clQuote('GBP');
					break;
				case 'EURO':
					$origem['cota'] = clQuote('EUR');
					break;
				case 'ARGENTINA':
					$origem['cota'] = clQuote('ARS');
					break;
			}

			$moeda_to = $wpdb->get_row( "SELECT MN.code, MN.pais, MN.nome, MD.compra, MD.data FROM moedas_nome MN LEFT JOIN moedas_dado MD on MD.code = MN.code WHERE MN.pais LIKE '".$destino['pais']."' ORDER BY MD.data DESC LIMIT 0 , 1" );

			$destino['pais'] = ucwords(strtolower($destino['pais']));
			$destino['code'] = $moeda_to->code;
			$destino['nome'] = ucwords(strtolower($moeda_to->nome));
			$destino['cota'] = $moeda_to->compra;

			switch ($to) {
				case 'ESTADOS UNIDOS':
					$destino['cota'] = clQuote('USD');
					break;
				case 'REINO UNIDO':
					$destino['cota'] = clQuote('GBP');
					break;
				case 'EURO':
					$destino['cota'] = clQuote('EUR');
					break;
				case 'ARGENTINA':
					$destino['cota'] = clQuote('ARS');
					break;
			}

			if (!$valor) $valor = 1;

			$valor = preg_replace("/,/",".",$valor);
			$origem['cota'] = preg_replace("/,/",".",$origem['cota']);
			$destino['cota'] = preg_replace("/,/",".",$destino['cota']);

			if ($origem['code'] == $destino['code']) {
				$resultado = '';
				$html = '<br>Escolha paises diferentes.';
			} else if ($origem['code'] == 'BRL') {
				$resultado = $valor / $destino['cota'];
			} else if ($destino['code'] == 'BRL') {
				$resultado = $valor * $origem['cota'];
			} else {
				$resultado = ($valor / $destino['cota']) * $origem['cota'];
			}

			$tpl = file_get_contents(dirname(__FILE__) . '/conversor.tpl.php');

			$tpl_vars['template_url'] = get_bloginfo('template_url');
			$tpl_vars['action'] = get_bloginfo('siteurl').'/moedas/conversor-de-moedas';
			$tpl_vars['nome_from'] = $origem['nome'];
			$tpl_vars['pais_from'] = $origem['pais'];
			$tpl_vars['code_from'] = $origem['code'];
			$tpl_vars['nome_to'] = $destino['nome'];
			$tpl_vars['pais_to'] = $destino['pais'];
			$tpl_vars['code_to'] = $destino['code'];
			$tpl_vars['valor'] = $valor;
			$tpl_vars['resultado'] = number_format(sprintf('%01.3f', $resultado), 2, ',', '.');
			$tpl_vars['data'] = date('d/m/Y');

			foreach ($tpl_vars as $key => $value) {
				$tpl = str_replace('{'.$key.'}', $value, $tpl);
			}

			$html .= $tpl;
		} else {
			$html .= conv_form();
		}
		return $html;
	}
?>
