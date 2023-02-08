<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_approve_nilai extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("m_approve_nilai");
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
        $this->load->view("f_approve_nilai",$data);
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

    // function get_form($no_kks='',$status='')
    // {
    //     $no_kk  = $this->input->post('no_kk');
    //     $status = $this->input->post("status");
    //     $data_keluarga = $this->db->select("A.nik,A.nama_keluarga,A.pekerjaan,A.status,B.alamat")
    //                              ->from("kk_detail A")
    //                              ->join("Tb_KK B","B.no_kk=A.no_kk")
    //                              ->where("A.no_kk",$no_kk)
    //                              ->where("A.status","KEPALA KELUARGA")
    //                              ->get();

    //     $data_kriteria = $this->db->select("A.kode_kriteria,A.nama_kriteria,A.bobot_kriteria")
    //                               ->from("kriteria A")
    //                               ->get();

    //     $data_list_kk=[];
    //     if($data_kriteria->num_rows()>0)
    //     {
    //         foreach ($data_kriteria->result() as $lists) {
    //             $lists->list_kk=[];
    //             $data_list_kriteria =$this->db->select("A.id_kriteria_detail,A.nama_kriteria_detail,A.nilai")
    //                                           ->from("kriteria_detail A")
    //                                           ->join("kriteria_keluarga B","B.id_kriteria_detail=A.id_kriteria_detail","JOIN")
    //                                           ->where("A.kode_kriteria",$lists->kode_kriteria)
    //                                           ->where("B.no_kk",$no_kk)
    //                                           ->get();
    //             if($data_list_kriteria->num_rows() >0)
    //             {
    //                 $lists->list_kk = $data_list_kriteria->result();
    //                 array_push($data_list_kk, $lists);
    //             }
    //         }
    //     }

    //     // var_dump($data_list_kk);die();
    //     $data["list_keluarga"] = $data_keluarga;
    //     $data["kriteria_kk"]   = $data_list_kk;
    //     $data["status"]        = $status;
    //     $data["no_kk"]         = $no_kk;

    //     // var_dump($status);die();

    //     $form = $this->load->view("f_input_nilai_detail",$data);
    // }

    // function get_nilai()
    // {
    //     $id_detail = $this->input->post("id_detail");
    //     $sql = $this->db->select("A.nilai")
    //                     ->from("kriteria_detail A")
    //                     ->where("A.id_kriteria_detail",$id_detail)
    //                     ->get()
    //                     ->row();
    //     $data_res = [
    //                     "nilai" => number_format($sql->nilai),
    //                 ];
    //     echo json_encode($data_res);
    // }

    // function cek_kriteria()
    // {
    //     $kriteria = $this->input->post("kriteria");
    //     $sql    = $this->db->select("A.nama_kriteria")
    //                        ->from("kriteria A")
    //                        ->where("A.nama_kriteria",$kriteria)
    //                        ->get();
    //     if($sql->num_rows() > 0)
    //     {
    //         $status ="ada";
    //     }
    //     else
    //     {
    //         $status = "kosong";
    //     }
    //     $data_res = ["status" => $status];
    //     echo json_encode($data_res);
    // }

    // function get_duplikat()
    // {
    //     $nama_detail    = $this->input->post("nama_detail");
    //     foreach ($nama_detail as $key => $value) {
    //         $cek_nama[] = $nama_detail[$key];

    //     }

    //     $cek_sql = $this->db->select("A.nama_kriteria")
    //                         ->where_in("A.nama_kriteria",$nama_detail)
    //                         ->from("kriteria A")
    //                         ->get();


    //     $hasil_duplikat = array_diff_key($cek_nama, array_unique($cek_nama));
    //     if(!empty($hasil_duplikat))
    //     {
    //         $status = "ada";
    //     }
    //     else if($cek_sql->num_rows()>0)
    //     {
    //         $status="ada";
    //     }
    //     else
    //     {
    //         $status = "oke";
    //     }
    //     $data_res = array(
    //         "status" => $status,
    //     );

    //     echo json_encode($data_res);
    // }

    function simpan_data()
    {
        $kode_user      = $this->session->userdata("kode_user");
        $tanggal        = $this->input->post("tanggal");

        $hapus_data = $this->db->select("A.kode_penilaian,B.id_penilaian_detail")
                               ->from("Penilaian A")
                               ->join("penilaian_detail B","B.kode_penilaian=A.kode_penilaian")
                               ->where("A.tgl_penilaian",$tanggal)
                               ->get();
        foreach ($hapus_data->result() as $ids) {
            $hapus_action = $this->db->where("id_penilaian_detail",$ids->id_penilaian_detail)
                                     ->delete("Nilai_Akhir");
            $update = [
                "status" => 1,
            ];
            $update_data = $this->db->update("Penilaian",$update,["kode_penilaian"=>$ids->kode_penilaian]);
        }

        if($hapus_action)
        {
            $simpan_nilai = $this->db->query("INSERT INTO nilai_akhir(id_penilaian_detail,nilai_akhir,user_input)
                                              SELECT A1.id_penilaian_detail,((A1.bobot_nilai_kriteria/A1.nilai_max)*A1.bobot_kriteria)/100 as nilai_akhir,'$kode_user' as kode FROM (
                                                SELECT A.kode_penilaian,B.id_penilaian_detail,A.no_kk,B.kode_kriteria,c.nama_kriteria,B.bobot_nilai_kriteria,
                                                (SELECT MAX(A1.bobot_nilai_kriteria) as nilai_max FROM penilaian_detail A1 INNER JOIN penilaian B1 ON B1.kode_penilaian=A1.kode_penilaian WHERE B1.tgl_penilaian='$tanggal' AND A1.kode_kriteria=B.kode_kriteria) AS nilai_max,C.bobot_kriteria
                                                FROM penilaian A INNER JOIN penilaian_detail B ON B.kode_penilaian=A.kode_penilaian
                                                INNER JOIN kriteria C ON C.kode_kriteria=B.kode_kriteria
                                                WHERE A.tgl_penilaian='$tanggal'
                                             ) A1");
        }
        if($simpan_nilai)
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

    // function update_data()
    // {
    //     $kode_user      = $this->session->userdata("kode_user");
    //     $no_kk          = $this->input->post("no_kk");
    //     $id_detail      = $this->input->post("id_detail");
        
    //     $hapus_data = $this->db->where("no_kk",$no_kk)
    //                     ->delete("kriteria_keluarga");
    //     $datas=[];
    //     foreach ($id_detail as $key => $value) {
    //         $row = [
    //             "no_kk"                 => $no_kk,
    //             "id_Kriteria_detail"    => $id_detail[$key],
    //             "user_input"            => $kode_user,
    //         ];

    //         array_push($datas,$row);
    //     }
    //     $simpan_detail = $this->db->insert_batch("kriteria_keluarga",$datas);
    //     if($simpan_detail)
    //     {
    //         $status ="ok";
    //     }
    //     else
    //     {
    //         $status ="gagal";
    //     }
    //     $data_res = [
    //                     "status" => $status,
    //                 ];
    //     echo json_encode($data_res);
    // }

    // function get_edit()
    // {
    //     $kode   = $this->input->post("kode");
    //     $sql    = $this->db->from("Kriteria A")
    //                     ->where("A.kode_kriteria",$kode)
    //                     ->get()
    //                     ->row();

    //     $data_res   = array(
    //         "kode_kriteria"    => $sql->kode_kriteria,
    //         "nama"    => $sql->nama_kriteria,
    //         "bobot"   => $sql->bobot_kriteria,
    //     );
    //     echo json_encode($data_res);
    // }

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

    // function get_no_kk()
    // {
    //     $tanggal = $this->input->post("tanggal");

    //     $sql      = $this->db->select("A.no_kk")
    //                         ->from("Penilaian A")
    //                         ->where("A.tgl_penilaian",$tanggal)
    //                         ->get();
        
    //     if($sql->num_rows()>0){
    //     foreach ($sql->result() as $value) {
    //       $no_kk = $value->no_kk;
    //     }
    //     }
    //     else
    //     {
    //         $no_kk='';
    //     }

    //     $sql2 = $this->db->from("Tb_KK A")
    //                      ->where_not_in("A.no_kk",$no_kk)
    //                      ->group_by("A.no_kk")
    //                      ->get();
    //     $opsi[] = '<option value="">PILIH</option>';
    //     foreach ($sql2->result() as $kk) {
    //         $no_kks[]= '<option value="'.$kk->no_kk.'">'.$kk->no_kk.'</option>';
    //     }
    //     $opsis = array_merge($opsi,$no_kks);
    //     $data_res = [
    //         "opsi" => $opsis,
    //     ];
    //     echo json_encode($data_res);

    // }
	
}
