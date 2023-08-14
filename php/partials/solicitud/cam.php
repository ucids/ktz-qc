<?php
require 'class/sqli.php';
if (isset($_GET['id'])) {
    $carro_id = $_GET['id'];
    // Obtener los datos existentes de la solicitud de la tabla "solicitudes"
    $query_carro = "SELECT * FROM carros WHERE id_carro = '$carro_id'";
    $resultado_carro = $conexion->query($query_carro);

    $query_componente = "SELECT * FROM componentes WHERE fk_carro = '$carro_id'";
    $resultado_componente = $conexion->query($query_componente);

    if ($resultado_carro->num_rows > 0) {
        $carro = $resultado_carro->fetch_assoc();
        $componente = $resultado_componente->fetch_assoc();
        // Obtener los datos existentes de los artículos relacionados con la carro
        // $query_articulos = "SELECT * FROM carros WHERE id_carro = '$carro_id'";
        // $resultado_articulos = $conexion->query($query_articulos);
    } else {
        echo 'carro no encontrado.';
        exit;
    }
} else {
    $carro_id = ''; // Dejar el ID vacío para la inserción de nuevos datos
    $carro = array(
        'tracking' => '',
        'empaque' => '',
        'tracking' => '',

    );
    $componente = array(
        'componente' => ''
    );
    $resultado_articulos = false; // No hay artículos existentes
}

?>

