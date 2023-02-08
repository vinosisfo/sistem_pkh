<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kriteria_keluarga extends CI_Model { 
   var $column_order = array('A1.id_kriteria_keluarga','A1.no_kk','A1.nama_kepala','A1.nama_kriteria','A1.bobot_kriteria','A1.id_kriteria_detail','A1.nama_Kriteria_detail','A1.nilai');
   var $column_search = array('A1.id_kriteria_keluarga','A1.no_kk','A1.nama_kepala','A1.nama_kriteria','A1.bobot_kriteria','A1.id_kriteria_detail','A1.nama_Kriteria_detail','A1.nilai');
   var $order = array('A1.nama_kriteria' => 'ASC');
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
    $sql = "(SELECT A.id_kriteria_keluarga,A.no_kk,C.nama_keluarga AS Nama_kepala,E.nama_kriteria,E.bobot_kriteria,
                D.id_kriteria_detail,D.nama_kriteria_detail,D.nilai FROM kriteria_keluarga A 
            INNER JOIN tb_kk B ON B.no_kk=A.no_kk
            INNER JOIN (SELECT C.no_kk,C.nama_keluarga FROM kk_detail C WHERE C.status='KEPALA KELUARGA' ) C ON C.no_kk=B.no_kk
            INNER JOIN kriteria_detail D ON D.id_kriteria_detail=A.id_kriteria_detail
            INNER JOIN kriteria E ON E.kode_kriteria=D.kode_kriteria) A1";

    $this->db->from($sql);
      
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
    $sql = "(SELECT A.id_kriteria_keluarga,A.no_kk,C.nama_keluarga AS Nama_kepala,E.nama_kriteria,E.bobot_kriteria,
                D.id_kriteria_detail,D.nama_kriteria_detail,D.nilai FROM kriteria_keluarga A 
            INNER JOIN tb_kk B ON B.no_kk=A.no_kk
            INNER JOIN (SELECT C.no_kk,C.nama_keluarga FROM kk_detail C WHERE C.status='KEPALA KELUARGA' ) C ON C.no_kk=B.no_kk
            INNER JOIN kriteria_detail D ON D.id_kriteria_detail=A.id_kriteria_detail
            INNER JOIN kriteria E ON E.kode_kriteria=D.kode_kriteria) A1";

    $this->db->from($sql);
    return $this->db->count_all_results();
  }  

   
}
