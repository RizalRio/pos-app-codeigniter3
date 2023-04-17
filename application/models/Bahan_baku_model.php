<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bahan_baku_model extends CI_Model
{

	private $table = 'bahan_baku';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->select('bahan_baku.id_bahan AS id, bahan_baku.nama_bahan AS nama, satuan_produk.satuan AS satuan, bahan_baku.harga AS harga, bahan_baku.stok AS stok, bahan_baku.terpakai AS terpakai, bahan_baku.gambar AS gambar');
		$this->db->from($this->table);
		$this->db->join('satuan_produk', 'bahan_baku.satuan = satuan_produk.id');
		return $this->db->get();
	}

	public function update($id, $data)
	{
		$this->db->where('id_bahan', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id_bahan', $id);
		return $this->db->delete($this->table);
	}

	public function getBahan($id)
	{
		$this->db->select('bahan_baku.id_bahan AS id, bahan_baku.nama_bahan AS nama, satuan_produk.id AS satuan_id, satuan_produk.satuan AS satuan, bahan_baku.harga AS harga, bahan_baku.stok AS stok, bahan_baku.terpakai AS terpakai, bahan_baku.gambar AS gambar');
		$this->db->from($this->table);
		$this->db->join('satuan_produk', 'bahan_baku.satuan = satuan_produk.id');
		$this->db->where('bahan_baku.id_bahan', $id);
		return $this->db->get();
	}

	public function getBahanSelect($search = '')
	{
		$this->db->select('bahan_baku.id_bahan AS id, bahan_baku.nama_bahan AS nama, satuan_produk.satuan AS satuan');
		$this->db->from($this->table);
		$this->db->join('satuan_produk', 'bahan_baku.satuan = satuan_produk.id', 'left');
		$this->db->like('bahan_baku.nama_bahan', $search);
		return $this->db->get()->result();
	}
}

/* End of file Produk_model.php */
/* Location: ./application/models/Produk_model.php */
