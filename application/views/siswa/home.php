<section class="content-header">
    <h1>
        Biodata Siswa
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('');?>" data-target="dashboard" onclick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Biodata Siswa</li>
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
                <h3 class="box-title">Biodata Siswa</h3>

                <div class="box-tools pull-right">
                    <!--<a data-toggle="tooltip" class="btn btn-sm btn-success" href="javascript:;" onclick="print_data();return false" title="Cetak Daftar Hadir"><i class="fa fa-print"></i> Cetak Daftar Hadir</a>-->
                    <a data-toggle="tooltip" class="btn btn-sm btn-default" href="<?php echo base_url('siswa/import_from_server');?>" onclick="show_modal(this);return false" title="Ambil biodata"><i class="fa fa-download"></i> Ambil dari ERAPOR</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-primary" href="javascript:;" onclick="show_modal({'href':base_url+'tulis/add_data','title':$(this).attr('title')});return false" title="Tambah Jadwal Tes Tulis"><i class="fa fa-plus"></i> Tambah Jadwal</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-danger disabled btn-delete" href="javascript:;" onclick="bulk_delete();return false" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
                </div>
            </div>
            <div class="box-body no-padding table-responsive">
                <div class="" style="margin:10px auto">
                    <div class="col-md-2" style="">
                        <select id="tapel" onchange="load_table()" class="form-control" style="width: 100%">
                            <?php
                            $min = $tapel - 1;
                            for ($i = $min; $i <= date('Y'); $i++){
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="kk_id" onchange="load_table();" class="form-control" style="width:100%">
                            <option value="">Kompetensi Keahlian</option>
                            <?php
                            foreach ($kk as $val){
                                echo '<option value="'.$val->kk_id.'">'.$val->kk_kode.'. '.$val->kk_name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="keyword form-control" placeholder="Cari ..." onkeyup="doSearch()">
                    </div>
                    <div class="clearfix"></div>
                </div>

                <form id="formTable">
                    <table id="dataTable" width="100%" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="30px"><input type="checkbox" onclick="icbxall(this)"></th>
                            <th width="80px">NIS</th>
                            <th width="120px">Nomor Peserta</th>
                            <th width="">Nama Lengkap</th>
                            <th width="30px">L/ P</th>
                            <th width="80px">Username</th>
                            <th width="80px">Password</th>
                            <th width="">Kompetensi Keahlian</th>
                            <th width="30px">Ting kat</th>
                            <th width="70px">Kelas</th>
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
    $('#tapel,#kk_id').select2();
    load_table();
    function load_table() {
        var tapel       = $('#tapel').val();
        var keyword     = $('.keyword').val();
        var kk_id       = $('#kk_id').val();
        $('.overlay').show();
        $.ajax({
            url     : base_url + 'siswa/data_home',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, keyword : keyword, kk_id : kk_id },
            success : function (dt) {
                if (dt.t == 0){
                    $('#dataTable tbody').html('<tr><td colspan="8">'+dt.msg+'</td></tr>');
                    $('.btn-delete').addClass('disabled');
                    $('.overlay').hide();
                    $('.jml').html('0');
                } else {
                    $('#dataTable tbody').html(dt.html);
                    $('.btn-delete').addClass('disabled');
                    $('.overlay').hide();
                    $('.jml').html(dt.jml);
                }
            }
        })
    }
    function bulk_delete() {
        var dtlen   = $('#dataTable tbody input:checkbox:checked').length;
        var konf    = confirm('Anda yakin ingin menghapus data ini ?')
        if (dtlen == 0){
            show_msg('Pilih data lebih dulu','error');
        } else if (konf){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'siswa/bulk_delete',
                type    : 'POST',
                dataType: 'JSON',
                data    : $('#formTable').serialize(),
                success : function (dt) {
                    if (dt.t == 0){
                        show_msg(dt.msg,'error');
                        $('.overlay').hide();
                    } else {
                        show_msg(dt.msg);
                        $.each(dt.data,function (i,v) {
                            $('.row_'+v).remove();
                        });
                        $('.overlay').hide();
                    }
                }
            })
        }
    }



    function set_active(ob) {
        var quiz_id     = $(ob).attr('data-id');
        var status      = $(ob).attr('data-value');
        var konf        = confirm('Aktifkan Tes ini ?');
        if (!quiz_id || !status){
            show_msg('Invalid parameter','error');
        } else if (konf){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'tulis/set_active',
                type    : 'POST',
                dataType: 'JSON',
                data    : { quiz_id : quiz_id, status : status },
                success : function (dt) {
                    if (dt.t == 0){
                        show_msg(dt.msg,'error');
                        $('.overlay').hide();
                    } else {
                        $('.overlay').hide();
                        show_msg(dt.msg);
                        if (status == 1){
                            $('.act_'+quiz_id).attr({'data-value':0,'title':'Non aktifkan Tes'});
                            $('.act_'+quiz_id).html('<i class="fa fa-check-circle"></i> Tes Aktif');
                            $('.act_'+quiz_id).removeClass('btn-warning').addClass('btn-success');
                        } else {
                            $('.act_'+quiz_id).attr({'data-value':1,'title':'Aktifkan Tes'});
                            $('.act_'+quiz_id).html('<i class="fa fa-close"></i> Tes Tidak Aktif');
                            $('.act_'+quiz_id).removeClass('btn-success').addClass('btn-warning');
                        }
                    }
                }
            })
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $('.printwrap').hide();
    function print_now() {

    }
    function cancel_print() {
        $('.printwrap').hide();
        $('.noprint').show();
        $('#printframe').attr({'src':base_url+'home/cetak_loading'});
    }
    function print_data() {
        var sis_id = '';
        $.each($('#dataTable tbody input:checkbox:checked'),function (i,v) {
            sis_id = $(this).val()+'-';
        });
        if ($('#dataTable tbody input:checkbox:checked').length == 0){
            show_msg('Pilih siswa lebih dulu','error');
        } else {
            $('#printframe').attr({'src':base_url+'siswa/cetak_kartu/'+sis_id});
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


    function distribusi(ob) {
        var id      = $(ob).attr('data-id');
        var konf    = confirm('Mulai Distribusikan Soal ?');
        if (!id){
            show_msg('Invalid parameter','error');
        } else if (konf){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'tulis/distribusi',
                type    : 'POST',
                dataType: 'JSON',
                data    : { id : id },
                success : function (dt) {
                    if (dt.t == 0){
                        $('.overlay').hide();
                        show_msg(dt.msg,'error');
                    } else {
                        $('.overlay').hide();
                        $('#ProsesModal').modal({backdrop: 'static', keyboard: false});
                        $('#progressBar').attr('aria-valuenow', 0).css('width', '0%').text('0%');
                        var ntotal    = dt.data.length;
                        var persen;
                        $.each(dt.data,function (i,v) {
                            $('#ProsesModal .alert-info').html('Distribusi soal ke peserta '+v.user_fullname);
                            $.ajax({
                                url     : base_url + 'tulis/distribusi_proses',
                                async   : false,
                                cache   : false,
                                type    : 'POST',
                                dataType: 'JSON',
                                data    : { user_id : v.user_id, mapel_id : dt.mapel_id, jml_soal : dt.jml_soal, quiz_id : dt.quiz_id  },
                                success : function (dtB) {
                                    if (dtB.t == 0){
                                        $('#ProsesModal .alert-danger').show();
                                        $('#ProsesModal .alert-danger').append(dtB.msg+'<br>');
                                    } else {
                                        persen = Math.round((i / ntotal) * 100);
                                        console.log(persen);
                                        $('#progressBar').attr('aria-valuenow', persen).css('width', persen+'%').text(persen+'%');
                                        if (i + 1 >= dt.data.length){
                                            load_table();
                                            show_msg(dt.data.length+' Siswa berhasil mendapatkan soal');
                                            $('#ProsesModal').modal('hide');
                                        }
                                    }
                                }
                            })
                        })
                    }
                }
            })
        }
    }
</script>
<div id="ProsesModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Proses Distribusi Soal</h4>
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