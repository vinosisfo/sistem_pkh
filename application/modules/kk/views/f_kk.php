<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Mengelola Data Kartu Keluarga
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Data KK</button>
                        <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Add KK</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="kk" style="table-layout: auto;">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>No KK</th>
                                  <th>Alamat</th>
                                  <!-- <th>Kepala Keluarga</th> -->
                                  <th>Edit</th>
                                  <th>Hapus</th>
                                </tr>
                              </thead>
                              <!-- <tbody> -->
                              <!-- </tbody> -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-default"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Input Data KK</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>

      <form id="form_input" autocomplete="off">
        <div class="modal-body">
          <div class="form-group">
            <label>No KK</label><br>
            <div class="form-line">
              <input type="hidden" name="no_kks" id="no_kks">
              <input type="text" name="no_kk" id="no_kk" maxlength="16" required class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label>alamat</label><br>
            <div class="form-line">
              <textarea name="alamat" id="alamat" class="alamat form-control" maxlength="200"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="form-group" style="text-align: left;">
            <button type="button" class="btn btn-primary btn-sm" id="btn_input" style="width: 150px;" onclick="simpan_data(this)">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript">

  

  var table;
  table = $('#kk').DataTable({ 
    "processing"  : true, 
    "serverSide"  : true, 
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "order": [],     
    "ajax": {
      "url": "<?php echo site_url('kk/c_kk/get_data')?>",
      "type": "POST",
      "data": function ( data ) {
              data.no_kk = $('#no_kk').val();
              data.alamat    = $('#alamat').val();
              
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

  function simpan_data()
  {
    no_kks = $("#no_kks").val();
    no_kk   = $("#no_kk").val();
    alamat  = $("#alamat").val();
    if(no_kks!="")
    {
      var path = "<?php echo base_url('kk/c_kk/update_data')?>";
    }
    else
    {
      var path = "<?php echo base_url('kk/c_kk/simpan_data')?>";
    }
    if(no_kk=="" || alamat=="")
    {
      error_msg();
    }
    else
    {
      $.ajax({
        url : path,
        type : "POST",
        data : $("#form_input").serialize(),
        success: function(data)
        {
          json = JSON.parse(data);
          if(json.status==="ok")
          {
            msg_ok();
            $("#modal-default").modal('hide');
            $("#form_input")[0].reset();
            table.ajax.reload();

          }
          else
          {
            error_msg();
          }
        }
      });
    }
  }


  function error_msg(){
   iziToast.error({
      title: 'Error !!',
      message: 'No KK, Alamat , Tidak Boleh Kosong',
      position: 'topCenter'
    });
  }

  function msg_ok()
  {
    iziToast.success({
    title: 'Berhasil!',
    message: 'Data Berhasil Disimpan',
    position: 'topCenter'
  });
  }

  function msg_hapus()
  {
    iziToast.error({
    title: 'Berhasil!',
    message: 'Data Berhasil DiHapus',
    position: 'topCenter'
  });
  }

  function edit_data(kode='')
  {
    $.ajax({
      url   : "<?php echo base_url('kk/c_kk/get_edit')?>",
      type  : "POST",
      data  : {kode : kode},
      success : function(data)
      {
        json = JSON.parse(data);
        $("#no_kks").val(kode);
        $("#no_kk").val(json.no_kk);
        $("#alamat").html(json.alamat);
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
                url:"<?php echo base_url()?>kk/c_kk/hapus_data/"+kode,
                success:function (status) {
                  json = status,
                  obj = JSON.parse(json);
                  msg_hapus();
                  table.ajax.reload();
                },
            });
    }
    return false;
  }

</script>