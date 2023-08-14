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
    $query_solicitud = "SELECT solicitudes.*, users.*, roles.*
    FROM solicitudes
    INNER JOIN users ON solicitudes.fk_user = users.id_user
    INNER JOIN roles ON users.fk_rol = roles.id_rol
    WHERE solicitudes.id = '$solicitud_id'";
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
        'notas' => '',
        'tipo' => '',
        'divisa' => '',
        'urgente' => ''
    );
    $resultado_articulos = false; // No hay artículos existentes
}

$query_gasto_mensual = "SELECT SUM(a.cantidad * a.precio) AS total_sum
        FROM articulos a
        JOIN solicitudes s ON a.solicitud_id = s.id
        WHERE s.id = $solicitud_id;";
    $result_gasto_mensaual = mysqli_query($conexion, $query_gasto_mensual);
    if($result_gasto_mensaual){
        $row_gasto_mensual = mysqli_fetch_assoc($result_gasto_mensaual);
        $gasto_mensual = $row_gasto_mensual['total_sum'];
    }

    $status = $solicitud['status'];
    if ($status == 0){
        $class_status = 'success';
        $status_value = 'Nueva';
		$query_status = "UPDATE solicitudes SET status = 1 WHERE id = $solicitud_id";
    	$conn->query($query_status);
    }elseif ($status == 1){
        $class_status = 'info';
        $status_value = 'En Revisión';
    }elseif ($status == 2){
        $class_status = 'success';
        $status_value = 'Aprobado';
    }elseif ($status == 3){
        $status_value = 'Rechazado';
        $class_status = 'danger';
    }elseif ($status == 4){
        $status_value = 'Finalizado';
        $class_status = 'primary';
    }
?>

