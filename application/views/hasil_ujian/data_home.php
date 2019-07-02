<?php
if ($data){
    foreach ($data as $val){
        echo '<tr>
                <td align="center">'.$val->sis_nopes.'</td>
                <td>'.$val->sis_fullname.'</td>
                <td align="center">'.$val->sis_kelas.'</td>
                <td align="center">'.$val->qn_nilai.'</td>
              </tr>';
    }
}