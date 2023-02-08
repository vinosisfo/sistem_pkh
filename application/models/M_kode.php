<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kode extends CI_Model { 
   public function __construct()
   {
      parent::__construct();
      
   }

   function kode_user()
   {
    $tahun = date('y');
    $bulan = date('m');
    $hari = date('d');

    $this->db->select('RIGHT(TbUser.kode_user,3) as kode', FALSE);
    $this->db->order_by('kode_user','DESC');    
    $this->db->limit(1);    
    $query = $this->db->get("TbUser");
    if($query->num_rows() <> 0){      
      //jika kode ternyata sudah ada.      
      $data = $query->row();      
      $kode = intval($data->kode) + 1;    
    }
    else 
    {      
         //jika kode belum ada      
      $kode = 1;    
    }
    $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
    $kode_res = "US".$tahun.$bulan.$kodemax;    // hasilnya ODJ-9921-0001 dst.
    return $kode_res;  
   }

  function kode_kriteria()
   {
    $tahun = date('y');
    $bulan = date('m');
    $hari = date('d');

    $this->db->select('RIGHT(kriteria.kode_kriteria,3) as kode', FALSE);
    $this->db->order_by('kode_kriteria','DESC');    
    $this->db->limit(1);    
    $query = $this->db->get("kriteria");
    if($query->num_rows() <> 0){      
      //jika kode ternyata sudah ada.      
      $data = $query->row();      
      $kode = intval($data->kode) + 1;    
    }
    else 
    {      
         //jika kode belum ada      
      $kode = 1;    
    }
    $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
    $kode_res = "KK".$tahun.$bulan.$kodemax;    // hasilnya ODJ-9921-0001 dst.
    return $kode_res;  
   }

   function kode_nilai()
   {
    $tahun = date('y');
    $bulan = date('m');
    $hari = date('d');

    $this->db->select('RIGHT(Penilaian.kode_penilaian,3) as kode', FALSE);
    $this->db->order_by('kode_penilaian','DESC');    
    $this->db->limit(1);    
    $query = $this->db->get("Penilaian");
    if($query->num_rows() <> 0){      
      //jika kode ternyata sudah ada.      
      $data = $query->row();      
      $kode = intval($data->kode) + 1;    
    }
    else 
    {      
         //jika kode belum ada      
      $kode = 1;    
    }
    $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT); // angka 4 menunjukkan jumlah digit angka 0
    $kode_res = "KN".$tahun.$bulan.$kodemax;    // hasilnya ODJ-9921-0001 dst.
    return $kode_res;  
   }
   
}
