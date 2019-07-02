<section class="content-header">
    <h1>
        Data Ruang
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('');?>" data-target="dashboard" onclick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('server');?>" onclick="load_page(this);return false">Server</a></li>
        <li class="active">Data Ruang</li>
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
                <h3 class="box-title">Data Ruang</h3>

                <div class="box-tools pull-right">
                    <a data-toggle="tooltip" class="btn btn-sm btn-primary" href="javascript:;" onclick="add_ruang();return false" title="Tambah Ruang"><i class="fa fa-plus"></i> Tambah Ruang</a>
                    <a data-toggle="tooltip" class="btn btn-sm btn-danger disabled btn-delete" href="javascript:;" onclick="bulk_delete();return false" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
                </div>
            </div>
            <div class="box-body no-padding table-responsive">
                <div class="" style="margin:10px auto">
                    <div class="col-md-2" style="">
                        <select id="tapel" onchange="tapel_selected()" class="form-control" style="width: 100%">
                            <?php
                            $min = $tapel;
                            for ($i = $min; $i <= date('Y'); $i++){
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="server_id" onchange="load_table()" style="width:100%">
                            <option value="">ID Server</option>
                            <?php
                            if ($server){
                                foreach ($server as $val){
                                    echo '<option value="'.$val->server_id.'">'.$val->server_kode.' - '.$val->server_name.'</option>';
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
                            <th width="30px"><input type="checkbox" onclick="icbxall(this)"></th>
                            <th width="120px">ID Server</th>
                            <th width="">Nama Ruang</th>
                            <th width="80px">Jml Sesi</th>
                            <th width="80px">Jml Peserta</th>
                            <th width="100px">Aksi</th>
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
    $('#tapel').val(<?php echo $sr_tapel; ?>);
    <?php if ($server_id) { echo '$("#server_id").val('.$server_id.');'; } ?>
    $('#tapel,#server_id').select2();
    function add_ruang() {
        var server_id   = $('#server_id').val();
        if (!server_id){
            show_msg('Invalid parameter','error');
        } else {
            $.ajax({
                url     : base_url + 'server/add_ruang',
                type    : 'POST',
                dataType: 'JSON',
                data    : { server_id : server_id },
                success : function (dt) {
                    if (dt.t == 0){
                        show_msg(dt.msg,'error');
                    } else {
                        show_msg(dt.msg);
                        $('#dataTable tbody').append(dt.html);
                    }
                }
            });
        }
    }
    function tapel_selected() {
        var tapel   = $('#tapel').val();
        $('#server_id').select2('destroy');
        $.ajax({
            url     : base_url + 'server/tapel_selected',
            type    : 'POST',
            data    : { tapel : tapel },
            dataType: 'JSON',
            success : function (dt) {
                if (dt.t == 0){
                    $('#server_id').html('<option value="">'+dt.msg+'</option>');
                    $('#server_id').select2();
                    load_table();
                } else {
                    $('#server_id').html('<option value="">ID Server</option>');
                    $.each(dt.data,function (i,v) {
                        $('#server_id').append('<option value="'+v.server_id+'">'+v.server_kode+' - '+v.server_name+'</option>');
                        if (i + 1 >= dt.data.length){
                            $('#server_id').select2();
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
        var server_id   = $('#server_id').val();
        $('.overlay').show();
        $.ajax({
            url     : base_url + 'server/data_ruang',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, server_id : server_id, keyword : keyword },
            success : function (dt) {
                if (dt.t == 0){
                    $('#dataTable tbody').html('<tr class="row_zero"><td colspan="6">'+dt.msg+'</td></tr>');
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
        var konf    = confirm('Anda yakin ingin menghapus data ini ?\n Data peserta juga akan ikut terhapus.')
        if (dtlen == 0){
            show_msg('Pilih data lebih dulu','error');
        } else if (konf){
            $('.overlay').show();
            $.ajax({
                url     : base_url + 'server/bulk_delete_ruang',
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
                            $('#dataTable tbody').html('<tr class="row_zero"><td colspan="6">Tidak ada data</td></tr>');
                        }
                        $('.overlay').hide();
                    }
                }
            })
        }
    }
</script>
