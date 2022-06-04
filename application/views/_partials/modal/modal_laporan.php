<div class="modal modal-blur fade" id="addLaporan" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="user" id="formAddLaporan">
                    <div class="form-floating mb-3">
                        <input type="date" name="tgl_laporan" class="form form-control form-control-sm required">
                        <label class="col-form-label">Tgl Laporan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="produk" class="form form-control form-control-sm required">
                            <option value="">Pilih Produk</option>
                            <?php
                                $produk = produk();
                                foreach ($produk as $produk) :?>
                                <option value="<?= $produk['produk']?>"><?= $produk['produk']?></option>
                            <?php endforeach;?>
                        </select>
                        <label for="">Produk</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="leads_iklan" class="form form-control form-control-sm number required">
                        <label class="col-form-label">Leads Iklan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="leads_inbox" class="form form-control form-control-sm number required">
                        <label class="col-form-label">Leads Inbox</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="leads_komen" class="form form-control form-control-sm number required">
                        <label class="col-form-label">Leads Komen</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn me-3" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary btnTambah">Tambah</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="detailLaporan" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_laporan" class="form required">
                <div class="form-floating mb-3">
                    <input type="date" name="tgl_laporan" class="form form-control form-control-sm required">
                    <label class="col-form-label">Tgl Laporan</label>
                </div>
                <div class="form-floating mb-3">
                    <select name="produk" class="form form-control form-control-sm required">
                        <option value="">Pilih Produk</option>
                        <?php
                            $produk = produk();
                            foreach ($produk as $produk) :?>
                            <option value="<?= $produk['produk']?>"><?= $produk['produk']?></option>
                        <?php endforeach;?>
                    </select>
                    <label for="">Produk</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="leads_iklan" class="form form-control form-control-sm number required">
                    <label class="col-form-label">Leads Iklan</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="leads_inbox" class="form form-control form-control-sm number required">
                    <label class="col-form-label">Leads Inbox</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="leads_komen" class="form form-control form-control-sm number required">
                    <label class="col-form-label">Leads Komen</label>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn me-3" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary btnEdit">Edit</button>
                </div>
            </div>
        </div>
    </div>
</div>