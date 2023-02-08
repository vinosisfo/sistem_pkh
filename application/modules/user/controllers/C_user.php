<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_user extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("m_user");
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
        // $this->load->view("f_user");
        $this->load->view("test");

		// $this->load->view("template/menu_bar/footer");

	}

    function test_data()
    {
        $no_urut = $this->input->post("no_urut");
        $qty     = $this->input->post("qty");

        $jumlah  = 5;

        $qty_set = '';
        foreach ($no_urut as $key => $value) {

            $sql = $this->db->query("SELECT A.nomor,A.qty FROM test A 
                                    WHERE A.id='$no_urut[$key]'");
            $nomor[]= ($sql->num_rows() > 0) ? ($sql->row()->nomor) : 0;
            $qty_c[]=($sql->num_rows() > 0) ? ($sql->row()->qty) : 0;

            
        }
        var_dump($nomor);
        var_dump($qty_c);die();
    }

	function get_data()
    {
        $list = $this->m_user->get_data();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kode_user;
            $row[] = $field->username;
            $row[] = "******";
            $row[] = $field->level;
            $row[] = '<button type="button" class="btn btn-info btn-sm" id="btn_edit_'.$no.'" onclick="edit_data(\''.$field->kode_user.'\')">Edit</button>';
            $row[] = '<a onclick="hapus_data(\''.$field->kode_user.'\')" href="#" class="btn btn-danger btn-sm">Hapus</a>';
 
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_user->count_all(),
            "recordsFiltered" => $this->m_user->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function simpan_data()
    {
        $kode_otomatis  = $this->m_kode->kode_user();
        $username       = $this->input->post("username");
        $password       = md5($this->input->post("password"));
        $level          = $this->input->post("level");

        $data = [
                    "kode_user" => $kode_otomatis,
                    "username"  => $username,
                    "password"  => $password,
                    "level"     => $level,
                ];
        $simpan_data    = $this->db->insert("TbUser",$data);
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
        $kode_user      = $this->input->post("kode_user");
        $username       = $this->input->post("username");
        $password       = md5($this->input->post("password"));
        $level          = $this->input->post("level");

        $data = [
                    "username"  => $username,
                    "password"  => $password,
                    "level"     => $level,
                ];
        $simpan_data    = $this->db->update("TbUser",$data,array("kode_user" => $kode_user));
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
        $kode_user  = $this->input->post("kode_user");
        $sql = $this->db->from("TbUser A")
                        ->where("A.kode_user",$kode_user)
                        ->get()
                        ->row();

        $levels[]   = '<option value="'.$sql->level.'">'.$sql->level.'</option>';
        $level[] = '<option value="">--PILIH--</option>
                    <option value="ADMIN">ADMIN</option>
                    <option value="KADES">KADES</option>';
        $val_level  = array_merge($levels,$level);
        $data_res   = array(
            "kode_user" => $sql->kode_user,
            "username"  => $sql->username,
            "level"     => $val_level,
        );
        echo json_encode($data_res);
    }

    function hapus_data($kode_user)
    {
        $sql = $this->db->where("kode_user",$kode_user)
                        ->delete("TbUser");
        if($sql)
        {
            $status="ok";
            echo json_encode($status);
        }
    }
	
}
