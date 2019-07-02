<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SERVER UJIAN</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css');?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/font-awesome/css/font-awesome.min.css');?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/AdminLTE.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/fonts/sans-pro.css');?>">

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo base_url('');?>"><b>SERVER UJIAN</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="<?php echo base_url('login/submit');?>" method="post" id="form">
            <div class="form-group has-feedback">
                <input name="username" type="text" class="form-control" placeholder="Username">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                </div>
                <div class="col-xs-12" style="margin-top:10px">
                    <a class="btn btn-block btn-flat btn-info" href="<?php echo base_url('hasil_ujian');?>">Lihat Hasil Ujian</a>
                </div>
                <!-- /.col -->
            </div>
        </form>


    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url('assets/bower_components/jquery/dist/jquery.min.js');?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>

<link rel="stylesheet" href="<?php echo base_url('assets/plugins/sweetalert/sweetalert.css');?>">
<script src="<?php echo base_url('assets/plugins/sweetalert/sweetalert.min.js');?>"></script>

<script>
    $('#form').submit(function () {
        $('#form .btn-primary').html('<i class="fa fa-spin fa-refresh"></i> Login').prop('disabled',true);
        $.ajax({
            url     : '<?php echo base_url('login/submit');?>',
            type    : 'POST',
            data    : $(this).serialize(),
            dataType: 'JSON',
            success : function (dt) {
                if (dt.t == 0){
                    $('#form .btn-primary').html('Login').prop('disabled',false);
                    show_msg(dt.msg,'error');
                } else {
                    window.location.href = '<?php echo base_url('');?>';
                }
            }
        });
        return false;
    })
    function show_msg(msg,type) {
        header = 'Gagal';
        if (!type){
            type = 'success';
            header = 'Berhasil';
        }
        swal(header, msg, type);
    }
</script>
</body>
</html>
