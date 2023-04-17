<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_produk extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('produk_model');
		$this->load->model('kategori_produk_model');
		$this->load->model('auth_model');
	}

	private function menuProduk()
	{
		$dataProduk = $this->produk_model->menuProduk();

		return $dataProduk;
	}

	private function menuKategori()
	{
		$data = $this->kategori_produk_model->read();

		return $data->result();
	}

	private function menuToko()
	{
		$data = $this->auth_model->getToko();

		return $data;
	}

	public function index()
	{
		$data = [
			'toko' => $this->menuToko(),
			'kategori' => $this->menuKategori(),
			'produk' => $this->menuProduk()
		];
		$this->load->view('menu_produk', $data);
	}

}

/* End of file Laporan_penjualan.php */
/* Location: ./application/controllers/Laporan_penjualan.php */
