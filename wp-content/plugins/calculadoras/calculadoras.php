<?php
/*
  Plugin Name: Calculadora Trabalhista
  Plugin URI: http://localhost/
  Description: Plugin para gerenciar cálculos trabalhistas
  Version: 1.0
  Author: Guilherme L. Chaves
  Author URI: http://limachaves.com
  License: GPLv2+
  Text Domain: calculadora
*/

// Widget
//require(dirname(__FILE__).'/widget.php');

// Templates
//require(dirname(__FILE__).'/../template.class.php');

// Init hook
add_filter('init', array('Calculadoras','init'));

if (!class_exists('Calculadoras')) :

class Calculadoras
{
    private static $wpdb;

    public static $extra50 = 1.5;
    public static $extra100 = 2;

    function __construct() {
        
    }

    public static function init()
    {
        if (isset($_POST['bt_submit'])){
            $response = Calculadoras::Resultado();
        }
        else
            $response = Calculadoras::formRescisao();

        return $response;
    }


    public static function ResultadoRescisaoAntecipada(){
        $dados['salario'] = $_POST['salario'];
        $dados['salario'] = str_replace(',', '.', str_replace('.', '', str_replace('R$', '', $dados['salario'])));

        $dados['ferias_ven'] = $_POST['ferias_ven'];

        $dados['dt_inicio'] = $_POST['dt_inicio'];
        $dados['dt_fim'] = $_POST['dt_fim'];
        $dados['dt_fim_contrato'] = $_POST['dt_fim_contrato'];

        $dados['dt_inicio'] = str_replace('/', '-', $dados['dt_inicio']);
        $dados['dt_fim'] = str_replace('/', '-', $dados['dt_fim']);
        $dados['dt_fim_contrato'] = str_replace('/', '-', $dados['dt_fim_contrato']);

        $dados['motivo'] = $_POST['motivo'];
 
        $salarioLiquido = new CalculoSalario($dados);
        $salarioLiquido->teste();

        // discount or increase payments, depends of reason of rescision
        // switch template result
        switch ($dados['motivo']) {
            case 5:
                $resultado = new Template(dirname(__FILE__) . '/resultado-rescisao-antecipada-empregador.tpl.php');
                break;
            
            case 6:
                $resultado = new Template(dirname(__FILE__) . '/resultado-rescisao-antecipada-empregado.tpl.php');
                break;
            
            case 7:
                $resultado = new Template(dirname(__FILE__) . '/resultado-rescisao-antecipada-falecimento.tpl.php');
                break;

            default: return '';
        }

        $salarioLiquido->formataCamposMonetarios();
        
        $resultado->addVar('prazoRemanescente', $salarioLiquido->prazoRemanescente);
        $resultado->addVar('valorPrazoRemanescente', $salarioLiquido->valorPrazoRemanescente);
        $resultado->addVar('irPrazoRemanescente', $salarioLiquido->irPrazoRemanescente);
        $resultado->addVar('inssPrazoRemanescente', $salarioLiquido->inssPrazoRemanescente);
        $resultado->addVar('totalDescontosPrazoRemanescente', $salarioLiquido->totalDescontosPrazoRemanescente);
        $resultado->addVar('liquidoPrazoRemanescente', $salarioLiquido->liquidoPrazoRemanescente);


        foreach ($dados as $key => $val) $resultado->addVar($key, $val);

        $linhaDecimo = 'Décimo Terceiro: '. $salarioLiquido->decimoTerceiro .' <br />';
        $totalDecimo = $totalDecimo + $decimoIndenizado;
        $totalDecimo = Calculadoras::dinheiro($salarioLiquido->totalDecimo);
        $linhaDecimoTotal = '<strong>Total de décimo terceiro: ' . $salarioLiquido->totalDecimo . '</strong><br />';

        $linhaVencimentos = 'Total de vencimentos: ' . $salarioLiquido->saldoDeSalario . ' + ' . $salarioLiquido->decimoTerceiro;
        if (in_array($salarioLiquido->motivo, [5])) 
            $linhaVencimentos .= ' + ' . $salarioLiquido->totalFerias .  ' + ' . $salarioLiquido->valorPrazoRemanescente;
        $linhaVencimentos .= ' = ' . $salarioLiquido->totalVencimentos . '<br>';
        
        if (in_array($salarioLiquido->motivo, [5, 7])) {
            $linhaDescontos = 'Total de descontos: ' . $salarioLiquido->totalDescontosSalario . ' + ' . $salarioLiquido->totalDescontosDecimo;
            if (in_array($salarioLiquido->motivo, [5])) {
                $linhaDescontos.= ' + ' . $salarioLiquido->totalDescontosPrazoRemanescente;
                $linhaDescontos.= ' + ' . $salarioLiquido->totalDescontosFerias;
            }

            $linhaDescontos .= ' = ' . $salarioLiquido->totalDescontosFinal . '<br>';
        }

        if (in_array($salarioLiquido->motivo, [6])) {
            $linhaDescontos = 'Total de descontos: ' . $salarioLiquido->totalDescontosSalario . ' + ' . $salarioLiquido->totalDescontosDecimo . ' + ' . $salarioLiquido->valorPrazoRemanescente;
            $linhaDescontos .= ' = ' . $salarioLiquido->totalDescontosFinal . '<br>';
        }

        $resultado->addVar('totalLiquido', $salarioLiquido->totalFinal);
        $resultado->addVar('totalVencimentos', $salarioLiquido->totalVencimentos);
        $resultado->addVar('diasTrabalhados', $salarioLiquido->diasTrabalhados);
        $resultado->addVar('saldoDeSalario', $salarioLiquido->saldoDeSalario);
        $resultado->addVar('inss', $salarioLiquido->inss);
        $resultado->addVar('ir', $salarioLiquido->ir);
        $resultado->addVar('totalDescontosSalario', $salarioLiquido->totalDescontosSalario);
        $resultado->addVar('decimoTerceiro', $salarioLiquido->decimoTerceiro);
        $resultado->addVar('inssDecimo', $salarioLiquido->inssDecimo);
        $resultado->addVar('irDecimo', $salarioLiquido->irDecimo);
        $resultado->addVar('totalSalario', $salarioLiquido->totalSalario);
        $resultado->addVar('totalDescontosDecimo', $salarioLiquido->totalDescontosDecimo);
        $resultado->addVar('totalDecimo', $salarioLiquido->totalDecimo);
        $resultado->addVar('totalDescontosSalario', $salarioLiquido->totalDescontosSalario);
        $resultado->addVar('totalFerias', $salarioLiquido->totalFerias);
        $resultado->addVar('linhaDecimo', $linhaDecimo);
        $resultado->addVar('linhaDecimoTotal', $linhaDecimoTotal);
        $resultado->addVar('linhaVencimentos', $linhaVencimentos);
        $resultado->addVar('linhaDescontos', $linhaDescontos);
        
        
        $htmlAux .= '<p>';
        if (in_array($salarioLiquido->motivo, [5, 7])) {
            if ($salarioLiquido->ferias_ven) {
                $htmlAux .= 
<<<EOT
                        Férias vencidas: {$salarioLiquido->feriasVencidas} <br />
                        1/3 sobre férias vencidas: {$salarioLiquido->tercoFeriasVencidas} <br />            
EOT;
            }

            $htmlAux .= 
<<<EOT
                Férias proporcionais: {$salarioLiquido->feriasProporcionais} <br />
                1/3 Férias proporcionais: {$salarioLiquido->tercoFeriasProporcionais} <br />
EOT;

            $htmlAux .= 
<<<EOT
                        Férias indenizadas: {$salarioLiquido->feriasIndenizadas} <br />
                        1/3 sobre férias indenizadas: {$salarioLiquido->tercoFeriasIndenizadas} <br />            
EOT;

            $htmlAux .= 
<<<EOT
                Total de Descontos Férias: {$salarioLiquido->totalDescontosFerias} <br />
                <strong>Total de Férias a receber: {$salarioLiquido->totalFerias} - {$salarioLiquido->totalDescontosFerias} = {$salarioLiquido->totalFeriasFinal}</strong>
            </p>
EOT;
        }

        $resultado->addVar('dtAdmissao', $_POST['dt_inicio']);
        $resultado->addVar('dtDemissao', $_POST['dt_fim']);
        $resultado->addVar('ferias', $htmlAux);
        $resultado->addVar('warning', $salarioLiquido->warning);

        $resultado = $resultado->render();
        return $resultado;
    }


