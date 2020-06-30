import cx_Oracle
import pandas
from tabulate import tabulate
import time
from simple_term_menu import TerminalMenu
from random import choice, randint

'''TO DO:
generar la fecha random al poblar la tabla


1 trigger
1 view (top10 o de esos)
comentar todo todillo
'''

# recibe flag=True para utilizar data customizado en vez de todo lo guardado en cursor
# esto se usa para no imprimir tablas vacias en ciertos casos y en vez tirar un aviso
def print_table(hdrs, flag=False, data=[],fmt='psql'):
	res = cur.fetchall()
	if flag:
		res = data
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
	nombre = input("Ingrese el nombre de pokemon: ")
	estado = input("Ingrese el estado. Si el pokemon no tiene estado, ingrese X: ")

	if estado.upper() == "X":
		estado = None

	if estado not in estados_permitidos:
		print("Estado de pokemon no permitido. Registro no fue insertado.")
		print("Devolviendo al menu principal...")
		return

	hp_actual = int(input("Ingrese HP actual de pokemon: "))
	fecha = input("Ingrese la fecha en formato DD/MM/YY HH:MM (ej 06/09/20 4:20): ")
	insertar_pokemon(nombre, hp_actual, estado, fecha)

#READ - lee registros con PK u otro parametro
def read():
	id_buscar = int(input("Ingrese ID de pokemon: "))
	existencia = """
				SELECT * FROM sansanito
				WHERE id = :1"""
	cur.execute(existencia, [id_buscar])
	res = cur.fetchall()
	if res == []:
		print("ID no encontrado en la tabla!")
		return
	else:
		print_table(hdrs_sansanito, True, res)



#UPDATE - cambia el registro usando su PK con un WHERE
def update():
	id_update = int(input("Ingrese ID de pokemon: "))
	existencia = """
				SELECT * FROM sansanito
				WHERE id = :1"""
	cur.execute(existencia, [id_update])
	res = cur.fetchall()
	if res == []:
		print("ID no encontrado en la tabla!")
		print("Devolviendo al menu principal...")
		time.sleep(1)
		return
	else:
		update_menu_title = "QUE CAMPO DE REGISTRO CON ID " + str(id_update) + " DESEA CAMBIAR?\n"
		update_menu_items = ["HP Actual", "Estado", "Fecha y hora de ingreso", "Salir"]
		update_menu_cursor = "> "
		update_menu_cursor_style = ("fg_red", "bold")
		update_menu_style = ("bg_purple", "fg_yellow")
		update_menu_exit = False

		update_menu = TerminalMenu(menu_entries=update_menu_items,
							 title=update_menu_title,
							 menu_cursor=update_menu_cursor,
							 menu_cursor_style=update_menu_cursor_style,
							 menu_highlight_style=update_menu_style,
							 cycle_cursor=True,
							 clear_screen=True)

	while not update_menu_exit:
		update_sel = update_menu.show()
		print("REGISTRO ESCOGIDO:")
		print_table(hdrs_sansanito, True, res)
		if update_sel == 0:
			hp_actual = int(input("Ingrese HP actual de pokemon: "))
			query_estnom  = """
							SELECT nombre, estado
							FROM sansanito
							WHERE id = :1"""
			cur.execute(query_estnom, [id_update])
			estnom = cur.fetchall()
			prioridad = calculate_priority(estnom[0][0], hp_actual, estnom[0][1])
			if prioridad != -1:
				query_update = """
								UPDATE sansanito
								SET hpactual = :1, prioridad = :2
								WHERE id = :3"""
				cur.execute(query_update, [hp_actual, prioridad, id_update])

		elif update_sel == 1:
			estado = input("Ingrese el estado. Si el pokemon no tiene estado, ingrese X: ")

			if estado.upper() == "X":
				estado = None

			if estado not in estados_permitidos:
				print("Estado de pokemon no permitido. Registro no fue insertado.")
				print("Devolviendo al menu de update...")
				time.sleep(1)
			else:
				query_nomhp  = """
								SELECT nombre, hpactual
								FROM sansanito
								WHERE id = :1"""
				cur.execute(query_nomhp, [id_update])
				nomhp = cur.fetchall()
				prioridad = calculate_priority(nomhp[0][0], nomhp[0][1], estado)
				if prioridad != -1:
					query_update = """
								UPDATE sansanito
								SET estado = :1, prioridad = :2
								WHERE id = :3"""
					cur.execute(query_update, [estado, prioridad, id_update])

		elif update_sel == 2:
			fecha = input("Ingrese la fecha en formato DD/MM/YY HH:MM (ej 06/09/20 4:20): ")
			print("Nueva fecha", fecha)
			query_fecha = """
						UPDATE sansanito
						SET ingreso = to_date(:1, 'DD/MM/YY HH:MI')
						WHERE id = :2
						"""
			cur.execute(query_fecha, [fecha, id_update])
		elif update_sel == 3:
			update_menu_exit = True

		cur.execute(existencia, [id_update])
		res = cur.fetchall()


