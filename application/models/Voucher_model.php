<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher_model extends CI_Model
{

	private $table = 'voucher';

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function read()
	{
		$this->db->select('id_vcr, nama_vcr, kode_vcr, nomimal_persen, nominal_rp, tgl_start, tgl_end');
		$this->db->from($this->table);
		return $this->db->get();
	}

	public function update($id, $data)
	{
		$this->db->where('id_vcr', $id);
		return $this->db->update($this->table, $data);
	}

	public function delete($id)
	{
		$this->db->where('id_vcr', $id);
		return $this->db->delete($this->table);
	}

	public function getVoucher($id)
	{
		$this->db->select('id_vcr AS id, nama_vcr AS nama, kode_vcr AS kode, nomimal_persen AS persen, nominal_rp AS rp, tgl_start AS start, tgl_end AS end');
		$this->db->from($this->table);
		$this->db->where('id_vcr', $id);
		return $this->db->get();
	}

	public function getVoucherByCode($code)
	{
		$this->db->select('id_vcr AS id, nama_vcr AS nama, kode_vcr AS kode, nomimal_persen AS persen, nominal_rp AS rp, tgl_start AS start, tgl_end AS end');
		$this->db->from($this->table);
		$this->db->where('kode_vcr', $code);
		return $this->db->get();
	}
}

/* End of file Stok_keluar_model.php */
/* Location: ./application/models/Stok_keluar_model.php */
