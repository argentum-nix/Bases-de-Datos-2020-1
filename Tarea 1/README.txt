Nombre: Anastasiia Fedorova
ROL: 201873505-1

INSTRUCCIONES:
===========================================

Parte 1: Pre-requisitos
*******************************************

Para poder correr el programa, se requieren los siguientes
modilos/software:

a) PANDAS - para leer .csv

pip install pandas
Mayor información: https://pypi.org/project/pandas/

b) TABULATE - para imprimir las tablas

pip install tabulate
Mayor información: https://pypi.org/project/tabulate/

c) SIMPLE-TERM-MENU - menu interactivo 

pip install simple-term-menu
Mayor información: https://pypi.org/project/simple-term-menu/

*d) ORACLE - base de datos

Para la creación de la tarea se ha utilizado Oracle 11g XE.
Es recomendable preferir está versión con tal de no generar las inconsistencias
en el programa. Como el sistema principal de desarollo se ha utilizado Ubuntu 19.10
(Eoan Ermine).

IMPORTANTE: ANTES DE EJECUTAR EL PROGRAMA, RELLENAR SUS DATOS EN LA SIGUIENTE LINEA:

if __name__ == "__main__":
	print("Conectando al Oracle...")
	connection = cx_Oracle.connect("ADMIN", "PWD", "DRIVER")
Donde:
ADMIN - nombre de usuario. Es aconsejable utilizar el usuario con derechos de SYSDBA
PWD - password asociado al usuario
DRIVER - driver de oracle utilizado (por ejemplo, XE para Express Version)

*e) ORACLE_CX - interfaz de Oracle para Python

Debido a los problemas y incumplimiento de requerimientos del sistema, se ha utilizado
el paquete Oracle_cx en vez de pyodbc.

pip install cx-Oracle
Mayor información: https://pypi.org/project/cx-Oracle/

Parte 2: Funcionamiento
*******************************************
El programa se divide en 2 partes. 
La primera corresponde al código dentro de la clausa if __name__ == "__main__".
En esta parte se crean las tablas poyo (de .csv) y sansanito, ultima pre-poblada de manera aleatoria 
pero consistente con la tabla poyo. La cantidad de pokemon insertados debe ser ingresada por
el usuario. Se utiliza el archivo .csv una sola vez.

La parte 2 corresponde al menu con interfaz interactivo. Se aconseja utilizar las 
"flechas" de teclado para cambiar de opciones. Utilice ENTER para confirmar.

Se presentan las siguientes opciones. La explicación es larga, pero el menú de por si
es INTUITIVO y se apunta al usuario que NO sabe programar.

> Crear un registro

	- Se presentara la tabla original de sansanito
	- Se pide ingresar el nombre de pokemon a ingresar
	- Se pide ingresar su estado (X si no tiene estado especifico)
	- Se pide ingresar HP Actual del pokemon
	- Se pide ingresar la fecha de ingreso en el formato DD/MM/YY HH:MM, en formato de 24 horas
	- Se presenta la tabla post-inserción o un error, en caso de que el input no fue consistente.
	- Ingresar "x" o "X" para volver al MENU PRINCIPAL

> Ingresar un pokemon

	- Completamente análogo con Crear registro

> Buscar en la tabla (read)
	> Buscar por ID

		- Se pide ingresar el ID de pokemon. Si ID no existe, programa arroja un error y pide ingresar
		"x" o "X" para volver al MENU PRINCIPAL
		- Si ID existe, se presenta el registro con todos los campos de Sansanito Pokemon.
		Ingresar "x" o "X" para volver al MENU PRINCIPAL

	> Salir
		- Devuelve al usuario al MENU PRINCIPAL

