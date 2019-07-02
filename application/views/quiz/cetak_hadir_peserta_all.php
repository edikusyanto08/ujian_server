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
    $jdUSBNPraktek = array(
        0 => array(
            'mapel_name' => 'Pendidikan Agama Islam dan Budi Pekerti',
            'tanggal' => '2019-03-04 00:00:00'
        ),
        1 => array(
            'mapel_name' => 'Bahasa Indonesia',
            'tanggal' => '2019-03-04 00:00:00'
        ),
        2 => array(
            'mapel_name' => 'Bahasa Inggris',
            'tanggal' => '2019-03-04 00:00:00'
        ),
        3 => array(
            'mapel_name' => 'Pendidikan Jasmani Olahraga dan Kesehatan',
            'tanggal' => '2019-03-04 00:00:00'
        ),
        4 => array(
            'mapel_name' => 'Seni Budaya',
            'tanggal' => '2019-03-04 00:00:00'
        ),
        5 => array(
            'mapel_name' => 'Prakarya dan Kewirausahaan',
            'tanggal' => '2019-03-04 00:00:00'
        )
    );
    $jdUNBK = array(
        0 => array(
            'mapel_name' => 'Bahasa Indonesia',
            'tanggal' => '2019-03-25 00:00:00'
        ),
        1 => array(
            'mapel_name' => 'Matematika',
            'tanggal' => '2019-03-26 00:00:00'
        ),
        2 => array(
            'mapel_name' => 'Bahasa Inggris',
            'tanggal' => '2019-03-27 00:00:00'
        ),
        3 => array(
            'mapel_name' => 'Teori Kejuruan',
            'tanggal' => '2019-03-28 00:00:00'
        )
    );
    $jdUKK = array(
        0 => array(
            'mapel_name' => 'UKK',
            'tanggal' => '2019-04-13 00:00:00'
        ),
        1 => array(
            'mapel_name' => 'UKK',
            'tanggal' => '2019-04-14 00:00:00'
        )
    );

    foreach ($data as $valPes){
        $nopage = 1;
        $npesunbk = '';
        $foto   = '';
        if (strlen($valPes->sis_nopes) > 0){
            $npesunbk = explode("-",$valPes->sis_nopes);
            $npesunbk = 'K'.implode($npesunbk).'.jpg';
            $foto     = base_url('assets/foto_siswa/'.$npesunbk);
        }
        ?>
        <div class="page">
            <div style="margin-left:70px;position:relative">
                <div>
                    <img src="<?php echo base_url('assets/logo.png');?>" width="50px" style="position:absolute;left:10px;">
                    <div style="margin-left:80px;text-align:center;font-weight:bold;">
                        PIMPINAN DAERAH MUHAMMADIYAH INDRAMAYU<br>
                        SEKOLAH MENENGAH KEJURUAN (SMK) MUHAMMDIYAH KANDANGHAUR<br>
                        DAFTAR HADIR PESERTA US, USBN, UNBK, dan UKK<br>
                        TAHUN PELAJARAN <?php echo $tapel.'/'.($tapel+1);?>
                    </div>
                </div>
                <div style="margin:10px auto;height:5px;border-top:solid 1px #000;border-bottom:solid 3px #000"></div>
                <table width="100%">
                    <tr>
                        <td width="100px">Nama Peserta</td>
                        <td width="10px">:</td>
                        <td><?php echo $valPes->sis_fullname;?></td>
                        <td width="2cm" rowspan="4">
                            <div style="border:solid 1px #000;width:2cm;height:2.8cm;overflow:hidden;">
                                <img src="<?php echo $foto;?>" width="100%">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">No. Peserta</td>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $valPes->sis_nopes;?></td>
                    </tr>
                    <tr>
                        <td valign="top">NISN / NIS</td>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $valPes->sis_nisn;?> / <?php echo $valPes->sis_nis;?></td>
                    </tr>
                    <tr>
                        <td valign="top">Kelas</td>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $valPes->sis_kelas;?></td>
                    </tr>
                </table>
                <div style="height:20px"></div>
                <table width="100%" class="it-grid it-cetak">
                    <thead>
                    <tr>
                        <th width="40px">No</th>
                        <th width="120px">Tanggal</th>
                        <th width="">Mata Pelajaran</th>
                        <th width="150px" colspan="2">Tanda Tangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td align="center"><strong>I</strong></td>
                        <td colspan="4"><strong>Ujian Sekolah Praktik (US Praktik)</strong></td>
                    </tr>
                    <?php
                    $nomor = 1;
                    foreach ($jdUSBNPraktek as $valMapel){
                        $mods = ($nomor % 2);
                        if ($mods == 1){
                            $td = ' <td rowspan="2" valign="top" align="left">'.$nomor.'</td>
                                    <td rowspan="2" valign="bottom" align="left">'.($nomor + 1).'</td>';
                        } else {
                            $td = '';
                        }
                        echo '<tr>
                                <td align="center">'.$nomor.'</td>
                                <td></td>
                                <td>'.$valMapel['mapel_name'].'</td>
                                '.$td.'
                              </tr>';
                        $nomor++;
                    }
                    ?>
                    <tr>
                        <td align="center"><strong>II</strong></td>
                        <td colspan="4"><strong>Ujian Sekolah Berstandar Nasional (USBN)</strong></td>
                    </tr>
                    <?php
                    //mapel usbn
                    foreach ($valPes->jadwal as $valJad){
                        $mods = ($nomor % 2);
                        if ($mods == 1){
                            $td = ' <td rowspan="2" valign="top" align="left">'.$nomor.'</td>
                                    <td rowspan="2" valign="bottom" align="left">'.($nomor + 1).'</td>';
                        } else {
                            $td = '';
                        }
                        echo '<tr>
                                <td align="center">'.$nomor.'</td>
                                <td align="center">'.$this->conv->tglIndo(date('Y-m-d',strtotime($valJad->quiz_start))).'</td>
                                <td>'.$valJad->mapel_name.'</td>
                                '.$td.'
                              </tr>';
                        $nomor++;
                    }
                    ?>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="text-align:right;font-size:8px !important;position:absolute;bottom:10px;right:50px">
                Daftar Hadir Peserta US, USBN, UNBK, UKK - <?php echo $valPes->sis_fullname;?>. Halaman <?php echo $nopage;?>
            </div>
        </div>
        <?php $nopage++; ?>
        <div class="page">
            <div style="margin-left:70px">
                <table width="100%" class="it-grid it-cetak">
                    <thead>
                    <tr>
                        <th width="40px">No</th>
                        <th width="120px">Tanggal</th>
                        <th width="">Mata Pelajaran</th>
                        <th width="150px" colspan="2">Tanda Tangan</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td align="center"><strong>III</strong></td>
                        <td colspan="4"><strong>Ujian Nasional Berbasis Komputer (UNBK)</strong></td>
                    </tr>
                    <?php
                    $nomor = 27;
                    foreach ($jdUNBK as $valMapel){
                        $mods = ($nomor % 2);
                        if ($mods == 1){
                            $td = ' <td rowspan="2" valign="top" align="left">'.$nomor.'</td>
                                    <td rowspan="2" valign="bottom" align="left">'.($nomor + 1).'</td>';
                        } else {
                            $td = '';
                        }
                        echo '<tr>
                                <td align="center">'.$nomor.'</td>
                                <td align="center">'.$this->conv->tglIndo(date('Y-m-d',strtotime($valMapel['tanggal']))).'</td>
                                <td>'.$valMapel['mapel_name'].'</td>
                                '.$td.'
                              </tr>';
                        $nomor++;
                    }
                    ?>
                    <tr>
                        <td align="center"><strong>IV</strong></td>
                        <td colspan="4"><strong>Ujian Kompetensi Keahlian (UKK)</strong></td>
                    </tr>
                    <?php
                    foreach ($jdUKK as $valMapel){
                        $mods = ($nomor % 2);
                        if ($mods == 1){
                            $td = ' <td rowspan="2" valign="top" align="left">'.$nomor.'</td>
                                    <td rowspan="2" valign="bottom" align="left">'.($nomor + 1).'</td>';
                        } else {
                            $td = '';
                        }
                        echo '<tr>
                                <td align="center">'.$nomor.'</td>
                                <td align="center">'.$this->conv->tglIndo(date('Y-m-d',strtotime($valMapel['tanggal']))).'</td>
                                <td>'.$valMapel['mapel_name'].'</td>
                                '.$td.'
                              </tr>';
                        $nomor++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align:right;font-size:8px !important;position:absolute;bottom:10px;right:50px">
                Daftar Hadir Peserta US, USBN, UNBK, UKK - <?php echo $valPes->sis_fullname;?>. Halaman <?php echo $nopage;?>
            </div>
        </div>
        <?php
    }
}
?>

</body>
</html>