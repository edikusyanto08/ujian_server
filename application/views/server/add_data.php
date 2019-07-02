<form id="modalForm" class="">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Tahun Pelajaran</label>
            <input type="text" class="form-control " value="<?php echo $tapel;?>" disabled>
            <input type="hidden" name="tapel" id="" value="<?php echo $tapel;?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Jenis Penilaian</label>
            <input type="text" class="form-control" value="<?php echo $jn->jn_name;?>" disabled>
            <input type="hidden" name="jn_id" value="<?php echo $jn->jn_id;?>">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Nama Server</label>
            <input type="text" class="form-control" value="<?php echo $server_name;?>" disabled>
            <input type="hidden" name="server_name" value="<?php echo $server_name;?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">ID Server</label>
            <input type="text" disabled class="form-control" value="<?php echo $server_kode;?>">
            <input type="hidden" name="server_kode" value="<?php echo $server_kode;?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Jml Ruang</label>
            <input type="number" name="jml_ruang" class="form-control" min="1" max="999" value="1">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Jml Client</label>
            <input type="number" name="jml_client" class="form-control" min="1" max="999" value="1">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
        <button style="margin-right:5px" type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $.ajax({
            url     : base_url + 'server/add_data_submit',
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
                    if ($('.row_zero').length > 0){ $('.row_zero').remove(); }
                    $('#dataTable tbody').append(dtA.html);
                }
            }
        })
        return false;
    })
</script>