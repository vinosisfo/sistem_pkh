<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_kk_detail extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("M_kk_detail");
        $this->load->model("m_kode"); 
        if(empty($this->session->userdata("kode_user")))
        {
            redirect(base_url('Auth'));
        }
	}

	public function index()
	{	
		$this->load->view('template/css/css_2');
        $this->load->view('template/css/css_table');
		$this->load->view('template/menu_bar/top_bar_2');
		$this->load->view('template/menu_bar/side_bar_2');

		// $this->load->view("f_user");
        $this->load->view("template/js/js_2");
        $this->load->view("f_kk_detail");

		// $this->load->view("template/menu_bar/footer");

	}

	function get_data()
    {
        $list = $this->M_kk_detail->get_data();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->no_kk;
            $row[] = $field->alamat;
            $row[] = $field->nik;
            $row[] = $field->nama_keluarga;
            $row[] = $field->status;
            if(empty($field->nik))
            {
                $row[] = '-';
            }
            else
            {
            $row[] = '<button type="button" class="btn btn-info btn-sm" id="btn_edit_'.$no.'" onclick="edit_data(\''.$field->no_kk.'\')">Edit</button>';
            }
            $row[] = '<a onclick="hapus_data(\''.$field->no_kk.'\',\''.$field->id_kk_detail.'\')" href="#" class="btn btn-danger btn-sm">Hapus</a>';
 

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_kk_detail->count_all(),
            "recordsFiltered" => $this->M_kk_detail->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_form($no_kks='')
    {
        // var_dump($no_kk);die();
        $no_kk = $this->db->select("A.no_kk")
                          ->from("Tb_KK A")
                          ->get();
        $data['no_kk'] = $no_kk;

        $data_kk =  $this->db->select("B.id_kk_detail,A.no_kk,A.alamat,B.nik,B.nama_keluarga,B.status,B.Tgl_Lahir,B.Pekerjaan")
                             ->from("Tb_KK A")
                             ->join("KK_Detail B","B.no_kk=A.no_kk","LEFT")
                             ->where("A.no_kk",$no_kks)
                             ->get();

        $data['status']  = (!empty($no_kks)) ? "edit" : "baru";
        $data["list_kk"] = $data_kk;
        $data["no_kks"]  = $no_kks;
        $form = $this->load->view("f_add_kk_detail",$data);
    }

    function simpan_data()
    {
        $kode_user  = $this->session->userdata("kode_user");
        $no_kk      = $this->input->post("no_kk_detail");

        $nik        = $this->input->post("nik");
        $nama       = $this->input->post("nama");
        $tgl_lahir  = $this->input->post("tgl_lahir");
        $pekerjaan  = $this->input->post("pekerjaan");
        $status     = $this->input->post("status");

        $data=[];
        foreach ($nik as $key => $value) {
             $row = [
                    "no_kk"         => $no_kk,
                    "nik"           => $nik[$key],
                    "nama_keluarga" => $nama[$key],
                    "Tgl_Lahir"     => $tgl_lahir[$key],
                    "Pekerjaan"     => $pekerjaan[$key],
                    "status"        => $status[$key],
                    "user_input"    => $kode_user,
                ];
            array_push($data, $row);
        }
        
        $simpan_data    = $this->db->insert_batch("kk_Detail",$data);
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
        $kode_user  = $this->session->userdata("kode_user");
        $no_kk      = $this->input->post("no_kk_detail");

        $nik        = $this->input->post("nik");
        $nama       = $this->input->post("nama");
        $tgl_lahir  = $this->input->post("tgl_lahir");
        $pekerjaan  = $this->input->post("pekerjaan");
        $status     = $this->input->post("status");

        $hapus_data = $this->db->where("no_kk",$no_kk)
                               ->delete("KK_Detail");
        $data=[];
        foreach ($nik as $key => $value) {
             $row = [
                    "no_kk"         => $no_kk,
                    "nik"           => $nik[$key],
                    "nama_keluarga" => $nama[$key],
                    "Tgl_Lahir"     => $tgl_lahir[$key],
                    "Pekerjaan"     => $pekerjaan[$key],
                    "status"        => $status[$key],
                    "user_input"    => $kode_user,
                ];
            array_push($data, $row);
        }
        
        $simpan_data    = $this->db->insert_batch("kk_Detail",$data);
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

    function hapus_data($kode,$id='')
    {
        
        $where = (!empty($id)) ? "no_kk='$kode' AND id_kk_detail='$id'" : "no_kk='$kode'";
        $sql = $this->db->where($where)
                        ->delete("kk_Detail");
        if($sql)
        {
            $status="ok";
            echo json_encode($status);
        }
    }

    function get_duplikat()
    {
        $nik    = $this->input->post("nik");
        foreach ($nik as $key => $value) {
            $cek_nik[] = $nik[$key];

        }

        $hasil_duplikat = array_diff_key($cek_nik, array_unique($cek_nik));
        if(!empty($hasil_duplikat))
        {
            $status = "ada";
        }
        else
        {
            $status = "oke";
        }
        $data_res = array(
            "status" => $status,
        );

        echo json_encode($data_res);
    }


	
}
