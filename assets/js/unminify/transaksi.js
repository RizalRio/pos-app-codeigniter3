let isCetak = false,
    produk = [],
    transaksi = $("#transaksi").DataTable({
        responsive: true,
        searching: false,
    });

function getProdukByKategori() {
	var html = ``;
	$.ajax({
		url: produkGetByCategory,
        type: "post",
        dataType: "json",
        data: {
			id: $("#barcode").val()
        },
        success: res => {
			$('#produk-container').empty();
			if(res){
				res.forEach(function(item, index){
					html+= 
					`<div class="col-sm-6 col-lg-4 mb-4">
						<div class="container p-3 produk-container-item h-100" style="border-radius: 12px;" data-id="${item.id}" data-stok="${item.stok}">
													<div class="d-flex justify-content-center">
														<div class="text-center">
															<img src="${getUrl + 'uploads/produk/cropped/' + item.gambar_cropped}" class="mb-2" style="border-radius: 12px;">

															<p class="info-nama" style="font-size: 14px;">
																${item.nama_produk}
																<br>
																<b class="info-harga" style="color: red;" hidden>Harga : ${item.harga}</b>
																<b class="info-harga-rp" style="color: red;">Harga : ${convertToRupiah(item.harga)}</b>
																<small class="form-text info-stok">Sisa ${item.stok}</small>
															</p>
														</div>
													</div>
												</div>
											</div>
					</div>
					`;
				});

				$('#produk-container').append(html);
			}
            checkEmpty()
        },
        error: err => {
            console.log(err)
        }
    })
}

$(document).ready(function(){
	$('input').attr('readonly', true);

	getProdukByKategori();
});

$('input').keypress(function(event) {
    event.preventDefault();
    return false;
});

function print_html_ele(element) {

	var contents = $(element).html();
	var frame1 = $('<iframe />');

	var css_link1 = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css';
	var css_link2 = getUrl + '/assets/css/cetak.css';

	frame1[0].name = "frame1";
	frame1.css({
		"position": "absolute",
		"top": "-1000000px"
	});
	$("body").append(frame1);
	var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
	frameDoc.document.open();
	//Create a new HTML document.
	frameDoc.document.write('<html><head>');
	frameDoc.document.write('</head><body>');
	//Append the external CSS file.
	frameDoc.document.write('<link href="' + css_link1 + '" rel="stylesheet" type="text/css" />');
	frameDoc.document.write('<link href="' + css_link2 + '" rel="stylesheet" type="text/css" />');
	//Append the DIV contents.
	frameDoc.document.write(contents);
	frameDoc.document.write('</body></html>');
	frameDoc.document.close();
	setTimeout(function () {
		window.frames["frame1"].focus();
		window.frames["frame1"].print();
		frame1.remove();
	}, 500);
}

function reloadTable() {
    transaksi.ajax.reload()
}

function convertToRupiah(angka) {
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for (var i = 0; i < angkarev.length; i++)
		if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
	return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('') + ',-';
}

function convertToAngka(rupiah) {
	return parseInt(rupiah.replace(/,.-*|[^0-9]/g, ''), 10);
}

/* function nota(jumlah) {
    let hasil = "",
        char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        total = char.length;
    for (var r = 0; r < jumlah; r++) hasil += char.charAt(Math.floor(Math.random() * total));
    return hasil
} */

function getNama() {
    $.ajax({
        url: produkGetNamaUrl,
        type: "post",
        dataType: "json",
        data: {
            id: $(".produk-container-item.active").data('id')
        },
        success: res => {
            $("#nama_produk").html(res.nama_produk);
            $("#sisa").html(`Sisa ${res.stok}`);
            checkEmpty()
        },
        error: err => {
            console.log(err)
        }
    })
}

