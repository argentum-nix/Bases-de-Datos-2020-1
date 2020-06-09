import cx_Oracle
import pandas
from tabulate import tabulate

def print_table(hdrs, fmt='psql'):
	res = cur.fetchall()
	print(tabulate(res, headers=hdrs, tablefmt=fmt))

#=========================================================================
# CONEXION AL SERVIDOR (nota: se ocupa ORACLE_CX y no ODBC por problemas de
# sistema y falta maquian que cumpla requisitos y tenga sistema WIN instalado.)

# connection string de tipo ("id","pwd","db")
connection = cx_Oracle.connect("argentum", "1234", "XE")
cur = connection.cursor()

#=========================================================================

#CREACION DE POYO
#quito espacios y no agrego _ por convencion (_ para relaciones y no atributos)
cur.execute("DROP TABLE poyo")
cur.execute("CREATE TABLE poyo (\
            pokedex INT,\
            nombre VARCHAR(40) NOT NULL PRIMARY KEY,\
            type1 VARCHAR(20),\
            type2 VARCHAR(20),\
            hptotal INT,\
            legendary NUMBER(1))"
            )
connection.commit()
# uso NUMBER(1) en vez de BOOLEAN para representar los booleanos
# uso usecols que guarda solo algunas columnas
pkmn = pandas.read_csv('pokemon.csv',sep=",",usecols=(0, 1, 2, 3 , 4, 12))
# leer fila por fila para pillar True/False y NaN


add_row_pkmn = ("INSERT INTO poyo "
           "(pokedex, nombre, type1, type2, hptotal, legendary) "
           "VALUES (:1,:2,:3,:4,:5,:6)")
for d in pkmn.values:
    t2 = d[3]
    bln = 0
    if not isinstance(t2, str):
        #caso nan
        t2 = ""
    if d[-1] == True:
        bln = 1
    row_pkmn = [int(d[0]), d[1], d[2], t2, int(d[4]), bln]
    cur.execute(add_row_pkmn, row_pkmn)
    connection.commit()


# IMPRIME LA poyo COMO TABLA CON FORMATO psql
cur.execute("SELECT * FROM poyo")
connection.commit()
hdrs_poyo = ['pokedex', 'nombre', 'type1', 'type2', 'hptotal', 'legendary']
print_table(hdrs_poyo)


#=========================================================================
#CREACION DE SANSANITO POKEMON
cur.execute("DROP TABLE sansanito")
cur.execute("CREATE TABLE sansanito (\
			id INT NOT NULL PRIMARY KEY,\
			pokedex INT,\
			nombre VARCHAR(40),\
			type1 VARCHAR(20),\
            type2 VARCHAR(20),\
          	hpactual INT,\
          	hpmax INT,\
          	legendary  NUMBER(1),\
          	estado VARCHAR(30),\
          	ingreso DATE,\
          	prioridad INT)"
          	)
connection.commit()

# IMPRIME LA sansanito COMO TABLA CON FORMATO psql
cur.execute("SELECT * FROM sansanito")
connection.commit()
res = cur.fetchall()
hdrs_sansanito = ['id', 'pokedex', 'type1',\
				'type2', 'hpactual', 'hptotal',\
				'legendary', 'ingreso', 'prioridad']
#=========================================================================

#CRUD
#CREATE - hace insercion de registro
#READ - lee registros con PK u otro parametro
#UPDATE - cambia el registro usando su PK con un WHERE
#DELETE - borra la fila con WHERE especifico


#Query
# Los 10 pokemon con mayor prioridad
def maxprio_sansanito():
	maxprio10 = "SELECT nombre, prioridad\
				FROM sansanito\
				ORDER BY prioridad DESC\
				LIMIT 10" 
	cur.execute(maxprio10)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

# Los 10 pokemon con menor prioridad
def minprio_sansanito():
	minprio10 = "SELECT nombre, prioridad\
				FROM sansanito\
				ORDER BY prioridad ASC\
				LIMIT 10"
	cur.execute(minprio10)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

#los de estado especifico
def estado_sansanito(estado):
	select_estado = "SELECT nombre\
					FROM sansanito\
					WHERE estado={}".format(estado)
	cur.execute(select_estado)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

#los legendarios
def legendarios_sansanito():
	select_legend = "SELECT nombre\
					FROM sansanito\
					WHERE legendary=1"
	cur.execute(select_legend)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

#el mas antiguo
def antiguedad_sansanito():
	select_antiguo = "SELECT nombre\
					FROM sansanito\
					ORDER BY ingreso DESC\
					LIMIT 1"
	cur.execute(select_antiguo)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

'''
SELECT       `column`,

             COUNT(`column`) AS `value_occurrence` 

    FROM     `my_table`

    GROUP BY `column`

    ORDER BY `value_occurrence` DESC

    LIMIT    1;
    '''

#el mas repetido
def repetido_sansanito():
	select_repeat = "SELECT nombre\
					COUNT(nombre) AS repetido_sansanito\
					FROM sansanito\
					ORDER BY repetido_sansanito DESC\
					LIMIT 1"
	cur.execute(select_repetido)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

def ordenado_sansanito():
	select_orderby = "SELECT nombre, hpactual, hpmax, prioridad\
					FROM sansanito\
					ORDER BY prioridad DESC"
	cur.execute(select_orderby)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

connection.close()
