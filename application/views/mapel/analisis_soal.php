<div class="col-md-5 no-padding">
    <select id="mdquiz_id" style="width:100%" onchange="load_table_form()">
        <?php
        if (!$quiz){
            echo '<option value="">Tidak ada data TES</option>';
        } else {
            foreach ($quiz as $val){
                echo '<option value="'.$val->quiz_id.'">'.$val->quiz_name.'</option>';
            }
        }
        ?>
    </select>
</div>
<div class="col-md-5">
    <div class="form-group">
        Standar Kelulusan : <strong class="std-lulus"><?php echo $tuntas; ?></strong>
    </div>
</div>
<div class="clearfix"></div>
<div class="panel panel-default" style="margin:10px 0;">
    <div class="panel-heading"><strong>Soal Nomor # <span class="nomor-soal">0</span></strong></div>
    <div class="panel-body isi-soal"></div>
    <div class="panel-body">
        <div class="panel panel-default">
            <div class="panel-body bg-gray-light">
                <strong>Jawaban :</strong> <strong class="nomor-pg">0</strong>
                <div class="pg-isi"></div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>Statistik</strong></div>
            <div class="panel-body">
                <table width="100%">
                    <tr>
                        <td width="300px">Jumlah Peserta Ujian / Tes</td>
                        <td width="10px">:</td>
                        <td class="jmlpes"><i class="fa fa-spin fa-refresh"></i></td>
                    </tr>
                    <tr>
                        <td>Batas Atas / Batas Bawah <code>BA / BB</code></td>
                        <td>:</td>
                        <td>
                            <strong style="display:inline-block;width:50px" class="ba"><i class="fa fa-spin fa-refresh"></i></strong> /
                            <strong style="display:inline-block;width:50px" class="bb"><i class="fa fa-spin fa-refresh"></i></strong>
                            <code>BA / BB = 27 &percnt; Jumlah Peserta </code>
                        </td>
                    </tr>
                    <tr>
                        <td>Jumlah Menjawab Atas / Bawah <code>JA / JB</code></td>
                        <td>:</td>
                        <td>
                            <strong style="display:inline-block;width:50px" class="ja"><i class="fa fa-spin fa-refresh"></i></strong> /
                            <strong style="display:inline-block;width:50px" class="jb"><i class="fa fa-spin fa-refresh"></i></strong>
                            <code>
                                PA = BA / JA &nbsp;&nbsp;&nbsp; PB = BB / JB
                            </code>
                        </td>
                    </tr>
                    <tr>
                        <td>PA / PB</td>
                        <td>:</td>
                        <td>
                            <strong style="display:inline-block;width:50px" class="pa"><i class="fa fa-spin fa-refresh"></i></strong> /
                            <strong style="display:inline-block;width:50px" class="pb"><i class="fa fa-spin fa-refresh"></i></strong>
                        </td>
                    </tr>
                    <tr>
                        <td>Daya Pembeda <code>DP</code></td>
                        <td>:</td>
                        <td>
                            <strong style="display:inline-block;width:50px" class="dayapembeda"><i class="fa fa-spin fa-refresh"></i></strong>
                            <code>
                                DP = PA - PB
                            </code>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Tingkat Kesulitan</td>
                        <td valign="top">:</td>
                        <td valign="top">
                            <strong style="display:inline-block;width:50px" class="sukar-int"><i class="fa fa-spin fa-refresh"></i></strong>
                            <strong style="display:inline-block;width:50px" class="sukar-txt"><i class="fa fa-spin fa-refresh"></i></strong>
                            <code>TK = ( Jumlah Menjawab Benar / Jumlah Peserta )</code><br>
                            <code>
                                TK &gt; 0.7 = MUDAH<br>
                                TK 0,3 s/d 0,7 = SEDANG<br>
                                TK &lt; 0,3 = SUSAH
                            </code>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Kesimpulan</td>
                        <td valign="top">:</td>
                        <td valign="top">
                            <strong style="display:inline-block;width:50px" class="statuslulus"><em><i class="fa fa-spin fa-refresh"></i></em></strong><br>
                            <code>
                                DP &lt; 0 = TIDAK BAIK<br>
                                DP 0 - 0,20 = JELEK<br>
                                DP 0,40 - 0,70 = BAIK<br>
                                DP 0,70 - 1,00 = BAIK SEKALI
                            </code>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Hasil Ujian (Kelompok Atas dan Kelompok Bawah)</strong></div>
    <div class="panel-body table-responsive">
        <table id="ModalFormTabel" width="100%" class="table table-bordered">
            <thead>
            <tr class="trh_1">
                <th rowspan="3" width="300px">Nama Siswa</th><th width="">Nomor Soal / Kunci Jawaban / Jawaban Peserta</th>
            </tr>
            <tr class="trh_2"></tr><tr class="trh_3"></tr>
            </thead>
            <tbody></tbody>
            <tfoot>
            <tr class="tf_1"><th>Jumlah Benar</th><th></th></tr>
            </tfoot>
        </table>

    </div>
</div>


