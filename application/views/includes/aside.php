<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?php echo site_url('') ?>" class="brand-link text-center">
		<span class="brand-text font-weight-light"><?php echo $this->session->userdata('toko')->nama ?></span>
	</a>
	<?php $uri = $this->uri->segment(1) ?>
	<?php $role = $this->session->userdata('role'); ?>

	<!-- Sidebar -->
	<div class="sidebar">

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
				<li class="nav-item">
					<a href="<?php echo site_url('dashboard') ?>" class="nav-link <?php echo $uri == 'dashboard' || $uri == '' ? 'active' : 'no' ?>">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>
							Dashboard
						</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="<?php echo site_url('transaksi') ?>" class="nav-link <?php echo $uri == 'transaksi' ? 'active' : 'no' ?>">
						<i class="fas fa-money-bill nav-icon"></i>
						<p>Transaksi</p>
					</a>
				</li>
				<?php if ($role === 'admin' || $role === 'superadmin') : ?>
					<li class="nav-item">
						<a href="<?php echo site_url('supplier') ?>" class="nav-link <?php echo $uri == 'supplier' ? 'active' : 'no' ?>">
							<i class="nav-icon fas fa-truck"></i>
							<p>
								Supplier
							</p>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo site_url('pelanggan') ?>" class="nav-link <?php echo $uri == 'pelanggan' ? 'active' : 'no' ?>">
							<i class="nav-icon fas fa-address-book"></i>
							<p>
								Pelanggan
							</p>
						</a>
					</li>
					<li class="nav-item has-treeview <?php echo $uri == 'produk' || $uri == 'kategori_produk' || $uri == 'satuan_produk' || $uri == "bahan_baku" ? 'menu-open' : 'no' ?>">
						<a href="#" class="nav-link <?php echo $uri == 'produk' || $uri == 'kategori_produk' || $uri == 'satuan_produk' || $uri == "bahan_baku" ? 'active' : 'no' ?>">
							<i class="nav-icon fas fa-box"></i>
							<p>
								Produk
							</p>
							<i class="right fas fa-angle-right"></i>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="<?php echo site_url('kategori_produk') ?>" class="nav-link <?php echo $uri == 'kategori_produk' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>
										Kategori Produk
									</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('satuan_produk') ?>" class="nav-link <?php echo $uri == 'satuan_produk' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>
										Satuan Produk
									</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('produk') ?>" class="nav-link <?php echo $uri == 'produk' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>
										Produk
									</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('bahan_baku') ?>" class="nav-link <?php echo $uri == 'bahan_baku' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>
										Bahan Baku
									</p>
								</a>
							</li>
						</ul>
					</li>
					<li class="nav-item has-treeview <?php echo $uri == 'stok_masuk' || $uri == 'stok_keluar' || $uri == 'stok_masuk_bahan' || $uri == 'stok_keluar_bahan' ? 'menu-open' : 'no' ?>">
						<a href="#" class="nav-link <?php echo $uri == 'stok_masuk' || $uri == 'stok_keluar' || $uri == 'stok_masuk_bahan' || $uri == 'stok_keluar_bahan' ? 'active' : 'no' ?>">
							<i class="fas fa-archive nav-icon"></i>
							<p>Info Stok</p>
							<i class="right fas fa-angle-right"></i>
						</a>
						<ul class="nav-treeview">
							<li class="nav-item">
								<a href="<?php echo site_url('stok_masuk') ?>" class="nav-link <?php echo $uri == 'stok_masuk' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Stok Masuk Produk</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('stok_keluar') ?>" class="nav-link <?php echo $uri == 'stok_keluar' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Stok Keluar Produk</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('stok_masuk_bahan') ?>" class="nav-link <?php echo $uri == 'stok_masuk_bahan' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Stok Masuk Bahan</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('stok_keluar_bahan') ?>" class="nav-link <?php echo $uri == 'stok_keluar_bahan' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Stok Keluar Bahan</p>
								</a>
							</li>
						</ul>
					</li>
				<?php endif ?>
				<li class="nav-item has-treeview <?php echo $uri == 'laporan_penjualan' || $uri == 'laporan_stok_masuk' || $uri == 'laporan_stok_keluar' || $uri == 'laporan_bahan_masuk' || $uri == 'laporan_bahan_keluar' ? 'menu-open' : 'no' ?>">
					<a href="<?php echo site_url('laporan') ?>" class="nav-link <?php echo $uri == 'laporan_penjualan' || $uri == 'laporan_stok_masuk' || $uri == 'laporan_stok_keluar' || $uri == 'laporan_bahan_masuk' || $uri == 'laporan_bahan_keluar' ? 'active' : 'no' ?>">
						<i class="fas fa-book nav-icon"></i>
						<p>Laporan</p>
						<i class="right fas fa-angle-right"></i>
					</a>
					<ul class="nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('laporan_penjualan') ?>" class="nav-link <?php echo $uri == 'laporan_penjualan' ? 'active' : 'no' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Laporan Penjualan</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('laporan_stok_masuk') ?>" class="nav-link <?php echo $uri == 'laporan_stok_masuk' ? 'active' : 'no' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Laporan Stok Masuk</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('laporan_stok_keluar') ?>" class="nav-link <?php echo $uri == 'laporan_stok_keluar' ? 'active' : 'no' ?>">
								<i class="far fa-circle nav-icon"></i>
								<p>Laporan Stok Keluar</p>
							</a>
						</li>
						<?php if ($role === "admin" || $role === 'superadmin') : ?>
							<li class="nav-item">
								<a href="<?php echo site_url('laporan_bahan_masuk') ?>" class="nav-link <?php echo $uri == 'laporan_bahan_masuk' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Laporan Bahan Masuk</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('laporan_bahan_keluar') ?>" class="nav-link <?php echo $uri == 'laporan_bahan_keluar' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Laporan Bahan Keluar</p>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</li>
				<?php if ($role === 'admin' || $role === 'superadmin') : ?>
					<li class="nav-item has-treeview <?php echo $uri == 'pengaturan' || $uri == 'voucher' ? 'menu-open' : 'no' ?>">
						<a href="#" class="nav-link <?php echo $uri == 'pengaturan' || $uri == 'voucher' ? 'active' : 'no' ?>">
							<i class="fas fa-cog nav-icon"></i>
							<p>Pengaturan</p>
							<i class="right fas fa-angle-right"></i>
						</a>
						<ul class="nav-treeview">
							<li class="nav-item">
								<a href="<?php echo site_url('pengaturan') ?>" class="nav-link <?php echo $uri == 'pengaturan' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Pengaturan</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('voucher') ?>" class="nav-link <?php echo $uri == 'voucher' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Setting Voucher</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="<?php echo site_url('sumber') ?>" class="nav-link <?php echo $uri == 'sumber' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Setting Sumber</p>
								</a>
							</li>
						</ul>
					</li>
					<li class="nav-item has-treeview <?php echo $uri == 'pengguna' || $uri == 'hak_akses' ? 'menu-open' : 'no' ?>">
						<a href="#" class="nav-link <?php echo $uri == 'pengguna' || $uri == 'hak_akses' ? 'active' : 'no' ?>">
							<i class="fas fa-user nav-icon"></i>
							<p>Pengguna</p>
							<i class="right fas fa-angle-right"></i>
						</a>
						<ul class="nav-treeview">
							<li class="nav-item">
								<a href="<?php echo site_url('pengguna') ?>" class="nav-link <?php echo $uri == 'pengguna' ? 'active' : 'no' ?>">
									<i class="far fa-circle nav-icon"></i>
									<p>Pengguna</p>
								</a>
							</li>
							<?php if ($role === 'superadmin') : ?>
								<li class="nav-item">
									<a href="<?php echo site_url('hak_akses') ?>" class="nav-link <?php echo $uri == 'hak_akses' ? 'active' : 'no' ?>">
										<i class="far fa-circle nav-icon"></i>
										<p>Hak Akses</p>
									</a>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				<?php endif ?>

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>