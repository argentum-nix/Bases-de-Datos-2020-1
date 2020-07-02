import cx_Oracle
import pandas
from tabulate import tabulate
import time
from simple_term_menu import TerminalMenu
from random import choice, randint

txt_sns = """
███████╗ █████╗ ███╗   ██╗███████╗ █████╗ ███╗   ██╗██╗████████╗ ██████╗                   
██╔════╝██╔══██╗████╗  ██║██╔════╝██╔══██╗████╗  ██║██║╚══██╔══╝██╔═══██╗                  
███████╗███████║██╔██╗ ██║███████╗███████║██╔██╗ ██║██║   ██║   ██║   ██║                  
╚════██║██╔══██║██║╚██╗██║╚════██║██╔══██║██║╚██╗██║██║   ██║   ██║   ██║                  
███████║██║  ██║██║ ╚████║███████║██║  ██║██║ ╚████║██║   ██║   ╚██████╔╝                  
╚══════╝╚═╝  ╚═╝╚═╝  ╚═══╝╚══════╝╚═╝  ╚═╝╚═╝  ╚═══╝╚═╝   ╚═╝    ╚═════╝                   
                            ██████╗  ██████╗ ██╗  ██╗███████╗███╗   ███╗ ██████╗ ███╗   ██╗
                            ██╔══██╗██╔═══██╗██║ ██╔╝██╔════╝████╗ ████║██╔═══██╗████╗  ██║
                            ██████╔╝██║   ██║█████╔╝ █████╗  ██╔████╔██║██║   ██║██╔██╗ ██║
                            ██╔═══╝ ██║   ██║██╔═██╗ ██╔══╝  ██║╚██╔╝██║██║   ██║██║╚██╗██║
                            ██║     ╚██████╔╝██║  ██╗███████╗██║ ╚═╝ ██║╚██████╔╝██║ ╚████║
                            ╚═╝      ╚═════╝ ╚═╝  ╚═╝╚══════╝╚═╝     ╚═╝ ╚═════╝ ╚═╝  ╚═══╝ 
					     █████████████						
				        ████▒▒░░░░░░░░██▒▒░░██			 		
				      ██▒▒░░░░██░░██░░░░██░░░░██				 		
				    ██▒▒░░░░░░██░░██░░░░░░▒▒░░██				 		
				    ██░░░░░░░░██░░██░░░░░░▒▒▒▒██				 		
				  ██░░░░░░▒▒▒▒░░░░░░▒▒▒▒░░░░▒▒██						
				██▒▒░░░░░░░░░░░░██░░░░░░░░░░░░███                		
				██░░░░▒▒░░░░░░░░██░░░░░░░░░░▒▒███                		
				██░░░░▒▒░░░░░░░░░░░░░░░░░░░░██						
				  ██████░░░░░░░░░░░░░░░░░░▒▒██						
				██▒▒▒▒▒▒██░░░░░░░░░░░░░░░░▒▒██						
				██▒▒▒▒▒▒▒▒██░░░░░░░░░░░░▒▒██				   		  
				██▒▒▒▒▒▒▒▒██░░░░░░░░░░▒▒████						
				  ██▒▒▒▒▒▒▒▒██▒▒▒▒▒▒████▒▒▒▒██			     		
				    ██▒▒▒▒██████████▒▒▒▒▒▒▒▒▒▒██				 		
				      ██████      ████████████				  		
				      ██████      ████████████                		   
				       █████       █████                       
				"""

def print_table(hdrs, flag=False, data=[],fmt='psql'):
	"""
	Imprime una tabla.

	Utiliza datos de cursor (o data, si flag es True)
	para rellenar la tabla.

	Parametros
	----------
	hdrs : headers de las columnas
		Datos de primera fila de la tabla
	flag : bool
		Es False por defecto. True indica que se debe utilizar
		data para rellenar la tabla.
	data : lista
		Datos que se utilizan para rellar la tabla si flag es True
	fmt : str
		Formato de la tabla. Por defecto, es psql.

	Return
	-------
	None
		No retorna nada

	"""
	res = cur.fetchall()
	if flag:
		res = data
	print(tabulate(res, headers=hdrs, tablefmt=fmt))

