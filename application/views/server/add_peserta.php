<form id="modalForm" class="">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Tahun Pelajaran</label>
            <input type="text" class="form-control" value="<?php echo $tapel;?>" disabled>
            <input type="hidden" name="tapel" value="<?php echo $tapel;?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">ID Server</label>
            <input type="text" class="form-control" disabled value="<?php echo $server->server_kode;?>">
            <input type="hidden" name="server_id" value="<?php echo $server->server_id;?>">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Ruang</label>
            <input type="text" class="form-control" disabled value="<?php echo $ruang->sr_name;?>">
            <input type="hidden" name="sr_id" value="<?php echo $ruang->sr_id;?>">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Sesi</label>
            <input type="text" class="form-control" disabled value="Sesi <?php echo $sesi;?>">
            <input type="hidden" name="sesi" value="<?php echo $sesi;?>">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Tampilkan Data</label>
            <select id="max_data" style="width: 100%" onchange="max_selected()">
                <option value="">Semua</option>
                <?php
                for ($i = 1; $i <= 100; $i++){
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Halaman</label>
            <select id="page" style="width: 100%" onchange="load_table_form()">
                <option value="1">Halaman 1</option>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <table id="modalTable" width="100%" class="table table-bordered table-bordered table-hover">
            <thead>
            <tr>
                <th width="30px"><input type="checkbox" onclick="frcbx(this)"></th>
                <th width="150px">Nomor Peserta</th>
                <th width="">Nama Siswa</th>
                <th width="30px">L/ P</th>
                <th width="100px">Kelas</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <strong>Jml Terpilih <em class="jmlpes">0</em></strong>
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
        <button style="margin-right:5px" type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('#max_data,#page').select2();
    function frcbx(ob) {
        if ($(ob).prop('checked') == true){
            var dtlength = $('#modalTable tbody input:checkbox:checked').length;
            $('#modalTable tbody input:checkbox').prop('checked',true);
        } else {
            var dtlength = $('#modalTable tbody input:checkbox:checked').length;
            $('#modalTable tbody input:checkbox').prop('checked',false);
        }
        $('.jmlpes').text(dtlength);
    }
    load_table_form();
    function load_table_form() {
        var tapel       = $('#tapel').val();
        var keyword     = $('.keyword').val();
        var server_id   = $('#server_id').val();
        var sr_id       = $("#sr_id").val();
        var limit       = $('#max_data').val();
        var page        = $('#page').val();
        //$('.overlay').show();
        $.ajax({
            url     : base_url + 'server/form_add_data_peserta',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, server_id : server_id, keyword : keyword, sr_id : sr_id, limit : limit, page : page },
            success : function (dt) {
                if (dt.t == 0){
                    $('#modalTable tbody').html('<tr class="row_zero"><td colspan="5">'+dt.msg+'</td></tr>');
                    $('.btn-delete').addClass('disabled');
                    //$('.overlay').hide();
                    $('.jmlpes').html('0');
                } else {
                    $('#modalTable tbody').html(dt.html);
                    $('.btn-delete').addClass('disabled');
                    //$('.overlay').hide();
                    $('.jmlpes').html(0);
                }
            }
        })
    }
    function max_selected() {
        var tapel       = $('#tapel').val();
        var keyword     = $('.keyword').val();
        var server_id   = $('#server_id').val();
        var sr_id       = $("#sr_id").val();
        var limit       = $('#max_data').val();
        var page        = $('#page').val();
        $.ajax({
            url     : base_url + 'server/max_selected',
            type    : 'POST',
            dataType: 'JSON',
            data    : { tapel : tapel, server_id : server_id, keyword : keyword, sr_id : sr_id, limit : limit, page : page },
            success : function (dt) {
                if (dt.t == 0){
                    $('#page').html('');
                    load_table_form();
                } else {
                    //paging
                    $('#page').html('');
                    if (dt.nopage == 1){
                        $('#page').html('<option value="1">1</option>');
                        load_table_form();
                    } else {
                        for (i = 1; i <= dt.nopage; i++){
                            $('#page').append('<option value="'+i+'">Halaman '+i+'</option>');
                        }
                        load_table_form();
                    }
                    console.log(dt.offset);
                }
            }
        })
    }
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $.ajax({
            url     : base_url + 'server/add_peserta_submit',
            type    : 'POST',
            data    : $(this).serialize(),
            dataType: 'JSON',
            success : function (dtA) {
                if (dtA.t == 0){
                    $('#MyModal .modal-body .btn-submit').prop({'disabled':false});
                    $('#MyModal .modal-body .btn-success').html('<i class="fa fa-floppy-o"></i> Submit');
                    show_msg(dtA.msg,'error');
                } else {
                    hide_modal();
                    show_msg(dtA.msg);
                    load_table();
                }
            }
        })
        return false;
    })
</script>