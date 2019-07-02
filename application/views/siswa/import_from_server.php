<form id="modalForm">
    <div class="inputnya">
        <div class="col-md-12">
            SERVER STATUS :
            <?php
            $submit = 'disabled';
            if ($conn){
                if (is_object($conn)){
                    $submit = '';
                    echo '<strong style="font-size:16px" class="text-success">ONLINE</strong>';
                } else {
                    echo '<strong style="font-size:16px" class="text-danger">OFFLINE</strong>';
                }
            } else {
                echo '<strong style="font-size:16px" class="text-danger">OFFLINE</strong>';
            }
            ?>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Tahun Pelajaran</label>
                <select id="fr_tapel" name="tapel" style="width:100%">
                    <?php
                    $min = $tapel - 1;
                    for ($i = $min; $i <= date('Y'); $i++){
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                <label class="control-label">Kompetensi Keahlian</label>
                <select id="fr_kk_id" name="kk_id" class="form-control" style="width:100%">
                    <option value="">Semua Kompetensi Keahlian</option>
                    <?php
                    foreach ($kk as $val){
                        echo '<option value="'.$val->kk_id.'">'.$val->kk_kode.'. '.$val->kk_name.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Tingkat</label>
                <select id="fr_tingkat" name="tingkat" style="width:100%">
                    <option value="">Semua Tingkat</option>
                    <?php
                    for($i = 7; $i <= 12; $i++){
                        echo '<option value="'.$i.'">Tingkat '.$i.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
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
    <div class="col-md-12">
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
        <button style="margin-right:5px" <?php echo $submit;?> type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('#MyModal .modal-body .alert-danger,#MyModal .modal-body .alert-info, #MyModal .modal-body .progressnya').hide();
    $('#fr_tapel,#fr_tingkat,#fr_kk_id').select2();
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $('#MyModal .modal-body .progressnya').show();
        $('#MyModal .modal-body .inputnya').hide();
        $.ajax({
            url     : base_url + 'siswa/import_from_server_submit',
            type    : 'POST',
            data    : $(this).serialize(),
            dataType: 'JSON',
            success : function (dtA) {
                if (dtA.t == 0){
                    $('#MyModal .modal-body .btn-submit').prop({'disabled':false});
                    $('#MyModal .modal-body .btn-success').html('<i class="fa fa-floppy-o"></i> Submit');
                    $('#MyModal .modal-body .progressnya').hide();
                    $('#MyModal .modal-body .inputnya').show();
                    show_msg(dtA.msg,'error');
                } else {
                    var ntotal    = dtA.data.length;
                    console.log(ntotal);
                    var persen;
                    $('#MyModal .modal-body .alert-info').show();
                    $.each(dtA.data,function (i,v) {
                        $('#MyModal .modal-body .alert-info').html('Memproses data : '+v.sis_fullname);
                        $.ajax({
                            url     : base_url + 'siswa/import_from_server_proses',
                            type    : 'POST',
                            dataType: 'JSON',
                            async   : false,
                            cache   : false,
                            data    : { data : v },
                            success : function (dtB) {
                                if (dtB.t == 0){
                                    $('#MyModal .modal-body .alert-danger').show();
                                    $('#MyModal .modal-body .alert-danger').html(dtB.msg);
                                } else {
                                    $('#MyModal .modal-body .alert-danger').hide();
                                }
                                console.log(persen);
                                persen = Math.round((i / ntotal) * 100);
                                $('#progressBar').attr('aria-valuenow', persen).css('width', persen+'%').text(persen+'%');
                                if (i + 1 >= dtA.data.length){
                                    load_table();
                                    hide_modal();
                                }
                            }
                        });
                    });
                }
            }
        })
        return false;
    })
</script>