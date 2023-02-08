<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                Laporan
            </h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <button class="btn btn-rounded btn-primary btn-sm">Laporan Kriteria</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <form autocomplete="off" action="<?php echo base_url('laporan/c_laporan/get_kriteria')?>" method="get">
                            <table class="table table-striped table-hover" style="width: 400px;">
                              <thead>
                                <tr>
                                  <td style="width: 100px;">KK</td>
                                  <td>:</td>
                                  <td style="width: 300px;">
                                    <select name="kk" id="kk" class="form-control" style="width: 100%;" onchange="jenis(this)" required>
                                      <option value="">PILIH</option>
                                      <?php
                                      foreach ($kk->result() as $ktg) { ?>
                                        <option value="<?php echo $ktg->no_kk ?>"><?php echo $ktg->no_kk ?></option>
                                      <?php } ?>
                                    </select>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="3">
                                    <button type="submit" class="btn btn-info btn-sm">View</button>
                                  </td>
                                </tr>
                              </thead>
                            </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

 function jenis()
  {
    $.ajax({
        type:"POST",
        url:"<?php echo base_url()?>laporan/c_laporan/get_jenis",
        data : {kategori : $("#kategori").val(),},
        success:function (status) {
          json = JSON.parse(status)
          $("#jenis").html(json.jenis);

          
        },
    });
  }

</script>