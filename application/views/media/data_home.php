<?php
foreach ($data as $val){
    if ($val->media_type == 'image'){
        $media_content = '<img src="'.base_url('assets/upload/'.$val->media_url).'" style="max-width:100%">';
    } elseif ($val->media_type == 'sound'){
        $media_content = '<audio controls="">
                            <source src="../../assets/upload/'.$val->media_url.'" type="audio/ogg">
                            <source src="../../assets/upload/'.$val->media_url.'" type="audio/mpeg">
                                Your browser does not support the audio element.
                          </audio>';
    }
    echo '<tr class="row_'.$val->media_id.'">
            <td align="center"><input type="checkbox" name="media_id[]" value="'.$val->media_id.'"></td>
            <td align="center">'.$media_content.'</td>
            <td>'.$val->media_name.'</td>
            <td></td>
          </tr>';
}