let laporan_penjualan = $("#laporan_penjualan").DataTable( {
    responsive:true,
    ajax:readUrl,
	"ordering":false,
    columnDefs:[{
        searcable: false,
        orderable: false,
        targets: 0
    }],
        columns:[ {
            data: null
        }
        , {
            data: "tanggal"
        }
        , {
            data: "nama_produk"
        }
        , {
            data: "total_bayar"
        }
        , {
            data: "jumlah_uang"
        }
        , {
            data: "diskon"
        }
        , {
            data: "pelanggan"
        }
        , {
            data: "action"
        }
        ]
}

);

function print_html_ele(element) {

	var contents = $(element).html();
	var frame1 = $('<iframe />');

	var css_link1 = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css';
	var css_link2 = getUrl + 'assets/css/cetak.css';

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
    laporan_penjualan.ajax.reload()
}

function remove(id) {
    Swal.fire( {
        title: "Hapus",
        text: "Hapus data ini?",
        type: "warning",
        showCancelButton: true
    }).then(function (result) {
		if(result.value){
			$.ajax( {
				url:deleteUrl,
				type:"post",
				dataType:"json",
				data: {
					id: id
				},
				success:()=> {
					Swal.fire("Sukses", "Sukses Menghapus Data", "success");
					reloadTable()
				},
				error:err=> {
					console.log(err)
				}
			})
		}else{
			Swal.fire("Batal", "Batal Menghapus Data", "error");
		}
    })
}

laporan_penjualan.on("order.dt search.dt", ()=> {
    laporan_penjualan.column(0, {
        search: "applied", order: "applied"
    }).nodes().each((el, err)=> {
        el.innerHTML=err+1
    })
});

$('#range').daterangepicker();

$("#product").select2({
	placeholder: "Produk",
	allowClear: true,
	ajax: {
		url: getBarcodeUrl,
		type: "post",
		dataType: "json",
		data: params => ({
			barcode: params.term
		}),
		processResults: res => ({
			results: res
		})
	}
});

/* $(document).on('click', '#exportBtn', function(){
	[startDate, endDate] = $('#range').val().split(' - ');
	$.ajax({
		type: "post",
		url: exportUrl,
		data: {
			start: startDate,
			end: endDate
		},
		dataType: "json",
		success: function (response) {
			console.log(response);
		}
	});
}) */

$("#search").select2({
	placeholder: "Order By",
	allowClear: true,
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

$(".modal").on("hidden.bs.modal", ()=> {
    $("#form")[0].reset();
    $("#form").validate().resetForm()
});

$(document).on('click', '.btn-cetak-modal', function(){
	let id = $(this).data('id');
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
			id : id
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
			// print_html_ele($('.cetak-div'));
			// printJS('cetak-div', 'html');
			printJS({
				printable : 'cetak-div',
				type : 'html',
				targetStyles : ['*'],
				font_size : '7pt'
			});
		}
	});
});