def print_poyo():
	"""
	Imprime una tabla de datos de poyo.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	poyo = "SELECT * FROM poyo"
	cur.execute(poyo)
	print_table(hdrs_poyo)

def print_sansanito():
	"""
	Imprime una tabla de datos de sansanito pokemon.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	ssn = "SELECT * FROM sansanito"
	cur.execute(ssn)
	print_table(hdrs_sansanito)

#==================================================CRUD===================================================================
def create():
	"""
	Crea una fila en la tabla. Funcion CREATE de CRUD.

	Recibe nombre, estado, hp actual y fecha de ingreso de pokemon.
	Llama a funcion insertar_pokemon que crea una fila en la tabla si 
	corresponde.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	nombre = input("Ingrese el nombre de pokemon: ")
	estado = input("Ingrese el estado. Si el pokemon no tiene estado, ingrese X: ")

	if estado.upper() == "X":
		estado = None

	if estado not in estados_permitidos:
		print("Estado de pokemon no permitido. Registro no fue insertado.")
		print("Devolviendo al menu principal...")
		return

	hp_actual = int(input("Ingrese HP actual de pokemon: "))
	fecha = input("Ingrese la fecha en formato DD/MM/YY HH24:MM (ej 06/09/20 14:20): ")
	insertar_pokemon(nombre, hp_actual, estado, fecha)

def read():
	"""
	Lea una fila de la tabla. Funcion READ de CRUD.

	Pide ID de fila a leer. Revisa su existencia.
	Si el ID existe, se llama a la funcion print_table 
	para imprimir una tabla (de una sola fila).

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	id_buscar = int(input("Ingrese ID de pokemon: "))
	existencia = """
				SELECT * FROM sansanito
				WHERE id = :1"""
	cur.execute(existencia, [id_buscar])
	res = cur.fetchall()
	# Res vacio implica que no existe registro con ID ingresado
	if res == []:
		print("ID no encontrado en la tabla!")
		return
	else:
		print_table(hdrs_sansanito, True, res)



