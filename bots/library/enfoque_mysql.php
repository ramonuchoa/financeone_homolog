<?php
class Enfoque_MySQL {
  private $pdo;
  private $categoriaEnfoque = 36;
  private $arrCategory = array();
  public $wpdb;

  public function __construct()
  {
    include_once  '../wp-config.php';
    include_once  '../wp-load.php';
    include_once  '../wp-includes/wp-db.php';
    include_once  '../wp-includes/pluggable.php';

    global $wpdb;

    $this->wpdb = $wpdb;

    try {
      $this->pdo = new PDO(sprintf("mysql:host=%s;dbname=%s", getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE')), getenv('MYSQL_ROOT_USER'), getenv('MYSQL_ROOT_PASSWORD'));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function post(array $arrDados)
  {

    print_r('<pre>');
    print_r($arrDados);
    print_r('</pre>');
  	if ($arrDados['conteudo'] != '') {
        $titulo   = $arrDados['titulo'];
        $conteudo = $arrDados['conteudo'];

 		if (Bot_Util::retiraAcentos((substr($arrDados['titulo'], 0, 23))) == "chamada de pre abertura") {
 		    $status = 'pending';
 		} else {
 		    $status = 'publish';
 		}

      $this->wpdb->insert('wp_posts', array(
        'post_author' =>'1',
        'post_date'   => $arrDados['data'],
        'post_content' => utf8_encode($conteudo),
        'post_title'   =>  $titulo,
        'post_name' => $this->create_slug(utf8_encode($arrDados['titulo'])),
        'post_status' =>  $status,
        'post_modified' => $arrDados['data'],
        'post_date' => $arrDados['data'],
        'post_date_gmt' => $arrDados['data'],
        'post_modified_gmt' => $arrDados['data']
      ));

      $ultima = $this->wpdb->insert_id;

		$this->pdo->exec("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ('{$ultima}', '_enfoque_id', '{$arrDados['id_pagina']}')");
		$this->pdo->exec("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ('{$ultima}', '_enfoque_url', '{$arrDados['url']}')");
		$this->pdo->exec("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ('{$ultima}', '_enfoque_category', '{$arrDados['categoria']}')");

		foreach ($arrDados['arrCategoria'] AS $categoria) {
			//echo "SELECT term_id FROM wp_terms WHERE name='{$categoria}'<br/>";
      		$arrCategoria = $this->pdo->query("SELECT term_id FROM wp_terms WHERE name='{$categoria}'")->fetch();
    		$this->arrCategory[$arrCategoria['term_id']] = $categoria;
    		$this->pdo->exec("INSERT INTO wp_term_relationships (object_id, term_taxonomy_id) VALUES ('{$ultima}', '{$arrCategoria['term_id']}')");
		}
		if ($status == 'pending') {
		    if (mail('juliana.sousa@hi-midia.com, heric.branco@hi-midia.com, hericbranco@gmail.com, juliannasoh@gmail.com', 'Há uma mensagem no FinanceOne que necessita ser editada', 'http://www.financeone.com.br/wp-admin/post.php?action=edit&post='.$ultima)) {
		    }
		}
    }
  }

  public function atualizaTotalPosts() {
  	foreach ($this->arrCategory AS $id => $value) {
  		$arrResult = $this->pdo->query("SELECT COUNT(object_id) AS total FROM wp_term_relationships WHERE term_taxonomy_id='{$id}'")->fetch();
  		$this->pdo->exec("UPDATE wp_term_taxonomy SET count='{$arrResult['total']}' WHERE term_taxonomy_id='{$id}'");
  	}
  }

  public function verificaId($id_pagina) {
  	if ($this->pdo->query("SELECT meta_id FROM wp_postmeta WHERE meta_key='_enfoque_id' AND meta_value='{$id_pagina}'")->fetchColumn() > 0) {
  		return true;
  	} else {
  		return false;
  	}
  }

    function create_slug($nome)
    {
      global $wpdb;

      // Limpa a string
      $nome = utf8_decode($nome);
      $nome = htmlentities($nome);
      $nome = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1', $nome);
      $nome = strtolower($nome);
      $nome = preg_replace('/\W/', ' ', $nome);
      $nome = preg_replace('/\ +/', '-', $nome);
      $nome = trim($nome, '-');

      return $nome;
    }

  public function saveLog($name, $logs)
  {
    $logger = Bot_Logger::getInstance($name);
    $logger->log('Log encerrado em ' . date('d/m/Y H:i:s'));

    $log = implode("\n", $logger->logs);

    $this->pdo->exec("INSERT INTO logs_bot (name, log, data) VALUES ('{$name}', '{$log}', NOW())");
  }

  public function deletaPosts() {
  	$query = $this->pdo->query("SELECT meta_id, post_id FROM wp_postmeta WHERE meta_key='_enfoque_id'");
  	$i = 0;
  	while ($arrPosts = $query->fetch()) {
  		$i++;
  	}

  	echo "UPDATE wp_term_taxonomy SET count='{$i}' WHERE term_taxonomy_id='36'";
  }

  public function atualizaPostsChamadaPreAbertura() {
      $query = $this->pdo->query('SELECT ID, post_title, post_content FROM wp_posts WHERE post_title LIKE "chamada de pre abertura%"');
      while ($arrPosts = $query->fetch()) {
          $sql_delete_terms = 'DELETE FROM wp_term_relationships WHERE object_id="'.$arrPosts['ID'].'"';
          $this->pdo->exec($sql_delete_terms);

          $this->pdo->exec('INSERT INTO wp_term_relationships(object_id, term_taxonomy_id) VALUES ("'.$arrPosts['ID'].'", "8")');
          $this->pdo->exec('INSERT INTO wp_term_relationships(object_id, term_taxonomy_id) VALUES ("'.$arrPosts['ID'].'", "38")');
      }
  }

  public function deletaPostsArquivo() {
  	$query = $this->pdo->query("SELECT
                                    p.*
                                  FROM
                                    wp_posts AS p,
                                    wp_term_relationships AS tr
                                  WHERE
                                    tr.object_id = p.ID AND
                                    p.post_type='post' AND
                                    tr.term_taxonomy_id = '1'");
 	while ($arrPosts = $query->fetch()) {
  		$sql_delete = 'DELETE FROM wp_posts WHERE ID="'.$arrPosts['ID'].'"';
  		$this->pdo->exec($sql_delete);
  	}
  }
}
?>
