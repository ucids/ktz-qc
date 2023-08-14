<?php
include('../../class/sqli.php');

if (isset($_GET['submit']) && isset($_GET['id'])) {
    $submitValue = $_GET['submit'];
    $solicitud_id = $_GET['id'];

    // Obtener el nuevo valor de status
    $newStatus = 0;
    if ($submitValue === 'aprove') {
        $newStatus = 2;
        $class = 'success';
        $mensaje = 'Solicitud Aprobada correctamente';
        $query = "UPDATE solicitudes SET status = '$newStatus' WHERE id = '$solicitud_id'";

    } elseif ($submitValue === 'deny') {
        $newStatus = 3;
        $class = 'danger';
        $mensaje = 'Solicitud Rechazada';
        $query = "UPDATE solicitudes SET status = '$newStatus' WHERE id = '$solicitud_id'";
    } elseif ($submitValue == 'ipos') {
        $newStatus = 4;
        $ipos =  $_GET['ipos'];
        $class = 'success';
        $mensaje = 'Codigo IPOS Asignado correctamente';
        $query = "UPDATE solicitudes SET ipos = '$ipos', status = 4 WHERE id = '$solicitud_id'";

    } else {
        // Valor desconocido en el parámetro "submit"
        echo "Valor inválido para el parámetro submit.";
        exit; // Termina la ejecución del script si el valor no es válido
    }

    if (mysqli_query($conexion, $query)) {
        
        session_start(); 
        $_SESSION['message'] = $mensaje;
        $_SESSION['message_type'] = $class;
        header('Location: /invoice.php?id='.$solicitud_id);
        // Realiza aquí cualquier otra acción necesaria
    } else {
        echo "Error al actualizar el valor de status: " . mysqli_error($connection);
    }
} else {
    // El parámetro "submit" o "id" no están presentes en la URL
    echo "Los parámetros submit e id no se proporcionaron en la URL.";
}


?>