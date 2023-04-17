<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Style;

class Laporan_bahan_masuk extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('stok_masuk_bahan_model', 'stok_bahan');
	}

	public function index()
	{
		if ($this->session->userdata('status') !== 'login') {
			redirect('/');
		}
		$this->load->view('laporan_bahan_masuk');
	}

	public function exportToExcel()
	{
		$explode = explode(" - ", $this->input->post("range"));
		$start = date("Y-m-d", strtotime($explode[0]));
		$end = date("Y-m-d", strtotime($explode[1]));

		$data = $this->stok_bahan->getBahanMasukByDate($start, $end);

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
		$spreadsheet->getActiveSheet()->mergeCells("A1:E1");
		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A2', 'No')
			->setCellValue('B2', 'Tanggal')
			->setCellValue('C2', 'Nama Bahan')
			->setCellValue('D2', 'Keterangan')
				->setCellValue('E2', 'Jumlah');
		$spreadsheet->getActiveSheet()->getStyle('A2:E2')->applyFromArray($header);

		/* variabel */
		$kolom = 3;
		$nomor = 1;
		$total = 0;
		$diskon = 0;
		$bayar = 0;

		/* Add Data */
		foreach ($data as $row) {
			$spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A' . $kolom, $nomor)
				->setCellValue('B' . $kolom, $row->tgl)
				->setCellValue('C' . $kolom, $row->nama_bahan)
				->setCellValue('D' . $kolom, $row->keterangan)
				->setCellValue('E' . $kolom, $row->jumlah);

			$total = $total + (float)$row->jumlah;

			$kolom++;
			$nomor++;
		}
		$spreadsheet->getActiveSheet()->getStyle('A3:H' . $kolom)->applyFromArray($content);
		/* Footer */
		$spreadsheet->getActiveSheet()->mergeCells('A' . $kolom . ':D' . $kolom);
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $kolom, 'Total');
		$spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $kolom, $total);
		$spreadsheet->getActiveSheet()->getStyle('A' . $kolom . ':E' . $kolom)->applyFromArray($footer);


		/* Styling */
		foreach (range('A', 'E') as $columnID) {
			$spreadsheet->getActiveSheet()->getColumnDimension($columnID)
				->setAutoSize(true);
		}

		/* Output */
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan Stok Masuk Bahan ' . date('d-m-Y') . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}

/* End of file Laporan_penjualan.php */
/* Location: ./application/controllers/Laporan_penjualan.php */