def update():
	"""
	Cambia un campo de fila de la tabla. Funcion UPDATE de CRUD.

	Pide ID de fila a leer. Revisa su existencia. Si no exista, se arroja un aviso.
	Si existe, se inicia un menu que permite elegir el campo para cambiar o Salir.

	Una vez elegido el campo, se muestra el registro escogido. Se pide el nuevo
	valor del campo. Se revisa su consistencia (menos en el caso de la fecha).
	En caso de ingresar estado o hp actual, se recalcula la prioridad.
	
	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	id_update = int(input("Ingrese ID de pokemon: "))
	existencia = """
				SELECT * FROM sansanito
				WHERE id = :1"""
	cur.execute(existencia, [id_update])
	res = cur.fetchall()
	# Si res es vacio, no existe ningun registro con ID ingresado
	if res == []:
		print("ID no encontrado en la tabla!")
		print("Devolviendo al menu principal...")
		time.sleep(1)
		return
	else:
		# Submenu de la opcion
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
		# Cambiar HP Actual
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
		# Cambiar estado
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
		# Cambio de fecha de ingreso
		elif update_sel == 2:
			fecha = input("Ingrese la fecha en formato DD/MM/YY HH:MM (ej 06/09/20 14:20): ")
			print("Nueva fecha", fecha)
			query_fecha = """
						UPDATE sansanito
						SET ingreso = to_date(:1, 'DD/MM/YY HH24:MI')
						WHERE id = :2
						"""
			cur.execute(query_fecha, [fecha, id_update])
		# Salir
		elif update_sel == 3:
			update_menu_exit = True

		cur.execute(existencia, [id_update])
		res = cur.fetchall()


def delete(aidi, flag=True):
	"""
	Borra un campo de fila de la tabla. Funcion DELETE de CRUD.

	Parametros
	----------
	aidi : int
		PK de la tabla, que corresponde al registro a borrar.
	flag : bool
		Es True por defecto. False indica que no quiero imprimir "registro borrado..."
		Este flag se usa para no imprimir nada al poblar sansanito pokemon.
		Los prints se hacen en cualquier otro caso.
	Return
	-------
	None
		No retorna nada

	"""
	existencia = """
				SELECT nombre FROM sansanito
				WHERE id = :1"""
	del_query = """
				DELETE FROM sansanito
				WHERE id = :1"""
	cur.execute(existencia, [aidi])
	res = cur.fetchall()
	# Hay un registro con ID - y como es PK, es unico.
	if res != []:
		cur.execute(del_query, [aidi])
		if flag:
			print("Registro con ID", aidi, "borrado exitosamente.")
	else:
		print("ID no encontrado en la tabla!")
		print("Devolviendo al menu principal...")

#==================================================QUERY==================================================================
def insert_aux(n, actual, e, f, prio):
	"""
	Inserta los datos a la tabla.

	Recibe los datos consistentes y revisados. Obtiene datos faltantes
	de la tabla poyo. Finalmente, inserte el registro  con datos 

	pokedex, nombre, type1, type2, hpactual, hpmax, legendary,
	estado, ingreso, prioridad

	Parametros
	----------
	n : str
		Nombre de pokemon a insertar.
	actual : int
		Entero que indica hp actual de pokemon a ingresar.
	e : str
		String que indica el estado de pokemon. Incluye None.
	f :	str
		String con fecha en formato DD/MM/YY HH24:MM
	prio : int
		Entero que indica la prioridad de pokemon a ingresar.
	Return
	-------
	None
		No retorna nada

	"""
	poyo_datos = """
				SELECT pokedex, type1, type2, hptotal, legendary 
				FROM poyo
				WHERE nombre = :1"""
	cur.execute(poyo_datos, [n])
	# Lista con tupla [(pokedex, tipo1, tipo2, hptotal, legendary)]
	data_poyo = cur.fetchall()
	data_poyo = data_poyo[0]
	pokedex, t1, t2, total, l = data_poyo
	ins_query = """
				INSERT INTO sansanito (pokedex, nombre, type1, type2,\
				hpactual, hpmax, legendary, estado, ingreso, prioridad)
				VALUES (:1, :2, :3, :4, :5, :6, :7, :8, to_date(:9, 'DD/MM/YY HH24:MI'), :10)""" 
				
	cur.execute(ins_query, [pokedex, n, t1, t2, actual, total, l, e, f, prio])
				


def calculate_priority(n, hpactual, estado):
	"""
	Calcula la prioridad de pokemon.

	Usa la formula entregada en pdf, para calcular la prioridad
	de pokemon a ingresar. Revisa si hpactual es consistente con poyo.

	Parametros
	----------
	n : str
		Nombre de pokemon a insertar.
	hpactual : int
		Entero que indica hp actual de pokemon a ingresar.
	estado : str
		String que indica el estado de pokemon. Incluye None.
	Return
	-------
	int
		Retorna variable prioridad, que contiene la prioridad calculada.
		Si hp actual ingresado es mayor que hp total o es negativo, se retorna
		-1.

	"""
	hptotal_query =  """
					SELECT hptotal
					FROM poyo
					WHERE nombre = :1
					""" 
	cur.execute(hptotal_query, [n])
	hptotal = cur.fetchall()
	# Reviso si hp actual no es negativo ni tampoco mayor que el total (maximo que puede tener pokemon)
	if (hptotal[0][0] < hpactual) or (hpactual < 0):
		print("Datos de hp actual inconsistentes con el hp total o hp actual negativio. Intente de nuevo.")
		time.sleep(1)
		return -1
	# Calculo prioridad como total - hpactual + 10 * (estado vacio == 0 y estado no vacio == 1)
	prioridad = hptotal[0][0] - hpactual + bool(estado) * 10
	return prioridad

def calcular_ocupacion():
	"""
	Calcula la ocupacion de sansanito en base a la
	cantidad de normales y de legendarios ingresados.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	int
		Retorna la ocupacion actual de sansanito

	"""
	cur.execute("""
				SELECT COUNT(*)
				FROM sansanito
				WHERE legendary=0""")
	normales = cur.fetchall()
	cur.execute("""
				SELECT COUNT(*)
				FROM sansanito
				WHERE legendary=1""")
	legendarios = cur.fetchall()
	# Calcula la ocupacion como cant_normales * 1 + cant_legendarios * 5
	ocupado  =  normales[0][0] + 5 * legendarios[0][0]
	return ocupado

# flag se usa para no imprimir al poblar el sansanito de manera random
def insertar_pokemon(n, hpactual, estado, fecha, flag=True):
	leg_query  = """
				SELECT legendary
				FROM poyo
				WHERE nombre = :1"""
	cur.execute(leg_query, [n])
	tipo = cur.fetchall()
	if tipo == []: #no se encontro qregistro de tipo, ergo el pkm no existe
		print("Revise su pokedex - el pokemon ingresado no existe.")
		return
	lowest = """
			SELECT * FROM
			(SELECT id, prioridad
			FROM sansanito
			WHERE legendary = :1
			ORDER BY prioridad ASC)
			WHERE ROWNUM <= 1
			"""
	ocupado  =  calcular_ocupacion()
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
			if flag:
				print("\nExiste un legendario de mismo nombre en el sansanito. Su ID es", id_lowest)

		#caso 1 - quepa 
		if ocupado + 5 <= 50:
			# no hay pokemons de mismo nombre, se inserta tal cual
			if res_exists == []:
				insert_aux(n, hpactual, estado, fecha, prioridad)	
			# si hay, se compara para borrar 1 de ellos
			else:
				if prioridad > prio_lowest:
					if flag:
						print("El legendario de mismo nombre tiene menor prioridad. Se insertara su registro en vez de este.")
					delete(id_lowest, False)
					insert_aux(n, hpactual, estado, fecha, prioridad)
				else:
					if flag:
						print("El legendario de mismo nombre tiene mayor prioridad. Su registro no fue insertado.")
		# caso 2 - sansanito lleno
		else:
			# por defecto, valores de prio_lowest y id_lowest son de legendario de mismo nombre.
			# si no hay, se reasignan aqui:
			# no hay legendarios de mismo nombre
			if res_exists == []:
				cur.execute(lowest, [tipo[0][0]])
				res = cur.fetchall()
				# tampoco hay legendarios
				if res == []:
					if flag:
						print("\nNo hay legendarios para comparar. Su registro no fue insertado.")
					return
				# si hay legendarios, se puede comparar
				prio_lowest = res[0][1]
				id_lowest = res[0][0]
			# en caso de que sea igual, ignorar y dejar el que estaba
			if prioridad > prio_lowest:
				if flag:
					print("Registro de pokemon legendario con ID", id_lowest, "tiene menor prioridad. Se insertara su registro en vez de este.")
				delete(id_lowest, False)
				insert_aux(n, hpactual, estado, fecha, prioridad)
			else:
				if res_exists == []:
					if flag:
						print("\nNo existe pokemon legendario de menor prioridad que pokemon a insertar. Su registro no fue insertado.")
				else:
					if flag:
						print("El legendario de mismo nombre tiene mayor prioridad. Su registro no fue insertado.")
	#normies
	else:
		#caso 1 - quepa 
		if ocupado + 1 <= 50:
			insert_aux(n, hpactual, estado, fecha, prioridad)	
		else:
			# no quepa
			cur.execute(lowest, [tipo[0][0]])
			res = cur.fetchall()
			# hay puros legendarios, no se puede comparar
			if res == []:
				if flag:
					print("\nNo hay pokemon normales para comparar. Su registro no fue insertado")
				return
			# existen normales para comparar
			prio_lowest = res[0][1]
			id_lowest = res[0][0]
			# en caso de que sea igual, ignorar y dejar el que estaba
			if prioridad > prio_lowest:
				if flag:
					print("Registro de pokemon normal con ID", id_lowest, "tiene menor prioridad. Se insertara su registro en vez de este.")
				delete(id_lowest, False)
				insert_aux(n, hpactual, estado, fecha, prioridad)
			else:
				if flag:
					print("No existe pokemon normal de menor prioridad que pokemon a insertar. Su registro no fue insertado.")

#==================================================QUERIES================================================================

def maxprio_sansanito():
	"""
	Imprima la tabla apartir de la vista maxprio_view.
	La tabla tendra 10 registros (o menos, si sansanito tiene menos de 10 registros)
	con mayor prioridad.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	cur.execute("""
				SELECT * FROM maxprio_view
				"""
				)

	print_table([hdrs_sansanito[2],hdrs_sansanito[-1]])

