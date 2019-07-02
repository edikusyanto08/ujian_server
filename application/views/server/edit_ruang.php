<form id="modalForm" class="form form-horizontal">
    <input type="hidden" name="sr_id" value="<?php echo $data->sr_id;?>">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Nama Ruang</label>
            <input type="text" name="sr_name" class="form-control" value="<?php echo $data->sr_name;?>">
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
            url     : base_url + 'server/edit_ruang_submit',
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