    public static function formRescisaoAntecipada() {
        $calculadora = new Template(dirname(__FILE__) . '/form-rescisao-antecipada.tpl.php');
        $calculadora->addVar('src', get_bloginfo('template_url'));

        $fields = [
            'salario'   => [
                'label'     => 'Salário Base',
                'type'      => 'text',
                'class'     => 'form-control',
                'maxlength' => '12',
                'item_class' => '',
            ],
            'dt_inicio' => [
                'label' => 'Data de Admissão',
                'type'  => 'text',
                'date'  => true,
                'class' => 'form-control js-datepicker',
                'maxlength' => '10',
                'item_class' => '',
            ],
            'dt_fim'    => [
                'label' => 'Data de Afastamento',
                'type'  => 'text',
                'date'  => true,
                'class' => 'form-control js-datepicker',
                'maxlength' => '10',
                'item_class' => '',
            ],
            'dt_fim_contrato'    => [
                'label' => 'Data do final do contrato',
                'type'  => 'text',
                'date'  => true,
                'class' => 'form-control js-datepicker',
                'item_class' => '',
                'maxlength' => '10'
            ],
            // 'ferias_ven'    => [
            //     'label' => 'Férias vencidas',
            //     'type'  => 'text',
            //     'class' => 'form-control',
            // ],
        ];

        $html = '';
        foreach ($fields as $key => $field){
            $html .=
<<<EOT
                <div class="form-group {$field['item_class']}">
                    <label for="input_$key">{$field['label']}</label>
                    <input type="text" class="{$field['class']}" id="input_$key" name="$key" placeholder="{$field['label']}" required="required" value="" maxlength="{$field['maxlength']}" >
                </div>
EOT;
        }
        $calculadora->addVar('form', $html);
        $render = $calculadora->render();
        return $render;
    }


    // original

