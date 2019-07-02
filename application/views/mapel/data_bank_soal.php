<?php
if ($data){
    foreach ($data as $val){
        if ($val->soal_type == 'pg'){ $btn_analisis = '<a onclick="show_modal(this);return false" data-toggle="tooltip" title="Analisis Soal" href="'.base_url('mapel/analisis_soal/'.$val->soal_id).'" class="btn btn-xs btn-default btn-flat btn-block"><i class="fa fa-bar-chart"></i> Analisis Soal</a>'; } else { $btn_analisis = ''; }
        echo '<tr class="row_'.$val->soal_id.'">
                <td align="center"><input type="checkbox" name="soal_id[]" value="'.$val->soal_id.'"></td>
                <td align="center">'.$val->soal_nomor.'</td>
                <td class="content_'.$val->soal_id.'">'.$val->soal_content.'</td>
                <td align="center" class="score_'.$val->soal_id.'">'.$val->soal_score.'</td>
                <td align="center" class="type_'.$val->soal_id.'">'.$val->soal_type.'</td>
                <td>';
        echo '<div class="dataPG_'.$val->soal_id.'">';
        if ($val->pg){
            $dataPG['data'] = $val->pg;
            $this->load->view('mapel/data_pg',$dataPG);
        }
        echo '</div>';
        echo '  </td>
                <td>
                    '.$btn_analisis.'
                    <a onclick="show_modal(this);return false" data-toggle="tooltip" title="Edit Soal" href="'.base_url('mapel/edit_bank_soal/'.$val->soal_id).'" class="btn btn-xs btn-primary btn-flat btn-block"><i class="fa fa-pencil"></i> Edit Soal</a>
                    <a onclick="show_modal(this);return false" data-toggle="tooltip" title="Tambah Jawaban" href="'.base_url('mapel/add_pg/'.$val->soal_id).'" class="btn btn-xs btn-success btn-flat btn-block"><i class="fa fa-plus"></i> Tambah Jawaban</a>
                    <a onclick="delete_data(this);return false" data-toggle="tooltip" title="Hapus data" data-id="'.$val->soal_id.'" href="javascript:;" class="btn btn-xs btn-danger btn-flat btn-block"><i class="fa fa-trash"></i> Hapus Soal</a> 
                </td>
              </tr>';
    }
}
?>
<script>
    $('#dataTable tbody input:checkbox').click(function () {
        var length = $('#dataTable tbody input:checkbox:checked').length;
        if (length == 0){
            $('.btn-delete').addClass('disabled');
        } else {
            $('.btn-delete').removeClass('disabled');
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
    MathJax.Hub.Config({
        tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
    });
    MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
</script>
