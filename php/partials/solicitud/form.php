<?php
$host = '192.168.0.194:5100';
$usuario = 'ucid';
$contraseña = '1974';
$base_de_datos = 'ktz';
// Conexión a la base de datos
$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);
// Verificar la conexión
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}
if (isset($_GET['id'])) {
    $solicitud_id = $_GET['id'];
    // Obtener los datos existentes de la solicitud de la tabla "solicitudes"
    $query_solicitud = "SELECT * FROM solicitudes WHERE id = '$solicitud_id'";
    $resultado_solicitud = $conexion->query($query_solicitud);

    if ($resultado_solicitud->num_rows > 0) {
        $solicitud = $resultado_solicitud->fetch_assoc();
        // Obtener los datos existentes de los artículos relacionados con la solicitud
        $query_articulos = "SELECT * FROM articulos WHERE solicitud_id = '$solicitud_id'";
        $resultado_articulos = $conexion->query($query_articulos);
    } else {
        echo 'Solicitud no encontrada.';
        exit;
    }
} else {
    $solicitud_id = ''; // Dejar el ID vacío para la inserción de nuevos datos
    $solicitud = array(
        'proveedor' => '',
        'departamento' => '',
        'proyecto' => '',
        'solicitante' => '',
        'notas' => ''
    );
    $resultado_articulos = false; // No hay artículos existentes
}
// Check if the 'tipo' parameter exists in the URL
if (isset($_GET['tipo'])) {
    // Get the value of 'tipo' parameter
    $tipo = $_GET['tipo'];

    // Compare the value of 'tipo' and perform actions accordingly
    if ($tipo === 'op') {
        // If 'tipo' is 'op'
        $tipo_solicitud = 'Nacional';
        // Perform additional actions for 'op'
    } elseif ($tipo === 'tj') {
        // If 'tipo' is 'tj'
        $tipo_solicitud = 'Internacional';
        // Perform additional actions for 'tj'
    } else {
        // If 'tipo' has a value other than 'op' or 'tj'
        echo "Invalid tipo value. Printing a default message...";
        // Perform actions for other values of 'tipo' if needed
    }
} else {
    // If 'tipo' parameter is not present in the URL
    echo "Tipo parameter is missing. Printing a default message...";
    // Perform actions when 'tipo' is missing
}

