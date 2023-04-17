<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hak_akses extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->model('hak_akses_model');
	}

	public function index()
	{
		$this->load->view('hak_akses');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->hak_akses_model->read()->num_rows() > 0) {
			foreach ($this->hak_akses_model->read()->result() as $hak_akses) {
				$data[] = array(
					'nama' => $hak_akses->name,
					'action' => '<button class="btn btn-sm btn-success" onclick="edit(' . $hak_akses->id . ')">Edit</button> <button class="btn btn-sm btn-danger" onclick="remove(' . $hak_akses->id . ')">Delete</button>'
				);
			}
		} else {
			$data = array();
		}
		$hak_akses = array(
			'data' => $data
		);
		echo json_encode($hak_akses);
	}

	public function add()
	{
		$nama = $this->input->post('nama');
		$data = array(
			'name' => $nama,
			'seo' => str_replace(' ', '',strtolower($nama))
		);
		if ($this->hak_akses_model->create($data)) {
			echo json_encode('sukses');
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->hak_akses_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$data = array(
			'name' => $nama,
			'seo' => str_replace(' ','', strtolower($nama))
		);
		if ($this->hak_akses_model->update($id, $data)) {
			echo json_encode('sukses');
		}
	}

	public function getHakAkses()
	{
		$id = $this->input->post('id');
		$hak_akses = $this->hak_akses_model->getHakAkses($id);
		if ($hak_akses->row()) {
			echo json_encode($hak_akses->row());
		}
	}

	public function search()
	{
		header('Content-type: application/json');
		$hak_akses = $this->input->post('hak_akses');
		$search = $this->hak_akses_model->search($hak_akses);
		foreach ($search as $hak_akses) {
			$data[] = array(
				'id' => $hak_akses->id,
				'text' => $hak_akses->name
			);
		}
		echo json_encode($data);
	}
}

/* End of file Pengguna.php */
/* Location: ./application/controllers/Pengguna.php */
