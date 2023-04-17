<div hidden>
	<div class="cetak-div" id="cetak-div">
		<div style="max-width: 100%; max-height: 100%; width: 58mm; height: 200mm; margin: auto; font-size: 10px;">
			<br>
			<center>
				<img src="<?= base_url('uploads/logo/') . $this->session->userdata('toko')->logo ?>" alt="" style="padding-bottom: 4px;">
				<div style="font-weight: bold;">
					<?php echo $this->session->userdata('toko')->nama; ?><br>
				</div>
				<?php echo $this->session->userdata('toko')->alamat; ?><br>
				NPWP : <?php echo $this->session->userdata('toko')->npwp; ?><br>
				No.Telp : <?php echo $this->session->userdata('toko')->tlpn; ?><br><br>
				<table width="100%" style="font-size: 8px;">
					<tr>
						<td id="nota-cetak"></td>
						<td align="right" id="tanggal-cetak"></td>
					</tr>
				</table>
				<hr style="border : 1px solid gray;margin: 0.7em;" noshade="noshade">
				<table width="100%" style="font-size: 8px;">
					<tr>
						<td width="50%"></td>
						<td width="3%"></td>
						<td width="10%" align="right"></td>
						<td align="right" width="17%" id="kasir-cetak"></td>
					</tr>
				</table>
				<table width="100%" id="table-cetak" style="font-size: 8px;">

				</table>
				<hr style="border : 1px solid gray;margin: 0.7em;" noshade="noshade">
				<table width="100%" style="font-size: 8px;">
					<tr>
						<td width="76%">
							Sub Total
						</td>
						<td width="23%" align="right" id="subtotal-cetak">
						</td>
					</tr>
					<tr>
						<td width="76%">
							Potongan
						</td>
						<td width="23%" align="right" id="diskon-cetak">
						</td>
					</tr>
				</table>
				<hr style="border : 1px solid gray;margin: 0.7em;" noshade="noshade">
				<table width="100%" style="font-size: 8px;font-weight:bold;">
					<tr>
						<td width="76%">
							Total
						</td>
						<td width="23%" align="right" id="total-cetak">
						</td>
					</tr>
				</table>
				<hr style="border : 1px solid gray;margin: 0.7em;" noshade="noshade">
				<table width="100%" style="font-size: 8px;font-weight: bold;">
					<tr>
						<td width="76%">
							Bayar
						</td>
						<td width="23%" align="right" id="bayar-cetak">
						</td>
					</tr>
					<tr>
						<td width="76%">
							Kembalian
						</td>
						<td width="23%" align="right" id="kembalian-cetak">
						</td>
					</tr>
				</table>
				<hr style="border : 1px solid gray;margin: 0.7em;" noshade="noshade">
				Instagram : <?php echo $this->session->userdata('toko')->instagram; ?><br>
				Terima Kasih <br>
				<div style="font-weight: bold;">
					<?php echo $this->session->userdata('toko')->nama; ?><br>
				</div>
				No.Telp : <?php echo $this->session->userdata('toko')->tlpn; ?>
				<br><br>
				<div style="font-weight: bold;">
					<?php echo $this->session->userdata('toko')->noted;	?>
				</div>
			</center>
		</div>
	</div>
</div>