    public static function Resultado(){
        $dados['salario'] = $_POST['salario'];
        $dados['salario'] = str_replace(',', '.', str_replace('.', '', str_replace('R$', '', $dados['salario'])));

        $dados['aviso']      = $_POST['aviso'];
        $dados['ferias_ven'] = $_POST['ferias_ven'];

        $dados['dt_inicio']  = $_POST['dt_inicio'];
        $dados['dt_fim']     = $_POST['dt_fim'];

        $dados['dt_inicio'] = str_replace('/', '-', $dados['dt_inicio']);
        $dados['dt_fim']    = str_replace('/', '-', $dados['dt_fim']);

        $dados['motivo'] = $_POST['motivo'];
 
        $salarioLiquido = new CalculoSalario($dados);
        $salarioLiquido->teste();
        $salarioLiquido->formataCamposMonetarios();

        $resultado = new Template(dirname(__FILE__) . '/resultado-rescisao.tpl.php');
        foreach ($dados as $key => $val){
            $resultado->addVar($key, $val);
        }

        $linhaDecimo = 'Décimo Terceiro: '. $salarioLiquido->decimoTerceiro .' <br />';
        if ($salarioLiquido->motivo == 2){
             if ($dados['aviso'] == 'indenizado')
                 $linhaDecimo .= 'Décimo terceiro indenizado: ' . $salarioLiquido->decimoTerceiroIndenizado . '<br />';
            $totalDecimo = $totalDecimo + $decimoIndenizado;
            $totalDecimo = Calculadoras::dinheiro($salarioLiquido->totalDecimo);
            $linhaDecimoTotal = '<strong>Total de décimo terceiro: ' . $salarioLiquido->totalDecimo . '</strong><br />';
        }else{
            $totalDecimo                = Calculadoras::dinheiro($totalDecimo);
            $decimoIndenizado = 0;
        }

        $linhaVencimentos   = 'Total de vencimentos: ' . $salarioLiquido->saldoDeSalario . ' + ' . $salarioLiquido->decimoTerceiro;
        if (($salarioLiquido->aviso == 'indenizado') && ($salarioLiquido->motivo == 2))
            $linhaVencimentos .= ' + ' . $salarioLiquido->decimoTerceiroIndenizado;
        $linhaVencimentos .= ' + ' . $salarioLiquido->totalFerias;
        if ($salarioLiquido->motivo == 2)
            $linhaVencimentos .= ' + ' . $salarioLiquido->valorAviso;
        $linhaVencimentos .= ' = ' . $salarioLiquido->totalVencimentos . '<br>';
        
        $linhaDescontos         = 'Total de descontos: ' . $salarioLiquido->totalDescontosSalario . ' + ' . $salarioLiquido->totalDescontosDecimo;
        if (($salarioLiquido->motivo == 1) && ($salarioLiquido->aviso == 'indenizado'))
            $linhaDescontos.= ' + ' . $salarioLiquido->salario;
        $linhaDescontos.= ' + ' . $salarioLiquido->totalDescontosFerias;
        $linhaDescontos .= ' = ' . $salarioLiquido->totalDescontosFinal . '<br>';

        $linhaAvisoIndenizado = '';
        if ($salarioLiquido->aviso == 'indenizado'){
            if ($salarioLiquido->motivo == 2)
                $linhaAvisoIndenizado = 'Aviso prévio indenizado ('. $salarioLiquido->diasAviso .' dias): ' . $salarioLiquido->valorAviso . '<br />';
        }

        $resultado->addVar('totalLiquido', $salarioLiquido->totalFinal);
        $resultado->addVar('totalVencimentos', $salarioLiquido->totalVencimentos);
        $resultado->addVar('diasTrabalhados', $salarioLiquido->diasTrabalhados);
        $resultado->addVar('saldoDeSalario', $salarioLiquido->saldoDeSalario);
        $resultado->addVar('inss', $salarioLiquido->inss);
        $resultado->addVar('ir', $salarioLiquido->ir);
        $resultado->addVar('totalDescontosSalario', $salarioLiquido->totalDescontosSalario);
        $resultado->addVar('decimoTerceiro', $salarioLiquido->decimoTerceiro);
        $resultado->addVar('inssDecimo', $salarioLiquido->inssDecimo);
        $resultado->addVar('irDecimo', $salarioLiquido->irDecimo);
        $resultado->addVar('totalSalario', $salarioLiquido->totalSalario);
        $resultado->addVar('linhaAvisoIndenizado', $linhaAvisoIndenizado);
        $resultado->addVar('totalDescontosDecimo', $salarioLiquido->totalDescontosDecimo);
        $resultado->addVar('totalDecimo', $salarioLiquido->totalDecimo);
        $resultado->addVar('totalDescontosSalario', $salarioLiquido->totalDescontosSalario);
        $resultado->addVar('totalFerias', $salarioLiquido->totalFerias);
        $resultado->addVar('linhaDecimo', $linhaDecimo);
        $resultado->addVar('linhaDecimoTotal', $linhaDecimoTotal);
        $resultado->addVar('linhaVencimentos', $linhaVencimentos);
        $resultado->addVar('linhaDescontos', $linhaDescontos);
        
        
        $htmlAux .= '<p>';
            if ($salarioLiquido->ferias_ven){
                $htmlAux .= 
<<<EOT
                        Férias vencidas: {$salarioLiquido->feriasVencidas} <br />
                        1/3 sobre férias vencidas: {$salarioLiquido->tercoFeriasVencidas} <br />            
EOT;
            }
            if ($salarioLiquido->motivo != 3){   //Justa causa
                $htmlAux .= 
<<<EOT
                Férias proporcionais: {$salarioLiquido->feriasProporcionais} <br />
                1/3 Férias proporcionais: {$salarioLiquido->tercoFeriasProporcionais} <br />
EOT;
            }
            if (($salarioLiquido->motivo == 2) && ($dados['aviso'] == 'indenizado')){
                
                $htmlAux .= 
<<<EOT
                        Férias indenizadas: {$salarioLiquido->feriasIndenizadas} <br />
                        1/3 sobre férias indenizadas: {$salarioLiquido->tercoFeriasIndenizadas} <br />            
EOT;
            }
            if ($salarioLiquido->motivo != 3){   //Justa causa
                $htmlAux .= 
<<<EOT
                Total de Descontos Férias: {$salarioLiquido->totalDescontosFerias} <br />
                <strong>Total de Férias a receber: {$salarioLiquido->totalFerias} - {$salarioLiquido->totalDescontosFerias} = {$salarioLiquido->totalFeriasFinal}</strong>
            </p>
EOT;
            }
        $resultado->addVar('dtAdmissao', $_POST['dt_inicio']);
        $resultado->addVar('dtDemissao', $_POST['dt_fim']);
        $resultado->addVar('ferias', $htmlAux);
        $resultado->addVar('warning', $salarioLiquido->warning);

        $resultado = $resultado->render();
        return $resultado;
    }