<script>
    $('#MyModal .modal-title').html('<strong>ANALISIS BUTIR SOAL</strong>');
    $('#mdquiz_id').select2();
    function toStr(code) {
        char = {1:'A',2:'B',3:'C',4:'D',5:'E'}
        return char[code];
    }
    load_table_form();
    function load_table_form() {
        var quiz_id     = $('#mdquiz_id').val();
        //console.log(quiz_id);
        $('#ModalFormTabel tbody').html('<tr><td colspan="2"><i class="fa fa-spin fa-refresh"></i></td></tr>');
        $.ajax({
            url     : base_url + 'mapel/analisis_table',
            type    : 'POST',
            dataType: 'JSON',
            data    : { quiz_id : quiz_id, soal_id : '<?php echo $soal_id;?>', mapel_id : '<?php echo $mapel_id;?>' },
            success : function (dt) {
                if (dt.t == 0){
                    $('#ModalFormTabel thead .trh_1').html('<th width="300px">Nama Siswa</th><th width="">Nomor Soal / Kunci Jawaban / Jawaban Peserta</th>');
                    $('#ModalFormTabel thead .trh_2,#formTable thead .trh_3').html('');
                    $('#ModalFormTabel tbody').html('<tr><td>'+dt.msg+'</td></tr>');
                    $('#ModalFormTabel tfoot .tf_1').html('<th>Jumlah Benar</th><th colspan="'+dt.data_soal.length+1+'"></th>');
                    $('.ba,.bb,.std-lulus,.jmlbenar,.jmlpes,.dayapembeda,.sukar-int,#MyModal .nomor-soal,.nomor-pg,.ja,.jb,.pa,.pb').html(0);
                    $('.statuslulus,.sukar-txt,.pg-isi').html('');
                    $('.isi-soal').html('');
                } else if (dt.t == 1){
                    $('.isi-soal').html(dt.isi_soal);
                    $('.jmlbenar').html(dt.jml_jawab_benar);
                    $('.dayapembeda').html(dt.dayapembeda);
                    $('.jmlpes').html(dt.jml_pes);
                    $('.statuslulus').html(dt.ket);
                    $('.std-lulus').html(dt.soal_tuntas_min);
                    $('.sukar-int').html(dt.sukar_int);
                    $('.sukar-txt').html(dt.sukar_txt);
                    $('#MyModal .nomor-soal').html(dt.nomor_soal);
                    $('.nomor-pg').html(toStr(dt.nomor_pg));
                    $('.ba').html(dt.ba); $('.bb').html(dt.bb); $('.ja').html(dt.ja); $('.jb').html(dt.jb);
                    $('.pa').html(dt.pa); $('.pb').html(dt.pb);
                    $('.pg-isi').html(dt.pg_content);
                    $('#ModalFormTabel thead .trh_1').append('<th rowspan="3" width="60px">Jml Benar</th><th rowspan="3" width="60px">Nilai</th><th rowspan="3" width="100px">Ket</th>');
                    $('#ModalFormTabel thead .trh_1 th').eq(1).attr({'colspan':dt.data_soal.length});
                    $.each(dt.data_soal,function (i,v) {
                        $('#ModalFormTabel thead .trh_2').append('<th width="20px">'+v.soal_nomor+'</th>');
                        $('#ModalFormTabel thead .trh_3').append('<th>'+toStr(v.pg_nomor)+'</th>');
                    });
                    $('#ModalFormTabel tbody').html('<tr><td colspan="'+dt.data_soal.length+1+'">'+dt.msg+'</td></tr>');
                    $('#ModalFormTabel tfoot .tf_1').html('<th>Jumlah Benar</th><td colspan="'+dt.data_soal.length+'">'+dt.msg+'</td>');
                } else {
                    $('.isi-soal').html(dt.isi_soal);
                    $('.jmlbenar').html(dt.jml_jawab_benar);
                    $('.dayapembeda').html(dt.dayapembeda);
                    $('.jmlpes').html(dt.jml_pes);
                    $('.statuslulus').html(dt.ket);
                    $('.std-lulus').html(dt.soal_tuntas_min);
                    $('.sukar-int').html(dt.sukar_int);
                    $('.sukar-txt').html(dt.sukar_txt);
                    $('#MyModal .nomor-soal').html(dt.nomor_soal);
                    $('.nomor-pg').html(toStr(dt.nomor_pg));
                    $('.pg-isi').html(dt.pg_content);
                    $('.ba').html(dt.ba); $('.bb').html(dt.bb); $('.ja').html(dt.ja); $('.jb').html(dt.jb);
                    $('.pa').html(dt.pa); $('.pb').html(dt.pb);
                    $('#ModalFormTabel thead .trh_1').append('<th rowspan="3" width="60px">Jml Benar</th><th rowspan="3" width="60px">Nilai</th><th rowspan="3" width="100px">Ket</th>');
                    $('#ModalFormTabel thead .trh_1 th').eq(1).attr({'colspan':dt.data_soal.length});
                    $('#ModalFormTabel tfoot .tf_1').html('<tr><th>Jumlah Benar</th></tr>');
                    $.each(dt.data_soal,function (i,v) {
                        $('#ModalFormTabel thead .trh_2').append('<th width="20px">'+v.soal_nomor+'</th>');
                        $('#ModalFormTabel thead .trh_3').append('<th>'+toStr(v.pg_nomor)+'</th>');
                        $('#ModalFormTabel tfoot .tf_1').append('<th>'+v.jml_benar+'</th>');
                    });
                    $('#ModalFormTabel tbody').html(dt.html);
                }
            }
        });
    }
</script>