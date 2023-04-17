let laporan_stok_keluar=$("#laporan_stok_keluar").DataTable( {
    responsive:true,
    ajax:readUrl,
	"ordering": false,
    columnDefs:[ {
        searcable: false,
        orderable: false,
        targets: 0
    }],
    columns:[ {
        data: null
    }
    , {
        data: "tanggal",
        "sortable": false
    }
    , {
        data: "barcode",
        "sortable": false
    }
    , {
        data: "nama_produk",
        "sortable": false
    }
    , {
        data: "jumlah",
        "sortable": false
    }
    , {
        data: "keterangan",
        "sortable": false
    }]
}

);
function reloadTable() {
    laporan_stok_keluar.ajax.reload()
}

function remove(id) {
    Swal.fire( {
        title: "Hapus",
        text: "Hapus data ini?",
        type: "warning",
        showCancelButton: true
    }).then(()=> {
        $.ajax( {
            url:deleteUrl, type:"post", dataType:"json", data: {
                id: id
            },
            success:()=> {
                Swal.fire("Sukses", "Sukses Menghapus Data", "success");
                reloadTable()
            },
            error:err=> {
                console.log(err)
            }
        }
        )
    }
    )
}

laporan_stok_keluar.on("order.dt search.dt", ()=> {
    laporan_stok_keluar.column(0, {
        search: "applied", order: "applied"
    }).nodes().each((el, err)=> {
        el.innerHTML=err+1
    })
});

$('#range').daterangepicker();

$(".modal").on("hidden.bs.modal", ()=> {
    $("#form")[0].reset();
    $("#form").validate().resetForm()
});
