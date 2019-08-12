<?php

require_once "currencylayer.php";

class FinanceOneLibra {

    public function __construct()
    {

    }

    public function getLibraCorrente()
    {

        $cl = new CurrencyLayer();
        $valor = $cl->getCotacao("live","GBP"); 

        return $valor; 

    }

    /**
     *  Variação: variação de 1 dia por padrão.
     * 
     *  Também é possível passar um período completo, no 
     *  formato AAAA-MM-DD, para a consulta direto no CurrencyLayer. 
     * 
     * 
     */

    public function libraVariacao($intervalo)
    {

        $default = new DateTime("today");

        $intervalo = (isset($intervalo)) ? $intervalo : $default;
        
        $data_captura = $intervalo->format("Y-m-d H:i");

        $cl = new CurrencyLayer();
        $variacao = $cl->getVariacao("GBP",$intervalo);        

        $compra = $variacao["quotes"]["GBPBRL"]["end_rate"];
        $venda = $variacao["quotes"]["GBPBRL"]["end_rate"];
        $variacao_pct = $variacao["quotes"]["GBPBRL"]["change_pct"];
        $moeda = "Comercial";
        
        $dados_libra = "INSERT INTO dados_libra (data,compra,venda,var,tipo) 
        VALUES ('%s', '%s','%s','%s','%s')";

        $query_libra_com = sprintf($dados_libra,$data_captura ,round($compra,3,PHP_ROUND_HALF_EVEN), 
                                   round($venda,3,PHP_ROUND_HALF_EVEN), $variacao_pct, $moeda); 
        
                                   

        return $query_libra_com;

    }

    public function insertCotacao($intervalo)
    {
        $intervalo = (isset($intervalo)) ? $intervalo : null;

        $conn = new mysqli("159.203.119.248","root","db_f1n4nc30n3","finance_one");

        if ($conn->connect_errno) {
            error_log("Problema ao conectar ao Banco.",3,"/var/log/apache/error_log");            
            die;
        }

        $query_libra_com = $this->libraVariacao($intervalo);

        $conn->query($query_libra_com);

    }

    

}