    public static function ResultadoSalarioLiquido(){
        $dados['salario'] = $_POST['salario'];
        $dados['salario'] = str_replace(',', '.', str_replace('.', '', str_replace('R$', '', $dados['salario'])));

        $dados['dependentes'] = $_POST['dependentes'];
        if ($dados['dependentes'] != '')
            $dados['dependentes'] = '0';
        $dados['outrasDeducoes']    = $_POST['descontos'];
        $dados['outrasDeducoes']    = str_replace(',', '.', str_replace('.', '', str_replace('R$', '', $dados['outrasDeducoes'])));

        $salarioLiquido = new CalculoSalario($dados);
        $salarioLiquido->calculaSalarioLiquido();
        $salarioLiquido->formataCamposMonetarios();

        $resultado = new Template(dirname(__FILE__) . '/resultado-salario-liquido.tpl.php');
        
        $resultado->addVar('salarioBase', $salarioLiquido->salario);
        $resultado->addVar('inss', $salarioLiquido->inss);
        $resultado->addVar('ir', $salarioLiquido->ir);
        $resultado->addVar('totalLiquido', $salarioLiquido->totalSalario);
        $resultado->addVar('deducoes', $salarioLiquido->outrasDeducoes);
        $resultado->addVar('dependentes', $salarioLiquido->dependentes);
        $resultado->addVar('deducaoPorDependentesTotal', Calculadoras::dinheiro($salarioLiquido->dependentes * $salarioLiquido->deducaoPorDependente));

        $resultado = $resultado->render();
        return $resultado;
    }

    public static function ResultadoHoraExtra() {
        $dados['salario'] = $_POST['salario'];
        $dados['salario'] = preg_replace("/[^0-9|,|.]/", "", $dados['salario']);
        $dados['salario'] = str_replace(',', '.', str_replace('.', '', str_replace('R$', '', $dados['salario'])));
        
        $resultado = new Template(dirname(__FILE__) . '/resultado-hora-extra.tpl.php');
        
        // salario base
        $salarioLiquido = new CalculoSalario($dados);
        $salarioLiquido->calculaSalarioLiquido();
        $salarioLiquido->formataCamposMonetarios();

        $resultado->addVar('salarioBase', $salarioLiquido->salario);

        // horas extras
        $dados['jornadaMensal'] = abs(intval($_POST['jornada_mensal']));
        $dados['qtdHorasNormal'] = abs(intval($_POST['qtd_extra_normal']));
        $dados['qtdHoras100'] = abs(intval($_POST['qtd_extra_100']));

        $horaExtra = new CalculoSalario($dados);
        $horaExtra->calculaHoraExtra();

        $totalLiquido = $horaExtra->totalLiquido;
        $horaExtra->formataCamposMonetarios();

        $totalLiquido = Calculadoras::dinheiro($totalLiquido);
        $resultado->addVar('valorExtraNormal', $horaExtra->valorTotalHoraNormal);
        $resultado->addVar('valorExtra100', $horaExtra->valorTotalHora100);
        $resultado->addVar('totalExtra', $horaExtra->subTotalExtra);
        $resultado->addVar('extraInss', $horaExtra->inss);
        $resultado->addVar('extraIr', $horaExtra->ir);
        $resultado->addVar('totalLiquido', $totalLiquido);

        return $resultado->render();
    }

    public static function formRescisao(){
        $calculadora = new Template(dirname(__FILE__) . '/form-rescisao.tpl.php');
        $calculadora->addVar('src', get_bloginfo('template_url'));

        $fields = [
            'salario'   => [
                'label'     => 'Salário Base',
                'type'      => 'text',
                'class'     => 'form-control',
                'maxlength' => '12'
            ],
            'dt_inicio' => [
                'label' => 'Data de Admissão',
                'type'  => 'text',
                'date'  => true,
                'class' => 'form-control js-datepicker',
                'maxlength' => '10'
            ],
            'dt_fim'    => [
                'label' => 'Data de Afastamento',
                'type'  => 'text',
                'date'  => true,
                'class' => 'form-control js-datepicker',
                'maxlength' => '10'
            ],
            // 'aviso'     => [
            //     'label' => 'Aviso Prévio',
            //     'type'  => 'text',
            //     'class' => 'form-control',
            // ],
            // 'ferias_ven'    => [
            //     'label' => 'Férias vencidas',
            //     'type'  => 'text',
            //     'class' => 'form-control',
            // ],
        ];

        $html = '';
        foreach ($fields as $key => $field){
            $html .=
<<<EOT
                <div class="form-group">
                    <label for="input_$key">{$field['label']}</label>
                    <input type="text" class="{$field['class']}" id="input_$key" name="$key" placeholder="{$field['label']}" required="required" value="" maxlength="{$field['maxlength']}" >
                </div>
EOT;
        }
        $calculadora->addVar('form', $html);
        $render = $calculadora->render();
        return $render;
    }

    public static function formSalarioLiquido(){
        $calculadora = new Template(dirname(__FILE__) . '/form-salario-liquido.tpl.php');
        $calculadora->addVar('src', get_bloginfo('template_url'));
        // $calculadora->addVar('form', $html);
        $render = $calculadora->render();
        return $render;
    }

