<?php
	defined('BASEPATH') or exit('No direct script access allowed');

	class Stok_masuk_bahan_model extends CI_Model
	{

		private $table = 'stok_masuk_bahan';

		public function create($data)
		{
			return $this->db->insert($this->table, $data);
		}

		public function read()
		{
			$this->db->select('stok_masuk_bahan.id_masuk AS id, bahan_baku.id_bahan AS id_bahan, bahan_baku.nama_bahan AS nama_bahan, stok_masuk_bahan.tgl_masuk AS tgl, stok_masuk_bahan.jumlah_masuk AS jumlah, stok_masuk_bahan.keterangan AS keterangan');
			$this->db->from($this->table);
			$this->db->join('bahan_baku', 'stok_masuk_bahan.id_bahan = bahan_baku.id_bahan');
			$this->db->order_by('stok_masuk_bahan.id_masuk', 'desc');
			return $this->db->get();
		}

		public function laporan()
		{
			$this->db->select('stok_masuk.tanggal, stok_masuk.jumlah, stok_masuk.keterangan, produk.barcode, produk.nama_produk, supplier.nama as supplier');
			$this->db->from($this->table);
			$this->db->join('produk', 'produk.id = stok_masuk.barcode');
			$this->db->join('supplier', 'supplier.id = stok_masuk.supplier', 'left outer');
			return $this->db->get();
		}

		public function getStok($id)
		{
			$this->db->select('stok');
			$this->db->where('id_bahan', $id);
			return $this->db->get('bahan_baku')->row();
		}

		public function addStok($id, $stok)
		{
			$this->db->where('id_bahan', $id);
			$this->db->set('stok', $stok);
			return $this->db->update('bahan_baku');
		}

		public function stokHari($hari)
		{
			return $this->db->query("SELECT SUM(jumlah) AS total FROM stok_masuk WHERE DATE_FORMAT(tanggal, '%d %m %Y') = '$hari'")->row();
		}

		public function getBahanMasukByDate($start, $end)
		{
			$this->db->select('stok_masuk_bahan.id_masuk AS id, bahan_baku.id_bahan AS id_bahan, bahan_baku.nama_bahan AS nama_bahan, stok_masuk_bahan.tgl_masuk AS tgl, stok_masuk_bahan.jumlah_masuk AS jumlah, stok_masuk_bahan.keterangan AS keterangan');
			$this->db->from($this->table);
			$this->db->join('bahan_baku', 'stok_masuk_bahan.id_bahan = bahan_baku.id_bahan');
			$this->db->where('date(stok_masuk_bahan.tgl_masuk) >=', $start);
			$this->db->where('date(stok_masuk_bahan.tgl_masuk) <=', $end);
			$this->db->order_by('stok_masuk_bahan.id_masuk', 'desc');
			return $this->db->get()->result();
		}
	}

/* End of file Stok_masuk_model.php */
/* Location: ./application/models/Stok_masuk_model.php */
