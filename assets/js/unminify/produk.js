let url;
let produk = $("#produk").DataTable({
    responsive: true,
    ajax: readUrl,
    columnDefs: [{
        searcable: false,
        orderable: false,
        targets: 0
    }],
    order: [
        [2, "asc"]
    ],
    columns: [
        { data: null },
		{ data: "gambar"}, 
        { data: "barcode" },
        { data: "nama" },
        { data: "stok" },
        { data: "satuan" },
        { data: "kategori" },
        { data: "harga" },
        { data: "action" }
    ]
});

gambar.onchange = evt => {
	const [file] = gambar.files
	if (file) {
		gambarReview.src = URL.createObjectURL(file)
	}
}

$uploadCrop = $('#cropImage').croppie({
	enableExif: true,
	viewport: {
		width: 100,
		height: 100
	},
	boundary: {
		width: 200,
		height: 200
	}
});

$('#gambar').on('change', function () {
	var reader = new FileReader();
	reader.onload = function (e) {
		$uploadCrop.croppie('bind', {
			url: e.target.result
		}).then(function () {
			console.log('jQuery bind complete');
		});

	}
	reader.readAsDataURL(this.files[0]);
});

function reloadTable() {
    produk.ajax.reload()
}

function addData() {
	$uploadCrop.croppie('result', {
		type: 'canvas',
		size: 'viewport'
	}).then(function (resp) {
		var formData = new FormData($('#form')[0]);
		formData.append('croppedImg', resp);
		$.ajax({
			url: addUrl,
			type: "post",
			dataType: "json",
			data: formData,
			processData: false,
			contentType: false,
			success: res => {
				$(".modal").modal("hide");
	
				Swal.fire("Sukses", "Sukses Menambahkan Data", "success");
				reloadTable();
			},
			error: res => {
				console.log(res);
			}
		})
	});
}

function remove(id) {
    Swal.fire({
        title: "Hapus",
        text: "Hapus data ini?",
        type: "warning",
        showCancelButton: true
    }).then(function(result) {
		if(result.value){
			$.ajax({
				url: deleteUrl,
				type: "post",
				dataType: "json",
				data: {
					id: id
				},
				success: () => {
					Swal.fire("Sukses", "Sukses Menghapus Data", "success");
					reloadTable();
				},
				error: () => {
					console.log(a);
				}
			})
		}else{
			Swal.fire("Batal", "Batal Menghapus Data", "error");
		}
    })
}

function editData() {
    $uploadCrop.croppie('result', {
    	type: 'canvas',
    	size: 'viewport'
    }).then(function (resp) {
    	var formData = new FormData($('#form')[0]);
    	formData.append('croppedImg', resp);
		$.ajax({
			url: editUrl,
			type: "post",
			dataType: "json",
			data: formData,
			processData: false,
			contentType: false,
			success: () => {
				$(".modal").modal("hide");
				Swal.fire("Sukses", "Sukses Mengedit Data", "success");
				reloadTable();
			},
			error: err => {
				console.log(err)
			}
		})
    });
}

function add(elem) {
    url = "add";

	var title;
	if($(elem).data('paket') == '1'){
		$('#input-paket-pr').attr('hidden', false);
		$('#input-bahan').attr('hidden', true);
		title = 'Add Data Paket';
	}else{
		$('#input-paket-pr').attr('hidden', true);
		$('#input-bahan').attr('hidden', false);
		title = 'Add Data Produk';
	}

	$('.input-new-row').empty();
    $(".modal-title").html(title);
    $('.modal button[type="submit"]').html("Add");
	$.ajax({
		url: getBarcodeUrl,
		type: "post",
		dataType: "json",
		success : res => {
			$('[name="barcode"]').val(res);
		}
	});
}

