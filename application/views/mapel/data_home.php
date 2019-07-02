<?php
if ($data){
    foreach ($data as $val){
        echo '<tr class="row_'.$val->mapel_id.'">
                <td align="center"><input type="checkbox" name="mapel_id[]" value="'.$val->mapel_id.'"></td>
                <td>'.$val->mapel_name.'</td>
                <td>'.$val->kk_name.'</td>
                <td align="center">'.$val->jml_soal.'</td>
                <td>
                    <a onclick="load_page(this);return false" data-toggle="tooltip" data-target="mapel" title="Bank Soal" href="'.base_url('mapel/bank_soal/'.$val->mapel_id).'" class="btn btn-xs btn-success btn-flat btn-block"><i class="fa fa-pencil"></i> Bank Soal</a> 
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
