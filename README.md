# ujian_server
Server Ujian SMK Muhammadiyah Kandanghaur
Digunakan untuk memanage ujian secara online pada sekolah

TODO LIST :
1. Edit file .htaccess
   a. ganti RewriteBase /ujian_server/ menjadi RewriteBase /direktori_aplikasi/
   b. ganti RewriteRule . /ujian_server/index.php [L] menjadi RewriteRule . /direktori_aplikasi/index.php [L]
2. Edit file application/config/config.php 
   a. baris 26 $config['base_url'] = ''; ganti menjadi full path dari websitenya.
   b. kalau masih di local, kosongkan saja 