    public static function formHoraExtra(){
        $calculadora = new Template(dirname(__FILE__) . '/form-hora-extra.tpl.php');
        $calculadora->addVar('src', get_bloginfo('template_url'));
        // $calculadora->addVar('form', $html);
        $render = $calculadora->render();
        return $render;
    }

    /**
    * Converte valor e retorna como dinheiro no padrão brasileiro
    **/
    public function dinheiro($value){
        $result = sprintf('%01.2f', $value);
        $result = number_format($result, '2', ',', '.');
        return 'R$ ' . $result;
    }
}

endif;

class CalculoSalario{
    public $dt_inicio;
    public $dt_fim;
    public $dt_fim_contrato;
    public $interval;
    public $salario;
    public $aviso;
    public $motivo;
    public $valorAviso;
    public $diasAviso;
    public $ferias_ven;
    public $dependentes;
    public $deducaoPorDependente = 189.59;
    public $outrasDeducoes = 0;
    public $mesSalarioPago;
    public $diasTrabalhados;
    public $saldoDeSalario;
    public $inss;
    public $aliquotaINSS;
    public $ir;
    public $faixaIR;
    public $decimoTerceiro;
    public $decimoTerceiroIndenizado;
    public $inssDecimo;
    public $irDecimo;
    public $feriasProporcionais;
    public $feriasIndenizadas;
    public $tercoFeriasIndenizadas;
    public $tercoFeriasProporcionais;
    public $feriasVencidas = 0;
    public $tercoFeriasVencidas = 0;
    public $inssFeriasProporcionais;
    public $irFeriasProporcionais;
    public $saldoSalario = 0;
    public $saldoDeSalarioComAviso;
    public $totalSalario;
    public $totalFerias;
    public $totalFeriasFinal;
    public $totalVencimentos;
    public $totalDescontosSalario;
    public $totalDescontosFinal;
    public $totalDescontosFerias;
    public $totalDecimoTerceiro;
    public $totalDescontosDecimo;
    public $totalFinal;
    public $warning = '';

    public $jornadaMensal = 0;
    public $valorHoraExtra = 0;
    public $qtdHorasNormal = 0;
    public $qtdHoras100 = 0;
    public $subTotalExtra = 0;

    public $prazoRemanescente = 0;
    public $valorPrazoRemanescente = 0;
    public $irPrazoRemanescente = 0;
    public $inssPrazoRemanescente = 0;
    public $totalDescontosPrazoRemanescente = 0;
    public $liquidoPrazoRemanescente = 0;

    function __construct($values = null) {
        foreach ($values as $key => $val){
            $this->$key = $val;
        }
    }

    public function init(){
        $this->mesSalarioPago        = date('m', strtotime($this->dt_fim));
        $this->diasTrabalhados       = date('d', strtotime($this->dt_fim));
        $this->saldoDeSalario        = ($this->salario / 30) * (int) $this->diasTrabalhados;
        $this->inss                  = $this->calculaINSS($this->saldoDeSalario);
        $this->ir                    = $this->calculaIR($this->saldoDeSalario, $this->inss);
        $this->totalDescontosSalario = $this->ir + $this->inss;

        $this->decimoTerceiro           = $this->decimoTerceiro($this->mesSalarioPago, $this->salario);
        $this->inssDecimo               = $this->calculaINSS($this->decimoTerceiro);
        $this->irDecimo                 = $this->calculaIR($this->decimoTerceiro, $this->inssDecimo);
        $this->totalDescontosDecimo     = $this->irDecimo + $this->inssDecimo;

        if ($this->ferias_ven){
            $this->feriasVencidas = $this->salario;
            $this->feriasVencidas += $this->feriasVencidas/3;
        }

        $this->feriasProporcionais = $this->calculaFerias($this->dt_inicio, $this->dt_fim, $this->salario);
        return true;
    }

    public function calculaINSS(float $salario){
        $inss[0][0] = 1659.38;
        $inss[0][1] = 0.08;
        $inss[1][0] = 2765.66;
        $inss[1][1] = 0.09;
        $inss[2][0] = 5531.31;
        $inss[2][1] = 0.11;

        $valorInss = 0;
        #if ($salario <= 4663.75){ //Em alguns sites, informam que se o valor do salário for maior do esse valor, aplica o teto máximo
        if ($salario <= $inss[2][0]){
            foreach ($inss as $r){
                if ($salario <= $r[0] ){
                    $valorInss = ($salario*$r[1]);
                    $this->aliquotaINSS = $r[1];
                    break;
                }
            }
        }
        else
            $valorInss = 608.44;
        return $valorInss;
    }

    public function calculaIR(float $salario, float $inss){
        $ir[0][0] = 1903.98;
        $ir[0][1] = 0;
        $ir[0][2] = 0;

        $ir[1][0] = 2826.65;
        $ir[1][1] = 7.5;
        $ir[1][2] = 142.80;

        $ir[2][0] = 3751.05;
        $ir[2][1] = 15;
        $ir[2][2] = 354.80;

        $ir[3][0] = 4664.68;
        $ir[3][1] = 22.5;
        $ir[3][2] = 636.13;
        #acima = 27.5 869,36


        #TODO
        $valorIr = 0;
        $salario = ($salario - $inss);
        if ($salario <= 4664.68) {
            foreach ($ir as $key => $i){
                if ($salario <= $i[0] ){
                    $valorIr = ($salario * ($i[1] / 100)) - $i[2];
                    $this->faixaIR = $i[1];
                    break;
                }
            }
        }
        else{
            $valorIr = (($salario) * 0.275) - 869.36;
        }
        return $valorIr;
    }

