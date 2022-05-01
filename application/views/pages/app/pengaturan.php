<?php $this->load->view("_partials/header")?>
    <div class="wrapper">
        <div class="sticky-top">
            <?php $this->load->view("_partials/navbar-header")?>
            <?php $this->load->view("_partials/navbar")?>
        </div>
        <div class="page-wrapper">
        <div class="container-xl">
                <!-- Page title -->
                <div class="page-header d-print-none">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                <?= $title?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="container-xl">
                    
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h3>Ubah Password</h3>
                            <form id="formUbahPassword">
                                <div class="form-floating mb-3">
                                    <input type="text" name="password" class="form form-control form-control-sm required">
                                    <label class="col-form-label">Password Baru</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="password_konfirm" class="form form-control form-control-sm required">
                                    <label class="col-form-label">Konfirm Password</label>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="javascript:void(0)" class="btn btn-success btnUbahPassword">Ubah Password</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <?php $this->load->view("_partials/footer-bar")?>
        </div>
    </div>

    <!-- load modal -->
    <?php 
        if(isset($modal)) :
            foreach ($modal as $i => $modal) {
                $this->load->view("_partials/modal/".$modal);
            }
        endif;
    ?>

    <script>
        $("#<?= $menu?>").addClass("active")
    </script>

    <!-- load javascript -->
    <?php  
        if(isset($js)) :
            foreach ($js as $i => $js) :?>
                <script src="<?= base_url()?>assets/myjs/<?= $js?>"></script>
                <?php 
            endforeach;
        endif;    
    ?>

    <script>
        $(".btnUbahPassword").click(function(){
            var form = "#formUbahPassword";

            let formData = {};
            $(form+" .form").each(function(){
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
                if(formData.password == formData.password_konfirm){
                    var result = ajax(url_base+"app/edit_password", "POST", formData);

                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        text: 'Berhasil merubah password Anda',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    $(form).trigger("reset");

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'password dan konfirm password tidak sama, silakan input ulang password dan input password'
                    })
                }
            }
        })
    </script>

    
<?php $this->load->view("_partials/footer")?>