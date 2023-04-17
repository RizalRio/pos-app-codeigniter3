<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sumber_model extends CI_Model
{

	private $table = 'buy_from';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->select('id_sumber, nama_sumber, keterangan');
		$this->db->from($this->table);
		return $this->db->get();
	}

	public function update($id, $data)
	{
		$this->db->where('id_sumber', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id_sumber', $id);
		return $this->db->delete($this->table);
	}

	public function getSumber($id)
	{
		$this->db->select('id_sumber AS id, nama_sumber AS nama, keterangan AS ket');
		$this->db->from($this->table);
		$this->db->where('id_sumber', $id);
		return $this->db->get();
	}

	public function search($search="")
	{
		$this->db->like('nama_sumber', $search);
		return $this->db->get($this->table)->result();
	}
}

/* End of file Stok_keluar_model.php */
/* Location: ./application/models/Stok_keluar_model.php */
