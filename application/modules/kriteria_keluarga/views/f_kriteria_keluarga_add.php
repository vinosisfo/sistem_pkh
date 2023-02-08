<?php 
if($status==="baru"){ ?>
<div class="table-responsive">
	<h4>List Data Keluarga</h4>
	<table class="table table-responsive table-bordered table-striped">
		<tr>
			<th>NIK</th>
			<th>Nama Keluarga</th>
			<th>Pekerjaan</th>
			<th>Status</th>
		</tr>
		<?php 
		foreach ($list_keluarga->result() as $keluarga) { 
			?>
			<tr>
				<td><?php echo $keluarga->nik ?></td>
				<td><?php echo $keluarga->nama_keluarga ?></td>
				<td><?php echo $keluarga->pekerjaan ?></td>
				<td><?php echo $keluarga->status ?></td>
			</tr>
		<?php } ?>
	</table>
	<h4>List Kriteria</h4>
	<table class="table table-responsive table-bordered table-striped">
		<tr>
			<th>No</th>
			<th>Nama Kriteria</th>
			<th>Nama Detail</th>
			<th>Nilai</th>
		</tr>
		<?php 
		$no=0;
		foreach ($kriteria as $kriterias) {
		$no++;
		?>
			<tr>
				<td><?php echo $no ?></td>
				<td>
					<input type="hidden" name="kode_kriteria[]" id="kode_kriteria_<?php echo $no ?>" value="<?php echo $kriterias->kode_kriteria ?>">
					<?php echo @$kriterias->nama_kriteria ?>
				</td>
				<td>
					<select name="id_detail[]" id="id_detail_<?php echo $no ?>" onchange="get_nilai('<?php echo $no ?>')">
						<option value="">PILIH</option>
						<?php 
						foreach ($kriterias->datas as $detail) { ?>
							<option value="<?php echo $detail->id_kriteria_detail ?>"><?php echo $detail->nama_kriteria_detail ?></option>
						<?php } ?>
					</select>
				</td>
				<td>
					<input type="hidden" name="nilai[]" id="nilai_<?php echo $no ?>">
					<span id="nilai_s_<?php echo $no ?>"></span>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>
<?php } 
else if ($status==="edit"){?>
	<input type="hidden" name="no_kks" id="no_kk_edit" value="<?php echo $no_kk ?>">
	<div class="table-responsive">
		<h4>List Data Keluarga</h4>
	<table class="table table-responsive table-bordered table-striped">
		<tr>
			<th>NIK</th>
			<th>Nama Keluarga</th>
			<th>Pekerjaan</th>
			<th>Status</th>
		</tr>
		<?php 
		foreach ($list_keluarga->result() as $keluarga) { 
			?>
			<tr>
				<td><?php echo $keluarga->nik ?></td>
				<td><?php echo $keluarga->nama_keluarga ?></td>
				<td><?php echo $keluarga->pekerjaan ?></td>
				<td><?php echo $keluarga->status ?></td>
			</tr>
		<?php } ?>
	</table>
	<h4>List Kriteria</h4>
	<table class="table table-responsive table-bordered table-striped">
		<tr>
			<th>No</th>
			<th>Nama Kriteria</th>
			<th>Nama Detail</th>
			<th>Nilai</th>
		</tr>
		<?php 
		$no=0;
		foreach ($kriteria_kk as $kriterias) {
		$no++;
		?>
			<tr>
				<td><?php echo $no ?></td>
				<td>
					<input type="hidden" name="kode_kriteria[]" id="kode_kriteria_<?php echo $no ?>" value="<?php echo $kriterias->kode_kriteria ?>">
					<?php echo @$kriterias->nama_kriteria ?>
				</td>
				<td>
					<select name="id_detail[]" id="id_detail_<?php echo $no ?>" onchange="get_nilai('<?php echo $no ?>')">
						<!-- <option value="">PILIH</option> -->
						<?php 
						foreach ($kriterias->list_kk as $detail) { ?>
							<option value="<?php echo $detail->id_kriteria_detail ?>"><?php echo $detail->nama_kriteria_detail ?></option>
							<option value="">--PILIH--</option>
						<?php } 
						$data_list =  $this->db->from("kriteria_detail A")
		                                       ->where("A.kode_kriteria",$kriterias->kode_kriteria)
		                                       ->get();
		                foreach ($data_list->result() as $list_kk_list) { ?>
		                	<option value="<?php echo $list_kk_list->id_kriteria_detail ?>"><?php echo $list_kk_list->nama_kriteria_detail ?></option>
		                <?php } ?>


					</select>
				</td>
				<td>
					<?php 
						foreach ($kriterias->list_kk as $nilai) { ?>
					<input type="hidden" name="nilai[]" id="nilai_<?php echo $no ?>" value="<?php echo $nilai->nilai ?>">
					<span id="nilai_s_<?php echo $no ?>"><?php echo $nilai->nilai ?></span>
					<?php } ?>	
				</td>
			</tr>
		<?php } ?>
	</table>
	</div>
<?php } ?>

<script type="text/javascript">
	function get_nilai(baris='') {
		id_detail = $('#id_detail_'+baris).val();
		$.ajax({
			url 	: "<?php echo base_url('kriteria_keluarga/c_kriteria_keluarga/get_nilai')?>",
			type 	: "POST",
			data 	: {id_detail : id_detail},
			success : function(data)
			{
				json = JSON.parse(data);
				$("#nilai_"+baris).val(json.nilai);
				$("#nilai_s_"+baris).html(json.nilai);
			}
		});
	}
</script>