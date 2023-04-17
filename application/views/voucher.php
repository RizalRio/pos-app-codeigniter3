<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Voucher</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2/css/select2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ?>">
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
							<h1 class="m-0 text-dark">Voucher</h1>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="card">
						<div class="card-header">
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelId" onclick="add()">
								Add
							</button>
						</div>
						<div class="card-body">
							<table class="table w-100 table-bordered table-hover" id="data-voucher">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama</th>
										<th>Kode</th>
										<th>Rp</th>
										<th>Persen</th>
										<th>Mulai</th>
										<th>Selesai</th>
										<th>Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>
			<!-- Modal -->
			<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<form id="form">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Data</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<input type="hidden" name="id">
								<div class="form-group">
									<label for="nama">Nama</label>
									<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" required>
								</div>
								<div class="form-group">
									<label for="kode">Kode</label>
									<div class="row">
										<div class="col-sm-9">
											<input type="text" name="kode" id="kode" class="form-control" placeholder="Kode" required>
										</div>
										<div class="col-sm-3">
											<button class="btn btn-info btn-block" id="generate" type="button">Generate</button>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Tanggal Mulai</label>
									<input type="text" class="form-control datetimepicker" name="start" id="start" required>
								</div>
								<div class="form-group">
									<label>Tanggal Akhir</label>
									<input type="text" class="form-control datetimepicker" name="end" id="end" required>
								</div>
								<div class="form-group">
									<label for="nominal-rp">Nominal Voucher (Rp)</label>
									<input type="number" name="rupiah" id="rupiah" class="form-control" placeholder="Nominal Rupiah">
								</div>
								<div class="form-group">
									<label for="nominal-rp">Nominal Voucher (%)</label>
									<input type="number" name="persen" id="persen" class="form-control" placeholder="Nominal Persen">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
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
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/select2/js/select2.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/moment/moment.min.js') ?>"></script>
	<script>
		var readUrl = '<?php echo site_url('voucher/read') ?>';
		var addUrl = '<?php echo site_url('voucher/add') ?>';
		var deleteUrl = '<?php echo site_url('voucher/delete') ?>';
		var getVoucherUrl = '<?php echo site_url('voucher/get_voucher') ?>';
		var editUrl ='<?php echo site_url('voucher/edit') ?>';
	</script>
	<script src="<?php echo base_url('assets/js/unminify/voucher.js') ?>"></script>
</body>

</html>
