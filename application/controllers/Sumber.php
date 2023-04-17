<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sumber extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->model('sumber_model');
	}

	public function index()
	{
		$this->load->view('sumber');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->sumber_model->read()->num_rows() > 0) {
			foreach ($this->sumber_model->read()->result() as $sumber) {
				$data[] = array(
					'nama' => $sumber->nama_sumber,
					'keterangan' => $sumber->keterangan,
					'action' => '<button class="btn btn-sm btn-success" onclick="edit(' . $sumber->id_sumber . ')"><i class="fas fa-edit"></i></button> <button class="btn btn-sm btn-danger" onclick="remove(' . $sumber->id_sumber . ')"><i class="fas fa-trash"></i></button>'
				);
			}
		} else {
			$data = array();
		}
		$sumber = array(
			'data' => $data
		);
		echo json_encode($sumber);
	}

	public function add()
	{
		$data = array(
			'nama_sumber' => $this->input->post('nama'),
			'keterangan' => $this->input->post('keterangan'),
		);

		if ($this->sumber_model->create($data)) {
			echo json_encode($data);
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$data = array(
			'nama_sumber' => $this->input->post('nama'),
			'keterangan' => $this->input->post('keterangan'),
		);

		if ($this->sumber_model->update($id, $data)) {
			echo json_encode('sukses');
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->sumber_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function get_sumber()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		$sumber = $this->sumber_model->getSumber($id);
		if ($sumber->row()) {
			echo json_encode($sumber->row());
		}
	}

	public function search()
	{
		header('Content-type: application/json');
		$sumber = $this->input->post('sumber');
		$search = $this->sumber_model->search($sumber);
		foreach ($search as $sumber) {
			$data[] = array(
				'id' => $sumber->id_sumber,
				'text' => $sumber->nama_sumber
			);
		}
		echo json_encode($data);
	}
}

/* End of file Voucher.php */
/* Location: ./application/controllers/Voucher.php */
