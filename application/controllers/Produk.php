<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class Produk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->model('produk_model');
	}

	public function index()
	{
		$this->load->view('produk');
	}

	public function read()
	{
		header('Content-type: application/json');
		if ($this->produk_model->read()->num_rows() > 0) {
			foreach ($this->produk_model->read()->result() as $produk) {
				// $role = $this->session->userdata('role');
				$data[] = array(
					'gambar' => ($produk->gambar !== null) ? '<img style="width: 15vh;height:15vh;" src="'.base_url('uploads/produk/'). $produk->gambar .'">': '<img style="width: 15vh;height:15vh;" src="' . base_url('uploads/default.jpg') . '">',
					'barcode' => $produk->barcode,
					'nama' => $produk->nama_produk,
					'kategori' => $produk->kategori,
					'satuan' => $produk->satuan,
					'harga' => $produk->harga,
					'stok' => $produk->stok,
				    
				    'action' => '<button class="btn btn-sm btn-success" onclick="edit('.$produk->id.')"><i class="fas fa-edit"></i></button> <button class="btn btn-sm btn-danger" onclick="remove('.$produk->id.')"><i class="fas fa-trash"></i></button>'

				);
			}
		} else {
			$data = array();
		}
		$produk = array(
			'data' => $data
		);
		echo json_encode($produk);
	}

	public function add()
	{
		$data = array(
			'barcode' => $this->input->post('barcode'),
			'nama_produk' => $this->input->post('nama_produk'),
			'satuan' => $this->input->post('satuan'),
			'kategori' => $this->input->post('kategori'),
			'harga' => $this->input->post('harga'),
			'stok' => $this->input->post('stok'),
			'tagline' => $this->input->post('tagline'),
			'tag_paket' => ($this->input->post('paket_pr') ? '1' : '0')
		);

		$config['upload_path'] = "./uploads/produk/";
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ($this->upload->do_upload("gambar")) {
			$dataImage = array('upload_data' => $this->upload->data());
			$data['gambar'] = $dataImage['upload_data']['file_name'];
			if ($cropped = $this->input->post('croppedImg')) {
				list($type, $cropped) = explode(';', $cropped);
				list(, $cropped)      = explode(',', $cropped);
	
				$cropped = base64_decode($cropped);
				$imageName = $dataImage['upload_data']['file_name'];
				$data['gambar_cropped'] = $imageName;
				file_put_contents('./uploads/produk/cropped/' . $imageName, $cropped);
			}
		}

		if ($id = $this->produk_model->create($data)) {
			$data['msg'] = $this->upload->display_errors();
			$data['id'] = $id;
			
			if($this->input->post('bahan')){
				$primary = [];
				$secondary = [];
				$bahan = $this->input->post('bahan');
				$jumlah_bahan = $this->input->post('jumlah_bahan');
				for($i=0; $i < count($bahan); $i++){
					$secondary['id_bahan'] = $bahan[$i];
					$secondary['id_produk'] = $id;
					$secondary['jumlah'] = $jumlah_bahan[$i];
	
					array_push($primary, $secondary);
	
	
					$row_bahan = $this->produk_model->getBahanBaku($bahan[$i]);
					$data_row_bahan = $row_bahan->row();
					$rumus = $data_row_bahan->stok - $jumlah_bahan[$i];
					$this->produk_model->removeBahan($bahan[$i], $rumus);
	
					$bahanOut = [
						'id_bahan' => $bahan[$i],
						'tgl_keluar' => date('Y-m-d H:i:s'),
						'jumlah_masuk' => $jumlah_bahan[$i],
						'keterangan' => 'Dipakai'
					];
					$this->produk_model->addBahanKeluar($bahanOut);
				}
				$this->produk_model->create_bahan($primary);
	
				$data['bahan'] = $primary;
			}

			if($this->input->post('paket_pr')){
				$primaryPr = [];
				$secondaryPr = [];
				$paket_pr = $this->input->post('paket_pr');
				$jumlah_pr = $this->input->post('jumlah_paket_pr');
				for($i=0; $i < count($paket_pr); $i++){
					$secondaryPr['id_pd_paket'] = $paket_pr[$i];
					$secondaryPr['qty'] = $jumlah_pr[$i];
					$secondaryPr['id_produk'] = $id;

					array_push($primaryPr, $secondaryPr);
				}

				$this->produk_model->add_paket_pr($primaryPr);
			}
			echo json_encode($data);
		}
	}

	public function delete()
	{
		$id = $this->input->post('id');
		if ($this->produk_model->delete($id)) {
			echo json_encode('sukses');
		}
	}

	public function edit()
	{
		$id = $this->input->post('id');
		$data = array(
			'barcode' => $this->input->post('barcode'),
			'nama_produk' => $this->input->post('nama_produk'),
			'satuan' => $this->input->post('satuan'),
			'kategori' => $this->input->post('kategori'),
			'harga' => $this->input->post('harga'),
			'stok' => $this->input->post('stok'),
			'tagline' => $this->input->post('tagline'),
			'tag_paket' => ($this->input->post('paket_pr') ? '1' : '0')
		);
		
		$config['upload_path'] = "./uploads/produk/";
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload("gambar")) {
			$dataImage = array('upload_data' => $this->upload->data());
			$data['gambar'] = $dataImage['upload_data']['file_name'];

			if ($cropped = $this->input->post('croppedImg')) {
				list($type, $cropped) = explode(';', $cropped);
				list(, $cropped)      = explode(',', $cropped);

				$cropped = base64_decode($cropped);
				$imageName = $dataImage['upload_data']['file_name'];
				$data['gambar_cropped'] = $imageName;
				file_put_contents('./uploads/produk/cropped/' . $imageName, $cropped);
			}
		}

		if ($this->produk_model->update($id, $data)) {
			if ($this->input->post('bahan')) {
				$this->produk_model->deleteProdukBahan($id);
				$primary = [];
				$secondary = [];
				$bahan = $this->input->post('bahan');
				$jumlah_bahan = $this->input->post('jumlah_bahan');
				if ($bahan) {
					for ($i = 0; $i < count($bahan); $i++) {
						$secondary['id_bahan'] = $bahan[$i];
						$secondary['id_produk'] = $id;
						$secondary['jumlah'] = $jumlah_bahan[$i];

						array_push($primary, $secondary);

						$row_bahan = $this->produk_model->getBahanBaku($bahan[$i]);
						$data_row_bahan = $row_bahan->row();
						$rumus = $data_row_bahan->stok - $jumlah_bahan[$i];
						$this->produk_model->removeBahan($bahan[$i], $rumus);

						$bahanOut = [
							'id_bahan' => $bahan[$i],
							'tgl_keluar' => date('Y-m-d H:i:s'),
							'jumlah_masuk' => $jumlah_bahan[$i],
							'keterangan' => 'Dipakai'
						];
						$this->produk_model->addBahanKeluar($bahanOut);
					}
					$this->produk_model->create_bahan($primary);
				}
			}

			if ($this->input->post('paket_pr')) {
				$this->produk_model->deletePaketProduk($id);
				$primaryPr = [];
				$secondaryPr = [];
				$paket_pr = $this->input->post('paket_pr');
				$jumlah_pr = $this->input->post('jumlah_paket_pr');
				for ($i = 0; $i < count($paket_pr); $i++) {
					$secondaryPr['id_pd_paket'] = $paket_pr[$i];
					$secondaryPr['qty'] = $jumlah_pr[$i];
					$secondaryPr['id_produk'] = $id;

					array_push($primaryPr, $secondaryPr);
				}

				$this->produk_model->add_paket_pr($primaryPr);
			}
			echo json_encode('sukses');
		}
	}

	public function get_produk()
	{
		header('Content-type: application/json');
		$data = [];
		$id = $this->input->post('id');
		$kategori = $this->produk_model->getProduk($id);
		$bahan = $this->produk_model->getProdukBahan($id);
		$paketProduk = $this->produk_model->getProdukPaket($id);
		if ($kategori->row()) {
			$data = $kategori->row_array();
		}
		if ($bahan->result()){
			$data['bahan'] = $bahan->result_array();
		}
		if($paketProduk->result()){
			$data['paket_produk'] = $paketProduk->result_array();
		}
		echo json_encode($data);
	}

	public function get_produk_category()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		if($id){
			$id = $id;
		}else{
			$id = null;
		}
		$kategori = $this->produk_model->getProdukByKategori($id);
		echo json_encode($kategori->result());
	}

	public function generate_barcode()
	{
		$menu = 'MENU';
		$randomstr = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 3);
		$id = $this->produk_model->getLastId();
		$id = $id->id + 1;
		$id = sprintf("%04s", $id);
		$barcode = $menu . $randomstr . $id;
		echo json_encode($barcode);
	}

	public function get_barcode()
	{
		header('Content-type: application/json');
		$barcode = $this->input->post('barcode');
		$search = $this->produk_model->getBarcode($barcode);
		foreach ($search as $barcode) {
			$data[] = array(
				'id' => $barcode->id,
				'text' => $barcode->nama_produk
			);
		}
		echo json_encode($data);
	}

	public function get_nama()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		echo json_encode($this->produk_model->getNama($id));
	}

	public function get_bahan_satuan()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		echo json_encode($this->produk_model->get_bahan_satuan($id));
	}

	public function get_stok()
	{
		header('Content-type: application/json');
		$id = $this->input->post('id');
		echo json_encode($this->produk_model->getStok($id));
	}

	public function produk_terlaris()
	{
		header('Content-type: application/json');
		$produk = $this->produk_model->produkTerlaris();
		foreach ($produk as $key) {
			$label[] = $key->nama_produk;
			$data[] = $key->Jumlah;
		}
		$result = array(
			'label' => $label,
			'data' => $data,
		);
		echo json_encode($result);
	}

	public function data_stok()
	{
		header('Content-type: application/json');
		$produk = $this->produk_model->dataStok();
		echo json_encode($produk);
	}
}

/* End of file Produk.php */
/* Location: ./application/controllers/Produk.php */
