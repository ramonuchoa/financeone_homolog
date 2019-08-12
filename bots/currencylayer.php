<?php 

/**
 * 
 * Classe pare conexão ao Currency Layer.
 * 
 * Atualmente temos a conta Enterprise, o que nos dá 
 * direito a todas as funções disponíveis na API. 
 * 
 * 
 * 
 * 
 */

require_once "vendor/autoload.php";
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class CurrencyLayer {


    public function __construct() {
        $this->access_key = "cb8a60847541bdb828ff4274697cdc36";
        $this->currencies = "BRL";

        $this->baseuri = "https://apilayer.net/api/";

        $this->client = new Client([
            "base_uri" => $this->baseuri
        ]);
    }


    /**
     * 
     * Consulta a cotação atual da moeda pedida.
     * 
     * Por padrão, a API retorna um JSON. Estamos
     * usando o Guzzle para termos maior flexibilidade
     * com a preparação dos itens a serem salvos no 
     * banco. Também nos facilita trabalhar com os 
     * códigos de erro. 
     * 
     * @endpoint String endpoint a ser consultado. 
     *           live = cotação atual .
     * 
     * @source String moeda para qual se deseja a cotação.
     * 
     * 
     */
    public function getCotacao($endpoint, $source) 
    {

        $endpoint = (isset($endpoint) == false) ? "live" : $endpoint;
        $source = (isset($source) == false) ? "USD" : $source;

        $currencies = $this->currencies;

        $cl = array("access_key" => $this->access_key, 
        "format" => 1, 
        "source" => $source, 
        "currencies" => $currencies);

        $resp = $this->client->request("POST", "/${endpoint}", ["query" => $cl] );
        $valores = $resp->getBody()->getContents();

        $array_valores = json_decode($valores, true); 

        if ($array_valores["success"] === true){
            return $array_valores;
        } else {
            echo "Houve um erro ao consultar a API: " . $array_valores["error"]["info"]; 
            die;
        }
            

    }

    /**
     * 
     * Consulta a variação da moeda no intervalo pedido.
     * 
     * Por padrão, a API retorna um JSON. Estamos
     * usando o Guzzle para termos maior flexibilidade
     * com a preparação dos itens a serem salvos no 
     * banco. Também nos facilita trabalhar com os 
     * códigos de erro. 
     * 
     * @endpoint String endpoint a ser consultado. 
     *           live = cotação atual .
     * 
     * @to String moeda da qual se deseja a conversão.
     * @from String moeda para qual será feita a conversão.
     * 
     * 
     */

    public function getVariacao($source, $intervalo) 
    {

        $source = (isset($source) == false) ? "USD" : $source;

        $currencies = $this->currencies;

        $cl = array("access_key" => $this->access_key, 
            "format" => 1, 
            "source" => $source, 
            "currencies" => $currencies,
            "date" => $intervalo
        );

        $resp = $this->client->request("POST", "/change", ["query" => $cl] );
        $valores = $resp->getBody()->getContents();

        $array_valores = json_decode($valores, true); 

        if ($array_valores["success"] === true){
            return $array_valores;
        } else {
            echo "Houve um erro ao consultar a API: " . $array_valores["error"]["info"]; 
            die;
        }

    }

    public function conversor($from, $to, $valor)
    {

        $from = (isset($from) == false) ? "USD" : $from;
        $to = (isset($to) == false) ? $this->currencies : $to;

        $cl = array("access_key" => $this->access_key,         
        "from" => $from, 
        "to" => $to,
        "amount" => round($valor,3,PHP_ROUND_HALF_EVEN)
        );

        $resp = $this->client->request("POST", "/convert", ["query" => $cl] );
        $valores = $resp->getBody()->getContents();

        $array_valores = json_decode($valores, true); 

        if ($array_valores["success"] === true){
            return $array_valores;
        } else {
            echo "Houve um erro ao consultar a API: " . $array_valores["error"]["info"]; 
            die;
        }

    }


}

