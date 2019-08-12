<?php
	function conv_get_moedas($principais = false, $order = 'pais', $conv = 'de') {
		global $wpdb;

		if (!$principais) {
			$sql = "SELECT code,nome,pais FROM moedas_nome ORDER BY {$order}";
			return $wpdb->get_results($sql);
		} else {
			$sql = "SELECT DISTINCT code,pais,nome FROM moedas_nome ORDER BY $conv DESC, $order LIMIT " . $principais;
			$moedas = $wpdb->get_results($sql);
			$result = array();

			foreach ($moedas as $moeda) {
				$result[$moeda->pais] = $moeda;
			}

			ksort($result);
			return $result;
		}
	}
?>
