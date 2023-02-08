<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Mengelola Data User
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Data User</button>
                        <button class="btn btn-rounded btn-danger btn-sm" onclick="add_user(this)">Add User</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="user" style="table-layout: auto;">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Kode User</th> 
                                  <th>Username</th>
                                  <th>Password</th>
                                  <th>Level</th>
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
        <!-- #END# Basic Examples -->
        <!-- Exportable Table -->
      
        <!-- #END# Exportable Table -->
    </div>
</section>

<div class="modal fade" id="modal-default"> 
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Input Data User</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>

      <form id="form_input" autocomplete="off">
        <div class="modal-body">
          <div class="form-group">
            <label>Username</label><br>
            <div class="form-line">
              <input type="hidden" name="kode_user" id="kode_user">
              <input type="text" name="username" id="username" maxlength="50" required class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label>Password</label><br>
            <div class="form-line">
              <input type="password" name="password" id="password" class="form-control" required maxlength="50">
            </div>
          </div>
          <div class="form-group">
            <label>Level</label><br>
             <select class="form-control show-tick" name="level" id="level">
            <!-- <select name="level" id="level" class="form-control"> -->
              <option value="">Pilih</option>
              <option value="ADMIN">ADMIN</option>
              <option value="KADES">KADES</option>
            </select>
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
  table = $('#user').DataTable({ 
    "processing"  : true, 
    "serverSide"  : true, 
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "order": [],     
    "ajax": {
      "url": "<?php echo site_url('user/c_user/get_data')?>",
      "type": "POST",
      "data": function ( data ) {
              data.username = $('#username').val();
              data.level    = $('#level').val();
              
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

  function add_user()
  {
    $("#form_input")[0].reset();
    $("#username").removeAttr("readonly","readonly");
    $("#modal-default").modal('show');
    // $("#level").append('<option value="tes">tes</option>');
  }

  function simpan_data()
  {
    kode_user = $("#kode_user").val();
    username  = $("#username").val();
    password  = $("#password").val();
    level     = $("#level").val();

    if(kode_user!="")
    {
      var path = "<?php echo base_url('user/c_user/update_data')?>";
    }
    else
    {
      var path = "<?php echo base_url('user/c_user/simpan_data')?>";
    }
    if(username=="" || password=="" || level=="")
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
      message: 'Username, Password , Level Tidak Boleh Kosong',
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

  function edit_data(kode_user='')
  {
    $.ajax({
      url   : "<?php echo base_url('user/c_user/get_edit')?>",
      type  : "POST",
      data  : {kode_user : kode_user},
      success : function(data)
      {
        json = JSON.parse(data);
        $("#username").attr("readonly","readonly");
        $("#kode_user").val(kode_user);
        $("#username").val(json.username);
        $("#level").html(json.level);
        $("#modal-default").modal('show');
      } 
    });
  }

  function hapus_data(kode_user='')
  {
    var confirmText = "Data Akan Dihapus ?";
       if(confirm(confirmText)) {
            $.ajax({
                type:"POST",
                url:"<?php echo base_url()?>user/c_user/hapus_data/"+kode_user,
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