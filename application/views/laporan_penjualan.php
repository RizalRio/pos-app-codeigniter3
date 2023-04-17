<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laporan Penjualan</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2/css/select2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/daterangepicker/daterangepicker.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/pritnjs/print.min.css') ?>">
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
							<h1 class="m-0 text-dark">Laporan Penjualan</h1>
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
						<?php if ($role === 'admin' || $role === 'superadmin' || $role === 'kasir') : ?>
							<div class="card-header">
								<form action="<?php echo site_url('laporan_penjualan/exportToExcel') ?>" method="post">
									<label>Export To Excel</label>
									<div class="row">
										<div class="col-sm-5">
											<input type="text" name="range" id="range" class="form-control">
										</div>
										<div class="col-sm-2">
											<select class="form-control select2" name="search" id="search"></select>
										</div>
										<div class="col-sm-3">
											<select class="form-control select2" name="product" id="product"></select>
										</div>
										<div class="col-sm-2">
											<button type="submit" id="exportBtn" class="btn btn-info"><i class="fas fa-file-export"></i></button>
										</div>
									</div>
								</form>
							</div>
						<?php endif; ?>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table w-100 table-bordered table-hover" id="laporan_penjualan">
									<thead>
										<tr>
											<th>No</th>
											<th>Tanggal</th>
											<th>Nama Produk</th>
											<th>Total Bayar</th>
											<th>Jumlah Uang</th>
											<th>Diskon</th>
											<th>Pelanggan</th>
											<th>Action</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div><!-- /.container-fluid -->

				<!-- Modal -->
				<?php $this->load->view('cetak'); ?>
				<!-- <div hidden>
					<div class="cetak-div">
						<div style="max-width: 100%; max-height: 100%; width: 58mm; height: 200mm; margin: auto; font-size: 10px;">
							<br>
							<center>
								<div style="font-weight: bold;">
									<?php echo $this->session->userdata('toko')->nama; ?><br>
								</div>
								<?php echo $this->session->userdata('toko')->alamat; ?><br>
								NPWP : <?php echo $this->session->userdata('toko')->npwp; ?><br>
								No.Telp : <?php echo $this->session->userdata('toko')->tlpn; ?><br><br>
								<table width="100%" style="font-size: 8px;">
									<tr>
										<td id="nota-cetak"></td>
										<td align="right" id="tanggal-cetak"></td>
									</tr>
								</table>
								<hr style="opacity:1" noshade="noshade">
								<table width="100%" style="font-size: 8px;">
									<tr>
										<td width="50%"></td>
										<td width="3%"></td>
										<td width="10%" align="right"></td>
										<td align="right" width="17%" id="kasir-cetak"></td>
									</tr>
								</table>
								<table width="100%" id="table-cetak" style="font-size: 8px;">

								</table>
								<hr style="opacity:1" noshade="noshade">
								<table width="100%" style="font-size: 8px;">
									<tr>
										<td width="76%">
											Sub Total
										</td>
										<td width="23%" align="right" id="subtotal-cetak">
										</td>
									</tr>
									<tr>
										<td width="76%">
											Potongan
										</td>
										<td width="23%" align="right" id="diskon-cetak">
										</td>
									</tr>
								</table>
								<hr style="opacity:1" noshade="noshade">
								<table width="100%" style="font-size: 8px;">
									<tr>
										<td width="76%">
											Total
										</td>
										<td width="23%" align="right" id="total-cetak">
										</td>
									</tr>
								</table>
								<hr style="opacity:1" noshade="noshade">
								<table width="100%" style="font-size: 8px;">
									<tr>
										<td width="76%">
											Bayar
										</td>
										<td width="23%" align="right" id="bayar-cetak">
										</td>
									</tr>
									<tr>
										<td width="76%">
											Kembalian
										</td>
										<td width="23%" align="right" id="kembalian-cetak">
										</td>
									</tr>
								</table>
								<hr style="opacity:1" noshade="noshade">
								Instagram : <?php echo $this->session->userdata('toko')->instagram; ?><br>
								Terima Kasih <br>
								<div style="font-weight: bold;">
									<?php echo $this->session->userdata('toko')->nama; ?><br>
								</div>
								No.Telp : <?php echo $this->session->userdata('toko')->tlpn; ?>
							</center>
						</div>
					</div>
				</div> -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

	</div>
	<!-- ./wrapper -->
	<?php $this->load->view('includes/footer'); ?>
	<?php $this->load->view('partials/footer'); ?>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/select2/js/select2.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/daterangepicker/moment.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/daterangepicker/daterangepicker.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/pritnjs/print.min.js') ?>"></script>
	<script>
		var getUrl = '<?php echo base_url() ?>';
		var readUrl = '<?php echo site_url('transaksi/read') ?>';
		var deleteUrl = '<?php echo site_url('transaksi/delete') ?>';
		var exportUrl = '<?php echo site_url('laporan_penjualan/exportToExcel') ?>';
		var sumberSearchUrl = '<?php echo site_url('sumber/search') ?>';
		var getBarcodeUrl = '<?php echo site_url('produk/get_barcode') ?>';
		var getDataCetak = '<?php echo site_url('transaksi/cetak') ?>';
	</script>
	<script src="<?php echo base_url('assets/js/unminify/laporan_penjualan.js') ?>"></script>
	<!-- <script src="<?php echo base_url('assets/js/laporan_penjualan.min.js') ?>"></script> -->
</body>

</html>