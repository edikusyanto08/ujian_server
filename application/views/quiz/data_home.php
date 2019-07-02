<?php
if ($data){
    foreach ($data as $val){
        echo '<tr class="row_'.$val->quiz_id.'">
                <td align="center"><input type="checkbox" name="quiz_id[]" value="'.$val->quiz_id.'"></td>
                <td align="center">'.$val->quiz_tapel.'</td>
                <td align="center">'.$val->jn_sing.'</td>
                <td>'.$val->quiz_name.'</td>
                <td>';
        echo '<ol type="a">';
        foreach ($val->mapel as $valMapel){
            echo '<li>'.$valMapel->mapel_name.'
                        <a href="javascript:;" data-mapel="'.$valMapel->mapel_id.'" class="btn btn-xs btn-primary pull-right" onclick="print_data(this);return false" data-type="kartu"><i class="fa fa-print"></i></a>
                  </li>';
        }
        echo '</ol>';
        echo '  </td>
                <td align="center">'.$val->quiz_timer.' menit</td>
                <td align="center">'.$this->conv->tglIndo(date('Y-m-d',strtotime($val->quiz_start))).'&nbsp;@'.date('H:i',strtotime($val->quiz_start)).'</td>
                <td align="center">'.$this->conv->tglIndo(date('Y-m-d',strtotime($val->quiz_end))).'&nbsp;@'.date('H:i',strtotime($val->quiz_end)).'</td>
                <td align="center">'.$val->quiz_jml_soal.'</td>
                <td>
                    <a onclick="show_modal(this);return false" data-toggle="tooltip" title="Rubah data" href="'.base_url('quiz/edit_data/'.$val->quiz_id).'" class="btn btn-xs btn-primary btn-flat btn-block"><i class="fa fa-pencil"></i> Rubah Data</a> 
                    <a onclick="show_modal(this);return false" data-toggle="tooltip" title="Distribusikan soal" href="'.base_url('quiz/distribusi/'.$val->quiz_id).'" class="btn-xs btn btn-warning btn-flat"><i class="fa fa-play"></i> Distribusikan Soal</a>
                    <a onclick="load_page(this);return false" data-toggle="tooltip" title="Hasil Tes" data-target="quiz" href="'.base_url('quiz/hasil/'.$val->quiz_id).'" class="btn-xs btn btn-flat btn-block btn-default"><i class="fa fa-check"></i> Hasil Tes</a> 
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
</script>
