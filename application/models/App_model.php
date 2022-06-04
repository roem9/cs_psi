<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends MY_Model {
    public function detail_closing(){
        $id_closing = $this->input->post("id_closing");

        $data['closing'] = $this->get_one("closing", ["id_closing" => $id_closing]);
        $data['detail_closing'] = $this->get_all("detail_closing", ["id_closing" => $id_closing]);

        return $data;
    }

    public function load_closing(){
        $id_cs = $this->session->userdata('id_cs');
        $this->datatables->select('id_closing, tgl_closing, catatan, jenis_closing, nama_closing, nominal_transaksi, nama_cs, nama_gudang, status, tgl_input, tgl_delivery, tgl_ubah_status, tgl_retur_cancel');
        $this->datatables->from('closing');
        $this->datatables->where('id_cs', $id_cs);
        $this->db->order_by("tgl_closing", "desc");

        $this->datatables->edit_column("status", "<span class='badge' style='background-color: $2'>$1</span>", "status, warna_status(status), id_closing");
        $this->datatables->add_column("stok", "$1", "stok_varian(id_closing)");
        $this->datatables->add_column("produk_closing", "$1", "produk_closing(id_closing)");
        $this->datatables->add_column("durasi", "$1", "durasi(tgl_input, tgl_closing, tgl_delivery, tgl_ubah_status, tgl_retur_cancel)");
        $this->datatables->add_column("status_input", "$1", "status_input(tgl_input, tgl_closing)");
        $this->datatables->add_column("status_delivered", "$1", "status_delivered(tgl_delivery, tgl_ubah_status)");

        $this->datatables->add_column('menu','
                    <span class="dropdown">
                    <button class="btn align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">
                        '.tablerIcon("settings", "").'
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item detailClosing" data-bs-toggle="modal" href="#detailClosing" data-id="$1">
                            '.tablerIcon("info-circle", "me-1").'
                            Detail Closing
                        </a>
                        <a class="dropdown-item komenClosing" data-bs-toggle="modal" href="#komenClosing" data-id="$1">
                            '.tablerIcon("message-circle", "me-1").'
                            Komen
                        </a>
                        <a class="dropdown-item komplainClosing" data-bs-toggle="modal" href="#komplainClosing" data-id="$1">
                            '.tablerIcon("alert-octagon", "me-1").'
                            Komplain
                        </a>
                    </div>
                    </span>', 'id_closing, md5(id_closing)');

        return $this->datatables->generate();
    }

    public function get_closing(){
        $id_closing = $this->input->post("id_closing");

        $data = $this->get_one("closing", ["id_closing" => $id_closing]);
        return $data;
    }

    public function add_komen(){
        $id_closing = $this->input->post("id_closing");
        $komen = $this->input->post("komen");
        
        $id_cs = $this->session->userdata('id_cs');
        $cs = $this->get_one("cs", ["id_cs" => $id_cs]);

        $table = "cs";

        $data = [
            "id_closing" => $id_closing,
            "id_user" => $id_cs,
            "nama_user" => $cs['nama_cs'],
            "table_user" => $table,
            "komen" => $komen
        ];

        $query = $this->add_data("komen_closing", $data);

        if($query) return 1;
        else return 0;
    }

    public function list_komen(){
        $id_closing = $this->input->post("id_closing");

        $komen = $this->get_all("komen_closing", ["id_closing" => $id_closing]);

        $data['komen'] = [];
        foreach ($komen as $i => $komen) {
            $data['komen'][$i] = $komen;
            $data['komen'][$i]['tgl_input'] = date("d-m-y h:i", strtotime($komen['tgl_input']));
        }

        $data['closing'] = $this->get_one("closing", ["id_closing" => $id_closing]);
        $data['closing']['produk_closing'] = produk_closing($data['closing']['id_closing']);

        return $data;
    }

    public function add_komplain(){
        $id_closing = $this->input->post("id_closing");
        $komplain = $this->input->post("komplain");
        
        $id_cs = $this->session->userdata('id_cs');
        $cs = $this->get_one("cs", ["id_cs" => $id_cs]);

        $data = [
            "id_closing" => $id_closing,
            "id_cs" => $id_cs,
            "komplain" => $komplain,
            "status" => "Sedang Ditangani"
        ];

        $query = $this->add_data("komplain_closing", $data);

        if($query) return 1;
        else return 0;
    }

    public function list_komplain(){
        $id_closing = $this->input->post("id_closing");

        $komplain = $this->get_all("komplain_closing", ["id_closing" => $id_closing]);

        $data['komplain'] = [];
        foreach ($komplain as $i => $komplain) {
            $data['komplain'][$i] = $komplain;
            $data['komplain'][$i]['tgl_input'] = date("d-m-y", strtotime($komplain['tgl_input']));
            $data['komplain'][$i]['tgl_tertangani'] = date("d-m-y", strtotime($komplain['tgl_tertangani']));
            if($komplain['status'] == "Selesai"){
                $tgl1 = new DateTime(date("Y-m-d", strtotime($komplain['tgl_input'])));
                $tgl2 = new DateTime($komplain['tgl_tertangani']);
                $durasi = date_diff($tgl1, $tgl2);
    
                $data['komplain'][$i]['durasi'] = $durasi->d . " hari";
            }
            
        }

        $data['closing'] = $this->get_one("closing", ["id_closing" => $id_closing]);
        $data['closing']['produk_closing'] = produk_closing($data['closing']['id_closing']);

        return $data;
    }

    public function change_status_komplain(){
        $data = $this->input->post("data");

        $data_komplain = explode("|", $data);

        if($data_komplain[1] == "Sedang Ditangani"){
            $tgl_tertangani = "0000-00-00";
        } else {
            $tgl_tertangani = date("Y-m-d");
        }

        $data = [
            "tgl_tertangani" => $tgl_tertangani,
            "status" => $data_komplain[1]
        ];

        $query = $this->edit_data("komplain_closing", ["id_komplain" => $data_komplain[0]], $data);

        if($query) return 1;
        else return 0;
    }

    public function load_laporan(){
        $id_cs = $this->session->userdata('id_cs');
        $this->datatables->select('id_laporan, tgl_laporan, leads_iklan, leads_inbox, leads_komen, produk');
        $this->datatables->from('laporan_harian');
        $this->datatables->where('id_cs', $id_cs);
        $this->db->order_by("tgl_laporan", "desc");

        $this->datatables->add_column('menu','
                    <span class="dropdown">
                    <button class="btn align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">
                        '.tablerIcon("settings", "").'
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item detailLaporan" data-bs-toggle="modal" href="#detailLaporan" data-id="$1">
                            '.tablerIcon("info-circle", "me-1").'
                            Detail Laporan
                        </a>
                    </div>
                    </span>', 'id_laporan, md5(id_laporan)');

        return $this->datatables->generate();
    }

    public function add_laporan(){
        $id_cs = $id_cs = $this->session->userdata('id_cs');

        $data = [];
        $data['id_cs'] = $id_cs;
        foreach ($_POST as $key => $value) {
            $data[$key] = $this->input->post($key);
        }

        $query = $this->add_data("laporan_harian", $data);
        if($query) return 1;
        else return 0;
    }

    public function edit_laporan(){
        $id_laporan = $this->input->post("id_laporan");
        unset($_POST['id_laporan']);

        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = $this->input->post($key);
        }

        $query = $this->edit_data("laporan_harian", ["id_laporan" => $id_laporan], $data);
        if($query) return 1;
        else return 0;
    }

    public function get_laporan(){
        $id_laporan = $this->input->post("id_laporan");
        $data = $this->get_one("laporan_harian", ["id_laporan" => $id_laporan]);
        return $data;
    }

    public function komisi(){
        $id_cs = $id_cs = $this->session->userdata('id_cs');

        $this->db->select("id_closing, tgl_closing, DATE_FORMAT(tgl_closing, '%M %Y') as periode")->from("closing")->where(["id_cs" => $id_cs, "status" => "Delivered"])->group_by('MONTH(tgl_closing), YEAR(tgl_closing)');
        $periode = $this->db->get()->result_array();

        foreach ($periode as $i => $periode) {
            $komisi = 0;

            $data['periode'][$i] = $periode;
            $closing = $this->get_all("closing", ["MONTH(tgl_closing)" => date("m", strtotime($periode['tgl_closing'])), "YEAR(tgl_closing)" => date("Y", strtotime($periode['tgl_closing'])), "status" => "Delivered"]);
            foreach ($closing as $closing) {
                $detail_closing = $this->get_all("detail_closing",["id_closing" => $closing['id_closing']]);
                foreach ($detail_closing as $detail_closing) {
                    $komisi += ($detail_closing['qty'] * $detail_closing['komisi']);
                }
            }

            $data['periode'][$i]['komisi'] = $komisi;

            // pencairan 
            $this->db->select("SUM(nominal) as pencairan")->from("pencairan_cs")->where(["id_cs" => $id_cs, "MONTH(periode)" => date("m", strtotime($periode['tgl_closing'])), "YEAR(periode)" => date("Y", strtotime($periode['tgl_closing']))]);
            $pencairan = $this->db->get()->row_array();

            $data['periode'][$i]['pencairan'] = $pencairan['pencairan'];
        }

        return $data;

    }

    public function conversion_rate(){
        $id_cs = $id_cs = $this->session->userdata('id_cs');

        $setting_cs = $this->get_one("setting_cs", ["id_cs" => $id_cs]);
        $tgl_awal = $setting_cs['tgl_awal'];
        $tgl_akhir = $setting_cs['tgl_akhir'];
        
        $this->db->select("id_closing, tgl_closing, COUNT(id_closing) as jumlah")->from("closing")->where(["id_cs" => $id_cs])->where("tgl_closing BETWEEN '$tgl_awal' AND '$tgl_akhir'")->group_by('tgl_closing');
        $closing = $this->db->get()->result_array();

        return $closing;
    }

    public function change_periode(){
        $id_cs = $this->session->userdata("id_cs");

        $periode_bulan = $this->input->post("periode_bulan");
        $periode_tahun = $this->input->post("periode_tahun");

        $periode = $periode_tahun . "-" . $periode_bulan . "-01";

        $data = [
            "periode" => $periode
        ];

        $this->edit_data("setting_cs", ["id_cs" => $id_cs], $data);
    }

    public function change_tgl(){
        $id_cs = $this->session->userdata("id_cs");

        $tgl_awal = $this->input->post("tgl_awal");
        $tgl_akhir = $this->input->post("tgl_akhir");

        $data = [
            "tgl_awal" => $tgl_awal,
            "tgl_akhir" => $tgl_akhir,
        ];

        $this->edit_data("setting_cs", ["id_cs" => $id_cs], $data);
    }

    public function edit_password(){
        $id_cs = $this->session->userdata("id_cs");
        $password = $this->input->post("password");

        $query = $this->edit_data("cs", ["id_cs" => $id_cs], ["password" => md5($password)]);
        if($query) return 1;
        else return 0;
    }
}

/* End of file App_model.php */
