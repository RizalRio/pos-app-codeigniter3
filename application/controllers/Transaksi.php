<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('transaksi_model');
		$this->load->model('produk_model');
		$this->load->model('kategori_produk_model');
	}

	public function index()
	{
		$this->load->view('transaksi');
	}

	public function getNota()
	{
		header('Content-type: application/json');
		$keyword = 'TRN';
		$tgl = date('dmY');
		$transaction = $this->transaksi_model->countAll();
		$transaction = $transaction + 1;
		$transaction = sprintf("%04s", $transaction);
		$nota = $keyword . $tgl . $transaction;
		echo json_encode($nota);
	}

	public function read()
	{
		// header('Content-type: application/json');
		if ($this->transaksi_model->read()->num_rows() > 0) {
			foreach ($this->transaksi_model->read()->result() as $transaksi) {
				$barcode = explode(',', $transaksi->barcode);
				$tanggal = new DateTime($transaksi->tanggal);
				$action = '<button type="button" class="btn btn-sm btn-success mr-2 btn-cetak-modal" data-toggle="modal" data-target="#modelId" data-id="'. $transaksi->id .'"><i class="fas fa-print"></i></button>';
				if($this->session->userdata('role') === "admin" ){
					$action .= '<button class="btn btn-sm btn-danger" onclick="remove(' . $transaksi->id . ')"><i class="fas fa-trash"></i></button>';
				}
				$data[] = array(
					'tanggal' => $tanggal->format('d-m-Y H:i:s'),
					'nama_produk' => '<table>'.$this->transaksi_model->getProdukV2($transaksi->id).'</table>',
					'total_bayar' => convertRupiah($transaksi->total_bayar),
					'jumlah_uang' => convertRupiah($transaksi->jumlah_uang),
					'diskon' => $transaksi->diskon,
					'pelanggan' => $transaksi->pelanggan,
					'action' => $action
					/* 'action' => '<a class="btn btn-sm btn-success" href="'.site_url('transaksi/cetak/').$transaksi->id.'" target="blank">Print</a> ' */
				);
			}
		} else {
			$data = array();
		}
		$transaksi = array(
			'data' => $data
		);
		echo json_encode($transaksi);
	}

	public function add()
	{
		$barcodeDetail = json_decode($this->input->post('produk'));
		$produk = json_decode($this->input->post('produk'));
		$tanggal = new DateTime($this->input->post('tanggal'));
		$barcode = array();
		$barcodeQty = array();
		$qty = $this->input->post('qty');

		foreach($barcodeDetail as $barcodeDetail){
			array_push($barcode, $barcodeDetail->id);
			array_push($barcodeQty, $barcodeDetail->terjual);
		}

		$data = array(
			'tanggal' => $tanggal->format('Y-m-d H:i:s'),
			'barcode' => implode(',', $barcode),
			'qty' => implode(',', $barcodeQty),
			'total_bayar' => $this->input->post('total_bayar'),
			'jumlah_uang' => $this->input->post('jumlah_uang'),
			'diskon' => $this->input->post('diskon'),
			'id_vcr' => $this->input->post('diskon_id'),
			'nominal_discvcr' => $this->input->post('diskon'),
			'sumber' => $this->input->post('sumber'),
			'pelanggan' => $this->input->post('pelanggan'),
			'nota' => $this->input->post('nota'),
			'kasir' => $this->session->userdata('id'),
			'metode_bayar' => $this->input->post('metode_bayar'),
			'debit_bank' => ($this->input->post('metode_bayar') == 'debit') ? $this->input->post('trn_nama_bank') : null,
			'debit_rek' => ($this->input->post('metode_bayar') == 'debit') ? $this->input->post('trn_no_rek') : null
		); 

		if ($this->transaksi_model->create($data)) {
			$transactionId = $this->db->insert_id();
			echo json_encode($transactionId);

			$qtyKey = 0;
			foreach ($produk as $produk) {
				$this->transaksi_model->removeStok($produk->id, $produk->stok);
				$this->transaksi_model->addTerjual($produk->id, $produk->terjual);

				$stockOut = [
					'tanggal' => $tanggal->format('Y-m-d H:i:s'),
					'barcode' => $produk->id,
					'jumlah' => $produk->terjual,
					'keterangan' => 'Terjual'
				];

				$this->transaksi_model->addStokKeluar($stockOut);

				$dataProduk = $this->produk_model->getProduk($produk->id);
				$dataProduk = $dataProduk->row();
				$detail = [
					'id_transaksi' => $transactionId,
					'id_produk' => $produk->id,
					'qty_beli' => $produk->terjual,
					'harga_real_produk' => $dataProduk->harga,
					'harga_total_perproduk' => $dataProduk->harga * $produk->terjual
				];
	
				if($dataProduk->tag_paket == 1){
					$data = $this->produk_model->getPaketPr($produk->id);

					foreach ($data as $value) {
						$hasil = $value['stok'] - ($value['qty'] * $produk->terjual);
						if ($hasil > 0) {
							$this->transaksi_model->removeStok($value['id_pd_paket'], $hasil);

							$stockOut = [
								'tanggal' => $tanggal->format('Y-m-d H:i:s'),
								'barcode' => $value['id_pd_paket'],
								'jumlah' => $value['qty'] * $produk->terjual,
								'keterangan' => 'Paket Terjual'
							];

							$this->transaksi_model->addStokKeluar($stockOut);
						}
					}
				}

				$this->transaksi_model->addDetail($detail);
				$qtyKey = $qtyKey + 1;

			}

			//TODO : PENGURANGAN STOK PADA PAKET PRODUK
		}
		$data = $this->input->post('form');
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->transaksi_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function cetak()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		$produk = $this->transaksi_model->getAll($id);
		
		$tanggal = new DateTime($produk->tanggal);
		$barcode = explode(',', $produk->barcode);
		$qty = explode(',', $produk->qty);

		$produk->tanggal = $tanggal->format('d m Y H:i:s');

		$dataProduk = $this->transaksi_model->getName($barcode);
		foreach ($dataProduk as $key => $value) {
			$value->total = $qty[$key];
			$value->satuan = $value->harga;
			$value->harga = $value->harga * $qty[$key];
		}

		$data = array(
			'nota' => $produk->nota,
			'tanggal' => $produk->tanggal,
			'produk' => $dataProduk,
			'total' => $produk->total_bayar,
			'diskon' => $produk->diskon,
			'bayar' => $produk->jumlah_uang,
			'kembalian' => $produk->jumlah_uang - ($produk->total_bayar - $produk->diskon),
			'kasir' => $produk->kasir
		);
		
		echo json_encode($data);
	}

	public function penjualan_bulan()
	{
		header('Content-type: application/json');
		$day = $this->input->post('day');
		foreach ($day as $key => $value) {
			$now = date($day[$value].' m Y');
			if ($qty = $this->transaksi_model->penjualanBulan($now) !== []) {
				$data[] = array_sum($this->transaksi_model->penjualanBulan($now));
			} else {
				$data[] = 0;
			}
		}
		echo json_encode($data);
	}

	public function grafikPenjualan()
	{
		header('Content-type: application/json');
		$format = date('Y-m-d');
		$formatInt = date("Y-m-d", strtotime("$format -1 month"));;
		$end = new DateTime($format);
		$begin = new DateTime($formatInt);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		$grafik = [];

		
		foreach ($period as $date) {
			$perDate = $this->transaksi_model->grafikPenjualanPerDate($date->format("Y-m-d"));
			
			$array = [
				'tanggal' => $date->format("m-d"),
				'jumlah' => ($perDate != null) ? $perDate->jumlah : 0
			];
			
			array_push($grafik, $array);
		}
	
		echo json_encode($grafik);
	}

	public function transaksi_hari()
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		$total = $this->transaksi_model->transaksiHari($now);
		echo json_encode($total);
	}

	public function transaksi_terakhir($value='')
	{
		header('Content-type: application/json');
		$now = date('d m Y');
		foreach ($this->transaksi_model->transaksiTerakhir($now) as $key) {
			$total = explode(',', $key);
		}
		echo json_encode($total);
	}

	public function transaksi_nominal()
	{
		header('Content-type: application/json');
		$total = $this->transaksi_model->nominalTransaksiHari();
		echo json_encode($total);
	}

	public function CekPaketPr()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		$jumlah = $this->input->post('jumlah');
		$return = true;
		$data = $this->produk_model->getPaketPr($id);

		foreach($data AS $value){
			$hasil = $value['stok'] - ($value['qty'] * $jumlah);
			if($hasil < 0){
				$return = false;
				break;
			} 
		}

		echo json_encode($return);
	}
}

/* End of file Transaksi.php */
/* Location: ./application/controllers/Transaksi.php */
