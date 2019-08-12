<?php
class Bot_Util {
  public static function get_url($url, $logger = null)
  {
    try {
      if ($logger) $logger->log('Requisitando arquivo ' . $url);
      
      $crl = curl_init();
      $timeout = 5;
      curl_setopt ($crl, CURLOPT_URL,$url);
      curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
      $ret = curl_exec($crl);

      $httpCode = curl_getinfo($crl, CURLINFO_HTTP_CODE);
      
      if($httpCode == 404) {
        throw new Exception('Erro 404: arquivo não encontrado');
      }
      
    } catch (Exception $e) {
      if ($logger) {
        $logger->log($e->getMessage());
        $logger->close();
      }
      
      die();
    }
    
    curl_close($crl);
    return $ret;
  }

  public static function strip_dolar($v)
  {
    $n = explode("\n", $v);
	//echo $v . "<BR>";
    for ($i=0; $i < count($n); $i++) { 
	  //echo trim($n[$i]) . "<BR>";
    }
	
    //$n = array_slice($n, 3, -1);
	//echo $n . "<BR>";
    for ($i=0; $i < count($n); $i++) { 
      $n[$i] = trim($n[$i]);
	  //echo $n[$i] . "<BR>";
    }

    return $n;
  }
  
  public static function strip_moeda($v)
  {
    if (strlen($v)) {
      while ($v{0} == '0') {
        $v = substr($v, 1);
      }
      
      if ($v{0} == '.') $v = '0' . $v;
    }
    
    return $v;
  }
  
  public static function strip_poupanca($values, $semestre)
  {
    $dados  = array();
    $pos    = 0;
    $limite = $semestre == 1 ? 7 : 8;
	
    foreach ($values as $value) {
      // Armazena 0-6 primeiros
      if ($pos < 7) {
        $dados[$pos][] = $value;
		//echo "$pos = $value<br/>";
      } else {
		//echo ">>" . ($pos == $limite) . "$pos = $limite<<<br/>";
		if($pos == $limite)
		{
			$pos = 0;
			continue;
		}
      }
      
      $pos++;
    }
    
    return $dados;
  }
  
  public static function strip_bolsa($v)
  {
    $n = explode("\n", $v);
    $n = array_slice($n, 1, -3);
    $r = array();

    for ($i=0; $i < count($n); $i++) { 
      if (trim($n[$i])) $r[] = trim($n[$i]);
    }
    
    return $r;
  }
	
