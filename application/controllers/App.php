<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class App extends MY_Controller {

    public function closing(){
        $data['title'] = 'List Closing';
        $data['menu'] = "closing";
        $data['modal'] = ["modal_closing"];
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
            "load_data/closing_reload.js",
            "modules/closing.js",
        ];

        $this->load->view("pages/app/list_closing", $data);
    }

    public function load_closing(){
        header('Content-Type: application/json');
        $output = $this->app->load_closing();
        echo $output;
    }

    public function detail_closing(){
        $data = $this->app->detail_closing();
        echo json_encode($data);
    }

    public function get_closing(){
        $data = $this->app->get_closing();
        echo json_encode($data);
    }

    public function add_komen(){
        $data = $this->app->add_komen();
        echo json_encode($data);
    }

    public function list_komen(){
        $data = $this->app->list_komen();
        echo json_encode($data);
    }

    public function add_komplain(){
        $data = $this->app->add_komplain();
        echo json_encode($data);
    }

    public function list_komplain(){
        $data = $this->app->list_komplain();
        echo json_encode($data);
    }

    public function change_status_komplain(){
        $data = $this->app->change_status_komplain();
        echo json_encode($data);
    }

    public function laporan(){
        $data['title'] = 'List Laporan';
        $data['menu'] = "laporan";
        $data['modal'] = ["modal_laporan"];
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
            "load_data/laporan_reload.js",
            "modules/laporan.js",
        ];

        $this->load->view("pages/app/list_laporan", $data);
    }

    public function load_laporan(){
        header('Content-Type: application/json');
        $output = $this->app->load_laporan();
        echo $output;
    }

    public function add_laporan(){
        $data = $this->app->add_laporan();
        echo json_encode($data);
    }

    public function edit_laporan(){
        $data = $this->app->edit_laporan();
        echo json_encode($data);
    }

    public function get_laporan(){
        $data = $this->app->get_laporan();
        echo json_encode($data);
    }

    public function kpi(){
        $data['id_cs'] = $this->session->userdata("id_cs");

        $data['menu'] = "kpi";
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
        ];

        $data['setting_cs'] = $this->app->get_one("setting_cs", ["id_cs" => $data['id_cs']]);
        $data['kpi'] = $this->app->get_one("kpi_cs", ["MONTH(tgl_kpi)" => date("m", strtotime($data['setting_cs']['periode'])), "YEAR(tgl_kpi)" => date("Y", strtotime($data['setting_cs']['periode']))]);

        $data['title'] = 'List Key Performance Indicator (KPI) ' . periode($data['setting_cs']['periode']);

        $this->load->view("pages/app/kpi", $data);
    }

    public function komisi(){
        $data['id_cs'] = $this->session->userdata("id_cs");
        $data['title'] = 'List Komisi';
        $data['menu'] = "komisi";
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
        ];

        $data['komisi'] = $this->app->komisi();
        $data['pencairan'] = $this->app->get_all("pencairan_cs", ["id_cs" => $data['id_cs']]);

        // var_dump($data['komisi']['periode']);

        $this->load->view("pages/app/komisi", $data);
    }

    public function conversion_rate(){
        $data['id_cs'] = $this->session->userdata("id_cs");
        $data['title'] = 'Conversion Rate';
        $data['menu'] = "conversion_rate";
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
        ];

        $closing = $this->app->conversion_rate();

        $data['labels'] = [];
        $data['data'] = [];
        $data['warna'] = [];

        foreach ($closing as $i => $data_closing) {
            $data['labels'][$i] = date("d-m-y", strtotime($data_closing['tgl_closing']));
            $data['data'][$i] = $data_closing['jumlah'];
            
            if($i == 0){
                $data['warna'][$i] = "green";
            } else {
                if($data['data'][$i] >= $data['data'][$i-1]){
                    $data['warna'][$i] = "green";
                } else {
                    $data['warna'][$i] = "red";
                }
            }
        }

        $data['labels'] = json_encode($data['labels']);
        $data['data'] = json_encode($data['data']);
        $data['warna'] = json_encode($data['warna']);

        $data['setting_cs'] = $this->app->get_one("setting_cs", ["id_cs" => $data['id_cs']]);

        $this->load->view("pages/app/conversion_rate", $data);
    }

    public function change_periode(){
        $this->app->change_periode();
        
        redirect(base_url("app/kpi"));
    }

    public function change_tgl(){
        $this->app->change_tgl();
        
        redirect(base_url("app/conversion_rate"));
    }

    public function pengaturan(){
        $data['id_cs'] = $this->session->userdata("id_cs");
        $data['title'] = 'Pengaturan';

        $data['menu'] = "pengaturan";
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
        ];
        

        $this->load->view("pages/app/pengaturan", $data);
    }

    public function edit_password(){
        $data = $this->app->edit_password();
        echo json_encode($data);
    }
}

/* End of file Produk.php */
