<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_kriteria_keluarga extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("m_kriteria_keluarga");
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
        $get_kk = $this->db->select("A.no_kk")
                           ->from("Tb_KK A")
                           ->join("kriteria_keluarga B","B.no_kk=A.no_kk","left")
                           ->where("B.no_kk IS NULL")
                           ->get();
        $data["no_kk"] = $get_kk;
        $this->load->view("template/js/js_2");
        $this->load->view("f_kriteria_keluarga",$data);

		// $this->load->view("template/menu_bar/footer");

	}

	function get_data()
    {
        $list = $this->m_kriteria_keluarga->get_data();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->no_kk;
            $row[] = $field->Nama_kepala;
            $row[] = $field->nama_kriteria;
            $row[] = number_format($field->bobot_kriteria);
            $row[] = $field->nama_kriteria_detail;
            $row[] = number_format($field->nilai);
            $row[] = '<button type="button" class="btn btn-info btn-sm" id="btn_edit_'.$no.'" onclick="edit_data(\''.$field->no_kk.'\')">Edit</button>';
            $row[] = '<a onclick="hapus_data(\''.$field->no_kk.'\')" href="#" class="btn btn-danger btn-sm">Hapus</a>';
            $data[]= $row;
        }

        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->m_kriteria_keluarga->count_all(),
            "recordsFiltered"   => $this->m_kriteria_keluarga->count_filtered(),
            "data"              => $data,
        );
        echo json_encode($output);
    }

    function get_form($no_kks='',$status='')
    {
        $no_kk  = $this->input->post('no_kk');
        $status = $this->input->post("status");

        $data_keluarga = $this->db->select("A.nik,A.nama_keluarga,A.pekerjaan,A.status")
                                 ->from("kk_detail A")
                                 ->where("A.no_kk",$no_kk)
                                 ->get();

        $data_kriteria = $this->db->select("A.kode_kriteria,A.nama_kriteria")
                                  ->from("kriteria A")
                                  ->get();
        $data_list=[];
        if($data_kriteria->num_rows()>0)
        {
            foreach ($data_kriteria->result() as $list) {
                $list->datas =[];
                $data_detail = $this->db->from("kriteria_detail A")
                                        ->where("A.kode_kriteria",$list->kode_kriteria)
                                        ->get();
                if($data_detail->num_rows() > 0)
                {
                    $list->datas = $data_detail->result();
                    array_push($data_list, $list);
                }
            }
        }


        $data_list_kk=[];
        if($data_kriteria->num_rows()>0)
        {
            foreach ($data_kriteria->result() as $lists) {
                $lists->list_kk=[];
                $data_list_kriteria =$this->db->select("A.id_kriteria_detail,A.nama_kriteria_detail,A.nilai")
                                              ->from("kriteria_detail A")
                                              ->join("kriteria_keluarga B","B.id_kriteria_detail=A.id_kriteria_detail","JOIN")
                                              ->where("A.kode_kriteria",$lists->kode_kriteria)
                                              ->where("B.no_kk",$no_kk)
                                              ->get();
                if($data_list_kriteria->num_rows() >0)
                {
                    $lists->list_kk = $data_list_kriteria->result();
                    array_push($data_list_kk, $lists);
                }
            }
        }



        // var_dump($data_list_kk);die();
        $data["list_keluarga"] = $data_keluarga;
        $data["kriteria"]      = $data_list;
        $data["kriteria_kk"]   = $data_list_kk;
        $data["status"]        = $status;
        $data["no_kk"]         = $no_kk;

        // var_dump($status);die();

        $form = $this->load->view("f_kriteria_keluarga_add",$data);
    }

    function get_nilai()
    {
        $id_detail = $this->input->post("id_detail");
        $sql = $this->db->select("A.nilai")
                        ->from("kriteria_detail A")
                        ->where("A.id_kriteria_detail",$id_detail)
                        ->get()
                        ->row();
        $data_res = [
                        "nilai" => number_format($sql->nilai),
                    ];
        echo json_encode($data_res);
    }

    function cek_kriteria()
    {
        $kriteria = $this->input->post("kriteria");
        $sql    = $this->db->select("A.nama_kriteria")
                           ->from("kriteria A")
                           ->where("A.nama_kriteria",$kriteria)
                           ->get();
        if($sql->num_rows() > 0)
        {
            $status ="ada";
        }
        else
        {
            $status = "kosong";
        }
        $data_res = ["status" => $status];
        echo json_encode($data_res);
    }

    function get_duplikat()
    {
        $nama_detail    = $this->input->post("nama_detail");
        foreach ($nama_detail as $key => $value) {
            $cek_nama[] = $nama_detail[$key];

        }

        $cek_sql = $this->db->select("A.nama_kriteria")
                            ->where_in("A.nama_kriteria",$nama_detail)
                            ->from("kriteria A")
                            ->get();


        $hasil_duplikat = array_diff_key($cek_nama, array_unique($cek_nama));
        if(!empty($hasil_duplikat))
        {
            $status = "ada";
        }
        else if($cek_sql->num_rows()>0)
        {
            $status="ada";
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

    function simpan_data()
    {
        $kode_user      = $this->session->userdata("kode_user");
        $no_kk          = $this->input->post("no_kk");
        $id_detail      = $this->input->post("id_detail");
       
        $datas=[];
        foreach ($id_detail as $key => $value) {
            $row = [
                "no_kk"                 => $no_kk,
                "id_Kriteria_detail"    => $id_detail[$key],
                "user_input"            => $kode_user,
            ];

            array_push($datas,$row);
        }
        $simpan_detail = $this->db->insert_batch("kriteria_keluarga",$datas);
        if($simpan_detail)
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
        $kode_user      = $this->session->userdata("kode_user");
        $no_kk          = $this->input->post("no_kk");
        $id_detail      = $this->input->post("id_detail");
        
        $hapus_data = $this->db->where("no_kk",$no_kk)
                        ->delete("kriteria_keluarga");
        $datas=[];
        foreach ($id_detail as $key => $value) {
            $row = [
                "no_kk"                 => $no_kk,
                "id_Kriteria_detail"    => $id_detail[$key],
                "user_input"            => $kode_user,
            ];

            array_push($datas,$row);
        }
        $simpan_detail = $this->db->insert_batch("kriteria_keluarga",$datas);
        if($simpan_detail)
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
        $kode   = $this->input->post("kode");
        $sql    = $this->db->from("Kriteria A")
                        ->where("A.kode_kriteria",$kode)
                        ->get()
                        ->row();

        $data_res   = array(
            "kode_kriteria"    => $sql->kode_kriteria,
            "nama"    => $sql->nama_kriteria,
            "bobot"   => $sql->bobot_kriteria,
        );
        echo json_encode($data_res);
    }

    function hapus_data($kode)
    {

        $sql = $this->db->where("no_kk",$kode)
                        ->delete("kriteria_keluarga");
        if($sql)
        {
            $status="ok";
            echo json_encode($status);
        }
    }
	
}
