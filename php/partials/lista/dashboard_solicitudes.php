<?php
    require_once  'class/lista/perpage.php';
    require_once  'class/lista/list.php';
    require_once  'class/sqli.php';
    setlocale(LC_TIME, 'es_ES.UTF-8');
    $database = new DataSource();
    $name = "";
    $code = "";
    $date = "";
    $inicio = "";
    $status = "";
    $fin = "";
    $queryCondition = "";

    if (! empty($_POST["search"])) {
        foreach ($_POST["search"] as $k => $v) {
            if (! empty($v)) {

                $queryCases = array(
                    "name",
                    "code",
                    "date",
                    "status"
                );
                if (in_array($k, $queryCases)) {
                    if (! empty($queryCondition)) {
                        $queryCondition .= " AND ";
                    } else {
                        $queryCondition .= " WHERE ";
                    }
                }
                switch ($k) {
                    case "name":
                        $name = $v;
                        $queryCondition .= "solicitudes.proyecto LIKE '" . $v . "%'";
                        break;
                    case "code":
                        $code = $v;
                        $queryCondition .= "solicitudes.departamento LIKE '" . $v . "%'";
                        break;
                    case "status":
                        $status = $v;
                        if($status=='nuevo'){
                            $queryCondition .= "solicitudes.status = 0";
                        }elseif($status=='todos'){
                            $queryCondition .= "solicitudes.status IN (0, 1, 2, 3, 4)";
                        }elseif($status=='revision'){
                            $queryCondition .= "solicitudes.status = 1";
                        }elseif($status=='aprobado'){
                            $queryCondition .= "solicitudes.status = 2";
                        }elseif($status='rechazado'){
                            $queryCondition .= "solicitudes.status = 3";
                        }   
                        
                        break;
                    case "date":
                        $date = $v;
                        $fechaActual = date("Y-m-d");
                        if($v == 1){
                            $inicio = date("Y-m-d");
                        }elseif($v == 2){
                            $inicio =  date("Y-m-d", strtotime("-7 days", strtotime($fechaActual)));
                        }elseif($v == 3){
                            $inicio = date("Y-m-d", strtotime("-1 month", strtotime($fechaActual)));
                        }elseif($v == 4){
                            $inicio = date("Y-m-d", strtotime("-1 year", strtotime($fechaActual))); 
                        }
                        $fin = $fechaActual;
                        $queryCondition .= "DATE(solicitudes.creacion) BETWEEN  '". $inicio ."' AND '". $fin . "'";
                        break;

                }
            }
        }
    }
    $orderby = " ORDER BY id desc";
    $sql = "SELECT solicitudes.*, users.nombre, users.apellidos, users.email
    FROM solicitudes
    INNER JOIN users ON solicitudes.fk_user = users.id_user". $queryCondition;
    $href = 'lista.php';

    $perPage = 8;
    $page = 1;
    if (isset($_POST['page'])) {
        $page = $_POST['page'];
    }
    $start = ($page - 1) * $perPage;
    if ($start < 0)
        $start = 0;

    $query = $sql . $orderby . " limit " . $start . "," . $perPage;
    $result = $database->select($query);

    if (! empty($result)) {
        $result["perpage"] = showperpage($sql, $perPage, $href);
    }

    $query_solicitudes = "SELECT COUNT(*) AS total_solicitudes FROM solicitudes WHERE DATE(creacion) = CURDATE() AND status = 0; ";
    $result_solicitudes = mysqli_query($conexion, $query_solicitudes);

    // Obtener el resultado
    $row_solicitudes = mysqli_fetch_assoc($result_solicitudes);
    $totalSolicitudes = $row_solicitudes['total_solicitudes'];

    $query_gasto_mensual = "SELECT SUM(a.cantidad * a.precio) AS total_sum
        FROM articulos a
        JOIN solicitudes s ON a.solicitud_id = s.id
        WHERE s.status = 2  
        AND YEAR(s.creacion) = YEAR(CURDATE())
        AND MONTH(s.creacion) = MONTH(CURDATE());";
    $result_gasto_mensaual = mysqli_query($conexion, $query_gasto_mensual);
    if($result_gasto_mensaual){
        $row_gasto_mensual = mysqli_fetch_assoc($result_gasto_mensaual);
        $gasto_mensual = $row_gasto_mensual['total_sum'];
    }

    // Mostrar el resultado
    // echo "Total de solicitudes: " . $totalSolicitudes;

