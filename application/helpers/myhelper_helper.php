<?php
    function tablerIcon($icon, $margin = ""){
        return '
            <svg width="24" height="24" class="alert-icon '.$margin.'">
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

    function stok_artikel($id_artikel){
        $CI =& get_instance();

        $CI->db->select("SUM(qty) as stok");
        $CI->db->from("detail_penyetokan");
        $CI->db->where(["id_artikel" => $id_artikel]);
        $CI->db->where(["hapus" => 0]);
        $penyetokan = $CI->db->get()->row_array();
        
        $CI->db->select("SUM(qty) as stok");
        $CI->db->from("detail_penjualan");
        $CI->db->where(["id_artikel" => $id_artikel]);
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

    function list_artikel(){
        $CI =& get_instance();
        $CI->db->from("artikel");
        $CI->db->where("hapus", "0");
        $CI->db->order_by("nama_artikel");
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
        $CI->db->from("detail_penjualan");
        $CI->db->where(["id_penjualan" => $id_penjualan]);
        
        $stok = $CI->db->get()->row_array();

        if($stok) return $stok['stok'];
        else return 0;
    }