function checkStok() {
    $.ajax({
        url: produkGetStokUrl,
        type: "post",
        dataType: "json",
		data: {
			id: $(".produk-container-item.active").data('id')
		},
        success: res => {
			$('#modal-produk').modal('hide');
            let barcode = $(".produk-container-item.active").data('id'),
                nama_produk = res.nama_produk,
                jumlah = parseInt($("#jumlah").val()),
                stok = parseInt(res.stok),
                harga = parseInt(res.harga),
                dataBarcode = res.barcode,
                total = parseInt(convertToAngka($("#total").html())),
				tag_paket = res.tag_paket;
            if (stok < jumlah) Swal.fire("Gagal", "Stok Tidak Cukup", "warning");
            else {
				/* let a = transaksi.rows().indexes().filter(function(a, t){
					 console.log($(transaksi.row(a).data()[0]).data('id'));
				}); */
				let a = transaksi.rows().indexes().filter((a, t) => barcode === $(transaksi.row(a).data()[0]).data('id'));
				if(tag_paket == 1){
					$.ajax({
						type: "post",
						url: cekPaketPr,
						data: {
							id: barcode,
							jumlah: jumlah
						},
						dataType: "json",
						success: function (response) {
							if(response == false){
								Swal.fire('Item', 'Item Produk Paket Gagal Tidak Mencukupi', 'warning');
							}else{
								if (a.length > 0) {
									let row = transaksi.row(a[0]),
										data = row.data();
										console.log($(data[1]).find('.satuan').data('satuan'))
									if (stok < $(data[1]).find('.satuan').data('satuan') + jumlah) {
										Swal.fire('stok', "Stok Tidak Cukup", "warning")
									} else if ((($(data[1]).find('.satuan').data('satuan')) + jumlah) <= 0) {
										Swal.fire('Item', 'Add Item Produk Gagal', 'warning');
									} else {
										var newHarga = parseInt($(data[1]).find('.harga-satuan').data('harga'));
										var newSatuan = parseInt($(data[1]).find('.satuan').data('satuan')) + jumlah;
										var newJumlah = newHarga * newSatuan;
										data[1] = `<div><span class="text-danger harga-satuan" data-harga="${newHarga}">${convertToRupiah(newHarga)}</span><span class="satuan" data-satuan="${newSatuan}">${'   ['+ newSatuan +']'}</span></div>`;
										data[2] = `<span class="text-danger harga-jumlah" data-jumlah="${newJumlah}">${convertToRupiah(newJumlah)}</span>`
										row.data(data).draw();
										indexProduk = produk.findIndex(a => a.id == barcode);
										produk[indexProduk].stok = stok - parseInt(newSatuan);
										produk[indexProduk].terjual = parseInt(newSatuan);
										$("#total").html(convertToRupiah(total + harga * jumlah))
									}
								} else {
									if(jumlah < 0){
										Swal.fire('Item', 'Add Item Produk Gagal', 'warning');
									}else{
										produk.push({
											id: barcode,
											stok: stok - jumlah,
											terjual: jumlah
										});
										transaksi.row.add([
										`<span class="nama_produk" data-id="${barcode}">${nama_produk}</span>`,
											`<div><span class="text-danger harga-satuan" data-harga="${harga}">${convertToRupiah(harga)}</span><span class="satuan" data-satuan=${jumlah}>${'   ['+ jumlah +']'}</span></div>`,
											`<span class="text-danger harga-jumlah" data-jumlah="${harga * jumlah}">${convertToRupiah(harga * jumlah)}</span>`,
											`<button name="${barcode}" class="btn btn-sm btn-danger" onclick="remove('${barcode}')"><i class="fas fa-trash"></i></btn>`]).draw();
										$("#total").html(convertToRupiah(total + harga * jumlah));
										$("#jumlah").val("");
										$("#tambah").attr("disabled", "disabled");
										$("#bayar").removeAttr("disabled")
									}
								}
							}
						}
					});
				}else{
					if (a.length > 0) {
						let row = transaksi.row(a[0]),
							data = row.data();
							console.log($(data[1]).find('.satuan').data('satuan'))
						if (stok < $(data[1]).find('.satuan').data('satuan') + jumlah) {
							Swal.fire('stok', "Stok Tidak Cukup", "warning")
						} else if ((($(data[1]).find('.satuan').data('satuan')) + jumlah) <= 0) {
							Swal.fire('Item', 'Add Item Produk Gagal', 'warning');
						} else {
							var newHarga = parseInt($(data[1]).find('.harga-satuan').data('harga'));
							var newSatuan = parseInt($(data[1]).find('.satuan').data('satuan')) + jumlah;
							var newJumlah = newHarga * newSatuan;
							data[1] = `<div><span class="text-danger harga-satuan" data-harga="${newHarga}">${convertToRupiah(newHarga)}</span><span class="satuan" data-satuan="${newSatuan}">${'   ['+ newSatuan +']'}</span></div>`;
							data[2] = `<span class="text-danger harga-jumlah" data-jumlah="${newJumlah}">${convertToRupiah(newJumlah)}</span>`
							row.data(data).draw();
							indexProduk = produk.findIndex(a => a.id == barcode);
							produk[indexProduk].stok = stok - parseInt(newSatuan);
							produk[indexProduk].terjual = parseInt(newSatuan);
							$("#total").html(convertToRupiah(total + harga * jumlah))
						}
					} else {
						if(jumlah < 0){
							Swal.fire('Item', 'Add Item Produk Gagal', 'warning');
						}else{
							produk.push({
								id: barcode,
								stok: stok - jumlah,
								terjual: jumlah
							});
							transaksi.row.add([
							`<span class="nama_produk" data-id="${barcode}">${nama_produk}</span>`,
								`<div><span class="text-danger harga-satuan" data-harga="${harga}">${convertToRupiah(harga)}</span><span class="satuan" data-satuan=${jumlah}>${'   ['+ jumlah +']'}</span></div>`,
								`<span class="text-danger harga-jumlah" data-jumlah="${harga * jumlah}">${convertToRupiah(harga * jumlah)}</span>`,
								`<button name="${barcode}" class="btn btn-sm btn-danger" onclick="remove('${barcode}')"><i class="fas fa-trash"></i></btn>`]).draw();
							$("#total").html(convertToRupiah(total + harga * jumlah));
							$("#jumlah").val("");
							$("#tambah").attr("disabled", "disabled");
							$("#bayar").removeAttr("disabled")
						}
					}
				}
            }
        }
    })
}

