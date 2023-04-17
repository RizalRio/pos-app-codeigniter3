<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Produk</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2/css/select2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
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
							<h1 class="m-0 text-dark">Produk</h1>
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
							<button class="btn btn-success" data-toggle="modal" data-target="#modal" onclick="add(this)" data-paket="0">Add Produk</button>
							<button class="btn btn-info" data-toggle="modal" data-target="#modal" onclick="add(this)" data-paket="1">Add Paket</button>
						</div>
						<div class="card-body">
							<table class="table w-100 table-bordered table-hover" id="produk">
								<thead>
									<tr>
										<th>No</th>
										<th>Gambar</th>
										<th>Barcode</th>
										<th>Nama</th>
										<th>Stok</th>
										<th>Satuan</th>
										<th>Kategori</th>
										<th>Harga</th>
										<th>Actions</th>
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

	<div class="modal fade" id="modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Data</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-7">
								<input type="hidden" name="id">
								<div class="form-group">
									<label>Barcode</label>
									<input type="text" class="form-control" placeholder="Barcode" name="barcode" readonly required>
								</div>
								<div class="form-group">
									<label>Nama</label>
									<input type="text" class="form-control" placeholder="Nama" name="nama_produk" required>
								</div>
								<div class="form-group">
									<label>Satuan</label>
									<select name="satuan" id="satuan" class="form-control select2" required></select>
								</div>
								<div class="form-group">
									<label>Kategori</label>
									<select name="kategori" id="kategori" class="form-control select2" required></select>
								</div>
								<div class="form-group">
									<label>Harga</label>
									<input type="text" class="form-control" placeholder="Harga" name="harga" required>
								</div>
								<div class="form-group">
									<label>Stok</label>
									<input type="text" class="form-control" placeholder="Stok" name="stok" value="0" readonly>
								</div>
								<div class="form-group">
									<label>Tagline</label>
									<input type="text" class="form-control" placeholder="Tagline" name="tagline" required>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group form-append" id="input-bahan">
									<label>Bahan Baku</label>
									<div class="row-append mb-3">
										<div class="input-new-row">
										</div>
									</div>
									<button id="addNewRow" type="button" class="btn btn-info">Tambah Bahan</button>
								</div>
								<div class="form-group form-append-pr" id="input-paket-pr">
									<label>Produk Paket</label>
									<div class="row-append-pr mb-3">
										<div class="input-new-row-pr">
										</div>
									</div>
									<button id="addNewRowPr" type="button" class="btn btn-info">Tambah Produk</button>
								</div>
								<div class="form-group">
									<label>Gambar</label>
									<br>
									<input type="file" name="gambar" id="gambar" class="mb-4">
									<div id="reviewImage" class="d-flex justify-content-center">
										<img id="cropImage" src="<?= base_url('uploads/default.jpg') ?>" class="img-fluid mt-4">
									</div>
									<div class="d-flex justify-content-center">
										<img id="gambarReview" src="<?= base_url('uploads/default.jpg') ?>" style="width: 200px; height: 200px;" class="mt-2">
									</div>
								</div>
							</div>
						</div>
						<button class="btn btn-success" type="submit">Add</button>
						<button class="btn btn-danger" data-dismiss="modal">Close</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- ./wrapper -->
	<?php $this->load->view('includes/footer'); ?>
	<?php $this->load->view('partials/footer'); ?>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/vendor/adminlte/plugins/select2/js/select2.min.js') ?>"></script>
	<script src="<?php echo base_url('node_modules/croppie/croppie.min.js') ?>"></script>
	<script>
		var defaultJpg = '<?php echo site_url('uploads/default.jpg') ?>';
		var siteUrl = '<?php echo site_url() ?>';
		var getBarcodeUrl = '<?php echo site_url('produk/generate_barcode') ?>';
		var readUrl = '<?php echo site_url('produk/read') ?>';
		var addUrl = '<?php echo site_url('produk/add') ?>';
		var deleteUrl = '<?php echo site_url('produk/delete') ?>';
		var editUrl = '<?php echo site_url('produk/edit') ?>';
		var getProdukUrl = '<?php echo site_url('produk/get_produk') ?>';
		var kategoriSearchUrl = '<?php echo site_url('kategori_produk/search') ?>';
		var satuanSearchUrl = '<?php echo site_url('satuan_produk/search') ?>';
		var getBahanUrl = '<?php echo site_url('bahan_baku/get_bahan_select') ?>';
		var getSatuanUrl = '<?php echo site_url('produk/get_bahan_satuan') ?>';
		var getSelectProdukUrl = '<?php echo site_url('produk/get_barcode') ?>';
	</script>
	<!-- <script src="<?php echo base_url('assets/js/produk.min.js') ?>"></script> -->
	<script src="<?php echo base_url('assets/js/unminify/produk.js') ?>"></script>
</body>

</html>