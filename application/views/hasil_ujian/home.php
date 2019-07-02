<section class="content-header">
    <h1>
        Hasil Ujian
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('');?>" data-target="dashboard" onclick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Hasil Ujian</li>
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
                <h3 class="box-title">Hasil Ujian</h3>
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
                    <div class="col-md-6">
                        <select id="mapel_id" onchange="load_table()" style="width: 100%">
                            <option value="">Mata Pelajaran</option>
                            <?php
                            if ($mapel){
                                foreach ($mapel as $val){
                                    echo '<option value="'.$val->mapel_id.'">'.$val->mapel_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12" style="margin-top:10px">
                        <input type="text" class="keyword form-control" placeholder="Cari nama peserta atau nomor peserta ..." onkeyup="doSearch()">
                    </div>
                    <div class="clearfix"></div>
                </div>

                <form id="formTable">
                    <table id="dataTable" width="100%" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="200px"><a href="javascript:;" by="sis_nopes" asc="ASC" onclick="sort_table(this);return false">Nomor Peserta</a></th>
                            <th width=""><a href="javascript:;" by="sis_fullname" asc="ASC" onclick="sort_table(this);return false">Nama Peserta</a></th>
                            <th width="100px"><a href="javascript:;" by="sis_kelas" asc="ASC" onclick="sort_table(this);return false">Kelas</a></th>
                            <th width="100px"><a href="javascript:;" by="qn_nilai" asc="ASC" onclick="sort_table(this);return false">Jml Jawaban Benar</a></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>

            </div>
            <input type="hidden" name="ordering" class="ordering" value="ASC">
            <input type="hidden" name="order_by" class="order_by" value="sis_nopes">
            <!-- /.box-body -->
            <div class="overlay"><i class="fa fa-spin fa-refresh"></i> </div>
        </div>
        <!-- /.box -->
    </div>


</section>
<script>
    $('[data-toggle="tooltip"]').tooltip();
    $('.printwrap').hide();
    $('#tapel,#jn_id,#mapel_id').select2();
    load_table();
    function sort_table(ob) {
        var order_by    = $(ob).attr('by');
        var ordering    = $(ob).attr('asc');
        if ($(ob).attr('asc') == 'ASC'){
            $(ob).attr({'asc':'DESC'});
        } else {
            $(ob).attr({'asc':'ASC'});
        }
        $('.order_by').val(order_by);
        $('.ordering').val(ordering);
        load_table();
    }
    function load_table() {
        var tapel       = $('#tapel').val();
        var keyword     = $('.keyword').val();
        var jn_id       = $('#jn_id').val();
        var mapel_id    = $('#mapel_id').val();
        var order_by    = $('.order_by').val();
        var ordering    = $('.ordering').val();
        $('.overlay').show();
        $.ajax({
            url     : base_url + 'hasil_ujian/data_home',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, keyword : keyword, jn_id : jn_id, mapel_id : mapel_id, ordering : ordering, order_by : order_by },
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
    $('.overlay').hide();
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