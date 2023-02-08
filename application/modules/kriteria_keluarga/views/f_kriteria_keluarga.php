<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Kriteria Keluarga Penerima Dana Bantuan Program Keluarga Harapan
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Data Kriteria Keluarga</button>
                        <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Add Kriteria Keluarga</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="Kriteria" style="table-layout: auto;">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>No KK</th>
                                  <th>Kepala Keluarga</th>
                                  <th>Nama Kriteria</th>
                                  <th>Bobot Kriteria</th>
                                  <th>Nama Detail</th>
                                  <th>Nilai</th>
                                  <th>Edit</th>
                                  <th>Hapus</th>
                                </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-default"> 
  <div class="modal-dialog" style="width:850px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Data Kriteria Keluarga</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>

      <form id="form_input" autocomplete="off">
        <div class="modal-body">
          <div class="form-group">
            <label>Nomor KK</label><br>
            <div class="form-line">
              <input type="hidden" name="no_kks" id="no_kks">

              <select name="no_kk" id="no_kk" class="no_kk select2" style="width:50%;" required>
                <option value="">PILIH</option>
                <?php
                foreach ($no_kk->result() as $data) { ?>
                  <option value="<?php echo $data->no_kk ?>"><?php echo $data->no_kk ?></option>
                <?php } ?>

              </select>
            </div>
          </div>
          <div class="form-group">
            <span id="form_detail"></span>
          </div>
        </div>
        <div class="modal-footer">
          <div class="form-group" style="text-align: left;">
            <button type="button" class="btn btn-primary btn-sm" id="btn_input" style="width: 150px;" onclick="validasi_data(this)">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript">

  

  var table;
  table = $('#Kriteria').DataTable({ 
    "processing"  : true, 
    "serverSide"  : true, 
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "order": [],     
    "ajax": {
      "url": "<?php echo site_url('Kriteria_keluarga/c_kriteria_keluarga/get_data')?>",
      "type": "POST",
      "data": function ( data ) {
              data.kode_kriteria  = $('#kode_kriteria_src').val();
              data.nama_kriteria  = $('#nama_kriteria_src').val();
              data.nama_detail    = $("#nama_detail_src").val();
              
          }
    },
    "columnDefs": [
      { 
        "targets": [ 0 ], 
        "orderable": false,
      },
    ],
  });

  $('#btn-filter').click(function(){ //button filter event click
      table.ajax.reload();  //just reload table
  });
  $('#btn-reset').click(function(){ //button reset event click
      $('#form-filter')[0].reset();
      table.ajax.reload();  //just reload table
  });

  // $("#user_filter").css("display","none");

  function add_data()
  {
    $("#form_input")[0].reset();
    $("#modal-default").modal('show');
  }

  $("#no_kk").change(function(){
    no_kk   = $("#no_kk").val();
    status  = $("#status").val();

    $.ajax({
      url : "<?php echo base_url('Kriteria_keluarga/c_kriteria_keluarga/get_form')?>",
      type : "POST",
      data : {no_kk : no_kk,status:'baru'},
      success : function(data)
      {
        $("#form_detail").html(data);
      }
    });
  });

  function validasi_data()
  {
    no_kk = $("#no_kk").val();
    if(no_kk=="")
    {
      pesan = "No KK Harus Diisi";
      error_msg(pesan);
    }
    else
    {
      no_kk_sts="ok";
    }
    v_id = document.getElementsByName('id_detail[]');
      for (i=0; i<v_id.length; i++)
        {
         if (v_id[i].value == "")
          {
           pesan = "Nama Detail Harus Dipilih";
           error_msg(pesan);
           return false;
          }
          else
          {
            var id_sts="ok";
          }
      }

      if(no_kk_sts==="ok" && id_sts==="ok")
      {
        simpan_data();
      }

  }

  function simpan_data()
  {

    no_kks = $("#no_kks").val();
    if(no_kks!="")
    {
      path = "<?php echo base_url('Kriteria_keluarga/c_kriteria_keluarga/update_data')?>";
    }
    else
    {
      path = "<?php echo base_url('Kriteria_keluarga/c_kriteria_keluarga/simpan_data')?>";
    }
    $.ajax({
      url   : path,
      type  : "POST",
      data  : $("#form_input").serialize(),
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

  function edit_data(kode='')
  {
    $("#form_input")[0].reset();
    $.ajax({
      url   : "<?php echo base_url('Kriteria_keluarga/c_kriteria_keluarga/get_form')?>",
      type  : "POST",
      data  : {no_kk : kode,status:"edit",},
      success : function(data)
      {
        console.log(kode);
        $("#no_kk").html('<option value="'+kode+'">'+kode+'</option>');
        $("#no_kks").val(kode);
        $("#form_detail").html(data);
        $("#modal-default").modal('show');
      } 
    });
  }

  function hapus_data(kode='')
  {
    var confirmText = "Data Akan Dihapus ?";
       if(confirm(confirmText)) {
            $.ajax({
                type:"POST",
                url:"<?php echo base_url()?>Kriteria_keluarga/c_kriteria_keluarga/hapus_data/"+kode,
                success:function (status) {
                  json = status,
                  obj = JSON.parse(json);
                  hapus_msg('Data Di Hapus!!');
                  table.ajax.reload();
                },
            });
    }
    return false;
  }

</script>