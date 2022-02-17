<?php
require_once 'conexion.php';

try {
	$conn = new PDO($dsn, $usuario, $contrasenia);

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (isset($id) && is_numeric($id)) {

			$sentencia = $conn->query('SELECT * FROM paises WHERE paises.id = ' . $id);
			$sentencia->execute();
			$result = $sentencia->fetchAll(PDO::FETCH_ASSOC);

			$json = json_encode($result[0], JSON_PRETTY_PRINT);

			$conn = null;
			$result = null;

			if ($json == 'null') {
				header('Content-Type: application/json');
				echo 'Not Found';
				http_response_code(404);
				$json = null;
			} else {
				header('Content-Type: application/json');
				echo $json;
				http_response_code(200);
				$json = null;
			}
		} else if (isset($id)) {
			header('Content-Type: application/json');
			echo 'Bad Request';
			http_response_code(400);
		} else {
			$sentencia = $conn->query('SELECT * FROM paises');
			$sentencia->execute();
			$result = $sentencia->fetchAll(PDO::FETCH_ASSOC);

			$json = json_encode($result, JSON_PRETTY_PRINT);

			$conn = null;
			$sentencia = null;
			$result = null;

			header('Content-Type: application/json');
			echo $json;
			http_response_code(200);
			$json = null;
		}
	} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$postBody = file_get_contents("php://input");
		$datos = json_decode($postBody, true);

		$sentencia = $conn->prepare('INSERT INTO paises (nombre, habitantes) VALUES (:nombre, :habitantes);');
		$sentencia->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
		$sentencia->bindParam(':habitantes', $datos['habitantes'], PDO::PARAM_INT);

		if ($sentencia->execute()) {
			$conn = null;
			$sentencia = null;
			header('Content-Type: application/json');
			echo 'Created';
			http_response_code(201);
		} else {
			$conn = null;
			$sentencia = null;
			header('Content-Type: application/json');
			echo 'Bad Request';
			http_response_code(400);
		}
	} else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		if (isset($id) && is_numeric($id)) {

			$postBody = file_get_contents("php://input");
			$datos = json_decode($postBody, true);

			$sentencia = $conn->prepare('UPDATE paises SET paises.nombre = :nombre, paises.habitantes = :habitantes WHERE paises.id = :id;');
			$sentencia->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
			$sentencia->bindParam(':habitantes', $datos['habitantes'], PDO::PARAM_INT);
			$sentencia->bindParam(':id', $id, PDO::PARAM_INT);

			if ($sentencia->execute()) {
				$sqlselect = 'SELECT * FROM paises WHERE paises.id = ' . $id;
				$result = $conn->query('SELECT * FROM paises WHERE paises.id = ' . $id, PDO::FETCH_ASSOC);

				header('Content-Type: application/json');
				echo json_encode($result, JSON_PRETTY_PRINT);
				http_response_code(200);

				$conn = null;
				$sentencia = null;
				$result = null;
			} else {
				$conn = null;
				$sentencia = null;
				$result = null;
				header('Content-Type: application/json');
				echo 'Bad Request';
				http_response_code(400);
			}
		} else {
			$conn = null;
			header('Content-Type: application/json');
			echo 'Bad Request';
			http_response_code(400);
		}
	} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
		if (isset($id) && is_numeric($id)) {

			$sqldelete = 'DELETE FROM paises WHERE paises.id = ' . $id;

			if ($conn->exec($sqldelete) > 0) {
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
		$conn = null;
	} else {
		header('Content-Type: application/json');
		echo 'Method Not Allowed';
		http_response_code(405);
	}

	$conn = null;
} catch (PDOException $e) {
	header('Content-Type: application/json');
	echo 'Internal Server Error';
	http_response_code(500);
}
