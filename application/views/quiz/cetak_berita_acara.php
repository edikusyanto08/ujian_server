<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>BERITA ACARA</title>


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
        .titik2{
            display:inline-block;width:100px;border-bottom:dotted 1px #000;
        }
        p{
            line-height:30px; text-align: justify-all;
        }
        .cetak tr td{
            height:30px;
        }
    </style>

</head>
<body>

    <div class="page">
        <table width="100%" class="">
            <tr>
                <td width="60px" align="center" valign="middle">
                    <img src="<?php echo base_url('assets/'.$sch->sch_logo_dinas);?>" width="100%">
                </td>
                <td align="center" valign="middle">
                    <strong style="font-size:14pt !important;text-transform:uppercase">
                        BERITA ACARA<br>
                        <?php echo $JN->jn_name.' ('.$JN->jn_sing.')';?><br>
                        TAHUN PELAJARAN <?php echo $tapel.'/'.($tapel + 1); ?>
                    </strong>
                </td>
                <td width="60px" align="center" valign="middle">
                    <img src="<?php echo base_url('assets/'.$sch->sch_logo);?>" width="80%">
                </td>
            </tr>
        </table>
        <div style="border-bottom:solid 2px #000;border-top:solid 1px #000;height:4px;margin:10px auto"></div>
        <div style="margin-top:20px;text-align:justify">
            <p>
                Pada hari ini <span class="titik2">&nbsp;</span> tanggal <span class="titik2" style="width:200px">&nbsp;</span> bulan <span class="titik2" style="width:150px">&nbsp;</span> tahun <span class="titik2">&nbsp;</span>,
                di <?php echo strtoupper($sch->sch_name); ?> telah diselenggarakan <?php echo $JN->jn_name.' Berbasis Komputer ('.$JN->jn_sing.'BK)'; ?>, untuk mata pelajaran <span class="titik2" style="width:300px">&nbsp;</span>
                dari pukul <span class="titik2">&nbsp;</span> sampai dengan pukul <span class="titik2">&nbsp;</span>
            </p>
        </div>
        <table width="100%" class="cetak">
            <tr>
                <td width="30px">1. </td>
                <td width="250px">Nama Sekolah</td>
                <td width="5px">:</td>
                <td width=""><span><?php echo $sch->sch_name;?></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Alamat</td>
                <td>:</td>
                <td><span><?php echo $sch->sch_address;?></span></td>
            </tr>
            <tr>
                <td></td><td>Ruang</td><td>:</td><td><span class="titik2" style="width:120px">&nbsp;</span></td>
            </tr>
            <tr>
                <td></td><td>Jumlah Peserta Seharusnya</td><td>:</td><td><span class="titik2" style="width:120px">&nbsp;</span> Orang</td>
            </tr>
            <tr>
                <td></td><td>Jumlah Peserta Yang Tidak Hadir</td><td>:</td><td><span class="titik2" style="width:120px">&nbsp;</span> Orang</td>
            </tr>
            <tr>
                <td></td><td>Jumlah Peserta Yang Hadir</td><td>:</td><td><span class="titik2" style="width:120px">&nbsp;</span> Orang, Yakni :</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">
                    <span class="titik2" style="width:100%">&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">
                    <span class="titik2" style="width:100%">&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">
                    <span class="titik2" style="width:100%">&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3"><span class="titik2" style="width:100%">&nbsp;</span></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3"><span class="titik2" style="width:100%">&nbsp;</span></td>
            </tr>
            <tr>
                <td>2.</td>
                <td colspan="3">Catatan Selama Pelaksanaan <?php echo $JN->jn_name;?> Berbasis Komputer</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3"><span class="titik2" style="width:100%">&nbsp;</span></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3"><span class="titik2" style="width:100%">&nbsp;</span></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3"><span class="titik2" style="width:100%">&nbsp;</span></td>
            </tr>
        </table>
        <p>
            Demikian berita acara ini dibuat dengan sesungguhnya.
        </p>
        <div style="float:right;margin-top:20px;width:300px;text-align:center;">
            Kandanghaur, ........................................<br><br>
            Yang membuat berita acara
            <div style="height:80px"></div>
            ( .................................................. )
        </div>
        <div style="font-size:8pt !important;position:absolute;bottom:10px;right:50px;text-align:right">
            Berita Acara <?php echo $JN->jn_name.' ('.$JN->jn_sing.')';?>
            Tahun Pelajaran <?php echo $tapel.'/'.($tapel + 1); ?>
        </div>
    </div>


</body>
</html>