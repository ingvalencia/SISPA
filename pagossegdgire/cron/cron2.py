import MySQLdb as db
import MySQLdb.cursors
 
DB_HOST = 'localhost' 
DB_USER = 'usr_pagos' 
DB_PASS = 'Rm45L+1Z' 
DB_NAME = 'dgire_pagos2016A'
DB_PORT = 7408

query = "select id_solicitante, folio_sol, referencia_ban, serie_fac from solicitud_pago where referencia_ban is not null and cve_edo_pago = 'FIENV'"

conn = db.Connect(host=DB_HOST, port=DB_PORT, user=DB_USER, passwd=DB_PASS, db=DB_NAME, cursorclass=MySQLdb.cursors.DictCursor)

#cursor = conn.cursor(cursorclass=db.cursors.DictCursor)
cursor = conn.cursor()         # Crear un cursor 
cursor.execute(query)          # Ejecutar una consulta 

if query.upper().startswith('SELECT'): 
    data = cursor.fetchall()   # Traer los resultados de un select 
else: 
    conn.commit()              # Hacer efectiva la escritura de datos 
    data = None 

cursor.close()                 # Cerrar el cursor 
conn.close()                   # Cerrar la conexin 

for i in data:
	print i['id_solicitante']

print query
