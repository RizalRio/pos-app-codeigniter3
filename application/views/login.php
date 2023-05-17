<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<?php $this->load->view('partials/head'); ?>
	<style>
		.card {
			border-radius: 16px;
		}
	</style>
</head>

<body class="hold-transition login-page" style="background-color: black;">

	<div class="login-box">
		<div class="login-logo text-white">
			Login POS
			<br>
			<?php echo tokoData()->nama ?>
		</div>
		<div class="card p-4">
			<div class="">
				<p class="login-box-msg">Login untuk masuk</p>
				<div class="alert alert-danger d-none"></div>
				<form>
					<div class="input-group mb-3">
						<input type="text" class="form-control" name="username" placeholder="Username" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control" name="password" placeholder="Password" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<!-- <div class="input-group p-2 mb-3" style="border-radius: 8px; background-color: #d96e00 !important;">
						<div class="text-center container-fluid mb-2">
							<?= generate_captcha() ?>
						</div>
						<input type="text" class="form-control mb-2" name="captcha" placeholder="Captcha" required>
						<div class="input">
							<?php for ($i = 0; $i <= 9; $i++) : ?>
								<button type="button" class="btn btn-sm inputChar text-white mx-1 mb-1" style="background-color: black;"><?php echo $i; ?></button>
							<?php endfor; ?>
							<button type="button" class="backspaceChar btn btn-sm text-white mx-2" style="background-color: #0168b1;">Backspace</button>
						</div>
					</div> -->
					<div class="form-group">
						<button class="btn btn-block btn-primary">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php $this->load->view('partials/footer'); ?>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
	<script>
		$(document).on('click', '.inputChar', function() {
			var value = $(this).text();
			if ($('[name="captcha"]').val()) {
				var valueSet = $('[name="captcha"]').val();
				$('[name="captcha"]').val(valueSet + value);
			} else {
				$('[name="captcha"]').val(value);
			}
			checkEmpty();
		});

		$(document).on('click', '.backspaceChar', function() {
			var value = $('[name="captcha"]').val();
			$('[name="captcha"]').val(value.substr(0, value.length - 1));
			checkEmpty()
		});

		$('form').validate({
			errorElement: 'span',
			errorPlacement: (error, element) => {
				error.addClass('invalid-feedback')
				element.closest('.input-group').append(error)
			},
			submitHandler: () => {
				$.ajax({
					url: '<?php echo site_url('login') ?>',
					type: 'post',
					dataType: 'json',
					data: $('form').serialize(),
					success: res => {
						if (res == 'tidakada') {
							$('.alert').html('Username tidak terdaftar')
							$('.alert').removeClass('d-none')
						} else if (res == 'passwordsalah') {
							$('.alert').html('Password Salah')
							$('.alert').removeClass('d-none')
						} else if (res == 'captchano') {
							$('.alert').html('Capthca Tidak Sesuai')
							$('.alert').removeClass('d-none')
						} else {
							$('.alert').html('Sukses')
							$('.alert').addClass('alert-success')
							$('.alert').removeClass('d-none alert-danger')
							setTimeout(function() {
								window.location.reload()
							}, 1000);
						}
					},
					error: err => {
						console.log(err);
					}
				})
			}
		})
	</script>
</body>

</html>
