<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{ 
		$this->load->view("login");

	}

	function login()
	{
		$username = $this->input->post("username");
		$password = md5($this->input->post("password"));

		$sql = $this->db->from("TbUser A")
						->where("A.username",$username)
						->where("A.password",$password)
						->get();
		if($sql->num_rows() > 0)
		{
			$data = $sql->row();
			$sess_data['kode_user'] = $data->kode_user;
        	$sess_data['username'] 	= $data->username;
        	$sess_data['level'] 	= $data->level;
        	$this->session->set_userdata($sess_data);
        	$level 	= $this->session->userdata("level");
        	$status = "Berhasil";
		}
		else
		{
			$status = "Gagal";
			$level  = "";
		}
		$data_res = [
						"level" 	=> $level,
						"status" 	=> $status,
					];
		echo json_encode($data_res);
	}

	function logout()
	{
		$this->session->sess_destroy();
    	redirect(base_url('Auth'));
	}
	
}
