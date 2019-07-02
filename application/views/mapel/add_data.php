<form id="modalForm" class="">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Tingkat</label>
            <select id="fr_tingkat" style="width:100%" name="tingkat">
                <?php
                for($i = 7; $i <= 12; $i++){
                    echo '<option value="'.$i.'">Tingkat '.$i.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">Kompetensi Keahlian</label>
            <select id="fr_kk_id" style="width:100%" name="kk_id">
                <?php
                if ($kk){
                    foreach ($kk as $val){
                        echo '<option value="'.$val->kk_id.'">'.$val->kk_name.'</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Kelompok Mapel</label>
            <select id="fr_mapel_group" name="mapel_group" style="width:100%">
                <option value="A">Muatan Nasional</option>
                <option value="B">Muatan Kewilayahan</option>
                <option value="C1">Dasar Bidang Keahlian</option>
                <option value="C2">Dasar Program Keahlian</option>
                <option value="C3">Kompetensi Keahlian</option>
                <option value="MULOK">Muatan Lokal</option>
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">Nama Mata Pelajaran</label>
            <input type="text" name="mapel_name" class="form-control" value="">
        </div>
    </div>
    <div class="col-md-12">
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
        <button style="margin-right:5px" type="submit" class="btn-submit pull-right btn btn-success btn-sm"><i class="fa fa-floppy-o"></i> Submit</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('#fr_kk_id,#fr_tingkat,#fr_mapel_group').select2();
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