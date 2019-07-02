<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>KARTU SOAL</title>

    <!-- jQuery 3 -->
    <script src="<?php echo base_url('assets/bower_components/jquery/dist/jquery.min.js');?>"></script>
    <!-- MathJax -->
    <script type="text/javascript" async src="<?php echo base_url('assets/bower_components/MathJax-master/MathJax.js?config=TeX-AMS_CHTML');?>"></script>

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
<?php
if ($data){
    $page   = array_chunk($data,2,true);
    $nopage = 1; $nomorSoal = 1;
    foreach ($page as $valPage){
        ?>
        <div class="page">
            <div style="margin-left:50px">
                <?php
                if ($nopage == 1){
                    ?>
                    <table width="100%" class="it-grid">
                        <tr>
                            <td width="100px" align="center" valign="middle">
                                <img src="<?php echo base_url('assets/logo-dinas-bw.jpg');?>" width="50px">
                            </td>
                            <td align="center" valign="middle" colspan="2">
                                <strong>
                                    PIMPINAN DAERAH MUHAMMADIYAH INDRAMAYU<br>
                                    SEKOLAH MENENGAH KEJURUAN (SMK) MUHAMMADIYAH<br>
                                    KANDANGHAUR KAB. INDRAMAYU
                                </strong>
                            </td>
                            <td width="100px" align="center" valign="middle">
                                <img src="<?php echo base_url('assets/logo-bw.png');?>" width="50px">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                FORMULIR
                            </td>
                            <td width="100px"><span style="font-size:10px !important;">Kode Dok </span></td>
                            <td><span style="font-size:10px !important;">F.KUR.03.02</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <strong>KARTU SOAL</strong>
                            </td>
                            <td><span style="font-size:10px !important;">Tanggal</span></td>
                            <td><span style="font-size:10px !important;"><?php echo $this->conv->tglIndo(date('Y-m-d'));?></span></td>
                        </tr>
                    </table>
                    <table width="100%" style="margin:20px 0">
                        <tr>
                            <td width="200px">Satuan Pendidikan</td>
                            <td>: SMK Muhammadiyah Kandanghaur</td>
                        </tr>
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td>: <?php echo $mapel->mapel_name;?></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>: <?php echo $mapel->mapel_tingkat;?></td>
                        </tr>
                        <tr>
                            <td>Tahun Pelajaran</td>
                            <td>: <?php echo $tapel.'/'.($tapel+1);?></td>
                        </tr>
                        <tr>
                            <td>Jenis Tagihan</td>
                            <td>: <?php echo $JN->jn_name;?></td>
                        </tr>
                    </table>
                    <?php
                }
                foreach ($valPage as $valSoal){
                    if ($valSoal->soal_type == 'pg'){
                        ?>
                        <table width="100%" class="it-grid" style="margin-bottom:20px">
                            <tr>
                                <td width="200px" rowspan="2" valign="top"> Kompetensi Dasar : </td>
                                <td width="90px">Kunci : <strong id="kunci<?php echo $valSoal->soal_id;?>">0</strong></td>
                                <td rowspan="2" valign="top">Buku Sumber :</td>
                            </tr>
                            <tr>
                                <td>Nomor : <strong><?php echo $nomorSoal;?></strong></td>
                            </tr>
                            <tr>
                                <td valign="top">Materi :</td>
                                <td rowspan="2" colspan="2" valign="top" style="padding:10px !important;">
                                    <?php
                                    echo $valSoal->soal_content;
                                    echo '<ol type="A">';
                                    $nomorPg = 1; $jawaban = '';
                                    foreach ($valSoal->pg as $valPg){
                                        echo '<li>'.$valPg->pg_content.'</li>';
                                        if ($valPg->pg_is_right == 1){
                                            $jawaban = $this->conv->toStr($nomorPg);
                                        }
                                        $nomorPg++;
                                    }
                                    echo '</ol>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">Indikator Soal :</td>
                            </tr>
                        </table>
                        <script>
                            $('#kunci<?php echo $valSoal->soal_id;?>').html('<?php echo $jawaban;?>');
                        </script>
                        <?php
                    } else {
                        ?>
                        <table width="100%" class="it-grid" style="margin-bottom:20px">
                            <tr>
                                <td width="200px" valign="top"> Kompetensi Dasar : </td>
                                <td width="100px">Nomor : <strong><?php echo $nomorSoal;?></strong></td>
                                <td valign="top">Buku Sumber :</td>
                            </tr>
                            <tr>
                                <td valign="top">Materi :</td>
                                <td rowspan="2" colspan="2" valign="top" style="padding:10px !important;">
                                    <?php
                                    echo $valSoal->soal_content;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top">Indikator Soal :</td>
                            </tr>
                        </table>
                        <table width="100%" class="it-grid" style="margin-bottom:20px">
                            <tr>
                                <td colspan="3" align="center"><strong>Pedoman Penskoran</strong></td>
                            </tr>
                            <tr>
                                <td width="50px" align="center">Nomor</td>
                                <td align="center">Jawaban / Aspek yang dinilai</td>
                                <td align="center" width="50px">Skor</td>
                            </tr>
                            <?php
                            if ($valSoal->pg){
                                foreach ($valSoal->pg as $valPg){
                                    echo '<tr>';
                                    echo '<td align="center">'.$nomorSoal.'</td>
                                          <td>'.$valPg->pg_content.'</td>
                                          <td align="center">'.$valPg->pg_score.'</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                            <tr>
                                <td colspan="3" align="center">Skor Maksimum <strong><?php echo $valSoal->soal_score;?></strong></td>
                            </tr>
                        </table>
                        <?php
                    }
                    $nomorSoal++;
                }
                ?>
            </div>
        </div>
        <?php
        $nopage++;
    }
}
?>
<script>
    MathJax.Hub.Config({
        tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
    });
    MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
</script>
</body>
</html>