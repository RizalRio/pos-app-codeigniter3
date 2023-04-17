<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class Bahan_baku extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->model('bahan_baku_model');
		$this->load->model('produk_model');
		$this->load->model('stok_masuk_bahan_model');
	}

	public function index()
	{
		$this->load->view('bahan_baku');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->bahan_baku_model->read()->num_rows() > 0) {
			foreach ($this->bahan_baku_model->read()->result() as $bahan_baku) {
				// $role = $this->session->userdata('role');
				$data[] = array(
					'gambar' => ($bahan_baku->gambar !== null) ? '<img style="width: 15vh;height:15vh;" src="' . base_url('uploads/bahan_baku/') . $bahan_baku->gambar . '">' : '<img style="width: 15vh;height:15vh;" src="' . base_url('uploads/default.jpg') . '">',
					'nama' => $bahan_baku->nama,
					'satuan' => $bahan_baku->satuan,
					'harga' => 'Rp. '.$bahan_baku->harga.',-',
					'stok' => $bahan_baku->stok,
					'action' => '<button class="btn btn-sm btn-success" onclick="edit(' . $bahan_baku->id . ')"><i class="fas fa-edit"></i></button> <button class="btn btn-sm btn-danger" onclick="remove(' . $bahan_baku->id . ')"><i class="fas fa-trash"></i></button>'
				);
			}
		} else {
			$data = array();
		}
		$bahan_baku = array(
			'data' => $data
		);
		echo json_encode($bahan_baku);
	}

	public function add()
	{
		$data = array(
			'nama_bahan' => $this->input->post('nama'),
			'satuan' => $this->input->post('satuan'),
			'harga' => $this->input->post('harga'),
			'stok' => $this->input->post('stok')
		);

		$config['upload_path'] = "./uploads/bahan_baku/";
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload("gambar")) {
			$dataImage = array('upload_data' => $this->upload->data());
			$data['gambar'] = $dataImage['upload_data']['file_name'];
		}

		if ($this->bahan_baku_model->create($data)) {
			$id = $this->db->insert_id();
			$bahanIn = [
				'id_bahan' => $id,
				'tgl_masuk' => date('Y-m-d H:i:s'),
				'jumlah_masuk' => $this->input->post('stok'),
				'keterangan' => 'Penambahan bahan baku'
			];
			$this->stok_masuk_bahan_model->create($bahanIn);

			$data['msg'] = $this->upload->display_errors();
			echo json_encode($data);
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->bahan_baku_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$model = $this->bahan_baku_model->getBahan($id);
		$dataBahan = $model->row();
		$stok = $this->input->post('stok');
		$rumus = $stok - $dataBahan->stok;
		if($rumus < 0){
			$bahanOut = [
				'id_bahan' => $id,
				'tgl_keluar' => date('Y-m-d H:i:s'),
				'jumlah_masuk' => preg_replace('/[^a-zA-Z0-9]/s', '', $rumus),
				'keterangan' => 'Pengurangan bahan baku edit'
			];
			$this->produk_model->addBahanKeluar($bahanOut);
		}else{
			$bahanIn = [
				'id_bahan' => $id,
				'tgl_masuk' => date('Y-m-d H:i:s'),
				'jumlah_masuk' => $rumus,
				'keterangan' => 'Penambahan bahan baku edit'
			];
			$this->stok_masuk_bahan_model->create($bahanIn);
		}
		
		$data = array(
			'nama_bahan' => $this->input->post('nama'),
			'satuan' => $this->input->post('satuan'),
			'harga' => $this->input->post('harga'),
			'stok' => $stok
		);

		$config['upload_path'] = "./uploads/bahan_baku/";
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload("gambar")) {
			$dataImage = array('upload_data' => $this->upload->data());
			$data['gambar'] = $dataImage['upload_data']['file_name'];
		}

		if ($this->bahan_baku_model->update($id, $data)) {
			echo json_encode('sukses');
		}
	}

	public function get_bahan()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		$data = $this->bahan_baku_model->getBahan($id);
		if ($data->row()) {
			echo json_encode($data->row());
		}
	}

	public function get_bahan_select()
	{
		header('Content-type: application/json');
		$nama = $this->input->post('nama');
		$search = $this->bahan_baku_model->getBahanSelect($nama);
		foreach ($search as $nama) {
			$data[] = array(
				'id' => $nama->id,
				'text' => $nama->nama. '  ('.$nama->satuan.')'
			);
		}
		echo json_encode($data);
	}
}

/* End of file Produk.php */
/* Location: ./application/controllers/Produk.php */
