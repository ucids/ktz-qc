<?php
require $_SERVER['DOCUMENT_ROOT'] . '/class/data.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    $username = explode('@', $email)[0];
    $nombre = $_POST['first-name'];
	$apellidos = $_POST['last-name'];
	$rol = $_POST['rol'];

    // Check if username or email already exists
    $stmt = $conn->prepare('SELECT COUNT(*) AS count FROM users WHERE username = :username OR email = :email');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
      $message = 'EL usuario ya ha sido registrado';
    } else {
      // Insert new user
      $sql = "INSERT INTO users (email, password, username, nombre, apellidos, fk_rol) VALUES (:email, :password, :username, :nombre, :apellidos, :rol)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':username', $username);
	  $stmt->bindParam(':nombre', $nombre);
      $stmt->bindParam(':apellidos', $apellidos);
      $stmt->bindParam(':rol', $rol);
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password);

      if ($stmt->execute()) {
        $message = 'Usuario Creado exitosamente.';
      } else {
        $message = 'Sorry there must have been an issue creating your account';
      }
    }

    // Redirect to the same page with the message
    header("Location: ".$_SERVER['PHP_SELF']."?message=" . urlencode($message));
    exit();
  }
}

// Retrieve the message from the URL parameter if available
$message = isset($_GET['message']) ? $_GET['message'] : '';
if ($message == 'Usuario Creado exitosamente.'){
	$color = 'success';
	$icon ='<i class="bi bi-check-circle-fill fs-5x text-success"></i>';
}else{
	$color = 'danger';
	$icon ='<i class="bi bi-x-circle-fill fs-5x text-danger"></i>';
}

?>

<!DOCTYPE html>

