import cx_Oracle
import pandas
from tabulate import tabulate
import time
from simple_term_menu import TerminalMenu

'''TO DO:
INSERT no funciona, especialmente el notpoyo que inserta datos que no estan en poyo
creo que hay que insertar todo en 1 sola secuencia, asi que no puede haber 2 funciones
hay problema con el trigger para el id, no funciona el self-increment
falta el delete, read, update, create
create debe ser una opcion en menu de terminal (pero es la misma wea)
poblar la tabla una vez que funcione el insert'''

def print_table(hdrs, fmt='psql'):
	res = cur.fetchall()
	print(tabulate(res, headers=hdrs, tablefmt=fmt))

def print_poyo():
	poyo = "SELECT * FROM poyo"
	cur.execute(poyo)
	print_table(hdrs_poyo)

def print_sansanito():
	ssn = "SELECT * FROM sansanito"
	cur.execute(ssn)
	print_table(hdrs_sansanito)

#CRUD
#CREATE - hace insercion de registro
def create():
	print("hola")

#READ - lee registros con PK u otro parametro
def read():
	# leer todo
	# leer que columnas
	# leer de que tabla
	# ordenar por cual valor
	# de que modo ordeno
	print("hola")
	
#UPDATE - cambia el registro usando su PK con un WHERE
def update():
	# solo modifica hpactual, estado
	# de hecho tendre que preguntar si con eso se modifica la Prio pues es funcion de hp y weas
	print("hola")

#DELETE - borra la fila con WHERE especifico
def delete(nombre):
	cur.execute("""
				DELETE FROM sansanito\
				WHERE nombre='%s'""" % (nombre)
				)
	
#Query

#sin terminar
#https://www.w3schools.com/sql/sql_insert_into_select.asp
def insert_notpoyo(n, actual, e, f, prio):
	cur.execute("""
				INSERT INTO sansanito (hpactual, estado, prioridad)
				VALUES (%d, '%s', %d)""" 
				% (actual, e, prio)
				)
#convert(DATETIME, '%s', 5)

def insert_poyodata(n):
	cur.execute("""
				INSERT INTO sansanito (pokedex, nombre, tipo1, tipo2, hpmax, legendary)
				SELECT pokedex,  FROM poyo
				WHERE nombre='%s'""" % (n))

#hdrs_poyo = ['pokedex', 'nombre', 'type1', 'type2', 'hptotal', 'legendary']

def calculate_priority(n, hpactual, estado):
	cur.execute("""SELECT hptotal FROM poyo WHERE nombre='%s'""" % (n))
	hptotal = cur.fetchall()
	prioridad = hptotal[0][0] - hpactual + bool(estado) * 10
	#print(n, "tiene hptotal = ", hptotal[0][0], "y actual", hpactual, "con estado", estado, "y prioridad", prioridad)
	return prioridad

#se asume que nunca se ingresara hpactual mayor que el hptotal
def insertar_pokemon(n, hpactual, estado, fecha):
	cur.execute("""SELECT legendary FROM poyo WHERE nombre = '%s'""" % (n))
	tipo = cur.fetchall()
	print("Fecthall da", tipo)
	lowest = """SELECT nombre, prioridad
			FROM sansanito
			WHERE legendary=%d
			WHERE ROWNUM <= 1
			ORDER BY ASC """ % (tipo[0][0])

	cur.execute("""SELECT COUNT(*) FROM sansanito WHERE legendary=0""")
	normales = cur.fetchall()

	print("Cantidad de normales en la tabla sansanito es:", normales)

	cur.execute("""SELECT COUNT(*) FROM sansanito WHERE legendary=1""")
	legendarios = cur.fetchall()

	print("Cantidad de legendarios en la tabla sansanito es:", legendarios)

	total_registros =  normales[0][0] + 5 * legendarios[0][0]

	print("total de registros en la tabla sansanito es:", total_registros)

	prioridad = calculate_priority(n, hpactual, estado)

	print("prioridad de", n, "es", prioridad)

	#legenadrio
	if tipo[0][0]:
		#caso 1 - quepa 
		print("Es un legenadrio y quepa!")
		if total_registros + 5 <= 50:
			insert_notpoyo(n, hpactual, estado, fecha, prioridad)
			#insert_poyodata(n)
			print_sansanito(hdrs_sansanito)		
		else:
			#no quepa
			cur.execute(lowest)
			res = cur.fetchall(lowest)
			# en caso de que sea igual, ignorar y dejar el que estaba
			if prioridad > res[1]:
				nombre = res[0]
				delete(nombre)
	else:
		#caso 1 - quepa 
		if total_registros + 1 <= 50:
			insert_notpoyo(n, hpactual, estado, fecha)
			insert_poyodata(n)	
		else:
			# no quepa
			cur.execute(lowest)
			res = cur.fetchall(lowest)
			if prioridad > res[1]:
				nombre = res[0]
				delete(nombre)

