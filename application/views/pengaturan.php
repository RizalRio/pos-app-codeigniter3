<!DOCTYPE html>
<html>

<head>
	<title>Pengaturan</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('node_modules/croppie/croppie.css') ?>">
	<?php $this->load->view('partials/head'); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">

		<?php $this->load->view('includes/nav'); ?>

		<?php $this->load->view('includes/aside'); ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col">
							<h1 class="m-0 text-dark">Pengaturan</h1>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>


			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">
							<form id="toko">
								<div class="form-row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Nama Toko</label>
											<input type="text" class="form-control" placeholder="Nama Toko" name="nama" value="<?php echo $toko->nama ?>" required>
										</div>
										<div class="form-group">
											<label>Telepon</label>
											<input type="text" class="form-control" placeholder="Telepon" name="tlpn" value="<?php echo $toko->tlpn ?>" required>
										</div>
										<div class="form-group">
											<label>NPWP</label>
											<input type="text" class="form-control" placeholder="NPWP" name="npwp" value="<?php echo $toko->npwp ?>" required>
										</div>
										<div class="form-group">
											<label>Instagram</label>
											<input type="text" class="form-control" placeholder="Instagram" name="instagram" value="<?php echo $toko->instagram ?>" required>
										</div>
										<div class="form-group">
											<label>Alamat</label>
											<textarea name="alamat" placeholder="Alamat" class="form-control" required><?php echo $toko->alamat ?></textarea>
										</div>
										<div class="form-group">
											<label>Note</label>
											<textarea name="note" placeholder="Note" class="form-control" required><?php echo $toko->noted ?></textarea>
										</div>
										<div class="form-group">
											<button class="btn btn-success btn-block" type="submit">Simpan</button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Logo</label>
											<br>
											<input type="file" name="logo" id="logo" class="mb-4">
											<div id="reviewImage" class="d-flex justify-content-center">
												<img id="cropImage" src="<?= base_url('uploads/default.jpg') ?>" style="width: 100%; height: 100%;">
											</div>
											<div class="d-flex justify-content-center">
												<img id="logoReview" src="<?= ($toko->logo) ? base_url('uploads/logo/'.$toko->logo) : base_url('uploads/default.jpg') ?>" style="width: 250px; height: 250px;" class="mt-2">
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

	</div>
	<!-- ./wrapper -->
	<?php $this->load->view('includes/footer'); ?>
	<?php $this->load->view('partials/footer'); ?>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
	<script src="<?php echo base_url('node_modules/croppie/croppie.min.js') ?>"></script>
	<script>
		logo.onchange = evt => {
			const [file] = logo.files
			if (file) {
				logoReview.src = URL.createObjectURL(file)
			}
		}

		$uploadCrop = $('#cropImage').croppie({
			enableExif: true,
			viewport: {
				width: 56.692913386,
				height: 56.692913386
			},
			boundary: {
				width: 250,
				height: 250
			}
		});

		$('#logo').on('change', function() {
			var reader = new FileReader();
			reader.onload = function(e) {
				$uploadCrop.croppie('bind', {
					url: e.target.result
				}).then(function() {
					console.log('jQuery bind complete');
				});

			}
			reader.readAsDataURL(this.files[0]);
		});

		$('#toko').validate({
			errorElement: 'span',
			errorPlacement: (error, element) => {
				error.addClass('invalid-feedback')
				element.closest('.form-group').append(error)
			},
			submitHandler: () => {
				$uploadCrop.croppie('result', {
					type: 'canvas',
					size: 'viewport'
				}).then(function(resp) {
					var formData = new FormData($('#toko')[0]);
					formData.append('croppedImg', resp);
					$.ajax({
						url: '<?php echo site_url('pengaturan/set_toko') ?>',
						type: 'post',
						dataType: 'json',
						data: formData,
						processData: false,
						contentType: false,
						success: res => {
							Swal.fire('Sukses', 'Sukses Mengedit', 'success').then(() => window.location.reload())
						},
						error: res => {
							console.log(res);
						}
					})
				});
			}
		});
	</script>
</body>

</html>
