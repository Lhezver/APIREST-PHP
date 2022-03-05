# APIREST-PHP
## GET:

http://localhost/api/paises

Obtiene todos los paises

http://localhost/api/paises/$id

Obtiene el pais con el id especificado


## POST:

http://localhost/api/paises

JSON:

~~~
{
"nombre": "nombrePais",
"habitantes": 0
}
~~~

Crea un pais

## PUT:

http://localhost/api/paises/$id

JSON:

~~~
{
"nombre": "nombrePais",
"habitantes": 0
}
~~~

Reemplaza los datos del pais que corresponda con el id especificado

## DELETE:

http://localhost/api/paises/$id

Elimina el pais que corresponda con el id especificado

------------------------------------------------------------------------------------
### Librería utilizada para enrutar:

web: https://phprouter.com/

Repositorio: https://github.com/phprouter/main
