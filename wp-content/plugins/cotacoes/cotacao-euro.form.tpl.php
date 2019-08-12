<div class="box-informativo" style="font-size: 14px;">
        <p class="left">&Uacute;ltima cota&ccedil;&atilde;o em {data_euro}:</p>

        <div style="clear:both">
                <p class="left">Compra: R$ {euro_compra}</p>
                <p class="right">Venda: R$ {euro_venda}</p>
        </div>          
</div>
                        
<div class="row">               
        <div class="col-md-12"> 
                <h2>Hist&oacute;rico e cota&ccedil;&otilde;es do euro</h2>
        </div>  
</div>  

<div class="row">
        <div class="col-md-6">
                <form class="FrmHistorico" action="{action}" method="post" accept-charset="utf-8">
                        <div class="form-group">
                                <label>Cota&ccedil;&atilde;o por data:</label>
                        </div>
                        
                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="dia" maxlength="2" placeholder="Dia" />
                        </div>
                        
                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="mes" maxlength="2" placeholder="M&ecirc;s" />
                        </div>
                        
                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="ano" maxlength="4" placeholder="Ano" />
                        </div>
                        
                        <div class="col-xs-2">
                                <button type="submit" class="btn btn-default submit right">Verificar cota&ccedil;&atilde;o</button>
                                <input type="hidden" name="cotacao" value="data" />
                        </div>
                </form>         
        </div>

        <div class="col-md-6">
                <form class="FrmHistorico" action="{action}" method="post" accept-charset="utf-8">
                        <div class="form-group">
                                <label>Cota&ccedil;&atilde;o por per&iacute;odo:</label>
                        </div>
                                
                        <div class="col-xs-1">
                                <label>De:</label>
                        </div>
                                
                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="dia_de" maxlength="2" placeholder="Dia" />
                        </div>

                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="mes_de" maxlength="2" placeholder="M&ecirc;s" />
                        </div>  
                        
                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="ano_de" maxlength="4" placeholder="Ano" />
                        </div>

                        <br><br><br>

                        <div class="col-xs-1">
                                <label>At&eacute;:</label>
                        </div>

                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="dia_ate" maxlength="2" placeholder="Dia" />
                        </div>

                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="mes_ate" maxlength="2" placeholder="M&ecirc;s" />
                        </div>

                        <div class="col-xs-2">
                                <input class="form-control" type="text" name="ano_ate" maxlength="4" placeholder="Ano" />
                        </div>

                        <br><br><br>

                        <div class="col-xs-2">
                                <input type="hidden" name="cotacao" value="periodo" />
                                <button type="submit" class="btn btn-default submit periodo">Verificar cota&ccedil;&atilde;o</button>
                        </div>
                </form>
        </div>
</div>