  public static function retornaSubCategoria($titulo, $texto, $categoria) {
  
  	$titulo = html_entity_decode($titulo);
  	$texto  = html_entity_decode($texto);  	
  	
  	$titulo = str_replace('.', ' ', $titulo);
  	$titulo = str_replace(',', ' ', $titulo);
  	$titulo = str_replace(':', ' ', $titulo);
  	
  	$texto  = str_replace('.', ' ', $texto);
  	$texto  = str_replace(',', ' ', $texto);
  	$texto  = str_replace(':', ' ', $texto);  	
  	
  	$arrConteudo['titulo']   = explode(' ', $titulo);
  	$arrConteudo['texto']    = explode(' ', $texto);
  	
  	if (Bot_Util::retiraAcentos((substr($titulo, 0, 23))) == "chamada de pre abertura") {
  	    $categoria = 'Mercado Financeiro';
  	}
	  	
  	if ($categoria == 'Mercado Cambial') {
  		$euro  = 0;
      	$dolar = 0;
      	$real  = 0;
      	$arrReal  = array('real', 'r$');
      	$arrEuro  = array('euro', '€');
      	$arrDolar = array('dolar', 'us$');
  		foreach ($arrConteudo AS $type => $arrConteudo) {          	
      		$i[$type] = 0;
      		$arrPalavras[$type] = array();  		
      		
          	foreach ($arrConteudo AS $palavra) {			
              	if (array_search(Bot_Util::retiraAcentos($palavra), $arrReal)!==false) {
              		$real++;
              		$arrPalavras[$type][] = Bot_Util::retiraAcentos($palavra);
              	}
              	if (array_search(Bot_Util::retiraAcentos($palavra), $arrEuro)!==false) {
              		$euro++;
              		$arrPalavras[$type][] = Bot_Util::retiraAcentos($palavra);
              	}
              	if (array_search(Bot_Util::retiraAcentos($palavra), $arrDolar)!==false) {
              		$dolar++;
              		$arrPalavras[$type][] = Bot_Util::retiraAcentos($palavra);          		
              	}
          	}
          	
          	if (array_search($arrPalavras[$type][0], $arrReal)!==false) {      		
          		$real++;
          	} elseif (array_search($arrPalavras[$type][0], $arrEuro)!==false) {      		
          		$euro++;
          	} elseif (array_search($arrPalavras[$type][0], $arrDolar)!==false) {      		
          		$dolar++;
          	}
  		}      	
      	if (($real > $euro) && ($real > $dolar)) {
      		$subCategoria = 'Real';
      	} elseif ($euro > $dolar) {
      		$subCategoria = 'Euro';
      	} else {
      		$subCategoria = 'Dolar';
      	}
      	//echo '<pre>';
      	//print_r($arrPalavras);
      	//echo '</pre>';
      	//echo $titulo."<br/>".$texto."<br/>      	
      	//SubCategoria = {$subCategoria}<br/><br/><br/>";
  	} elseif ($categoria == 'Mercado Financeiro') {  	    
  		$arrPoupanca = array('poupanca');
  		$arrBolsa    = array('bolsa');
  		$poupanca    = 0;
  		$bolsa       = 0;
  		//echo Bot_Util::retiraAcentos($titulo).'<br/>';
  		
  		if (strstr(Bot_Util::retiraAcentos($texto), 'bolsa de valores')!==false) {
  			$bolsa++;
  		}
  		foreach ($arrConteudo AS $type => $arrConteudo) {
      		
          	foreach ($arrConteudo AS $palavra) {			
              	if (array_search(Bot_Util::retiraAcentos($palavra), $arrPoupanca)!==false) {
              		$poupanca++;              		
              	}
              	
          		if (array_search(Bot_Util::retiraAcentos($palavra), $arrBolsa)!==false) {
              		$bolsa++;              		
              	}
          	}
  		}
  		if (Bot_Util::retiraAcentos((substr($titulo, 0, 23))) == "chamada de pre abertura") {
  		    $subCategoria = utf8_decode('Chamada de Pré Abertura');
  		} elseif ($poupanca > $bolsa) {
  			$subCategoria = utf8_decode('Poupança');
  		} elseif ($bolsa > $poupanca) {
  			$subCategoria = 'Bolsa de Valores';
  		}
  	} elseif ($categoria == 'Economia') {
  		$arrIR   = array('imposto', 'renda');
  		$arrEuro = array('euro');
  		$ir      = 0;
  		$euro    = 0;
  		foreach ($arrConteudo AS $type => $arrConteudo) {
      		
          	foreach ($arrConteudo AS $palavra) {			
              	if (array_search(Bot_Util::retiraAcentos($palavra), $arrIR)!==false) {
              		$ir++;              		
              	}
              	
          	    if (array_search(Bot_Util::retiraAcentos($palavra), $arrEuro)!==false) {
              		$euro++;              		
              	}
          	}
  		}
  		if ($ir > $euro) {
  			$subCategoria = 'Imposto de Renda';
  		} elseif ($euro > $ir) {
  		    $subCategoria = 'Euro';
  		}
  	} elseif ($categoria == 'Internacional') {
  	    $arrEuro = array('euro');  		
  		$euro    = 0;
  		foreach ($arrConteudo AS $type => $arrConteudo) {
      		
          	foreach ($arrConteudo AS $palavra) {
          	    if (array_search(Bot_Util::retiraAcentos($palavra), $arrEuro)!==false) {
              		$euro++;              		
              	}
          	}
  		}
  		if ($euro > 0) {
  		    $subCategoria = 'Euro';
  		}
  	}
  	
  	if (isset($subCategoria)) {  		
  		$return = array($categoria, $subCategoria);
  	} else {
  		$return = array($categoria);
  	}
  	//echo $titulo.' - '.$categoria.' - '.$subCategoria.'<br/>';
  	return $return;
  }

	public static function retiraAcentos($msg) {	    
	    $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyrr';
        $string = $msg;        
        $string = strtr($string, utf8_decode($a), $b);
        str_replace(" ","",$string);
        $return = $string;
	    $return = strtolower($return);
	    return $return;
	}
  
}
?>
