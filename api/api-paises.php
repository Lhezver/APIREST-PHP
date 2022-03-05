<?php
require_once 'conexion.php';

try {
	$conn = new PDO(DSN, USUARIO, CONTRASENIA);

	switch ($_SERVER['REQUEST_METHOD']) {
		case 'GET':
			if (isset($id) && is_numeric($id)) {

				$sentencia = $conn->query('SELECT * FROM paises WHERE paises.id = ' . $id);
				$sentencia->execute();
				$result = $sentencia->fetchAll(PDO::FETCH_ASSOC);

				$json = json_encode($result[0], JSON_PRETTY_PRINT);

				$sentencia = null;
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

				$id = null;
			} else if (isset($id)) {
				$id = null;

				header('Content-Type: application/json');
				echo 'Bad Request';
				http_response_code(400);
			} else {
				$sentencia = $conn->query('SELECT * FROM paises');
				$sentencia->execute();
				$result = $sentencia->fetchAll(PDO::FETCH_ASSOC);

				$json = json_encode($result, JSON_PRETTY_PRINT);

				$sentencia = null;
				$result = null;

				header('Content-Type: application/json');
				echo $json;
				http_response_code(200);

				$json = null;
			}
			break;

		case 'POST':
			$postBody = file_get_contents("php://input");
			$datos = json_decode($postBody, true);

			$sentencia = $conn->prepare('INSERT INTO paises (nombre, habitantes) VALUES (:nombre, :habitantes);');
			$sentencia->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
			$sentencia->bindParam(':habitantes', $datos['habitantes'], PDO::PARAM_INT);

			if ($sentencia->execute()) {
				$postBody = null;
				$sentencia = null;
				$datos = null;

				header('Content-Type: application/json');
				echo 'Created';
				http_response_code(201);
			} else {
				$postBody = null;
				$sentencia = null;
				$datos = null;
				header('Content-Type: application/json');
				echo 'Bad Request';
				http_response_code(400);
			}
			break;

		case 'PUT':
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

					$postBody = null;
					$sentencia = null;
					$datos = null;
					$sqlselect = null;

					header('Content-Type: application/json');
					echo json_encode($result, JSON_PRETTY_PRINT);
					http_response_code(200);
					$result = null;
				} else {
					$postBody = null;
					$sentencia = null;
					$datos = null;
					header('Content-Type: application/json');
					echo 'Bad Request';
					http_response_code(400);
				}

				$id = null;
			} else {
				header('Content-Type: application/json');
				echo 'Bad Request';
				http_response_code(400);
			}
			break;

		case 'DELETE':
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

				$sqldelete = null;
				$id = null;
			} else {
				header('Content-Type: application/json');
				echo 'Bad Request';
				http_response_code(400);
			}
			break;

		default:
			header('Content-Type: application/json');
			echo 'Method Not Allowed';
			http_response_code(405);
			break;
	}

	$conn = null;
} catch (PDOException $pdoexc) {
	header('Content-Type: application/json');
	echo 'Internal Server Error';
	http_response_code(500);
}
catch (Exception $exc){
	header('Content-Type: application/json');
	echo 'Internal Server Error';
	http_response_code(500);
}
?>