function bayarCetak() {
    isCetak = true
}

function bayar() {
    isCetak = false
}

function checkEmpty() {
    let barcode = $("#barcode").val(),
        jumlah = $("#jumlah").val(),
		stok = $(".produk-container.active").data('stok');
    if (barcode !== "" && jumlah !== "") {
        $("#tambah").removeAttr("disabled")    
    } else {
        $("#tambah").attr("disabled", "disabled")
    }
}

function checkUang() {
    let jumlah_uang = $('[name="jumlah_uang"').val(),
        total_bayar = parseInt(convertToAngka($(".total_bayar").html()));
    if (jumlah_uang !== "" && jumlah_uang >= total_bayar) {
        $("#add").removeAttr("disabled");
        $("#cetak").removeAttr("disabled")
    } else {
        $("#add").attr("disabled", "disabled");
        $("#cetak").attr("disabled", "disabled")
    }
}

//!error penghapusan data
function remove(nama) {
    let data = transaksi.row($("[name=" + nama + "]").closest("tr")).data(),
        stok = parseInt($(data[1]).find('.satuan').data('satuan')),
        harga = parseInt($(data[1]).find('.harga-satuan').data('harga')),
        total = parseInt(convertToAngka($("#total").html()));
        akhir = total - stok * harga
    $("#total").html(convertToRupiah(akhir));
    transaksi.row($("[name=" + nama + "]").closest("tr")).remove().draw();
    $("#tambah").attr("disabled", "disabled");
    if (akhir < 1) {
        $("#bayar").attr("disabled", "disabled")
    }

	produk = produk.filter(x => {
  		return x.id != nama;
	});
}

