<?php 
	require 'class/sqli.php';
?>

<div class="content flex-row-fluid" id="kt_content">
    <!--begin::Row-->
    <div class="row gy-5 g-xl-8">

        <div class="card">
                <!--begin::Card body-->
                <div class="card-body p-0">
                    <!--begin::Wrapper-->
                    <div class="card-px text-center py-20 my-10">
                        <!--begin::Title-->
                        <h2 class="fs-2x fw-bolder mb-10">Bienvenido!</h2>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <p class="text-gray-400 fs-4 fw-bold mb-10">
                                <?php echo $user['nombre']; ?>
                        <br> Asegurate de Escanear el Tracking Number y Tomar Las Fotos del Empaque y de Small Parts </p>
                        <!--end::Description-->
                        <!--begin::Action-->
                        <a href="solicitud.php" class="btn btn-primary">Agregar Evidencia</a>
                        <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    
                    <!--begin::Illustration-->
                    <div class="text-center px-4">
                        
                        <img class="mw-100 mh-300px" alt="" src="view/assets/media/illustrations/sigma-1/2.png">
                    </div>
                    <!--end::Illustration-->
                </div>
                <!--end::Card body-->
        </div>
    
    </div>
    <!--end::Row-->
</div>