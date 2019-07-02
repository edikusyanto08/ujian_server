<?php
if ($data){
    foreach ($data as $val){
        echo '<tr class="row_'.$val->server_id.'">
                <td align="center"><input type="checkbox" name="server_id[]" value="'.$val->server_id.'"></td>
                <td align="center">'.$val->server_kode.'</td>
                <td>'.$val->server_name.'</td>
                <td align="center">'.$val->server_jml_client.'</td>
                <td align="center">'.$val->jml_ruang.'</td>
                <td align="center">'.$val->jml_sesi.'</td>
                <td align="center">'.$val->jml_pes.'</td>
                <td>
                    <a onclick="show_modal(this);return false" data-toggle="tooltip" title="Rubah data" href="'.base_url('server/edit_data/'.$val->server_id).'" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></a> 
                    <a onclick="load_page(this);return false" data-toggle="tooltip" data-target="server" title="Data Ruang" href="'.base_url('server/ruang/'.$val->server_id).'" class="btn-xs btn btn-primary btn-flat"><i class="fa fa-building"></i></a> 
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
