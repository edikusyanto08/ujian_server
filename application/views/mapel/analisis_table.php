<?php
if ($data){
    foreach ($data as $valPes){
        $class = '';
        $class = 'style="background:#beffb6"';
        if ($valPes->batas == 'bawah'){ $class = 'style="background:#fea693"'; }
        echo '<tr '.$class.'>
                <td>'.$valPes->sis_fullname.'</td>';
        if ($valPes->hasil){
            foreach ($valPes->hasil as $valHasil){
                echo '<td align="center">'.$this->conv->toStr($valHasil->pg_nomor).'</td>';
            }
        }
        echo '  <td align="center">'.$valPes->jml_benar.'</td>
                <td align="center">'.$valPes->nilai_akhir.'</td>
                <td align="center">'.$valPes->ket.'</td>';
        echo '</tr>';
    }
}