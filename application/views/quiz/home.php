<section class="content-header">
    <h1>
        Data Tes
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('');?>" data-target="dashboard" onclick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Data Tes</li>
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
                <h3 class="box-title">Data Tes</h3>

                <div class="box-tools pull-right">
                    <a data-toggle="tooltip" data-type="hadir_peserta" class="btn btn-sm btn-success" href="javascript:;" onclick="print_data(this);return false" title="Cetak daftar hadir"><i class="fa fa-print"></i> Cetak Daftar Hadir</a>
                    <a data-toggle="tooltip" data-type="hadir_peserta_all" class="btn btn-sm btn-success" href="javascript:;" onclick="print_data(this);return false" title="Cetak daftar hadir"><i class="fa fa-print"></i> Cetak Daftar Hadir All</a>
                    <a data-toggle="tooltip" data-type="berita_acara" class="btn btn-sm btn-success" href="javascript:;" onclick="print_data(this);return false" title="Cetak daftar hadir"><i class="fa fa-print"></i> Cetak Berita Acara</a>
                    <a data-toggle="tooltip" data-type="tata_tertib" class="btn btn-sm btn-success" href="javascript:;" onclick="print_data(this);return false" title="Cetak daftar hadir"><i class="fa fa-print"></i> Cetak Tata Tertib</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-primary" href="javascript:;" onclick="show_modal({'href':base_url+'quiz/add_data/'+$('#tapel').val()+'/'+$('#jn_id').val(),'title':$(this).attr('title')});return false" title="Tambah Tes"><i class="fa fa-plus"></i> Tambah Tes</a>
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
                        <select id="jn_id" onchange="load_table()" style="width: 100%">
                            <option value="">Jenis Penilaian</option>
                            <?php
                            if ($jn){
                                foreach ($jn as $val){
                                    echo '<option value="'.$val->jn_id.'">'.$val->jn_name.'</option>';
                                }
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
                            <th width="30px" rowspan="2"><input type="checkbox" onclick="icbxall(this)"></th>
                            <th width="80px" rowspan="2">Tahun Pelajaran</th>
                            <th width="70px" rowspan="2">Jenis Penilaian</th>
                            <th width="" rowspan="2">Nama Tes</th>
                            <th width="" rowspan="2">Mata Pelajaran</th>
                            <th width="100px" rowspan="2">Batas Waktu</th>
                            <th width="" colspan="2">Tgl Pelaksanaan</th>
                            <th width="" rowspan="2">Jml Soal</th>
                            <th width="100px" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th width="150px">Mulai</th>
                            <th width="150px">Berakhir</th>
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
    $('#tapel,#jn_id').select2();
    load_table();
    function load_table() {
        var tapel       = $('#tapel').val();
        var keyword     = $('.keyword').val();
        var jn_id       = $('#jn_id').val();
        $('.overlay').show();
        $.ajax({
            url     : base_url + 'quiz/data_home',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, keyword : keyword, jn_id : jn_id },
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
    function bulk_delete() {
        var dtlen   = $('#dataTable tbody input:checkbox:checked').length;
        var konf    = confirm('Anda yakin ingin menghapus data ini ?')
        if (dtlen == 0){
            show_msg('Pilih data lebih dulu','error');
        } else if (konf){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'quiz/bulk_delete',
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
        var mapel_id= $(ob).attr('data-mapel');
        if (!tapel || !jn_id) {
            show_msg('Pilih TAPEL dan JENIS PENILAIAN', 'error');
        } else {
            if (dt_type == 'hadir_peserta'){
                var url     = base_url + 'quiz/cetak_hadir_peserta/' + tapel + '/' + jn_id
            } else if (dt_type == 'berita_acara'){
                var url     = base_url + 'quiz/cetak_berita_acara/' + tapel + '/' + jn_id
            } else if (dt_type == 'tata_tertib'){
                var url     = base_url + 'quiz/cetak_tata_tertib/' + tapel + '/' + jn_id
            } else if (dt_type == 'hadir_peserta_all'){
                var url     = base_url + 'quiz/cetak_hadir_peserta_all/' + tapel + '/' + jn_id
            } else if (dt_type == 'kartu'){
                var url     = base_url + 'quiz/cetak_kartu_soal/' + tapel + '/' + jn_id + '/' + mapel_id;
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