<?php
class Bot_MySQL
{

  private $pdo;
  public $wpdb;

    public function __construct()
    {


        try {
            //$this->pdo = new PDO(sprintf("mysql:host=%s;dbname=%s", getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE')), getenv('MYSQL_ROOT_USER'), getenv('MYSQL_ROOT_PASSWORD'));
            $this->pdo = new PDO(sprintf("mysql:dbname=%s;host=%s",getenv('MYSQL_DATABASE'), '192.241.253.18'), getenv('MYSQL_ROOT_USER'), getenv('MYSQL_ROOT_PASSWORD'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    public function salvarIndicadores($indicadores)
    {
        foreach ($indicadores as $key => $indicador) {
            $papel      = $key;
            $descricao  = $indicador['descricao'];
            $fechamento = $indicador['fechamento'];
            $hora       = $indicador['hora'];
            $data       = $indicador['data'];
            echo "\nINSERT INTO indicadores_economicos (data,fechamento,descricao,indicador) VALUES ('{$data}', '{$fechamento}','{$descricao}','{$papel}')\n";
            $sql = "INSERT INTO indicadores_economicos (data,fechamento,descricao,indicador) VALUES ('$data', '$fechamento','$descricao','$papel');";
            echo $this->executarSQL($sql);
        }
    }

    public function salvarIndicesMundiais($indicadores)
    {
        foreach ($indicadores as $key => $indicador) {
            $papel      = $key;
            $descricao  = $indicador['descricao'];
            $fechamento = $indicador['fechamento'];
            $hora       = $indicador['hora'];
            $data       = $indicador['data'];
            echo "\nINSERT INTO indices_mundiais (data,fechamento,descricao,indicador) VALUES ('{$data}', '{$fechamento}','{$descricao}','{$papel}')\n";
            $sql = "INSERT INTO indices_mundiais (data,fechamento,descricao,indicador) VALUES ('$data', '$fechamento','$descricao','$papel');";
            echo $this->executarSQL($sql);
        }
    }

    public function executarSQL($sql)
    {
        $this->pdo->exec($sql);
    }


    public function dolar_novo_jef_site_valor($dolar)
    {
        $data = date('Y-m-d H:i:s');
        $sql_2 = "";

        foreach($dolar as $key => $dados) {
            $compra = strip_tags($dados['compra']);
            $venda  = strip_tags($dados['venda']);
            $moeda  = 'USD';
            $sql = "INSERT INTO dados_dolar  (`data`, `compra`, `venda`, `var`, `tipo`) VALUES ('".$data."', '".strip_tags($dados['compra'])."', '". strip_tags($dados['venda'])."', '".$dados['variacao']."', '". $key."')";
            file_put_contents('teste_sql.txt', $sql);
            $this->pdo->exec($sql);
            $sql_2 .= $sql. "<hr>";

            if ($key == 'Comercial') {
                $dataSemHora = date('Y-m-d', strtotime($data));
                if (count($this->moedaByDate('USD', $dataSemHora)) > 0) {
                    $this->updateMoeda('USD', $dataSemHora, $venda, $compra);
                }else{
                    $this->insertMoeda('USD', $dataSemHora, $venda, $compra);
                }

                if (count($this->temHistoricoDolar($dataSemHora)) > 0) {
                    $sql = "UPDATE historico_dolar SET `compra` = '".strip_tags($dados['compra'])."', `venda` = '". strip_tags($dados['venda'])."' WHERE data = '$dataSemHora'";
                } else {
                    $sql = "INSERT INTO historico_dolar  (`data`, `compra`, `venda`) VALUES ('".$data."', '".strip_tags($dados['compra'])."', '". strip_tags($dados['venda'])."')";
                }

                file_put_contents('teste_sql.txt', $sql);
                $this->pdo->exec($sql);
                $sql = '';
                $sql_2 .= $sql. "<hr>";
            }
        }
    }

    public function calcula_variacao($valor, $tipo)
    {
        $yesterday = date("Y-m-d", time() - 86400);

        $sql = "SELECT venda FROM dados_dolar WHERE tipo LIKE '".$tipo."' and DATE(data) = '".$yesterday."' ORDER BY data DESC LIMIT 1";

        $ultima = $this->pdo->query($sql)->fetch();

        $ultimo_valor = floatval($ultima['venda']);
        $valor = floatval($valor);

        $variacao =(double) (($valor/ $ultimo_valor)-1);

        return round($variacao,2);
    }

  public function dolar($dados, $dataGerada)
  {
    $logger = Bot_Logger::getInstance('dolar');

    foreach ($dados as $tipo => $values) {
      // Hora
	  $hora = str_replace("h",":",$values['hora']);
      $hora = explode(':',$hora);
      $min  = intval($hora[1]);

      if ($min < 15) {
  			$min = "00";
  		} else if ($min < 30) {
  			$min = "15";
  		} else if ($min < 45) {
  			$min = "30";
  		} else {
  			$min = "45";
  		}

  		//$data = $dataGerada . ' ' . $hora[0] . ':' . $min . ':00';

        $data = date('Ymd H:i:s');

      $sql = "INSERT INTO dados_dolar (data,compra,venda,var,tipo) VALUES ('{$data}', '".$values['compra']."', '".$values['venda']."', '".$values['variacao']."', '{$tipo}')";

        $sql_2 = "INSERT INTO dados_dolar  (`data`, `compra`, `venda`, `var`, `tipo`) VALUES ('2015-03-25 00:00:00', '3.12', '3.13', '', 'Paralelo');";
        $this->pdo->exec($sql);
  		// Ultima entrada no db
  		$ultima = $this->pdo->query("SELECT compra, venda FROM dados_dolar WHERE tipo LIKE '{$tipo}' ORDER BY data DESC LIMIT 1")->fetch();


		$ultima_compra  = $ultima['compra'];
  		$ultima_venda   = $ultima['venda'];

  		if ($ultima_compra != substr($values['compra'], 0, 5) && $ultima_venda != substr($values['venda'], 0, 5)) {
  		  $busca = $this->pdo->query("SELECT * FROM dados_dolar WHERE data = '{$data}' AND tipo LIKE '{$tipo}'")->fetchAll();

  		  if ($busca) {
  		    $logger->log('Atualizando cotação do dolar: ' . $tipo);

  		    $this->pdo->exec("UPDATE dados_dolar SET compra = '".$values['compra']."', venda = '".$values['venda']."', var = '".$values['variacao']."', WHERE data = '{$data}' AND tipo LIKE '{$tipo}'");
  		  } else {
  		    $logger->log('Inserindo nova cotação do dolar: ' . $tipo);

  		    $this->pdo->exec("INSERT INTO dados_dolar (data,compra,venda,var,tipo) VALUES ('{$data}', '".$values['compra']."', '".$values['venda']."', '".$values['variacao']."', '{$tipo}')");
  		  }
  		}
    }
  }

  public function poupanca($dados, $semestre, $ano)
  {
    $logger = Bot_Logger::getInstance('poupanca');

    foreach ($dados as $mes => $dias) {
      foreach ($dias as $dia => $valor) {
        if (strstr($valor, ',')) {
          $_dia  = $dia + 1;
          $_dia  = isset($_dia{2}) ? $_dia : '0'.$_dia;
          $_mes = $mes + ($semestre == 1 ? 0 : 6);

		  $data = $ano . "-" . $_mes . '-' . $_dia;

		  echo $data . " = " . $valor . "<BR>";

          $busca = $this->pdo->query("SELECT rend FROM poupanca WHERE data = '{$data}'")->fetch();

          if (!$busca) {
            $logger->log('Incluindo valor da data ' . $data . ' para ' . $valor);
            $this->pdo->exec("INSERT INTO poupanca (data, rend) VALUES ('{$data}', '{$valor}')");
          }
		  else
		  {
            $logger->log('Atualizando valor da data ' . $data . ' para ' . $valor);
            $this->pdo->exec("UPDATE poupanca SET rend = '{$valor}' WHERE data='{$data}'");
		  }
          $this->pdo->exec("DELETE FROM poupanca WHERE day(data)='29'");
		  $this->pdo->exec("DELETE FROM poupanca WHERE day(data)='30'");
		  $this->pdo->exec("DELETE FROM poupanca WHERE day(data)='31'");
        }
      }
    }
  }

  public function moedaByDate($moeda, $data)
  {
    return $this->pdo->query("SELECT * FROM moedas_dado WHERE code = '{$moeda}' AND data = '{$data}'")->fetch();
  }

  public function temHistoricoDolar($data)
  {
    return $this->pdo->query("SELECT * FROM historico_dolar WHERE data = '{$data}'")->fetch();
  }

  public function insertMoeda($moeda, $data, $venda, $compra)
  {
    $this->pdo->exec("INSERT INTO moedas_dado (data, code, valor, compra) VALUES ('{$data}', '{$moeda}', '{$venda}', '{$compra}')");

    if ($moeda == 'USD') {
      $query = "INSERT INTO historico_dolar (data, compra, venda) VALUES ('{$data}', '{$compra}', '{$venda}')";
      try {
            $this->pdo->exec($query);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
  }

  public function updateMoeda($moeda, $data, $venda, $compra)
  {
    $this->pdo->exec("UPDATE moedas_dado SET valor = '{$venda}', compra = '{$compra}' WHERE code = '{$moeda}' AND data = '{$data}'");
  }

  public function bolsa($dados)
  {
    $logger = Bot_Logger::getInstance('bolsa');

    foreach ($dados as $tipo => $values) {
      // Data
      $hora = (strstr($values['hora'], '/')) ? '00:00' : $values['hora'];
      $min  = (intval($hora{4}) >= 0 && intval($hora{4}) < 5) ? 0 : 5;
    	$hora = substr($hora, 0, 4) . $min;
  		$data = date('Y-m-d') . ' ' . $hora;

  		// Variação
  		$sinal  = (preg_match("/^-/", $values['var'])) ? '-' : '+';
    	$var    = str_replace(',', '.', $values['var']) . '%';

    	// Pontos
    	$pontos = str_replace(array('.', ','), array('', '.'), $values['pontos']);

    	// Erros
    	if (substr($hora,0,2) > date("G", strtotime(5 . 'hours'))) {
    	  return false;
    	} else if ($hora == '00:00') {
    	  return false;
    	} else if ($pontos == '' || strlen($pontos) > 10 || !is_numeric($pontos)) {
    	  return false;
    	} else if (!is_numeric(str_replace('%', '', $var))) {
    	  return false;
    	}

    	$logger->log('Atualizando dados da bolsa: ' . $tipo);

    	// Atualiza os dados atuais
    	$this->pdo->exec("UPDATE dados_bolsas SET tipo = '{$sinal}', var = '{$var}', pontos = '{$pontos}', data = '{$data}' WHERE code = '{$tipo}'");

    	// Atualiza a data
    	if ($tipo == 'IBovespa') {
    	  $this->pdo->exec("UPDATE dados_bolsas SET data = '{$data}' WHERE code = 'data' and data < '{$data}' and '{$data}' <= NOW()");
    	}

  		// Histórico
  		$ultima = $this->pdo->query("SELECT pontos FROM dados_bolsas_historico WHERE code LIKE '{$tipo}' ORDER BY data DESC LIMIT 1")->fetch();
  		$pontos_old = ($ultima) ? $ultima['pontos'] : 0;

  		if ($pontos_old != $pontos) {
  		  $logger->log('Histórico atualizado para bolsa: ' . $tipo);

  		  $this->pdo->exec("INSERT INTO dados_bolsas_historico (code, pontos, data) VALUES ('{$tipo}', '{$pontos}', '{$data}')");
  		}
    }
  }

  public function saveLog($name, $logs)
  {
    $logger = Bot_Logger::getInstance($name);
    $logger->log('Log encerrado em ' . date('d/m/Y H:i:s'));

    $log = implode("\n", $logger->logs);

    $this->pdo->exec("INSERT INTO logs_bot (name, log, data) VALUES ('{$name}', '{$log}', NOW())");
  }

  // BITCOIN
  public function handleBitcoin($exchanges, $rates)
  {
      $today = date('Y-m-d');
      $query = "SELECT id, data, exchange FROM cotacao_bitcoin WHERE data = '$today'";
      $cotacoes = $this->pdo->query($query)->fetchAll();

      // var_dump($cotacoes);die;

      if (count($cotacoes) > 0) {
          echo "Updating exchanges...\n";
          $this->updateBitcoin($exchanges,$rates, $today);
          echo "Done! \n";
      } else {
          echo "Inserting exchanges... \n";
          $this->insertBitcoin($exchanges,$rates, $today);
          echo "Done! \n";
      }
  }

  public function insertBitcoin($exchanges, $rates, $today)
  {
      $query = "INSERT INTO cotacao_bitcoin
              (cotacao, usd_comercial, usd_turismo, data, exchange)
            VALUES ";

      foreach ($exchanges as $exchange => $values) {
          $query .= "({$values->last},{$rates->USDCBRL},{$rates->USDTBRL},'$today','$exchange'),";
      }

      $query = rtrim($query,",");

      try {
          $this->pdo->exec($query);
      } catch (PDOException $e) {
          echo "Err... ", $e->getMessage(), "\n";
      }
  }
  
  public function updateBitcoin($exchanges, $rates, $today)
  {
      foreach ($exchanges as $exchange => $values) {
          $query = "UPDATE cotacao_bitcoin
              SET cotacao = {$values->last},
                usd_comercial = {$rates->USDCBRL},
                usd_turismo = {$rates->USDTBRL}
              WHERE
                data = '$today'
                AND exchange = '$exchange'";

          try {
              $this->pdo->exec($query);
          } catch (PDOException $e) {
              echo "Err... ", $e->getMessage(), "\n";
          }
      }
  }

}
?>
