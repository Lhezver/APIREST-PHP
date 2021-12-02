<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/api/router.php");

get('/api', 'api/index.html');

get('/api/paises', 'api/api-paises.php');
get('/api/paises/$id', 'api/api-paises.php');

post('/api/paises', 'api/api-paises.php');

put('/api/paises/$id', 'api/api-paises.php');

delete('/api/paises/$id', 'api/api-paises.php');

any('/404','api/404.html');
