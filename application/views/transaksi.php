<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Transaksi</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2/css/select2.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/adminlte/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/pritnjs/print.min.css') ?>">
	<?php $this->load->view('partials/head'); ?>
	<style>
		body:fullscreen {
			overflow: scroll !important;
		}

		body:-ms-fullscreen {
			overflow: scroll !important;
		}

		body:-webkit-full-screen {
			overflow: scroll !important;
		}

		body:-moz-full-screen {
			overflow: scroll !important;
		}

		@media(max-width: 576px) {
			.nota {
				justify-content: center !important;
				text-align: center !important;
			}
		}

		.modal {
			overflow: auto !important;
		}

		.produk-container-item {
			box-shadow: 0 0 0 1px rgb(0 0 0 / 8%);
			transition: all 200ms ease-out
		}

		.produk-container-item.active {
			box-shadow: 0 0 5px gray;
		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed" id="page-transaksi">
	<div class="wrapper">
		<?php $this->load->view('includes/nav'); ?>

		<?php $this->load->view('includes/aside'); ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-6">
							<h1 class="m-0 text-dark">Transaksi</h1>
						</div><!-- /.col -->
						<div class="col-6">
							<div class="text-right">
								<button class="btn btn-info btn-flat" type="button" id="openFullscreen"><i class="fa fa-expand"></i></button>
								<button class="btn btn-info btn-flat" type="button" id="closeFullscreen"><i class="fa fa-compress"></i></button>
							</div>
						</div>
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-7">
									<div class="form-group">
										<label>Kategori</label>
										<div class="form-inline">
											<select id="barcode" class="form-control select2 col-sm-12" onchange="getProdukByKategori()"></select>
										</div>
									</div>
									<div class="form-group">
										<label>Produk</label>
										<div class="container p-3 row" id="produk-container" style="max-width: auto;">

										</div>
									</div>
								</div>
								<!-- <div class="form-group">
										<label>Jumlah</label>
										<input type="number" class="form-control col-sm-6" placeholder="Jumlah" id="jumlah" onkeyup="checkEmpty()">
									</div> -->
								<div class="col-sm-5 p-3" style="background-color: antiquewhite;">
									<div class="text-right nota mb-2">
										<div class="mb-0">
											<b class="mr-2">Nota</b> <span id="nota"><?= generateNota() ?></span>
										</div>
										<span id="total" style="font-size: 50px; line-height: 1" class="text-danger">0</span>
									</div>
									<div class="form-group">
										<!-- <button id="tambah" class="btn btn-success" onclick="checkStok()" disabled>Tambah</button> -->
										<button id="bayar" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" disabled>Bayar</button>
									</div>
									<div class="table-responsive">
										<table class="table w-100 table-bordered table-hover" id="transaksi" style="font-size: 12px;">
											<thead>
												<tr>
													<th>Nama</th>
													<th>Harga</th>
													<th>Jumlah</th>
													<th>Actions</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	</div>

	<!-- Modal Tambah Produk -->
	<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group">
						<div class="row mb-3">
							<label class="col-sm-2 col-form-label">Jumlah</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Jumlah" id="jumlah" onkeyup="checkEmpty()" oninput="checkEmpty()">
							</div>
						</div>
						<div class="row mb-3">
							<?php for ($i = 0; $i <= 9; $i++) : ?>
								<button type="button" class="btn btn-sm inputChar text-white mx-1 mb-1" style="background-color: crimson;"><?php echo $i; ?></button>
							<?php endfor; ?>
							<?php for ($i = -1; $i >= -9; $i--) : ?>
								<button type="button" class="btn btn-sm inputChar text-white mx-1 mb-1" style="background-color: crimson;"><?php echo $i; ?></button>
							<?php endfor; ?>
							<button type="button" class="backspaceJumlah btn btn-sm text-white mx-1 mb-1" style="background-color: #0168b1;">Backspace</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button id="tambah" class="btn btn-success" onclick="checkStok()" disabled>Tambah</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Bayar</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="form">
						<div class="row">
							<div class="col-sm-9">
								<div class="form-group" hidden>
									<label>Tanggal</label>
									<input type="text" class="form-control" name="tanggal" id="tanggal" required>
								</div>
								<div class="form-group">
									<label>Pelanggan</label>
									<select name="pelannggan" id="pelanggan" class="form-control select2">
									</select>
								</div>
								<div class="form-group">
									<label>Jumlah Uang</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">Rp.</span>
										</div>
										<input placeholder="Jumlah Uang" type="number" class="form-control text-bold" style="font-size: 16px;" name="jumlah_uang" onkeyup="kembalian()" required>
										<div class="input-group-append">
											<span class="input-group-text">,-</span>
										</div>
									</div>
									<div class="row my-3">
										<?php for ($i = 0; $i <= 9; $i++) : ?>
											<button type="button" class="btn btn-success btn-sm inputCharBayar text-white mx-1 mb-1" style="font-size: 18px;"><?php echo $i; ?></button>
										<?php endfor; ?>
										<button type="button" class="btn btn-success btn-sm inputCharBayarNominal text-white mx-1 mb-1" style="font-size: 18px;">5000</button>
										<button type="button" class="btn btn-success btn-sm inputCharBayarNominal text-white mx-1 mb-1" style="font-size: 18px;">10000</button>
										<button type="button" class="btn btn-success btn-sm inputCharBayarNominal text-white mx-1 mb-1" style="font-size: 18px;">20000</button>
										<button type="button" class="btn btn-success btn-sm inputCharBayarNominal text-white mx-1 mb-1" style="font-size: 18px;">50000</button>
										<button type="button" class="btn btn-success btn-sm inputCharBayarNominal text-white mx-1 mb-1" style="font-size: 18px;">100000</button>
										<button type="button" class="backspaceBayar btn btn-sm text-white mx-2" style="background-color: #0168b1;">Backspace</button>
									</div>
								</div>
								<div class="form-group" hidden>
									<label>Diskon</label>
									<input placeholder="Diskon" type="number" class="form-control" onkeyup="kembalian()" name="diskon">
								</div>
								<div class="form-group">
									<label>Voucher</label>
									<input placeholder="Voucher" type="text" class="form-control" id="voucher" name="voucher">
									<div class="row my-3">
										<?php foreach (range('A', 'Z') as $alpha) : ?>
											<button type="button" class="btn btn-sm inputCharVoucher text-white mx-1 mb-1" style="background-color: crimson;"><?php echo $alpha; ?></button>
										<?php endforeach; ?>
										<?php for ($i = 0; $i <= 9; $i++) : ?>
											<button type="button" class="btn btn-sm inputCharVoucher text-white mx-1 mb-1" style="background-color: black;"><?php echo $i; ?></button>
										<?php endfor; ?>
										<button type="button" class="backspaceVoucher btn btn-sm text-white mx-2" style="background-color: #0168b1;">Backspace</button>
									</div>
								</div>
								<div class="form-group">
									<label>Sumber</label>
									<select name="sumber" id="sumber" class="form-control select2">
									</select>
								</div>
								<div class="form-group">
									<label>Metode Pembayaran</label>
									<br>
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<label class="btn btn-secondary btn-metode-bayar">
											<input type="radio" name="metode_bayar" id="option_tunai" value="tunai"> Tunai
										</label>
										<label class="btn btn-secondary btn-metode-bayar">
											<input type="radio" name="metode_bayar" id="option_qris" value="qris"> QRIS
										</label>
										<label class="btn btn-secondary btn-metode-bayar">
											<input type="radio" name="metode_bayar" id="option_debit" value="debit"> Debit
										</label>
									</div>
								</div>
								<div class="form-group" hidden>
									<input type="text" name="trn_nama_bank" readonly>
									<input type="text" name="trn_no_rek" readonly>
								</div>
							</div>
							<div class="col-sm-3 p-3" style="background-color: bisque;">
								<div style="font-size: 18px;">
									<div class="form-group">
										<b>Bayar:</b>
										<br>
										<span style="font-weight: bold; font-size: 18px;" class="bayar text-danger">Rp. 0,-</span>
									</div>
									<div class="form-group">
										<b>Voucher :</b>
										<br>
										<span style="font-weight: bold; font-size: 18px;" class="diskon text-danger">Rp. 0,-</span>
									</div>
									<div class="form-group" style="background-color: palegreen;">
										<b>TOTAL :</b>
										<br>
										<span style="font-weight: bold; font-size: x-large;" class="total_bayar text-danger"></span>
									</div>
									<div class="form-group">
										<b>Kembalian:</b>
										<br>
										<span style="font-weight: bold; font-size: 18px;" class="kembalian text-danger"></span>
									</div>
								</div>
							</div>
						</div>
						<button id="add" class="btn btn-success" type="submit" onclick="bayar()" disabled>Bayar</button>
						<button id="cetak" class="btn btn-success" type="submit" onclick="bayarCetak()" disabled>Bayar Dan Cetak</button>
						<button class="btn btn-danger" data-dismiss="modal">Close</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	// TODO : MODAL PEMBAYARAN DEBIT
	<!-- Modal -->
	<div class="modal fade" id="modalMetodeBayar" tabindex="-1" role="dialog" aria-labelledby="modalMetodeBayar" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Metode Pembayaran Debit</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label>Nama Bank</label>
						<input type="text" name="nama_bank" id="nama_bank" class="form-control mb-3">
						<?php foreach (range('A', 'Z') as $alpha) : ?>
							<button type="button" class="btn btn-sm inputCharBank text-white mx-1 mb-1" style="background-color: crimson;"><?php echo $alpha; ?></button>
						<?php endforeach; ?>
						<?php for ($i = 0; $i <= 9; $i++) : ?>
							<button type="button" class="btn btn-sm inputCharBank text-white mx-1 mb-1" style="background-color: black;"><?php echo $i; ?></button>
						<?php endfor; ?>
						<button type="button" class="backspaceBank btn btn-sm text-white mx-2" style="background-color: #0168b1;">Backspace</button>
					</div>
					<div class="form-group">
						<label>No. Kartu ATM </label>
						<input type="text" name="no_rek" id="no_rek" class="form-control mb-3">
						<?php for ($i = 0; $i <= 9; $i++) : ?>
							<button type="button" class="btn btn-sm inputCharRek text-white mx-1 mb-1" style="background-color: crimson;"><?php echo $i; ?></button>
						<?php endfor; ?>
						<button type="button" class="backspaceRek btn btn-sm text-white mx-2" style="background-color: #0168b1;">Backspace</button>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="save_debit">Save</button>
				</div>
			</div>
		</div>
	</div>

	<?php $this->load->view('cetak'); ?>
	<!-- <div hidden>
		<div class="cetak-div">
			<div style="max-width: 100%; max-height: 100%; width: 58mm; height: 200mm; margin: auto; font-size: 10px;">
				<br>
				<center>
					<img src="<?= base_url('uploads/logo/') . $this->session->userdata('toko')->logo ?>" alt="">
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
					<div style="font-weight: bold;">
						Noted : <?php echo $this->session->userdata('toko')->noted;	?>				
					</div>	
				</center>
			</div>
		</div>
	</div> -->

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
	<script src="<?php echo base_url('assets/vendor/pritnjs/print.min.js') ?>"></script>
	<script src="<?= base_url() ?>assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
	<script>
		var getUrl = '<?php echo base_url() ?>';
		var produkGetNamaUrl = '<?php echo site_url('produk/get_nama') ?>';
		var produkGetStokUrl = '<?php echo site_url('produk/get_stok') ?>';
		var produkGetByCategory = '<?php echo site_url('produk/get_produk_category') ?>';
		var addUrl = '<?php echo site_url('transaksi/add') ?>';
		var getBarcodeUrl = '<?php echo site_url('produk/get_barcode') ?>';
		var getCategoryUrl = '<?php echo site_url('kategori_produk/search') ?>'
		var pelangganSearchUrl = '<?php echo site_url('pelanggan/search') ?>';
		var sumberSearchUrl = '<?php echo site_url('sumber/search') ?>';
		var cetakUrl = '<?php echo site_url('transaksi/cetak/') ?>';
		var getVoucherUrl = '<?php echo site_url('voucher/getVoucherCode') ?>';
		var getNotaUrl = '<?php echo site_url('transaksi/getNota') ?>';
		var getDataCetak = '<?php echo site_url('transaksi/cetak') ?>';
		var cekPaketPr = '<?php echo site_url('transaksi/cekPaketPr') ?>';
	</script>
	<!-- <script src="<?php echo base_url('assets/js/transaksi.min.js') ?>"></script> -->
	<script src="<?php echo base_url('assets/js/unminify/transaksi.js') ?>"></script>
</body>

</html>