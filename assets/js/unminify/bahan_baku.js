let url;
let bahan_baku = $("#bahan_baku").DataTable({
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
	columns: [{
			data: null
		},
		{
			data: "gambar"
		},
		{
			data: "nama"
		},
		{
			data: "stok"
		},
		{
			data: "satuan"
		},
		{
			data: "harga"
		},
		{
			data: "action"
		}
	]
});

gambar.onchange = evt => {
	const [file] = gambar.files
	if (file) {
		gambarReview.src = URL.createObjectURL(file)
	}
}

function reloadTable() {
	bahan_baku.ajax.reload()
}

function addData() {
	$.ajax({
		url: addUrl,
		type: "post",
		dataType: "json",
		data: new FormData($('#form')[0]),
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
	$.ajax({
		url: editUrl,
		type: "post",
		dataType: "json",
		data: new FormData($('#form')[0]),
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
}

function add() {
	url = "add";
	$(".modal-title").html("Add Data");
	$('.modal button[type="submit"]').html("Add");
}

function edit(id) {
	$.ajax({
		url: getBahanUrl,
		type: "post",
		dataType: "json",
		data: {
			id: id
		},
		success: res => {
			$('[name="id"]').val(res.id);
			$('[name="nama"]').val(res.nama);
			$('[name="satuan"]').append(`<option value='${res.satuan_id}'>${res.satuan}</option>`);
			$('[name="harga"]').val(res.harga);
			$('[name="stok"]').val(res.stok);
			if (res.gambar !== null) {
				$('#gambarReview').attr('src', siteUrl + 'uploads/bahan_baku/' + res.gambar);
			} else {
				$('#gambarReview').attr('src', siteUrl + 'uploads/default.jpg');
			}
			$(".modal").modal("show");
			$(".modal-title").html("Edit Data");
			$('.modal button[type="submit"]').html("Edit");
			url = "edit";
		},
		error: err => {
			console.log(err)
		}
	});
}
bahan_baku.on("order.dt search.dt", () => {
	bahan_baku.column(0, {
		search: "applied",
		order: "applied"
	}).nodes().each((el, val) => {
		el.innerHTML = val + 1
	});
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

$(".modal").on("hidden.bs.modal", () => {
	$("#satuan").val('').trigger("change");
	$("#form")[0].reset();
	$("#gambarReview").attr('src', defaultJpg);
	$("#form").validate().resetForm();
});
