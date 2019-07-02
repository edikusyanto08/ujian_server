<section class="content-header">
    <h1>
        Hasil Tes
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('');?>" data-target="dashboard" onclick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('quiz');?>" data-target="dashboard" onclick="load_page(this);return false">Tes</a></li>
        <li class="active">Hasil Tes</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="printwrap">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Cetak Daftar Hadir</h3>

                <div class="box-tools pull-right">
                    <a data-toggle="tooltip" class="btn btn-sm btn-success" href="javascript:;" onclick="print_now();return false" title="Cetak"><i class="fa fa-print"></i> Cetak</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-danger" href="javascript:;" onclick="cancel_print();return false" title="Batal Cetak"><i class="fa fa-close"></i> Batal Cetak</a>
                </div>
            </div>
            <div class="box-body no-padding">
                <iframe name="printframe" id="printframe" src="<?php echo base_url('home/cetak_loading');?>" style="width: 100%;border:solid 1px #CCC;height:450px"></iframe>
            </div>
        </div>
    </div>
    <div class="noprint">
        <?php
        ?>
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Hasil Tes</h3>

                <div class="box-tools pull-right">
                    <a data-toggle="tooltip" class="btn btn-sm btn-success" href="javascript:;" onclick="download_hasil();return false" title="Download Hasil"><i class="fa fa-download"></i> Download Hasil</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-primary disabled btn-delete" href="javascript:;" onclick="gen_hasil();return false" title="Mulai Koreksi"><i class="fa fa-play"></i> Start Koreksi</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-primary" href="javascript:;" onclick="gen_rank();return false" title="Mulai Generate"><i class="fa fa-play"></i> Generate Ranking</a>
                </div>
            </div>
            <div class="box-body no-padding table-responsive">
                <div class="" style="margin:10px auto">
                    <div class="col-md-2" style="">
                        <select id="tapel" onchange="find_tes()" class="form-control" style="width: 100%">
                            <?php
                            $min = $tapel - 1;
                            for ($i = $min; $i <= date('Y'); $i++){
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="jn_id" onchange="find_tes()" style="width: 100%">
                            <?php
                            if ($jn){
                                foreach ($jn as $val){
                                    echo '<option value="'.$val->jn_id.'">'.$val->jn_sing.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="quiz_id" onchange="find_mapel()" style="width:100%">
                            <?php
                            if ($quiz){
                                foreach ($quiz as $val){
                                    echo '<option value="'.$val->quiz_id.'">'.$val->quiz_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="qm_id" onchange="load_table()" style="width:100%">
                            <?php
                            if ($mapel){
                                foreach ($mapel as $val){
                                    echo '<option value="'.$val->qm_id.'">'.$val->mapel_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="keyword form-control" placeholder="Cari ..." onkeyup="doSearch()">
                    </div>
                    <div class="clearfix"></div><div  style="height:10px"></div>
                    <div class="col-md-6">
                        <select id="server_id" style="width:100%" onchange="find_ruang();">
                            <?php
                            if ($server){
                                foreach ($server as $val){
                                    echo '<option value="'.$val->server_id.'">'.$val->server_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="sr_id" style="width:100%" onchange="load_table()">
                            <option value="">==Ruang==</option>
                            <?php
                            if($ruang){
                                foreach ($ruang as $val){
                                    echo '<option value="'.$val->sr_id.'">'.$val->sr_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="clearfix"></div><div  style="height:10px"></div>
                </div>

                <form id="formTable">
                    <table id="dataTable" width="100%" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="30px"><input type="checkbox" onclick="icbxall(this)"></th>
                            <th width="200px">Nomor Peserta</th>
                            <th width="">Nama Peserta</th>
                            <th width="100px">Kelas</th>
                            <th width="50px">Jml Soal</th>
                            <th width="50px">Jml Hasil</th>
                            <th width="50px">Skor</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>

            </div>
            <!-- /.box-body -->
            <div class="overlay"><i class="fa fa-spin fa-refresh"></i> </div>
        </div>
        <!-- /.box -->
    </div>


</section>
<script>
    $('[data-toggle="tooltip"]').tooltip();
    $('.printwrap').hide();
    $('#tapel').val(<?php echo $data->quiz_tapel;?>);
    $('#quiz_id').val(<?php echo $data->quiz_id;?>);
    $('#jn_id').val('<?php echo $data->jn_id;?>');
    $('#tapel,#jn_id,#quiz_id,#qm_id,#server_id,#sr_id').select2();
    function download_hasil() {
        var qm_id       = $('#qm_id').val();
        var quiz_id     = $('#quiz_id').val();
        var sr_id       = $('#sr_id').val();
        var url         = base_url + 'quiz/download_hasil/' + quiz_id + '/' + qm_id + '/' + sr_id;
        window.open(url,'new_tab');
    }
    function find_tes() {
        var tapel       = $('#tapel').val();
        var jn_id       = $('#jn_id').val();
        $.ajax({
            url     : base_url + 'quiz/find_tes',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, jn_id : jn_id },
            success : function (dt) {
                if (dt.t == 0){
                    $('#quiz_id').html('<option value="">'+dt.msg+'</option>');
                    $('#server_id').html('<option value="">'+dt.msg+'</option>');
                    $('#sr_id').html('<option value="">'+dt.msg+'</option>');
                    find_mapel();
                } else {
                    $('#quiz_id').html('');
                    $.each(dt.data,function (i,v) {
                        $('#quiz_id').append('<option value="'+v.quiz_id+'">'+v.quiz_name+'</option>');
                        if (i + 1 >= dt.data.length){
                            find_mapel();
                        }
                    });
                    if (dt.data2.length == 0) {
                        $('#server_id').html('<option value="">Tidak ada Server</option>');
                        $('#sr_id').html('<option value="">Tidak ada server</option>');
                    } else {
                        $('#server_id').html('');
                        $.each(dt.data2,function (i,v) {
                            $('#server_id').append('<option value="'+v.server_id+'">'+v.server_name+'</option>');
                            if (i + 1 >= dt.data2.length){
                                find_ruang();
                            }
                        });
                    }
                }
            }
        });
    }
    function find_ruang() {
        var server_id   = $('#server_id').val();
        $.ajax({
            url     : base_url + 'quiz/find_ruang',
            type    : 'POST',
            dataType: 'JSON',
            data    : { server_id : server_id },
            success : function (dt) {
                if (dt.t == 0){
                    $('#sr_id').html('<option value="">'+dt.msg+'</option>');
                    load_table();
                } else {
                    $('#sr_id').html('<option value="">==Ruang==</option>');
                    $.each(dt.data,function (i,v) {
                        $('#sr_id').append('<option value="'+v.sr_id+'">'+v.sr_name+'</option>');
                        if (i + 1 >= dt.data.length){
                            load_table();
                        }
                    })
                }
            }
        })
    }
    function find_mapel() {
        var tapel       = $('#tapel').val();
        var jn_id       = $('#jn_id').val();
        var quiz_id     = $('#quiz_id').val();
        $.ajax({
            url     : base_url + 'quiz/find_mapel',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, jn_id : jn_id, quiz_id : quiz_id },
            success : function (dt) {
                if (dt.t == 0){
                    $('#qm_id').html('<option value="">'+dt.msg+'</option>');
                    load_table();
                } else {
                    $('#qm_id').html('');
                    $.each(dt.data,function (i,v) {
                        $('#qm_id').append('<option value="'+v.qm_id+'">'+v.mapel_name+'</option>');
                        if (i + 1 >= dt.data.length){
                            load_table();
                        }
                    });
                }
            }
        })
    }
    load_table();
    function load_table() {
        var tapel       = $('#tapel').val();
        var keyword     = $('.keyword').val();
        var jn_id       = $('#jn_id').val();
        var quiz_id     = $('#quiz_id').val();
        var qm_id       = $('#qm_id').val();
        var server_id   = $('#server_id').val();
        var sr_id       = $('#sr_id').val();
        $('.overlay').show();
        $.ajax({
            url     : base_url + 'quiz/data_hasil',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, keyword : keyword, jn_id : jn_id, quiz_id : quiz_id, qm_id : qm_id, server_id : server_id, sr_id : sr_id },
            success : function (dt) {
                if (dt.t == 0){
                    $('#dataTable tbody').html('<tr class="row_zero"><td colspan="7">'+dt.msg+'</td></tr>');
                    $('.overlay').hide();
                    $('.jml').html('0');
                    $('.btn-delete').addClass('disabled');
                    $('#dataTable thead input:checkbox').prop({'checked':false});
                } else {
                    $('#dataTable tbody').html(dt.html);
                    $('.btn-delete').addClass('disabled');
                    $('.overlay').hide();
                    $('.jml').html(dt.jml);
                    $('#dataTable thead input:checkbox').prop({'checked':false});
                }
            }
        })
    }
    function gen_hasil() {
        var dtlen   = $('#dataTable tbody input:checkbox:checked').length;
        var konfirm = confirm('Mulai Koreksi Hasil ?\nHal ini akan membutuhkan waktu yang cukup lama.');
        if (dtlen == 0){
            show_msg('Pilih peserta lebih dulu','error');
        } else if (konfirm) {
            $('.overlay').show();
            $('#ProsesModal').modal('show');
            $('#progressBar').attr({'aria-valuenow':0}).css({'width':0}).html('0%');
            var quiz_id     = $('#quiz_id').val();
            var qm_id       = $('#qm_id').val();
            var total       = dtlen;
            $.each($('#dataTable tbody input:checkbox:checked'),function (i,v) {
                var fullname    = $(this).parents('tr').find('td').eq(2).text();
                $('#ProsesModal .alert-info').html('Memproses data : '+fullname);
                $.ajax({
                    url     : base_url + 'quiz/gen_hasil',
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : { sis_id : $(this).val(), quiz_id : quiz_id, qm_id : qm_id },
                    async   : false,
                    cache   : false,
                    success : function (dt) {
                        persen  = Math.round(((i + 1) / total)*100);
                        $('#progressBar').attr({'aria-valuenow':persen}).css({'width':persen+'%'}).html(persen+'%');
                        if (i + 1 >= total){
                            $('#ProsesModal').modal('hide');
                            $('.overlay').hide();
                            load_table();
                            //gen_rank();
                        }
                    }
                });
            })
        }
    }
    function gen_rank() {
        var quiz_id     = $('#quiz_id').val();
        var qm_id       = $('#qm_id').val();
        $('.overlay').show();
        $.ajax({
            url         : base_url + 'quiz/gen_rank',
            type        : 'POST',
            dataType    : 'JSON',
            data        : { quiz_id : quiz_id, qm_id : qm_id },
            success     : function (dta) {
                if (dta.t == 0){
                    $('.overlay').hide();
                    show_msg(dta.msg,'error');
                } else {
                    $('.overlay').hide();
                    hide_modal();
                }
            }
        });
    }
    function print_now() {
        window.frames["printframe"].focus();
        window.frames["printframe"].print();
    }
    function cancel_print() {
        $('.printwrap').hide();
        $('.noprint').show();
        $('#printframe').attr({'src':base_url+'home/cetak_loading'});
    }
    function print_data(ob) {
        var tapel   = $('#tapel').val();
        var jn_id   = $('#jn_id').val();
        var dt_type = $(ob).attr('data-type');
        if (!tapel || !jn_id) {
            show_msg('Pilih TAPEL dan JENIS PENILAIAN', 'error');
        } else {
            if (dt_type == 'hadir_peserta'){
                var url     = base_url + 'quiz/cetak_hadir_peserta/' + tapel + '/' + jn_id
            } else if (dt_type == 'berita_acara'){
                var url     = base_url + 'quiz/cetak_berita_acara/' + tapel + '/' + jn_id
            } else if (dt_type == 'tata_tertib'){
                var url     = base_url + 'quiz/cetak_tata_tertib/' + tapel + '/' + jn_id
            }
            $('#printframe').attr({ 'src' : url });
            $('.printwrap').show();
            $('.noprint').hide();
        }
    }
    $('.overlay').hide();
    function icbxall(ob) {
        if ($(ob).prop('checked') == true){
            $('#dataTable tbody input:checkbox').prop('checked',true);
            $('.btn-delete').removeClass('disabled');
        } else {
            $('#dataTable tbody input:checkbox').prop('checked',false);
            $('.btn-delete').addClass('disabled');
        }
    }
    var delayTimer;
    function doSearch() {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            load_table();
        }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
    }

</script>
<div id="ProsesModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Proses Koreksi Soal</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 progressnya">
                    <div class="progress">
                        <div id="progressBar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <div class="alert alert-info"></div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-danger" style="display: none"></div>
                </div>
                <div class="clearfix"></div>
                <button type="button" onclick="$('#ProsesModal').modal('hide');return false" class="pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>
                <div class="clearfix"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->