function add() {
    let data = transaksi.rows().data(),
        qty = [];
    $.each(data, (index, value) => {
        qty.push($(value[1]).find('.satuan').data('satuan'))
    });
    $.ajax({
        url: addUrl,
        type: "post",
        dataType: "json",
        data: {
            produk: JSON.stringify(produk),
            tanggal: $("#tanggal").val(),
            qty: qty,
            total_bayar: convertToAngka($("#total").html()),
            jumlah_uang: $('[name="jumlah_uang"]').val(),
            diskon: convertToAngka($('.diskon').html()),
			diskon_id: $('.diskon').data('id'),
			sumber: $('#sumber').val(),
            pelanggan: $("#pelanggan").val(),
            nota: $("#nota").html(),
			metode_bayar: $('.btn-metode-bayar.active').find('[name="metode_bayar"]').val(),
			trn_nama_bank: $('[name="trn_nama_bank"]').val(),
			trn_no_rek: $('[name="trn_no_rek"]').val()
        },
        success: res => {
			if (isCetak) {
				let id = res;
				$('#nota-cetak').empty();
				$('#tanggal-cetak').empty();
				$('#kasir-cetak').empty();
				$('#subtotal-cetak').empty();
				$('#diskon-cetak').empty();
				$('#diskon-cetak').empty();
				$('#total-cetak').empty();
				$('#bayar-cetak').empty();
				$('#kembalian-cetak').empty();
				$('#table-cetak').empty();
				$.ajax({
					type: "post",
					url: getDataCetak,
					data: {
						id: id
					},
					dataType: "json",
					success: function (response) {
						console.log(response);
						var total = response.total - response.diskon;
						$('#nota-cetak').html(response.nota);
						$('#tanggal-cetak').html(response.tanggal);
						$('#kasir-cetak').html(response.kasir);
						$('#subtotal-cetak').html('Rp. ' + response.total + ',-');
						$('#diskon-cetak').html('Rp. ' + response.diskon + ',-');
						$('#total-cetak').html('Rp. ' + total + ',-');
						$('#bayar-cetak').html('Rp. ' + response.bayar + ',-');
						$('#kembalian-cetak').html('Rp. ' + response.kembalian + ',-');
						$.each(response.produk, function (index, value) {
							var produkHtml =
								`<tr>
								<td>${value.nama_produk} (${value.total})</td>
								<td></td>
								<td align="right">@ ${value.satuan}</td>
								<td align="right">Rp. ${value.harga},-</td>
							</tr>
							`;

							$("#table-cetak").append(produkHtml);
						});
					},
					complete: function() {
						$("#form")[0].reset();
						$("#form").validate().resetForm();
						$('#modal').modal('hide');
						$('#total').html('0');
						$("#barcode").val('').change();
						$.ajax({
							type: "post",
							url: getNotaUrl,
							dataType: "json",
							success: function (response) {
								transaksi.clear().draw();
								$("#bayar").attr("disabled", "disabled");
								$("#nota").html(response);
								produk = [];
							},
							complete: function () {
								Swal.fire("Sukses", "Sukses Membayar", "success").
								then(function (){
									// TODO : NOT SHOWING MODAL PRINT 
									printJS({
										printable : 'cetak-div',
										type : 'html',
										targetStyles : ['*'],
										font_size : '7pt'
									});
								});
							}
						});
					}
				});
            } else {
                Swal.fire("Sukses", "Sukses Membayar", "success").
                    then(() => window.location.reload())
            }
        },
        error: err => {
            console.log(err)
        }
    })
}

$(document).on('click', '.btn-cetak', function () {
	print_html_ele($('.cetak-div'));
});

function kembalian() {
    let total = convertToAngka($("#total").html()),
        jumlah_uang = $('[name="jumlah_uang"').val(),
        diskon = convertToAngka($('.diskon').html());
	$(".bayar").html(convertToRupiah(jumlah_uang));
	$(".total_bayar").html(convertToRupiah(total - diskon));
    $(".kembalian").html(convertToRupiah(jumlah_uang - (total - diskon)));
    checkUang()
}