#DELETE - borra la fila con WHERE especifico
# recibe flag  - si False, no se imprie registro borrado (delete se llamo durante la insercion)
# si es True, fue llamado por el usuario desde el menu
def delete(aidi, flag=True):
	existencia = """
				SELECT nombre FROM sansanito
				WHERE id = :1"""
	del_query = """
				DELETE FROM sansanito
				WHERE id = :1"""
	cur.execute(existencia, [aidi])
	res = cur.fetchall()
	if res != []:
		cur.execute(del_query, [aidi])
		if flag:
			print("Registro con ID", aidi, "borrado exitosamente.")
	else:
		print("ID no encontrado en la tabla!")
		print("Devolviendo al menu principal...")

#Query

def insert_aux(n, actual, e, f, prio):
	poyo_datos = """
				SELECT pokedex, type1, type2, hptotal, legendary 
				FROM poyo
				WHERE nombre = :1"""
	cur.execute(poyo_datos, [n])
	data_poyo = cur.fetchall() #lista con tupla [(pokedex, tipo1, tipo2, hptotal, legendary)]
	#saco la primera tupla
	data_poyo = data_poyo[0]
	#desempaqueto la tupla
	pokedex, t1, t2, total, l = data_poyo
	ins_query = """
				INSERT INTO sansanito (pokedex, nombre, type1, type2, hpactual, hpmax, legendary, estado, ingreso, prioridad)
				VALUES (:1, :2, :3, :4, :5, :6, :7, :8, to_date(:9, 'DD/MM/YY HH:MI'), :10)""" 
				
	cur.execute(ins_query, [pokedex, n, t1, t2, actual, total, l, e, f, prio])
				


#hdrs_poyo = ['pokedex', 'nombre', 'type1', 'type2', 'hptotal', 'legendary']
#hdrs_sansanito = ['id', 'pokedex', 'nombre', 'type1','type2', 'hpactual', 'hptotal','legendary', 'estado', 'ingreso', 'prioridad']

def calculate_priority(n, hpactual, estado):
	hptotal_query =  """SELECT hptotal FROM poyo WHERE nombre = :1""" 
	cur.execute(hptotal_query, [n])
	hptotal = cur.fetchall()
	# a pesar de que se dice que datos que ingresaran seran los correctos
	# agrego el condicional...
	if (hptotal[0][0] < hpactual) or (hpactual < 0):
		print("Datos de hp actual inconsistentes con el hp total o hp actual negativio. Intente de nuevo.")
		time.sleep(1)
		return -1
	prioridad = hptotal[0][0] - hpactual + bool(estado) * 10
	return prioridad

