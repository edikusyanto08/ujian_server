<form id="modalForm" class="">
    <input type="hidden" name="mapel_id" value="<?php echo $mapel->mapel_id;?>">
    <div class="inputnya" style="margin-bottom: 10px">
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Tingkat</label>
                <input class="form-control" type="text" disabled value="Tingkat <?php echo $mapel->mapel_tingkat;?>">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Kompetensi Keahlian</label>
                <input class="form-control" type="text" disabled value="<?php if (isset($kk)){ echo $mapel->mapel_tingkat; } ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Mata Pelajaran</label>
                <input type="text" class="form-control" value="<?php echo $mapel->mapel_name;?>" disabled>
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group">
                <span class="input-group-btn">
                    <label class="btn btn-default" for="file"><i class="fa fa-folder-open"></i></label>
                </span>
                <label for="file" class="form-control file_label">Nama file</label>
                <span class="input-group-btn">
                    <button class="btn btn-default btn-upload" type="submit" disabled><i class="fa fa-play-circle"></i> Upload</button>
                </span>
                <input type="file" id="file" name="file" style="display:none">
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12" style="margin-top: 10px">
            <div class="pull-right">
                <a data-toggle="tooltip" title="Download Format" target="_blank" href="<?php echo base_url('mapel/download_format_soal/'.$mapel->mapel_id);?>" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download Format</a>
                <a data-toggle="tooltip" title="Batal" href="javascript:;" onclick="hide_modal();return false" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Batal</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="progressnya">
        <div class="col-md-12">
            <div class="progress">
                <div id="progressBar" class="progress-bar progress-bar-striped active" role="progressbar"
                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                    0%
                </div>
            </div>
        </div>
        <div class="col-md-12" style="margin-top:15px">
            <div class="alert alert-info"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('.progressnya').hide();
    $('#file').change(function () {
        $('.btn-upload').prop('disabled',true);
        var file_name   = $(this).val();
        var ext         = file_name.split(".");
        ext             = ext[ext.length - 1];
        if (ext != 'xlsx'){
            show_msg('File yang diizinkan adalah *.xlsx','error');
        } else {
            $('.btn-upload').prop('disabled',false);
            $('.file_label').html(file_name);
        }
    })
    $('#modalForm').submit(function () {
        $('.inputnya').hide();
        $('.progressnya').show();
        var formdata    = new FormData($('#modalForm')[0]);
        $.ajax({
            xhr     : function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e){
                    if(e.lengthComputable){
                        console.log('Bytes Loaded : ' + e.loaded);
                        console.log('Total Size : ' + e.total);
                        console.log('Persen : ' + (e.loaded / e.total));

                        var percent = Math.round((e.loaded / e.total) * 100);

                        $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
                    }
                });
                return xhr;
            },
            type        : 'POST',
            url         : base_url + 'mapel/import_bank_soal_submit',
            data        : formdata,
            processData : false,
            contentType : false,
            dataType    : 'JSON',
            success     : function(dt){
                if (dt.t == 0){
                    $('.file_label').html('Nama File');
                    $('#modalForm')[0].reset();
                    $('.inputnya,.progressnya').toggle();
                    show_msg(dt.msg,'error');
                    $('.btn-upload').prop('disabled',true);
                    $('#progressBar').attr('aria-valuenow', 0).css('width', '0%').text('0%');
                } else {
                    $('#progressBar').attr('aria-valuenow', 0).css('width', '0%').text('0%');
                    $('.alert-info').html('');
                    var total   = dt.data.length;
                    $.each(dt.data,function (i,v) {
                        soal_ke = parseInt(i);
                        soal_ke = soal_ke + 1;
                        $('.alert-info').html('Memproses soal ke '+soal_ke)
                        $.ajax({
                            url     : base_url + 'mapel/import_bank_soal_proses',
                            type    : 'POST',
                            dataType: 'JSON',
                            data    : { data : dt.data[i], mapel_id : dt.mapel_id },
                            cache   : false,
                            async   : false,
                            success : function (dtX) {
                                var percent = Math.round((i / total) * 100);
                                $('#progressBar').attr('aria-valuenow', percent).css('width', percent+'%').text(percent+'%');
                                if (i + 1 >= total){
                                    hide_modal();
                                    show_msg(dtX.msg);
                                    load_table();
                                }
                            }
                        });
                    })
                }
            }
        });
        return false;
    })
</script>