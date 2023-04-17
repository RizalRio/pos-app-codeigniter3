<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	public function login()
	{
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		return $this->db->get('pengguna')->row();
	}

	public function getUser($username)
	{
		$this->db->where('username', $username);
		return $this->db->get('pengguna');
	}

	public function getAkses($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('hak_akses')->row();
	}

	public function getToko()
	{
		$this->db->select('nama, alamat, tlpn, npwp, instagram, noted, logo');
		return $this->db->get('toko')->row();
	}

}

/* End of file Auth_model.php */
/* Location: ./application/models/Auth_model.php */
