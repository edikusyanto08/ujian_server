<form id="modalForm" class="">
    <input type="hidden" name="pg_id" value="<?php echo $data->pg_id;?>">
    <input type="hidden" name="soal_id" value="<?php echo $data->soal_id;?>">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Mata Pelajaran</label>
            <input type="text" class="form-control" value="<?php echo $mapel->mapel_name;?>" disabled>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Skor</label>
            <input class="form-control" type="number" name="pg_score" min="0" max="<?php echo $soal->soal_score;?>" value="<?php echo $data->pg_score;?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Jawaban Benar</label>
            <div class="clearfix">
                <label for="ya"><input type="radio" name="pg_is_right" id="ya" value="1" <?php if ($data->pg_is_right == 1){ echo 'checked'; };?> > Ya</label>&nbsp;&nbsp;&nbsp;
                <label for="tidak"><input type="radio" name="pg_is_right" id="tidak" value="0" <?php if ($data->pg_is_right == 0){ echo 'checked'; };?> > Tidak</label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Isi Soal</label>
            <div class="panel panel-default">
                <div class="panel-body"><?php echo $soal->soal_content;?></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Isi Jawaban</label>
            <textarea name="pg_content" id="pg_content" class="form-control"><?php echo $data->pg_content;?></textarea>
        </div>
    </div>
    <div class="col-md-12">
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
        <button style="margin-right:5px" type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    var editor = $('#pg_content').summernote({
        dialogsInBody   : true,
        height          : 200,
        shortcuts       : false,
        callbacks       : {
            onImageUpload: function(files) {
                //console.log(editor);
                sendFile(files[0]);
            }
        }

    });
    function sendFile(file) {
        data = new FormData();
        data.append("file", file);
        data.append("mapel_id",'<?php echo $mapel->mapel_id;?>');
        $.ajax({
            dataType    : 'JSON',
            data        : data,
            type        : "POST",
            url         : base_url + 'image/upload_img',
            cache       : false,
            contentType : false,
            processData : false,
            success     : function(dt) {
                if (dt.t == 0){
                    show_msg(dt.msg,'error');
                } else {
                    var uri = dt.url;
                    editor.summernote('insertImage', uri, dt.file_name);
                }
            }
        });
    }
    //$('#fr_tapel,#fr_tingkat,#fr_kk_id').select2();
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $.ajax({
            url     : base_url + 'mapel/edit_pg_submit',
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
                    $('.pg_'+dtA.id).html(dtA.content);
                    $('.score_'+dtA.id).html(dtA.score);
                    MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                }
            }
        })
        return false;
    })
</script>