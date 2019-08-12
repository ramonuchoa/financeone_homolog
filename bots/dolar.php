<?php

require_once "currencylayer.php";

class FinanceOneDolar {

    public function __construct()
    {

    }

    public function getDolarCorrente()
    {

        $cl = new CurrencyLayer();
        $valor = $cl->getCotacao("live","USD"); 

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

    public function dolarVariacao($intervalo)
    {

        $default = new DateTime("today");

        $intervalo = (isset($intervalo)) ? $intervalo : $default;
        
        $data_captura = $intervalo->format("Y-m-d H:i");

        $cl = new CurrencyLayer();
        $variacao = $cl->getVariacao("USD",$intervalo);        

        $compra = $variacao["quotes"]["USDBRL"]["end_rate"];
        $venda = $variacao["quotes"]["USDBRL"]["end_rate"];
        $variacao_pct = $variacao["quotes"]["USDBRL"]["change_pct"];
        $moeda = "Comercial";
        
        $dados_dolar = "INSERT INTO dados_dolar (data,compra,venda,var,tipo) 
        VALUES ('%s', '%s','%s','%s','%s')";

        $query_dolar_com = sprintf($dados_dolar,$data_captura ,round($compra,3,PHP_ROUND_HALF_EVEN), 
                                   round($venda,3,PHP_ROUND_HALF_EVEN), $variacao_pct, $moeda); 

        return $query_dolar_com;

    }

    public function insertCotacao($intervalo)
    {
        
        $conn = new mysqli("159.203.119.248","root","db_f1n4nc30n3","finance_one");

        if ($conn->connect_errno) {
            error_log("Problema ao conectar ao Banco.",3,"/var/log/apache/error_log");            
            die;
        }

        $query_dolar_com = $this->dolarVariacao($intervalo);

        $conn->query($query_dolar_com);

    } 

}
