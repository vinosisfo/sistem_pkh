<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Mengelola Data Kartu Keluarga Detail
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Data KK DETAIL</button>
                        <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Add KK DETAIL</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="kk" style="table-layout: auto;">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>No KK</th>
                                  <th>Alamat</th>
                                  <th>NIK</th>
                                  <th>Nama Keluarga</th>
                                  <th>Status</th>
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
  <div class="modal-dialog" style="width:850px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Input Data KK DETAIL</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>

      <form id="form_input" autocomplete="off">
        <div class="modal-body">
          <div class="form-group">
            <span id="form_kk_detail"></span>
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
  table = $('#kk').DataTable({ 
    "processing"  : true, 
    "serverSide"  : true, 
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "order": [],     
    "ajax": {
      "url": "<?php echo site_url('kk_detail/c_kk_detail/get_data')?>",
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
    $.ajax({
      url   : "<?php echo base_url('kk_detail/c_kk_detail/get_form')?>",
      type  : "POST",
      data  : $("#form_input").serialize(),
      success : function(data)
      {
        $("#form_kk_detail").html(data);
        $("#modal-default").modal('show');
      }
    });
    // $("#modal-default").modal('show');
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
    
    $("#form_input")[0].reset();
    $.ajax({
      url   : "<?php echo base_url('kk_detail/c_kk_detail/get_form/')?>"+kode,
      type  : "POST",
      data  : $("#form_input").serialize(),
      success : function(data)
      {
        $("#form_kk_detail").html(data);
        $("#modal-default").modal('show');
      }
    });
  }

  function hapus_data(kode='',id='')
  {
    var confirmText = "Data Akan Dihapus ?";
       if(confirm(confirmText)) {
            $.ajax({
                type:"POST",
                url:"<?php echo base_url()?>kk_detail/c_kk_detail/hapus_data/"+kode+'/'+id,
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

  $(".select2").select2({
      allowClear:true,
      placeholder: 'Pilih',
      required : true
    });

</script>