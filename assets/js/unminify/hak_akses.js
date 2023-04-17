let url, hakAkses = $("#hak_akses").DataTable({
	responsive: true,
	ajax: readUrl,
	columnDefs: [{
		searcable: false,
		orderable: false,
		targets: 0
	}],
	order: [
		[1, "asc"]
	],
	columns: [{
		"data": null,
		"sortable": false,
		render: function (data, type, row, meta) {
			return meta.row + meta.settings._iDisplayStart + 1;
		}
	}, {
		data: "nama"
	}, {
		data: "action"
	}]
});

function reloadTable() {
	hakAkses.ajax.reload()
}

function addData() {
	$.ajax({
		url: addUrl,
		type: "post",
		dataType: "json",
		data: $("#form").serialize(),
		success: () => {
			$(".modal").modal("hide");
			Swal.fire("Sukses", "Sukses Menambahkan Data", "success");
			reloadTable()
		},
		error: err => {
			console.log(err)
		}
	})
}

function remove(id) {
	Swal.fire({
		title: "Hapus",
		text: "Hapus data ini?",
		type: "warning",
		showCancelButton: true
	}).then(function (result) {
		if (result.value) {
			$.ajax({
				url: deleteUrl,
				type: "post",
				dataType: "json",
				data: {
					id: id
				},
				success: () => {
					Swal.fire("Sukses", "Sukses Menghapus Data", "success");
					reloadTable()
				},
				error: err => {
					console.log(err)
				}
			})
		} else {
			Swal.fire('Batal', 'Batal Menghapus Data', 'error');
		}
	})
}

function editData() {
	$.ajax({
		url: editUrl,
		type: "post",
		dataType: "json",
		data: $("#form").serialize(),
		success: () => {
			$(".modal").modal("hide");
			Swal.fire("Sukses", "Sukses Mengedit Data", "success"), reloadTable()
		},
		error: err => {
			console.log(err)
		}
	})
}

function add() {
	url = "add";
	$(".modal-title").html("Add Data");
	$('.modal button[type="submit"]').html("Add")
}

function edit(id) {
	$.ajax({
		url: getHakAksesUrl,
		type: "post",
		dataType: "json",
		data: {
			id: id
		},
		success: res => {
			$('[name="id"]').val(res.id);
			$('[name="nama"]').val(res.name);
			$(".modal").modal("show");
			$(".modal-title").html("Edit Data");
			$('.modal button[type="submit"]').html("Edit");
			url = "edit"
		},
		error: err => {
			console.log(err)
		}
	})
}
hakAkses.on("order.dt search.dt", () => {
	hakAkses.column(0, {
		search: "applied",
		order: "applied"
	}).nodes().each((el, err) => {
		el.innerHTML = err + 1
	})
});
$("#form").validate({
	errorElement: "span",
	errorPlacement: (err, el) => {
		err.addClass("invalid-feedback"), el.closest(".form-group").append(err)
	},
	submitHandler: () => {
		"edit" == url ? editData() : addData()
	}
});
$(".modal").on("hidden.bs.modal", () => {
	$("#form")[0].reset();
	$("#form").validate().resetForm()
});