#==================================================QUERIES================================================================
#LIMIT X no funciona en 11g, asi que se uso WHERE ROWNUM <=  / = X
# Los 10 pokemon con mayor prioridad
def maxprio_sansanito():
	cur.execute("""
				SELECT nombre, prioridad
				FROM sansanito
				WHERE ROWNUM <= 10
				ORDER BY prioridad DESC
				"""
				)
	res = cur.fetchall()
	print_table([hdrs_sansanito[2],hdrs_sansanito[-1]])

# Los 10 pokemon con menor prioridad
def minprio_sansanito():
	cur.execute("""
				SELECT nombre, prioridad
				FROM sansanito
				WHERE ROWNUM <= 10
				ORDER BY prioridad ASC
				""")
	res = cur.fetchall()
	print_table([hdrs_sansanito[2],hdrs_sansanito[-1]])

#los de estado especifico
def estado_sansanito(estado):
	cur.execute("""SELECT nombre
				FROM sansanito
				WHERE estado = '%s'""" % (estado)
				)
	res = cur.fetchall()
	print_table([hdrs_sansanito[2],hdrs_sansanito[8]])

#los legendarios
def legendarios_sansanito():
	cur.execute("""
				SELECT nombre
				FROM sansanito
				WHERE legendary = 1
				""")
	res = cur.fetchall()
	print_table([hdrs_sansanito[2],hdrs_sansanito[-4]])

#el mas antiguo
def antiguedad_sansanito():
	cur.execute("""
				SELECT nombre
				FROM sansanito
				WHERE ROWNUM <= 1
				ORDER BY ingreso DESC
				""")
	res = cur.fetchall()
	print_table([hdrs_sansanito[2], hdrs_sansanito[-2]])

#el mas repetido
def repetido_sansanito():
	cur.execute("""
				SELECT nombre
				FROM sansanito
				WHERE ROWNUM <= 1
				GROUP BY nombre
				ORDER BY COUNT(*) DESC
				""")
	res = cur.fetchall()
	print_table(hdrs_sansanito)

def ordenado_sansanito(orden):
	cur.execute("""
				SELECT nombre, hpactual, hpmax, prioridad
				FROM sansanito
				ORDER BY prioridad %s""" % (orden)
				)
	res = cur.fetchall()
	print_table(hdrs_sansanito)

#==================================================QUERIES================================================================

#===================================================CREAR TABLA POYO======================================================
def ctable_poyos(archive="pokemon."):
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
	#print_poyo()

#===================================================CREAR TABLA POYO======================================================


