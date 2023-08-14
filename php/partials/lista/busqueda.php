<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the tracking number from the form submission
    $trackingNumber = $_POST['trackingNumber'];

    // Redirect to the same page with the tracking number as a query parameter

    require 'class/sqli.php';
    // Form submitted, process the data
    $trackingNumber = $_POST['trackingNumber'];
    $query = "SELECT carros.*, users.* 
          FROM carros 
          INNER JOIN users ON carros.fk_user = users.id_user 
          WHERE carros.tracking = '$trackingNumber'";
    // Execute the query
    $result = $conexion->query($query);
    if ($result->num_rows > 0) {
        $tracking = $result->fetch_assoc();
    } else {
        $_SESSION['mensaje'] = 'TRACKING NO ENCONTRADO';
        $_SESSION['tracking'] = 'Intenta de nuevo';
        $_SESSION['color'] = 'danger';
        $tracking = array(
            'tracking' => 'No Encontrado',
            'empaque' => '',
            'parts' => '',
            'creacion' => '',
            'nombre' => 'User',
            'apellidos' => '',
            'username' => '',
        );
    }
}else{
    $tracking_id = ''; // Dejar el ID vacío para la inserción de nuevos datos
    $tracking = array(
        'tracking' => 'No Encontrado',
        'empaque' => '',
        'parts' => '',
        'creacion' => '',
        'nombre' => 'User',
        'apellidos' => '',
        'username' => '',
    );
}


?>    <!--begin::Post-->

