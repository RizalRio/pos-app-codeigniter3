<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hak_akses_model extends CI_Model
{

	private $table = 'hak_akses';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		return $this->db->get($this->table);
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

	public function getHakAkses($id)
	{
		$this->db->select('id, name, seo');
		$this->db->where('id', $id);
		return $this->db->get($this->table);
	}

	public function search($search = "")
	{
		$sess = $this->session->userdata('role');
		if($sess !== 'superadmin'){
			$this->db->where_not_in('seo', 'superadmin');
			$this->db->where_not_in('seo', 'superuser');
		}
		$this->db->like('name', $search);
		return $this->db->get($this->table)->result();
	}
}

/* End of file Pengguna_model.php */
/* Location: ./application/models/Pengguna_model.php */
