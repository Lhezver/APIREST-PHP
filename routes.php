<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/APIREST-PHP/router.php");

get('/APIREST-PHP', 'APIREST-PHP/index.html');

get('/APIREST-PHP/paises', 'APIREST-PHP/api-paises.php');
get('/APIREST-PHP/paises/$id', 'APIREST-PHP/api-paises.php');

post('/APIREST-PHP/paises', 'APIREST-PHP/api-paises.php');

put('/APIREST-PHP/paises/$id', 'APIREST-PHP/api-paises.php');

delete('/APIREST-PHP/paises/$id', 'APIREST-PHP/api-paises.php');

any('/404','APIREST-PHP/404.html');
