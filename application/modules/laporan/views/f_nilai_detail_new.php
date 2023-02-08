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
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                          <form id="list_data" action="<?php echo base_url('laporan/c_laporan/view_laporan_nilai')?>" method="get">
                          <table class="table table-responsive table-striped" style="width: 300px;">
                            <tr>
                              <td style="width: 100px;">Peiode </td>
                              <td style="width: 1px;">:</td>
                              <td style="width: 200px;">
                                <select name="periode_src" id="periode_src" style="width : 100%;">
                                  <option value="">PILIH</option>
                                  <option value="1">PERIODE 1 <?php echo date('Y') ?></option>
                                  <option value="2">PERIODE 2 <?php echo date('Y') ?></option>
                                </select>
                                <input type="hidden" name="tanggal_src" id="tanggal_src" class="tanggal_src" style="width: 30%;">
                              </td>
                            </tr>
                            <tr>
                              <td>No KK</td>
                              <td>:</td>
                              <td>
                                <select name="no_kk_src" id="no_kk_src" class="select2" style="width: 100%;">
                                   <option value="">PILIH</option>
                                  <?php
                                  foreach ($no_kk->result() as $data) { ?>
                                    <option value="<?php echo $data->no_kk ?>"><?php echo $data->no_kk ?></option>
                                  <?php } ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <button type="submit" class="btn btn-info btn-sm"> CARI</button>
                              </td>
                            </tr>
                          </table>
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