    public function decimoTerceiro($mesSalarioPago, $salario){
      return ($salario / 12) * $mesSalarioPago;
    }

    public function calculaAviso(){
        $date1 = new DateTime(date('d-m-Y', strtotime($this->dt_inicio)));
        $date2 = new DateTime(date('d-m-Y', strtotime($this->dt_fim)));
        $interval = $date2->diff($date1);
        $diasAviso = 30;
        if (($this->motivo != 1) && ($interval->y > 0)){ //Motivo 1 => Pedido de demissão. O aviso é sempre de 30 dias
            $diasAviso += ($interval->y * 3);
        }
        $this->diasAviso = $diasAviso;
        $valorAviso = ($this->salario)/30 * $diasAviso;
        
        $this->valorAviso = $valorAviso;
    }

    public function step($msg){
        echo '<hr />';
        echo $msg;
    }

    public function pedidoDeDemissao(){
        $this->calculaSalario();
        $this->calculaDecimo();
        $this->calculaFerias();
        if ($this->ferias_ven){
            $this->calculaFeriasVencidas();
        }
        $this->calculaAviso();
        $this->calculaTotalAReceber();
    }

    public function demissaoSemJustaCausa(){
        $this->calculaAviso();
        $this->calculaSalario();
        $this->calculaDecimo();
        $this->calculaFerias();
        if ($this->ferias_ven){
            $this->calculaFeriasVencidas();
        }
        $this->calculaTotalAReceber();
    }

    public function demissaoComJustaCausa(){
        $this->calculaAviso();
        $this->calculaSalario();
        $this->calculaDecimo();
        $this->calculaFerias();
        if ($this->ferias_ven){
            $this->calculaFeriasVencidas();
        }
        $this->calculaTotalAReceber();
    }

    public function terminoDoContratoDeExperiencia(){
        $this->calculaAviso();
        $this->calculaSalario();
        $this->calculaDecimo();
        $this->calculaFerias();
        $this->calculaTotalAReceber();
    }

    public function terminoDoContratoDeExperienciaAntecipado(){
        $this->calculaAviso();
        $this->calculaSalario();
        $this->calculaDecimo();
        if ($this->motivo != 7 && $this->ferias_ven) {
            $this->calculaSaldoTerminoContrato();
            $this->calculaFeriasVencidas();
        }
        $this->calculaTotalAReceberRescisaoAntecipada();
    }

    public function saldoDeSalario(){
        $diasTrabalhados = date('d', strtotime($this->dt_fim));
        if ($diasTrabalhados > 30){
            $diasTrabalhados = 30;  //Igual a 30 pois a base de cálculo é feita com 30, independente do mês
        }
        $saldo = $this->salario;
        $saldo = ($saldo/30) * $diasTrabalhados;
        $this->diasTrabalhados = $diasTrabalhados;
        return $saldo;
    }

    public function calculaSalario(){
        $this->saldoDeSalario           = $this->saldoDeSalario();
        if ($this->aviso == 'indenizado'){
            if ($this->motivo == 2){
                $this->saldoDeSalarioComAviso   = $this->saldoDeSalario + $this->valorAviso;
                $this->inss                     = $this->calculaINSS($this->saldoDeSalario) + $this->calculaINSS($this->valorAviso);
                $this->ir                       = $this->calculaIR($this->saldoDeSalario, $this->calculaINSS($this->saldoDeSalario));
                $this->totalDescontosSalario    = $this->inss + $this->ir;
                $this->totalSalario             = $this->saldoDeSalarioComAviso - $this->totalDescontosSalario;
            }
            else{
                $this->inss                     = $this->calculaINSS($this->saldoDeSalario);
                $this->ir                       = $this->calculaIR($this->saldoDeSalario, $this->inss);
                $this->totalDescontosSalario    = $this->inss + $this->ir;
                $this->totalSalario             = $this->saldoDeSalario - $this->totalDescontosSalario;    
            }
        }
        else{
            $this->inss                     = $this->calculaINSS($this->saldoDeSalario);
            $this->ir                       = $this->calculaIR($this->saldoDeSalario, $this->inss);
            $this->totalDescontosSalario    = $this->inss + $this->ir;
            $this->totalSalario             = $this->saldoDeSalario - $this->totalDescontosSalario;
        }
    }

    public function calculaDecimo(){
        if ($this->motivo == 3){
            $this->decimoTerceiro           = 0;
            $this->inssDecimo               = 0;
            $this->irDecimo                 = 0;
            $this->totalDescontosDecimo     = 0;
            $this->totalDecimo              = 0;
        }
        else{
            $mes = date('m', strtotime($this->dt_fim));

            $date1 = new DateTime(date('d-m-Y', strtotime($this->dt_inicio)));
            $date2 = new DateTime(date('d-m-Y', strtotime($this->dt_fim)));
            $interval = $date1->diff($date2);

            if ($interval->y < 1)
                $mes = $interval->m;
            
            if ($this->diasTrabalhados < 15)
                $mes--;
            $decimoProporcional = ($this->salario/12) * ($mes);
            
            $this->decimoTerceiroIndenizado = 0;
            $totalLiquido = $this->totalSalario;
            
            $this->decimoTerceiro           = $decimoProporcional;
            $this->inssDecimo               = $this->calculaINSS($this->decimoTerceiro);
            $this->irDecimo                 = $this->calculaIR($this->decimoTerceiro, $this->inssDecimo);
            $this->totalDescontosDecimo     = $this->irDecimo + $this->inssDecimo;
            $this->totalDecimo              = $this->decimoTerceiro - $this->totalDescontosDecimo;
            
            if ($this->aviso == 'indenizado'){
                $valorAviso = $this->valorAviso;
                if ($this->motivo == 1){
                $this->totalVencimentos -= $valorAviso;
                }
                elseif ($this->motivo == 2){
                $this->totalVencimentos          += $valorAviso;
                $this->decimoTerceiroIndenizado  = $this->salario/12;
                $this->totalDecimo               += $this->decimoTerceiroIndenizado;
                }
            }
        }
    }

