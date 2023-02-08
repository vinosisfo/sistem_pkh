<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_input_nilai extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("m_nilai");
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
                           ->get();
        $data["no_kk"] = $get_kk;
        $this->load->view("template/js/js_2");
        $this->load->view("f_input_nilai",$data);

		// $this->load->view("template/menu_bar/footer");

	}

	function get_data()
    {
        $no_kk      = $this->input->post("no_kk_src");
        $tanggal    = $this->input->post("tanggal_src");

        $data["no_kk"]  = $no_kk;
        $data["tgl"]    = $tanggal;
        $this->load->view("f_list",$data);
    }

    function get_form($no_kks='',$status='')
    {
        $no_kk  = $this->input->post('no_kk');
        $status = $this->input->post("status");
        $data_keluarga = $this->db->select("A.nik,A.nama_keluarga,A.pekerjaan,A.status,B.alamat")
                                 ->from("kk_detail A")
                                 ->join("Tb_KK B","B.no_kk=A.no_kk")
                                 ->where("A.no_kk",$no_kk)
                                 ->where("A.status","KEPALA KELUARGA")
                                 ->get();

        $data_kriteria = $this->db->select("A.kode_kriteria,A.nama_kriteria,A.bobot_kriteria")
                                  ->from("kriteria A")
                                  ->get();

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
        $data["kriteria_kk"]   = $data_list_kk;
        $data["status"]        = $status;
        $data["no_kk"]         = $no_kk;

        // var_dump($status);die();

        $form = $this->load->view("f_input_nilai_detail",$data);
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
        $kode_nilai     = $this->m_kode->kode_nilai();
        $no_kk          = $this->input->post("no_kk");
        $tanggal        = $this->input->post("tanggal");

        $kode_kriteria  = $this->input->post("kode_kriteria");
        $nama_kriteria  = $this->input->post("nama_kriteria");
        $nilai          = $this->input->post("nilai");
        $id_kriteria_detail = $this->input->post("id_kriteria_detail");
        
        $row = [
                "kode_penilaian"        => $kode_nilai,
                "no_kk"                 => $no_kk,
                "tgl_penilaian"         => $tanggal,
                "user_input"            => $kode_user,
                "status"                => 0,
            ];
        $simpan_data = $this->db->insert("Penilaian",$row);
        $data=[];
        foreach ($kode_kriteria as $key => $value) {
            $datas = [
                "kode_penilaian"        => $kode_nilai,
                "kode_kriteria"         => $kode_kriteria[$key],
                "nama_kriteria_show"    => $nama_kriteria[$key],
                "bobot_nilai_kriteria"  => $nilai[$key],
                "id_kriteria_detail"    => $id_kriteria_detail[$key],
               
            ];

            array_push($data,$datas);
        }
        $simpan_detail = $this->db->insert_batch("penilaian_detail",$data);
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

    function get_no_kk()
    {
        $tanggal = $this->input->post("tanggal");

        $sql      = $this->db->select("A.no_kk")
                            ->from("Penilaian A")
                            ->where("A.tgl_penilaian",$tanggal)
                            ->get();
        
        if($sql->num_rows()>0){
        foreach ($sql->result() as $value) {
          $no_kk = $value->no_kk;
        }
        }
        else
        {
            $no_kk='';
        }

        $sql2 = $this->db->from("Tb_KK A")
                         ->where_not_in("A.no_kk",$no_kk)
                         ->group_by("A.no_kk")
                         ->get();
        $opsi[] = '<option value="">PILIH</option>';
        foreach ($sql2->result() as $kk) {
            $no_kks[]= '<option value="'.$kk->no_kk.'">'.$kk->no_kk.'</option>';
        }
        $opsis = array_merge($opsi,$no_kks);
        $data_res = [
            "opsi" => $opsis,
        ];
        echo json_encode($data_res);

    }
	
}
