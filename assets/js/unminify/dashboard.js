function getDays() {
    let now=new Date,
    bulan=now.getMonth()+1,
    tahun=now.getFullYear(),
    hari=new Date(tahun, bulan, 0).getDate(),
    totalHari=[];
    for(var o=0; o<=hari; o++) {
        totalHari.push(o);
    }
    return totalHari
}

function convertToRupiah(angka) {
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for (var i = 0; i < angkarev.length; i++)
		if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
	return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('') + ',-';
}


$.ajax( {
    url:transaksi_hariUrl,
    type:"get",
    dataType:"json",
    success:(res)=> {
        $("#transaksi_hari").html(res.total)
    }
});
/* $.ajax( {
    url:transaksi_terakhirUrl,
    type:"get",
    dataType:"json",
    success:res=> {
        $("#transaksi_terakhir").html(res)
    }
}); */
$.ajax( {
    url:transaksi_nominalUrl,
    type:"get",
    dataType:"json",
    success:res=> {
        $("#transaksi_terakhir").html(convertToRupiah(res.jumlah_total))
    }
});
$.ajax( {
    url:stok_hariUrl,
    type:"get",
    dataType:"json",
    success:res=> {
        $("#stok_hari").html(res.total)
    }
});
$.ajax( {
    url:produk_terlarisUrl,
    type:"get",
    dataType:"json",
    success:res=> {
        var el=$("#produkTerlaris").get(0).getContext("2d");
        new Chart(el, {
            type:"pie",
            data: {
                labels:res.label,
                datasets:[ {
                    backgroundColor: ["#f56954", "#00a65a", "#f39c12", "#00c0ef", "#3c8dbc", "#d2d6de"],
                    data: res.data
                }],
                options: {
                    maintainAspectRatio: false,
                    responsive: true
                }
            }
        })
    },
});
$.ajax( {
    url:data_stokUrl,
    type:"get",
    dataType:"json",
    success:res=> {
        $.each(res, (key, index)=> {
            let html=`<li class="list-group-item">
                ${index.nama_produk}
                <span class="float-right">${index.stok}</span>
            </li>`;
            $("#stok_produk").append(html)
        })
    }
});
$.ajax( {
    url:grafik_bulanUrl,
    type:"post",
    dataType:"json",
    success:res=> {
		var label = [];
		var value = [];
		for (var i in res) {
			label.push(res[i].tanggal);
			value.push(res[i].jumlah);
		}
        var el=$("#bulanIni").get(0).getContext("2d");
        new Chart(el, {
            type:"bar",
            data: {
                labels:label,
                datasets:[ {
                    label: "Total",
                    backgroundColor: "rgba(60,141,188,0.9)",
                    borderColor: "rgba(60,141,188,0.8)",
                    pointRadius: false,
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: value
                }],
            },
			options: {
				maintainAspectRatio:false,
				responsive:true, 
				scales: {
					xAxes:[ {
					
					}],
					yAxes:[{
						ticks: {
							beginAtZero: true,
							stepSize: 1
						}
					}]
				}
			}
        }
        )
    },
    error:err=> {
        console.log(err)
    }
});
