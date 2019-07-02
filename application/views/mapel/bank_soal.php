<section class="content-header">
    <h1>
        Bank Soal
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('');?>" data-target="dashboard" onclick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a data-target="mapel" href="<?php echo base_url('mapel');?>" onclick="load_page(this);return false">Mata Pelajaran</a></li>
        <li class="active">Bank Soal</li>
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
                <h3 class="box-title">Bank Soal</h3>

                <div class="box-tools pull-right">
                    <a data-toggle="tooltip" class="btn btn-sm btn-primary" href="javascript:;" onclick="show_modal({'href':base_url+'mapel/add_bank_soal/'+$('#tingkat').val()+'/'+$('#mapel_id').val()+'/'+$('#kk_id').val(),'title':$(this).attr('title')});return false" title="Tambah Soal"><i class="fa fa-plus"></i> Tambah Soal</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-default" href="javascript:;" onclick="show_modal({'href':base_url+'mapel/import_bank_soal/'+$('#tingkat').val()+'/'+$('#mapel_id').val()+'/'+$('#kk_id').val(),'title':$(this).attr('title')});return false" title="Import Soal"><i class="fa fa-upload"></i> Upload Soal</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-default" href="javascript:;" onclick="show_modal({'href':base_url+'mapel/copy_bank_soal/'+$('#tingkat').val()+'/'+$('#mapel_id').val()+'/'+$('#kk_id').val(),'title':$(this).attr('title')});return false" title="Copy Soal"><i class="fa fa-copy"></i> Copy Soal</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-danger disabled btn-delete" href="javascript:;" onclick="bulk_delete();return false" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
                </div>
            </div>
            <div class="box-body no-padding table-responsive">
                <div class="" style="margin:10px auto">
                    <div class="col-md-4">
                        <select id="kk_id" style="width: 100%" onchange="cari_mapel()">
                            <option value="">Kompetensi Keahlian</option>
                            <?php
                            foreach ($kk as $val){
                                echo '<option value="'.$val->kk_id.'">'.$val->kk_kode.' - '.$val->kk_name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="tingkat" style="width: 100%" onchange="cari_mapel()">
                            <option value="">Semua Tingkat</option>
                            <?php
                            for($i = 7; $i <= 12; $i++){
                                echo '<option value="'.$i.'">Tingkat '.$i.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="mapel_id" style="width: 100%" onchange="load_table()">
                            <?php
                            if ($mapel){
                                foreach ($mapel as $val){
                                    echo '<option value="'.$val->mapel_id.'">'.$val->mapel_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="keyword form-control" placeholder="Cari ..." onkeyup="doSearch()">
                    </div>
                    <div class="clearfix"></div>
                </div>

                <form id="formTable">
                    <table id="dataTable" width="100%" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="30px"><input type="checkbox" onclick="icbxall(this)"></th>
                            <th width="30px">No</th>
                            <th width="">Soal</th>
                            <th width="50px">Max Skor</th>
                            <th width="50px">Jenis Soal</th>
                            <th width="350px">Pilihan Ganda / Jawaban</th>
                            <th width="100px">Aksi</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>

            </div>
            <div class="box-footer">
                <div class="box-tools pull-right">
                    <a data-toggle="tooltip" class="btn btn-sm btn-primary" href="javascript:;" onclick="show_modal({'href':base_url+'mapel/add_bank_soal/'+$('#tingkat').val()+'/'+$('#mapel_id').val()+'/'+$('#kk_id').val(),'title':$(this).attr('title')});return false" title="Tambah Soal"><i class="fa fa-plus"></i> Tambah Soal</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-danger disabled btn-delete" href="javascript:;" onclick="bulk_delete();return false" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="overlay"><i class="fa fa-spin fa-refresh"></i> </div>
        </div>
        <!-- /.box -->
    </div>


</section>
<!-- MathJax -->
<script type="text/javascript" async src="<?php echo base_url('assets/bower_components/MathJax-master/MathJax.js?config=TeX-AMS_CHTML');?>"></script>
<script>
    function cetak_data(ob) {
        var jenis       = $(ob).attr('data-type');
        var mapel_id    = $('#mapel_id').val();
        var url         = base_url + 'mapel/cetak_kartu_soal/'+mapel_id;
        $('#printframe').attr({'src':url});
        $('.noprint').hide();
        $('.printwrap').show();
    }
    function cari_mapel() {
        var kk_id   = $('#kk_id').val();
        var tingkat = $('#tingkat').val();
        $.ajax({
            url     : base_url + 'mapel/cari_mapel',
            type    : 'POST',
            dataType: 'JSON',
            data    : { kk_id : kk_id, tingkat : tingkat },
            success : function (dt) {
                if (dt.t == 0){
                    $('#mapel_id').html('<option value="">'+dt.msg+'</option>');
                } else {
                    $('#mapel_id').html('');
                    $.each(dt.data,function (i,v) {
                        $('#mapel_id').append('<option value="'+v.mapel_id+'">'+v.mapel_name+'</option>');
                        if (i + 1 >= dt.data.length){
                            load_table();
                        }
                    })
                }
            }
        });
    }
    var delayTimer;
    function doSearch() {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(function() {
            load_table();
        }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
    }
    $('[data-toggle="tooltip"]').tooltip();
    $('.printwrap').hide();
    <?php if (isset($data)) { if ($data){ ?>
        $('#kk_id').val(<?php echo $data->kk_id;?>);
        $('#tingkat').val(<?php echo $data->mapel_tingkat;?>);
        $('#mapel_id').val(<?php echo $data->mapel_id;?>)
    <?php } } ?>
    $('#tingkat,#kk_id,#mapel_id').select2();
    load_table();
    function load_table() {
        var mapel_id    = $('#mapel_id').val();
        var keyword     = $('.keyword').val();
        $('.overlay').show();
        $.ajax({
            url     : base_url + 'mapel/data_bank_soal',
            type    : 'POST',
            dataType: 'JSON',
            data    : { keyword : keyword, mapel_id : mapel_id },
            success : function (dt) {
                if (dt.t == 0){
                    $('#dataTable tbody').html('<tr class="row_zero"><td colspan="7">'+dt.msg+'</td></tr>');
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
    function icbxall(ob) {
        if ($(ob).prop('checked') == true){
            $('#dataTable tbody input:checkbox').prop('checked',true);
            $('.btn-delete').removeClass('disabled');
        } else {
            $('#dataTable tbody input:checkbox').prop('checked',false);
            $('.btn-delete').addClass('disabled');
        }
    }
    function bulk_delete() {
        var dtlen   = $('#dataTable tbody input:checkbox:checked').length;
        var konf    = confirm('Anda yakin ingin menghapus data ini ?\n Data peserta juga akan ikut terhapus.')
        if (dtlen == 0){
            show_msg('Pilih data lebih dulu','error');
        } else if (konf){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'mapel/soal_bulk_delete',
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
                        if ($('#dataTable tbody tr').length == 0){
                            $('#dataTable tbody').html('<tr class="row_zero"><td colspan="7">Tidak ada data</td></tr>');
                        }
                        $('.overlay').hide();
                    }
                }
            })
        }
    }
    function delete_data(ob) {
        var soal_id     = $(ob).attr('data-id');
        var konfir      = confirm('Anda yakin ingin menghapus data ini ?');
        if (!soal_id){
            show_msg('Pilih data lebih dulu','error');
        } else if (konfir){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'mapel/delete_soal',
                dataType: 'JSON',
                type    : 'POST',
                data    : { soal_id : soal_id },
                success : function (dt) {
                    if (dt.t == 0){
                        show_msg(dt.msg,'error');
                        $('.overlay').hide();
                    } else {
                        show_msg(dt.msg);
                        $('.overlay').hide();
                        $('.row_'+soal_id).remove();
                        if ($('#dataTable tbody tr').length == 0){
                            $('#dataTable tbody').html('<tr class="row_zero"><td colspan="7">Tidak ada data</td></tr>');
                        }
                    }
                }
            })
        }
    }
    function delete_pg(ob) {
        var soal_id     = $(ob).attr('data-id');
        var konfir      = confirm('Anda yakin ingin menghapus data ini ?');
        if (!soal_id){
            show_msg('Pilih data lebih dulu','error');
        } else if (konfir){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'mapel/delete_pg',
                dataType: 'JSON',
                type    : 'POST',
                data    : { soal_id : soal_id },
                success : function (dt) {
                    if (dt.t == 0){
                        show_msg(dt.msg,'error');
                        $('.overlay').hide();
                    } else {
                        show_msg(dt.msg);
                        $('.overlay').hide();
                        $('.pg_'+soal_id).remove();
                    }
                }
            })
        }
    }
    function set_jawaban(ob) {
        var soal_id     = $(ob).attr('soal-id');
        var pg_id       = $(ob).attr('pg-id');
        var konfirm     = confirm('Set sebagai jawaban yang benar ?\n Hanya berlaku untuk soal PILIHAN GANDA');
        if (!soal_id){
            show_msg('Invalid parameter SOAL','error');
        } else if (!pg_id){
            show_msg('Invalid parameter JAWABAN','error');
        } else if (konfirm){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'mapel/set_jawaban',
                type    : 'POST',
                dataType: 'JSON',
                data    : { soal_id : soal_id, pg_id : pg_id },
                success : function (dt) {
                    if (dt.t == 0){
                        show_msg(dt.msg,'error');
                        $('.overlay').hide();
                    } else {
                        show_msg(dt.msg);
                        $('.overlay').hide();
                        $('.dataPG_'+soal_id).find('.hidden').removeClass('hidden');
                        $('.jawab_'+pg_id).addClass('hidden');
                    }
                }
            });
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