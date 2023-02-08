<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_kriteria extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("m_kriteria");
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
        $this->load->view("f_kriteria");

		// $this->load->view("template/menu_bar/footer");

	}

	function get_data()
    {
        $list = $this->m_kriteria->get_data();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kode_kriteria;
            $row[] = $field->nama_kriteria;
            $row[] = number_format($field->bobot_kriteria).' %';
            $row[] = $field->Nama_Kriteria_detail;
            $row[] = number_format($field->nilai);
            $row[] = '<button type="button" class="btn btn-info btn-sm" id="btn_edit_'.$no.'" onclick="edit_data(\''.$field->kode_kriteria.'\')">Edit</button>';
            $row[] = '<a onclick="hapus_data(\''.$field->kode_kriteria.'\')" href="#" class="btn btn-danger btn-sm">Hapus</a>';
            $data[]= $row;
        }

        $output = array(
            "draw"              => $_POST['draw'],
            "recordsTotal"      => $this->m_kriteria->count_all(),
            "recordsFiltered"   => $this->m_kriteria->count_filtered(),
            "data"              => $data,
        );
        echo json_encode($output);
    }

    function get_form($kode_kriteria='')
    {
        $kode_kriteria = $this->input->post('kode');
        $cek_sisa = $this->db->select("A.bobot_kriteria,sum(A.bobot_kriteria) as bobot")
                          ->from("kriteria A")
                          ->get();

        $bobot_kriteria = ($cek_sisa->num_rows() >0) ? $cek_sisa->row()->bobot : 0;
        $sisa = 100-$bobot_kriteria;

        $data['sisa'] = $sisa;

        $data_kriteria = $this->db->select("A.kode_kriteria,A.nama_kriteria,A.bobot_kriteria,B.id_kriteria_detail,
                                            B.Nama_Kriteria_detail,B.nilai")
                                 ->from("kriteria A")
                                 ->join("kriteria_detail B","B.kode_kriteria=A.kode_kriteria")
                                 ->where("A.kode_kriteria",$kode_kriteria)
                                 ->get();

        $data['status']  = (!empty($kode_kriteria)) ? "edit" : "baru";
        $data["list_kriteria"] = $data_kriteria;
        $data["kode_kriteria"] = $kode_kriteria; 
        $form = $this->load->view("f_kriteria_detail",$data);
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

    function get_nilai($nilais='')
    {
        $nilai    = $this->input->post("nilai");
        foreach ($nilai as $key => $value) {
            $cek_nilai[] = $nilai[$key];

        }

        $hasil_duplikat = array_diff_key($cek_nilai, array_unique($cek_nilai));
        if(!empty($hasil_duplikat))
        {
            $status = "ada";
        }
        else if($nilais>100)
        {
            $status = "l_100";
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
        $kode_kriteria  = $this->m_kode->kode_kriteria();
        $nama           = $this->input->post("nama_kriteria");
        $bobot          = str_replace(',', '', $this->input->post("bobot"));

        $nama_detail    = $this->input->post("nama_detail");
        $nilai          = str_replace(',', '', $this->input->post("nilai"));

        $data = [
                    "kode_kriteria"     => $kode_kriteria,
                    "nama_kriteria"     => $nama,
                    "bobot_kriteria"    => $bobot,
                    "user_input"        => $kode_user,
                ];
        $simpan_data    = $this->db->insert("Kriteria",$data);

        if($simpan_data)
        {
            $datas=[];
            foreach ($nama_detail as $key => $value) {
                $row = [
                    "kode_kriteria"         => $kode_kriteria,
                    "nama_Kriteria_detail"  => $nama_detail[$key],
                    "nilai"                 => $nilai[$key],
                ];

                array_push($datas,$row);
            }
            $simpan_detail = $this->db->insert_batch("kriteria_detail",$datas);
        }
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
        $kode_user      = $this->session->userdata("kode_user");
        $kode_kriteria  = $this->input->post("kode_kriteria");
        $nama           = $this->input->post("nama_kriteria");
        $bobot          = str_replace(',', '', $this->input->post("bobot"));

        $nama_detail    = $this->input->post("nama_detail");
        $nilai          = str_replace(',', '', $this->input->post("nilai"));

        $data = [
                    "nama_kriteria"     => $nama,
                    "bobot_kriteria"    => $bobot,
                    "user_input"        => $kode_user,
                ];
        $simpan_data    = $this->db->update("Kriteria",$data,["kode_kriteria" => $kode_kriteria]);

        if($simpan_data)
        {
            $hapus_data = $this->db->where("kode_kriteria",$kode_kriteria)
                                   ->delete("kriteria_detail");
            $datas=[];
            foreach ($nama_detail as $key => $value) {
                $row = [
                    "kode_kriteria"         => $kode_kriteria,
                    "nama_Kriteria_detail"  => $nama_detail[$key],
                    "nilai"                 => $nilai[$key],
                ];

                array_push($datas,$row);
            }
            $simpan_detail = $this->db->insert_batch("kriteria_detail",$datas);
        }
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
        $sql = $this->db->where("kode_kriteria",$kode)
                        ->delete("Kriteria");
        if($sql)
        {
            $status="ok";
            echo json_encode($status);
        }
    }
	
}
