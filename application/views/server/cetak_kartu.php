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
    <script type="text/javascript" src="<?php echo base_url('assets/bower_components/jquery/dist/jquery.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bower_components/qrcode/jquery-qrcode-0.15.0.min.js');?>"></script>
    <script>
        function render_qr(elem,text) {
            var options = {
                // render method: 'canvas', 'image' or 'div'
                render: 'canvas',

                // version range somewhere in 1 .. 40
                minVersion: 1,
                maxVersion: 40,

                // error correction level: 'L', 'M', 'Q' or 'H'
                ecLevel: 'L',

                // offset in pixel if drawn onto existing canvas
                left: 0,
                top: 0,

                // size in pixel
                size: 60,

                // code color or image element
                fill: '#000',

                // background color or image element, null for transparent background
                background: null,

                // content
                text: text,

                // corner radius relative to module width: 0.0 .. 0.5
                radius: 0,

                // quiet zone in modules
                quiet: 0,

                // modes
                // 0: normal
                // 1: label strip
                // 2: label box
                // 3: image strip
                // 4: image box
                mode: 0,

                mSize: 0.1,
                mPosX: 0.5,
                mPosY: 0.5,

                label: 'no label',
                fontname: 'sans',
                fontcolor: '#000',

                image: null
            };
            $(elem).qrcode(options);
        }
    </script>
    <style>
        *{
            font-size: 10pt !important;
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
        .fotopes{
            float: left;
        }
        .qrwrapper{
            border:solid 1px #000; float: left; padding:5px; margin-left:10px;
        }
    </style>

</head>
<body>
<?php
if (!$data){
    echo 'tidak ada data';
} else {
    foreach ($data as $valServer){
        if ($valServer->ruang){
            foreach ($valServer->ruang as $valRuang){
                if ($valRuang->peserta){
                    $dataPage   = array_chunk($valRuang->peserta,8,true);
                    foreach ($dataPage as $valPage){
                        echo '<div class="page">';
                        $row = array_chunk($valPage,2,true);
                        foreach ($row as $valRow){
                            echo '<table width="100%">';
                            echo '<tr>';
                            foreach ($valRow as $pes){
                                $img = explode("-",$pes->sis_nopes);
                                $img = 'K'.implode("",$img);
                                ?>
                                <td width="50%" style="padding:5px">
                                    <table width="100%" frame="border" rules="all">
                                        <tr>
                                            <td align="center" valign="middle">
                                                <img src="<?php echo base_url('assets/tutwuri_warna.png');?>" width="38px" style="float:left;margin:5px;">
                                                <strong>
                                                    KARTU PESERTA<br>
                                                    <?php echo strtoupper($jn->jn_name); ?><br>
                                                    TAHUN PELAJARAN <?php echo $valServer->server_tapel.'/'.($valServer->server_tapel+1); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table width="100%">
                                                    <tr>
                                                        <td width="100px">Nomor Peserta</td>
                                                        <td width="5px">:</td>
                                                        <td><?php echo $pes->sis_nopes;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nama Peserta</td>
                                                        <td>:</td>
                                                        <td><?php echo $pes->sis_fullname;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Server / Ruang</td>
                                                        <td>:</td>
                                                        <td><?php echo $valServer->server_kode.' / '.$valRuang->sr_name;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Username</td>
                                                        <td>:</td>
                                                        <td><strong><?php echo $pes->sis_username;?></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Password</td>
                                                        <td>:</td>
                                                        <td><strong><?php echo $pes->sis_password;?></strong></td>
                                                    </tr>
                                                </table>
                                                <table width="100%">
                                                    <tr>
                                                        <td width="50%">
                                                            <?php
                                                            if (file_exists(FCPATH.'assets/foto_siswa/'.$img)){
                                                                echo '<img class="fotopes" style="width:2cm" src="'.base_url('assets/foto_siswa/'.$img).'.jpg">';
                                                            } elseif(file_exists(FCPATH.'assets/foto_siswa/'.$pes->sis_nis.'.jpg')) {
                                                                echo '<img class="fotopes" style="width:2cm" src="'.base_url('assets/foto_siswa/'.$pes->sis_nis.'.jpg').'">';
                                                            } else {
                                                                echo '<div class="fotopes"></div>';
                                                            }
                                                            ?>
                                                            <div class="qr_<?php echo $pes->sis_username;?> qrwrapper"></div>
                                                            <script>
                                                                render_qr('.qr_<?php echo $pes->sis_username;?>','<?php echo $pes->sis_username;?>');
                                                            </script>
                                                        </td>
                                                        <td align="center" valign="top">
                                                            Kandanghaur, ....................<br>
                                                            Kepala Sekolah
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <?php
                                if (count($valRow) < 2){
                                    ?>
                                    <td width="50%" style="padding:5px">

                                    </td>
                                    <?php
                                }
                            }
                            echo '</tr>';
                            echo '</table>';
                        }
                        echo '</div>';
                    }
                }
            }
        }
    }
}
?>

</body>
</html>