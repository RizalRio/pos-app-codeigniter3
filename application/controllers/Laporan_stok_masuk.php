<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Style;

class Laporan_stok_masuk extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('stok_masuk_model');
	}

	public function index()
	{
		if ($this->session->userdata('status') !== 'login' ) {
			redirect('/');
		}
		$this->load->view('laporan_stok_masuk');
	}

	public function exportToExcel()
	{
		$explode = explode(" - ", $this->input->post("range"));
		$start = date("Y-m-d", strtotime($explode[0]));
		$end = date("Y-m-d", strtotime($explode[1]));

		$data = $this->stok_masuk_model->readByDate($start, $end);

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

		/* Header  Excel */
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Periode Tanggal : ' . date('d-m-Y', strtotime($start)) . ' sampai ' . date('d-m-Y', strtotime($end)));
		$spreadsheet->getActiveSheet()->mergeCells("A1:F1");
		$spreadsheet->setActiveSheetIndex(0)
		->setCellValue('A2', 'No')
		->setCellValue('B2', 'Tanggal')
		->setCellValue('C2', 'Barcode')
		->setCellValue('D2', 'Nama Produk')
		->setCellValue('E2', 'Keterangan')
		->setCellValue('F2', 'Jumlah');
		$spreadsheet->getActiveSheet()->getStyle('A2:F2')->applyFromArray($header);

		/* variabel */
		$kolom = 3;
		$nomor = 1;
		$total = 0;

		/* Add Data */
		foreach ($data as $row) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom, $nomor)
				->setCellValue('B' . $kolom, $row->tanggal)
				->setCellValue('C' . $kolom, $row->barcode)
				->setCellValue('D' . $kolom, $row->nama_produk)
				->setCellValue('E' . $kolom, $row->keterangan)
				->setCellValue('F' . $kolom, $row->jumlah);

			$total = $total + (int)$row->jumlah;

			$kolom++;
			$nomor++;
		}
		$spreadsheet->getActiveSheet()->getStyle('A3:H' . $kolom)->applyFromArray($content);
		/* Footer */
		$spreadsheet->getActiveSheet()->mergeCells('A' . $kolom . ':E' . $kolom);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $kolom, 'Total');
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $kolom, $total);
		$spreadsheet->getActiveSheet()->getStyle('A' . $kolom . ':F' . $kolom)->applyFromArray($footer);


		/* Styling */
		foreach (range('A', 'F') as $columnID) {
			$spreadsheet->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		/* Output */
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Stok Masuk ' . date('d-m-Y') . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}

/* End of file Laporan_stok_masuk.php */
/* Location: ./application/controllers/Laporan_stok_masuk.php */
