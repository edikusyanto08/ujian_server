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
        .page li{
            padding-top:0;padding-bottom:0;
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
                        TATA TERTIB PENGAWAS<br>
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
        <ol type="1">
            <li>
                Ruang pengawas USBN
                <ol type="a">
                    <li>Dua puluh lima (25) menit sebelum ujian dimulai pengawas ruang telah hadir di ruang pengawas <?php echo $JN->jn_sing;?>.</li>
                    <li>Pengawas ruang menerima penjelasan dan pengarahan dari ketua penyelenggara <?php echo $JN->jn_sing;?>.</li>
                    <li>Pengawas ruang menerima bahan <?php echo $JN->jn_sing;?> untuk ruang yang akan diawasi, berupa naskah soal <?php echo $JN->jn_sing;?>, LJ<?php echo $JN->jn_sing;?>, amplop LJ<?php echo $JN->jn_sing;?>, daftar hadir, dan berita acara pelaksanaan <?php echo $JN->jn_sing;?>, serta lem.</li>
                    <li>Pengawas ruang mendatangani Pakta Integritas.</li>
                </ol>
            </li>
            <li>
                Ruang USBN
                <ol type="a">
                    <li>Pengawas ruang dilarang membawa alat komunikasi/elektronik ke dalam ruang <?php echo $JN->jn_sing;?>.</li>
                    <li>
                        Pengawas masuk ke dalam ruang <?php echo $JN->jn_sing;?> lima belas (15) menit sebelum waktu pelaksanaan ujian untuk:
                        <ul>
                            <li>memeriksa kesiapan ruang ujian, meminta peserta untuk memasuki ruang ujian dengan menunjukkan kartu peserta, dan menempati tempat duduk sesuai nomor yang telah ditentukan;</li>
                            <li>memastikan setiap peserta tidak membawa tas, buku atau catatan lain, alat komunikasi elektronik, kalkulator dan sebagainya ke dalam ruang kecuali alat tulis yang akan digunakan;</li>
                            <li>membacakan tata tertib;</li>
                            <li>meminta peserta <?php echo $JN->jn_sing;?> menandatangani daftar hadir;</li>
                            <li>membagikan LJ<?php echo $JN->jn_sing;?> kepada peserta dan memandu serta memeriksa pengisian identitas peserta (nomor ujian, nama, tanggal lahir, dan tanda tangan);</li>
                            <li>memastikan peserta telah mengisi identitas dengan benar;</li>
                            <li>setelah seluruh peserta selesai mengisi identitas, pengawas ruang membuka amplop soal, memeriksa kelengkapan bahan ujian, dan meyakinkan bahwa amplop tersebut dalam keadaan baik dan tertutup rapat (disegel), disaksikan oleh peserta ujian; dan</li>
                            <li>membagikan naskah soal dengan cara meletakkan di atas meja peserta dalam posisi tertutup (terbalik). Peserta ujian tidak diperkenankan menyentuhnya sampai tanda waktu dimulai.</li>
                        </ul>
                    </li>
                    <li>
                        Setelah tanda waktu mengerjakan dimulai, pengawas ruang:
                        <ul>
                            <li>mempersilakan peserta untuk mengecek kelengkapan soal;</li>
                            <li>mempersilakan peserta untuk mulai mengerjakan soal; dan</li>
                            <li>mengingatkan peserta agar terlebih dahulu membaca petunjuk cara menjawab soal.</li>
                        </ul>
                    </li>
                    <li>Kelebihan naskah soal selama <?php echo $JN->jn_sing;?> berlangsung tetap disimpan di ruang ujian dan pengawas ruang tidak diperbolehkan membacanya.</li>
                    <li>
                        Selama <?php echo $JN->jn_sing;?> berlangsung, pengawas ruang wajib:
                        <ul>
                            <li>menjaga ketertiban dan ketenangan suasana sekitar ruang <?php echo $JN->jn_sing;?>;</li>
                            <li>memberi peringatan dan sanksi kepada peserta yang melakukan kecurangan; dan</li>
                            <li>melarang orang lain memasuki ruang <?php echo $JN->jn_sing;?>.</li>
                        </ul>
                    </li>
                    <li>Pengawas ruang dilarang memberi isyarat, petunjuk, dan bantuan apapun kepada peserta berkaitan dengan jawaban dari soal yang diujikan.</li>
                    <li>Lima menit sebelum waktu ujian selesai, pengawas ruang memberi peringatan kepada peserta <?php echo $JN->jn_sing;?> bahwa waktu tinggal lima menit.</li>
                    <li>
                        Setelah waktu <?php echo $JN->jn_sing;?> selesai, pengawas ruang:
                        <ul>
                            <li>mempersilakan peserta untuk berhenti mengerjakan soal;</li>
                            <li>mempersilakan peserta meletakkan naskah soal dan LJ<?php echo $JN->jn_sing;?> di atas meja dengan rapi;</li>
                            <li>mengumpulkan LJ<?php echo $JN->jn_sing;?> dan naskah soal;</li>
                            <li>menghitung jumlah LJ<?php echo $JN->jn_sing;?> sama dengan jumlah peserta;</li>
                            <li>mempersilakan peserta meninggalkan ruang ujian; dan</li>
                            <li>menyusun secara urut LJ<?php echo $JN->jn_sing;?> dari nomor peserta terkecil dan memasukkannya ke dalam amplop LJ<?php echo $JN->jn_sing;?> disertai dengan dua lembar daftar hadir peserta, dua lembar berita acara pelaksanaan, kemudian ditutup dan dilem serta ditandatangani oleh pengawas ruang <?php echo $JN->jn_sing;?> di dalam ruang ujian.</li>
                        </ul>
                    </li>
                    <li>Pengawas Ruang USBN menyerahkan LJ<?php echo $JN->jn_sing;?> dan naskah soal <?php echo $JN->jn_sing;?> kepada Panitia <?php echo $JN->jn_sing;?> disertai dengan satu lembar daftar hadir peserta dan satu lembar berita acara pelaksanaan <?php echo $JN->jn_sing;?>; dan</li>
                    <li>Pengawas yang melanggar tata tertib diberi teguran, peringatan oleh kepala sekolah dan/atau sanksi sesuai dengan peraturan yang berlaku.</li>
                </ol>
            </li>
        </ol>
        <div style="float:right;margin-top:20px;width:300px;text-align:center;">
            Kandanghaur, Maret 2019<br><br>
            Ketua Panitia
            <div style="height:80px"></div>
            ( <strong><?php echo $sch->sch_kepsek; ?></strong> )
        </div>
        <div style="font-size:8pt !important;position:absolute;bottom:10px;right:50px;text-align:right">
            Tata Tertib Pengawas <?php echo $JN->jn_name.' ('.$JN->jn_sing.')';?>
            Tahun Pelajaran <?php echo $tapel.'/'.($tapel + 1); ?>
        </div>
    </div>
    <div class="page">
        <table width="100%" class="">
            <tr>
                <td width="60px" align="center" valign="middle">
                    <img src="<?php echo base_url('assets/'.$sch->sch_logo_dinas);?>" width="100%">
                </td>
                <td align="center" valign="middle">
                    <strong style="font-size:14pt !important;text-transform:uppercase">
                        TATA TERTIB PESERTA<br>
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
        <ol type="1">
            <li>Peserta <?php echo $JN->jn_sing;?> memasuki ruangan setelah tanda masuk dibunyikan, yakni lima belas (15) menit sebelum <?php echo $JN->jn_sing;?> dimulai.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> yang terlambat hadir hanya diperkenankan mengikuti <?php echo $JN->jn_sing;?> setelah mendapat izin dari ketua panitia <?php echo $JN->jn_sing;?> tanpa diberi perpanjangan waktu.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> dilarang membawa alat komunikasi elektronik dan kalkulator.</li>
            <li>Tas, buku, dan catatan dalam bentuk apapun dikumpulkan di depan kelas di samping pengawas ruang.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> membawa alat tulis dan kartu peserta ujian.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> mengisi daftar hadir menggunakan pulpen yang disediakan oleh pengawas ruang.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> mengisi identitas pada LJ<?php echo $JN->jn_sing;?> secara lengkap dan benar.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> yang memerlukan penjelasan cara pengisian identitas pada LJ<?php echo $JN->jn_sing;?> dapat bertanya kepada pengawas ruang dengan cara mengacungkan tangan terlebih dahulu.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> mulai mengerjakan soal setelah ada tanda waktu mulai ujian.</li>
            <li>Selama <?php echo $JN->jn_sing;?> berlangsung, peserta <?php echo $JN->jn_sing;?> hanya dapat meninggalkan ruangan dengan izin dan pengawasan dari pengawas ruang.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> yang memperoleh naskah soal yang cacat atau rusak, pengerjaan soal tetap dilakukan sambil menunggu penggantian naskah soal.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> yang meninggalkan ruangan setelah membaca soal dan tidak kembali lagi sampai tanda selesai dibunyikan, dinyatakan telah selesai menempuh/mengikuti <?php echo $JN->jn_sing;?> mata pelajaran yang terkait.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> yang telah selesai mengerjakan soal sebelum waktu <?php echo $JN->jn_sing;?> berakhir tidak diperbolehkan meninggalkan ruangan sebelum berakhirnya waktu ujian.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> berhenti mengerjakan soal setelah ada waktu ujian berakhir dan meletakkan lembar jawaban serta naskah soal di atas meja masing- masing.</li>
            <li>
                Selama <?php echo $JN->jn_sing;?> berlangsung, peserta dilarang:
                <ol type="a">
                    <li>menanyakan jawaban soal kepada siapa pun;</li>
                    <li>bekerja sama dengan peserta lain;</li>
                    <li>memberi atau menerima bantuan dalam menjawab soal;</li>
                    <li>memperlihatkan pekerjaan sendiri kepada peserta lain atau melihat pekerjaan peserta lain;</li>
                    <li>membawa naskah soal <?php echo $JN->jn_sing;?> dan LJ<?php echo $JN->jn_sing;?> keluar dari ruang ujian; dan</li>
                    <li>menggantikan atau digantikan oleh orang lain.</li>
                </ol>
            </li>
            <li>Meninggalkan ruang <?php echo $JN->jn_sing;?> dengan tertib dan tenang setelah pengawas ruang ujian mengumpulkan dan menghitung lembar jawaban dan naskah soal sesuai dengan jumlah peserta <?php echo $JN->jn_sing;?>.</li>
            <li>Peserta <?php echo $JN->jn_sing;?> yang melanggar tata tertib ujian, diberi peringatan/teguran oleh pengawas ruang <?php echo $JN->jn_sing;?> dan dicatat dalam berita acara <?php echo $JN->jn_sing;?> sebagai salah satu bahan pertimbangan kelulusan.</li>
        </ol>
        <div style="float:right;margin-top:20px;width:300px;text-align:center;">
            Kandanghaur, Maret 2019<br><br>
            Ketua Panitia
            <div style="height:80px"></div>
            ( <strong><?php echo $sch->sch_kepsek; ?></strong> )
        </div>
        <div style="font-size:8pt !important;position:absolute;bottom:10px;right:50px;text-align:right">
            Tata Tertib Pengawas <?php echo $JN->jn_name.' ('.$JN->jn_sing.')';?>
            Tahun Pelajaran <?php echo $tapel.'/'.($tapel + 1); ?>
        </div>
    </div>

</body>
</html>