def maxprio_view():
	"""
	Imprima la tabla que tendra 10 registros
	(o menos, si sansanito tiene menos de 10 registros)
	con mayor prioridad global.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	cur.execute(
		"""
			CREATE OR REPLACE VIEW maxprio_view AS
			SELECT nombre, prioridad FROM
				(SELECT nombre, prioridad
				FROM sansanito
				ORDER BY prioridad DESC)
			WHERE ROWNUM <= 10
		""")
	

def minprio_sansanito():
	"""
	Crea una vista con 10 o menos registros 
	de minima prioridad de la tabla.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	cur.execute("""
				SELECT * FROM
				(SELECT nombre, prioridad
				FROM sansanito
				ORDER BY prioridad ASC)
				WHERE ROWNUM <= 10
				""")
	print_table([hdrs_sansanito[2],hdrs_sansanito[-1]])


def estado_sansanito(estado):
	"""
	Imprime tabla de una columna, nombre, con nombres
	de pokemon con cierto estado (None o un string permitido)

	Parametros
	----------
	estado : str o None
		Estado por el cual se quiere filtrar los registros

	Return
	-------
	None
		No retorna nada

	"""
	# Si estado no esta en la lista predefinida, no sera aceptado
	if estado not in estados_permitidos:
		print("Estado de pokemon no permitido. Intente de nuevo.")
		return
	# Si estado es None, busca registros con campo nulo
	if estado == None:
		cur.execute("""
					SELECT nombre
					FROM sansanito
					WHERE estado is null"""
					)
	# Si no, busca con el estado ingresado pues es strng
	else:
		cur.execute("""
					SELECT nombre
					FROM sansanito
					WHERE estado = '%s' """ % (estado))

	print_table([hdrs_sansanito[2]])

