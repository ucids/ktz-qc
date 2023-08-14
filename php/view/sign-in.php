<?php

  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
  }
  require '../class/data.php';

//   if (!empty($_POST['email']) && !empty($_POST['password'])) {
//     $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
//     $records->bindParam(':email', $_POST['email']);
//     $records->execute();
//     $results = $records->fetch(PDO::FETCH_ASSOC);
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
    $records->bindParam(':email', $_POST['email']);
    $records->bindParam(':username', $_POST['email']); // Suponiendo que el campo de entrada para el nombre de usuario tiene el mismo valor que el campo de entrada para el correo electrónico
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

	if (is_array($results) && count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
		$_SESSION['user_id'] = $results['id_user'];
		header("Location: /index.php");
	} else {
		$message = 'Por favor verifica la contraseña o el nombre de usuario';
	}
	
  }

?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="/">
		<title>Katzkin</title>
		<meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
		<meta property="og:url" content="https://ucid.com" />
		<meta property="og:site_name" content="Katzkin | Compras" />
		<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
		<link rel="shortcut icon" href="/view/assets/media/logos/katzkin.png" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="view/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="view/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="bg-body">

		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(/view/assets/media/illustrations/sigma-1/14.png">
			
				<!--begin::Content-->
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					
					<!--begin::Logo-->
					<a href="index.php" class="mb-12">
						<img alt="Logo" src="/view/assets/media/logos/logo-ktz.png" class="h-100px" />
					</a>
					<!--end::Logo-->
					<!--begin::Wrapper-->
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<?php if(!empty($message)): ?>
							<!--begin::Alert-->
							<div class="alert alert-dismissible bg-light-danger d-flex flex-center flex-column py-10 px-10 px-lg-20 mb-10">
								<!--begin::Close-->
								<button type="button" class="position-absolute top-0 end-0 m-2 btn btn-icon btn-icon-danger" data-bs-dismiss="alert">
									<span class="svg-icon svg-icon-1"><i class="bi bi-x-circle fs-2qx"></i></span>
								</button>
								<!--end::Close-->

								<!--begin::Icon-->
								<span class="svg-icon svg-icon-5tx svg-icon-danger mb-5"><i class="bi bi-x-octagon-fill fs-5x text-danger"></i></span>
								<!--end::Icon-->

								<!--begin::Wrapper-->
								<div class="text-center">
									<!--begin::Title-->
									<!-- <h1 class="fw-bolder mb-5"><?php echo $message; ?></h1> -->
									<!--end::Title-->

									<!--begin::Separator-->
									<div class="separator separator-dashed border-danger opacity-25 mb-5"></div>
									<!--end::Separator-->

									<!--begin::Content-->
									<div class="mb-9 text-dark">
										<strong><?php echo $message; ?></strong>.<br/>
										<!-- Please read our <a href="#" class="fw-bolder me-1">Terms and Conditions</a> for more info. -->
									</div>
									<!--end::Content-->

									<!--begin::Buttons-->
									<div class="d-flex flex-center flex-wrap">
										<!-- <a href="#" class="btn btn-outline btn-outline-danger btn-active-danger m-2">Cancel</a> -->
										<a href="#" class="btn btn-primary m-2">Reintentar</a>
									</div>
									<!--end::Buttons-->
								</div>
								<!--end::Wrapper-->
							</div>
							<!--end::Alert-->
						<?php endif; ?>
						<!--begin::Form-->
						<form class="form w-100" novalidate="novalidate" action="view/sign-in.php" method="POST">
							<!--begin::Heading-->
							<div class="text-center mb-10">
								<!--begin::Title-->
								<h1 class="text-dark mb-3">Inicia Sesión</h1>
								<!--end::Title-->
							</div>
							<!--begin::Heading-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Label-->
								<label class="form-label fs-6 fw-bolder text-dark">Numero de Empleado</label>
								<!--end::Label-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid" type="text" name="email" autocomplete="off" />
								<!--end::Input-->
							</div>
							<!--end::Input group-->
							<!--begin::Input group-->
							<div class="fv-row mb-10">
								<!--begin::Wrapper-->
								<div class="d-flex flex-stack mb-2">
									<!--begin::Label-->
									<label class="form-label fw-bolder text-dark fs-6 mb-0">Contraseña</label>
									<!--end::Label-->
									<!--begin::Link-->
									<!-- <a href="/demo2/dist/authentication/flows/basic/password-reset.html" class="link-primary fs-6 fw-bolder">Forgot Password ?</a> -->
									<!--end::Link-->
								</div>
								<!--end::Wrapper-->
								<!--begin::Input-->
								<input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" />
								<!--end::Input-->
							</div>
							<!--end::Input group-->
							<!--begin::Actions-->
							<div class="text-center">
								<!--begin::Submit button-->
								<button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5" value="submit">
									<span class="indicator-label">Continue</span>
									<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
								<!--end::Submit button-->
								<!--begin::Separator-->
							<!--end::Actions-->
						</form>
						<!--end::Form-->
					</div>
					
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
				<!--begin::Footer-->
				<!--end::Footer-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Main-->
		<script>var hostUrl = "view/assets/";</script>
		<script src="date.js"></script>
		<!--begin::Javascript-->
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="view/assets/plugins/global/plugins.bundle.js"></script>
		<script src="view/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="view/assets/js/custom/authentication/sign-in/general.js"></script>
		<!--end::Page Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>