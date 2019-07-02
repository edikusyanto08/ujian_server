<?php
if ($data){
    foreach ($data as $val){
        echo '<tr class="row_'.$val->rm_id.'">
                <td align="center"><input type="checkbox" name="rm_id[]" value="'.$val->rm_id.'"></td>
                <td align="center">'.$val->sis_nopes.'</td>
                <td>'.$val->sis_fullname.'</td>
                <td align="center">'.$val->sis_kelas.'</td>
                <td align="center">'.$val->sr_name.'</td>
                <td align="center">Sesi '.$val->rm_sesi.'</td>
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
