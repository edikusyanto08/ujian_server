<?php
foreach ($data as $valPes){
    echo '<tr class="row_'.$valPes->sis_id.'">
            <td align="center"><input type="checkbox" name="sis_id[]" value="'.$valPes->sis_id.'"></td>
            <td align="center">'.$valPes->sis_nopes.'</td>
            <td>'.$valPes->sis_fullname.'</td>
            <td align="center">'.$valPes->sis_kelas.'</td>
            <td align="center">'.$valPes->jml_soal.'</td>
            <td align="center">'.$valPes->jml_hasil.'</td>
            <td align="center">'.$valPes->jml_skor.'</td>
         </tr>';
}
?>
<script>
    $('#dataTable tbody input:checkbox').click(function () {
        var dtlen = $('#dataTable tbody input:checkbox:checked').length;
        if (dtlen == 0){
            $('.btn-delete').addClass('disabled');
        } else {
            $('.btn-delete').removeClass('disabled');
        }
    })
</script>
