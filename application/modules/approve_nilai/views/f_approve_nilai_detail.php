<?php 
if($status==="baru"){ ?>
<div class="table-responsive">
	<h4>List Data Keluarga</h4>
	<table class="table table-responsive table-bordered table-striped" style="table-layout: auto;">
		<?php
		$keluarga = $list_keluarga->row();
		?>
		<tr>
			<td style="width:150px;">Nama Kepla Keluarga</td>
			<td style="width: 1px;">:</td>
			<td style="width: 450px;"><?php echo $keluarga->nama_keluarga ?></td>
		</tr>

		<tr>
			<td>Alamat</td>
			<td>:</td>
			<td><?php echo $keluarga->alamat ?></td>
		</tr>
	</table>

	<h4>List Kriteria</h4>
	<table class="table table-responsive table-bordered table-striped" style="table-layout: auto;">
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
					<input type="hidden" name="nama_kriteria[]" id="nama_kriteria_<?php echo $no ?>" value="<?php echo str_replace(' ', '_', $kriterias->nama_kriteria) ?>">
					<?php echo @$kriterias->nama_kriteria ?>
				</td>
				<td>
						<table>
							<?php 
							foreach ($kriterias->list_kk as $detail) { ?>
							<tr>
								<td><?php echo $detail->nama_kriteria_detail ?></td>
								<td><?php echo number_format($detail->nilai) ?></td>
							</tr>
						<?php } ?>
						</table>
				</td>
				<td>
					<?php 
					foreach ($kriterias->list_kk as $nilai) { ?>
					<input type="hidden" name="nilai[]" id="nilai_<?php echo $no ?>" value="<?php echo ($kriterias->bobot_kriteria*$nilai->nilai)/100; ?>">
					<!-- <span id="nilai_s_<?php //echo $no ?>"><?php //echo $nilai->nilai ?></span> -->
					<!-- <span><?php //echo $kriterias->bobot_kriteria ?></span> -->
					<span><?php echo ($kriterias->bobot_kriteria*$nilai->nilai)/100 ?></span>
					<?php } ?>	
				</td>
			</tr>
		<?php } ?>
	</table>
	** Note : Nilai Sudah Dikalikan Dengan Bobot Kriteria
</div>
<?php } ?>