<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Usthad Develoeprs</title>
	<!-- Vendors Style-->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/assets/css/vendors_css.css">
	<link rel="icon" href="<?php echo base_url()?>uploads/banner/banner.jpeg">
	<!-- Style-->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/assets/css/skin_color.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.0/js/bootstrapValidator.min.js"></script>
	<style type="text/css">
		.has-error .help-block {
			color: red;
		}

		.has-success .help-block {
			color: green;
		}
	</style>
</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url(<?php echo base_url() ?>assets/images/auth-bg/bg-1.jpg)" data-overlay="5">

	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">

			<div class="col-12">
				<div class="row justify-content-center no-gutters">
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded30 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<!-- <h2 class="text-primary">Let's Get Started</h2> -->
								<img class="image mg-5" src="<?php echo base_url()?>uploads/banner/banner.jpeg" height="100"/>
								
							</div>
							<div class="p-20">
							<!-- <h2 class="mb-0 justify-content-center" style="text-align:center;padding-bottom:10px">Login</h2> -->
								<form id="loginForm" action="<?php echo base_url('Master/authLogin') ?>" method="post">
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
											</div>
											<input type="text" class="form-control pl-15 bg-transparent" placeholder="Username" id="username" name="username">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text  bg-transparent"><i class="ti-lock"></i></span>
											</div>
											<input type="password" class="form-control pl-15 bg-transparent" placeholder="Password" id="password" name="password">
										</div>
									</div>
									<div class="row">
										<!-- <div class="col-6">
										  <div class="checkbox">
											<input type="checkbox" id="basic_checkbox_1" >
											<label for="basic_checkbox_1">Remember Me</label>
										  </div>
										</div> -->
										<div class="col-12 text-center">
											<button type="submit" class="btn btn-danger mt-10">SIGN IN</button>
										</div>
										<!-- /.col -->
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				$('#loginForm').bootstrapValidator({
					message: 'This value is not valid',
					feedbackIcons: {
						valid: 'glyphicon glyphicon-ok',
						invalid: 'glyphicon glyphicon-remove',
						validating: 'glyphicon glyphicon-refresh'
					},
					fields: {
						username: {

							validators: {
								notEmpty: {
									message: 'The username is required'
								},
							}
						},
						password: {
							validators: {
								notEmpty: {
									message: 'The password is required'
								},
								stringLength: {
									min: 6,
									max: 10,
									message: 'The password must contain atleast 6 characters '
								},

							}
						},
					}
				});
			});
		</script>

		<!-- Vendor JS -->

		<!-- <script src="<?php echo base_url() ?>assets/assets/js/vendors.min.js"></script> -->
		<script src="<?php echo base_url() ?>assets/assets/icons/feather-icons/feather.min.js"></script>

</body>

</html>