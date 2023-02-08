<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sri</title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url('assets/adminbsb/favicon.ico') ?>" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/adminbsb/node_modules/material-design-icons-iconfont/dist/material-design-icons.css') ?>">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('assets/adminbsb/plugins/bootstrap/css/bootstrap.css')?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('assets/adminbsb/plugins/node-waves/waves.css')?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url('assets/adminbsb/plugins/animate-css/animate.css')?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url('assets/adminbsb/css/style.css')?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('assets/izitoast/dist/css/iziToast.min.css')?>">
</head>

<body class="login-page" style="background:url(assets/adminbsb/images/image.jpg);
no-repeat center center fixed; background-size: cover;
 -webkit-background-size: cover; 
 -moz-background-size: cover; -o-background-size: cover;">
    <div class="login-box">
        <div class="logo">
            <!-- <a href="javascript:void(0);"><font color="blue"><h4>PROGRAM KELUARGA HARAPAN</font></h4></a> -->
        </div>
        <div class="card">
            <div class="body">
                <form id="f_login">
                    <div class="msg">Sign in</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="button" id="login">SIGN IN</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery/jquery.min.js')?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/bootstrap/js/bootstrap.js')?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/node-waves/waves.js')?>"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('assets/adminbsb/plugins/jquery-validation/jquery.validate.js')?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('assets/adminbsb/js/admin.js')?>"></script>
    <script src="<?php echo base_url('assets/adminbsb/js/pages/examples/sign-in.js')?>"></script>
    <script src="<?php echo base_url('assets/izitoast/dist/js/iziToast.min.js')?>"></script>
  <!-- endinject -->

    <script type="text/javascript">
    $("#login").click(function(){
      username = $("#username").val();
      password = $("#password").val();
      if(username=="" || password=="")
      {
        error_msg();
      }
      else
      {
        $.ajax({
          url   : "<?php echo base_url('Auth/login')?>",
          type  : "POST",
          data  : $("#f_login").serialize(),
          success : function(data)
          {
            json = JSON.parse(data);

            if(json.status==="Berhasil")
            {
              if(json.level==="ADMIN" || json.level==="KADES")
              {
                location.replace("<?php echo base_url('user/c_user')?>");
              }
              else
              {
                error_msg();
              }
            }
            else
            {
              error_msg();
            }
          }
        });
      }
    });

    function error_msg(){
     iziToast.error({
        title: 'Error !!',
        message: 'Username Atau Password Salah',
        position: 'topCenter'
      });
    }
  </script>
</body>

</html>