def insertar_pokemon(n, hpactual, estado, fecha):
	leg_query  = """SELECT legendary FROM poyo WHERE nombre = :1"""
	cur.execute(leg_query, [n])
	tipo = cur.fetchall()
	if tipo == []: #no se encontro qregistro de tipo, ergo el pkm no existe
		print("Revise su pokedex - el pokemon ingresado no existe.")
		print("Devolviendo al menu principal...")
		time.sleep(1)
		return
	lowest = """
			SELECT * FROM
			(SELECT id, prioridad
			FROM sansanito
			WHERE legendary = :1
			ORDER BY prioridad ASC)
			WHERE ROWNUM <= 1
			"""
	cur.execute("""SELECT COUNT(*) FROM sansanito WHERE legendary=0""")
	normales = cur.fetchall()
	cur.execute("""SELECT COUNT(*) FROM sansanito WHERE legendary=1""")
	legendarios = cur.fetchall()
	ocupado  =  normales[0][0] + 5 * legendarios[0][0]
	prioridad = calculate_priority(n, hpactual, estado)
	if prioridad == -1:
		return
	#legenadrio
	if tipo[0][0]:
		# caso 0 - hay un legendario de mismo nombre. como no se especifica, voy a compararlos para dejar el de mayor prio.
		existencia = """
					SELECT prioridad, id FROM sansanito WHERE nombre = :1"""
		cur.execute(existencia, [n])
		res_exists = cur.fetchall()
		# hay un legendario de mismo nombre
		if res_exists != []: #se encontro un registro
			prio_lowest = res_exists[0][0]
			id_lowest = res_exists[0][1]

		#caso 1 - quepa 
		if ocupado + 5 <= 50:
			# no hay pokemons de mismo nombre, se inserta tal cual
			if res_exists == []:
				insert_aux(n, hpactual, estado, fecha, prioridad)	
			# si hay, se compara para borrar 1 de ellos
			else:
				prio_lowest = res_exists[0][0]
				id_lowest = res_exists[0][1]
				if prioridad > prio_lowest:
					print("Prioridad de pkmn de mismo nombre id: ",id_lowest,"es ", prio_lowest, "y es menor que la de pokemon leg actual con prioridad", prioridad)
					delete(id_lowest, False)
					insert_aux(n, hpactual, estado, fecha, prioridad)
		# caso 2 - sansanito lleno
		else:
			if res_exists != []:
				prio_lowest = res_exists[0][0]
				id_lowest = res_exists[0][1]
			else:
				cur.execute(lowest, [tipo[0][0]])
				res = cur.fetchall()
				if res == []:
					print("No hay legendarios para comparar. Su registro no fue insertado.")
					print("Devolviendo al menu principal...")
					return
				prio_lowest = res[0][1]
				id_lowest = res[0][0]
			# en caso de que sea igual, ignorar y dejar el que estaba
			if prioridad > prio_lowest:
				print("Prioridad de",id_lowest,"es", prio_lowest, "y es menor que la de pokemon legendario actual con prioridad", prioridad)
				delete(id_lowest, False)
				insert_aux(n, hpactual, estado, fecha, prioridad)
	#normies
	else:
		#caso 1 - quepa 
		if ocupado + 1 <= 50:
			insert_aux(n, hpactual, estado, fecha, prioridad)	
		else:
			# no quepa
			cur.execute(lowest, [tipo[0][0]])
			res = cur.fetchall()
			if res == []:
					print("No hay normales para comparar. Su registro no fue insertado")
					print("Devolviendo al menu principal...")
					return
			prio_lowest = res[0][1]
			id_lowest = res[0][0]
			# en caso de que sea igual, ignorar y dejar el que estaba
			if prioridad > prio_lowest:
				print("Prioridad de",id_lowest,"es ", prio_lowest, "es menor que la de pokemon normal actual con prioridad", prioridad)
				delete(id_lowest, False)
				insert_aux(n, hpactual, estado, fecha, prioridad)
	print_sansanito()

#==================================================QUERIES================================================================

# Los 10 pokemon con mayor prioridad
def maxprio_sansanito():
	cur.execute("""
				SELECT * FROM maxprio_view
				"""
				)

	print_table([hdrs_sansanito[2],hdrs_sansanito[-1]])

