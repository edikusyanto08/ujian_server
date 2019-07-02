<form id="modalForm" class="form form-horizontal">
    <input type="hidden" name="server_id" value="<?php echo $data->server_id;?>">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Tahun Pelajaran</label>
            <input type="text" class="form-control frtapel" value="<?php echo $data->server_tapel;?>" disabled>
            <input type="hidden" name="tapel" value="<?php echo $data->server_tapel;?>">
        </div>
    </div><div class="clearfix"></div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Jml Ruang</label>
            <input type="number" name="jml_ruang" class="form-control" min="1" max="999" value="<?php echo $data->jml_ruang;?>">
        </div>
    </div><div class="clearfix"></div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Jml Client</label>
            <input type="number" name="jml_client" class="form-control" min="1" max="999" value="<?php echo $data->server_jml_client;?>">
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
    //$('#fr_tapel,#fr_tingkat,#fr_kk_id').select2();
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $.ajax({
            url     : base_url + 'server/edit_data_submit',
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