def legendarios_sansanito():
	"""
	Imprime tabla de una columna, nombre, con nombres
	de los pokemon legendarios.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	cur.execute("""
				SELECT nombre
				FROM sansanito
				WHERE legendary = 1
				""")
	print_table([hdrs_sansanito[2],hdrs_sansanito[-4]])

def antiguedad_sansanito():
	"""
	Imprime tabla de una fila y dos columnas - nombre y ingreso.
	El registro imprimido es el con fecha mas antigua.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	cur.execute("""
				SELECT * FROM
				(SELECT nombre, ingreso
				FROM sansanito
				ORDER BY ingreso ASC)
				WHERE ROWNUM <= 1
				""")
	print_table([hdrs_sansanito[2], hdrs_sansanito[-2]])

def repetido_sansanito():
	"""
	Imprime tabla de una columna, nombre, con nombre del
	pokemon mas repetido. En caso de empate, si elige cualquier
	opcion dentro de los empatados.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
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
	"""
	Imprime tabla de 4 columnas - nombre, hp actual, hp max, prioridad
	ordenada segun el parametro orden.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	cur.execute("""
				SELECT nombre, hpactual, hpmax, prioridad
				FROM sansanito
				ORDER BY prioridad %s""" % (orden)
				)
	print_table([hdrs_sansanito[2], hdrs_sansanito[5], hdrs_sansanito[6], hdrs_sansanito[-1]])



