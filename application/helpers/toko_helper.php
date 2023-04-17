<?php
if (!function_exists('tokoData')) {
	function tokoData()
	{
		$CI = get_instance();
		$CI->load->model('auth_model');
		$toko = $CI->auth_model->getToko();
		return $toko;
	}
}

if(!function_exists('generateNota')) {
	function generateNota()
	{
		$CI = get_instance();
		$CI->load->model('transaksi_model');
		$keyword = 'TRN';
		$tgl = date('dmY');
		$transaction = $CI->transaksi_model->countAll();
		$transaction = $transaction + 1;
		$transaction = sprintf("%04s", $transaction);
		$nota = $keyword . $tgl . $transaction;

		return $nota;
	}
}

if(!function_exists('convertRupiah')){
	function convertRupiah(float $angka)
	{
		$data = 'Rp. ' . number_format($angka, 2, ',', '.');
		return $data;
	}
}
