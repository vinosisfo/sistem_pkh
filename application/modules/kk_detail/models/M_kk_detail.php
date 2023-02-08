<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kk_detail extends CI_Model { 
   var $column_order = array('A.no_kk','A.alamat','B.status','B.nama_keluarga','B.nik');
   var $column_search = array('A.no_kk','A.alamat','B.status','B.nama_keluarga','B.nik');
   var $order = array('A.no_kk' => 'ASC');
   public function __construct()
   {
      parent::__construct();
      
   }

   private function query_data()
   { 

     
    // if($this->input->post('username'))
    // {
    //     $this->db->like('username', $this->input->post('username'));
    // }

    // if($this->input->post('level'))
    // {
    //     $this->db->like('level', $this->input->post('level'));
    // }
    $this->db->select("B.id_kk_detail,A.no_kk,A.alamat,B.nik,B.nama_keluarga,B.status")
             ->from("Tb_KK A")
             ->join("KK_Detail B","B.no_kk=A.no_kk","LEFT");
      
    $i = 0;
    foreach ($this->column_search as $item) // loop column 
    {
      if($_POST['search']['value']) // if datatable send POST for search
      {
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if(count($this->column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_data()
  {
    $this->query_data();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered()
  {
    $this->query_data();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
     $this->db->from("Tb_KK A")
              ->join("KK_Detail B","B.no_kk=A.no_kk","LEFT");
    return $this->db->count_all_results();
  }  

   
}