#Vista de los 10 pokemon con mayor prioridad
def maxprio_view():
    cur.execute(
        """
            CREATE OR REPLACE VIEW maxprio_view AS
            SELECT nombre, prioridad FROM
            	(SELECT nombre, prioridad
            	FROM sansanito
                ORDER BY prioridad DESC)
            WHERE ROWNUM <= 10
        """)
    

# Los 10 pokemon con menor prioridad
def minprio_sansanito():
	cur.execute("""
				SELECT * FROM
				(SELECT nombre, prioridad
				FROM sansanito
				ORDER BY prioridad ASC)
				WHERE ROWNUM <= 10
				""")
	print_table([hdrs_sansanito[2],hdrs_sansanito[-1]])

#los de estado especifico, incluyendo los None 
def estado_sansanito(estado):
	if estado not in estados_permitidos:
		print("Estado de pokemon no permitido. Intente de nuevo.")
		return
	if estado == None:
		cur.execute("""
					SELECT nombre
					FROM sansanito
					WHERE estado is null"""
					)
	else:
		cur.execute("""
					SELECT nombre
					FROM sansanito
					WHERE estado = '%s' """ % (estado))

	print_table([hdrs_sansanito[2],hdrs_sansanito[8]])

#los legendarios
def legendarios_sansanito():
	cur.execute("""
				SELECT nombre
				FROM sansanito
				WHERE legendary = 1
				""")
	print_table([hdrs_sansanito[2],hdrs_sansanito[-4]])

#el mas antiguo
def antiguedad_sansanito():
	cur.execute("""
				SELECT * FROM
				(SELECT nombre, ingreso
				FROM sansanito
				ORDER BY ingreso ASC)
				WHERE ROWNUM <= 1
				""")
	print_table([hdrs_sansanito[2], hdrs_sansanito[-2]])

#el mas repetido
def repetido_sansanito():
	cur.execute("""
				SELECT * FROM
				(SELECT nombre
				FROM sansanito
				GROUP BY nombre
				ORDER BY COUNT(*) DESC)
				WHERE ROWNUM <= 1
				""")
	print_table(hdrs_sansanito)

def ordenado_sansanito(orden):
	cur.execute("""
				SELECT nombre, hpactual, hpmax, prioridad
				FROM sansanito
				ORDER BY prioridad %s""" % (orden)
				)
	print_table([hdrs_sansanito[2], hdrs_sansanito[5], hdrs_sansanito[6], hdrs_sansanito[-1]])

#==================================================QUERIES================================================================

#===================================================CREAR TABLA POYO======================================================
def ctable_poyos(archive="pokemon."):
	#quito espacios y no agrego _ por convencion (_ para relaciones y no atributos)
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

#===================================================CREAR TABLA POYO======================================================


#===================================================CREAR TABLA SANSANITO=================================================
def ctable_sansanito():
	cur.execute("""
				CREATE TABLE sansanito(
				id NUMBER NOT NULL PRIMARY KEY,
				pokedex INT,
				nombre VARCHAR(40),
				type1 VARCHAR(20),
				type2 VARCHAR(20),
				hpactual INT,
				hpmax INT,
				legendary  NUMBER(1),
				estado VARCHAR(30),
				ingreso DATE,
				prioridad INT)"""
				)

	
	connection.commit()


def id_trigger():
	# Como Oracle 11g no posee valor autoincrementable (existe desde 12C)
	# El trigger se encarga de ello.
	cur = connection.cursor()
	cur.execute("""	
				CREATE SEQUENCE SANS_SEQ
				START WITH 1""")

	cur.execute("""CREATE OR REPLACE TRIGGER SANS_TRG
				BEFORE INSERT ON sansanito
				FOR EACH ROW
				BEGIN
				SELECT SANS_SEQ.NEXTVAL
				INTO :new.id
				FROM dual;
				END;
				"""
				)
	connection.commit()

