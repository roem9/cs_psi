<?php
    function tablerIcon($icon, $margin = ""){
        return '
            <svg width="24" height="24" class="'.$margin.'">
                <use xlink:href="'.base_url().'assets/tabler-icons-1.39.1/tabler-sprite.svg#tabler-'.$icon.'" />
            </svg>';
    }

    function hari_indo($hari){
        switch($hari){
            case 'Sun':
                $hari_ini = "Minggu";
            break;

            case 'Mon':			
                $hari_ini = "Senin";
            break;
    
            case 'Tue':
                $hari_ini = "Selasa";
            break;
    
            case 'Wed':
                $hari_ini = "Rabu";
            break;
    
            case 'Thu':
                $hari_ini = "Kamis";
            break;
    
            case 'Fri':
                $hari_ini = "Jumat";
            break;
    
            case 'Sat':
                $hari_ini = "Sabtu";
            break;
            
            default:
                $hari_ini = "Tidak di ketahui";
            break;
        }
        return $hari_ini;
    }

    function tgl_indo($tgl, $day = ""){
        $data = explode("-", $tgl);
        $hari = $data[2];
        $bulan = $data[1];
        $tahun = $data[0];

        if($bulan == "01") $bulan = "Januari";
        if($bulan == "02") $bulan = "Februari";
        if($bulan == "03") $bulan = "Maret";
        if($bulan == "04") $bulan = "April";
        if($bulan == "05") $bulan = "Mei";
        if($bulan == "06") $bulan = "Juni";
        if($bulan == "07") $bulan = "Juli";
        if($bulan == "08") $bulan = "Agustus";
        if($bulan == "09") $bulan = "September";
        if($bulan == "10") $bulan = "Oktober";
        if($bulan == "11") $bulan = "November";
        if($bulan == "12") $bulan = "Desember";

        if($day == TRUE){
            $hari_indo = hari_indo(date("D", strtotime($tgl)));

            return $hari_indo . ", " . $hari . " " . $bulan . " " . $tahun;
        } else {
            return $hari . " " . $bulan . " " . $tahun;
        }
    }

    function tgl_waktu($tgl, $day = ""){
        
        $tgl = date("Y-m-d-H-i", strtotime($tgl));
        $data = explode("-", $tgl);
        $hari = $data[2];
        $bulan = $data[1];
        $tahun = $data[0];
        $jam = $data[3];
        $menit = $data[4];

        if($bulan == "01") $bulan = "Januari";
        if($bulan == "02") $bulan = "Februari";
        if($bulan == "03") $bulan = "Maret";
        if($bulan == "04") $bulan = "April";
        if($bulan == "05") $bulan = "Mei";
        if($bulan == "06") $bulan = "Juni";
        if($bulan == "07") $bulan = "Juli";
        if($bulan == "08") $bulan = "Agustus";
        if($bulan == "09") $bulan = "September";
        if($bulan == "10") $bulan = "Oktober";
        if($bulan == "11") $bulan = "November";
        if($bulan == "12") $bulan = "Desember";

        if($day == TRUE){
            $hari_indo = hari_indo(date("D", strtotime($tgl)));

            return $hari_indo . ", " . $hari . " " . $bulan . " " . $tahun . " " . $jam . ":" . $menit;
        } else {
            return $hari . " " . $bulan . " " . $tahun . " " . $jam . ":" . $menit;
        }
    }

    function stok_varian($id_varian){
        $CI =& get_instance();

        $CI->db->select("SUM(qty) as stok");
        $CI->db->from("detail_penyetokan");
        $CI->db->where(["id_varian" => $id_varian]);
        $CI->db->where(["hapus" => 0]);
        $penyetokan = $CI->db->get()->row_array();
        
        $CI->db->select("SUM(qty) as stok");
        $CI->db->from("detail_closing");
        $CI->db->where(["id_varian" => $id_varian]);
        $CI->db->where(["hapus" => 0]);
        $penjualan = $CI->db->get()->row_array();


        return $penyetokan['stok'] - $penjualan['stok'];
    }

    function produk(){
        $CI =& get_instance();
        $CI->db->from("produk");
        $CI->db->where("hapus", "0");
        $CI->db->order_by("produk");
        return $CI->db->get()->result_array();
    }

    function list_varian(){
        $CI =& get_instance();
        $CI->db->from("varian_produk");
        $CI->db->where("hapus", "0");
        $CI->db->order_by("nama_varian");
        return $CI->db->get()->result_array();
    }

    function list_gudang(){
        $CI =& get_instance();
        $CI->db->from("gudang");
        $CI->db->order_by("nama_gudang");
        return $CI->db->get()->result_array();
    }

    function list_cs(){
        $CI =& get_instance();
        $CI->db->from("cs");
        $CI->db->order_by("nama_cs");
        return $CI->db->get()->result_array();
    }

    function rupiah_to_int($data){
        $data = str_replace("Rp. ", "", $data);
        $data = str_replace(".", "", $data);
        return $data;
    }

    function rupiah($angka){
        $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }

    function item_penyetokan($id_penyetokan){
        $CI =& get_instance();
        $CI->db->select("SUM(qty) as stok");
        $CI->db->from("detail_penyetokan");
        $CI->db->where(["id_penyetokan" => $id_penyetokan]);
        
        $stok = $CI->db->get()->row_array();

        if($stok) return $stok['stok'];
        else return 0;
    }

    function item_penjualan($id_penjualan){
        $CI =& get_instance();
        $CI->db->select("SUM(qty) as stok");
        $CI->db->from("detail_closing");
        $CI->db->where(["id_penjualan" => $id_penjualan]);
        
        $stok = $CI->db->get()->row_array();

        if($stok) return $stok['stok'];
        else return 0;
    }

    function produk_closing($id_closing){
        $CI =& get_instance();
        $CI->db->from("detail_closing");
        $CI->db->where(["id_closing" => $id_closing]);
        
        $data = $CI->db->get()->result_array();
        
        // $produk = "<span>";
        $produk = "";
        foreach ($data as $data) {
            $produk .= $data['kode_varian'] . " (" . $data['qty'] . "),";
        }
        $produk = substr($produk, 0, -1);
        // $produk .= "</span>";

        return $produk;
    }

    function durasi($tgl_input = "", $tgl_closing = "", $tgl_delivery = "", $tgl_ubah_status = "", $tgl_retur_cancel = ""){
        if($tgl_delivery == NULL && $tgl_retur_cancel == NULL){
            // $tgl1 = new DateTime($tgl_closing);
            $tgl1 = new DateTime($tgl_input);
            $tgl2 = new DateTime('NOW');
            $durasi = date_diff($tgl1, $tgl2);

            return $durasi->d . " hari";
        } else if($tgl_retur_cancel != NULL){
            $tgl1 = new DateTime($tgl_closing);
            $tgl2 = new DateTime($tgl_retur_cancel);
            $durasi = date_diff($tgl1, $tgl2);

            return $durasi->d . " hari";
        } else {
            $tgl1 = new DateTime($tgl_delivery);
            $tgl2 = new DateTime($tgl_ubah_status);
            $durasi = date_diff($tgl1, $tgl2);

            return $durasi->d . " hari";
        }
    }

    function status_input($tgl_input, $tgl_closing){
        $tgl1 = new DateTime($tgl_input);
        $tgl2 = new DateTime($tgl_closing);
        $durasi = date_diff($tgl1, $tgl2);

        if($durasi->d > 2){
            return '<a data-bs-toggle="tooltip" data-bs-placement="top" title="telat menginput data closing">'.tablerIcon("alert-circle", "text-warning").'</a>';
        } else {
            return "";
        }
    }

    function status_delivered($tgl_delivery, $tgl_ubah_status){
        $tgl1 = new DateTime($tgl_delivery);
        $tgl2 = new DateTime($tgl_ubah_status);
        $durasi = date_diff($tgl1, $tgl2);

        if($durasi->d > 3){
            return '<a data-bs-toggle="tooltip" data-bs-placement="top" title="telat mengupdate status pengiriman">'.tablerIcon("alert-circle", "text-warning").'</a>';
        } else {
            return "";
        }
    }

    function warna_status($status){
        if($status == "Waiting") return "#620000";
        elseif($status == "Shipping") return "#E4CD05";
        elseif($status == "Delivered") return "skyblue";
        elseif($status == "Returned") return "red";
        elseif($status == "Canceled") return "grey";
        elseif($status == "Produksi") return "green";
    }

    function periode($tgl){
        $data = explode("-", $tgl);
        $hari = $data[2];
        $bulan = $data[1];
        $tahun = $data[0];

        if($bulan == "01") $bulan = "Januari";
        if($bulan == "02") $bulan = "Februari";
        if($bulan == "03") $bulan = "Maret";
        if($bulan == "04") $bulan = "April";
        if($bulan == "05") $bulan = "Mei";
        if($bulan == "06") $bulan = "Juni";
        if($bulan == "07") $bulan = "Juli";
        if($bulan == "08") $bulan = "Agustus";
        if($bulan == "09") $bulan = "September";
        if($bulan == "10") $bulan = "Oktober";
        if($bulan == "11") $bulan = "November";
        if($bulan == "12") $bulan = "Desember";

        return $bulan." ".$tahun;
    }

    function leads($data){
        $CI =& get_instance();
        $CI->db->select("SUM(leads_iklan) as leads_iklan, SUM(leads_inbox) as leads_inbox, SUM(leads_komen) as leads_komen");
        $CI->db->from("laporan_harian");
        $CI->db->where(["MONTH(tgl_laporan)" => date('m', strtotime($data['periode'])), "YEAR(tgl_laporan)" => date('Y', strtotime($data['periode']))]);
        
        $leads = $CI->db->get()->row_array();


        if($leads) return $leads['leads_iklan'] + $leads['leads_inbox'] + $leads['leads_komen'];
        else return 0;
    }

    function closing($data){
        $CI =& get_instance();
        $CI->db->select("id_closing");
        $CI->db->from("closing");
        $CI->db->where(["MONTH(tgl_closing)" => date('m', strtotime($data['periode'])), "YEAR(tgl_closing)" => date('Y', strtotime($data['periode'])), "id_cs" => $data['id_cs']]);
        
        $closing = $CI->db->get()->result_array();


        if($closing) return COUNT($closing);
        else return 0;
    }

    function delivered_closing($data){
        $CI =& get_instance();
        $CI->db->select("id_closing");
        $CI->db->from("closing");
        $CI->db->where(["MONTH(tgl_closing)" => date('m', strtotime($data['periode'])), "YEAR(tgl_closing)" => date('Y', strtotime($data['periode'])), "id_cs" => $data['id_cs'], "status" => "Delivered"]);
        $delivered = $CI->db->get()->result_array();

        
        $CI->db->select("id_closing");
        $CI->db->from("closing");
        $CI->db->where(["MONTH(tgl_closing)" => date('m', strtotime($data['periode'])), "YEAR(tgl_closing)" => date('Y', strtotime($data['periode'])), "id_cs" => $data['id_cs']]);
        $closing = $CI->db->get()->result_array();

        $persentase = (count($delivered) / count($closing)) * 100;


        if($delivered) return $persentase;
        else return 0;
    }

    function customer_retensi($data){
        $CI =& get_instance();
        $CI->db->select("id_closing");
        $CI->db->from("closing");
        $CI->db->where(["MONTH(tgl_closing)" => date('m', strtotime($data['periode'])), "YEAR(tgl_closing)" => date('Y', strtotime($data['periode'])), "id_cs" => $data['id_cs'], "jenis_closing" => "RO"]);
        $CI->db->or_where("jenis_closing", "Cross Selling");
        
        $closing = $CI->db->get()->result_array();


        if($closing) return COUNT($closing);
        else return 0;
    }

    function referal($data){
        $CI =& get_instance();
        $CI->db->select("id_closing");
        $CI->db->from("closing");
        $CI->db->where(["MONTH(tgl_closing)" => date('m', strtotime($data['periode'])), "YEAR(tgl_closing)" => date('Y', strtotime($data['periode'])), "id_cs" => $data['id_cs'], "jenis_closing" => "Referal"]);
        
        $closing = $CI->db->get()->result_array();


        if($closing) return COUNT($closing);
        else return 0;
    }

    function kesalahan_data($data){
        $CI =& get_instance();
        $CI->db->select("id_closing");
        $CI->db->from("closing");
        $CI->db->where(["MONTH(tgl_closing)" => date('m', strtotime($data['periode'])), "YEAR(tgl_closing)" => date('Y', strtotime($data['periode'])), "id_cs" => $data['id_cs'], "kesalahan_data" => "Ya"]);
        
        $closing = $CI->db->get()->result_array();


        if($closing) return COUNT($closing);
        else return 0;
    }

    function komplain($data){
        $CI =& get_instance();
        $CI->db->select("id_closing");
        $CI->db->from("closing as closing");
        $CI->db->where(["MONTH(tgl_closing)" => date('m', strtotime($data['periode'])), "YEAR(tgl_closing)" => date('Y', strtotime($data['periode'])), "id_cs" => $data['id_cs']])
        ->where("closing.id_closing IN (select id_closing from komplain_closing)");
        
        $closing = $CI->db->get()->result_array();

        if($closing) return COUNT($closing);
        else return 0;
    }

    function rapel_laporan(){
        $CI =& get_instance();
        $CI->db->select("id_laporan, tgl_laporan, tgl_input, DATEDIFF(tgl_input, tgl_laporan) as rapel");
        $CI->db->from("laporan_harian");
        $CI->db->having("rapel <> 1");
        
        $laporan = $CI->db->get()->result_array();
        // $laporan = $CI->db->get()->row_array();

        if($laporan) return count($laporan);
        else return 0;
    }

    function minimal_maksimal($data1, $data2, $operator){
        if($operator == "Minimal"){
            if($data1 >= $data2) return "<td class='bg-success'>Success";
            else return "<td class='bg-danger'><center>Failed</center></td>";
        } else {
            if($data1 <= $data2) return "<td class='bg-success'>Success";
            else return "<td class='bg-danger'><center>Failed</center></td>";
        }
    }