
<div class="table-responsive">
	<button type="button" class="btn btn-danger btn-sm" onclick="add_row(this)">+ Tambah</button>
	<input type="hidden" name="sisa" id="sisa" value="<?php echo $sisa ?>">
	<input type="hidden" name="status" id="status" value="<?php echo $status ?>">
	<table class="table table-borderd table-striped table-responsive" style="width: 450px;">
		<thead>
			<tr>
				<td style="width:20px;"></td>
				<td style="width:20px;"></td>
				<th style="width:300px;">Nama Detail</th>
				<th style="width:100px;">Nilai</th>
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
							<input type="text" name="nama_detail[]" id="nama_detail_1" onkeyup="cek_detail(1);" class="nama_detail_1" maxlength="50" style="width:100%;">
						</div>
					</td>
					<td>
						<div class="form-line">
							<input type="text" name="nilai[]" id="nilai_1" class="nilai_1" maxlength="4" onkeyup="hanya_angka(this);cek_nilai(1)" style="width: 100%;">
						<div class="form-line">
					</td>
				</tr>
			</tbody>
		<?php } 
		else if($status==="edit"){ 
			$head = $list_kriteria->row();?>
			<input type="hidden" name="kode_kriterias" id="kode_kriterias" value="<?php echo $head->kode_kriteria ?>">
			<input type="hidden" name="nama_kriterias" id="nama_kriterias" value="<?php echo $head->nama_kriteria ?>">
			<input type="hidden" name="bobots" id="bobots" value="<?php echo $head->bobot_kriteria ?>">
			<tbody id="row_number">
				<?php 
				$no=0;
				foreach ($list_kriteria->result() as $list) {
				$no++; ?>
				<tr>
					<td></td>
					<td><?php echo $no ?></td>
					<td>
						<div class="form-line">
							<input type="hidden" name="id_kriteria_detail[]" value="<?php echo $list->id_kriteria_detail ?>">
							<input type="text" name="nama_detail[]" id="nama_detail_<?php echo $no ?>" class="nama_detail_<?php echo $no ?>" maxlength="50" style="width:100%;" value="<?php echo $list->Nama_Kriteria_detail ?>" onkeyup="cek_detail('<?php echo $no ?>');">
						</div>
					</td>
					<td>
						<div class="form-line">
							<input type="text" name="nilai[]" id="nilai_<?php echo $no ?>" class="nilai_<?php echo $no ?>" maxlength="4" style="width:100%;" onkeyup="hanya_angka(this);cek_nilai('<?php echo $no ?>')"  value="<?php echo $list->nilai ?>" style="width:100%;">
						<div class="form-line">
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
		i 	 = $("#row_number tr").length+1//+parseInt(nomor);
		console.log(i)
		rows = '<tr>'+
					'<td><button type="button" id="hapus_row" class="btn btn-danger btn-xs hapus_row" >Hapus</button></td>'+
					'<td>'+i+'</td>'+
					'<td>'+
						'<div class="form-line">'+
							'<input type="text" name="nama_detail[]" id="nama_detail_'+i+'" class="nama_detail_'+i+'" maxlength="50" style="width:100%;" onkeyup="cek_detail('+i+')">'+
						'</div>'+
					'</td>'+
					'<td>'+
						'<div class="form-line">'+
							'<input type="text" name="nilai[]" id="nilai_'+i+'" class="nilai_'+i+'" maxlength="4" onkeyup="hanya_angka(this);cek_nilai('+i+');" style="width:60px;">'+
						'<div class="form-line">'+
					'</td>'+
				'</tr>';
		$("#row_number").append(rows);
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
	      endDate: '+0d',
	      autoclose: true,
	      todayHighlight: true,
	      toggleActive: true,
	      
	    });
  	});


  	function cek_detail(baris='')
    {
    	nama = $("#nama_detail_"+baris).val();
		$.ajax({
			url 	: "<?php echo base_url('kriteria/c_kriteria/get_duplikat')?>",
			type 	: "POST",
			data 	: $("#form_input").serialize(),
			success : function(data)
			{
				json = JSON.parse(data);
				if(json.status==="ada")
				{
					error_msg('Duplikat Nama Detail Kriteria '+nama);
					$("#nama_detail_"+baris).val("");
				}
			}
		});
    }


  	function cek_nilai(baris='')
    {

    	nilai = $("#nilai_"+baris).val();
    	console.log("nilai :"+nilai)
		$.ajax({
			url 	: "<?php echo base_url('kriteria/c_kriteria/get_nilai')?>/"+nilai,
			type 	: "POST",
			data 	: $("#form_input").serialize(),
			success : function(data)
			{
				json = JSON.parse(data);
				if(json.status==="ada")
				{
					error_msg('Duplikat Nilai '+nilai);
					$("#nilai_"+baris).val("");
				}
				else if(json.status==="l_100")
				{
					error_msg('Nilai Tidak Boleh Lebih Dari 100 ');
					$("#nilai_"+baris).val("");
				}
			}
		});
    }

   

	function validasi_data()
	{
		kriteria = $("#nama_kriteria").val();
		if(kriteria=="")
		{
			error_msg('Nama Kriteria Tidak Boleh Kosong');
		}
		else
		{
			n_krt="ok";
		}

		bobot = $("#bobot").val();
		if(bobot=="")
		{
			error_msg('Bobot Tidak Boleh Kosong');
		}
		else
		{
			n_bobot="ok";
		}

		v_nama = document.getElementsByName('nama_detail[]');
	    for (i=0; i<v_nama.length; i++)
	      {
	       if (v_nama[i].value == "")
	        {
	         error_msg("Nama Detail Tidak Boleh Kosong");
	         return false;
	        }
	        else
	        {
	          var nm_sts="ok";
	        }
	    }

	    v_nilai = document.getElementsByName('nilai[]');
	    for (i=0; i<v_nilai.length; i++)
	      {
	       if (v_nilai[i].value == "")
	        {
	         error_msg("Nilai Tidak Boleh Kosong");
	         return false;
	        }
	        else
	        {
	          var nl_sts="ok";
	        }
	    }

	    if(n_krt==="ok" && n_bobot==="ok" && nm_sts==="ok" && nl_sts==="ok")
	    {
	    	simpan_data();
	    }

	}

	function simpan_data()
	{
		status = "<?php echo $status ?>";
		if(status==="baru")
		{
			path = "<?php echo base_url('kriteria/c_kriteria/simpan_data')?>";
		}
		else if(status==="edit")
		{
			path = "<?php echo base_url('kriteria/c_kriteria/update_data')?>";
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
					table.ajax.reload(); 
					$("#modal-default").modal('hide');
            		$("#form_input")[0].reset();
					info_msg("Data Berhasil Disimpan");
				}
				else
				{
					pesan = "Gagal!! Hubungi Tim IT!!";
					error_msg(pesan);
				}
			}
		});
	}
</script>