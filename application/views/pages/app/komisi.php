<?php $this->load->view("_partials/header")?>
    <div class="wrapper">
        <div class="sticky-top">
            <?php $this->load->view("_partials/navbar-header")?>
            <?php $this->load->view("_partials/navbar")?>
        </div>
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    
                    <div class="card shadow mb-4 overflow-auto">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                
                                <?php 
                                    $sisa_komisi = 0;
                                    if($komisi){
                                        foreach ($komisi['periode'] as $data_komisi){
                                            $sisa_komisi += ($data_komisi['komisi'] - $data_komisi['pencairan']);
                                        }
                                    }
                                ?>

                                <h3>Rekapan Komisi</h3>
                                <h3>Sisa Komisi <?= rupiah($sisa_komisi)?></h3>
                            </div>
                            <table id="dataTable" class="table card-table table-vcenter text-dark">
                                <thead>
                                    <tr>
                                        <th class="text-dark desktop" style="font-size: 11px">Periode</th>
                                        <th class="text-dark desktop" style="font-size: 11px"><center>Total Komisi</center></th>
                                        <th class="text-dark desktop" style="font-size: 11px"><center>Komisi Cair</center></th>
                                        <th class="text-dark desktop" style="font-size: 11px"><center>Sisa Komisi</center></th>
                                        <th class="text-dark desktop w-1 text-nowrap" style="font-size: 11px"><center>Status Komisi</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($komisi) :?>
                                        <?php
                                            foreach ($komisi['periode'] as $komisi) :?>
                                            <tr>
                                                <td><?= $komisi['periode']?></td>
                                                <td><center><?= rupiah($komisi['komisi'])?></center></td>
                                                <td><center><?= rupiah($komisi['pencairan'])?></center></td>
                                                <td><center><?= rupiah($komisi['komisi'] - $komisi['pencairan'])?></center></td>
                                                <?php if($komisi['pencairan'] == 0) :?>
                                                    <td class="text-nowrap bg-danger text-light"><center>Belum Cair</center></td>
                                                <?php elseif($komisi['komisi'] - $komisi['pencairan'] == 0):?>
                                                    <td class="text-nowrap bg-success text-light"><center>Cair Seluruhnya</center></td>
                                                <?php else :?>
                                                    <td class="text-nowrap bg-warning text-light"><center>Cair Sebagian</center></td>
                                                <?php endif;?>
                                            </tr>
                                            <?php endforeach;?>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card shadow mb-4 overflow-auto">
                        <div class="card-body">
                            <h3>Riwayat Pencairan</h3>
                            <table id="dataTable" class="table card-table table-vcenter text-dark">
                                <thead>
                                    <tr>
                                        <th class="text-dark desktop w-1 text-nowrap" style="font-size: 11px">Tgl Pencairan</th>
                                        <th class="text-dark desktop w-1 text-nowrap" style="font-size: 11px">Periode</th>
                                        <th class="text-dark desktop w-1" style="font-size: 11px">Nominal</th>
                                        <th class="text-dark desktop" style="font-size: 11px">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($pencairan) :?>
                                        <?php
                                            foreach ($pencairan as $pencairan) :?>
                                            <tr>
                                                <td><?= date("d M Y", strtotime($pencairan['tgl_pencairan']))?></td>
                                                <td class="text-nowrap"><?= periode($pencairan['periode'])?></td>
                                                <td class="text-nowrap"><?= rupiah($pencairan['nominal'])?></td>
                                                <td><?= $pencairan['catatan']?></td>
                                            </tr>
                                            <?php endforeach;?>
                                    <?php endif;?>
                                </tbody>
                            </table>
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

    
<?php $this->load->view("_partials/footer")?>