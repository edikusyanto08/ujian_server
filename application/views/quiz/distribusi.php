<form id="modalForm" class="">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="30px">No</th>
                <th>Mata Pelajaran</th>
                <th width="50px">Bank Soal</th>
                <th width="50px">Max Soal</th>
                <th width="50px">Jml Siswa</th>
                <th width="50px">Distri busi</th>
                <th width="100px">Progress</th>
                <th width="50px">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $nomor = 1;
            foreach ($data->mapel as $valMapel){
                $persen = '0%';
                if ($valMapel->dist > 0){
                    if ($valMapel->jml_siswa > 0){
                        $persen = ( $valMapel->dist / $valMapel->jml_siswa ) * 100;
                        $persen = round($persen).'%';
                    }
                }
                if ($valMapel->jml_siswa == 0){
                    $btn = '<a onclick="return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Tidak ada data peserta" class="start_'.$valMapel->mapel_id.' btn btn-default btn-flat btn-block btn-xs"><i class="fa fa-stop"></i></a> ';
                    $btnHapus = '<a onclick="delete_dist(this);return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Hapus Distribusi" class="delete_'.$valMapel->mapel_id.' btn btn-danger btn-flat btn-block btn-xs" style="display:none"><i class="fa fa-trash"></i></a>';
                } elseif ($valMapel->dist < $valMapel->jml_siswa && $valMapel->bank >= $data->quiz_jml_soal){
                    $btn = '<a onclick="mulai_dist(this);return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Mulai" class="start_'.$valMapel->mapel_id.' btn btn-default btn-flat btn-block btn-xs"><i class="fa fa-play"></i></a> ';
                    $btnHapus = '<a onclick="delete_dist(this);return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Hapus Distribusi" class="delete_'.$valMapel->mapel_id.' btn btn-danger btn-flat btn-block btn-xs" style="display:none"><i class="fa fa-trash"></i></a>';
                } elseif ($valMapel->bank < $data->quiz_jml_soal){
                    $btn = '<a onclick="return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Bank soal kurang" class="start_'.$valMapel->mapel_id.' btn btn-default btn-flat btn-block btn-xs"><i class="fa fa-stop"></i></a> ';
                    $btnHapus = '<a onclick="delete_dist(this);return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Hapus Distribusi" class="delete_'.$valMapel->mapel_id.' btn btn-danger btn-flat btn-block btn-xs" style="display:none"><i class="fa fa-trash"></i></a>';
                } else {
                    $btn = '<a onclick="return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Bank soal kurang atau sudah terdistribusi" class="start_'.$valMapel->mapel_id.' btn btn-default btn-flat btn-block btn-xs" style="display:none"><i class="fa fa-stop"></i></a> ';
                    $btnHapus = '<a onclick="delete_dist(this);return false" qm-id="'.$valMapel->qm_id.'" mapel-id="'.$valMapel->mapel_id.'" quiz-id="'.$data->quiz_id.'" data-toggle="tooltip" title="Hapus Distribusi" class="delete_'.$valMapel->mapel_id.' btn btn-danger btn-flat btn-block btn-xs"><i class="fa fa-trash"></i></a>';
                }


                echo '<tr>
                        <td align="center">'.$nomor.'</td>
                        <td>'.$valMapel->mapel_name.'</td>
                        <td align="center">'.$valMapel->bank.'</td>
                        <td align="center">'.$data->quiz_jml_soal.'</td>
                        <td align="center">'.$valMapel->jml_siswa.'</td>
                        <td align="center" class="jml_'.$valMapel->mapel_id.'">'.$valMapel->dist.'</td>
                        <td align="center" class="persen_'.$valMapel->mapel_id.'">'.$persen.'</td>
                        <td>
                            '.$btn.$btnHapus.'
                        </td>
                      </tr>';
                $nomor++;
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <button type="button" onclick="hide_modal();return false" class="btn-submit pull-right btn btn-warning btn-sm"><i class="fa fa-close"></i> Tutup</button>&nbsp;
    </div>
    <div class="clearfix"></div>
</form>

<script>
    $('[data-toggle="tooltip"]').tooltip();
    function delete_dist(ob) {
        var qm_id       = $(ob).attr('qm-id');
        var mapel_id    = $(ob).attr('mapel-id');
        var konfir      = confirm('Anda yakin ingin menghapus distribusi soal yang ada ?');
        if (!qm_id){
            show_msg('Invalid parameter MAPEL TES');
        } else if (konfir){
            $('.delete_'+mapel_id).addClass('btn-info').removeClass('btn-danger');
            $('.delete_'+mapel_id).html('<i class="fa fa-spin fa-refresh"></i>').attr({'onclick':'return false'});
            $.ajax({
                url     : base_url + 'quiz/delete_dist',
                type    : 'POST',
                dataType: 'JSON',
                data    : { qm_id : qm_id },
                success : function (dt) {
                    if (dt.t == 0){
                        $('.delete_'+mapel_id).addClass('btn-danger').removeClass('btn-info');
                        $('.delete_'+mapel_id).html('<i class="fa fa-trash"></i>').attr({'onclick':'delete_dist(this);return false'});
                        show_msg(dt.msg,'error');
                    } else {
                        $('.jml_'+mapel_id).html('0');
                        $('.delete_'+mapel_id).addClass('btn-danger').removeClass('btn-info');
                        $('.delete_'+mapel_id).html('<i class="fa fa-trash"></i>').attr({'onclick':'delete_dist(this);return false'}).hide();
                        $('.start_'+mapel_id).html('<i class="fa fa-play"></i>').attr({'onclick':'mulai_dist(this);return false'}).show();
                        show_msg('Berhasil menghapus distribusi soal');
                        load_table();
                    }
                }
            })
        }
    }
    function mulai_dist(ob) {
        var quiz_id     = $(ob).attr('quiz-id');
        var mapel_id    = $(ob).attr('mapel-id');
        var qm_id       = $(ob).attr('qm-id');
        var konfirm     = confirm('Anda yakin ingin memulai distribusi soal ke peserta?\nProses ini membutuhkan waktu yang cukup lama.\nDiharapkan anda bisa bersabar sampai proses selesai.')
        if (!quiz_id){
            show_msg('Invalid parameter TES','error');
        } else if (!mapel_id){
            show_msg('Invalid parameter MAPEL','error');
        } else if (!qm_id){
            show_msg('Invalid parameter MAPEL TES','error');
        } else if (konfirm){
            $('.start_'+mapel_id).removeClass('btn-default').addClass('btn-info');
            $('.start_'+mapel_id).html('<i class="fa fa-spin fa-refresh"></i>').attr({'onclick':'return false'});
            $.ajax({
                url     : base_url + 'quiz/mulai_dist',
                type    : 'POST',
                dataType: 'JSON',
                data    : { quiz_id : quiz_id, mapel_id : mapel_id, qm_id : qm_id },
                success : function (dt) {
                    if (dt.t == 0){
                        $('.start_'+mapel_id).removeClass('btn-info').addClass('btn-default');
                        $('.start_'+mapel_id).html('<i class="fa fa-play"></i>').attr({'onclick':'mulai_dist(this);return false'});
                        show_msg(dt.msg,'error');
                    } else {
                        $('.persen_'+mapel_id).html('0%');
                        var total   = dt.data.length;
                        var jml_soal= $('.jml_'+mapel_id).text();
                        jml_soal    = parseInt(jml_soal);
                        $.each(dt.data,function (i,v) {
                            $.ajax({
                                url     : base_url + 'quiz/mulai_dist_proses',
                                type    : 'POST',
                                dataType: 'JSON',
                                data    : { sis_id : dt.data[i].sis_id, quiz_id : quiz_id, mapel_id : mapel_id, qm_id : qm_id },
                                async   : false,
                                cache   : false,
                                success : function (dta) {
                                    var percent = Math.round((i / total) * 100);
                                    $('.persen_'+mapel_id).html(percent+'%');
                                    jml_soal = jml_soal + 1;
                                    $('.jml_'+mapel_id).html(jml_soal);
                                    if (i + 1 >= total){
                                        $('.start_'+mapel_id).removeClass('btn-info').addClass('btn-default');
                                        $('.start_'+mapel_id).html('<i class="fa fa-stop"></i>').attr({'onclick':'return false'}).hide();
                                        $('.delete_'+mapel_id).show();
                                        load_table();
                                    }
                                }
                            });
                        });
                        //$('.start_'+mapel_id).removeClass('btn-info').addClass('btn-success');
                        //$('.start_'+mapel_id).html('<i class="fa fa-play"></i>').attr({'onclick':'mulai_dist(this);return false'});
                    }
                }
            });
        }
    }
    $('#quiz_start,#quiz_end').datepicker({
        format      : 'yyyy-mm-dd',
        autoclose   : true
    })
    $('#frtapel,.frtapel').val($('#tapel').val());
    $('#frjn_id,#frmapel_id').select2();
    $('#modalForm').submit(function () {
        $('#MyModal .modal-body .btn-submit').prop({'disabled':true});
        $('#MyModal .modal-body .btn-success').html('<i class="fa fa-spin fa-refresh"></i> Submit');
        $.ajax({
            url     : base_url + 'quiz/add_data_submit',
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