function edit(id) {
    $.ajax({
        url: getProdukUrl,
        type: "post",
        dataType: "json",
        data: {
            id: id
        },
        success: res => {
			url = "edit";
			$(".modal").modal("show");
			$(".modal-title").html("Edit Data");
			$('.modal button[type="submit"]').html("Edit");
			$('.input-new-row').empty();
			$('.input-new-row-pr').empty();
			if(res.tag_paket == 1){
				$('#input-bahan').attr('hidden', true);
				$('#input-paket-pr').attr('hidden', false);

				$.each(res.paket_produk, function(index, val){
					$('.paket-pr').select2("destroy");
					var html = 
					`<div class="row input-produk-pr mb-3">
						<div class="col-sm-6">
							<select name="paket_pr[]" class="paket-pr form-control select2"></select>
						</div>
						<div class="col-sm-4">
							<input type="text" name="jumlah_paket_pr[]" id="jumlah_paket_pr" class="form-control" placeholder="Jumlah" value="${ val.qty }">
						</div>
						<div class="col-sm-2">
							<button class="btn btn-danger btn-block btn-block deleteRowPr" type="button"><i class="fas fa-trash"></i></button>
						</div>
					</div>
					`;
					$('.input-new-row-pr').append(html);
					reloadSelectProduk();
					var option =`<option value="${val.id_pd_paket}">${ val.nama_produk }</option>`; 
					$('.paket-pr').last().append(option).trigger('change');
				});
			}else{
				$('#input-bahan').attr('hidden', false);
				$('#input-paket-pr').attr('hidden', true);

				$.each(res.bahan, function (index, value) {
					$(".bahan").select2("destroy");
					var html =
						`<div class="row input-bahan mb-3">
							<div class="col-sm-6">
								<select name="bahan[]" class="bahan form-control select2">
								</select>
							</div>
							<div class="col-sm-4">
								<input type="text" name="jumlah_bahan[]" id="jumlah_bahan" class="form-control" placeholder="Jumlah" value="${ value.jumlah }">
							</div>
							<div class="col-sm-2">
								<button class="btn btn-danger btn-block deleteRow" type="button"><i class="fas fa-trash"></i></button>
							</div>
						</div>
						`;
					$('.input-new-row').append(html);
					reloadSelectBahan();
					var option = `<option value="${value.id_bahan}">${ value.nama_bahan }</option>`;
					$('.bahan').last().append(option).trigger('change');
				});
			}

            $('[name="id"]').val(res.id);
            $('[name="barcode"]').val(res.barcode);
            $('[name="nama_produk"]').val(res.nama_produk);
			$('[name="satuan"]').empty();
			$('[name="kategori"]').empty();
            $('[name="satuan"]').append(`<option value="${res.satuan_id}">${res.satuan}</option>`);
            $('[name="kategori"]').append(`<option value="${res.kategori_id}">${res.kategori}</option>`);
            $('[name="harga"]').val(res.harga);
            $('[name="stok"]').val(res.stok);
			$('[name="tagline"]').val(res.tagline);
			if (res.gambar !== null) {
				$('#gambarReview').attr('src', siteUrl + 'uploads/produk/' + res.gambar);
			}else{
				$('#gambarReview').attr('src', siteUrl + 'uploads/default.jpg');
			}
        },
        error: err => {
            console.log(err)
        }
    });
}

function reloadSelectBahan()
{
	$('.bahan').select2({
		placeholder: "Bahan",
		ajax: {
			url: getBahanUrl,
			type: "post",
			dataType: "json",
			data: paras => ({
				nama: paras.term
			}),
			processResults: data => ({
				results: data
			}),
			cache: true
		}
	});
}

function reloadSelectProduk()
{
	$('.paket-pr').select2({
		placeholder: "Produk Paket",
		ajax: {
			url: getSelectProdukUrl,
			type: 'post',
			dataType: 'json',
			data: paras => ({
				nama: paras.term
			}),
			processResults: data => ({
				results: data
			}),
			cache: true
		}
	});
}

