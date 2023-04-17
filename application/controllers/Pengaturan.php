<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
	}
	
	public function index()
	{
		$toko = $this->db->get('toko')->row();
		$data['toko'] = $toko;
		$this->load->view('pengaturan', $data);
	}

	public function set_toko()
	{
		$data = array(
			'nama' => $this->input->post('nama'),
			'alamat' => $this->input->post('alamat'),
			'tlpn' => $this->input->post('tlpn'),
			'npwp' => $this->input->post('npwp'),
			'instagram' => $this->input->post('instagram'),
			'noted' =>$this->input->post('note')
		);

		if ($cropped = $this->input->post('croppedImg')) {
			list($type, $cropped) = explode(';', $cropped);
			list(, $cropped)      = explode(',', $cropped);

			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
			$cropped = base64_decode($cropped);
			$imageName = substr(str_shuffle($permitted_chars), 0, 10).".png";
			$data['logo'] = $imageName;
			file_put_contents('./uploads/logo/' . $imageName, $cropped);
		}

		$this->db->where('id', 1);
		if ($this->db->update('toko', $data)) {
			$this->db->select('nama, alamat, tlpn, npwp, instagram, noted, logo');
			$toko = $this->db->get('toko')->row();
			$this->session->set_userdata('toko', $toko);
			echo json_encode('sukses');
		}
	}
}