<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxxl">
    <!--begin::Aside-->
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">

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
                    <?php unset($_SESSION['mensaje'],$_SESSION['tracking'],$_SESSION['componente']); }?>
                        <!--begin::Card-->
                    <div class="card">
                        <!--begin::Card body-->
                        <div class="card-body p-12">
                            <!--begin::Form-->
                            <form action="functions/solicitud/guardar.php" method="POST" id="kt_invoice_form">
                                <div class="d-flex flex-column align-items-start flex-xxl-row">
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" title="Enter invoice number">
                                        <span class="fs-2x fw-bolder text-gray-800"><? echo 'Tracking Number'?> # </span>
                                        <span class="fs-2x fw-bolder text-gray-800"> <? echo $carro['tracking'];?></span>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Top-->
                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-10"></div>
                                <!--end::Separator-->
                                <!--begin::Wrapper-->
                                <div class="mb-0">
                                    <!--begin::Row-->
                                    <!-- //!  DATOS DE LA SOLICITUD -->
                                    <div class="row gx-10 mb-5">
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <label
                                                class="required form-label fs-6 fw-bolder text-gray-700 mb-3">Tracking
                                                Number</label>
                                            <!--begin::Input group-->
                                            <div class="mb-5">
                                                <input type="text" class="form-control form-control-solid"
                                                    placeholder="Tracking Number" name="tracking"
                                                    value="<?php echo $carro['tracking']; ?>" required/>
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <!-- <div class="col-lg-6">
                                            <label class="required form-label fs-6 fw-bolder text-gray-700 mb-3">Estacion</label>
                                            <div class="mb-5">
                                                <input type="text" class="form-control form-control-solid"
                                                    placeholder="Estacion" name="estacion"
                                                    value="<?php echo $carro['estacion']; ?>" required/>
                                            </div>
                                        </div> -->
                                        <div class="col-lg-6">
                                            <label class="required form-label fs-6 fw-bolder text-gray-700 mb-3">Componente</label>
                                            <div class="mb-5">

                                            <select name = "evidencia" class="form-select form-select-solid" data-control="select2" data-hide-search="true">
                                                <?php
                                                    if (empty($carro['empaque'])) {
                                                        echo '<option value="empaque" selected>Empaque</option>';
                                                    }

                                                    if (empty($carro['parts'])) {
                                                        echo '<option value="parts">Small Parts</option>';
                                                    }
                                                ?>
                                            </select>


                                            </div>
                                        </div>
                                        <!--  //! CAMARA -->
                                        <div class="dsp row ">
                                            <div class="dsp">
                                                <div id="web_cam"></div>
                                                    <br>


                                                    <input type="hidden" name="image" class="image-tag">

                                            </div>
                                            <div class="separator separator-dashed my-10"></div>

                                            <div class="dsp">
                                                <div id="response">
                                                <label class="required form-label fs-6 fw-bolder text-gray-700 mb-3">
                                                    Aquí se mostrará la fotografía
                                                </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-center">
                                                <br />
                                                <!-- <button class="btn btn-success">Submit</button> -->
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                </div>
                                <!--end::Wrapper-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
                <!--begin::Sidebar-->
                <div class="flex-lg-auto min-w-lg-300px">
                    <!--begin::Card Lateral-->
                    <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
                        data-kt-sticky-offset="{default: false, lg: '200px'}"
                        data-kt-sticky-width="{lg: '250px', lg: '300px'}" data-kt-sticky-left="auto"
                        data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                        <!--begin::Card body-->
                        <div class="card-body p-10">
                            <div class="mb-10">
                                <div class="fw-bolder d-flex align-items-center fs-5">
                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                fill="#00A3FF"></path>
                                            <path class="permanent"
                                                d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                fill="white"></path>
                                        </svg>
                                    </span>
                                    <?php echo $user['nombre'].' '.$user['apellidos']; ?>
                                    <span
                                        class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2"><?php echo $user['rol']; ?></span>
                                    <input type="hidden" name="user" value="<?php echo $user['id_user']; ?>">
                                    <input type="hidden" name="fk_rol" value="<?php echo $user['fk_rol']; ?>">
                                    <input type="hidden" name="id_carro" value="<?php echo $carro_id; ?>">
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-8"></div>

                            <div class="mb-1">
                                <a type=button class="btn btn-flex btn-info px-15"
                                    onClick="take_snapshot()">
                                    <span class="svg-icon svg-icon-2x"><i
                                            class="las la-camera fs-3x"></i></span>
                                    <span class="d-flex flex-column align-items-start ms-2">
                                        <span class="fs-3 fw-bolder">Capturar</span>
                                    </span>
                                </a>
                            </div>    
                            
                            <div class="separator separator-dashed mb-8"></div>

                            
                            <div class="mb-0">
                                <button type="submit"
                                    value="<?php echo isset($_GET['id']) ? 'Actualizar Solicitud' : 'Insertar Solicitud'; ?>"
                                    class="btn btn-primary w-100">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen016.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M15.43 8.56949L10.744 15.1395C10.6422 15.282 10.5804 15.4492 10.5651 15.6236C10.5498 15.7981 10.5815 15.9734 10.657 16.1315L13.194 21.4425C13.2737 21.6097 13.3991 21.751 13.5557 21.8499C13.7123 21.9488 13.8938 22.0014 14.079 22.0015H14.117C14.3087 21.9941 14.4941 21.9307 14.6502 21.8191C14.8062 21.7075 14.9261 21.5526 14.995 21.3735L21.933 3.33649C22.0011 3.15918 22.0164 2.96594 21.977 2.78013C21.9376 2.59432 21.8452 2.4239 21.711 2.28949L15.43 8.56949Z"
                                                fill="black" />
                                            <path opacity="0.3"
                                                d="M20.664 2.06648L2.62602 9.00148C2.44768 9.07085 2.29348 9.19082 2.1824 9.34663C2.07131 9.50244 2.00818 9.68731 2.00074 9.87853C1.99331 10.0697 2.04189 10.259 2.14054 10.4229C2.23919 10.5869 2.38359 10.7185 2.55601 10.8015L7.86601 13.3365C8.02383 13.4126 8.19925 13.4448 8.37382 13.4297C8.54839 13.4145 8.71565 13.3526 8.85801 13.2505L15.43 8.56548L21.711 2.28448C21.5762 2.15096 21.4055 2.05932 21.2198 2.02064C21.034 1.98196 20.8409 1.99788 20.664 2.06648Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    Enviar Solicitud
                                </button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
            </div>
            <!--end::Layout-->
        </div>

        </form>
    </div>
    <!--end::Aside-->
</div>