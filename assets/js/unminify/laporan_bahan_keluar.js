let laporan_bahan_keluar = $("#laporan_bahan_keluar").DataTable({
		responsive: true,
		ajax: laporanUrl,
		"ordering": false,
		columnDefs: [{
			searcable: false,
			orderable: false,
			targets: 0
		}],
		columns: [{
			"data": null,
			"sortable": false,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			}
		}, {
			data: "tanggal",
        	"sortable": false
		}, {
			data: "nama",
        	"sortable": false
		}, {
			data: "jumlah",
        	"sortable": false
		}, {
			data: "keterangan",
        	"sortable": false
		}]
	}

);

function reloadTable() {
	laporan_bahan_keluar.ajax.reload()
}

function remove(id) {
	Swal.fire({
		title: "Hapus",
		text: "Hapus data ini?",
		type: "warning",
		showCancelButton: true
	}).then(() => {
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
	})
}

laporan_bahan_keluar.on("order.dt search.dt", () => {
	laporan_bahan_keluar.column(0, {
		search: "applied",
		order: "applied"
	}).nodes().each((el, err) => {
		el.innerHTML = err + 1
	})
});

$('#range').daterangepicker();

$(".modal").on("hidden.bs.modal", () => {
	$("#form")[0].reset();
	$("#form").validate().resetForm()
});