<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
						<!--begin::Post-->
						
						<div class="content flex-row-fluid" id="kt_content">
							
							<!--begin::Invoice 2 main-->
							<div class="card">
								<?php if(isset($_SESSION['message'])){?>
									<div class="alert alert-dismissible bg-light-<?= $_SESSION['message_type']?> d-flex flex-center flex-column py-10 px-10 px-lg-20 mb-10">
										<!--begin::Close-->
										<button type="button"
											class="position-absolute top-0 end-0 m-2 btn btn-icon btn-icon-<?= $_SESSION['message_type']?>"
											data-bs-dismiss="alert">
											<i class="las la-times-circle fs-3x text-danger"></i>
										</button>
										<!--end::Close-->

										<!--begin::Icon-->
										<a href="#" data-bs-dismiss="alert">
											<i class="las la-check-circle fs-5x py-5 text-<?= 
												$_SESSION['message_type']?>"></i>
										</a>

										<!--end::Icon-->

										<!--begin::Wrapper-->
										<div class="text-center">
											<!--begin::Title-->
											<h1 class="fw-bolder mb-5"><?= $_SESSION['message']?></h1>
											<!--end::Title-->

											<!--begin::Separator-->
											<div class="separator separator-dashed border-<?= $_SESSION['message_type']?> opacity-25 mb-5">
											</div>
											<!--end::Separator-->

											<!--begin::Content-->
											<div class="mb-9 text-dark">
												La solicitud se procesó. <strong><? echo $solicitud['email'] ?></strong>.<br />
												Podrá ver la solicitud con el nuevo Status.
											</div>
											<!--end::Content-->

											<!--begin::Buttons-->
											<div class="d-flex flex-center flex-wrap">
												<a href="index.php" class="btn btn-outline btn-outline-<?= $_SESSION['message_type']?> btn-active-<?= $_SESSION['message_type']?> m-2">Continuar</a>
												<!-- <a href="index" data-bs-dismiss="alert" class="btn btn-<?= $_SESSION['message_type']?> m-2">Continuar</a> -->
											</div>
											<!--end::Buttons-->
										</div>
										<!--end::Wrapper-->
									</div>
								<?php unset($_SESSION['message'],$_SESSION['message_type']); }?>
								<!--begin::Body-->
								<div class="card-body p-lg-20">
									<!--begin::Layout-->
									<div class="d-flex flex-column flex-xl-row">
										<!--begin::Content-->
										<div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
											
											<!--begin::Invoice 2 content-->
											<div class="mt-n1">
												<!--begin::Top-->
												<div class="d-flex flex-stack pb-10">
													<!--begin::Logo-->
													<!-- <a href="#"> -->
                                                    <img alt="Logo" src="view/assets/media/logos/logo-ktz.png" class="pb-10"/>
													<!-- </a> -->
													<!--end::Logo-->
													<!--begin::Action-->

													<!--end::Action-->
												</div>
												<!--end::Top-->
												<!--begin::Wrapper-->
												<div class="m-0">
													<!--begin::Label-->
													<div class="fw-bolder fs-3 text-gray-800 mb-8">Solicitud #34782</div>

													<!--end::Label-->
													<!--begin::Row-->
													<div class="row g-5 mb-11">
														<!--end::Col-->
														<div class="col-sm-6">
															<!--end::Label-->
															<div class="fw-bold fs-7 text-gray-600 mb-1">Fecha de Creación:</div>
															<!--end::Label-->
															<!--end::Col-->
															<div class="fw-bolder fs-6 text-gray-800"><?php echo date("d-F-Y", strtotime($solicitud['creacion'])); ?></div>
															<!--end::Col-->
														</div>
														<!--end::Col-->
														<!--end::Col-->
														<div class="col-sm-6">
															<!--end::Label-->
															<div class="fw-bold fs-7 text-gray-600 mb-1">Divisa:</div>
															<!--end::Label-->
															<!--end::Info-->
															<div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center flex-wrap">
																<span class="pe-2"><?php echo $solicitud['divisa'] ?></span>
																<span class="fs-7 text-danger d-flex align-items-center">
																<!-- <span class="bullet bullet-dot bg-danger me-2"></span>Due in 7 days</span> -->
															</div>
															<!--end::Info-->
														</div>
														<!--end::Col-->
													</div>
													<!--end::Row-->
													<!--begin::Row-->
													<div class="row g-5 mb-12">
														<!--end::Col-->
														<div class="col-sm-6">
															<!--end::Label-->
															<div class="fw-bold fs-7 text-gray-600 mb-1">Solicitado por:</div>
															<!--end::Label-->
															<!--end::Text-->
															<div class="fw-bolder fs-6 text-gray-800"><?php echo $solicitud['nombre'].' '.$solicitud['apellidos']; ?></div>
															<!--end::Text-->
															<!--end::Description-->
															<div class="fw-bold fs-7 text-gray-600"><? echo $solicitud['descripcion'] ?>
															<br /><? echo $solicitud['departamento'] ?></div>
															<!--end::Description-->
														</div>
														<!--end::Col-->
														<!--end::Col-->
														<div class="col-sm-6">
															<!--end::Label-->
															<div class="fw-bold fs-7 text-gray-600 mb-1">Proveedor:</div>
															<!--end::Label-->
															<!--end::Text-->
															<div class="fw-bolder fs-6 text-gray-800"><?php echo $solicitud['proveedor']; ?></div>
															<!--end::Text-->
															<!--end::Description-->
															<div class="fw-bold fs-7 text-gray-600"><? echo $solicitud['notas'] ?>
															<br /></div>
															<!--end::Description-->
														</div>
														<!--end::Col-->
													</div>
													<!--end::Row-->
													<!--begin::Content-->
													<div class="flex-grow-1">
														<!--begin::Table-->
														<div class="table-responsive border-bottom mb-9">
															<table class="table mb-3">
																<thead>
																	<tr class="border-bottom fs-6 fw-bolder text-muted">
																		<th class="min-w-175px pb-2">Description</th>
																		<th class="min-w-70px text-end pb-2">Cantidad</th>
																		<th class="min-w-80px text-end pb-2">Precio</th>
																		<th class="min-w-100px text-end pb-2">Subtotal</th>
																	</tr>
																</thead>
																<tbody>
                                                                <?php
                                                                if ($resultado_articulos) {
                                                                    while ($articulo = $resultado_articulos->fetch_assoc()) {
                                                                ?>
																	<tr class="fw-bolder text-gray-700 fs-5 text-end">
																		<td class="d-flex align-items-center">
																		<i class="fa fa-genderless text-primary fs-2 me-2"></i><?echo $articulo['numero_parte']?></td>
																		<td><?echo $articulo['cantidad']?></td>
																		<td>$<?echo $articulo['precio']?></td>
																		<td class="fs-5 text-dark fw-boldest">$<?echo ($articulo['cantidad']*$articulo['precio'])?></td>
																	</tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
																</tbody>
															</table>
														</div>
														<!--end::Table-->
														<!--begin::Container-->
														<div class="d-flex justify-content-end">
															<!--begin::Section-->
															<div class="mw-300px">
																<!--begin::Item-->
																<div class="d-flex flex-stack mb-3">
																	<!--begin::Accountname-->
																	<!-- <div class="fw-bold pe-10 text-gray-600 fs-7">Subtotal:</div> -->
																	<!--end::Accountname-->
																	<!--begin::Label-->
																	<!-- <div class="text-end fw-bolder fs-6 text-gray-800">$ 20,600.00</div> -->
																	<!--end::Label-->
																</div>
																<!--end::Item-->
																<!--begin::Item-->
																<div class="d-flex flex-stack mb-3">
																	<!--begin::Accountname-->
																	<!-- <div class="fw-bold pe-10 text-gray-600 fs-7">VAT 0%</div> -->
																	<!--end::Accountname-->
																	<!--begin::Label-->
																	<!-- <div class="text-end fw-bolder fs-6 text-gray-800">0.00</div> -->
																	<!--end::Label-->
																</div>
																<!--end::Item-->
																<!--begin::Item-->
																<div class="d-flex flex-stack mb-3">
																	<!--begin::Accountnumber-->
																	<!-- <div class="fw-bold pe-10 text-gray-600 fs-7">Subtotal + VAT</div> -->
																	<!--end::Accountnumber-->
																	<!--begin::Number-->
																	<!-- <div class="text-end fw-bolder fs-6 text-gray-800">$ 20,600.00</div> -->
																	<!--end::Number-->
																</div>
																<!--end::Item-->
																<!--begin::Item-->
																<div class="d-flex flex-stack">
																	<!--begin::Code-->
																	<div class="fw-bold pe-10 text-gray-600 fs-7">Total</div>
																	<!--end::Code-->
																	<!--begin::Label-->
																	<div class="text-end fw-bolder fs-6 text-gray-800">$ <? echo $gasto_mensual; ?></div>
																	<!--end::Label-->
																</div>
																<!--end::Item-->
															</div>
															<!--end::Section-->
														</div>
														<!--end::Container-->
													</div>
													<!--end::Content-->
												</div>
												<!--end::Wrapper-->
											</div>
											<!--end::Invoice 2 content-->
										</div>
										<!--end::Content-->
										<!--begin::Sidebar-->
										<div class="m-0">
											<!--begin::Invoice 2 sidebar-->
											<div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
												<!--begin::Labels-->
												<div class="mb-8">
													<span class="badge badge-light-<?echo $class_status; ?> me-2"><?echo $status_value; ?></span>
													<!-- <span class="badge badge-light-warning">Pending Payment</span> -->
												</div>
												<!--end::Labels-->
												<!--begin::Title-->
												<h6 class="mb-8 fw-boldest text-gray-600 text-hover-primary">Detalles de la Solicitud</h6>
												<!--end::Title-->
												<!--begin::Item-->
												<div class="mb-6">
													<div class="fw-bold text-gray-600 fs-7">email:</div>
													<div class="fw-bolder text-gray-800 fs-6"><? echo $solicitud['email'] ?></div>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="mb-6">
												<div class="fw-bolder text-gray-800 fs-6">Notas:</div>
												<div class="fw-bold text-gray-600 fs-7"> <? echo $solicitud['notas']; ?></div>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<!-- <div class="mb-15">
													<div class="fw-bold text-gray-600 fs-7">Payment Term:</div>
													<div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">14 days
													<span class="fs-7 text-danger d-flex align-items-center">
													<span class="bullet bullet-dot bg-danger mx-2"></span>Due in 7 days</span></div>
												</div> -->
												<!--end::Item-->
												<!--begin::Title-->
												<h6 class="mb-8 fw-boldest text-gray-600 text-hover-primary">Revision del Proyecto</h6>
												<!--end::Title-->
												<!--begin::Item-->
												<div class="mb-6">
													<div class="fw-bold text-gray-600 fs-7">Nombre del Proyecto</div>
													<div class="fw-bolder fs-6 text-gray-800"><? echo $solicitud['proyecto'] ?>
													<!-- <a href="#" class="link-primary ps-1">View Project</a> -->
                                                    </div>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="mb-6">
													<!-- <div class="fw-bold text-gray-600 fs-7">Autorizado por:</div> -->
													<div class="fw-bolder text-gray-800 fs-6"></div>
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div class="m-0">
													<div class="fw-bold text-gray-600 fs-7">Accion:</div>
														<div class="fw-bolder fs-6 text-gray-800 d-flex align-items-center">													
															<? if ($user['fk_rol'] == 3 || $user['fk_rol'] == 1) { ?>
																<a href="functions/invoice/action.php?submit=aprove&id=<?echo $solicitud_id ?>" class="btn btn-sm btn-success">Aprobar</a>
																<span class="bullet bullet-dot bg-success mx-2"></span></span>
																<a href="functions/invoice/action.php?submit=deny&id=<?echo $solicitud_id ?>" class="btn btn-sm btn-danger">Rechazar</a>
																<span class="fs-7 text-danger d-flex align-items-center">
																<span class="bullet bullet-dot bg-danger mx-2"></span></span>
																<a href="solicitud.php?tipo=<?echo $solicitud['tipo']?>&id=<?echo $solicitud_id?>" class="btn btn-sm btn-primary">Editar</a>
																	<span class="bullet bullet-dot bg-primary mx-2"></span></span>
															<? }else{ ?>
																<? if ($solicitud['status'] == 0 | $solicitud['status'] == 1) { ?>
																	<a href="solicitud.php?tipo=<?echo $solicitud['tipo']?>&id=<?echo $solicitud_id?>" class="btn btn-sm btn-primary">Editar</a>
																	<span class="bullet bullet-dot bg-primary mx-2"></span></span>
																<? }else{ ?>
																	<h6 class="mb-8 fw-boldest text-gray-600 text-hover-primary">La Solicitud esta 
																	<span class="badge badge-light-<?echo $class_status; ?> me-2"><?echo $status_value; ?></span>
																	</h6>
																<? } ?>
															<? } ?>
														</div>
												</div>
												<? if ($user['fk_rol'] == 2 || $user['fk_rol'] == 1) { ?>
													<div class="mb-6">
														<div class="fw-bold text-gray-600 fs-7 required">Código IPOS</div>
														<center>
														<div class="fw-bolder fs-6 text-gray-800">
														<? if ($solicitud['status'] == 4){ ?>
															<input type="text" class="form-control form-control-solid"
															placeholder="ipos" name="ipos" id="ipos"
															value="<?php echo $solicitud['ipos']; ?>" />
															<a href="functions/invoice/action.php?submit=ipos&id=<?php echo $solicitud_id ?>&ipos=" id="guardarIposLink" 
															class="btn btn-sm btn-primary">
															Editar
														<? } else { ?>
															<input type="text" class="form-control form-control-solid"
															name="ipos" id="ipos"
															value="" placeholder="Código IPOS"/>
															<a href="functions/invoice/action.php?submit=ipos&id=<?php echo $solicitud_id ?>&ipos=" id="guardarIposLink" 
															class="btn btn-sm btn-success">
															Guardar
														<? } ?>
														</a>
															<span class="bullet bullet-dot bg-success mx-2"></span></span>
														</div>
														</center>
													</div>
												<? } ?>
                                                
												<!--end::Item-->
											</div>
											<!--end::Invoice 2 sidebar-->
										</div>
										<!--end::Sidebar-->
									</div>
									<!--end::Layout-->
								</div>
								<!--end::Body-->
							</div>
							<!--end::Invoice 2 main-->
						</div>
						<!--end::Post-->
					</div>

					<script>
						// Obtener el elemento del campo ipos
						var iposInput = document.getElementById('ipos');
						
						// Obtener el enlace del botón Guardar IPOS
						var guardarIposLink = document.getElementById('guardarIposLink');
						
						// Escuchar el evento click en el enlace
						guardarIposLink.addEventListener('click', function() {
							// Obtener el valor actual del campo ipos
							var iposValue = iposInput.value;
							
							// Concatenar el valor en el enlace del href
							guardarIposLink.href += iposValue;
						});
					</script>