?>
<!--end::Toolbar-->
<!--begin::Container-->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Navbar-->
        <div class="card mb-8">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    <!--begin::Image-->
                    <div
                        class="d-flex flex-center flex-shrink-0 bg-light rounded w-100px h-100px w-lg-150px h-lg-150px me-7 mb-4">
                        <img class="mw-50px mw-lg-125px" src="view/assets/media/logos/katzkin.png" alt="image" />
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
                                    <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3">
                                        Resumen de las solicitudes de compra</a>
                                    <span class="badge badge-light-success me-auto">Nuevas</span>
                                </div>
                                <!--end::Status-->
                                <!--begin::Description-->
                                <div class="d-flex flex-wrap fw-bold mb-4 fs-5 text-gray-400">
                                    Revisa el desempeño de las solicitudes de compra
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Details-->
                            <!--begin::Actions-->
                            <div class="d-flex mb-4">
                                <a href="#" class="btn btn-sm btn-bg-light btn-active-color-primary me-3"
                                    data-bs-toggle="modal" data-bs-target="#kt_modal_users_search">Add
                                    User</a>
                                <a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_new_target">Add Target</a>
                                <!--begin::Menu-->
                                <div class="me-0">
                                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="bi bi-three-dots fs-3"></i>
                                    </button>
                                    <!--begin::Menu 3-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3"
                                        data-kt-menu="true">
                                        <!--begin::Heading-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                Payments</div>
                                        </div>
                                        <!--end::Heading-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">Create Invoice</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link flex-stack px-3">Create Payment
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                                    title="Specify a target name for future usage and reference"></i></a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">Generate Bill</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-end">
                                            <a href="#" class="menu-link px-3">
                                                <span class="menu-title">Subscription</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">Plans</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">Billing</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">Statements</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu separator-->
                                                <div class="separator my-2"></div>
                                                <!--end::Menu separator-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <div class="menu-content px-3">
                                                        <!--begin::Switch-->
                                                        <label
                                                            class="form-check form-switch form-check-custom form-check-solid">
                                                            <!--begin::Input-->
                                                            <input class="form-check-input w-30px h-20px"
                                                                type="checkbox" value="1" checked="checked"
                                                                name="notifications" />
                                                            <!--end::Input-->
                                                            <!--end::Label-->
                                                            <span
                                                                class="form-check-label text-muted fs-6">Recuring</span>
                                                            <!--end::Label-->
                                                        </label>
                                                        <!--end::Switch-->
                                                    </div>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3 my-1">
                                            <a href="#" class="menu-link px-3">Settings</a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu 3-->
                                </div>
                                <!--end::Menu-->
                            </div>
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
                                        <div class="fs-4 fw-bolder"><?php echo date("d-M-Y")?></div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-bold fs-6 text-gray-400">Hoy</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Stat-->
                                <!--begin::Stat-->
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <!--begin::Number-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                                                    transform="rotate(90 13 6)" fill="black" />
                                                <path
                                                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <div class="fs-4 fw-bolder" data-kt-countup="true"
                                            data-kt-countup-value="<?php echo $totalSolicitudes ?>">0</div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-bold fs-6 text-gray-400">Solicitudes Nuevas </div>
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
                                                    transform="rotate(90 13 6)" fill="black" />
                                                <path
                                                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <div class="fs-4 fw-bolder" data-kt-countup="true"
                                            data-kt-countup-value="<?php echo $gasto_mensual; ?>"
                                            data-kt-countup-prefix="$">0</div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-bold fs-6 text-gray-400">Gastos de este Mes (MXN)</div>
                                    <!--end::Label-->
                                </div>
                                <div
                                    class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <!--begin::Number-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1"
                                                    transform="rotate(90 13 6)" fill="black" />
                                                <path
                                                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <div class="fs-4 fw-bolder" data-kt-countup="true"
                                            data-kt-countup-value="<?php echo $gasto_mensual; ?>"
                                            data-kt-countup-prefix="$">0</div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-bold fs-6 text-gray-400">Gastos de este Mes (USD) </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Stat-->
                            </div>
                            <!--end::Stats-->
                            <!--begin::Users-->

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

    </div>
    <!--end::Post-->