#===================================================CREAR TABLA POYO======================================================
def ctable_poyos():
	"""
	Lee un archivo CSV pokemon.csv, pero solo algunas columnas
	Adicionalmente, se ignora por completo la primera fila (headers)
	Crea una tabla de poyo y la rellena con datos leidos

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
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
	# Uso NUMBER(1) en vez de BOOLEAN para representar los booleanos
	# Uso usecols que guarda solo algunas columnas
	pkmn = pandas.read_csv('pokemon.csv',sep=",",usecols=(0, 1, 2, 3 , 4, 12))
	# Leer fila por fila para pillar True/False y NaN
	add_row_pkmn = ("""
					INSERT INTO poyo 
		   			(pokedex, nombre, type1, type2, hptotal, legendary) 
		  			VALUES (:1,:2,:3,:4,:5,:6)"""
		  			)
	for d in pkmn.values:
		t2 = d[3]
		bln = 0
		if not isinstance(t2, str):
			# Caso NaN
			t2 = ""
		if d[-1] == True:
			bln = 1
		row_pkmn = [int(d[0]), d[1], d[2], t2, int(d[4]), bln]
		cur.execute(add_row_pkmn, row_pkmn)
		connection.commit()



#===================================================CREAR TABLA SANSANITO=================================================
def ctable_sansanito():
	"""
	Crea una tabla sansanito, con campos exigidos. Su PK es el ID
	autogenerado con trigger (unico y autoincrementable)

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
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

#===================================================CREAR TRIGGER=========================================================
def id_trigger():
	"""
	Como Oracle 11g no posee valor autoincrementable (existe desde 12C),
	se crea un sequencia y trigger para automatizar el proceso al
	insertar

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
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

#===================================================POBLAR TABLA SANSANITO================================================
def generar_fecha(): 
	"""
	Genera un string con fecha de forma DD/MM/YY HH:MM formato de 24h
	Se genera desde 2009 a 2020 (de fecha de que se creo campus SJ hasta dia de hoy)

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	mes = randint(1, 12)
	year = randint(9, 20)
	bisiestos = [2012, 2016, 2020]
	mes30 = [4, 6, 9, 11]
	# Caso de febrero
	if mes == 2:
		# Febrero tiene 29 dias en caso de ano bisiesto
		if year in bisiestos:
			dia = randint(1, 29)
		else:
			# En otro caso son 28 dias
			dia = randint(1, 28)
	elif mes in mes30:
		# Abril, junio, septiembre y noviembre todos tienen 30 dias max
		dia = randint(1, 30)
	else:
		# Los demas meses tienen 31 dia
		dia = randint(1, 31)

	# Genera fecha desde fecha de inaguracion de campus SJ hasta este ano
	hora = randint(1, 23)
	minuto = randint(0, 59)
	return "{}/{}/{} {}:{}".format(dia, mes, year, hora, minuto)

def poblar_sansanito(n):
	"""
	Genera datos random para rellenar la tabla sansanito.
	Genera n registros consistentes y los inserta en la tabla.

	Parametros
	----------
	n : int
		Cantidad de registros a insertar

	Return
	-------
	None
		No retorna nada

	"""
	cur.execute("""
				SELECT nombre
				FROM poyo""")
	nombres = cur.fetchall()
	# Una lista de nombres, super grande
	for i in range(n):
		nombre_elegido = choice(nombres)[0] 
		# Elige nombre random de pokemon, pero existente
		estado = choice(estados_permitidos) 
		# Elige estado incluyendo el None
		hptot_query = """
					SELECT hptotal
					FROM poyo
					WHERE nombre = :1"""
		cur.execute(hptot_query, [nombre_elegido])
		hpmax = cur.fetchall()
		hpactual = randint(0, hpmax[0][0]) 
		# Genera hpactual que no sea mayor que hpmax
		fecha_ingreso = generar_fecha()
		insertar_pokemon(nombre_elegido,hpactual, estado, fecha_ingreso, False)

