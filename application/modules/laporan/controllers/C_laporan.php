<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_laporan extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library("fpdf_general");
        $this->load->model("m_laporan");
        $this->load->model("m_kode"); 
        if(empty($this->session->userdata("kode_user")))
        {
            redirect(base_url('C_login'));
        }
	}

	public function laporan_kriteria()
	{	
    $this->load->view('template/css/css_2');
    $this->load->view('template/css/css_table');
    $this->load->view('template/menu_bar/top_bar_2');
    $this->load->view('template/menu_bar/side_bar_2');

    $kk = $this->db->from("Tb_KK A")
                   ->get();
    // $this->load->view("f_user");
    $this->load->view("template/js/js_2",["kk" => $kk]);
    $this->load->view("f_kriteria");

	}

    function get_kriteria()
    {
        $no_kk   = $this->input->get("kk");


        $sql = $this->db->from("kriteria_detail A")
                        ->join("kriteria B","B.kode_kriteria=A.kode_kriteria")
                        ->join("kriteria_keluarga C","C.id_kriteria_detail=A.id_kriteria_detail")
                        ->where("C.no_kk",$no_kk)
                        ->get();

        $this->load->library('fpdf_general');
        $pdf = new fpdf_general('P','cm',array(20,28)); 
        // $pdf->SetMargins(0.2,0.2,0,2);
        $pdf->AddPage();
        $pdf->AliasNbPages();

        $pdf->Cell(3,0.6,'No KK',0,0,'L');
        $pdf->Cell(0.2,0.6,':',0,0,'C');
        $pdf->Cell(5,0.6,$sql->row()->no_kk,0,1,'L');

        $keluarga = $this->db->from("Tb_KK A ")
                             ->join("kk_detail B","B.no_kk=A.no_kk")
                             ->where("A.no_kk",$no_kk)
                             ->where("B.status","KEPALA KELUARGA")
                             ->limit(1)
                             ->get();

        $pdf->Cell(3,0.6,'Kepala Keluarga',0,0,'L');
        $pdf->Cell(0.2,0.6,':',0,0,'C');
        $pdf->Cell(5,0.6,$keluarga->row()->nama_keluarga,0,1,'L');

        $pdf->Cell(3,0.6,'Alamat',0,0,'L');
        $pdf->Cell(0.2,0.6,':',0,0,'C');
        $pdf->Cell(5,0.6,$keluarga->row()->alamat,0,1,'L');

        $pdf->Cell(1,0.6,'No',1,0,'C');
        $pdf->Cell(3,0.6,'Kriteria',1,0,'C');
        $pdf->Cell(3,0.6,'Nama Kriteria',1,0,'C');
        $pdf->Cell(3,0.6,'Nilai',1,0,'C');
        $pdf->Cell(3,0.6,'Bobot',1,1,'C');

        $no=0;
        foreach ($sql->result() as $item){
        $no++;
        $pdf->SetAligns(array('L','L','L','L','R','R'));
        $pdf->SetWidths(array(1,3,3,3,3,3,3));
        $pdf->Row(
            array(
            $no,$item->nama_kriteria,$item->nama_kriteria_detail,$item->nilai,$item->bobot_kriteria,
          ));
        }
        $pdf->Output();
    }
   
   public function laporan_nilai()
    { 
      $this->load->view('template/css/css_2');
      $this->load->view('template/css/css_table');
      $this->load->view('template/menu_bar/top_bar_2');
      $this->load->view('template/menu_bar/side_bar_2');

      $get_kk = $this->db->select("A.no_kk")
                           ->from("Tb_KK A")
                           ->get();
        $data["no_kk"] = $get_kk;
      $this->load->view("template/js/js_2");
      $this->load->view("f_nilai_detail_new",$data);

    }

    function view_laporan_nilai()
    {
        $tgl = $this->input->get("tanggal_src");
        $no_kk = $this->input->get("no_kk_src");

        $this->load->library('tc_pdf_a');
        $pdf = new tc_pdf_a('L', 'cm', array(19,35), true, 'UTF-8', false);
        $pdf->SetTitle('Laporan Penilaian');
        // $pdf->SetHeaderMargin(1);
        $pdf->SetTopMargin(0.5);
        $pdf->setFooterMargin(1);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Author');
        $pdf->SetDisplayMode('real', 'default');
        // $pdf->AddFont('CourierNewPSMT','','CourierNewPSMT.php');
        $pdf->AddPage();
        // $i=0;
        // $pdf->Image('logo.png',10,6,30);
        // $pdf->Image(base_url('assets/img/a.jpg'),1,1,2,2);
        $pdf->Cell(0,0.6,'Program Keluarga Harapan',0,1,'C');
        $pdf->Cell(0,0.6,'Ds. Sukatani Kec. Cisoka - Kabupaten Tangerang - Banten',0,1,'C');
        // $pdf->Cell(0,0.6,'NO TLP',0,0,'C');
        $pdf->Cell(0,0.6,'Hal : '.$pdf->PageNo(),0,1,'R');
        // $pdf->Line(1,3.1,20.5,3.1);
        // $pdf->SetLineWidth(0.1);      
        // $pdf->Line(1,3.2,20.5,3.2);   
        // $pdf->SetLineWidth(0);
        $no_kks     = $no_kk;
        $tanggal    = (empty($tgl)) ? date('Y').'-01-01' : $tgl;

        $pdf->cell(0,0.5,'____________________________________________________________________________________________________________________________________________________',0,1,'C');
        $pdf->Cell(0,0.6,'Laporan Hasil Penilaian Periode : '.$tanggal,0,0,'C');
        $pdf->ln(1);

        $pdf->SetFont('Helvetica','',8);
        $html = '<table id="example" class="table table-responsive table-striped table-bordered" border="1">
                    <thead>
                        <tr>
                            <th style="width:20px;"> No</th>
                            <th> Kode Penilaian</th>
                            <th> Periode</th>
                            <th> No KK</th>
                            <th> Kepala Keluarga</th>';
                            $head = $this->db->select("A.kode_kriteria,A.nama_kriteria,replace(A.nama_kriteria,' ','_') as nama_kriteria_show")
                                             ->from("kriteria A")
                                             ->join("Penilaian_detail B","B.kode_kriteria=A.kode_kriteria")
                                             ->join("Penilaian C","C.kode_penilaian=B.kode_penilaian")
                                             ->where("C.no_kk like '%$no_kk%'")
                                             ->group_by("A.kode_kriteria")
                                             ->get();
                            foreach ($head->result() as $list_head) {
                               $html.='<th> '.$list_head->nama_kriteria.'</th>';
                            }
                            $html.='<th> Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $list = $this->db->query("CALL view_nilai_akhir2 ('$no_kks','$tanggal')");
                    $no=0;
                    foreach ($list->result_array() as $data) { 
                        $no++;
                        $periode = substr($data['tgl_penilaian'], 5,9);

                        $tahun = date('Y',strtotime($data['tgl_penilaian']));
                        if($periode==="01-01")
                        {
                          $periodes = "PERIODE 1 ".$tahun;
                        }
                        else if($periode==="07-01")
                        {
                          $periodes = "PERIODE 2 ".$tahun;
                        }
                        else
                        {
                          $periodes = $data['tgl_penilaian'];
                        }
                    
                    $html.='<tr>
                        <td style="width:20px;"> '.$no.'</td>
                        <td> '.$data['kode_penilaian'].'</td>
                        <td> '.$periodes.'</td>
                        <td> '.$data['no_kk'].'</td>
                        <td> '.$data['nama_keluarga'].'</td>';

                        foreach ($head->result_array() as $heads) {
                             $html.='<td> '.($data[$heads['nama_kriteria_show']]).'</td>';
                        }
                        $html.='<td> &nbsp;'.$data['total'].'</td>';
                    $html.='</tr>';
                }
                $html.='</tbody>
                </table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('penilaian.pdf', 'I');

       
    }
   
	
	
}