<html lang="es">
	<!--begin::Head-->
	<head><base href="../../../">
		<title>Katzkin Compras</title>
		<meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
		<meta property="og:url" content="https://ucid.com" />
		<meta property="og:site_name" content="Keenthemes | Metronic" />
		<link rel="canonical" href="https://ucid.com" />
		<link rel="shortcut icon" href="/view/assets/media/logos/katzkin.png" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="/view/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/view/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-up -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(/view/assets/media/illustrations/sigma-1/14.png">
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<!--begin::Logo-->
					<a href="../../demo2/dist/index.html" class="mb-12">
						<img alt="Logo" src="/view/assets/media/logos/logo-ktz.png" class="h-100px" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<!--begin::Form-->
						<?php if(!empty($message)): ?>
							<!--begin::Alert-->
							<div class="alert alert-dismissible bg-light-<?php echo $color; ?> d-flex flex-center flex-column py-10 px-10 px-lg-20 mb-10">
								<!--begin::Close-->
								<button type="button" class="position-absolute top-0 end-0 m-2 btn btn-icon btn-icon-<?php echo $color; ?>" data-bs-dismiss="alert">
									<span class="svg-icon svg-icon-1"><i class="bi bi-x-circle fs-2qx"></i></span>
								</button>
								<!--end::Close-->

								<!--begin::Icon-->
								<span class="svg-icon svg-icon-5tx svg-icon-<?php echo $color; ?> mb-5"><?php echo $icon;?></span>
								<!--end::Icon-->

								<!--begin::Wrapper-->
								<div class="text-center">
									<!--begin::Title-->
									<!-- <h1 class="fw-bolder mb-5"><?php echo $message; ?></h1> -->
									<!--end::Title-->

									<!--begin::Separator-->
									<div class="separator separator-dashed border-<?php echo $color; ?> opacity-25 mb-5"></div>
									<!--end::Separator-->

									<!--begin::Content-->
									<div class="mb-9 text-dark">
										<strong><?php echo $message; ?></strong>.<br/>
										<!-- Please read our <a href="#" class="fw-bolder me-1">Terms and Conditions</a> for more info. -->
									</div>
									<!--end::Content-->

									<!--begin::Buttons-->
									<div class="d-flex flex-center flex-wrap">
										<!-- <a href="#" class="btn btn-outline btn-outline-success btn-active-success m-2">Cancel</a> -->
										<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary m-2">Ok</a>
									</div>
									<!--end::Buttons-->
								</div>
								<!--end::Wrapper-->
							</div>
							<!--end::Alert-->
						<?php endif; ?>
						<form action="view/sign-up.php" method="POST" class="form w-100 needs-validation">
							<!--begin::Heading-->
							<div class="mb-10 text-center">
								<!--begin::Title-->
								<h1 class="text-dark mb-3">Crea un usuario</h1>
								<!--end::Title-->
								<!--begin::Link-->
								<div class="text-gray-400 fw-bold fs-4">Ya tienes cuenta?
								<a href="view/sign-in.php" class="link-primary fw-bolder">Inicia Sesión Aquí</a></div>
								<!--end::Link-->
							</div>
							<!--end::Heading-->
							<!--begin::Separator-->
							<div class="d-flex align-items-center mb-10">
								<div class="border-bottom border-gray-300 mw-50 w-100"></div>
								<span class="fw-bold text-gray-400 fs-7 mx-2">OR</span>
								<div class="border-bottom border-gray-300 mw-50 w-100"></div>
							</div>
							<!--end::Separator-->
							<!--begin::Input group-->
							<div class="row fv-row mb-7">
								<!--begin::Col-->
								<div class="col-xl-6">
									<label  for="validationServerUsername" class="required form-label fw-bolder text-dark fs-6">Nombre(s)</label>
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="first-name" autocomplete="off" required="true"/>
								</div>
								<!--end::Col-->
								<!--begin::Col-->
								<div class="col-xl-6">
									<label  class="required form-label fw-bolder text-dark fs-6">Apellido</label>
									<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="last-name" autocomplete="off" required="true"/>
								</div>
								<!--end::Col-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-7">
							<?php
								// $stmt = $conn->prepare('SELECT id_rol, descripcion FROM roles');
								// $stmt->execute();
								// $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
								// $options = '';
								// foreach ($roles as $role) {
								// 	$roleID = $role['id_rol'];
								// 	$roleName = $role['descripcion'];
								// 	$options .= '<option value="' . $roleID . '">' . $roleName . '</option>';
								// }
								?>
								<!-- <label class=" required form-label fw-bolder text-dark fs-6">Departamento</label>
								<select name="rol" class="form-select form-select-solid" aria-label="Select example" required="true">
									<option value="">Selecciona el Departamento</option>
									<?php echo $options; ?>
								</select> -->
								<input type="hidden" name="rol" value="3">
							</div>

							<div class="fv-row mb-7">
								<label class="required form-label fw-bolder text-dark fs-6">Número de Empleado</label>
								<input class="form-control form-control-lg form-control-solid" type="text" placeholder="" name="email" autocomplete="off" required="true"/>
							</div>
							

							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="mb-10 fv-row" data-kt-password-meter="true">
								<!--begin::Wrapper-->
								<div class="mb-1">
									<!--begin::Label-->
									<label class="required form-label fw-bolder text-dark fs-6">Contraseña</label>
									<!--end::Label-->
									<!--begin::Input wrapper-->
									<div class="position-relative mb-3">
										<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="password" autocomplete="off" required="true"/>
										<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
											<i class="bi bi-eye-slash fs-2"></i>
											<i class="bi bi-eye fs-2 d-none"></i>
										</span>
									</div>
									<!--end::Input wrapper-->
									<!--begin::Meter-->
									<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
									</div>
									<!--end::Meter-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Hint-->
								<!-- <div class="text-muted">Usa 8 digitos mezclados con numeros, maysuculas y minusculas</div> -->
								<!--end::Hint-->
							</div>

							<div class="text-center">
								<button type="submit" id="kt_sign_up_submit" class="btn btn-lg btn-primary" value="Submit">
									<span class="indicator-label">Submit</span>
									<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
							</div>
							<!--end::Actions-->
						</form>
						<!--end::Form-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Authentication - Sign-up-->
		</div>
		<!--end::Main-->
		<script>var hostUrl = "/view/assets/";</script>
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="/view/assets/plugins/global/plugins.bundle.js"></script>
		<script src="/view/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="/view/assets/js/custom/authentication/sign-up/general.js"></script>
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	</html>
	<!--end::Body-->