<?php
	session_start();

	require 'class/data.php';

	if (isset($_SESSION['user_id'])) {
		// $records = $conn->prepare('SELECT * FROM users WHERE id_user = :id');
        $records = $conn->prepare('SELECT users.*, roles.descripcion AS rol FROM users INNER JOIN roles ON users.fk_rol = roles.id_rol WHERE id_user = :id');
		$records->bindParam(':id', $_SESSION['user_id']);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);
		$user = null;

	if (count($results) > 0) {
		$user = $results;
	}

	}else{
		header("Location: /view/sign-in.php");
	}
?>
<!DOCTYPE html>
<html lang="en">

<!--begin::Head-->

<head>
    <base href="../">
    <title>Katzkin | Compras</title>
    <meta name="description" content="KTZ Dashboard" />
    <meta name="keywords" content="ucid, " />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="ucid - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://ucid.com" />
    <meta property="og:site_name" content="Ucid | Katzkin" />
    <link rel="canonical" href="https://ucid.com" />
    <link rel="shortcut icon" href="view/assets/media/logos/katzkin.png" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <link href="view/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="view/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" style="background-image: url(view/assets/media/patterns/header-bg.png)"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled aside-enabled">
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!-- Begin Header -->
                <?php include "partials/skeleton/header.php"; ?>
                <!-- End: Header -->
                <!--begin::Toolbar-->
                <?php include "partials/skeleton/toolbar.php"; ?>
                <!--end::Toolbar-->
                <!--begin::Container-->

                <?php include "partials/solicitud/cam.php"; ?>
                <!--end::Container-->
                <!--begin::Footer-->
                <?php include "partials/skeleton/footer.php" ?>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)"
                    fill="black" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="black" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Scrolltop-->
    <!--end::Main-->
    <script>
    var hostUrl = "view/assets/";
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- jQuery UI library -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#proveedor").autocomplete({
                source: "functions/solicitud/sugerencias.php",
            });
        });
    </script>
    <script language="JavaScript">
    // Función para verificar si es un dispositivo móvil
    function isMobileDevice() {
        return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
    }
    
    // Verificar el tipo de dispositivo y ajustar los valores de width y height
    function adjustWebcamSize() {
        var width, height;
        
        if (isMobileDevice()) {
        // Valores para dispositivos móviles
        width = 320;
        height = 240;
        } else {
        // Valores para computadoras
        width = 720;
        height = 490;
        }
        
        // Actualizar los valores de Webcam.set
        Webcam.set({
        width: width,
        height: height,
        image_format: 'jpg',
        jpeg_quality: 100
        });
    }
    
    // Llamar a la función para ajustar los valores de Webcam.set
    adjustWebcamSize();
    
    // Adjuntar la webcam al elemento con el ID "web_cam"
    Webcam.attach('#web_cam');
    
    function take_snapshot() {
        Webcam.snap(function(web_cam_data) {
        $(".image-tag").val(web_cam_data);
        document.getElementById('response').innerHTML = '<img src="' + web_cam_data + '"/>';
        });
    }
    </script>

    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>