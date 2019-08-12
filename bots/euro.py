import urllib3
import MySQLdb
import time
from contextlib import closing
from bs4 import BeautifulSoup


query_euro_com = ""
query_euro_tur = ""
query_euro_x_dolar = ""


http = urllib3.PoolManager()
req = http.request("GET", "http://valor.com.br/")

bs = BeautifulSoup(req.data,"html.parser")

conn = MySQLdb.connect(host="159.203.119.248", user="root", passwd="db_f1n4nc30n3", db="finance_one")
cursor = conn.cursor() 

#html_tags = bs.find_all("div", class_="item")

html_tags_text = []

data_captura = time.strftime('%Y-%m-%d %H:%M:%S')

#parser table
dados_tabela = bs.find_all("table", class_="table-cotacao-moeda")

cotacao_euro = { "Comercial" : query_euro_com, "Turismo" : query_euro_tur, "Euro x Dólar": query_euro_x_dolar }
#cotacao_euro = { "Comercial" : query_euro, "Turismo", "x Dólar" }


for row in dados_tabela:
    tds = row('td')

    for i in tds:
        if i.string != None:
            html_tags_text.append(i.string)

for content in html_tags_text:
    if content == "Euro Comercial": 
       compra = round(float(html_tags_text[1].replace(",",".")),3)
       venda = round(float(html_tags_text[2].replace(",",".")),3)
       variacao = html_tags_text[3].strip("%")
       moeda = content.replace("Euro","") 
       moeda = moeda.strip()
       query_euro_com = "INSERT INTO dados_euro (data,compra,venda,var,tipo) VALUES ('%s', '%s','%s','%s','%s')"  % (data_captura, compra, venda, variacao, moeda) 
       cotacao_euro.update({"Comercial" : query_euro_com})

    if content == "Euro x Dólar - Bacen": 
       compra = round(float(html_tags_text[5].replace(",",".")),3)
       venda = round(float(html_tags_text[6].replace(",",".")),3)       
       variacao = html_tags_text[7].strip("%")
       moeda = content.replace("- Bacen","") 
       moeda = moeda.strip()
       query_euro_x_dolar = "INSERT INTO dados_euro (data,compra,venda,var,tipo) VALUES ('%s', '%s','%s','%s','%s')"  % (data_captura, compra, venda, variacao, moeda) 
       cotacao_euro.update({"Euro x Dólar" : query_euro_x_dolar})

    if content == "Euro Turismo": 
       compra = round(float(html_tags_text[9].replace(",",".")),3)
       venda = round(float(html_tags_text[10].replace(",",".")),3)
       variacao = html_tags_text[11].strip("%")
       moeda = content.replace("Euro","") 
       moeda = moeda.strip()
       query_euro_tur = "INSERT INTO dados_euro (data,compra,venda,var,tipo) VALUES ('%s', '%s','%s','%s','%s')"  % (data_captura, compra, venda, variacao, moeda) 
       cotacao_euro.update({"Turismo" : query_euro_tur})



with closing(cursor) as cur:
    cursor.execute(cotacao_euro['Comercial'])
    cursor.execute(cotacao_euro['Euro x Dólar'])
    cursor.execute(cotacao_euro['Turismo'])
    conn.commit()

conn.close()

