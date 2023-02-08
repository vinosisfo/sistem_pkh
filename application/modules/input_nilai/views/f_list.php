<div class="table-responsive">
	<?php 
		$no_kks 	= $no_kk;
		$tanggal 	= (empty($tgl)) ? date('Y').'-01-01' : $tgl;
	?>
	<table id="example" class="table table-responsive table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Kode Penilaian</th>
				<th>Periode</th>
				<th>No KK</th>
				<th>Kepala Keluarga</th>
				<?php 
				$head = $this->db->select("A.kode_kriteria,A.nama_kriteria,replace(A.nama_kriteria,' ','_') as nama_kriteria_show")
								 ->from("kriteria A")
								 ->join("Penilaian_detail B","B.kode_kriteria=A.kode_kriteria")
								 ->join("Penilaian C","C.kode_penilaian=B.kode_penilaian")
								 ->where("C.no_kk like '%$no_kk%'")
								 ->group_by("A.kode_kriteria")
								 ->get();
				foreach ($head->result() as $list_head) { ?>
					<th><?php echo $list_head->nama_kriteria ?></th>
				<?php } ?>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$list = $this->db->query("CALL view_nilai ('$no_kks','$tanggal')");
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
			?>
		
		<tr>
			<td><?php echo $no ?></td>
			<td><?php echo $data['kode_penilaian'] ?></td>
			<td><?php echo $periodes ?></td>
			<td><?php echo $data['no_kk'] ?></td>
			<td><?php echo $data['nama_keluarga'] ?></td>

			<?php foreach ($head->result_array() as $heads) { ?>
				 <td><?php echo $data[$heads['nama_kriteria_show']] ?></td>
			<?php } ?>
			<td><?php echo $data['total'] ?></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<script type="text/javascript">
	 $('#example').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": true,
        });
</script>