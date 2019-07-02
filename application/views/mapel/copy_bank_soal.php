<form id="modalForm" class="">
    <div class="inputnya">
        <div class="panel panel-info">
            <div class="panel-heading">Source</div>
            <div class="panel-body">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Tingkat</label>
                        <select id="frtingkat" name="src_tingkat" onchange="load_mapel()" style="width:100%">
                            <?php
                            for($i = 7; $i <= 12; $i++){
                                echo '<option value="'.$i.'">Tingkat '.$i.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Kompetensi Keahlian</label>
                        <select id="frkk_id" name="src_kkid" onchange="load_mapel()" style="width:100%">
                            <option value="">Kompetensi Keahlian</option>
                            <?php
                            foreach ($kk as $val){
                                echo '<option value="'.$val->kk_id.'">'.$val->kk_name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Mata Pelajaran</label>
                        <select id="frmapel_id" name="src_mapelid" style="width:100%">
                            <?php
                            foreach ($mapel as $val){
                                echo '<option value="'.$val->mapel_id.'">'.$val->mapel_name.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">Destination</div>
            <div class="panel-body">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">Tingkat</label>
                        <input type="text" class="form-control" value="Tingkat <?php echo $data->mapel_tingkat;?>" disabled>
                        <input type="hidden" name="dst_tingkat" value="<?php echo $data->mapel_tingkat;?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Kompetensi Keahlian</label>
                        <input type="text" class="form-control" value="<?php if (isset($dtkk)){ echo $dtkk->kk_name; }?>" disabled>
                        <input type="hidden" name="dst_kkid" value="<?php if (isset($dtkk)){ echo $dtkk->kk_id; }?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Mata Pelajaran</label>
                        <input type="text" class="form-control" value="<?php echo $data->mapel_name;?>" disabled>
                        <input type="hidden" name="dst_mapelid" value="<?php echo $data->mapel_id;?>">
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
                    <button style="margin-right:5px" type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="progressnya">
        <div class="col-md-12">
            <div class="progress">
                <div id="progressBar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                    0%
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('#frtingkat').val(<?php echo $data->mapel_tingkat;?>);
    $('#frkk_id').val(<?php echo $data->kk_id;?>);
    $('#frtingkat,#frkk_id,#frmapel_id').select2();
    function load_mapel() {
        var tingkat     = $('#frtingkat').val();
        var kk_id       = $('#frkk_id').val();
        $.ajax({
            url     : base_url + 'mapel/load_mapel',
            type    : 'POST',
            dataType: 'JSON',
            data    : { kk_id : kk_id, tingkat : tingkat },
            success : function (dt) {
                if (dt.t == 0){
                    $('#frmapel_id').html('<option value="">'+dt.msg+'</option>');
                } else {
                    $('#frmapel_id').html('');
                    $.each(dt.data,function (i,v) {
                        $('#frmapel_id').append('<option value="'+v.mapel_id+'">'+v.mapel_name+'</option>');
                    });
                }
            }
        })
    }
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $.ajax({
            url     : base_url + 'mapel/copy_bank_soal_submit',
            type    : 'POST',
            data    : $(this).serialize(),
            dataType: 'JSON',
            success : function (dt) {
                if (dt.t == 0){
                    $('#MyModal .modal-body .btn-submit').prop({'disabled':false});
                    $('#MyModal .modal-body .btn-success').html('<i class="fa fa-floppy-o"></i> Submit');
                    show_msg(dt.msg,'error');
                } else {
                    var total   = dt.data.length;
                    $('.inputnya').hide();
                    $('.progressnya').show();
                    $('#progressBar').attr({'aria-valuenow':0}).css({'width':'0%'}).html('0%');
                    $.each(dt.data,function (i,v) {
                        $.ajax({
                            url     : base_url + 'mapel/copy_bank_soal_proses',
                            type    : 'POST',
                            dataType: 'JSON',
                            data    : { data : dt.data[i] },
                            async   : false,
                            cache   : false,
                            success : function (dta) {
                                var persen = Math.round((i / total)*100);
                                $('#progressBar').attr({'aria-valuenow':persen}).css({'width':persen+'%'}).html(persen+'%');
                                if (i + 1 >= total){
                                    show_msg('Soal berhasil disalin');
                                    hide_modal();
                                    load_table();
                                }
                            }
                        })
                    });
                }
            }
        })
        return false;
    })
</script>