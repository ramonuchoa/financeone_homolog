import urllib3
import MySQLdb
import time
from contextlib import closing
from bs4 import BeautifulSoup


query_cdi = ""
query_selic = ""
query_poupanca_ant = ""
query_poupanca = ""


http = urllib3.PoolManager()
req = http.request("GET", "http://valor.com.br/valor-data")

bs = BeautifulSoup(req.data,"html.parser")

conn = MySQLdb.connect(host="159.203.119.248", user="root", passwd="db_f1n4nc30n3", db="finance_one")
cursor = conn.cursor() 

#html_tags = bs.find_all("div", class_="item")

html_tags_text = []

data_captura = time.strftime('%Y-%m-%d %H:%M:%S')

#parser table
dados_tabela = bs.find_all("div", id="block-valor_data-tabela_aplicacao")

taxas = { "CDI" : query_cdi, "Poupança Antiga" : query_poupanca_ant, "Poupança": query_poupanca, "Selic" : query_selic }


for row in dados_tabela:
    tds = row('td')

    for i in tds:
        if i.string != None:
            html_tags_text.append(i.string)


for content in html_tags_text:

    content = content.strip()
    if content == "Selic over, ao ano":
        taxa = html_tags_text[1]
        taxa = taxa.strip()
        query_selic = "INSERT INTO indicadores_economicos (data,fechamento,descricao,indicador) VALUES ('%s','%s','%s','%s')" % (data_captura, taxa, "Selic over, ao ano", "SELICMETA")
        taxas.update({"Selic" : query_selic})

    if content == "CDI over Cetip, ao ano":        
        taxa = html_tags_text[3]
        taxa = taxa.strip()
        query_cdi = "INSERT INTO indicadores_economicos (data,fechamento,descricao,indicador) VALUES ('%s','%s','%s','%s')" % (data_captura, taxa, "CDI ao ANO", "CDI ANO")
        taxas.update({"CDI" : query_cdi})


    poupanca_antiga = "Poupança antiga (" + time.strftime('%d/%m') + ")"    
    if content == poupanca_antiga:        
        taxa = html_tags_text[9]
        taxa = taxa.strip()
        query_poupanca_antiga = "INSERT INTO indicadores_economicos (data,fechamento,descricao,indicador) VALUES ('%s','%s','%s','%s')" % (data_captura, taxa, "Poupança antiga" , "POUPANCA ANTIGA")
        taxas.update({"Poupança Antiga" : query_poupanca_antiga})

    poupanca_nova = "Poupança nova (" + time.strftime('%d/%m') + ")"    
    if content == poupanca_nova:        
        taxa = html_tags_text[11]
        taxa = taxa.strip()
        query_poupanca = "INSERT INTO indicadores_economicos (data,fechamento,descricao,indicador) VALUES ('%s','%s','%s','%s')" % (data_captura, taxa, "Poupança nova" ,"POUPANCA NOVA")
        taxas.update({"Poupança" : query_poupanca})
 

with closing(cursor) as cur:
    cursor.execute(taxas['Selic'])
    cursor.execute(taxas['Poupança Antiga'])
    cursor.execute(taxas['Poupança'])
    cursor.execute(taxas['CDI'])
    conn.commit()

conn.close()

