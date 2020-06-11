import cx_Oracle
import pandas
from tabulate import tabulate
import time
from simple_term_menu import TerminalMenu

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
	delete_query = "DELETE FROM sansanito\
					WHERE nombre={}".format(nombre)
	cur.execute(maxprio10)
	connection.commit()
	
#Query

#sin terminar
#https://www.w3schools.com/sql/sql_insert_into_select.asp
def insert_notpoyo(n, hpactual, estado, fecha):
	prioridad = calculate_priority(n, hpactual, estado)
	insert_notinpoyo = "INSERT INTO sansanito (hpactual, estado, ingreso, prioridad)\
						VALUES (:0,:1,convert(DATETIME,:2, 5),:3)"
	cur.execute([hpactual, estado, fecha, prioridad])
	connection.commit()

def insert_poyodata(n):
	insert_legend = "INSERT INTO sansanito (pokedex, nombre, tipo1, tipo2, hpmax, legendary)\
							SELECT () FROM poyo\
							WHERE nombre=n"
	cur.execute(insert_legend)
	connection.commit()

def calculate_priority(n, hpactual, estado):
	hptotal = "SELECT hptotal FROM sansanito WHERE nombre=n"
	prioridad = hptotal - hpactual + bool(estado) * 10
	return prioridad


def insertar_pokemon(n, hpactual, estado, fecha):
	tipo = "SELECT legendary FROM sansanito WHERE nombre=n"
	cur.execute(tipo)
	lowest = "SELECT nombre, prioridad\
			FROM sansanito\
			WHERE legendary={}\
			ORDER BY ASC LIMIT 1".format(tipo)

	normales = "SELECT COUNT(*) FROM sansanito WHERE legendary=0"
	legendarios = "SELECT COUNT(*) FROM sansanito WHERE legendary=1" 
	total_registros =  normales + 5 * legendarios
	prioridad = calculate_priority(n, hpactual, estado)

	#legenadrio
	if tipo:
		#caso 1 - quepa 
		if total_registros + 5 <= 50:
			insert_notpoyo(n, hpactual, estado, fecha)
			insert_poyodata(n)		
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

#el mas repetido
def repetido_sansanito():
	select_repetido = "SELECT nombre\
					COUNT(nombre) AS repetido_sansanito\
					FROM sansanito\
					ORDER BY repetido_sansanito DESC\
					LIMIT 1"
	cur.execute(select_repetido)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)

def ordenado_sansanito(orden):
	select_orderby = "SELECT nombre, hpactual, hpmax, prioridad\
					FROM sansanito\
					ORDER BY prioridad {}".format(orden)
	cur.execute(select_orderby)
	connection.commit()
	res = cur.fetchall()
	print_table(hdrs_sansanito)



#=========================================================================
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

#=========================================================================
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


#=========================================================================

# MENU
#=========================================================================

#Headers globales
hdrs_poyo = ['pokedex', 'nombre', 'type1', 'type2', 'hptotal', 'legendary']
hdrs_sansanito = ['id', 'pokedex', 'nombre', 'type1',\
				'type2', 'hpactual', 'hptotal',\
				'legendary', 'ingreso', 'prioridad']

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
			fecha = input("Ingrese la fecha en formato dd-mm-yy hh:mm:ss XM (ej 06:09:2020 4:20:11 AM): ")
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
				# 10 con menor prioidad
				elif menu2_sel == 1:
					minprio_sansanito()
				# filtrado por estado
				elif menu2_sel == 2:
					estado = input("Ingrese un estado para filtrar los datos:")
					estado_sansanito(estado)
				# los legendarios ingresados
				elif menu2_sel == 3:
					legendarios_sansanito()
				# el mas antiguo
				elif menu2_sel == 4:
					antiguedad_sansanito()
				# pokemon mas repetido
				elif menu2_sel == 5:
					repetido_sansanito()
				# ordenados por prioidad
				elif menu2_sel == 6:
					orden = int(input("Ingrese 1 si desea el orden DESCENDIENTE, 0 en caso contrario:"))
					if orden:
						ordenado_sansanito("DESC")
					else:
						ordenado_sansanito("ASC")
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
	ctable_sansanito()
	print(">>>OK")
	print("Entrando al Sansanito Pokemon...")
	time.sleep(3)
	print(">>>OK")
	main()

connection.close()
