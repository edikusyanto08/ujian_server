<?php
if ($data){
    foreach ($data as $val){
        echo '<tr class="row_'.$val->sr_id.'">
                <td align="center"><input type="checkbox" name="sr_id[]" value="'.$val->sr_id.'"></td>
                <td align="center">'.$val->server_kode.'</td>
                <td>'.$val->sr_name.'</td>
                <td align="center">'.$val->jml_sesi.'</td>
                <td align="center">'.$val->jml_pes.'</td>
                <td>
                    <a onclick="show_modal(this);return false" data-toggle="tooltip" title="Rubah Ruang" href="'.base_url('server/edit_ruang/'.$val->sr_id).'" class="btn-xs btn btn-primary btn-flat"><i class="fa fa-pencil"></i></a>
                    <a onclick="load_page(this);return false" data-toggle="tooltip" data-target="server" title="Data Peserta" href="'.base_url('server/peserta/'.$val->sr_id).'" class="btn-xs btn btn-primary btn-flat"><i class="fa fa-user-circle"></i></a>
                    <a onclick="load_page(this);return false" data-toggle="tooltip" data-target="server" title="Data Pengawas" href="'.base_url('server/pengawas/'.$val->sr_id).'" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-user-secret"></i></a> 
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