$(document).on('click', '.inputChar', function () {
	var value = $(this).text();
	if ($('#jumlah').val()) {
		var valueSet = $('#jumlah').val();
		$('#jumlah').val(valueSet + value);
	} else {
		$('#jumlah').val(value);
	}
	checkEmpty();
});

$(document).on('click', '.backspaceJumlah', function (){
	var value = $('#jumlah').val();
    $('#jumlah').val(value.substr(0, value.length - 1));
	checkEmpty()
});

$(document).on('click', '.backspaceVoucher', function (){
	var value = $('[name="voucher"]').val();
    $('[name="voucher"]').val(value.substr(0, value.length - 1));
	checkVoucher();
});

$(document).on('click', '.backspaceBayar', function (){
	var value = $('[name="jumlah_uang"]').val();
    $('[name="jumlah_uang"]').val(value.substr(0, value.length - 1));
	kembalian();
});

$(document).on('click', '.backspaceBank', function (){
	var value = $('[name="nama_bank"]').val();
    $('[name="nama_bank"]').val(value.substr(0, value.length - 1));
});

$(document).on('click', '.backspaceRek', function (){
	var value = $('[name="no_rek"]').val();
    $('[name="no_rek"]').val(value.substr(0, value.length - 1));
});

$(document).on('click', '.inputCharBayar', function () {
	var value = $(this).text();
	if ($('[name="jumlah_uang"]').val()){
		var valueSet = $('[name="jumlah_uang"]').val();
		$('[name="jumlah_uang"]').val(valueSet+value);
	}else{
		$('[name="jumlah_uang"]').val(value);
	}
	kembalian();
});

$(document).on('click', '.inputCharBayarNominal', function () {
	var value = $(this).text();
	$('[name="jumlah_uang"]').val(value);
	kembalian();
});

$(document).on('click', '.inputCharVoucher', function () {
	var value = $(this).text();
	if($('[name="voucher"]').val()){
		var valueSet = $('[name="voucher"]').val();
		$('[name="voucher"]').val(valueSet+value);
	}else{
		$('[name="voucher"]').val(value);
	}
	checkVoucher();
});

$(document).on('click', '.inputCharBank', function () {
	var value = $(this).text();
	if($('[name="nama_bank"]').val()){
		var valueSet = $('[name="nama_bank"]').val();
		$('[name="nama_bank"]').val(valueSet+value);
	}else{
		$('[name="nama_bank"]').val(value);
	}
});

$(document).on('click', '.inputCharRek', function () {
	var value = $(this).text();
	if($('[name="no_rek"]').val()){
		var valueSet = $('[name="no_rek"]').val();
		$('[name="no_rek"]').val(valueSet+value);
	}else{
		$('[name="no_rek"]').val(value);
	}
});

function checkVoucher(){
	let kode = $('[name="voucher"]').val(),
		total = convertToAngka($('#total').html());
	$('.diskon').html(convertToRupiah(0));
	$.ajax({
		type: "post",
		url: getVoucherUrl,
		data: {
			kode: kode
		},
		dataType: "json",
		success: function (res) {
			if (res.id) {
				$('.diskon').attr('data-id', res.id);
			}

			if (res.rp) {
				var newTotal = convertToRupiah(res.rp);
				$('.diskon').html(newTotal);
			} 
			if (res.persen) {
				var perhitungan = total * (res.persen / 100);
				var newTotal = convertToRupiah(perhitungan);
				$('.diskon').html(newTotal);
			}
		},
		complete: function(){
			kembalian()
		}
	});
}

$(document).on('keyup', '[name="voucher"]', function(){
	let kode = $('[name="voucher"]').val(),
		total = convertToAngka($('#total').html());
		$('.diskon').html(convertToRupiah(0));
	$.ajax({
		type: "post",
		url: getVoucherUrl,
		data: {
			kode : kode
		},
		dataType: "json",
		success: function (res) {
			if (res.id) {
				$('.diskon').attr('data-id', res.id);
			}

			if (res.rp) {
				var newTotal = convertToRupiah(res.rp);
				$('.diskon').html(newTotal);
			}
			if (res.persen) {
				var perhitungan = total * (res.persen / 100);
				var newTotal = convertToRupiah(perhitungan);
				$('.diskon').html(newTotal); 
			}
		},
		error: function (res) {

		},
		complete: function (){
			kembalian()
		}
	});
});