> Opciones especiales de busqueda
	> 10 pokemon con mayor prioridad

		- Imprime la tabla con 10 filas (menos si sansanito tiene menos de 10 registros)
		formadas por pokemon de mayor prioridad (ordenados). Se utiliza una vista.
		Ingresar "x" o "X" para volver al menu de BUSQUEDA.

	> 10 pokemon con menor prioridad

		- Imprime la tabla con 10 filas (menos si sansanito tiene menos de 10 registros)
		formadas por pokemon de menor prioridad (ordenados). Se utiliza query.
		Ingresar "x" o "X" para volver al menu de BUSQUEDA.

	> Pokemon con estado especifico

		- Pide ingresar un estado especifico, X si se quiere buscar pokemons sin estado.
		Si se ingresa estado invalido, programa avisa al usuario de ello. Si no, se imprimen 
		los nombres de pokemon en forma de tabla de 1 columna.
		Ingresar "x" o "X" para volver al menu de BUSQUEDA.

	> Pokemon legendarios ingresados

		- Imprime la tabla con todos los nombres de legendarios, en 1 columna.
		Ingresar "x" o "X" para volver al menu de BUSQUEDA.

	> Pokemon que lleva más tiempo ingresado

		- Imprime tabla de 1 fila y dos columnas - nombre y fecha de ingreso de pokemon
		más antiguo. Ingresar "x" o "X" para volver al menu de BUSQUEDA.

	> Pokemon mas repetido

		- Imprime tabla de 1 columna con nombre de pokemon mas repetido. En caso de empate,
		imprime cualquiera de los empatados.

	> Pokemon ingresados, ordenados por su prioridad

		- Pide ingresar 0 si se desea orden DESCENDIENTE y 1 si ASCENDIENTE. En caso de input
		incorrecto, se vuelve al submenu de busqueda.
		- Imprime la tabla ordenada segun lo deseado por el usuario.
		Ingresar "x" o "X" para volver al menu de BUSQUEDA.

	> Salir 

		- Devuelve al usuario al MENU PRINCIPAL.

> Cambiar datos de pokemon ingresado
	- Se pide ingresar un ID. Si ID no existe, usuario es devuelto al menu PRINCIPAL.
	- Si ID existe, se despliega otro menu:

		QUE CAMPO DE REGISTRO CON ID X DESEA CAMBIAR?
		> HP Actual

			- Imprime registro escogido para facilitar los cambios.
			- Pide nuevo HP Actual. Si este es mayor que HP Total (Max) o negativo, se
			arroja un error. El usuario será devuelto al menu de UPDATE.
			- Si HP Actual es consistente, se ejecuta la query. El usuario será devuelto al menu de UPDATE.

		> Estado

			- Imprime registro escogido para facilitar los cambios.
			- Se pide un estado. Si se quiere quitar un estado, se debe ingresar X. Si el estado no es
			el permitido, se arroja el error. El usuario será devuelto al menu de UPDATE.
			- Si el estado es consistente, se ejecuta la query. El usuario será devuelto al menu de UPDATE.



		> Fecha y hora de ingreso

			- Imprime registro escogido para facilitar los cambios.
			- Se pide la fecha, en formato DD/MM/YY HH:MM en formato de 24 horas.
			- Se ejecuta la query. El usuario será devuelto al menu de UPDATE.

		> Salir

			- Devuelve al usuario al MENU PRINCIPAL.


> Borrar registro (delete)

	-  Se pide ingresar el ID de pokemon. Si ID no existe, programa arroja un error. El usuario será
	devuelto al menu PRINCIPAL.
	- Si ID existe en la tabla sansanito, el usuario será notificado de borrado exitoso y será 
	devuelto al menu PRINCIPAL.

> Ver la tabla Poyo

	- Imprime la tabla Sansanito Pokemon.
	Ingresar "x" o "X" para volver al menu PRINCIPAL

> Ver la tabla Sansanito Pokemon

	- Imprime la tabla Sansanito Pokemon.
	Ingresar "x" o "X" para volver al menu PRINCIPAL

> Capacidad actual

	- Imprime la capacidad actual en formato actual/50. 
	Ingresar "x" o "X" para volver al menu PRINCIPAL

> Salir
	- Cierra el programa, borrando las tablas, views y secuencias creadas.


Parte 3: ¿Qué hago si he ingresado algo mal?
*******************************************

Si algún dato fue ingresado mal (ej: fecha), por favor seguir los siguientes pasos:

PASO 1. Ingresar a sqlplus, con comando:

sqlplus

PASO 2: Ingresar el usuario y password de sysdba 

PASO 3: Ejecutar las siguientes consultas:

DROP TABLE sansanito;
DROP TABLE poyo;
DROP VIEW maxprio_view;
DROP SEQUENCE sans_seq;
exit;

PASO 4: Una vez hecho esto, volver a ejecutar el programa principal.