produk.on("order.dt search.dt", () => {
    produk.column(0, {
        search: "applied",
        order: "applied"
    }).nodes().each((el, val) => {
        el.innerHTML = val + 1
    });
});

$("#addNewRow").on('click', function(){
	$(".bahan").select2("destroy");
	var html = 
	`<div class="row input-bahan mb-3">
		<div class="col-sm-6">
			<select name="bahan[]" class="bahan form-control select2"></select>
		</div>
		<div class="col-sm-4">
			<input type="text" name="jumlah_bahan[]" id="jumlah_bahan" class="form-control" placeholder="Jumlah">
		</div>
		<div class="col-sm-2">
			<button class="btn btn-danger btn-block btn-block deleteRow" type="button"><i class="fas fa-trash"></i></button>
		</div>
	</div>
	`;

	$('.input-new-row').append(html);
	reloadSelectBahan();
});

$("#addNewRowPr").on('click', function(){
	$(".paket-pr").select2('destroy');
	var html = 
	`<div class="row input-produk-pr mb-3">
		<div class="col-sm-6">
			<select name="paket_pr[]" class="paket-pr form-control select2"></select>
		</div>
		<div class="col-sm-4">
			<input type="text" name="jumlah_paket_pr[]" id="jumlah_paket_pr" class="form-control" placeholder="Jumlah">
		</div>
		<div class="col-sm-2">
			<button class="btn btn-danger btn-block btn-block deleteRowPr" type="button"><i class="fas fa-trash"></i></button>
		</div>
	</div>
	`;

	$('.input-new-row-pr').append(html);
	reloadSelectProduk();
});

$(document).on('click', '.deleteRow', function(){
	$(this).closest('.input-bahan').remove();
});

$(document).on('click', '.deleteRowPr', function(){
	$(this).closest('.input-produk-pr').remove();
});

$("#form").validate({
    errorElement: "span",
    errorPlacement: (err, el) => {
        err.addClass("invalid-feedback");
        el.closest(".form-group").append(err)
    },
    submitHandler: () => {
        "edit" == url ? editData() : addData()
    }
});
$("#kategori").select2({
    placeholder: "Kategori",
    ajax: {
        url: kategoriSearchUrl,
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

$("#satuan").select2({
    placeholder: "Satuan",
    ajax: {
        url: satuanSearchUrl,
        type: "post",
        dataType: "json",
        data: paras => ({
            satuan: paras.term
        }),
        processResults: data => ({
            results: data
        }),
        cache: true
    }
});

$('.bahan').select2({
	placeholder: "Bahan", 
	ajax:{
		url:getBahanUrl,
		type: "post",
		dataType: "json",
		data: paras => ({
			nama: paras.term
		}),
		processResults: data => ({
			results: data
		}),
		cache: true
	}
});

$(document).on('change', '.bahan', function (e) {
	var id = $(this).val();
	$.ajax({
		type: "post",
		url: getSatuanUrl,
		data: {
			id: id
		},
		dataType: "json",
		success: function (response) {
			$(this).closest('.desc-satuan').empty();
			var html = `<small class="text-muted>${response.satuan}</small>`
			if(response.satuan){
				$(this).closest('.desc-satuan').append(html);
			}
		}
	});
});

$(".modal").on("hidden.bs.modal", () => {
	$("#satuan").val('').trigger('change');
	$("#kategori").val('').trigger('change');
    $("#form")[0].reset();
    $("#form").validate().resetForm();
});

$('.upload-result').on('click', function (ev) {
	$uploadCrop.croppie('result', {
		type: 'canvas',
		size: 'viewport'
	}).then(function (resp) {

		$.ajax({
			url: "/my-form-upload",
			type: "POST",
			data: {
				"image": resp
			},
			success: function (data) {
				html = '<img src="' + resp + '" />';
				$("#upload-demo-i").html(html);
			}
		});
	});
});