    public function calculaFerias(){
        if ($this->motivo == 3){
            $this->feriasProporcionais          = 0;
            $this->tercoFeriasProporcionais     = 0;
            $this->inssFeriasProporcionais      = 0;
            $this->irFeriasProporcionais        = 0;
            $this->totalDescontosFerias         = 0;
            $this->totalFerias                  = 0;
            $this->totalFeriasFinal             = 0;
        }
        else{
            $dt_fim     = strtotime($this->dt_fim);
            $dtAux      = strtotime($this->dt_inicio);
            #$dtAux      = strtotime($this->dt_inicio . ' +1 year'); #TODO REMOVER SE FUNCIONAR

            $date1 = new DateTime(date('d-m-Y', $dtAux));
            $date2 = new DateTime(date('d-m-Y', $dt_fim));
            $interval = $date1->diff($date2);
            $mesesTrabalhados = $interval->m;

            if (($interval->y > 1) && ($this->diasTrabalhados >= 15))
                $mesesTrabalhados++;

            if ($interval->y == 1 && 0 == $mesesTrabalhados)
                $mesesTrabalhados = 12;

            if ($mesesTrabalhados == 0)
                $mesesTrabalhados = 0;

            $feriasProporcionais                = ($this->salario /12) * $mesesTrabalhados;
            $this->feriasProporcionais          = $feriasProporcionais;
            $this->tercoFeriasProporcionais     = $feriasProporcionais /3;
            // $this->inssFeriasProporcionais      = $this->calculaINSS($feriasProporcionais + $this->tercoFeriasProporcionais);
            // $this->irFeriasProporcionais        = $this->calculaIR($feriasProporcionais, $this->inssFeriasProporcionais);
            $this->inssFeriasProporcionais      = 0; //Numa rescisão, não é descontado o valor de INSS e IRPF das férias
            $this->irFeriasProporcionais        = 0;
            $this->totalDescontosFerias         = $this->inssFeriasProporcionais + $this->irFeriasProporcionais;
            $this->totalFerias                  = $this->feriasProporcionais + $this->tercoFeriasProporcionais;
            $this->totalFeriasFinal             = $this->totalFerias - $this->totalDescontosFerias;

            if ($this->aviso == 'indenizado'){
                if ($this->motivo == 2){
                    $this->feriasIndenizadas        = $this->salario / 12;
                    $this->tercoFeriasIndenizadas   = $this->feriasIndenizadas / 3;
                    $this->totalFerias              += $this->feriasIndenizadas + $this->tercoFeriasIndenizadas;
                    $this->totalFeriasFinal         += $this->feriasIndenizadas + $this->tercoFeriasIndenizadas;

                    // $this->totalVencimentos += $this->feriasIndenizadas + $this->tercoFeriasIndenizadas;
                }
            }
        }
    }

    public function calculaFeriasVencidas(){
        $this->feriasVencidas       = $this->salario;
        $this->tercoFeriasVencidas  = $this->salario / 3;
        $this->totalFerias          += $this->feriasVencidas + $this->tercoFeriasVencidas;
        $this->totalFeriasFinal     = $this->totalFerias - $this->totalDescontosFerias;
    }

    public function calculaTotalAReceberRescisaoAntecipada() {
        if (in_array($this->motivo, [5])) {
            $this->totalVencimentos = $this->saldoDeSalario + $this->totalFerias + $this->decimoTerceiro + $this->decimoTerceiroIndenizado + $this->valorPrazoRemanescente;
            $this->totalDescontosFinal = $this->totalDescontosSalario + $this->totalDescontosDecimo + $this->totalDescontosFerias + $this->totalDescontosPrazoRemanescente;            
        } elseif(in_array($this->motivo, [6])) {
            $this->totalVencimentos = $this->saldoDeSalario + $this->decimoTerceiro;
            $this->totalDescontosFinal = $this->totalDescontosSalario + $this->totalDescontosDecimo + $this->valorPrazoRemanescente;
        } elseif(in_array($this->motivo, [7])) {
            $this->totalVencimentos = $this->saldoDeSalario + $this->decimoTerceiro;
            $this->totalDescontosFinal = $this->totalDescontosSalario + $this->totalDescontosDecimo;
        }

        $this->totalFinal = $this->totalVencimentos - $this->totalDescontosFinal;
    }

    public function calculaTotalAReceber(){
        $this->totalVencimentos = 0;
        
        if ($this->aviso == 'indenizado'){
            if ($this->motivo == 2){
                $this->totalVencimentos += $this->saldoDeSalarioComAviso;
            }
            else{
                $this->totalVencimentos += $this->saldoDeSalario;
            }
        }
        else
            $this->totalVencimentos += $this->saldoDeSalario;

        $this->totalVencimentos += $this->totalFerias + $this->decimoTerceiro + $this->decimoTerceiroIndenizado;
        $this->totalFinal = $this->saldoDeSalario + $this->totalFerias + $this->decimoTerceiro + $this->decimoTerceiroIndenizado
                            - $this->totalDescontosSalario  - $this->totalDescontosDecimo - $this->totalDescontosFerias;
        $this->totalDescontosFinal = $this->totalDescontosSalario + $this->totalDescontosDecimo + $this->totalDescontosFerias;
        if (($this->motivo == 1) && ($this->aviso == 'indenizado')){
            $this->totalDescontosFinal += $this->salario;
            $this->totalFinal -= $this->valorAviso;
        }
        if ($this->aviso == 'indenizado'){
            if ($this->motivo == 2){
                $this->totalFinal += $this->valorAviso;
            }
        }
    }

