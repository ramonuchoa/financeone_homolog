<!--[if IE]><script language="javascript" type="text/javascript" src="{src}/js/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="{src}/js/jquery.flot.min.js"></script>

<script id="source" language="javascript" type="text/javascript">
        jQuery(document).ready(function($) {
                function grafico(dados, el) {
                        var g = new Array;

                        options = {
                                xaxis: { ticks: [] },
                                yaxis: { ticks: [] },
                                grid:  { backgroundColor: '#fab53055', borderColor: '#fab53055' }
                        };

                        for (i=0; i<dados.length;i++) {
                                g[i] = [i+1, dados[i]];
                        }

                        jQuery.plot(jQuery(el), [{ color: '#10284a', data : g }], options);
                }

                grafico([{grafico7dados}], '#grafico-7');
                grafico([{grafico30dados}], '#grafico-30');
                grafico([{grafico100dados}], '#grafico-100');
                grafico([{grafico365dados}], '#grafico-365');

                $('input[name="dia"], input[name="mes"], input[name="ano"], input[name="dia_de"], input[name="mes_de"], input[name="ano_de"], input[name="dia_ate"], input[name="mes_ate"], input[name="ano_ate"]').keyup(function() {
                        var s = new String($(this).val());
                        s = s.replace(/\D/g,'');
                        $(this).val(s);
                });
        });
</script>

<h2>Últimas Cotações</h2>
<table class='rtable'>
        <tr>
                <th>&nbsp;</th>
                <th>Variação</th>
                <th>Compra</th>
                <th>Venda</th>
        </tr>
        {dolar}
</table>
<p>{data_dolar}</p>

<h2>Histórico do Dólar</h2>
{box}

<h2>Gráficos do Dólar</h2>
<section class='charts'>
        <div>
                <h4>Últimos 7 dias</h4>
                <div id="grafico-7" class='chart'></div>
                <p>Alta: {grafico7alta}</p>
                <p>Baixa: {grafico7baixa}</p>
        </div>

        <div>
                <h4>Últimos 30 dias</h4>
                <div id="grafico-30" class='chart'></div>
                <p>Alta: {grafico30alta}</p>
                <p>Baixa: {grafico30baixa}</p>
        </div>

        <div>
                <h4>Últimos 100 dias</h4>
                <div id="grafico-100" class='chart'></div>
                <p>Alta: {grafico100alta}</p>
                <p>Baixa: {grafico100baixa}</p>
        </div>

        <div>
                <h4>Últimos 365 dias</h4>
                <div id="grafico-365" class='chart'></div>
                <p>Alta: {grafico365alta}</p>
                <p>Baixa: {grafico365baixa}</p>
        </div>
</section>
