<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_keluar_bahan_model extends CI_Model
{

	private $table = 'stok_keluar_bahan';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->select('stok_keluar_bahan.tgl_keluar AS tanggal, stok_keluar_bahan.jumlah_masuk AS jumlah, stok_keluar_bahan.keterangan AS keterangan, bahan_baku.nama_bahan AS nama');
		$this->db->from($this->table);
		$this->db->join('bahan_baku', 'stok_keluar_bahan.id_bahan = bahan_baku.id_bahan');
		$this->db->order_by('stok_keluar_bahan.id_keluar', 'desc');
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

	public function getBahanKeluarByDate($start, $end)
	{
		$this->db->select('stok_keluar_bahan.tgl_keluar AS tanggal, stok_keluar_bahan.jumlah_masuk AS jumlah, stok_keluar_bahan.keterangan AS keterangan, bahan_baku.nama_bahan AS nama');
		$this->db->from($this->table);
		$this->db->join('bahan_baku', 'stok_keluar_bahan.id_bahan = bahan_baku.id_bahan');
		$this->db->where('date(stok_keluar_bahan.tgl_keluar) >=', $start);
		$this->db->where('date(stok_keluar_bahan.tgl_keluar) <=', $end);
		$this->db->order_by('stok_keluar_bahan.id_keluar', 'desc');
		return $this->db->get()->result();
	}
}

/* End of file Stok_keluar_model.php */
/* Location: ./application/models/Stok_keluar_model.php */
