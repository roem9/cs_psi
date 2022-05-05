let nomor_urut = 0;
let id_urut = 0;

$(document).on("click", ".detailClosing", function(){
    $("#btnEdit").show();

    id_urut = 0;
    nomor_urut = 0;

    let id_closing = $(this).data("id");
    let result = ajax(url_base+"app/detail_closing", "POST", {id_closing:id_closing});

    let  form = "#formDetailClosing";

    $.each(result.closing, function(key, value){
        $(form+" [name='"+key+"']").val(value)
    })

    html_detail = "";

    result.detail_closing.forEach(detail => {
        id_urut++;
        nomor_urut++;

        html_detail += `
        <tr id="`+id_urut+`">
            <td>
                <input type="hidden" name="id_varian" value="`+detail.id_varian+`">
                <span class="urut">`+nomor_urut+`</span>
            </td>
            <td>
                `+detail.nama_varian+`
            </td>
            <td class="text-right">
                <input type="number" name="qty" class="form form-control form-control-md required" readonly value="`+detail.qty+`">
            </td>
        </tr>
        `
    })

    $("#listOfClosingDetail").html(html_detail);

    listOfClosing('varianDetail');

    let kelurahan = $(form+" [name='kelurahan']").val();
    let kecamatan = $(form+" [name='kecamatan']").val();

    let choice = `<option value="">Pilih Rekomendasi Kode Pos</option>`;
    result = ajax(url_base+"closing/get_rekomendasi_kode_pos", "POST", {kelurahan:kelurahan, kecamatan:kecamatan});

    console.log(result);

    result.forEach(data => {
        choice += `<option value="`+data.provinsi+`|`+data.kota_kabupaten+`|`+data.kecamatan+`|`+data.kelurahan+`|`+data.kode_pos+`">`+data.kecamatan+` `+data.kelurahan+` `+data.kode_pos+`</option>`
    });

    $("[name='rekomendasi_kode_pos']").html(choice)
})

function listOfClosing(clas_varian){
    let result = ajax(url_base+"produk/get_all_varian", "POST");
    let html = "";

    result.forEach(varian => {
        html += `
        <li class="list-group-item list-group-item-light text-dark">
            <div class="d-flex justify-content-between">
                `+ varian.kode_varian + ` - `+ varian.nama_varian + ` (` + varian.stok + `)
                <a href="javascript:void(0)" class="`+clas_varian+` text-success" data-id="`+varian.id_varian+`">
                    `+tablerIcon("square-plus", "me-1")+`
                </a>
            </div>
        </li>
        `
    })

    $(".listOfClosingSelect").html(html);
}

$(document).on("click", ".komenClosing", function(){
    var id_closing = $(this).data("id");

    loadKomen(id_closing);
})

$("#btnKirimKomen").click(function(){
    Swal.fire({
        icon: 'question',
        text: 'Yakin akan menambahkan komen baru?',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then(function (result) {
        if (result.value) {
            let form = "#komenClosing";
            
            let formData = {};
            $(form+" .form").each(function(index){
                formData = Object.assign(formData, {[$(this).attr("name")]: $(this).val()})
            })

            let eror = required(form);
            
            if( eror == 1){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'lengkapi isi form terlebih dahulu'
                })
            } else {
                let result = ajax(url_base+"app/add_komen", "POST", formData);

                if(result == 1){
                    loadData();
                    $("#komenClosing [name='komen']").val("");

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        text: 'Berhasil menambahkan komen',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    
                    var id_closing = $(form+" [name='id_closing']").val();
                    loadKomen(id_closing);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'terjadi kesalahan, silahkan mulai ulang halaman'
                    })
                }
            }
        }
    })
})

