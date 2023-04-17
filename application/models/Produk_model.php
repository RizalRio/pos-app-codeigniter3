<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model {

	private $table = 'produk';
	private $produkBahan = 'produk_bahan_pakai';
	private $tableBahanBaku = 'bahan_baku';
	private $tableBahanKeluar = 'stok_keluar_bahan';
	private $tablePaketProduk = 'item_pd_paket';  

	public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function create_bahan($data)
	{
		return $this->db->insert_batch($this->produkBahan, $data);
	}

	public function add_paket_pr($data)
	{
		return $this->db->insert_batch($this->tablePaketProduk,  $data);
	}

	public function read()
	{
		$this->db->select('produk.id, produk.gambar,produk.barcode, produk.nama_produk, produk.harga, produk.stok, kategori_produk.kategori, satuan_produk.satuan');
		$this->db->from($this->table);
		$this->db->join('kategori_produk', 'produk.kategori = kategori_produk.id');
		$this->db->join('satuan_produk', 'produk.satuan = satuan_produk.id');
		return $this->db->get();
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}

	public function getLastId()
	{
		$this->db->select("*");
		$this->db->from($this->table);
		$this->db->limit(1);
		$this->db->order_by('id', "DESC");
		$query = $this->db->get();
		$result = $query->row();
		return $result;
	}

	public function getProduk($id)
	{
		$this->db->select('produk.id, produk.barcode, produk.nama_produk, produk.tagline, produk.harga, produk.stok, produk.gambar, produk.tag_paket, kategori_produk.id as kategori_id, kategori_produk.kategori, satuan_produk.id as satuan_id, satuan_produk.satuan');
		$this->db->from($this->table);
		$this->db->join('kategori_produk', 'produk.kategori = kategori_produk.id');
		$this->db->join('satuan_produk', 'produk.satuan = satuan_produk.id');
		$this->db->where('produk.id', $id);
		return $this->db->get();
	}

	public function getProdukBahan($id)
	{
		$this->db->select('produk_bahan_pakai.id_bahan, bahan_baku.nama_bahan, produk_bahan_pakai.jumlah');
		$this->db->from($this->produkBahan);
		$this->db->join('bahan_baku', 'produk_bahan_pakai.id_bahan=bahan_baku.id_bahan');
		$this->db->where('produk_bahan_pakai.id_produk', $id);
		return $this->db->get();
	}

	public function getProdukPaket($id)
	{
		$query =
		"SELECT 
			a.id_pd_paket, b.nama_produk, a.qty 
		FROM `item_pd_paket` a 
		LEFT JOIN produk b 
			ON a.id_pd_paket = b.id 
		WHERE a.id_produk = '$id'
		";

		return $this->db->query($query);
	}

	public function deleteProdukBahan($id)
	{
		$this->db->where('id_produk', $id);
		return $this->db->delete($this->produkBahan);
	}

	public function deletePaketProduk($id)
	{
		$this->db->where('id_produk', $id);
		return $this->db->delete($this->tablePaketProduk);
	}

	public function getProdukByKategori($id = null)
	{
		$this->db->select('produk.id, produk.barcode, produk.nama_produk, produk.harga, produk.stok, produk.gambar, produk.tagline, produk.gambar_cropped, kategori_produk.id as kategori_id, kategori_produk.kategori, satuan_produk.id as satuan_id, satuan_produk.satuan');
		$this->db->from($this->table);
		$this->db->join('kategori_produk', 'produk.kategori = kategori_produk.id');
		$this->db->join('satuan_produk', 'produk.satuan = satuan_produk.id');
		if($id){
			$this->db->where('kategori_produk.id', $id);
		}
		return $this->db->get();
	}

	public function getBarcode($search='')
	{
		$this->db->select('produk.id, produk.barcode, produk.nama_produk');
		$this->db->like('barcode', $search);
		return $this->db->get($this->table)->result();
	}

	public function getNama($id)
	{
		$this->db->select('nama_produk, stok');
		$this->db->where('id', $id);
		return $this->db->get($this->table)->row();
	}

	public function getStok($id)
	{
		$this->db->select('stok, nama_produk, harga, barcode, tag_paket');
		$this->db->where('id', $id);
		return $this->db->get($this->table)->row();
	}

	public function produkTerlaris()
	{
		$query =
		"SELECT 
			transaksi_detail.id_produk, 
			produk.nama_produk, 
			SUM(transaksi_detail.qty_beli) AS Jumlah 
		FROM transaksi_detail 
		JOIN produk 
			ON transaksi_detail.id_produk = produk.id 
		WHERE 
			MONTH(transaksi_detail.created_at) = MONTH(CURRENT_DATE) 
		GROUP BY transaksi_detail.id_produk 
		ORDER BY Jumlah DESC 
		LIMIT 5
		";
		return $this->db->query($query)->result();
	}

	public function dataStok()
	{
		return $this->db->query('SELECT produk.nama_produk, produk.stok FROM `produk` ORDER BY CONVERT(stok, decimal) DESC LIMIT 50')->result();
	}

	public function addBahanKeluar($data)
	{
		$this->db->insert($this->tableBahanKeluar, $data);
	}

	public function getBahanBaku($id)
	{
		$this->db->select('bahan_baku.id_bahan, bahan_baku.stok');
		$this->db->from($this->tableBahanBaku);
		$this->db->where('bahan_baku.id_bahan', $id);
		return $this->db->get();
	}

	public function get_bahan_satuan($id)
	{
		$this->db->select('bahan_baku.id_bahan, bahan_baku.satuan, satuan_produk.satuan');
		$this->db->from($this->tableBahanBaku);
		$this->db->join('satuan_produk', 'satuan_produk.id=bahan_baku.satuan');
		$this->db->where('bahan_baku.id_bahan', $id);
		return $this->db->get()->row();
	}

	public function removeBahan($id, $stok)
	{ 
		$this->db->where('id_bahan', $id);
		$this->db->set('stok', $stok);
		return $this->db->update('bahan_baku');
	}

	public function menuProduk()
	{
		$query =
		"SELECT 
			a.id, a.barcode, a.nama_produk, a.harga, a.gambar_cropped, a.kategori, a.tagline, b.kategori as kategori_nama
		FROM produk a 
		LEFT JOIN kategori_produk b 
			ON a.kategori = b.id 
		ORDER BY b.kategori 
		";

		return $this->db->query($query)->result();
	}

	public function getPaketPr($id)
	{
		$query =
		"SELECT 
			a.id_pd_paket, b.nama_produk, a.qty, b.stok 
		FROM item_pd_paket a 
		LEFT JOIN produk b ON a.id_pd_paket = b.id 
		WHERE
			a.id_produk = '$id'
		";

		return $this->db->query($query)->result_array();
	}
}

/* End of file Produk_model.php */
/* Location: ./application/models/Produk_model.php */