#===================================================CREAR TABLA SANSANITO=================================================
def poblar_sansanito(n):
	cur.execute("""
				SELECT nombre
				FROM poyo""")
	nombres = cur.fetchall() #una lista de nombres, super grande
	for i in range(n):
		nombre_elegido = choice(nombres)[0] #elige nombre random de pokemon, pero existente
		print("Se insertara a ",nombre_elegido)
		estado = choice(estados_permitidos) #elige estado incluyendo el None
		hptot_query = """
					SELECT hptotal
					FROM poyo
					WHERE nombre = :1"""
		cur.execute(hptot_query, [nombre_elegido])
		hpmax = cur.fetchall()
		hpactual = randint(0, hpmax[0][0]) #genera hpactual que no sea mayor que hpmax
		insertar_pokemon(nombre_elegido,hpactual, estado, "06/09/20 4:20")
		#Este loop permite ver paso a paso la insercion de N registros.
		'''
		print("Ingrese X seguir")
		condicion = input()
		while(condicion != "X" and condicion != "x"):
			condicion = input()'''




# MENU
#=========================================================================

#Headers globales
hdrs_poyo = ['pokedex', 'nombre', 'type1', 'type2', 'hptotal', 'legendary']
hdrs_sansanito = ['id', 'pokedex', 'nombre', 'type1',\
				'type2', 'hpactual', 'hpmax',\
				'legendary', 'estado', 'ingreso', 'prioridad']
estados_permitidos = ['Envenenado', 'Paralizado', 'Quemado', 'Dormido', 'Congelado', None]

def main():
	main_menu_title = "  BIENVENIDO AL SANSANITO POKEMON. QUE DESEA HACER?\n"
	main_menu_items = ["Crear un registro (create)", "Ingresar un pokemon", "Buscar en tabla (read)", "Opciones especiales de busqueda",\
						"Cambiar datos de pokemon ingresado (update)", "Borrar registro (delete)",\
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
		if main_sel == 0 or main_sel == 1:
			submenu_flag = False
			create()
			time.sleep(3)

		elif main_sel == 2:
			menu1_title = "BUSQUEDA DE REGISTRO EN SANSANITO POKEMON\n"
			menu1_items = ["Buscar por ID", "Salir"]
			menu1 = TerminalMenu(menu_entries=menu1_items,
							 title=menu1_title,
							 menu_cursor=main_menu_cursor,
							 menu_cursor_style=main_menu_cursor_style,
							 menu_highlight_style=main_menu_style,
							 cycle_cursor=True,
							 clear_screen=True)
			while submenu_flag:
				menu1_sel = menu1.show()
				if menu1_sel == 0:
					read()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				elif menu1_sel == 1:
					submenu_flag = False 

		elif main_sel == 3:
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
					print("NOTA: Estados disponibles son: Envenenado, Paralizado, Quemado, Dormido, Congelado")
					print("Para ver pokemons sin estado, ingrese X.\n")
					estado = input("Ingrese un estado para filtrar los datos: ")
					if estado.upper() == "X":
						estado = None
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
		elif main_sel == 4:
			update()
		# Delete de CRUD
		elif main_sel == 5:
			a_borrar = int(input("Ingrese el ID de registro a borrar: "))
			delete(a_borrar)
			time.sleep(1)
		#muestra la tabla de poyo, tanto tiempo como lo quiere el usuario
		elif main_sel == 6:
			print_poyo()
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion.upper() != "X"):
				condicion = input()
		#muestra la tabla de sansanito, tanto tiempo como lo quiere el usuario	
		elif main_sel == 7:
			print_sansanito()
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion.upper() != "X"):
				condicion = input()
		#saliendo, quiero dropear todas las secuencias
		elif main_sel == 8:
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
	cantidad =int(input("Cuantos pokemons desea generar en el Sansanito?: "))
	ctable_sansanito()
	id_trigger()
	poblar_sansanito(cantidad)
	print("Poblando Sansanito Pokemon...")
	print(">>>OK")
	print("Entrando al Sansanito Pokemon...")
	print_sansanito()
	maxprio_view()
	time.sleep(3)
	print(">>>OK")
	main()

	cur.execute("DROP SEQUENCE SANS_SEQ")
	cur.execute("DROP TABLE sansanito")
	cur.execute("DROP TABLE poyo")

	connection.close()
