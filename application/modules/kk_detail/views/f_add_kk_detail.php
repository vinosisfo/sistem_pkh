<!-- <form id="form_input" autocomplete="off"> -->
<table class="table table-borderd" style="width:500px;">
	<thead>
		<td>No KK</td>
		<td>:</td>
		<td>
			<input type="hidden" name="status" value="<?php echo $status ?>">
			<select name="no_kk_detail" id="no_kk" style="width: 200px;" class="select2">
				<?php if($status==="baru"){ ?>
				<option value="">--PILIH--</option>
				<?php 
				foreach ($no_kk->result() as $data) { ?>
					<option value="<?php echo $data->no_kk ?>"><?php echo $data->no_kk ?></option>
				<?php } }
				else { ?>
					<option value="<?php echo $no_kks ?>"><?php echo $no_kks ?></option>
				<?php } ?>

			</select>
		</td>
	</thead>
</table>
<div class="table-responsive">
<button type="button" class="btn btn-danger btn-sm" onclick="add_row(this)">+ Tambah</button>
<table class="table table-borderd table-striped table-responsive" style="width: 800px;">
	<thead>
		<tr>
			<td style="width:20px;"></td>
			<td style="width:20px;"></td>
			<th style="width:100px;">NIK</th>
			<th style="width:150px;">Nama</th>
			<th style="width:80px;">Tgl Lahir</th>
			<th style="width:100px;">Pekerjaan</th>
			<th style="width:100px;">Status</th>
		</tr>
	</thead>
	<?php
	if($status==="baru"){ 
		$no=1;?>
		<tbody id="row_number">
			<tr>
				<td></td>
				<td>1</td>
				<td>
					<div class="form-line">
						<input type="text" name="nik[]" id="nik_1" class="nik_1" maxlength="16" style="width:100%;" onkeypress="return hanyaAngka(event)"  onkeyup="cek_niks(1);">
					</div>
				</td>
				<td>
					<div class="form-line">
						<input type="text" name="nama[]" id="nama_1" class="nama_1" maxlength="50" minlength="1" style="width:100%;" onkeyup="cek_huruf(1)" pattern="[a-zA-Z . , - ]{1,}">
					<div class="form-line">
				</td>
				<td>
					<input type="text" name="tgl_lahir[]" id="tgl_lahir_1" class="tanggal" value="<?php echo date('Y-m-d')?>" style="width:100%;">
				</td>
				<td>
					<select name="pekerjaan[]" id="pekerjaan" class="pekerjaan" style="width:100%;">
						<option value="-">-</option>
						<option value="WIRA SWASTA">WIRA SWASTA</option>
						<option value="PNS">PNS</option>
						<option value="KARYAWAN">KARYAWAN</option>
						<option value="PETANI">PETANI</option>
					</select>
				</td>
				<td>
					<div class="form-line">
						<select name="status[]" id="status_1" class="status_1" style="width:100%;">
							<option value="">PILIH</option>
							<option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
							<option value="ISTRI">ISTRI</option>
							<option value="ANAK">ANAK</option>
						</select>
					</div>
				</td>
			</tr>
		</tbody>
	<?php } 
	else if($status==="edit"){ ?>
		<tbody id="row_number">
			<?php 
			$no=0;
			foreach ($list_kk->result() as $list) {
			$no++; ?>
			<tr>
				<td></td>
				<td><?php echo $no ?></td>
				<td>
					<div class="form-line">
						<input type="hidden" name="id_kk_detail[]" value="<?php echo $list->id_kk_detail ?>">
						<input type="text" name="nik[]" id="nik_<?php echo $no ?>" class="nik_<?php echo $no ?>" maxlength="16" style="width:100%;" onkeypress="return hanyaAngka(event)"  onkeyup="cek_niks('<?php echo $no ?>');" value="<?php echo $list->nik ?>">
					</div>
				</td>
				<td>
					<div class="form-line">
						<input type="text" name="nama[]" id="nama_<?php echo $no ?>" class="nama_<?php echo $no ?>" maxlength="50" minlength="1" style="width:100%;" onkeyup="cek_huruf('<?php echo $no ?>')" pattern="[a-zA-Z . , - ]{1,}" value="<?php echo $list->nama_keluarga ?>">
					<div class="form-line">
				</td>
				<td>
					<input type="text" name="tgl_lahir[]" id="tgl_lahir_<?php echo $no ?>" class="tanggal" value="<?php echo $list->Tgl_Lahir ?>" style="width:100%;">
				</td>
				<td>
					<select name="pekerjaan[]" id="pekerjaan_<?php echo $no ?>" class="pekerjaan" style="width:100%;">
						<option value="<?php echo $list->Pekerjaan ?>"><?php echo $list->Pekerjaan ?></option>
						<option value="-">-</option>
						<option value="WIRA SWASTA">WIRA SWASTA</option>
						<option value="PNS">PNS</option>
						<option value="KARYAWAN">KARYAWAN</option>
						<option value="PETANI">PETANI</option>
						<option value="PELAJAR">PELAJAR</option>
					</select>
				</td>
				<td>
					<div class="form-line">
						<select name="status[]" id="status_<?php echo $no ?>" class="status_<?php echo $no ?>" style="width:100%;">
							<option value="<?php echo $list->status ?>"><?php echo $list->status ?></option>
							<option value="">PILIH</option>
							<option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
							<option value="ISTRI">ISTRI</option>
							<option value="ANAK">ANAK</option>
						</select>
					</div>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	<?php } ?>
</table>
</div>


<script type="text/javascript">
	function add_row()
	{
		nomor = "<?php echo $no ?>";
		i 	 = $("#row_number tr").length+parseInt(nomor);
		rows = '<tr>'+
					'<td><button type="button" id="hapus_row" class="btn btn-danger btn-xs hapus_row" >Hapus</button></td>'+
					'<td>'+i+'</td>'+
					'<td>'+
						'<div class="form-line">'+
							'<input type="text" name="nik[]" id="nik_'+i+'" class="nik_'+i+'" maxlength="16" minlength="15" onkeypress="return hanyaAngka(event);" onkeyup="cek_niks('+i+')">'+
						'</div>'+
					'</td>'+
					'<td>'+
						'<div class="form-line">'+
							'<input type="text" name="nama[]" id="nama_'+i+'" class="nama_'+i+'" maxlength="50" onkeyup="cek_huruf('+i+')" minlength="1">'+
						'<div class="form-line">'+
					'</td>'+
					'<td>'+
						'<input type="text" name="tgl_lahir[]" id="tgl_lahir_'+i+'" class="tanggal" value="<?php echo date('Y-m-d')?>">'+
					'</td>'+
					'<td>'+
						'<select name="pekerjaan[]" id="pekerjaan_'+i+'" class="pekerjaan">'+
							'<option value="-">-</option>'+
							'<option value="WIRA SWASTA">WIRA SWASTA</option>'+
							'<option value="PNS">PNS</option>'+
							'<option value="KARYAWAN">KARYAWAN</option>'+
							'<option value="PETANI">PETANI</option>'+
						'</select>'+
					'</td>'+
					'<td>'+
						'<div class="form-line">'+
							'<select name="status[]" id="status_'+i+'" class="status_'+i+'">'+
								'<option value="">PILIH</option>'+
								'<option value="KEPALA KELUARGA">KEPALA KELUARGA</option>'+
								'<option value="ISTRI">ISTRI</option>'+
								'<option value="ANAK">ANAK</option>'+
							'</select>'+
						'</div>'+
					'</td>'+
				'</tr>';
		$("#row_number").append(rows);
		$(".tanggal").datepicker({
	      autoclose: true,
	      format: 'yyyy-mm-dd',
	      changeMonth: true,
	      changeYear: true,
	      orientation: "top",
	      // endDate: '+0d',
	      autoclose: true,
	      todayHighlight: true,
	      toggleActive: true,
	      
	    });
	}
	$("#row_number").on('click', '#hapus_row', function(){
      $(this).parent().parent().remove();
  	});

	$(function () {
	  	$(".select2").select2({
	      allowClear:true,
	      placeholder: 'Pilih',
	      required : true
	    });

	    $(".tanggal").datepicker({
	      autoclose: true,
	      format: 'yyyy-mm-dd',
	      changeMonth: true,
	      changeYear: true,
	      orientation: "top",
	      // endDate: '+0d',
	      autoclose: true,
	      todayHighlight: true,
	      toggleActive: true,
	      
	    });

	 //    $('.tanggal').datepicker({
		//     format: 'mm/dd/yyyy',
		//     startDate: '-3d'
		// });

  	});


  	function cek_niks(baris='')
    {
    	nik = $("#nik_"+baris).val();
    	jumlah = nik.length;
    	console.log(jumlah);
    	if(jumlah>15)
    	{
    		$.ajax({
    			url 	: "<?php echo base_url('kk_detail/c_kk_detail/get_duplikat')?>",
    			type 	: "POST",
    			data 	: $("#form_input").serialize(),
    			success : function(data)
    			{
    				json = JSON.parse(data);
    				if(json.status==="ada")
    				{
    					pesan = 'Nik Duplikat Pada Baris '+baris;
    					error_msg(pesan);
    					$("#nik_"+baris).val("");
    				}
    			}
    		});
    	}
    }

    function cek_huruf(baris)
	{
		isi = $("#nama_"+baris).val();
	    var letters = /^[a-zA-Z . , - ]+$/;
	    if(isi.match(letters))
	    {
	    	return true;
	    }
	    else
	    {
	    	pesan = 'Nama Hanya Bisa Diisi Huruf';
	    	error_msg(pesan);
	    	$("#nama_"+baris).val("");
	    }
	}

	function validasi_data()
	{
		no_kk = $("#no_kk_detail").val();
		if(no_kk=="")
		{
			pesan = "No KK Harus Diisi";
			error_msg(pesan);
		}
		else
		{
			no_kk_sts="ok";
		}
		v_nik = document.getElementsByName('nik[]');
	    for (i=0; i<v_nik.length; i++)
	      {
	       if (v_nik[i].value == "")
	        {
	         pesan = "Nik Harus Diisi";
	         error_msg(pesan);
	         return false;
	        }
	        else
	        {
	          var nik_sts="ok";
	        }
	    }

	    if(no_kk_sts==="ok" && nik_sts==="ok")
	    {
	    	simpan_data();
	    }

	}

	function simpan_data()
	{
		status = "<?php echo $status ?>";
		if(status==="baru")
		{
			path = "<?php echo base_url('kk_detail/c_kk_detail/simpan_data')?>";
		}
		else if(status==="edit")
		{
			path = "<?php echo base_url('kk_detail/c_kk_detail/update_data')?>";
		}
		$.ajax({
			url 	: path,
			type 	: "POST",
			data 	: $("#form_input").serialize(),
			success : function(data)
			{
				json = JSON.parse(data);
				if(json.status==="ok")
				{
					pesan = "Data Berhasil Disimpan";
					table.ajax.reload(); 
					$("#modal-default").modal('hide');
            		$("#form_input")[0].reset();
					info_msg(pesan);
				}
				else
				{
					pesan = "Gagal!! Hubungi Tim IT!!";
					error_msg(pesan);
				}
			}
		});
	}


  	function error_msg(row){
	   iziToast.error({
	      title: 'Error !!',
	      message: row,
	      position: 'topCenter'
	   });
	}
</script>