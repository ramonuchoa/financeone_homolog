<div class="coluna-box">
    <p class="subtitulo-box">Calcula os valores da rescisão do contrato de trabalho de um empregado.</p>
    <h3>Salários</h3>
    <p>
        Período trabalhado: {dtAdmissao} até {dtDemissao}<br /><br />
        Saldo de salário ({diasTrabalhados}/30): {saldoDeSalario} <br />
        {linhaAvisoIndenizado}
        INSS: {inss} <br />
        IRPF: {ir} <br />
        Total de descontos sobre salário: {totalDescontosSalario} <br />
        <strong>Total a receber de salário: {totalSalario}</strong> <br />
    </p>
    <p>

    </p>
    <p>
        {linhaDecimo}
        INSS 13º: {inssDecimo} <br />
        IRPF 13º: {irDecimo} <br />
        Total de descontos sobre 13º: {totalDescontosDecimo}<br />
        {linhaDecimoTotal}
    </p>
    <p>
        {ferias}
    </P>
    <p>
        {linhaVencimentos}
        {linhaDescontos}
        <strong>Total Líquido: {totalLiquido}</strong><br /><br />
        {warning}
    </P>
    <p><a onClick="history.go(-1);return true;" style="cursor:pointer">Voltar</a></p>
</div>