<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($id) && is_numeric($id)) {

		$result = mysqli_query($conn, 'SELECT * FROM paises WHERE paises.id = ' . $id);

		$json = json_encode(mysqli_fetch_assoc($result), JSON_PRETTY_PRINT);
		mysqli_free_result($result);

		if ($json == 'null') {
			header('Content-Type: application/json');
			echo 'Not Found';
			http_response_code(404);
		} else {
			header('Content-Type: application/json');
			echo $json;
			http_response_code(200);
		}

	} else if (isset($id)) {
		header('Content-Type: application/json');
		echo 'Bad Request';
		http_response_code(400);
	} else {
		$result = mysqli_query($conn, 'SELECT * FROM paises');

		$myArray = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$myArray[] = $row;
		}
		mysqli_free_result($result);

		header('Content-Type: application/json');
		echo json_encode($myArray, JSON_PRETTY_PRINT);
		http_response_code(200);
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$postBody = file_get_contents("php://input");
	$datos = json_decode($postBody, true);

	$sqlinsert = 'INSERT INTO paises (nombre, habitantes) VALUES (?, ?);';

	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sqlinsert);
	mysqli_stmt_bind_param($stmt, 'si', $datos['nombre'], $datos['habitantes']);

	if (mysqli_stmt_execute($stmt)) {
		header('Content-Type: application/json');
		echo 'Created';
		http_response_code(201);
	} else {
		header('Content-Type: application/json');
		echo 'Bad Request';
		http_response_code(400);
	}
	mysqli_stmt_close($stmt);

} else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
	if (isset($id) && is_numeric($id)) {

		$postBody = file_get_contents("php://input");
		$datos = json_decode($postBody, true);

		$sqlupdate = 'UPDATE paises SET paises.nombre = ?, paises.habitantes = ? WHERE paises.id = ?;';

		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sqlupdate);
		mysqli_stmt_bind_param($stmt, 'sii', $datos['nombre'], $datos['habitantes'], $id);

		if (mysqli_stmt_execute($stmt)) {
			$sqlselect = 'SELECT * FROM paises WHERE paises.id = ' . $id;
			$result = mysqli_query($conn, $sqlselect);

			header('Content-Type: application/json');
			echo json_encode(mysqli_fetch_assoc($result), JSON_PRETTY_PRINT);
			http_response_code(200);

			mysqli_free_result($result);
		} else {
			header('Content-Type: application/json');
			echo 'Bad Request';
			http_response_code(400);
		}
		mysqli_stmt_close($stmt);
	} else {
		header('Content-Type: application/json');
		echo 'Bad Request';
		http_response_code(400);
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
	if (isset($id) && is_numeric($id)) {
		
		$sqldelete = 'DELETE FROM paises WHERE paises.id = ' . $id;

		if (mysqli_query($conn, $sqldelete)) {
			header('Content-Type: application/json');
			echo 'No Content';
			http_response_code(204);
		} else {
			header('Content-Type: application/json');
			echo 'Not Found';
			http_response_code(404);
		}
	} else {
		header('Content-Type: application/json');
		echo 'Bad Request';
		http_response_code(400);
	}
} else {
	header('Content-Type: application/json');
	echo 'Method Not Allowed';
	http_response_code(405);
}

mysqli_close($conn);
?>