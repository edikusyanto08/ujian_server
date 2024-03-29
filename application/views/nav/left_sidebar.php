<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <?php if ($this->session->userdata('login')) { ?>
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo base_url('assets/dist/img/avatar5.png');?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $this->session->userdata('user_fullname'); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <?php } ?>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <?php if (!$this->session->userdata('login')) { ?>
            <li class="dashboard <?php if ($menu == 'dashboard'){ echo 'active'; }?>">
                <a data-target="dashboard" href="<?php echo base_url('hasil_ujian');?>" onclick="load_page(this);return false">
                    <i class="fa fa-bar-chart"></i> <span>Hasil Ujian</span>
                </a>
            </li>
            <?php } else { ?>
            <li class="header">MAIN NAVIGATION</li>
            <li class="dashboard <?php if ($menu == 'dashboard'){ echo 'active'; }?>">
                <a data-target="dashboard" href="<?php echo base_url('');?>" onclick="load_page(this);return false">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php if ($this->session->userdata('user_level') == 99) { ?>
            <li class="siswa <?php if ($menu == 'siswa'){ echo 'active'; }?>">
                <a data-target="siswa" href="<?php echo base_url('siswa');?>" onclick="load_page(this);return false">
                    <i class="fa fa-users"></i> <span>Biodata Siswa</span>
                </a>
            </li>
            <li class="server <?php if ($menu == 'server'){ echo 'active'; }?>">
                <a data-target="server" href="<?php echo base_url('server');?>" onclick="load_page(this);return false">
                    <i class="fa fa-server"></i> <span>Server, Ruang dan Peserta</span>
                </a>
            </li>
            <li class="mapel <?php if ($menu == 'mapel'){ echo 'active'; }?>">
                <a data-target="mapel" href="<?php echo base_url('mapel');?>" onclick="load_page(this);return false">
                    <i class="fa fa-book"></i> <span>Mapel dan Bank Soal</span>
                </a>
            </li>
            <li class="quiz <?php if ($menu == 'quiz'){ echo 'active'; }?>">
                <a data-target="quiz" href="<?php echo base_url('quiz');?>" onclick="load_page(this);return false">
                    <i class="fa fa-television"></i> <span>Tes dan Jadwal Tes</span>
                </a>
            </li>
            <li class="media <?php if ($menu == 'media'){ echo 'active'; }?>">
                <a data-target="media" href="<?php echo base_url('media');?>" onclick="load_page(this);return false">
                    <i class="fa fa-image"></i> <span>Media</span>
                </a>
            </li>
            <?php } ?>
            <?php if ($this->session->userdata('user_level') >= 50 && $this->session->userdata('user_level') < 90) { ?>
            <li class="komponen <?php if ($menu == 'komponen'){ echo 'active'; }?>">
                <a data-target="komponen" href="<?php echo base_url('komponen');?>" onclick="load_page(this);return false">
                    <i class="fa fa-database"></i> <span>Komponen Penilaian</span>
                </a>
            </li>
            <li class="kelompok <?php if ($menu == 'kelompok'){ echo 'active'; }?>">
                <a data-target="kelompok" href="<?php echo base_url('kelompok');?>" onclick="load_page(this);return false">
                    <i class="fa fa-users"></i> <span>Kelompok Belajar</span>
                </a>
            </li>
            <li class="jadwal <?php if ($menu == 'jadwal'){ echo 'active'; }?>">
                <a data-target="jadwal" href="<?php echo base_url('jadwal');?>" onclick="load_page(this);return false">
                    <i class="fa fa-calendar-check-o"></i> <span>Jadwal</span>
                </a>
            </li>
            <li class="treeview tulis <?php if ($menu == 'tulis'){ echo 'active'; }?>">
                <a href="#">
                    <i class="fa fa-pencil-square"></i>
                    <span>Tes Tulis</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a data-target="tulis" href="<?php echo base_url('tulis');?>" onclick="load_page(this);return false"><i class="fa fa-circle-o"></i> Jadwal Tes Tulis</a>
                    </li>
                    <li>
                        <a data-target="tulis" href="<?php echo base_url('tulis/soal');?>" onclick="load_page(this);return false"><i class="fa fa-circle-o"></i> Bank Soal Tes Tulis</a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php if ($this->session->userdata('user_level') >= 40 && $this->session->userdata('user_level') < 90){ ?>
            <li class="treeview penilaian <?php if ($menu == 'penilaian'){ echo 'active'; }?>">
                <a href="#">
                    <i class="fa fa-check-circle-o"></i>
                    <span>Penilaian</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a data-target="penilaian" href="<?php echo base_url('nilai');?>" onclick="load_page(this);return false"><i class="fa fa-circle-o"></i> Penilaian Praktik & Lisan</a>
                    </li>
                    <li>
                        <a data-target="penilaian" href="<?php echo base_url('nilai/rekap');?>" onclick="load_page(this);return false"><i class="fa fa-circle-o"></i> Rekap Nilai</a>
                    </li>
                </ul>
            </li>
            <?php } } ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>