<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->model('voucher_model');
	}

	public function index()
	{
		$this->load->view('voucher');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->voucher_model->read()->num_rows() > 0) {
			foreach ($this->voucher_model->read()->result() as $voucher) {
				$start = new DateTime($voucher->tgl_start);
				$end = new DateTime($voucher->tgl_end);
				$data[] = array(
					'nama' => $voucher->nama_vcr,
					'kode' => $voucher->kode_vcr,
					'rp' => ($voucher->nominal_rp) ? 'Rp. ' . $voucher->nominal_rp. ',-' : '-',
					'persen' => ($voucher->nomimal_persen) ? $voucher->nomimal_persen.'%': '-',
					'start' => $start->format('d-m-Y H:i:s'),
					'end' => $end->format('d-m-Y H:i:s'),
					'action' => '<button class="btn btn-sm btn-success" onclick="edit(' . $voucher->id_vcr . ')"><i class="fas fa-edit"></i></button> <button class="btn btn-sm btn-danger" onclick="remove(' . $voucher->id_vcr . ')"><i class="fas fa-trash"></i></button>'
				);
			}
		} else {
			$data = array();
		}
		$voucher = array(
			'data' => $data
		);
		echo json_encode($voucher);
	}

	public function add()
	{
		$data = array(
			'nama_vcr' => $this->input->post('nama'),
			'kode_vcr' => $this->input->post('kode'),
			'nomimal_persen' => $this->input->post('persen'),
			'nominal_rp' => $this->input->post('rupiah'),
			'tgl_start' =>  date('Y-m-d H:i:s', strtotime($this->input->post('start'))),
			'tgl_end' => date('Y-m-d H:i:s', strtotime($this->input->post('end')))
		);

		if ($this->voucher_model->create($data)) {
			echo json_encode($data);
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$data = array(
			'nama_vcr' => $this->input->post('nama'),
			'kode_vcr' => $this->input->post('kode'),
			'nomimal_persen' => $this->input->post('persen'),
			'nominal_rp' => $this->input->post('rupiah'),
			'tgl_start' =>  date('Y-m-d H:i:s', strtotime($this->input->post('start'))),
			'tgl_end' => date('Y-m-d H:i:s', strtotime($this->input->post('end')))
		);

		if ($this->voucher_model->update($id, $data)) {
			echo json_encode('sukses');
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->voucher_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function get_voucher()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		$voucher = $this->voucher_model->getVoucher($id);
		if ($voucher->row()) {
			echo json_encode($voucher->row());
		}
	}

	public function getVoucherCode()
	{
		header('Content-type: application/json');
		$kode = $this->input->post('kode');
		$data = $this->voucher_model->getVoucherByCode($kode);
		if($data->row()){
			echo json_encode($data->row());
		}
	}
}

/* End of file Voucher.php */
/* Location: ./application/controllers/Voucher.php */