/* $("#barcode").select2({
    placeholder: "Barcode",
    ajax: {
        url: getBarcodeUrl,
        type: "post",
        dataType: "json",
        data: params => ({
            barcode: params.term
        }),
        processResults: res => ({
            results: res
        }),
        cache: true
    }
}); */
$("#barcode").select2({
    placeholder: "Kategori",
	allowClear: true,
	ajax: {
		url: getCategoryUrl,
		type: "post",
		dataType: "json",
		data: params => ({
    		kategori: params.term
    	}),
    	processResults: data => ({
    		results: data
    	}),
    	cache: true
    }
});
$("#pelanggan").select2({
    placeholder: 'Pelanggan',
    ajax: {
        url: pelangganSearchUrl,
        type: "post",
        dataType: "json",
        data: params => ({
            pelanggan: params.term
        }),
        processResults: res => ({
            results: res
        }),
        cache: true
    }
});
$("#sumber").select2({
	placeholder: "Sumber",
	ajax: {
		url: sumberSearchUrl,
		type: 'post',
		dataType: 'json',
		data: params => ({
			sumber: params.term
		}),
		processResults: res => ({
			results: res
		}),
		cache: true
	}
})

$("#tanggal").datetimepicker({
    format: "dd-mm-yyyy h:ii:ss"
});

/* $("#modal").on("hidden.bs.modal", () => {
    $("#form")[0].reset();
    $("#form").validate().resetForm();
	window.location.reload();
}); */

$("#modal-produk").on("hidden.bs.modal", function(){
	$('#jumlah').val("");
});

$("#modal").on("show.bs.modal", () => {
    let now = moment().format("D-MM-Y H:mm:ss"),
        total = convertToAngka($("#total").html()),
        jumlah_uang = $('[name="jumlah_uang"').val();
    var option = `<option value="1">Customer Umum</option>`;
    var sumber = `<option value="4">Outlet</option>`;
    $('#sumber').append(sumber).trigger('change');
	$('#pelanggan').append(option).trigger('change');
	$("#tanggal").val(now), $(".bayar").html(convertToRupiah(jumlah_uang)), $(".total_bayar").html(convertToRupiah(total)), $(".kembalian").html(convertToRupiah(Math.max(jumlah_uang - total, 0)));
});
$("#form").validate({
    errorElement: "span",
    errorPlacement: (err, el) => {
        err.addClass("invalid-feedback"), el.closest(".form-group").append(err)
    },
    submitHandler: () => {
        add()
    }
});
$(document).on('click', '.produk-container-item', function(){
	$('.produk-container-item').removeClass('active');
	$(this).addClass('active');

	$('#modal-produk').modal('show');
});

$(document).on('change', 'input:radio[name="metode_bayar"]',function(){
    if ($(this).is(':checked') && $(this).val() == 'debit') {
		$('#modalMetodeBayar').modal('show');
    }
});

function openFullscreen(elem) {
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
	} else if (elem.webkitRequestFullscreen) {
    	/* Safari */
    	elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) {
    	/* IE11 */
    	elem.msRequestFullscreen();
    }
}

function closeFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
        /* Safari */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
        /* IE11 */
        document.msExitFullscreen();
    }
}

$(document).on('click', '#openFullscreen', function(){
	var elem = document.getElementById('page-transaksi');
    openFullscreen(elem);
});

$(document).on('click', '#closeFullscreen', function(){
    closeFullscreen();
});

$(document).on("click", "#save_debit", function(){
	var nama = $('#nama_bank').val();
	var no = $('#no_rek').val();

	$('[name="trn_nama_bank"]').val(nama);
	$('[name="trn_no_rek"]').val(no);

	setTimeout(function(){
		$('#modalMetodeBayar').modal('hide');
	}, 500);
});
/* $("#nota").html(nota(15)); */