<div id="capture-container">
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">

        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Navbar-->
            <div class="card">
                <div class="card-body pt-9 pb-0">
                    <!-- !! CHECK IF ERROR -->
                <?php if(isset($_SESSION['mensaje'])){?>
                    <div class="alert alert-dismissible bg-light-<?echo $_SESSION['color'] ?> border border-<?echo $_SESSION['color'] ?> border-3 border-dashed d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                            <!--begin::Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen007.svg-->
                            <span class="svg-icon svg-icon-2hx svg-icon-<?echo $_SESSION['color'] ?> me-4 mb-5 mb-sm-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z" fill="black"></path>
                                    <path d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.6 8.7 5.6 11 5.1V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V5.1C15.3 5.6 17 7.6 17 10V13C17 14.1 17.9 15 19 15ZM11 10C11 9.4 11.4 9 12 9C12.6 9 13 8.6 13 8C13 7.4 12.6 7 12 7C10.3 7 9 8.3 9 10C9 10.6 9.4 11 10 11C10.6 11 11 10.6 11 10Z" fill="black"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--end::Icon-->
                            <!--begin::Content-->
                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                <h5 class="mb-1">" <?echo $_SESSION['mensaje'];?> "</h5>
                                <span>
                                </span>
                            </div>
                            <!--end::Content-->
                            <!--begin::Close-->
                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                <i class="bi bi-x fs-1 text-<?echo $_SESSION['color'] ?>"></i>
                            </button>
                            <!--end::Close-->
                    </div>
                <?php unset($_SESSION['mensaje'],$_SESSION['tracking'],$_SESSION['color']); }?>

                <button id="capture-btn">Capturar pantalla</button>
                    <!--begin::Details-->
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                        <!--begin::Image-->
                        <div
                            class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                            <img class="mw-50px mw-lg-125px" src="view/assets/media/logos/katzkin-logo.png" alt="image">
                        </div>
                        <!--end::Image-->
                        <!--begin::Wrapper-->
                        <div class="flex-grow-1">
                            <!--begin::Head-->
                            
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::Details-->
                                <div class="d-flex flex-column">
                                    <!--begin::Status-->
                                    <div class="d-flex align-items-center mb-1">
                                        <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3">Tracking Number:</a>
                                        <span class="badge badge-light-success me-auto"><?echo $tracking['tracking'];?></span>
                                    </div>
                                    <!--end::Status-->
                                    <!--begin::Description-->
                                    <div class="d-flex flex-wrap fw-bold mb-4 fs-5 text-gray-400">Aqui se muestra la evidencia del Tracking Number</div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Details-->
                                <!--begin::Actions-->
                                <!--begin::Controls-->
                                <form method="POST" action="">
                                    <div class="d-flex my-2">
                                        <!--begin::Search-->
                                        <div class="d-flex align-items-center position-relative me-4">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                            <span class="svg-icon svg-icon-3 position-absolute ms-3 mt-n1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                                        transform="rotate(45 17.0365 15.1223)" fill="black"></rect>
                                                    <path
                                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                        fill="black"></path>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <input type="text" name="trackingNumber"
                                                class="form-control form-control-sm form-control-solid bg-body fw-bold fs-7 w-220px ps-9"
                                                placeholder="Buscar Tracking Number">
                                        </div>
                                        <!--end::Search-->
                                        <button type="submit" class="btn btn-success btn-sm fw-bolder">Buscar Tracking Number</button>
                                    </div>
                                </form>
                                <!--end::Controls-->
                                <!--end::Actions-->
                            </div>
                            <!--end::Head-->
                            <!--begin::Info-->
                            <div class="d-flex flex-wrap justify-content-start">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <div class="fs-4 fw-bolder"><? echo $formattedFecha = date('M-d-Y H:i', strtotime( $tracking['creacion']));?></div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-6 text-gray-400">Fecha de Empaque</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                            <span class="svg-icon svg-icon-3 svg-icon-danger me-2">
                                                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1"
                                                        transform="rotate(-90 11 18)" fill="black"></rect>
                                                    <path
                                                        d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z"
                                                        fill="black"></path>
                                                </svg> -->
                                            </span>
                                            <!--end::Svg Icon-->
                                            <div class="fs-4 fw-bolder counted" data-kt-countup="true"
                                                data-kt-countup-value="75"><? echo $tracking['username'] ?></div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-6 text-gray-400">Inspector</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                    <!--begin::Stat-->
                                    <div
                                        class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
                                            <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                                                        transform="rotate(90 13 6)" fill="black"></rect>
                                                    <path
                                                        d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                        fill="black"></path>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <div class="fs-4 fw-bolder counted" data-kt-countup="true"
                                                data-kt-countup-value="15000" data-kt-countup-prefix="$"><? echo $tracking['tracking']?></div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-6 text-gray-400">Tacking Number</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                </div>
                                <!--end::Stats-->
                                <!--begin::Users-->
                                <div class="symbol-group symbol-hover mb-3">
                                    <!--begin::User-->
                                    <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="<?echo $tracking['nombre'].' '.$tracking['apellidos']?>">
                                        <span class="symbol-label bg-info text-inverse-info fw-bolder"><? echo $tracking['nombre'][0] ?></span>
                                    </div>
                                    <!--end::User-->
                                </div>
                                <!--end::Users-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Details-->
                    <div class="separator"></div>
                    <!--begin::Nav wrapper-->

                    <!--end::Nav wrapper-->
                </div>
            </div>
            <!--end::Navbar-->
            <!--begin::Toolbar-->
            <div class="d-flex flex-wrap flex-stack my-5">
                <!--begin::Heading-->
                <h3 class="fw-bolder my-2">Evidencia Tracking Number
                    <span class="fs-6 text-gray-400 fw-bold ms-1"><? echo $tracking['tracking']?></span>
                </h3>
                <!--end::Heading-->

            </div>
            <!--end::Toolbar-->
            <!--begin::Row-->
            <div class="row g-8 row-cols-2 row-cols-lg-2">
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Overlay-->
                        <a target="_blank" class="d-block overlay" data-fslightbox="lightbox-hot-sales" href="<?echo $tracking['empaque'] ?>">
                            <!--begin::Image-->
                            <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-350px" style="background-image:url('<?echo $tracking['empaque'] ?>')"></div>
                            <!--end::Image-->
                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                <i class="bi bi-eye-fill fs-2x text-white"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                        <!--begin::Overlay-->
                        <a target="_blank" class="d-block overlay" data-fslightbox="lightbox-hot-sales" href="<?echo $tracking['parts'] ?>">
                            <!--begin::Image-->
                            <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-350px" style="background-image:url('<?echo $tracking['parts'] ?>')"></div>
                            <!--end::Image-->
                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                <i class="bi bi-eye-fill fs-2x text-white"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                    </div>
                    <!--end::Col-->
            </div>

            <!--end:Row-->
        </div>
        <!--end::Post-->
    </div>
</div>

<script>
  // Obtener el botón de captura
  var captureBtn = document.getElementById('capture-btn');
  
  // Agregar un evento click al botón de captura
  captureBtn.addEventListener('click', function() {
    // Obtener el contenedor que deseas capturar
    var captureContainer = document.getElementById('capture-container');

    // Capturar la parte de la página y generar una imagen
    html2canvas(captureContainer).then(function(canvas) {
      // Crear un enlace temporal para descargar la imagen
      var link = document.createElement('a');
      link.href = canvas.toDataURL('image/jpeg'); // Convertir el canvas a formato JPEG
      link.download = 'captura.jpg'; // Nombre del archivo a descargar
      
      // Simular un clic en el enlace para iniciar la descarga
      link.click();
    });
  });
</script>
