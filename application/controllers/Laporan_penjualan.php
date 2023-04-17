<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border as StyleBorder;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Laporan_penjualan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('transaksi_model');
		$this->load->model('produk_model');
	}

	public function index()
	{
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->view('laporan_penjualan');
	}

	public function exportToExcel()
	{
		$explode = explode(" - " ,$this->input->post("range"));
		$start = date("Y-m-d", strtotime($explode[0]));
		$end = date("Y-m-d", strtotime($explode[1]));
		$search = null;
		$product = null;
		if($this->input->post('search')){
			$search = $this->input->post('search');
		}
		if($this->input->post('product')){
			$product = $this->input->post('product');
		}
		
		$data = $this->transaksi_model->getTransactionByDate($start, $end, $search, $product);

		$spreadsheet = new Spreadsheet;

		/* Excel Style */
		$header = [
			'font' => [
				'bold' => true
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER
			]
		];

		$footer = [
			'font' => [
				'bold' => true
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER
			]
		];

		$content = [
			'alignment' => [
				'vertical' => Alignment::VERTICAL_CENTER
			]
		];

		$all = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => StyleBorder::BORDER_THIN,
					'color' => ['argb' => '000000'],
				],
			],
		];

		// TODO: LAPORAN TRANSAKSI 
		/* Header  Excel */
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'LAPORAN PENJUALAN PER-TRANSAKSI');
		$spreadsheet->getActiveSheet()->mergeCells("A1:K1");
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A2', 'Periode Tanggal : ' . date('d-m-Y', strtotime($start)) . ' sampai ' . date('d-m-Y', strtotime($end)));
		$spreadsheet->getActiveSheet()->mergeCells("A2:K2");
		$spreadsheet->getActiveSheet()->getStyle('A1:K2')->applyFromArray($header);
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A3', 'No')
		->setCellValue('B3', 'Tanggal')
		->setCellValue('C3', 'Nota')
		->setCellValue('D3', 'Nama Pelanggan')
		->setCellValue('E3', 'Produk')
		->setCellValue('F3', 'Order From')
		->setCellValue('G3', 'Metode Bayar')
		->setCellValue('H3', 'Total')
		->setCellValue('I3', 'Diskon')
		->setCellValue('J3', 'Bayar')
		->setCellValue('K3', 'Kembalian');
		$spreadsheet->getActiveSheet()->getStyle('A3:K3')->applyFromArray($header);
		
		/* variabel */
		$kolom = 4;
		$nomor = 1;
		$total = 0;
		$diskon = 0;
		$bayar = 0;
		$kembalian = 0;
		
		/* Add Data */
		foreach ($data as $row) {
			/* $produk = explode(",", $row->barcode);
			$qty = explode(",", $row->qty);
			$barcode = '';
			for($i = 0; $i < count($produk); $i++){
				$data = $this->produk_model->getProduk($produk[$i]);
				$data = $data->row();
				$barcode .= $data->nama_produk." [". $qty[$i] . "] - [@" . $data->harga . "] \n";
			} */
			$barcode = '';
			$metode_bayar = '-';
			$dataProduct = $this->transaksi_model->getProdukV3($row->id, $product);
			foreach($dataProduct AS $result){
				$barcode .= $result['nama_produk']. " [" . $result['qty_beli'] . "] - [@" . $result['harga_real_produk'] . "] \n";
			}

			$rumus_kembalian = (int)$row->jumlah_uang  - (int)$row->total_bayar;
			
			if($row->metode_bayar == 'qris'){
				$metode_bayar = 'Qris';
			}else if($row->metode_bayar == 'tunai'){
				$metode_bayar = 'Tunai';
			}else if($row->metode_bayar == 'debit'){
				$metode_bayar = 'Debit';
			}

			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom, $nomor)
				->setCellValue('B' . $kolom, $row->tanggal)
				->setCellValue('C' . $kolom, $row->nota)
				->setCellValue('D' . $kolom, $row->pelanggan)
				->setCellValue('E' . $kolom, $barcode)
				->setCellValue('F' . $kolom, $row->sumber)
				->setCellValue('G' . $kolom, $metode_bayar)
				->setCellValue('H' . $kolom, convertRupiah($row->total_bayar))
				->setCellValue('I' . $kolom, $row->diskon)
				->setCellValue('J' . $kolom, convertRupiah($row->jumlah_uang))
				->setCellValue('K' . $kolom, convertRupiah($rumus_kembalian));
			$spreadsheet->getActiveSheet()->getStyle('E'.$kolom)->getAlignment()->setWrapText(true);

			$total = $total + (int)$row->total_bayar;
			$diskon = $diskon + (int)$row->diskon;
			$bayar = $bayar + (int)$row->jumlah_uang;
			$kembalian = $kembalian + (int)$rumus_kembalian;

			$kolom++;
			$nomor++;
		}
		$spreadsheet
			->getActiveSheet()
			->getStyle('H2:H'.$kolom)
			->getFill()
			->setFillType(Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('FFFF00');

		$spreadsheet->getActiveSheet()->getStyle('A3:K'.$kolom)->applyFromArray($content);

		/* Footer */
		$spreadsheet->getActiveSheet()->mergeCells('A'.$kolom. ':G'.$kolom);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$kolom, 'Total');
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$kolom, convertRupiah($total));
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$kolom, $diskon);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$kolom, convertRupiah($bayar));
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$kolom, convertRupiah($kembalian));
		$spreadsheet->getActiveSheet()->getStyle('A'.$kolom.':K'.$kolom)->applyFromArray($footer);

		/* All Style */
		$spreadsheet->getActiveSheet()->getStyle('A3:K'.$kolom)->applyFromArray($all);


		/* Styling */
		foreach (range('A', 'K') as $columnID) {
			$spreadsheet->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		//TODO: KESIMPULAN PENJUALAN
		$headerKolom2 = $kolom + 2;

		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$headerKolom2, 'LAPORAN PENJUALAN PER-ITEM PRODUK');
		$spreadsheet->getActiveSheet()->mergeCells('A'.$headerKolom2.':E'.$headerKolom2);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$headerKolom2 + 1, 'Periode Tanggal : ' . date('d-m-Y', strtotime($start)) . ' sampai ' . date('d-m-Y', strtotime($end)));
		$spreadsheet->getActiveSheet()->mergeCells('A'.$headerKolom2 + 1 .':E'. $headerKolom2 + 1);
		$spreadsheet->getActiveSheet()->getStyle('A'. $headerKolom2 .':E'. $headerKolom2 + 1)->applyFromArray($header);
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A'. $headerKolom2 + 2, 'No')
		->setCellValue('B'. $headerKolom2 + 2, 'Produk')
		->setCellValue('C'. $headerKolom2 + 2, 'QTY Terjual')
		->setCellValue('D'. $headerKolom2 + 2, 'Harga Satuan')
		->setCellValue('E'. $headerKolom2 + 2 , 'Total');
		$spreadsheet->getActiveSheet()->getStyle('A'. $headerKolom2 + 2 .':J' . $headerKolom2 + 2)->applyFromArray($header);

		$kolom2 = $headerKolom2 + 3;
		$nomor2 = 1;
		$total2 = 0;
		$dataKesimpulan = $this->transaksi_model->getTransactionConclusion($start, $end, $product);

		foreach($dataKesimpulan AS $kesimpulan){
			$total2 += $kesimpulan->harga * $kesimpulan->jumlah;
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom2, $nomor2)
				->setCellValue('B' . $kolom2, $kesimpulan->nama_produk)
				->setCellValue('C' . $kolom2, $kesimpulan->jumlah)
				->setCellValue('D' . $kolom2, convertRupiah($kesimpulan->harga))
				->setCellValue('E' . $kolom2, convertRupiah($kesimpulan->harga * $kesimpulan->jumlah));
			$kolom2++;
			$nomor2++;
		}

		$spreadsheet->getActiveSheet()->getStyle('A'. $headerKolom2 + 2 .':E' . $kolom2)->applyFromArray($all);

		$spreadsheet
			->getActiveSheet()
			->getStyle('E' . $headerKolom2 + 3 . ':E' . $kolom2)
			->getFill()
			->setFillType(Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('FFFF00');

		$spreadsheet->getActiveSheet()->getStyle('A'. $headerKolom2 + 3 .':E' . $kolom2)->applyFromArray($content);

		/* Footer */
		$spreadsheet->getActiveSheet()->mergeCells('A' . $kolom2 . ':D' . $kolom2);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $kolom2, 'Total');
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('E' . $kolom2, convertRupiah($total2));
		$spreadsheet->getActiveSheet()->getStyle('A' . $kolom2 . ':D' . $kolom2)->applyFromArray($footer);
		

		/* Output */
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Penjualan '. date('d-m-Y') .'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}

/* End of file Laporan_penjualan.php */
/* Location: ./application/controllers/Laporan_penjualan.php */
