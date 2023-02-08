<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Mengelola Data Kriteria
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Data Kriteria</button>
                        <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Add Kriteria</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="Kriteria" style="table-layout: auto;">
                              <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Kode Kriteria</th>
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
            <label>Nama Kriteria</label><br>
            <div class="form-line">
              <input type="hidden" name="kode_kriteria" id="kode_kriteria">
              <input type="text" name="nama_kriteria" id="nama_kriteria" maxlength="50" required class="form-control" onkeyup="cek_nama(this)">
            </div>
          </div>
          <div class="form-group">
            <label>Bobot Kriteria</label><br>
            <div class="form-line">
              <input type="text" name="bobot" id="bobot" onkeyup="hanya_angka(this);cek_bobot(this)" class="form-control" maxlength="4">
            </div>
            <font color="red">**<label>Sisa Bobot :</label><label id="sisa_show"></label></font>
            <input type="hidden" name="bobot_edit" id="bobot_edit">
          </div>
          <div class="form-group">
            <span id="form_detail"></span>
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
  table = $('#Kriteria').DataTable({ 
    "processing"  : true, 
    "serverSide"  : true, 
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "order": [],     
    "ajax": {
      "url": "<?php echo site_url('Kriteria/c_kriteria/get_data')?>",
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
    kode_kriteria='';
    $("#form_input")[0].reset();
    $.ajax({
      url   : "<?php echo base_url('Kriteria/c_kriteria/get_form')?>",
      data  : {kode_kriteria : kode_kriteria},
      type  : "POST",
      success : function(data)
      {
        // json = JSON.parse(data);
        $("#form_detail").html(data);
        $("#sisa_show").html($("#sisa").val());
        $("#modal-default").modal('show');
      }
    });
    
  }

  function cek_nama()
  {
    kriteria = $("#nama_kriteria").val();
    $.ajax({
      url   : "<?php echo base_url('kriteria/c_kriteria/cek_kriteria')?>",
      type  : "POST",
      data  : {kriteria : kriteria},
      success : function(data)
      {
        json = JSON.parse(data);
        if(json.status==="ada")
        {
          error_msg('Nama Kriteria '+kriteria+' Sudah ada!!');
          $("#nama_kriteria").val("");
        }
      } 
    });
  }


  function cek_bobot()
  {
    status = $("#status").val();
    // console.log(status);
    bobot   = $("#bobot").val();
    bobot_edit = $("#bobot_edit").val();
    sisa    = $("#sisa").val();
    replace = bobot.replace(",","");
    replace_e = bobot_edit.replace(",","");
    if(status==="baru"){
    hasil = parseInt(sisa)-parseInt(replace);
    }
    else if(status==="edit")
    {
      hasil = (parseInt(replace_e)-parseInt(replace)); 
    }
    if (hasil < 0)
    {
      error_msg('Bobot Melebihi 100');
      $("#bobot").val("");
    }
    console.log(hasil);
  }

  function edit_data(kode='')
  {
    $.ajax({
      url   : "<?php echo base_url('kriteria/c_kriteria/get_form')?>",
      type  : "POST",
      data  : {kode : kode},
      success : function(data)
      {
        $("#form_detail").html(data);
        sisa_show = parseInt($("#sisa").val())+parseInt($("#bobots").val());
        $("#sisa_show").html(sisa_show);
        $("#bobot_edit").val(sisa_show);
        $("#nama_kriteria").val($("#nama_kriterias").val());
        $("#kode_kriteria").val($("#kode_kriterias").val());
        $("#bobot").val($("#bobots").val());
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
                url:"<?php echo base_url()?>kriteria/c_kriteria/hapus_data/"+kode,
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