function loadKomen(id_closing) {
    let result = ajax(url_base+"app/list_komen", "POST", {id_closing:id_closing});
    
    $("#komenClosing [name='id_closing'").val(result.closing.id_closing);
    $("#komenClosing #nama_closing").html("<b>"+result.closing.nama_closing+"</b>");
    $("#komenClosing #produk_closing").html("<b>"+result.closing.produk_closing+"</b>");

    html_komen = "";
    result.komen.forEach(komen => {
        html_komen += `<p>
            <b>`+komen.nama_user+` (`+komen.tgl_input+`)</b><br>
            `+komen.komen+`
        </p>`;
    });

    $("#list_komen").html(html_komen);
}

$(document).on("click", ".komplainClosing", function(){
    var id_closing = $(this).data("id");

    loadKomplain(id_closing);
})

function loadKomplain(id_closing) {
    let result = ajax(url_base+"app/list_komplain", "POST", {id_closing:id_closing});

    console.log(result)
    
    $("#komplainClosing [name='id_closing'").val(result.closing.id_closing);
    $("#komplainClosing #nama_closing").html("<b>"+result.closing.nama_closing+"</b>");
    $("#komplainClosing #produk_closing").html("<b>"+result.closing.produk_closing+"</b>");

    html_komplain = "";
    result.komplain.forEach(komplain => {

        if(komplain.status == "Sedang Ditangani"){
            html_komplain += `
                <div class="card mb-3">
                    <div class="card-body">
                        <p>
                            <b>`+komplain.tgl_input+`</b><br>
                            `+komplain.komplain+`
                            <div class="form-floating mb-3">
                                <select name="status" class="form form-control form-control-sm required">
                                    <option value="`+komplain.id_komplain+`|Sedang Ditangani" selected>Sedang Ditangani</option>
                                    <option value="`+komplain.id_komplain+`|Selesai">Selesai</option>
                                </select>
                                <label class="col-form-label">Status</label>
                            </div>
                        </p>
                    </div>
                </div>`;
        } else {
            html_komplain += `
            <div class="card mb-3">
                <div class="card-body">
                    <p>
                        <b>`+komplain.tgl_input+` s.d `+komplain.tgl_tertangani+` (`+komplain.durasi+`)</b><br>
                        `+komplain.komplain+`
                        <div class="form-floating mb-3">
                            <select name="status" class="form form-control form-control-sm required">
                                <option value="`+komplain.id_komplain+`|Sedang Ditangani">Sedang Ditangani</option>
                                <option value="`+komplain.id_komplain+`|Selesai" selected>Selesai</option>
                            </select>
                            <label class="col-form-label">Status</label>
                        </div>
                    </p>
                </div>
            </div>
            `;
        }
    });

    $("#list_komplain").html(html_komplain);
}

$("#btnKirimKomplain").click(function(){
    Swal.fire({
        icon: 'question',
        text: 'Yakin akan menambahkan komplain baru?',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then(function (result) {
        if (result.value) {
            let form = "#komplainClosing";
            
            let formData = {};
            $(form+" .form").each(function(index){
                formData = Object.assign(formData, {[$(this).attr("name")]: $(this).val()})
            })

            let eror = required(form);
            
            if( eror == 1){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'lengkapi isi form terlebih dahulu'
                })
            } else {
                let result = ajax(url_base+"app/add_komplain", "POST", formData);

                if(result == 1){
                    loadData();
                    $("#komplainClosing [name='komplain']").val("");

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        text: 'Berhasil menambahkan komplain',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    
                    var id_closing = $(form+" [name='id_closing']").val();
                    loadKomplain(id_closing);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'terjadi kesalahan, silahkan mulai ulang halaman'
                    })
                }
            }
        }
    })
})

$(document).on("change", "#komplainClosing [name='status']", function(){
    let data = $(this).val();
    var id_closing = $("#komplainClosing [name='id_closing'").val();

    let result = ajax(url_base+"app/change_status_komplain", "POST", {data:data});
    if(result == 1){

        Swal.fire({
            position: 'center',
            icon: 'success',
            text: 'Berhasil mengubah status komplain',
            showConfirmButton: false,
            timer: 1500
        })

        loadKomplain(id_closing);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'terjadi kesalahan, silahkan mulai ulang halaman'
        })
    }
})