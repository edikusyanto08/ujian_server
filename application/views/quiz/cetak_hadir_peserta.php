<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DAFTAR HADIR PESERTA</title>


    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/cetak.min.css');?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/font-awesome/css/font-awesome.min.css');?>">
    <!-- Fonts-->
    <link rel="stylesheet" href="<?php echo base_url('assets/fonts/tinos/tinos-v12-latin-regular.css');?>">

    <style>
        *{
            font-size: 11pt !important; font-family: 'Tinos', serif !important;
        }
        .it-grid, .it-grid tr:nth-child(2n){
            background: none;
        }
        .detail td span{
            display:inline-block;width:100%;
        }
        .info td span{
            display: inline-block;border-bottom: dotted 1px #000; width:30px;
        }
        .nomor{
            font-size:9pt !important;
        }
    </style>

</head>
<body>
<?php
if (!$data){
    echo 'tidak ada data';
} else {
    foreach ($data as $valRuang){
        if (!$valRuang->peserta) {
            echo 'TIDAK ADA PESERTA';
        } else {
            if (count($valRuang->peserta) == 0) {
                echo 'TIDAK ADA PESERTA';
            } else {
                $dtPeserta  = $valRuang->peserta;
                $dtPeserta  = array_chunk($dtPeserta,20,true);
                $nomor      = 1;
                $page = 1;
                foreach ($dtPeserta as $valPage){
                    ?>
                    <div class="page">
                        <div style="margin-left:70px">
                            <table width="100%" class="">
                                <tr>
                                    <td width="60px" align="center" valign="middle">
                                        <img src="<?php echo base_url('assets/'.$sch->sch_logo_dinas);?>" width="100%">
                                    </td>
                                    <td align="center" valign="middle">
                                        <strong style="font-size:14pt !important;text-transform:uppercase">
                                            DAFTAR HADIR PESERTA<br>
                                            <?php echo $valRuang->jn_name.' ('.$valRuang->jn_sing.')';?><br>
                                            TAHUN PELAJARAN <?php echo $valRuang->server_tapel.'/'.($valRuang->server_tapel+1); ?>
                                        </strong>
                                    </td>
                                    <td width="60px" align="center" valign="middle">
                                        <img src="<?php echo base_url('assets/'.$sch->sch_logo);?>" width="80%">
                                    </td>
                                </tr>
                            </table>
                            <div style="border-bottom:solid 2px #000;border-top:solid 1px #000;height:4px;margin:10px auto"></div>
                            <table width="90%" class="detail">
                                <tr>
                                    <td width="100px">Nama Sekolah</td>
                                    <td width="5px">:</td>
                                    <td width=""><span><?php echo $sch->sch_name;?></span></td>
                                    <td width="100px">Kode</td>
                                    <td width="5px">:</td>
                                    <td width="100px"><span><?php echo $sch->sch_kode;?></span></td>
                                </tr>
                                <tr>
                                    <td>Kabupaten</td>
                                    <td>:</td>
                                    <td><span>INDRAMAYU</span></td>
                                    <td>Kode</td>
                                    <td>:</td>
                                    <td><span>18</span></td>
                                </tr>
                                <tr>
                                    <td>Propinsi</td>
                                    <td>:</td>
                                    <td><span>JAWA BARAT</span></td>
                                    <td>Kode</td>
                                    <td>:</td>
                                    <td><span>02</span></td>
                                </tr>
                                <tr>
                                    <td>Ruang</td>
                                    <td>:</td>
                                    <td><span><?php echo $valRuang->sr_name;?></span></td>
                                    <td>Jumlah Peserta</td>
                                    <td>:</td>
                                    <td><span><?php echo count($valRuang->peserta);?> Siswa</span></td>
                                </tr>
                                <tr>
                                    <td>Mata Pelajaran</td>
                                    <td>:</td>
                                    <td colspan="4"><span>&nbsp;</span></td>
                                </tr>
                                <tr>
                                    <td>Hari / Tanggal</td>
                                    <td>:</td>
                                    <td><span>&nbsp;</span></td>
                                    <td>Pukul</td>
                                    <td>:</td>
                                    <td><span>&nbsp;</span></td>
                                </tr>
                            </table>
                            <div style="height:10px"></div>
                            <table width="100%" class="it-grid">
                                <thead>
                                <tr>
                                    <th width="30px" rowspan="2">No</th>
                                    <th width="" colspan="5">Nomor Peserta</th>
                                    <th width="" rowspan="2">Nama Peserta</th>
                                    <th width="20px" rowspan="2">L/ P</th>
                                    <th width="100px" colspan="2" rowspan="2">Tanda Tangan</th>
                                </tr>
                                <tr>
                                    <th width="20px">00</th>
                                    <th width="20px">00</th>
                                    <th width="40px">0000</th>
                                    <th width="40px">0000</th>
                                    <th width="20px">0</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($valPage as $valPes){
                                    //$nopes = explode("-",$valPes->sis_nopes);
                                    $mods = ($nomor % 2);
                                    echo '<tr>
                                        <td align="center">'.$nomor.'</td>';
                                    if (strlen($valPes->sis_nopes) > 0){
                                        $nopes = explode("-",$valPes->sis_nopes);
                                        if (count($nopes) > 0){
                                            foreach ($nopes as $valNopes){
                                                echo '<td align="center">'.$valNopes.'</td>';
                                            }
                                        } else {
                                            echo '<td></td><td></td><td></td><td></td><td></td>';
                                        }
                                    } else {
                                        echo '<td></td><td></td><td></td><td></td><td></td>';
                                    }
                                    //foreach ($nopes as $valnopes){
                                    //    echo '<td align="center">'.$valnopes.'</td>';
                                    //}
                                    //echo    '<td align="center">'.$valPes->sis_nopes.'</td>';
                                    echo    '<td>'.ucwords(strtolower($valPes->sis_fullname)).'</td>
                                        <td align="center">'.$valPes->sis_sex.'</td>';
                                    if ($mods == 1){
                                        echo '<td rowspan="2" align="left" valign="top" class="nomor">'.$nomor.'</td>
                                          <td rowspan="2" align="left" valign="bottom" class="nomor">'.($nomor+1).'</td>';
                                    }
                                    echo '</tr>';
                                    $nomor++;
                                }
                                ?>
                                </tbody>
                            </table>
                            <div style="height:20px"></div>
                            <div style="width:50%;float: right">
                                <table width="100%" style="">
                                    <tr>
                                        <td colspan="2" align="right">Kandanghaur, ............................</td>
                                    </tr>
                                    <tr>
                                        <td width="50%" align="center">
                                            Pengawas 1,
                                            <div style="height:70px"></div>
                                            ...........................
                                        </td>
                                        <td width="50%" align="center">
                                            Pengawas 2,
                                            <div style="height:70px"></div>
                                            ...........................
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="width:50%;float: left">
                                <table width="100%" class="it-grid info">
                                    <tr>
                                        <td width="70%">Jumlah Peserta Seharusnya</td>
                                        <td>: <span><?php echo count($valRuang->peserta);?></span> Peserta</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Peserta Yang Hadir</td>
                                        <td>: <span>&nbsp;</span> Peserta</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Peserta Yang Tidak Hadir</td>
                                        <td>: <span>&nbsp;</span> Peserta</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div style="font-size:8pt !important;position:absolute;bottom:10px;right:50px;text-align:right">
                            Daftar Hadir Peserta <?php echo $valRuang->jn_name.' ('.$valRuang->jn_sing.')';?>
                            Tahun Pelajaran <?php echo $valRuang->server_tapel.'/'.($valRuang->server_tapel+1); ?>
                        </div>
                    </div>
                    <?php
                    $page++;
                }
            }
        }
    }
}
?>

</body>
</html>