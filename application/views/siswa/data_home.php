<?php
if ($data){
    foreach ($data as $val){
        echo '<tr class="row_'.$val->sis_id.'">
                <td align="center"><input type="checkbox" name="sis_id[]" value="'.$val->sis_id.'"></td>
                <td align="center">'.$val->sis_nis.'</td>
                <td align="center">'.$val->sis_nopes.'</td>
                <td>'.$val->sis_fullname.'</td>
                <td align="center">'.$val->sis_sex.'</td>
                <td>'.$val->sis_username.'</td>
                <td align="center">'.$val->sis_password.'</td>
                <td>'.$val->kk_name.'</td>
                <td align="center">'.$val->sis_tingkat.'</td>
                <td align="center">'.$val->sis_kelas.'</td>
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
    })
</script>
