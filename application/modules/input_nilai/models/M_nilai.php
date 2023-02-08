<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_nilai extends CI_Model { 
   var $column_order = array('A1.no_kk','A1.tgl_penilaian');
   var $column_search = array('A1.no_kk','A1.tgl_penilaian');
   var $order = array('A1.no_kk' => 'ASC');
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
    $sql = "(SET @sql = NULL;
            SET @kepala = 'KEPALA KELUARGA';
            SET @no_kk = no_kk;
            SET @tanggal = tanggal;
          SELECT
            GROUP_CONCAT(DISTINCT
              CONCAT(
                'MAX(IF(pa.nama_kriteria_show = ''',
                nama_kriteria_show,
                ''', pa.bobot_nilai_kriteria, NULL)) AS ',
                nama_kriteria_show
              )
            ) INTO @sql
          FROM penilaian_detail;

          SET @sql = CONCAT('SELECT p.kode_penilaian, p.no_kk,p.tgl_penilaian,pc.nama_keluarga', IF(@sql IS NULL, '',CONCAT(',',@sql)),'FROM penilaian p LEFT JOIN penilaian_detail AS pa ON p.kode_penilaian = pa.kode_penilaian INNER JOIN (select kk.nama_keluarga,kk.no_kk from kk_detail AS kk where KK.status='@kepala') pc on pc.no_kk=p.no_kk  GROUP BY p.kode_penilaian');

          PREPARE stmt FROM @sql;
          EXECUTE stmt;
          DEALLOCATE PREPARE stmt;)A1";

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
    $sql = "(CALL view_nilai('',''))A1";

    $this->db->query("CALL view_nilai()");
    return $this->db->count_all_results();
  }  

   
}
