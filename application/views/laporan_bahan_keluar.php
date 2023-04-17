<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laporan Bahan Keluar</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/daterangepicker/daterangepicker.css') ?>">
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
							<h1 class="m-0 text-dark">Laporan Bahan Keluar</h1>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="card">
						<?php $role = $this->session->userdata('role'); ?>
						<?php if ($role === 'admin'|| $role === 'superadmin') : ?>
							<div class="card-header">
								<form action="<?php echo site_url('laporan_bahan_keluar/exportToExcel') ?>" method="post">
									<label>Export To Excel</label>
									<div class="row">
										<div class="col-sm-5">
											<input type="text" name="range" id="range" class="form-control">
										</div>
										<div class="col-sm-3">
											<button type="submit" id="exportBtn" class="btn btn-info"><i class="fas fa-file-export"></i></button>
										</div>
									</div>
								</form>
							</div>
						<?php endif; ?>
						<div class="card-body">
							<table class="table w-100 table-bordered table-hover" id="laporan_bahan_keluar">
								<thead>
									<tr>
										<th>No</th>
										<th>Tanggal</th>
										<th>Nama Bahan</th>
										<th>Jumlah</th>
										<th>Keterangan</th>
									</tr>
								</thead>
							</table>
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
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/daterangepicker/moment.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/daterangepicker/daterangepicker.js') ?>"></script>
	<script>
		var laporanUrl = '<?php echo site_url('stok_keluar_bahan/read') ?>';
	</script>
	<!-- <script src="<?php echo base_url('assets/js/laporan_stok_masuk.min.js') ?>"></script> -->
	<script src="<?php echo base_url('assets/js/unminify/laporan_bahan_keluar.js') ?>"></script>
</body>

</html>
