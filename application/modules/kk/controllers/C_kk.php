<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_kk extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("m_kk");
        $this->load->model("m_kode"); 
        if(empty($this->session->userdata("kode_user")))
        {
            redirect(base_url('Auth'));
        }
	}

	public function index()
	{	
		$this->load->view('template/css/css_2');
		$this->load->view('template/menu_bar/top_bar_2');
		$this->load->view('template/menu_bar/side_bar_2');

		// $this->load->view("f_user");
        $this->load->view("template/js/js_2");
        $this->load->view("f_kk");

		// $this->load->view("template/menu_bar/footer");

	}

	function get_data()
    {
        $list = $this->m_kk->get_data();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->no_kk;
            $row[] = $field->alamat;
            // $row[] = "";
            $row[] = '<button type="button" class="btn btn-info btn-sm" id="btn_edit_'.$no.'" onclick="edit_data(\''.$field->no_kk.'\')">Edit</button>';
            $row[] = '<a onclick="hapus_data(\''.$field->no_kk.'\')" href="#" class="btn btn-danger btn-sm">Hapus</a>';
 

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_kk->count_all(),
            "recordsFiltered" => $this->m_kk->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function simpan_data()
    {
        $kode_user   = $this->session->userdata("kode_user");
        $no_kk       = $this->input->post("no_kk");
        $alamat      = $this->input->post("alamat");

        $data = [
                    "no_kk"         => $no_kk,
                    "alamat"        => $alamat,
                    "user_input"    => $kode_user,
                ];
        $simpan_data    = $this->db->insert("Tb_KK",$data);
        if($simpan_data)
        {
            $status ="ok";
        }
        else
        {
            $status ="gagal";
        }
        $data_res = [
                        "status" => $status,
                    ];
        echo json_encode($data_res);
    }

    function update_data()
    {
        $kode_user   = $this->session->userdata("kode_user");
        $no_kks      = $this->input->post("no_kks");
        $no_kk       = $this->input->post("no_kk");
        $alamat      = $this->input->post("alamat");

        $data = [
                    "no_kk"         => $no_kk,
                    "alamat"        => $alamat,
                    "user_input"    => $kode_user,
                ];
        $simpan_data    = $this->db->update("Tb_KK",$data,array("no_kk" => $no_kks));
        if($simpan_data)
        {
            $status ="ok";
        }
        else
        {
            $status ="gagal";
        }
        $data_res = [
                        "status" => $status,
                    ];
        echo json_encode($data_res);
    }

    function get_edit()
    {
        $kode  = $this->input->post("kode");
        $sql = $this->db->from("Tb_KK A")
                        ->where("A.no_kk",$kode)
                        ->get()
                        ->row();

        $data_res   = array(
            "no_kks"    => $sql->no_kk,
            "no_kk"     => $sql->no_kk,
            "alamat"    => $sql->alamat,
        );
        echo json_encode($data_res);
    }

    function hapus_data($kode)
    {
        $sql = $this->db->where("no_kk",$kode)
                        ->delete("tb_KK");
        if($sql)
        {
            $status="ok";
            echo json_encode($status);
        }
    }
	
}
