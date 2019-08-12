<?php

require_once "currencylayer.php";

class FinanceOneEuro {

    public function __construct()
    {

    }

    public function getEuroCorrente()
    {

        $cl = new CurrencyLayer();
        $valor = $cl->getCotacao("live","EUR"); 

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

    public function euroVariacao($intervalo)
    {

        $default = new DateTime("today");

        $intervalo = (isset($intervalo)) ? $intervalo : $default;
        
        $data_captura = $intervalo->format("Y-m-d H:i:s");

        $cl = new CurrencyLayer();
        $variacao = $cl->getVariacao("EUR",$intervalo);        

        $compra = $variacao["quotes"]["EURBRL"]["end_rate"];
        $venda = $variacao["quotes"]["EURBRL"]["end_rate"];
        $variacao_pct = $variacao["quotes"]["EURBRL"]["change_pct"];
        $moeda = "Comercial";
        
        $dados_euro = "INSERT INTO dados_euro (data,compra,venda,var,tipo) 
        VALUES ('%s', '%s','%s','%s','%s')";

        $query_euro_com = sprintf($dados_euro,$data_captura ,round($compra,3,PHP_ROUND_HALF_EVEN), 
                                   round($venda,3,PHP_ROUND_HALF_EVEN), $variacao_pct, $moeda);





        return $query_euro_com;

    }

    public function insertCotacao($intervalo)
    {
        

        $intervalo = (isset($intervalo)) ? $intervalo : null;

        $conn = new mysqli("159.203.119.248","root","db_f1n4nc30n3","finance_one");

        if ($conn->connect_errno) {
            error_log("Problema ao conectar ao Banco.",3,"/var/log/apache/error_log");            
            die;
        }

        $query_euro_com = $this->euroVariacao($intervalo);


        $conn->query($query_euro_com);

    } 

}