</div>
<!-- ** LISTA ** -->
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->

                <div class="card-title">
                    <form name="frmSearch" method="post" action="">

                        <!--begin::Search-->

                        <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar justify-content-center">
                    <div class="d-flex justify-content-center mb-1">
                        <input type="text" class="form-control form-control-solid w-120px" placeholder="Proyecto"
                            name="search[name]" value="<?php echo $name; ?>">
                        <input type="text" class="form-control form-control-solid w-120px" placeholder="Departamento"
                            name="search[code]" value="<?php echo $code; ?>">

                    </div>
                    <div class="d-flex justify-content-center mb-1">
                        <select name="search[status]" class="form-select" aria-label="Select example">
                            <option value="<?php echo $status; ?>">Estatus</option>
                            <option value="nuevo">Nuevo</option>
                            <option value="revision">En Revisión</option>
                            <option value="aprobado">Aprobados</option>
                            <option value="rechazado">Rechazados</option>
                            <option value="todos">Todos</option>

                        </select>
                        <select name="search[date]" class="form-select" aria-label="Select example">
                            <option value="<?php echo $date; ?>">Fecha</option>
                            <option value="1">Hoy</option>
                            <option value="2">Ulitma Semana</option>
                            <option value="3">Ultimo Mes</option>
                            <option value="4">Ultimo Año</option>
                            <option value="5">Todos</option>
                        </select>
                    </div>
                </div>

            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <button type="submit" name="go" class="btn btn-primary" value="Filtrar">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                    transform="rotate(-90 11.364 20.364)" fill="black"></rect>
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black"></rect>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->Filtrar
                    </button>
                    <input type="reset" class="btn btn-light-danger me-3" value="Reset"
                        onclick="window.location='lista.php'">
                </div>

                <div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                            id="kt_table_users">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1"
                                        style="width: 27px;" aria-label="">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_table_users .form-check-input" value="1">
                                        </div>
                                    </th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users"
                                        rowspan="1" colspan="1" style="width: 210.233px;"
                                        aria-label="User: activate to sort column ascending">Usuario</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users"
                                        rowspan="1" colspan="1" style="width: 125px;"
                                        aria-label="Role: activate to sort column ascending">Proyecto</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users"
                                        rowspan="1" colspan="1" style="width: 125px;"
                                        aria-label="Last login: activate to sort column ascending">Departamento</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users"
                                        rowspan="1" colspan="1" style="width: 125px;"
                                        aria-label="Two-step: activate to sort column ascending">Estatus</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users"
                                        rowspan="1" colspan="1" style="width: 125px;"
                                        aria-label="Joined Date: activate to sort column ascending">Fecha de Creación
                                    </th>
                                    <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                        style="width: 100px;" aria-label="Actions">Acciones</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-gray-600 fw-bold">
                                <!--begin::Table row-->
                                <?php
                                if (! empty($result)) {
                                    foreach ($result as $key => $value) {
                                        if (is_numeric($key)) {

                                            $claseCSS = '';
                                            $primeraLetra = $result[$key]['nombre'][0];
                                            if (preg_match('/^[A-I]/', $primeraLetra)) {
                                                $claseCSS = 'primary';
                                            } elseif (preg_match('/^[J-P]/', $primeraLetra)) {
                                                $claseCSS = 'warning';
                                            } elseif (preg_match('/^[Q-Z]/', $primeraLetra)) {
                                                $claseCSS = 'success';
                                            }
                                            $departamento = $result[$key]['departamento'];
                                            $primera_letra_departamento = strtoupper(substr($departamento, 0, 1));;
                                            if (preg_match('/^[A-I]/', $primera_letra_departamento)) {
                                                $class_departamento = 'info';
                                            } elseif (preg_match('/^[J-P]/', $primera_letra_departamento)) {
                                                $class_departamento = 'success';
                                            } elseif (preg_match('/^[Q-Z]/', $primera_letra_departamento)) {
                                                $class_departamento = 'primary';
                                            }
                                            $status = $result[$key]['status'];
                                            if ($status == 0) {
                                                $class_status = 'success';
                                                $status_value = 'Nueva';
                                            } elseif ($status == 1) {
                                                $class_status = 'info';
                                                $status_value = 'En Revisión';
                                            } elseif ($status == 2) {
                                                $class_status = 'primary';
                                                $status_value = 'Aprobada';
                                            } elseif ($status == 3) {
                                                $class_status = 'danger';
                                                $status_value = 'Rechazada';
                                            }
                                            ?>
                                <tr class="odd">
                                    <!--begin::Checkbox-->
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="1">
                                        </div>
                                    </td>
                                    <!--end::Checkbox-->
                                    <!--begin::User=-->
                                    <td class="d-flex align-items-center">
                                        <!--begin:: Avatar -->
                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                            <a href="../../demo2/dist/apps/user-management/users/view.html">
                                                <div
                                                    class="symbol-label fs-3 bg-light-<?php echo $claseCSS; ?> text-<?php echo $claseCSS; ?>">
                                                    <?php echo $result[$key]['nombre'][0]; ?></div>
                                            </a>
                                        </div>
                                        <!--end::Avatar-->
                                        <!--begin::User details-->
                                        <div class="d-flex flex-column">
                                            <a href="../../demo2/dist/apps/user-management/users/view.html"
                                                class="text-gray-800 text-hover-primary mb-1">
                                                <? echo  $result[$key]['nombre'].' '.$result[$key]['apellidos']; ?>
                                            </a>
                                            <span><?php echo $result[$key]['email'] ?></span>
                                        </div>
                                        <!--begin::User details-->
                                    </td>
                                    <!--end::User=-->
                                    <!--begin::Role=-->
                                    <td>
                                        <? echo $result[$key]['proyecto']?>
                                    </td>
                                    <!--end::Role=-->
                                    <!--begin::Last login=-->
                                    <td data-order="2023-05-23T11:10:48-07:00">
                                        <div class="badge badge-light-<? echo $class_departamento; ?> fw-bolder">
                                            <? echo $departamento ?>
                                        </div>
                                    </td>
                                    <!--end::Last login=-->
                                    <!--begin::Two step=-->
                                    <td>
                                        <div class="badge badge-light-<?echo $class_status; ?> fw-bolder">
                                            <? echo $status_value; ?>
                                        </div>
                                    </td>
                                    <!--end::Two step=-->
                                    <!--begin::Joined-->
                                    <td data-order="2021-06-20T18:05:00-07:00"><?php
                                                    $fechaHora = $result[$key]['creacion'];
                                                    $fecha = new DateTime($fechaHora);
                                                    $fechaFormateada = $fecha->format("M - d - Y");
                                                    echo $fechaFormateada; // Imprime: May - 24 - 2023
                                                ?></td>
                                    <!--begin::Joined-->
                                    <!--begin::Action=-->
                                    <td class="text-end">
                                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="black"></path>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <!-- <a href="solicitud.php?tipo=<?php echo $result[$key]['tipo'];?>&id=<?php echo $result[$key]['id']?>"
                                                                class="menu-link px-3">Edit</a> -->
                                                <a href="invoice.php?id=<? echo $result[$key]['id']; ?>"
                                                    class="menu-link px-3">
                                                    Revisar</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3"
                                                    data-kt-users-table-filter="delete_row">Delete</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    <!--end::Action=-->
                                </tr>
                                <?php
                                        }
                                    }
                                } ?>
                            </tbody>
                            <!--end::Table body-->
                        </table>
                    </div>
                    <!-- !!* PAGIANDOR  -->


                    <div class="row">
                        <?php
                                if (isset($result["perpage"])) {
                            ?>
                        <div
                            class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                        </div>
                        <div
                            class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                            <div class="dataTables_paginate paging_simple_numbers" id="kt_subscriptions_table_paginate">
                                <ul class="pagination">
                                    <?php echo $result["perpage"]; ?>
                                </ul>
                            </div>
                        </div>
                        <?php } ?>
                    </div>


                </div>
                <!--end::Table-->
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Post-->
</div>
<!--end::Container-->
<script>
// EVITAR REENVIO DE DATOS.
if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
}
</script>

<script>

</script>