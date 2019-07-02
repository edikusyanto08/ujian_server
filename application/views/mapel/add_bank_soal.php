<form id="modalForm" class="">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Tingkat</label>
            <input type="text" class="form-control" value="Tingkat <?php echo $tingkat;?>" disabled>
            <input type="hidden" name="tingkat" value="<?php echo $tingkat;?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Kompetensi Keahlian</label>
            <input type="text" class="form-control" value="<?php if (isset($kk)){ echo $kk->kk_name; }?>" disabled>
            <input type="hidden" name="kk_id" value="<?php if (isset($kk)){ echo $kk->kk_id; }?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Mata Pelajaran</label>
            <input type="text" class="form-control" value="<?php echo $mapel->mapel_name;?>" disabled>
            <input type="hidden" name="mapel_id" value="<?php echo $mapel->mapel_id;?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Jenis Soal</label>
            <select name="soal_type" id="frsoal_type" style="width:100%">
                <option value="pg">Pilihan Ganda</option>
                <option value="uraian">Uraian</option>
                <option value="singkat">Jawaban Singkat</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Max Score</label>
            <input name="soal_score" class="form-control" type="number" min="1" max="999" value="1">
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Isi Soal</label>
            <textarea name="soal_content" id="soal_content" class="form-control"></textarea>
        </div>
    </div>
    <div class="col-md-12">
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
        <button style="margin-right:5px" type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
    $('#frsoal_type').select2();
    var editor = $('#soal_content').summernote({
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
            url     : base_url + 'mapel/add_bank_soal_submit',
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
                    MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                }
            }
        })
        return false;
    })
</script>