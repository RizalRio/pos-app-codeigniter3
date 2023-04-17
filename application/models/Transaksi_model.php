<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

	private $table = 'transaksi';
	private $tableStockOut = 'stok_keluar';
	private $tableProdukBahan = 'produk_bahan_pakai';
	private $tableBahanBaku = 'bahan_baku';
	private $tableBahanKeluar = 'stok_keluar_bahan';
	private $tableDetail = 'transaksi_detail';

	private function delete_transaction_detail($id)
	{
		$this->db->where('id_transaksi', $id);
		return $this->db->delete($this->tableDetail);
	}

	public function removeStok($id, $stok)
	{
		$this->db->where('id', $id);
		$this->db->set('stok', $stok);
		return $this->db->update('produk');
	}

	public function countAll()
	{
		$sql = 
		"SELECT
			*
		FROM
			transaksi
		WHERE
			date(tanggal) = current_date
		";

		$data = $this->db->query($sql);

		return $data->num_rows();
	}

	public function addTerjual($id, $jumlah)
	{
		$this->db->where('id', $id);
		$this->db->set('terjual', $jumlah);
		return $this->db->update('produk');;
	}

	public function addStokKeluar($data)
	{
		$this->db->insert($this->tableStockOut, $data);
	}

	public function addBahanKeluar($data)
	{
		$this->db->insert($this->tableBahanKeluar, $data);
	}

	public function addDetail($data)
	{
		$this->db->insert($this->tableDetail, $data);
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->select('transaksi.id, transaksi.tanggal, transaksi.barcode, transaksi.qty, transaksi.total_bayar, transaksi.jumlah_uang, transaksi.diskon, pelanggan.nama as pelanggan');
		$this->db->from($this->table);
		$this->db->join('pelanggan', 'transaksi.pelanggan = pelanggan.id', 'left outer');
		$this->db->order_by('transaksi.id', 'desc');
		return $this->db->get();
	}

	public function delete($id)
	{
		$this->delete_transaction_detail($id);
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getProduk($barcode, $qty)
	{
		$total = explode(',', $qty);
		foreach ($barcode as $key => $value) {
			if($value){
				$this->db->select('nama_produk');
				$this->db->where('id', $value);
				$result = $this->db->get('produk')->row();
				$text = ($result) ? $result->nama_produk : '<span class="text-danger">Produk Telah Dihapus</span>';
			}
			$data[] = '<tr><td>'. $text .' ('.$total[$key].')</td></tr>';
		}
		return join($data);
	}

	public function getProdukV2($transaksi_id)
	{
		$query =
		"SELECT 
			a.id_item, 
			a.id_transaksi, 
			a.id_produk, 
			b.nama_produk, 
			a.qty_beli, 
			a.harga_real_produk, 
			a.harga_total_perproduk 
		FROM 
			transaksi_detail a 
		LEFT JOIN 
			produk b 
			ON b.id = a.id_produk 
		WHERE a.id_transaksi = $transaksi_id
		";
		$data = $this->db->query($query)->result_array();

		$result = [];
		foreach($data as $value){
			$result[] = '<tr><td>'. $value['nama_produk'] .' ('. $value['qty_beli'] .')</td></tr>';
		}

		return join($result);
	}

	public function getProdukV3($transaksi_id, $product_id = null)
	{
		$query = 
		"SELECT 
			a.id_item, 
			a.id_transaksi, 
			a.id_produk, 
			b.nama_produk, 
			a.qty_beli, 
			a.harga_real_produk, 
			a.harga_total_perproduk 
		FROM 
			transaksi_detail a 
		LEFT JOIN 
			produk b 
			ON b.id = a.id_produk 
		WHERE a.id_transaksi = $transaksi_id
		";

		if($product_id != null){
			$query .= "AND a.id_produk = $product_id";
		}

		$get = $this->db->query($query)->result_array();

		return $get;
	}

	public function getBahanBaku($id)
	{
		$this->db->select('produk_bahan_pakai.id_bahan, produk_bahan_pakai.jumlah, bahan_baku.stok');
		$this->db->from($this->tableProdukBahan);
		$this->db->join($this->tableBahanBaku, 'bahan_baku.id_bahan = produk_bahan_pakai.id_bahan');
		$this->db->where('produk_bahan_pakai.id_produk', $id);
		return $this->db->get();
	}

	public function removeBahan($id, $stok)
	{
		$this->db->where('id_bahan', $id);
		$this->db->set('stok', $stok);
		return $this->db->update('bahan_baku');
	}

	public function penjualanBulan($date)
	{
		$qty = $this->db->query("SELECT qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$date'")->result();
		$d = [];
		$data = [];
		foreach ($qty as $key) {
			$d[] = explode(',', $key->qty);
		}
		foreach ($d as $key) {
			$data[] = array_sum($key);
		}
		return $data;
	}

	public function grafikPenjualan()
	{
		return $this->db->query("SELECT DISTINCT(date(tanggal)) AS tanggal, COUNT(id) as jumlah FROM transaksi WHERE DATE(tanggal) >= (CURRENT_DATE() - INTERVAL 10 DAY)  GROUP BY date(tanggal) ORDER BY date(tanggal) ASC, id DESC")->result();
	}
	public function grafikPenjualanPerDate($date)
	{
		return $this->db->query("SELECT DISTINCT(date(tanggal)) AS tanggal, COUNT(id) as jumlah FROM transaksi WHERE DATE(tanggal) = '$date' GROUP BY date(tanggal)")->row();
	}

	public function transaksiHari($hari)
	{
		return $this->db->query("SELECT COUNT(*) AS total FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
	}

	public function transaksiTerakhir($hari)
	{
		return $this->db->query("SELECT transaksi.qty FROM transaksi WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari' LIMIT 1")->row();
	}

	public function nominalTransaksiHari()
	{
		return $this->db->query("SELECT SUM(transaksi.total_bayar) AS jumlah_total FROM `transaksi` WHERE date(transaksi.tanggal) = CURRENT_DATE()")->row();
	}

	public function getAll($id)
	{
		$this->db->select('transaksi.nota, transaksi.tanggal, transaksi.barcode, transaksi.qty, transaksi.total_bayar, transaksi.jumlah_uang, transaksi.diskon, pengguna.nama as kasir');
		$this->db->from('transaksi');
		$this->db->join('pengguna', 'transaksi.kasir = pengguna.id');
		$this->db->where('transaksi.id', $id);
		return $this->db->get()->row();
	}

	public function getName($barcode)
	{
		foreach ($barcode as $b) {
			$this->db->select('nama_produk, harga');
			$this->db->where('id', $b);
			$data[] = $this->db->get('produk')->row();
		}
		return $data;
	}

	public function getTransactionByDate($start, $end, $search = null, $product = null)
	{
		$this->db->select('transaksi.id, transaksi.tanggal, transaksi.barcode, transaksi.metode_bayar, transaksi.qty, transaksi.total_bayar, transaksi.jumlah_uang, transaksi.diskon, pelanggan.nama AS pelanggan, transaksi.nota, pengguna.nama AS kasir, buy_from.nama_sumber AS sumber');
		$this->db->from($this->table);
		$this->db->join('pelanggan', 'transaksi.pelanggan = pelanggan.id', 'left outer');
		$this->db->join('pengguna', 'transaksi.kasir=pengguna.id', 'left outer');
		$this->db->join('buy_from', 'transaksi.sumber=buy_from.id_sumber', 'left outer');
		$this->db->where('date(transaksi.tanggal) >=', $start);
		$this->db->where('date(transaksi.tanggal) <=', $end);
		if($search !== null){
			$this->db->where('transaksi.sumber', $search);
		}
		if($product !== null){
			$this->db->like('transaksi.barcode', $product, 'both');
		}
		
		return $this->db->get()->result();
	}

	public function getTransactionConclusion($start, $end, $product = null)
	{
		if($product){
			$searchProduct = "AND transaksi_detail.id_produk = $product";   
		}else{
			$searchProduct = '';
		}

		$query =
		"SELECT 
			transaksi_detail.id_produk, 
			produk.nama_produk,
			produk.harga, 
			SUM(transaksi_detail.qty_beli) AS jumlah 
		FROM transaksi_detail 
		JOIN produk 
			ON transaksi_detail.id_produk = produk.id 
		WHERE 
			date(transaksi_detail.created_at) >= '$start'
			AND date(transaksi_detail.created_at) <= '$end'
			$searchProduct
		GROUP BY transaksi_detail.id_produk 
		";

		return $this->db->query($query)->result();
	}
}

/* End of file Transaksi_model.php */
/* Location: ./application/models/Transaksi_model.php */
