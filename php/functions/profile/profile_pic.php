<?php
	require 'class/sqli.php';
	// Realizar la conexión a la base de datos
	
	// Obtener el ID del usuario de la sesión
	$userID = $_SESSION['user_id'];
	// Ejecutar la consulta SQL
	$query_user = "SELECT * FROM users WHERE id_user = '$userID'";
	$result_user = mysqli_query($conexion, $query_user);
	
	// Verificar si se encontraron result_userados
	if (mysqli_num_rows($result_user) > 0) {
		// Obtener los datos del usuario
		$row = mysqli_fetch_assoc($result_user);
		// Acceder a los campos de la tabla
		$username = $row['username'];
		$email = $row['email'];
		$name = $row['nombre'];
	
		// Mostrar los datos del usuario
		echo "Username: " . $username . "<br>";
		echo "Email: " . $email . "<br>";
		echo "Name: " . $name . "<br>";
	} else {
		echo "No se encontraron resultados para el ID de usuario: " . $userID;
	}
	
?>