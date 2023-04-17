<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Menu Makanan dan Minuman</title>
	<meta content="" name="description">
	<meta content="" name="keywords">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

	<!-- Vendor CSS Files -->
	<link href="<?= base_url() ?>assets/vendor/animate.css/animate.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
	<!-- Template Main CSS File -->
	<link href="<?= base_url() ?>assets/css/menu_produk.css" rel="stylesheet">
</head>

<body class="h-100">
	<section id="menu" class="menu h-100">
		<div class="container" data-aos="fade-up">

			<div class="section-title">
				<h2>Menu</h2>
				<div class="d-flex justify-content-center">
					<div class="row">
						<div class="col-12 d-flex justify-content-center">
							<img src="<?= base_url('uploads/logo/' . $toko->logo) ?>" class="menu-img" alt="" style="width: 90px;">
						</div>
						<div class="col-12 d-flex justify-content-center">
							<p><?= $toko->nama ?></p>
						</div>
					</div>
				</div>
			</div>

			<div class="row" data-aos="fade-up" data-aos-delay="100">
				<div class="col-lg-12 d-flex justify-content-center">
					<ul id="menu-flters">
						<li data-filter="*" class="filter-active">All</li>
						<?php foreach ($kategori as $result) : ?>
							<li data-filter=".filter-<?= $result->id ?>"><?= $result->kategori ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<div class="row menu-container" data-aos="fade-up" data-aos-delay="200">
				<?php foreach ($produk as $result) : ?>
					<div class="col-lg-6 menu-item filter-<?= $result->kategori ?>">
						<img src="<?= base_url('uploads/produk/cropped/' . $result->gambar_cropped) ?>" class="menu-img" alt="">
						<div class="menu-content">
							<a href="#"><?= $result->nama_produk ?></a><span>Rp. <?= $result->harga ?>,-</span>
						</div>
						<div class="menu-ingredients">
							<?= $result->tagline ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

		</div>
	</section><!-- End Menu Section -->

	<div class="d-flex justify-content-center text-center mb-4">
		<div class="container">
			<div class="copyright">
				<p>Terimakasih</p>
				<p style="color: #cda45e;"><strong><span><?php echo $toko->nama; ?><br></span></strong></p>
				<p>Instagram : <strong><span><?php echo $toko->instagram; ?></span></strong></p>
				<p> No. Telp : <strong><span><?php echo $toko->tlpn; ?><br></span></strong></p>
			</div>
			<div class=" credits" style="color: #cda45e;"><?php echo $toko->noted; ?>
			</div>
		</div>
	</div>

	<!-- Vendor JS Files -->
	<script src="<?= base_url() ?>assets/vendor/aos/aos.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/glightbox/js/glightbox.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/php-email-form/validate.js"></script>

	<!-- Template Main JS File -->
	<script src="<?= base_url() ?>assets/js/unminify/menu_produk.js"></script>

</body>

</html>
