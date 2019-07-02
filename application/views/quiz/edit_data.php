<form id="modalForm" class="">
    <input type="hidden" name="quiz_id" value="<?php echo $data->quiz_id;?>">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Tahun Pelajaran</label>
            <input type="text" class="form-control frtapel" value="<?php echo $data->quiz_tapel;?>" disabled>
            <input type="hidden" name="tapel" id="frtapel" value="<?php echo $data->quiz_tapel;?>">
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">Jenis Penilaian</label>
            <select id="frjn_id" name="jn_id" style="width:100%">
                <?php
                foreach ($jn as $val){
                    echo '<option value="'.$val->jn_id.'">'.$val->jn_name.'</option>';
                }
                ?>
            </select>
        </div>
    </div><div class="clearfix"></div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label" for="quiz_start">Tanggal Mulai</label>
            <input type="text" name="quiz_start" id="quiz_start" class="form-control" value="<?php echo date('Y-m-d',strtotime($data->quiz_start));?>">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" for="quiz_start_jam">Jam Mulai</label>
            <input type="text" name="quiz_start_jam" id="quiz_start_jam" class="form-control" placeholder="00:00" value="<?php echo date('H:i',strtotime($data->quiz_start));?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label" for="quiz_end">Tanggal Berakhir</label>
            <input type="text" name="quiz_end" id="quiz_end" class="form-control" value="<?php echo date('Y-m-d',strtotime($data->quiz_end));?>">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" for="quiz_end_jam">Jam Berakhir</label>
            <input type="text" name="quiz_end_jam" id="quiz_end_jam" class="form-control" placeholder="00:00" value="<?php echo date('H:i',strtotime($data->quiz_end));?>">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" for="quiz_timer">Timer (menit)</label>
            <input type="number" name="quiz_timer" id="quiz_timer" min="10" max="99999" value="<?php echo $data->quiz_timer;?>" class="form-control">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" for="quiz_jml_soal">Max Soal</label>
            <input type="number" name="quiz_jml_soal" id="quiz_jml_soal" min="1" max="99999" value="<?php echo $data->quiz_jml_soal;?>" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label" for="">Acak Soal</label>
            <div>
                <label for="rs_ya"><input id="rs_ya" type="radio" name="quiz_random_soal" value="1" <?php if ($data->quiz_random_soal == 1){ echo 'checked'; } ?> > Ya</label>&nbsp;&nbsp;&nbsp;
                <label for="rs_no"><input id="rs_no" type="radio" name="quiz_random_soal" value="0" <?php if ($data->quiz_random_soal == 0){ echo 'checked'; } ?> > Tidak</label>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label" for="">Acak Pilihan Ganda</label>
            <div>
                <label for="pg_ya"><input id="pg_ya" type="radio" name="quiz_random_pg" value="1" <?php if ($data->quiz_random_pg == 1){ echo 'checked'; } ?> > Ya</label>&nbsp;&nbsp;&nbsp;
                <label for="pg_no"><input id="pg_no" type="radio" name="quiz_random_pg" value="0" <?php if ($data->quiz_random_pg == 0){ echo 'checked'; } ?> > Tidak</label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Mata Pelajaran</label>
            <select id="frmapel_id" name="mapel_id[]" style="width: 100%" multiple>
                <?php
                foreach ($mapel as $val){
                    $select = '';
                    if (in_array($val->mapel_id,$data->mapel)){
                        $select = 'selected';
                    }
                    echo '<option '.$select.' value="'.$val->mapel_id.'">'.$val->mapel_tingkat.' - '.$val->mapel_name.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
        <button style="margin-right:5px" type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('#quiz_start,#quiz_end').datepicker({
        format      : 'yyyy-mm-dd',
        autoclose   : true
    })
    $('#frjn_id').val(<?php echo $data->jn_id;?>);
    $('#frjn_id,#frmapel_id').select2();
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $.ajax({
            url     : base_url + 'quiz/edit_data_submit',
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