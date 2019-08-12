import urllib3
import MySQLdb
import time
from contextlib import closing
from bs4 import BeautifulSoup


query_dolar_com = ""
query_dolar_tur = ""
query_dolar_ptax = ""


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

cotacao_dolar = { "Comercial" : query_dolar_com, "Turismo" : query_dolar_tur, "Ptax": query_dolar_ptax }
#cotacao_euro = { "Comercial" : query_dolar, "Turismo", "Ptax" }


for row in dados_tabela:
    tds = row('td')

    for i in tds:
        if i.string != None:
            html_tags_text.append(i.string)


for content in html_tags_text:
    if content == "Dólar Comercial": 
       compra = round(float(html_tags_text[1].replace(",",".")),3)
       venda = round(float(html_tags_text[2].replace(",",".")),3)
       variacao = html_tags_text[3].strip("%")
       moeda = content.replace("Dólar","") 
       moeda = moeda.strip()
       query_dolar_com = "INSERT INTO dados_dolar (data,compra,venda,var,tipo) VALUES ('%s', '%s','%s','%s','%s')"  % (data_captura, compra, venda, variacao, moeda) 
       cotacao_dolar.update({"Comercial" : query_dolar_com})

    if content == "Dólar Ptax - Bacen": 
       compra = round(float(html_tags_text[5].replace(",",".")),3)
       venda = round(float(html_tags_text[6].replace(",",".")),3)       
       variacao = html_tags_text[7].strip("%")
       moeda = content.replace("Dólar","") 
       moeda = moeda.replace("- Bacen","") 
       moeda = moeda.strip()
       query_dolar_ptax = "INSERT INTO dados_dolar (data,compra,venda,var,tipo) VALUES ('%s', '%s','%s','%s','%s')"  % (data_captura, compra, venda, variacao, moeda) 
       cotacao_dolar.update({"Ptax" : query_dolar_ptax})

    if content == "Dólar Turismo": 
       compra = round(float(html_tags_text[9].replace(",",".")),3)
       venda = round(float(html_tags_text[10].replace(",",".")),3)
       variacao = html_tags_text[11].strip("%")
       moeda = content.replace("Dólar","") 
       moeda = moeda.strip()
       query_dolar_tur = "INSERT INTO dados_dolar (data,compra,venda,var,tipo) VALUES ('%s', '%s','%s','%s','%s')"  % (data_captura, compra, venda, variacao, moeda) 
       cotacao_dolar.update({"Turismo" : query_dolar_tur})
      

with closing(cursor) as cur:
    cursor.execute(cotacao_dolar['Comercial'])
    cursor.execute(cotacao_dolar['Ptax'])
    cursor.execute(cotacao_dolar['Turismo'])
    conn.commit()

conn.close()