?>

           
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
<?php if(isset($_SESSION['message'])){?>
    echo 'mesameiea';
<?php unset($_SESSION['message'],$_SESSION['message_type']); }?>
    <!--begin::Aside-->
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
                    <!--begin::Card-->
                    <div class="card">
                        <!--begin::Card body-->
                        <div class="card-body p-12">
                            <!--begin::Form-->
                            <form action="<?php echo isset($_GET['id']) ? 'functions/solicitud/editar_solicitud.php?tipo=' .$tipo. '&id=' . $_GET['id']  : 'functions/solicitud/guardar_solicitud.php'; ?>" method="POST" id="kt_invoice_form">
                                <div class="d-flex flex-column align-items-start flex-xxl-row">
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" title="Enter invoice number">
                                        <span class="fs-2x fw-bolder text-gray-800">Solicitud
                                            <?php echo $tipo_solicitud; ?> #</span>
                                        <span class="fs-2x fw-bolder text-gray-800">FOLIO</span>
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
                                                class="required form-label fs-6 fw-bolder text-gray-700 mb-3">Proveedor</label>
                                            <!--begin::Input group-->
                                            <div class="mb-5">
                                                <input type="text" class="form-control form-control-solid"
                                                    placeholder="Proveedor" name="proveedor"
                                                    value="<?php echo $solicitud['proveedor']; ?>" />
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <label
                                                class="required form-label fs-6 fw-bolder text-gray-700 mb-3">Departamento</label>
                                            <div class="mb-5">
                                                <input type="text" class="form-control form-control-solid"
                                                    placeholder="Departamento" name="departamento"
                                                    value="<?php echo $solicitud['departamento']; ?>" />
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <label
                                                class="required form-label fs-6 fw-bolder text-gray-700 mb-3">Proyecto</label>
                                            <div class="mb-5">
                                                <input class="form-control form-control-solid" rows="3"
                                                    placeholder="Nombre del Proyecto" name="proyecto"
                                                    value="<?php echo $solicitud['proyecto']; ?>">
                                            </div>
                                        </div>
                                        <!--end::Col-->
                                    </div>


                                    
                                    <?php
                                    if ($resultado_articulos) {
                                        while ($articulo = $resultado_articulos->fetch_assoc()) {
                                    ?>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="articulo" data-articulo-id="<?echo $articulo['id']?>">

                                        <div class="row gx-10 mb-5">
                                            <a
                                                class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary mb-9"><?php echo $articulo['numero_parte'] ?></a>

                                            <!-- Campos existentes de los artículos -->
                                            <!-- <h3 class="card-title mb-9">Articulo</h3> -->
                                            <div class="col-lg-6">
                                                <div class="mb-9">

                                                    <label for="articulo_numero_parte"
                                                        class="required form-label">Número de Parte:</label>
                                                    <input type="text" class="form-control form-control-solid"
                                                        placeholder="Número de Parte" name="articulo_numero_parte[]"
                                                        value="<?php echo $articulo['numero_parte']; ?>" required />
                                                </div>
                                                <div class="mb-9">
                                                    <label for="articulo_descripcion"
                                                        class="required form-label">Descripcion:</label>
                                                    <input type="text" class="form-control form-control-solid"
                                                        placeholder="descripcion" name="articulo_descripcion[]"
                                                        value="<?php echo $articulo['descripcion']; ?>" required />
                                                </div>
                                                <div class="mb-9">
                                                    <label for="articulo_unidad"
                                                        class="required form-label">unidad:</label>
                                                    <input type="text" class="form-control form-control-solid"
                                                        placeholder="unidad" name="articulo_unidad[]"
                                                        value="<?php echo $articulo['unidad']; ?>" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-9">
                                                    <label for="articulo_cantidad"
                                                        class="required form-label">Cantidad</label>
                                                    <input type="text" class="form-control form-control-solid"
                                                        name="articulo_cantidad[]"
                                                        value="<?php echo $articulo['cantidad']; ?>" required />
                                                </div>
                                                <div class="mb-19">
                                                    <label for="articulo_precio"
                                                        class="required form-label">Precio:</label>
                                                    <input type="text" class="form-control form-control-solid"
                                                        name="articulo_precio[]"
                                                        value="<?php echo $articulo['precio']; ?>" required />
                                                </div>
                                                <div class="mb-9">
                                                    <!-- <label for="exampleFormControlInput1" class="required form-label">unidad:</label> -->
                                                    <a
                                                        class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info">
                                                        <span class="subtotal">Subtotal: $<span
                                                                class="subtotal-value"></span></span>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                        <button type="button" class="eliminar-articulo btn btn-light-danger" data-articulo-id="<?php echo $articulo['id']; ?>"> Eliminar Artículo</button>
                                        <!-- <a href="#" class="btn btn-light-danger">Danger</a> -->
                                        <!-- 
                                    <label for="articulo_numero_parte">Número de Parte:</label>
                                    <input type="text" name="articulo_numero_parte[]" value="<?php echo $articulo['numero_parte']; ?>">
                                    <label for="articulo_descripcion">Descripción:</label>
                                    <textarea name="articulo_descripcion[]"><?php echo $articulo['descripcion']; ?></textarea>
                                    <label for="articulo_unidad">Unidad:</label>
                                    <input type="text" name="articulo_unidad[]" value="<?php echo $articulo['unidad']; ?>">
                                    <label for="articulo_cantidad">Cantidad:</label>
                                    <input type="text" name="articulo_cantidad[]" value="<?php echo $articulo['cantidad']; ?>">
                                    <label for="articulo_precio">Precio:</label>
                                    <input type="text" name="articulo_precio[]" value="<?php echo $articulo['precio']; ?>">
                                    <span class="subtotal">Subtotal: $<span class="subtotal-value"></span></span>
                                    <button type="button" class="eliminar-articulo">Eliminar Artículo</button> -->
                                    </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <div class="separator separator-dashed my-10"></div>

                                    <!--end::Row-->
                                    <tfoot>
                                        <tr class="border-top border-top-dashed align-top fs-6 fw-bolder text-gray-700">
                                            <th class="text-primary">
                                                <button type="button" class="btn btn-link py-1"
                                                    id="agregar-articulo">Agregar Artículo</button>
                                            </th>
                                        </tr>
                                        <tr class="align-top fw-bolder text-gray-700">
                                            <th></th>
                                            <th colspan="2" class="fs-4 ps-0">
                                            </th>
                                            <th colspan="2" class="text-end fs-4 text-nowrap">
                                                <!-- <span class="total">Total: $<span class="total-value"></span></span> -->
                                            </th>
                                        </tr>
                                    </tfoot>
                                    <!--end::Table-->
                                    <!--begin::Item template-->

                                    <!--end::Item template-->
                                    <!--begin::Notes-->
                                    <div class="mb-0">
                                        <label class="form-label fs-6 fw-bolder text-gray-700"><?php
                                            if ($tipo == 'tj' ){
                                                echo 'Shipping Address';
                                                $place_notes = 'Dirección de Envío';
                                            }else{
                                                echo 'Motivo';
                                                $place_notes = 'Ingresa un Motivo';
                                            }
                                        ?></label>
                                        <textarea name="notas" class="form-control form-control-solid" rows="3"
                                            placeholder="<? echo $place_notes; ?>"><?php echo $solicitud['notas']; ?></textarea>
                                    </div>
                                    <!--end::Notes-->
                                </div>
                                <!--end::Wrapper-->
                                <!--end::Form-->
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
                                    <input type="hidden" name="solicitante" value="<?php echo $user['id_user']; ?>">
                                    <input type="hidden" name="fk_rol" value="<?php echo $user['fk_rol']; ?>">
                                    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-8"></div>
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder fs-6 text-gray-700">Tipo de Cambio</label>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select name="currency" aria-label="Select a Timezone" data-control="select2"
                                    data-placeholder="Selecciona" class="form-select form-select-solid">
                                    <? if ($tipo == 'tj'){
                                                                                                    echo '<option data-kt-flag="flags/united-states.svg" value="USD">
                                                                                                        <b>USD</b>&#160;-&#160;USA dollar
                                                                                                    </option>    ';
                                                                                                }else{
                                                                                                    echo '
                                                                                                <option data-kt-flag="flags/united-kingdom.svg" value="MXN">
                                                                                                    <b>MXN</b>&#160;-&#160;Peso Mexicano
                                                                                                </option>
                                                                                                <option data-kt-flag="flags/united-states.svg" value="USD">
                                                                                                    <b>USD</b>&#160;-&#160;USA dollar
                                                                                                </option>
                                                                                                    ';
                                                                                                } ?>

                                </select>
                                <!--end::Select-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed mb-8"></div>
                            <!--end::Separator-->
                            <!--begin::Input group-->
                            <div class="mb-8">
                                <label
                                    class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                    <span class="form-check-label ms-0 fw-bolder fs-6 text-gray-700">Urgente</span>
                                    <!-- <input class="form-check-input" type="checkbox" value="" /> -->
                                    <input class="form-check-input" type="checkbox" name="urgente" value="1" />
                                </label>
                                <!-- <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                                                                    <div class="text-inverse-success bg-success">
                                                                        <span class="total ">Total: $<span class="total-value"></span></span>
                                                                    </div>    
                                                                </label> -->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Separator-->
                            <div class="separator separator-dashed mb-8"></div>
                            <!--end::Separator-->
                            <!--begin::Actions-->
                            <div class="mb-0">
                                <!--begin::Row-->
                                <div class="row mb-5">

                                    <a
                                        class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success">
                                        <span class="total ">Total: $<span class="total-value"></span></span>
                                    </a>


                                </div>
                                <!--end::Row-->
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

<script>
document.getElementById("agregar-articulo").addEventListener("click", function() {
    var articuloDiv = document.createElement("div");
    articuloDiv.className = "articulo";
    var html = `
        <div class="row gx-10 mb-9">
            <a class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary mb-9">Articulo +</a>
                        <!-- Campos existentes de los artículos -->
                            <div class="col-lg-6">
                                <div class="mb-9">
                                    <label for="articulo_numero_parte" class="required form-label">Número de Parte:</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Número de Parte"
                                    name="articulo_numero_parte[]" required/>
                                </div>
                                <div class="mb-9">
                                    <label for="articulo_descripcion" class="required form-label">Descripcion:</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="descripcion"
                                    name="articulo_descripcion[]" required/>
                                </div>
                                <div class="mb-9">
                                    <label for="articulo_unidad" class="required form-label">unidad:</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="unidad"
                                    name="articulo_unidad[]" required/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-9">
                                    <label for="articulo_cantidad" class="required form-label">Cantidad:</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="cantidad"
                                    name="articulo_cantidad[]" required/>
                                </div>
                                <div class="mb-19">
                                    <label for="for="articulo_precio" " class="required form-label">Precio:</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="precio"
                                    name="articulo_precio[]" required/>
                                </div>
                                <div class="mb-9">
                                    <a class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info">
                                        <span class="subtotal">Subtotal: $<span class="subtotal-value"></span></span>
                                    </a>
                                </div>
                                <div class="mb-9">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="eliminar-articulo btn btn-light-danger">Eliminar Artículo</button>

            </div>
            <div class="separator separator-dashed my-10"></div>

            `;
    articuloDiv.innerHTML = html;

    document.getElementById("agregar-articulo").before(articuloDiv);
});
document.addEventListener("click", function(event) {
    if (event.target.classList.contains("eliminar-articulo")) {
        event.target.parentNode.remove();
    }
});

function calcularSubtotal(articuloDiv) {
    var cantidad = parseFloat(articuloDiv.querySelector('input[name="articulo_cantidad[]"]').value);
    var precio = parseFloat(articuloDiv.querySelector('input[name="articulo_precio[]"]').value);
    var subtotal = cantidad * precio;

    articuloDiv.querySelector('.subtotal-value').textContent = subtotal.toFixed(2);
}
// Función para recalcular los subtotales de todos los artículos
function recalcularSubtotales() {
    var articulos = document.getElementsByClassName('articulo');
    for (var i = 0; i < articulos.length; i++) {
        calcularSubtotal(articulos[i]);
    }
}
// Evento para calcular el subtotal al cargar la página
window.addEventListener('load', function() {
    recalcularSubtotales();
});
// Evento para calcular el subtotal al cambiar la cantidad o el precio
document.addEventListener('change', function(event) {
    if (
        event.target.name === 'articulo_cantidad[]' ||
        event.target.name === 'articulo_precio[]'
    ) {
        var articuloDiv = event.target.closest('.articulo');
        calcularSubtotal(articuloDiv);
    }
});

function calcularTotal() {
    var subtotales = document.getElementsByClassName('subtotal-value');
    var total = 0;
    for (var i = 0; i < subtotales.length; i++) {
        total += parseFloat(subtotales[i].textContent);
    }
    document.querySelector('.total-value').textContent = total.toFixed(2);
}
// Evento para calcular el total al cargar la página
window.addEventListener('load', function() {
    calcularTotal();
});
// Evento para recalcular el total al cambiar cualquier cantidad o precio
document.addEventListener('change', function(event) {
    if (
        event.target.name === 'articulo_cantidad[]' ||
        event.target.name === 'articulo_precio[]'
    ) {
        calcularTotal();
    }
});
</script>
