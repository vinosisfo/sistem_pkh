<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Approve Nilai Penerima Dana Bantuan Program Keluarga Harapan
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">NILAI AKHIR</button>
                        <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Approve Nilai</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                          <form id="list_data">
                          <table class="table table-responsive table-striped">
                            <tr>
                              <td>Peiode </td>
                              <td>:</td>
                              <td>
                                <select name="periode_src" id="periode_src" style="width : 80%;">
                                  <option value="">PILIH</option>
                                  <option value="1">PERIODE 1 <?php echo date('Y') ?></option>
                                  <option value="2">PERIODE 2 <?php echo date('Y') ?></option>
                                </select>
                                <input type="hidden" name="tanggal_src" id="tanggal_src" class="tanggal_src" style="width: 30%;">
                              </td>
                              <td>No KK</td>
                              <td>:</td>
                              <td>
                                <select name="no_kk_src" id="no_kk_src" class="select2">
                                   <option value="">PILIH</option>
                                  <?php
                                  foreach ($no_kk->result() as $data) { ?>
                                    <option value="<?php echo $data->no_kk ?>"><?php echo $data->no_kk ?></option>
                                  <?php } ?>
                                </select>
                              </td>
                              <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="get_data(this)"> CARI</button>
                              </td>
                            </tr>
                          </table>
                          <span id="table_list"></span>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-default"> 
  <div class="modal-dialog" style="width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Approve Nilai</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>

      <form id="form_input" autocomplete="off">
        <div class="modal-body">
          <div class="form-group">
            <label>Tanggal</label><br>
            <!-- <div class="form-line"> -->
              <select name="periode" id="periode" style="width : 30%;">
                <option value="">PILIH</option>
                <option value="1">PERIODE 1 <?php echo date('Y') ?></option>
                <option value="2">PERIODE 2 <?php echo date('Y') ?></option>
              </select>
              <input type="hidden" name="tanggal" id="tanggal" class="tanggal" style="width: 30%;">
            <!-- </div> -->
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

  function get_data()
  {
    no_kk   = $('#no_kk_src').val();
    tanggal = $('#tanggal_src').val();

    $.ajax({
      url   : "<?php echo base_url('approve_nilai/c_approve_nilai/get_data')?>",
      type  : "POST",
      data  : $("#list_data").serialize(),
      success : function(data)
      {
        $("#table_list").html(data);
      }
    });
  }

  get_data();

  function add_data()
  {
    $("#form_input")[0].reset();
    $("#modal-default").modal('show');
  }

  $("#no_kk").change(function(){
    no_kk   = $("#no_kk").val();
    status  = $("#status").val();

    $.ajax({
      url : "<?php echo base_url('approve_nilai/c_approve_nilai/get_form')?>",
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

    tanggal = $("#tanggal").val();
    console.log(tanggal);
    if(tanggal=="")
    {
      error_msg('Periode Tidak Boleh Kosong');
      tgl_sts="";
    }
    else
    {
      tgl_sts="ok";
    }

      if(tgl_sts==="ok")
      {
        simpan_data();
      }

  }

  function simpan_data()
  {
    path = "<?php echo base_url('approve_nilai/c_approve_nilai/simpan_data')?>";
    $.ajax({
      url   : path,
      type  : "POST",
      data  : $("#form_input").serialize(),
      success : function(data)
      {
        json = JSON.parse(data);
        if(json.status==="ok")
        {
          pesan = "Approve Data Berhasil";
          get_data();
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


  $("#periode").change(function(){
    let periode = $("#periode").val();
    if(periode==1)
    {
      date = "<?php echo date("Y-01-01")?>";
      $('#tanggal').val(date);
    }
    else if(periode==2)
    {
      date = "<?php echo date("Y-07-01")?>";
      $('#tanggal').val(date);
    }
    else if(periode=="")
    {
     $('#tanggal').val(''); 
    }
  });

   $("#periode_src").change(function(){
    let periode = $("#periode_src").val();
    if(periode==1)
    {
      date = "<?php echo date("Y-01-01")?>";
      $('#tanggal_src').val(date);
    }
    else if(periode==2)
    {
      date = "<?php echo date("Y-07-01")?>";
      $('#tanggal_src').val(date);
    }
    else if(periode=="")
    {
     $('#tanggal_src').val(''); 
    }
  })

</script>