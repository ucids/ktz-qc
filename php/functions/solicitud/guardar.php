<?php
require $_SERVER['DOCUMENT_ROOT'].'/class/sqli.php';

// Retrieve form inputs
$tracking = $_POST['tracking'];
$user = $_POST['user'];
$img = $_POST['image'];
$evidencia = $_POST['evidencia'];
$id_carro = $_POST['id_carro'];

$query_exist = "SELECT id_carro FROM carros WHERE tracking = '$tracking' AND empaque <> '' AND parts <> ''";
$exists = $conexion->query($query_exist);

if(isset($id_carro)){
    $redirect = header('Location: /solicitud.php?id='.$id_carro.'');
}else{
    $redirect = header('Location: /solicitud.php');
}
if ($evidencia == 'parts'){
    $evidencia_mensaje = 'Fotografia Small Parts Agregada Correctamente';
    $componente = '-SP';
}else{
    $evidencia_mensaje = 'Fotografia de Paquete Agregada Correctamente';
    $componente = '-Box';
}

if(mysqli_num_rows($exists) > 0) {
    session_start();
    $_SESSION['mensaje'] = 'Este Tracking Number Ya ha sido Escaneado';
    $_SESSION['tracking'] = $tracking;
    $_SESSION['color'] = 'warning';
    header('Location: /solicitud.php');
    exit; // Ensure to include exit after the redirection
}

if (empty($img)) {
            // Component inserted successfully
    session_start();
    $_SESSION['mensaje'] = 'Error: No se haz tomado la Fotografía';
    $_SESSION['tracking'] = $tracking;
    $_SESSION['color'] = 'danger';
    $redirect;
    exit; // Ensure to include exit after the redirection
}else{
    $tracking = mysqli_real_escape_string($conexion, $tracking);
    $user = mysqli_real_escape_string($conexion, $user);
    // Check if the tracking exists
    $query_tracking = "SELECT id_carro FROM carros WHERE tracking = '$tracking'";
    $result = $conexion->query($query_tracking);
    // Create folder path for uploads
    $folderPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
    $year = date("Y");
    $month = date("m");
    $yearFolderPath = $folderPath . $year . "/";
    $monthFolderPath = $yearFolderPath . $month . "/";
    $trackingFolderPath = $monthFolderPath . $tracking . "/";
    // Create necessary folders
    if (!is_dir($yearFolderPath)) {
        mkdir($yearFolderPath, 0777, true);
    }
    if (!is_dir($monthFolderPath)) {
        mkdir($monthFolderPath, 0777, true);
    }
    if (!is_dir($trackingFolderPath)) {
        mkdir($trackingFolderPath, 0777, true);
    }
    $ruta = $trackingFolderPath;
    // Save image file
    $fetch_imgParts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $fetch_imgParts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($fetch_imgParts[1]);
    $img_name = $tracking . $componente . '.jpg';
    $file = $ruta . $img_name;
    file_put_contents($file, $image_base64);
    $ruta_imagen = '/uploads/'.$year.'/'.$month.'/'.$tracking.'/'.$img_name;
    if ($result) {
        if ($result->num_rows > 0) {
            // !! Edit
            $query = "UPDATE carros SET $evidencia = '$ruta_imagen' WHERE tracking = '$tracking'";
            $resultado = $conexion->query($query);
            $row = $result->fetch_assoc();
            $fk_carro = $row['id_carro'];
            $boolean = 1;
            $color = 'success';
            $evidencia_mensaje = 'Tracking Number '. $tracking .' Finalizado';
        } else {
            $query_insertar_tracking = "INSERT INTO carros(tracking, fk_user, $evidencia ) VALUES ('$tracking', '$user', '$ruta_imagen')";
            $resultado = $conexion->query($query_insertar_tracking);
            $boolean = 0;
            $color = 'warning';
            if ($resultado) {
                // Registro insertado correctamente
                $fk_carro = $conexion->insert_id;
            } else {
                // Error ocurrido al insertar el registro
                // Maneja el error o muestra un mensaje
                die("Error: " . $conexion->error);
            }
            
        }
        if ($resultado) {
            // Component inserted successfully
            session_start();
            $_SESSION['mensaje'] = $evidencia_mensaje;
            $_SESSION['tracking'] = $tracking;
            $_SESSION['color'] = $color;
            // $_SESSION['componente'] = $componente;
            if($boolean == 0){
                header('Location: /solicitud.php?id='.$fk_carro.'');
            }else{
                header('Location: /solicitud.php?');
            }
            exit; // Ensure to include exit after the redirection
        } else {
            die("Error: " . $conexion->error);
        }
    } else {
        die("Error: " . $conexion->error);
    }

    $conexion->close();
}
?>
