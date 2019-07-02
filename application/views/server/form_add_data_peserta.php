<?php
if ($data){
    foreach ($data as $val){
        echo '<tr class="row_'.$val->sis_id.'">
                <td align="center"><input type="checkbox" name="sis_id[]" value="'.$val->sis_id.'"></td>
                <td align="center">'.$val->sis_nopes.'</td>
                <td>'.$val->sis_fullname.'</td>
                <td align="center">'.$val->sis_sex.'</td>
                <td align="center">'.$val->sis_kelas.'</td>
              </tr>';
    }
}
?>
<script>
    $('#modalTable tbody input:checkbox').click(function () {
        var dtlength = $('#modalTable tbody input:checkbox:checked').length;
        $('.jmlpes').text(dtlength);
    });
    $('[data-toggle="tooltip"]').tooltip();
</script>