#====================================================MENU=================================================================

#Headers globales, usados para imprimir tablas
hdrs_poyo = ['pokedex', 'nombre', 'type1', 'type2', 'hptotal', 'legendary']
hdrs_sansanito = ['id', 'pokedex', 'nombre', 'type1',\
				'type2', 'hpactual', 'hpmax',\
				'legendary', 'estado', 'ingreso', 'prioridad']
estados_permitidos = ['Envenenado', 'Paralizado', 'Quemado', 'Dormido', 'Congelado', None]

def main():
	"""
	Crea un menu interactivo, basado en TerminalMenu.
	Este contiene varias opciones, pedidas por el enunciado
	y varios submenus. Es interactivo e intuitivo, pues se entregan las 
	instrucciones durante la ejecucion.

	Parametros
	----------
	No recibe parametros.

	Return
	-------
	None
		No retorna nada

	"""
	main_menu_title = txt_sns + "\nBIENVENIDO AL SANSANITO POKEMON. QUE DESEA HACER?\n"
	# Opciones de menu principal
	main_menu_items = ["Crear un registro (create)", "Ingresar un pokemon", "Buscar en tabla (read)", "Opciones especiales de busqueda",\
						"Cambiar datos de pokemon ingresado (update)", "Borrar registro (delete)",\
						"Ver la tabla Poyo", "Ver la tabla Sansanito Pokemon", "Capacidad actual", "Salir"]
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
		# Create de CRUD y Ingresar pokemon
		if main_sel == 0 or main_sel == 1:
			submenu_flag = False
			print("TABLA ORIGINAL:")
			print_sansanito()
			create()
			print("\nTABLA FINAL:")
			print_sansanito()
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion != "X" and condicion != "x"):
				condicion = input()

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
				# 10 con mayor prioridad
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
				# Filtrado por estado
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
				# Los legendarios ingresados
				elif menu2_sel == 3:
					legendarios_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# El mas antiguo
				elif menu2_sel == 4:
					antiguedad_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# Pokemon mas repetido
				elif menu2_sel == 5:
					repetido_sansanito()
					print("Ingrese X para volver al MENU PRINCIPAL.")
					condicion = input()
					while(condicion != "X" and condicion != "x"):
						condicion = input()
				# Ordenados por prioidad
				elif menu2_sel == 6:
					orden = input("Ingrese 1 si desea el orden DESCENDIENTE, 0 en caso contrario: ")
					if orden != "1" and orden != "0":
						print("Input invalido! Intente de nuevo.")
					else:
						if orden == "1":
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
		# Muestra la tabla de poyo
		elif main_sel == 6:
			print_poyo()
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion.upper() != "X"):
				condicion = input()
		# Muestra la tabla de sansanito
		elif main_sel == 7:
			print_sansanito()
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion.upper() != "X"):
				condicion = input()
		# Funcion extra que muestra la ocupacion actual de sansanito
		elif main_sel == 8:
			print("La capacidad actual es: {}/50".format(calcular_ocupacion()))
			print("Ingrese X para volver al MENU PRINCIPAL.")
			condicion = input()
			while(condicion != "X" and condicion != "x"):
				condicion = input()
		# Salir
		elif main_sel == 9:
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
	cantidad = int(input("Cuantos pokemons desea generar en el Sansanito?: "))
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
	cur.execute("DROP VIEW maxprio_view")

	connection.close()
