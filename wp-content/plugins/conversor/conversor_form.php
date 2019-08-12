<?php
	function conv_form() {
		$tpl = file_get_contents(dirname(__FILE__) . '/conversor.form.tpl.php');

		$tpl_vars['template_url'] = get_bloginfo('template_url');
		$tpl_vars['url'] = get_bloginfo('siteurl');
		$tpl_vars['action'] = get_bloginfo('siteurl').'/moedas/conversor-de-moedas';
		$tpl_vars['valor'] = (isset($_POST['valor'])) ? $_POST['valor'] : 1;

		if (isset($_GET['o'])) {
			switch ($_GET['o']) {
				case 'code':
					$order = 'code';
					break;
				case 'nome':
					$order = 'nome';
					break;
				default:
					$order = 'pais';
			}
		} else {
			$order = 'pais';
		}

		$moedas = conv_get_moedas(false, $order);
		$options = array();

		foreach ($moedas as $moeda) {
			switch ($order) {
				case 'code':
					$label = '('.$moeda->code.') '.ucwords(strtolower($moeda->pais)).', '.ucwords(strtolower($moeda->nome));
					break;
				case 'nome':
					$label = ucwords(strtolower($moeda->nome)).', '.ucwords(strtolower($moeda->pais)).' ('.$moeda->code.')';
					break;
				default:
					$label = ucwords(strtolower($moeda->pais)).', '.ucwords(strtolower($moeda->nome)).' ('.$moeda->code.')';
			}
			$options[strtoupper($moeda->pais)] = $label;
		}

		$dt = (isset($_GET['f'])) ? strtoupper($_GET['f']) : 'BRASIL';
		$df = (isset($_GET['t'])) ? strtoupper($_GET['t']) : 'ESTADOS UNIDOS';
		$tpl_vars['origem'] = conv_build_select('conv_from', $options, $df);
		$tpl_vars['destino'] = conv_build_select('conv_to', $options, $dt);

		// aplicando variáveis
		foreach ($tpl_vars as $key => $value) {
			$tpl = str_replace('{'.$key.'}', $value, $tpl);
		}
		return $tpl;
	}
?>