#===================================================CREAR TABLA SANSANITO=================================================
def ctable_sansanito():
	cur.execute("DROP TABLE sansanito")

	cur.execute("CREATE TABLE sansanito (\
				id NUMBER NOT NULL PRIMARY KEY,\
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
	
	cur.execute("DROP SEQUENCE SANS_SEQ")
	cur.execute("CREATE SEQUENCE SANS_SEQ")
	cur.execute("CREATE OR REPLACE TRIGGER SANS_TRG\
				BEFORE INSERT ON sansanito\
				FOR EACH ROW\
				BEGIN\
				SELECT SANS_SEQ.NEXTVAL\
				INTO :new.id\
				FROM dual\
				END"
				)
	connection.commit()
	print_sansanito()


#===================================================CREAR TABLA SANSANITO=================================================


# MENU
#=========================================================================

#Headers globales
hdrs_poyo = ['pokedex', 'nombre', 'type1', 'type2', 'hptotal', 'legendary']
hdrs_sansanito = ['id', 'pokedex', 'nombre', 'type1',\
				'type2', 'hpactual', 'hptotal',\
				'legendary', 'estado', 'ingreso', 'prioridad']

def main():
	main_menu_title = "  BIENVENIDO AL SANSANITO POKEMON. QUE DESEA HACER?\n"
	main_menu_items = ["Ingresar un pokemon (create)", "Buscar en tabla (read)", "Opciones especiales de busqueda",\
						"Cambiar datos de pokemon ingresado (update)",\
						"Ver la tabla Poyo", "Ver la tabla Sansanito Pokemon","Salir"]
	main_menu_cursor = "> "
	main_menu_cursor_style = ("fg_red", "bold")
	main_menu_style = ("bg_purple", "fg_yellow")
	main_menu_exit = False

	main_menu = TerminalMenu(menu_entries=main_menu_items,
							 title=main_menu_title,
							 menu_cursor=main_menu_cursor,
							 menu_cursor_style=main_menu_cursor_style,
							 menu_highlight_style=main_menu_style,
							 cycle_cursor=True,
							 clear_screen=True)

	while not main_menu_exit:
		main_sel = main_menu.show()
		submenu_flag = True
		if main_sel == 0:
			nombre = input("Ingrese el nombre de pokemon: ")
			hp_actual = int(input("Ingrese HP actual de pokemon: "))
			estado = input("Ingrese el estado. Si el pokemon no tiene estado, ingrese X: ")
			if estado == "X":
				estado = ""
			fecha = input("Ingrese la fecha en formato DD-MM-YYYY HH:MM:SS XM (ej 06-09-2020 4:20:11 AM): ")
			submenu_flag = False
			insertar_pokemon(nombre, hp_actual, estado, fecha)

		elif main_sel == 1:
			menu1_title = "BUSQUEDA EN SANSANITO POKEMON. ELIGA UNA OPCION.\n"
			menu1_items = ["Busqueda por un campo", "Salir"]
			menu1 = TerminalMenu(menu_entries=menu1_items,
							 title=menu1_title,
							 menu_cursor=main_menu_cursor,
							 menu_cursor_style=main_menu_cursor_style,
							 menu_highlight_style=main_menu_style,
							 cycle_cursor=True,
							 clear_screen=True)

			time.sleep(5)
		elif main_sel == 2:
			#total de opciones: 8
			menu2_title = "BUSQUEDA ESPECIAL EN SANSANITO POKEMON. ELIGA UNA OPCION.\n"
			menu2_items = ["10 pokemon con mayor prioridad", "10 pokemon con menor prioridad",\
			"Pokemon con estado especifico", "Pokemon legendarios ingresados",\
			"Pokemon que lleva mas tiempo ingresado", "Pokemon mas repetido",\
			"Pokemon ingresados, ordenados por su prioridad", "Salir"]
			menu2 = TerminalMenu(menu_entries=menu2_items,
								title=menu2_title,
								menu_cursor=main_menu_cursor,
								menu_cursor_style=main_menu_cursor_style,
								menu_highlight_style=main_menu_style,
								cycle_cursor=True,
								clear_screen=True)
			while submenu_flag:
				menu2_sel = menu2.show()
				if menu2_sel == 0:
					maxprio_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# 10 con menor prioidad
				elif menu2_sel == 1:
					minprio_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# filtrado por estado
				elif menu2_sel == 2:
					print("NOTA: Estados disponibles son: Envenenado, Paralizado, Quemado, Dormido, Congelado\n")
					estado = input("Ingrese un estado para filtrar los datos: ")
					estado_sansanito(estado)
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# los legendarios ingresados
				elif menu2_sel == 3:
					legendarios_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# el mas antiguo
				elif menu2_sel == 4:
					antiguedad_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# pokemon mas repetido
				elif menu2_sel == 5:
					repetido_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# ordenados por prioidad
				elif menu2_sel == 6:
					orden = int(input("Ingrese 1 si desea el orden DESCENDIENTE, 0 en caso contrario: "))
					if orden:
						ordenado_sansanito("DESC")
						print("Ingrese X para volver al MENU PRINCIPAL.")
						condicion = input()
						while(condicion != "X" and condicion != "x"):
							condicion = input()
					else:
						ordenado_sansanito("ASC")
						print("Ingrese X para volver al MENU PRINCIPAL.")
						condicion = input()
						while(condicion != "X" and condicion != "x"):
							condicion = input()
				elif menu2_sel == 7:
					submenu_flag = False
		elif main_sel == 3:
			menu3_title = "CAMBIAR DATOS DE UN POKEMON INGRESADO. ELIGA UNA OPCION.\n"
			menu3_items = ["Cambiar un solo campo", "Cambiar varios campos", "Salir"]
			menu3 = TerminalMenu(menu_entries=menu3_items,
							title=menu3_title,
							menu_cursor=main_menu_cursor,
							menu_cursor_style=main_menu_cursor_style,
							menu_highlight_style=main_menu_style,
							cycle_cursor=True,
							clear_screen=True)
		#muestra la tabla de poyo, tanto tiempo como lo quiere el usuario
		elif main_sel == 4:
			print_poyo()
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion != "X" and condicion != "x"):
				condicion = input()
		#muestra la tabla de sansanito, tanto tiempo como lo quiere el usuario	
		elif main_sel == 5:
			print_sansanito()
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion != "X" and condicion != "x"):
				condicion = input()
		#saliendo, quiero dropear todas las secuencias
		elif main_sel == 6:
			cur.execute("DROP SEQUENCE SANS_SEQ")
			main_menu_exit = True


if __name__ == "__main__":
	print("Conectando al Oracle...")
	connection = cx_Oracle.connect("argentum", "1234", "XE")
	cur = connection.cursor()
	print(">>>OK")
	print("Rellenando la Base de Datos...")
	ctable_poyos()
	print(">>>OK")
	print("Montando el edificio de Sansanito Pokemon...")
	print(">>>OK")
	print("Poblando Sansanito Pokemon...")
	ctable_sansanito()
	print(">>>OK")
	print("Entrando al Sansanito Pokemon...")
	time.sleep(3)
	print(">>>OK")
	main()

connection.close()