    public function calculaSalarioLiquido(){
        $salarioBase = $this->salario;
        $this->inss  = $this->calculaINSS($salarioBase);
        
        if ($this->dependentes > 0){
            $deducoes = $this->dependentes * $this->deducaoPorDependente;
            $salarioBase -= $deducoes;
        }
        
        $this->ir                       = $this->calculaIR($salarioBase, $this->inss);
        $this->totalDescontosSalario    = $this->inss + $this->ir;
        $this->totalSalario             = $this->salario - $this->totalDescontosSalario;

        if ($this->outrasDeducoes > 0){
            $this->totalSalario -= $this->outrasDeducoes;
        }
    }

    public function calculaSaldoTerminoContrato() {
        $dt_inicio = strtotime($this->dt_fim_contrato);
        $date1 = new DateTime(date('d-m-Y', $dt_inicio));

        $dt_fim = strtotime($this->dt_fim);
        $date2 = new DateTime(date('d-m-Y', $dt_fim));

        $interval = $date1->diff($date2);
        $mesesTrabalhados = $interval->m;

        if ($interval->y > 1 && $this->diasTrabalhados >= 15) $mesesTrabalhados++;

        if ($interval->y == 1 && 0 == $mesesTrabalhados) $mesesTrabalhados = 12; // @TODO

        if ($interval->m == 0) $mesesTrabalhados = 0;

        $this->prazoRemanescente = $interval->days;
        $this->valorPrazoRemanescente = (($this->salario / 31) * (int) $interval->days) / 2;
        $this->totalDescontosPrazoRemanescente = $this->irPrazoRemanescente + $this->inssPrazoRemanescente;
        $this->liquidoPrazoRemanescente = $this->valorPrazoRemanescente + $this->totalDescontosPrazoRemanescente;
    }

    public function calculaHoraExtra() {
        // calc extra hour value, by base and monthly journey
        $salarioBase = (float)$this->salario;
        $this->valorHoraExtra = $salarioBase / $this->jornadaMensal;

        // calc normal extra hours
        $valorHorasNormal = $this->valorHoraExtra * $this->qtdHorasNormal;
        $this->valorTotalHoraNormal = $valorHorasNormal * Calculadoras::$extra50;

        // calc special extra hours
        $valorHoras100 = $this->valorHoraExtra * $this->qtdHoras100;
        $this->valorTotalHora100 = $valorHoras100 * Calculadoras::$extra100;

        // accumulate extra hours with base
        $this->subTotalExtra = $this->valorTotalHoraNormal + $this->valorTotalHora100;
        $subTotal = $salarioBase + $this->subTotalExtra;

        // calc and apply tributes on total yield
        $this->inss = $this->calculaINSS($subTotal);

        $this->ir = $this->calculaIR($subTotal, $this->inss);
        $this->totalDescontosHora = $this->inss + $this->ir;

        // liquid result
        $this->totalLiquido = $subTotal - $this->totalDescontosHora;
    }

    public function formataCamposMonetarios() {
        $campos = ['valorPrazoRemanescente', 'irPrazoRemanescente', 'inssPrazoRemanescente', 'totalDescontosPrazoRemanescente', 'liquidoPrazoRemanescente', 'salarioLiquido', 'subTotalExtra', 'valorTotalHoraNormal', 'valorTotalHora100', 'totalExtra', 'totalLiquido', 'salario','valorAviso','saldoDeSalario','inss','ir','decimoTerceiro','inssDecimo','irDecimo','feriasProporcionais','tercoFeriasProporcionais',
                    'feriasVencidas','tercoFeriasVencidas','inssFeriasProporcionais','irFeriasProporcionais','saldoSalario','totalSalario','totalVencimentos','totalDescontosSalario',
                    'totalDecimo','totalDescontosDecimo','totalFerias','totalFeriasFinal','totalDescontosFerias','totalFinal', 'totalDescontosFinal', 'outrasDeducoes',
                    'feriasIndenizadas', 'tercoFeriasIndenizadas', 'decimoTerceiroIndenizado'];
        foreach ($campos as $campo) {
            $this->$campo = Calculadoras::dinheiro($this->$campo);
        }
    }

    public function teste(){
        $date1 = new DateTime(date('d-m-Y', strtotime($this->dt_inicio)));
        $date2 = new DateTime(date('d-m-Y', strtotime($this->dt_fim)));
        $this->interval = $date1->diff($date2);
        if ($this->interval->y < 1){
            if ($this->ferias_ven)
                $this->warning    = '*Se o período trabalhado for menor que 1 ano, o funcionário não pode ter férias vencidas.';
            $this->ferias_ven = false;
        }
        if ($this->motivo == 1)
            $this->pedidoDeDemissao();
        elseif ($this->motivo == 2)
            $this->demissaoSemJustaCausa();
        elseif ($this->motivo == 3)
            $this->demissaoComJustaCausa();
        elseif ($this->motivo == 4)
            $this->terminoDoContratoDeExperiencia();
        elseif (in_array($this->motivo, [5,6,7]))
            $this->terminoDoContratoDeExperienciaAntecipado();
    }
}

?>
