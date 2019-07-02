<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz extends CI_Controller {
	public function index(){
	    if(!$this->session->userdata('login')){
	        redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
	        redirect(base_url('quiz/landing'));
        }
        $data['body']   = 'quiz/home';
	    $data['menu']   = 'quiz';
	    $data['jn']     = $this->dbase->dataResult('default','jenis_nilai',array('jn_status'=>1),'jn_id,jn_name');
	    $tapel  = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MAX(sis_tapel) AS tapel')->tapel;
	    if ($tapel){
            $data['tapel'] = $tapel;
        } else {
            $data['tapel'] = date('Y');
        }
	    if ($this->input->is_ajax_request()){
	        $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
	}
	function data_home(){
	    $json['t'] = 0; $json['msg'] = '';
	    $jn_id          = $this->input->post('jn_id');
	    $keyword        = $this->input->post('keyword');
	    $tapel          = $this->input->post('tapel');
	    $sql_jn = "";
	    if ($jn_id){ $sql_jn = " AND q.jn_id = '".$jn_id."' "; }
	    $dtQuiz         = $this->dbase->sqlResult('default',"
	        SELECT    q.quiz_id,jn.jn_sing,q.quiz_name,q.quiz_start,q.quiz_end,q.quiz_tapel,q.quiz_timer,q.quiz_jml_soal
            FROM      tb_quiz AS q
            LEFT JOIN tb_jenis_nilai AS jn ON q.jn_id = jn.jn_id AND jn.jn_status = 1
            WHERE     (
                      q.quiz_name LIKE '%".$keyword."%' OR
                      jn.jn_name LIKE '%".$keyword."%'
                      ) AND q.quiz_status = 1 AND q.quiz_tapel = '".$tapel."' ".$sql_jn."
            ORDER BY  q.quiz_tapel,q.quiz_start ASC
	    ");
	    if (!$dtQuiz){
	        $json['msg'] = 'Tidak ada data';
        } else {
	        $this->load->library('conv');
	        $i = 0;
	        foreach ($dtQuiz as $valQ){
	            $dtQuiz[$i]     = $valQ;
	            $dtQuiz[$i]->mapel = $this->dbase->sqlResult('default',"
	                SELECT    qm.mapel_id,m.mapel_name
                    FROM      tb_quiz_mapel AS qm
                    LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id
                    WHERE     qm.quiz_id = '".$valQ->quiz_id."' AND qm.qm_status = 1
                    ORDER BY  m.mapel_name ASC
	            ");
	            $i++;
            }
	        $data['data']   = $dtQuiz;
	        $json['t']      = 1;
	        $json['html']   = $this->load->view('quiz/data_home',$data,TRUE);
        }
	    die(json_encode($json));
    }
    function add_data(){
        $jn_id  = $this->uri->segment(4);
        $tapel  = $this->uri->segment(3);
        $dtJN   = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
        if (!$dtJN){
            die('Pilih Jenis Penilaian');
        } else {
            $data['jn'] = $dtJN;
            $dtMapel    = $this->dbase->sqlResult('default',"
                SELECT    m.mapel_id,m.mapel_tingkat,m.mapel_name
                FROM      tb_mapel AS m
                WHERE     m.mapel_status = 1 
                          AND m.mapel_id NOT IN (
	                        SELECT    m2.mapel_id 
	                        FROM      tb_quiz_mapel AS m2
	                        LEFT JOIN tb_quiz AS q ON m2.quiz_id = q.quiz_id AND q.quiz_status = 1
	                        WHERE     q.quiz_tapel = '".$tapel."' AND q.jn_id = ".$jn_id."
                          )
                ORDER BY  m.mapel_group ASC
            ");
            //$data['mapel'] = $this->dbase->dataResult('default','mapel',array('mapel_status'=>1),'mapel_id,mapel_name,mapel_tingkat','mapel_tingkat','ASC');
            $data['mapel'] = $dtMapel;
            $this->load->view('quiz/add_data',$data);
        }
    }
    function add_data_submit(){
	    $json['t'] = 0; $json['msg'] = '';
	    $data_mapel         = $this->input->post('mapel_id');
	    $tapel              = $this->input->post('tapel');
	    $jn_id              = $this->input->post('jn_id');
        $quiz_start         = $this->input->post('quiz_start');
        $quiz_start_jam     = $this->input->post('quiz_start_jam');
        $quiz_end           = $this->input->post('quiz_end');
        $quiz_end_jam       = $this->input->post('quiz_end_jam');
	    $quiz_timer         = $this->input->post('quiz_timer');
	    $quiz_jml_soal      = $this->input->post('quiz_jml_soal');
	    $quiz_random_soal   = $this->input->post('quiz_random_soal');
	    $quiz_random_pg     = $this->input->post('quiz_random_pg');
	    $dtJN               = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
	    if (!$jn_id || !$dtJN){
	        $json['msg'] = 'Invalid parameter JENIS PENILAIAN';
        } elseif (strlen($quiz_start) != 10) {
            $json['msg'] = 'Tanggal mulai belum diisi';
        } elseif (strlen($quiz_start_jam) != 5){
	        $json['msg'] = 'Jam Mulai belum diisi';
        } elseif (strlen($quiz_end) != 10) {
            $json['msg'] = 'Tanggal berakhir belum diisi';
        } elseif (strlen($quiz_end_jam) != 5){
	        $json['msg'] = 'Jam berakhir belum diisi';
        } elseif (!$quiz_timer){
	        $json['msg'] = 'Batas waktu belum diisi';
        } elseif (!$quiz_jml_soal) {
            $json['msg'] = 'Maksimal soal belum diisi';
        } elseif (!$data_mapel) {
            $json['msg'] = 'Pilih mata pelajaran';
        } elseif (count($data_mapel) == 0){
            $json['msg'] = 'Pilih mata pelajaran';
        } else {
	        $nomor  = $this->dbase->dataRow('default','quiz',array('jn_id'=>$jn_id,'quiz_tapel'=>$tapel,'quiz_status'=>1),'COUNT(quiz_id) AS cnt')->cnt;
	        $nomor  = $nomor + 1;
	        $quiz_name = $dtJN->jn_sing.$tapel.'_'.$nomor;
	        $arr = array(
	            'jn_id' => $jn_id, 'quiz_name' => $quiz_name, 'quiz_start' => $quiz_start.' '.$quiz_start_jam.':00', 'quiz_end' => $quiz_end.' '.$quiz_end_jam.':00',
                'quiz_tapel' => $tapel, 'quiz_timer' => $quiz_timer, 'quiz_jml_soal' => $quiz_jml_soal, 'quiz_random_soal' => $quiz_random_soal,
                'quiz_random_pg' => $quiz_random_pg
            );
	        $quiz_id    = $this->dbase->dataInsert('default','quiz',$arr);
	        if (!$quiz_id){
	            $json['msg'] = 'DB Error';
            } else {
	            foreach ($data_mapel as $mapel_id){
	                $this->dbase->dataInsert('default','quiz_mapel',array('mapel_id'=>$mapel_id,'quiz_id'=>$quiz_id));
                }
                $this->load->library('conv');
	            $json['t'] = 1;
                $dtQuiz         = $this->dbase->sqlResult('default',"
                    SELECT    q.quiz_id,jn.jn_sing,q.quiz_name,q.quiz_start,q.quiz_end,q.quiz_tapel,q.quiz_timer,q.quiz_jml_soal
                    FROM      tb_quiz AS q
                    LEFT JOIN tb_jenis_nilai AS jn ON q.jn_id = jn.jn_id AND jn.jn_status = 1
                    WHERE     q.quiz_id = '".$quiz_id."'
                    ORDER BY  q.quiz_tapel,q.quiz_start ASC
                ");
                $dtQuiz[0]->mapel = $this->dbase->sqlResult('default',"
	                SELECT    qm.mapel_id,m.mapel_name
                    FROM      tb_quiz_mapel AS qm
                    LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id
                    WHERE     qm.quiz_id = '".$quiz_id."' AND qm.qm_status = 1
                    ORDER BY  m.mapel_name ASC
	            ");
                $dtQuiz[0]->dist = 0;
                $data['data'] = $dtQuiz;
                $json['html']   = $this->load->view('quiz/data_home',$data,true);
            }
        }
	    die(json_encode($json));
    }
    function edit_data(){
	    $quiz_id        = $this->uri->segment(3);
	    $dtQuiz         = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
	    if (!$quiz_id || !$dtQuiz){
	        die('Invalid parameter TES');
        } else {
            $data['mapel'] = $this->dbase->dataResult('default','mapel',array('mapel_status'=>1),'mapel_id,mapel_name,mapel_tingkat','mapel_tingkat','ASC');
            $data['jn'] = $this->dbase->dataResult('default','jenis_nilai',array('jn_status'=>1),'jn_id,jn_name');
            $mapel = $this->dbase->dataResult('default','quiz_mapel',array('quiz_id'=>$quiz_id,'qm_status'=>1),'mapel_id');
            $dtMapel = array();
            foreach ($mapel as $val){
                $dtMapel[] = $val->mapel_id;
            }
            $dtQuiz->mapel = $dtMapel;
            $data['data']   = $dtQuiz;
            $this->load->view('quiz/edit_data',$data);
        }
    }
    function edit_data_submit(){
	    $json['t'] = 0; $json['msg'] = '';
        $quiz_id            = $this->input->post('quiz_id');
        $dtQuiz             = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
        $data_mapel         = $this->input->post('mapel_id');
        $tapel              = $this->input->post('tapel');
        $jn_id              = $this->input->post('jn_id');
        $quiz_start         = $this->input->post('quiz_start');
        $quiz_start_jam     = $this->input->post('quiz_start_jam');
        $quiz_end           = $this->input->post('quiz_end');
        $quiz_end_jam       = $this->input->post('quiz_end_jam');
        $quiz_timer         = $this->input->post('quiz_timer');
        $quiz_jml_soal      = $this->input->post('quiz_jml_soal');
        $quiz_random_soal   = $this->input->post('quiz_random_soal');
        $quiz_random_pg     = $this->input->post('quiz_random_pg');
        $dtJN               = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
        if (!$quiz_id || !$dtQuiz){
            $json['msg'] = 'Invalid parameter TES';
        } elseif (!$jn_id || !$dtJN){
            $json['msg'] = 'Invalid parameter JENIS PENILAIAN';
        } elseif (strlen($quiz_start) != 10){
            $json['msg'] = 'Tanggal mulai belum diisi';
        } elseif (strlen($quiz_start_jam) != 5){
            $json['msg'] = 'Jam Mulai belum diisi';
        } elseif (strlen($quiz_end) != 10) {
            $json['msg'] = 'Tanggal berakhir belum diisi';
        } elseif (strlen($quiz_end_jam) != 5){
            $json['msg'] = 'Jam berakhir belum diisi';
        } elseif (!$quiz_timer){
            $json['msg'] = 'Batas waktu belum diisi';
        } elseif (!$quiz_jml_soal) {
            $json['msg'] = 'Maksimal soal belum diisi';
        } elseif (!$data_mapel) {
            $json['msg'] = 'Pilih mata pelajaran';
        } elseif (count($data_mapel) == 0){
            $json['msg'] = 'Pilih mata pelajaran';
        } else {
            $arr = array(
                'jn_id' => $jn_id, 'quiz_start' => $quiz_start.' '.$quiz_start_jam.':00', 'quiz_end' => $quiz_end.' '.$quiz_end_jam.':00',
                'quiz_timer' => $quiz_timer, 'quiz_jml_soal' => $quiz_jml_soal, 'quiz_random_soal' => $quiz_random_soal,
                'quiz_random_pg' => $quiz_random_pg
            );
            $this->dbase->dataUpdate('default','quiz',array('quiz_id'=>$quiz_id),$arr);
            $this->dbase->dataUpdate('default','quiz_mapel',array('quiz_id'=>$quiz_id),array('qm_status'=>0));
            foreach ($data_mapel as $val){
                $chk = $this->dbase->dataRow('default','quiz_mapel',array('quiz_id'=>$quiz_id,'mapel_id'=>$val));
                if ($chk){
                    $this->dbase->dataUpdate('default','quiz_mapel',array('qm_id'=>$chk->qm_id),array('qm_status'=>1));
                } else {
                    $this->dbase->dataInsert('default','quiz_mapel',array('quiz_id'=>$quiz_id,'mapel_id'=>$val));
                }
            }
            $json['t'] = 1;
            $json['msg'] = 'Data berhasil diupdate';
        }
        die(json_encode($json));
    }
    function bulk_delete(){
	    $json['t'] = 0; $json['msg'] = '';
	    $data_quiz  = $this->input->post('quiz_id');
	    if (!$data_quiz){
	        $json['msg'] = 'Pilih data lebih dulu';
        } elseif (count($data_quiz) == 0){
            $json['msg'] = 'Pilih data lebih dulu';
        } else {
	        foreach ($data_quiz as $quiz_id){
	            $this->dbase->dataUpdate('default','quiz',array('quiz_id'=>$quiz_id),array('quiz_status'=>0));
            }
            $json['t'] = 1;
	        $json['data'] = $data_quiz;
	        $json['msg'] = 'Data berhasil dihapus';
        }
	    die(json_encode($json));
    }
    function distribusi(){
	    $quiz_id    = $this->uri->segment(3);
	    $dtQuiz     = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
	    if (!$quiz_id || !$dtQuiz){
	        die('Invalid parameter TES');
        } else {
	        $dtMapel    = $this->dbase->sqlResult('default',"
	            SELECT    qm.qm_id,qm.mapel_id,m.mapel_name,m.mapel_tingkat,m.kk_id,Count(s.soal_id) AS bank_soal
                FROM      tb_quiz_mapel AS qm
                LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id AND m.mapel_status = 1
                LEFT JOIN tb_soal AS s ON s.mapel_id = m.mapel_id AND s.soal_status = 1
                WHERE     qm.quiz_id = '".$quiz_id."' AND qm.qm_status = 1
                GROUP BY  qm.qm_id,qm.mapel_id
                ORDER BY  m.mapel_group ASC
	        ");
	        if (!$dtMapel){
	            die('Invalid parameter MAPEL');
            } else {
	            $i = 0;
	            foreach ($dtMapel as $valMapel){
	                $dtMapel[$i]    = $valMapel;
	                $dtMapel[$i]->bank = $this->dbase->dataRow('default','soal',array('mapel_id'=>$valMapel->mapel_id,'soal_status'=>1),'COUNT(soal_id) AS jml')->jml;
	                $arr_sis = array('sis_status'=>1,'sis_tingkat'=>$valMapel->mapel_tingkat);
	                if (strlen($valMapel->kk_id) > 0){ $arr_sis['kk_id'] = $valMapel->kk_id; }
	                $dtMapel[$i]->jml_siswa = $this->dbase->dataRow('default','siswa',$arr_sis,'COUNT(sis_id) AS cnt')->cnt;
                    $dtSoal = $this->dbase->dataResult('default','quiz_soal',array('qm_id'=>$valMapel->qm_id,'mapel_id'=>$valMapel->mapel_id,'qs_status'=>1),'DISTINCT(sis_id)');
                    $dtMapel[$i]->dist = 0;
                    if ($dtSoal){ $dtMapel[$i]->dist = count($dtSoal); }
	                //$dtMapel[$i]->dist = @$this->dbase->dataRow('default','quiz_soal',array('mapel_id'=>$valMapel->mapel_id,'quiz_id'=>$quiz_id,'qs_status'=>1),'COUNT(DISTINCT(soal_id)) AS jml')->jml;
	                $i++;
                }
	            $dtQuiz->mapel  = $dtMapel;
	            $data['data']   = $dtQuiz;
	            $this->load->view('quiz/distribusi',$data);
            }
        }
    }
    function mulai_dist(){
        $json['t'] = 0; $json['msg'] = 'START ERROR';
        $mapel_id       = $this->input->post('mapel_id');
        $quiz_id        = $this->input->post('quiz_id');
        $qm_id          = $this->input->post('qm_id');
        $dtQM           = $this->dbase->dataRow('default','quiz_mapel',array('qm_id'=>$qm_id));
        $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
        $dtQuiz         = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
        if (!$mapel_id || !$dtMapel){
            $json['msg'] = 'Invalid parameter MAPEL';
        } elseif (!$quiz_id || !$dtQuiz) {
            $json['msg'] = 'Invalid parameter TES';
        } elseif (!$qm_id || !$dtQM){
            $json['msg'] = 'Invalid parameter MAPEL TES';
        } else {
            //$arr = array('sis_tingkat'=>$dtMapel->mapel_tingkat,'sis_status'=>1,'sis_id'=>'20197303_000175');
            $arr = array('sis_tingkat'=>$dtMapel->mapel_tingkat,'sis_status'=>1,'sis_id !='=>'20197303_000175');
            if (strlen($dtMapel->kk_id) > 0){ $arr['kk_id'] = $dtMapel->kk_id; }
            $dtSiswa    = $this->dbase->dataResult('default','siswa',$arr,'sis_id');
            if (!$dtSiswa){
                $json['msg'] = 'Tidak ada data siswa';
            } else {
                //delete dulu data yg ada
                /*foreach ($dtSiswa as $valS){
                    ini_set('max_execution_time',1000);
                    $this->dbase->dataDelete('default','quiz_soal_pg',array('sis_id'=>$valS->sis_id,'qm_id'=>$qm_id));
                    $this->dbase->dataDelete('default','quiz_soal',array('sis_id'=>$valS->sis_id,'qm_id'=>$qm_id));
                }*/
                //end delete
                $json['t'] = 1;
                $json['data'] = $dtSiswa;
            }
        }
        die(json_encode($json));
    }
    function mulai_dist_proses(){
	    ini_set('max_execution_time',10000);
        $json['t'] = 0; $json['msg'] = 'START ERROR';
        $sis_id     = $this->input->post('sis_id');
        $quiz_id    = $this->input->post('quiz_id');
        $mapel_id   = $this->input->post('mapel_id');
        $dtQuiz     = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
        $qm_id      = $this->input->post('qm_id');
        $order      = 'soal_nomor';
        $orderPG    = 'pg_nomor';

        $chkJmlSoal = $this->dbase->dataRow('default','quiz_soal',array('qm_id'=>$qm_id,'sis_id'=>$sis_id),'COUNT(qs_id) AS cnt')->cnt;
        if ($chkJmlSoal >= $dtQuiz->quiz_jml_soal){
            $json['msg'] = 'Sudah ada soal';
        } else {
            //delete dulu soal yg sudah ada
            // sudah delete di fungsi mulai_dist()
            //buat soal yg baru
            // SOAL PILIHAN GANDA
            $soal_id    = '';
            $dtSoal     = $this->dbase->dataResult('default','soal',array('mapel_id'=>$mapel_id,'soal_type'=>'pg','soal_status'=>1),'soal_id','soal_nomor','ASC');
            if ($dtQuiz->quiz_random_soal == 1){ //randomize data soal
                if ($dtSoal){
                    $soal_id = array(); $i = 0;
                    foreach ($dtSoal as $val){
                        $soal_id[$i]    = $val->soal_id;
                        $i++;
                    }
                }
                shuffle($soal_id);
                $dtSoal = array_slice($soal_id,0,$dtQuiz->quiz_jml_soal);
            } else {//randomize data soal
                //die('random');
                if ($dtSoal){
                    $soal_id = array(); $i = 0;
                    foreach ($dtSoal as $val){
                        $soal_id[$i]    = $val->soal_id;
                        $i++;
                    }
                }
                $dtSoal = $soal_id;
            }
            if ($dtSoal){
                $nomor = 1;
                foreach ($dtSoal as $valSoal){
                    $qs_id      = $sis_id.'.'.str_pad($qm_id,4,"0",STR_PAD_LEFT).'.'.str_pad($nomor,4,"0",STR_PAD_LEFT);
                    $this->dbase->dataInsert('default','quiz_soal',array(
                        'qs_id' => $qs_id,
                        'soal_id'=>$valSoal, 'sis_id'=>$sis_id, 'qm_id'=>$qm_id, 'mapel_id'=>$mapel_id, 'soal_nomor'=>$nomor
                    ));
                    $dtPG = $this->dbase->dataResult('default','soal_pg',array('soal_id'=>$valSoal,'pg_status'=>1),'pg_id',$orderPG,'asc');
                    if ($dtQuiz->quiz_random_pg == 1){ //randomize PG
                        $pg_id = array(); $i = 0;
                        foreach ($dtPG as $val){
                            $pg_id[$i] = $val->pg_id;
                            $i++;
                        }
                        shuffle($pg_id);
                        $dtPG = $pg_id;
                    } else {//randomize PG
                        $pg_id = array(); $i = 0;
                        foreach ($dtPG as $val){
                            $pg_id[$i] = $val->pg_id;
                            $i++;
                        }
                        $dtPG = $pg_id;
                    }
                    if ($dtPG){
                        $nomorPG = 1;
                        foreach ($dtPG as $valPG){
                            $qspgid         = $qs_id.'.'.str_pad($nomorPG,3,"0",STR_PAD_LEFT);
                            $this->dbase->dataInsert('default','quiz_soal_pg',array(
                                'qs_id' => $qs_id, 'qspg_id' => $qspgid, 'pg_id' => $valPG, 'qm_id' => $qm_id, 'sis_id' => $sis_id,
                                'qspg_nomor' => $nomorPG
                            ));
                            $nomorPG++;
                        }
                    }
                    $nomor++;
                }
            }
            // SOAL URAIAN
            /*$dtSoalUrai = $this->dbase->dataResult('default','soal',array('mapel_id'=>$mapel_id,'soal_type !='=>'pg','soal_status'=>1),'soal_id',$order,'ASC');
            if ($dtSoalUrai){
                foreach ($dtSoalUrai as $valSoal){
                    $qs_id      = $sis_id.'.'.str_pad($qm_id,10,"0",STR_PAD_LEFT).'.'.str_pad($nomor,10,"0",STR_PAD_LEFT);
                    $this->dbase->dataInsert('default','quiz_soal',array(
                        'qs_id' => $qs_id,
                        'soal_id' => $valSoal->soal_id, 'sis_id' => $sis_id, 'qm_id' => $qm_id, 'mapel_id' => $mapel_id,
                        'soal_nomor' => $nomor
                    ));
                    $nomor++;
                }
            }*/
            if ($nomor == 0){
                $json['msg'] = 'Tidak ada distribusi';
            } else {
                $json['siswa']  = 1;
                $json['t']      = 1;
            }
        }
        die(json_encode($json));
    }
    function delete_dist(){
	    $json['t'] = 0; $json['msg'] = 'START';
	    $qm_id      = $this->input->post('qm_id');
	    $dtQM       = $this->dbase->dataRow('default','quiz_mapel',array('qm_id'=>$qm_id),'qm_id,mapel_id');
	    $dtQS       = $this->dbase->dataRow('default','quiz_soal',array('qm_id'=>$qm_id,'qs_status'=>1),'COUNT(qs_id) AS cnt');
	    if (!$qm_id || !$dtQM){
	        $json['msg'] = 'Invalid parameter MAPEL TES';
        } elseif (!$dtQS){
	        $json['msg'] = 'Tidak ada DISTRIBUSI SOAL yang harus dihapus';
        } else {
	        $this->dbase->dataDelete('default','quiz_soal_pg',array('qm_id'=>$qm_id));
            $this->dbase->runQuery('default',"OPTIMIZE TABLE tb_quiz_soal;");
	        $this->dbase->dataDelete('default','quiz_soal',array('qm_id'=>$qm_id));
	        $this->dbase->runQuery('default',"OPTIMIZE TABLE tb_quiz_soal_pg;");
	        //$this->dbase->dataUpdate('default','quiz_soal',array('qm_id'=>$qm_id),array('qs_status'=>0));
	        //$this->dbase->dataUpdate('default','soal',array('mapel_id'=>$dtQM->mapel_id),array('soal_status'=>0));
	        $json['t']  = 1;
	        $json['msg']= $dtQS->cnt.' DISTRIBUSI SOAL berhasil dihapus';
        }
	    die(json_encode($json));
    }
    function cetak_hadir_peserta(){
	    $tapel      = $this->uri->segment(3);
	    $jn_id      = $this->uri->segment(4);
	    $dtJN       = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
        if (!$jn_id) {
            die('Pilih jenis penilaian');
        } elseif (!$dtJN){
            die('Invalid parameter JENIS PENILAIAN');
        } else {
            $dtRuang = $this->dbase->sqlResult('default',"
                SELECT    sr.sr_id,sr.server_id,sr.sr_name,s.jn_id,s.server_kode,s.server_tapel,jn.jn_name,jn.jn_sing
                FROM      tb_server_ruang AS sr
                LEFT JOIN tb_server AS s ON sr.server_id = s.server_id
                LEFT JOIN tb_jenis_nilai AS jn ON s.jn_id = jn.jn_id
                WHERE     s.server_tapel = '".$tapel."' AND s.jn_id = '".$jn_id."' AND s.server_status = 1
                ORDER BY  sr.sr_name ASC
            ");
            if (!$dtRuang){
                die('Tidak ada data RUANG. BUAT RUANG lebih dulu di menu SERVER dan RUANG');
            } else {
                $i = 0;
                foreach ($dtRuang as $valRuang){
                    $dtRuang[$i]       = $valRuang;
                    $dtRuang[$i]->peserta = $this->dbase->sqlResult('default',"
                        SELECT    s.sis_nopes,s.sis_fullname,s.sis_sex
                        FROM      tb_ruang_member AS rm
                        LEFT JOIN tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                        WHERE     rm.sr_id = '".$valRuang->sr_id."' AND rm.rm_status = 1
                        ORDER BY  s.sis_nopes,s.sis_fullname ASC
                    ");
                    $i++;
                }
                $data['sch'] = $this->dbase->dataRow('default','school',array());
                $data['data']   = $dtRuang;
                $this->load->library('conv');
                $this->load->view('quiz/cetak_hadir_peserta',$data);
            }
        }
    }
    function cetak_berita_acara(){
        $tapel      = $this->uri->segment(3);
        $jn_id      = $this->uri->segment(4);
        $dtJN       = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
        if (!$jn_id) {
            die('Pilih jenis penilaian');
        } elseif (!$dtJN){
            die('Invalid parameter JENIS PENILAIAN');
        } else {
            $data['sch']    = $this->dbase->dataRow('default','school',array());
            $data['JN']     = $dtJN;
            $data['tapel']  = $tapel;
            $this->load->view('quiz/cetak_berita_acara',$data);
        }
    }
    function cetak_tata_tertib(){
        $tapel      = $this->uri->segment(3);
        $jn_id      = $this->uri->segment(4);
        $dtJN       = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
        if (!$jn_id) {
            die('Pilih jenis penilaian');
        } elseif (!$dtJN){
            die('Invalid parameter JENIS PENILAIAN');
        } else {
            $data['sch']    = $this->dbase->dataRow('default','school',array());
            $data['JN']     = $dtJN;
            $data['tapel']  = $tapel;
            $this->load->view('quiz/cetak_tata_tertib',$data);
        }
    }
    function hasil(){
        if(!$this->session->userdata('login')){
            redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
            redirect(base_url('quiz/landing'));
        }
        $data['body']   = 'quiz/hasil';
        $data['menu']   = 'quiz';
        $data['jn']     = $this->dbase->dataResult('default','jenis_nilai',array('jn_status'=>1),'jn_id,jn_sing');
        $tapel  = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MAX(sis_tapel) AS tapel')->tapel;
        if ($tapel){
            $data['tapel'] = $tapel;
        } else {
            $data['tapel'] = date('Y');
        }
        $quiz_id        = $this->uri->segment(3);
        $dtQuiz         = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
        if (!$quiz_id || !$dtQuiz){
            $data['body'] = 'errors/404';
        } else {
            $data['data']   = $dtQuiz;
            $data['mapel']  = $this->dbase->sqlResult('default',"
                SELECT    qm.qm_id,m.mapel_name
                FROM      tb_quiz_mapel AS qm
                LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id
                WHERE     qm.quiz_id = '".$quiz_id."'
            ");
            $data['server'] = $this->dbase->dataResult('default','server',array('server_tapel'=>$dtQuiz->quiz_tapel,'jn_id'=>$dtQuiz->jn_id));
            $data['ruang']  = array();
            if ($data['server']){
                $data['ruang'] = $this->dbase->dataResult('default','server_ruang',array('server_id'=>$data['server'][0]->server_id));
            }
            $data['quiz']   = $this->dbase->dataResult('default','quiz',array('quiz_tapel'=>$dtQuiz->quiz_tapel,'jn_id'=>$dtQuiz->jn_id),'quiz_id,quiz_name');
        }
        if ($this->input->is_ajax_request()){
            $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
    }
    function find_tes(){
	    $json['t'] = 0; $json['msg'] = '';
	    $tapel      = $this->input->post('tapel');
	    $jn_id      = $this->input->post('jn_id');
	    $dtQuiz     = $this->dbase->dataResult('default','quiz',array('jn_id'=>$jn_id,'quiz_tapel'=>$tapel),'quiz_id,quiz_name');
	    if (!$dtQuiz){
	        $json['msg'] = 'Tidak ada TES';
        } else {
	        $json['t']      = 1;
            $json['data2']  = $this->dbase->dataResult('default','server',array('server_tapel'=>$tapel,'jn_id'=>$jn_id),'server_id,server_name');
	        $json['data']   = $dtQuiz;
        }
	    die(json_encode($json));
    }
    function find_ruang(){
        $json['t'] = 0; $json['msg'] = '';
        $server_id      = $this->input->post('server_id');
        $dtRuang        = $this->dbase->dataResult('default','server_ruang',array('server_id'=>$server_id,'sr_status'=>1),'sr_id,sr_name');
        if (!$dtRuang){
            $json['msg'] = 'Tidak ada Ruang';
        } else {
            $json['t']      = 1;
            $json['data']   = $dtRuang;
        }
        die(json_encode($json));
    }
    function find_mapel(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $quiz_id    = $this->input->post('quiz_id');
        $dtQuiz     = $this->dbase->sqlResult('default',"
                SELECT    qm.qm_id,m.mapel_name
                FROM      tb_quiz_mapel AS qm
                LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id
                WHERE     qm.quiz_id = '".$quiz_id."'
            ");
        if (!$dtQuiz){
            $json['msg'] = 'Tidak ada TES';
        } else {
            $json['t'] = 1;
            $json['data'] = $dtQuiz;
        }
        die(json_encode($json));
    }
    function data_hasil(){
	    $json['t'] = 0; $json['msg'] = 'Tidak ada data';
        $tapel      = $this->input->post('tapel');
        $jn_id      = $this->input->post('jn_id');
        $quiz_id    = $this->input->post('quiz_id');
        $qm_id      = $this->input->post('qm_id');
        $server_id  = $this->input->post('server_id');
        $sr_id      = $this->input->post('sr_id');
        $keyword    = $this->input->post('keyword');
        $dtQuiz     = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
        //$dtQM       = $this->dbase->dataRow('default','quiz_mapel',array('qm_id'=>$qm_id));
        $dtQM       = $this->dbase->sqlRow('default',"
            SELECT    qm.qm_id,m.kk_id,qm.mapel_id
            FROM      tb_quiz_mapel AS qm
            LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id
            WHERE     qm.qm_id = '".$qm_id."'
        ");
        if (!$dtQuiz){
            $json['msg'] = 'Invalid parameter TES';
        } elseif (!$dtQM) {
            $json['msg'] = 'Invalid parameter MAPEL';
        } else {
            $sql_kk = "";
            if (strlen($dtQM->kk_id) > 0){
                $sql_kk = " AND s.kk_id = '".$dtQM->kk_id."' ";
            }
            if (strlen($sr_id) > 0){
                $dtPes  = $this->dbase->sqlResult('default',"
                    SELECT    s.sis_nopes,s.sis_fullname,s.sis_kelas,rm.sis_id,s.sis_kelas
                    FROM      tb_ruang_member AS rm
                    LEFT JOIN tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1 AND sr.server_id = '".$server_id."'
                    LEFT JOIN tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                    WHERE     rm.sr_id = '".$sr_id."' AND rm.rm_status = 1 AND
                              (
                              s.sis_nopes LIKE '%".$keyword."%' OR
                              s.sis_fullname LIKE '%".$keyword."%' OR
                              s.sis_kelas LIKE '%".$keyword."%'
                              ) ".$sql_kk."
                    ORDER BY  s.sis_kelas,s.sis_nopes,s.sis_fullname ASC
                ");
            } else {
                $dtPes  = $this->dbase->sqlResult('default',"
                SELECT    s.sis_nopes,s.sis_fullname,s.sis_kelas,rm.sis_id,s.sis_kelas
                FROM      tb_ruang_member AS rm
                LEFT JOIN tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1 AND sr.server_id = '".$server_id."'
                LEFT JOIN tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                WHERE     rm.rm_status = 1 AND
                          (
                          s.sis_nopes LIKE '%".$keyword."%' OR
                          s.sis_fullname LIKE '%".$keyword."%' OR
                          s.sis_kelas LIKE '%".$keyword."%'
                          ) ".$sql_kk."
                ORDER BY  s.sis_kelas,s.sis_nopes,s.sis_fullname ASC
                ");
            }

            if (!$dtPes){
                $json['msg'] = 'Tidak ada data';
            } else {
                $i = 0;
                foreach ($dtPes as $valPes){
                    $dtPes[$i]      = $valPes;
                    $dtPes[$i]->jml_soal    = $dtQuiz->quiz_jml_soal;
                    $dtPes[$i]->jml_hasil   = $this->dbase->dataRow('default','quiz_hasil',array('sis_id'=>$valPes->sis_id,'quiz_id'=>$quiz_id,'mapel_id'=>$dtQM->mapel_id),'COUNT(qh_id) AS cnt')->cnt;
                    $dtPes[$i]->jml_skor    = $this->dbase->dataRow('default','quiz_hasil',array('sis_id'=>$valPes->sis_id,'quiz_id'=>$quiz_id,'mapel_id'=>$dtQM->mapel_id),'SUM(qh_score) AS cnt')->cnt;
                        $i++;
                }
                $json['t']      = 1;
                $data['data']   = $dtPes;
                $json['html']   = $this->load->view('quiz/data_hasil',$data,true);
            }
        }
	    die(json_encode($json));
    }
    function gen_rank(){
	    $json['t'] = 0; $json['msg'] = '';
	    $quiz_id        = $this->input->post('quiz_id');
	    $qm_id          = $this->input->post('qm_id');
	    $dtQuiz         = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
	    $dtQM           = $this->dbase->dataRow('default','quiz_mapel',array('qm_id'=>$qm_id));
	    if (!$dtQuiz){
	        $json['msg'] = 'Invalid TES';
        } elseif (!$dtQM){
	        $json['msg'] = 'Invalid MAPEL';
        } else {
	        $dtNilai    = $this->dbase->dataResult('default','quiz_nilai',array('quiz_id'=>$quiz_id,'mapel_id'=>$dtQM->mapel_id),'qn_id','qn_nilai','DESC');
	        if (!$dtNilai){
	            $json['msg'] = 'Tidak ada data Nilai';
            } else {
	            $rank = 1;
	            foreach ($dtNilai as $val){
	                $this->dbase->dataUpdate('default','quiz_nilai',array('qn_id'=>$val->qn_id),array('qn_rank'=>$rank));
	                $rank++;
                }
                $json['t'] = 1;
	            $json['msg'] = 'Berhasil generate ranking';
            }
	        /*$dtPes      = $this->dbase->sqlResult('default',"
	            SELECT          qh.sis_id,SUM(qh.qh_score) AS nilai
                FROM            tb_quiz_hasil AS qh
                LEFT JOIN       tb_siswa AS s ON qh.sis_id = s.sis_id
                WHERE           qh.quiz_id = '".$quiz_id."' AND qh.mapel_id = '".$dtQM->mapel_id."'
                GROUP BY        qh.sis_id
	        ");
            uasort($dtPes, function($a, $b) {
                return $b->nilai <=> $a->nilai;
            });
            $i = 1;
            foreach ($dtPes as $valPes){
                $chNil = $this->dbase->dataRow('default','quiz_nilai',array('sis_id'=>$valPes->sis_id,'quiz_id'=>$quiz_id,'jn_id'=>$dtQuiz->jn_id,'mapel_id'=>$dtQM->mapel_id,'qn_tapel'=>$dtQuiz->quiz_tapel),'qn_id');
                if ($chNil){
                    $this->dbase->dataUpdate('default','quiz_nilai',array('qn_id'=>$chNil->qn_id),array('qn_rank'=>$i,'qn_nilai'=>$valPes->nilai));
                } else {
                    $this->dbase->dataInsert('default','quiz_nilai',array(
                        'sis_id' => $valPes->sis_id, 'quiz_id' => $quiz_id, 'jn_id' => $dtQuiz->jn_id, 'mapel_id' => $dtQM->mapel_id,
                        'qn_tapel' => $dtQuiz->quiz_tapel, 'qn_nilai' => $valPes->nilai, 'qn_rank' => $i
                    ));
                }
                $i++;
            }*/
        }
	    die(json_encode($json));
    }
    function gen_hasil(){
	    $json['t'] = 0; $json['msg'] = 'START';
	    $sis_id         = $this->input->post('sis_id');
	    $quiz_id        = $this->input->post('quiz_id');
	    $qm_id          = $this->input->post('qm_id');
	    $dtQM           = $this->dbase->dataRow('default','quiz_mapel',array('qm_id'=>$qm_id),'mapel_id');
	    if (!$dtQM){
	        $json['msg'] = 'Invalid MAPEL';
        } else {
	        $mapel_id   = $dtQM->mapel_id;
	        $dtHasil    = $this->dbase->dataResult('default','quiz_hasil',array('sis_id'=>$sis_id,'quiz_id'=>$quiz_id,'mapel_id'=>$mapel_id),'qh_id,pg_id');
	        if (!$dtHasil){
	            $json['msg'] = 'Belum ada Hasil';
            } else {
	            $dtquiz = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id),'jn_id,quiz_tapel');
	            $nilai = 0;
	            foreach ($dtHasil as $valHasil){
	                $dtPg = $this->dbase->dataRow('default','soal_pg',array('pg_id'=>$valHasil->pg_id),'pg_is_right,pg_score');
	                if ($dtPg){
	                    if ($dtPg->pg_is_right == 1){
	                        $nilai++;
                            $this->dbase->dataUpdate('default','quiz_hasil',array('qh_id'=>$valHasil->qh_id),array('qh_score'=>1));
                        }
                    }
                }
                $dtNilai = $this->dbase->dataRow('default','quiz_nilai',array(
                    'quiz_id'=>$quiz_id,'jn_id'=>$dtquiz->jn_id,'mapel_id'=>$dtQM->mapel_id,'sis_id'=>$sis_id,'qn_tapel'=>$dtquiz->quiz_tapel
                    ));
	            if (!$dtNilai){
	                $this->dbase->dataInsert('default','quiz_nilai',array(
                        'quiz_id'=>$quiz_id,'jn_id'=>$dtquiz->jn_id,'mapel_id'=>$dtQM->mapel_id,'sis_id'=>$sis_id,'qn_tapel'=>$dtquiz->quiz_tapel,
                        'qn_nilai' => $nilai
                    ));
	                $json['ini'] = 'insert';
                } else {
                    $json['ini'] = 'updadte';
	                $this->dbase->dataUpdate('default','quiz_nilai',array('qn_id'=>$dtNilai->qn_id),array('qn_nilai'=>$nilai));
                }
                $json['nilai'] = $nilai;
                $json['t'] = 1;
            }
        }
	    die(json_encode($json));
    }
    function download_hasil(){
	    $quiz_id        = $this->uri->segment(3);
	    $sr_id          = $this->uri->segment(5);
	    $qm_id          = $this->uri->segment(4);
	    $dtQuiz         = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id));
	    $dtRuang        = $this->dbase->dataRow('default','server_ruang',array('sr_id'=>$sr_id),'server_id,sr_name');
	    if (!$dtQuiz){
	        die('Invalid parameter : TES');
        //} elseif (!$dtRuang){
	        //die('Invalid parameter : RUANG');
        } else {
            //$server_id  = $dtRuang->server_id;
            $dtMapel    = $this->dbase->sqlRow('default',"
                SELECT    qm.qm_id,qm.mapel_id,m.mapel_sing,m.kk_id,m.mapel_tingkat,m.mapel_name
                FROM      tb_quiz_mapel AS qm
                LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id AND m.mapel_status = 1
                WHERE     qm.quiz_id = '".$quiz_id."' AND qm.qm_status = 1 AND qm.qm_id = '".$qm_id."'
            ");
            //$dtMapel    = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
            if (!$dtMapel){
                die('Tidak ada MAPEL');
            } else {
                $this->load->library(array('PHPExcel','PHPExcel/IOFactory','conv'));
                $objPHPExcel    = new PHPExcel();
                $styleBorders = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                $sheetIndex     = 0;
                //foreach ($dtMapel as $valMapel){
                    $sheet = $objPHPExcel->createSheet($sheetIndex);
                    $sheet->setTitle('Hasil');
                    $objPHPExcel->setActiveSheetIndex($sheetIndex);
                    $sheet->setCellValue('A1', 'HASIL UJIAN ONLINE')
                        ->setCellValue('A2', 'Nama Tes')
                        ->setCellValue('C2', ': '.$dtQuiz->quiz_name)
                        ->setCellValue('A3', 'Tahun Pelajaran')
                        ->setCellValue('C3', ': '.$dtQuiz->quiz_tapel)
                        ->setCellValue('A4', 'Mata Pelajaran')
                        ->setCellValue('C4', ': '.$dtMapel->mapel_name)
                        ->setCellValue('A6', 'No')
                        ->setCellValue('B6', 'Nomor Peserta')
                        ->setCellValue('C6', 'Nama Peserta')
                        ->setCellValue('D6', 'L/ P')
                        ->setCellValue('E6', 'Kelas')
                        ->setCellValue('F6', 'Nomor Soal / Kunci Jawaban / Jawaban');
                    $sheet->getColumnDimension('A')->setWidth(5);
                    $sheet->getColumnDimension('B')->setWidth(15);
                    $sheet->getColumnDimension('C')->setWidth(35);
                    $sheet->getColumnDimension('D')->setWidth(4);
                    $sheet->getColumnDimension('E')->setWidth(10);
                    $sheet->mergeCells('A6:A8');
                    $sheet->mergeCells('B6:B8');
                    $sheet->mergeCells('C6:C8');
                    $sheet->mergeCells('D6:D8');
                    $sheet->mergeCells('E6:E8');


                    $dtSoal = $this->dbase->sqlResult('default',"
                        SELECT    s.soal_id,s.soal_nomor,pg.pg_nomor
                        FROM      tb_soal AS s
                        LEFT JOIN tb_soal_pg AS pg ON pg.soal_id = s.soal_id 
                        WHERE     s.mapel_id = '".$dtMapel->mapel_id."' AND s.soal_status = 1 AND s.soal_type = 'pg'
                                  AND pg.pg_is_right = 1 AND pg.pg_status = 1
                        ORDER BY  s.soal_nomor ASC
                    ");
                    //die(var_dump($dtSoal));
                    if ($dtSoal){
                        $col = 6;
                        foreach ($dtSoal as $valSoal){
                            $sheet->setCellValue($this->conv->toStr($col).'7', $valSoal->soal_nomor)
                                ->setCellValue($this->conv->toStr($col).'8', $this->conv->toStr($valSoal->pg_nomor));
                            $sheet->getColumnDimension($this->conv->toStr($col))->setWidth(4);
                            $col++;
                        }
                    }
                    $sheet->setCellValue($this->conv->toStr($col).'6', 'JML BENAR');
                    $sheet->mergeCells($this->conv->toStr($col).'6:'.$this->conv->toStr($col).'8');
                    $sheet->mergeCells('F6:'.$this->conv->toStr(($col-1)).'6');
                    $sheet->getStyle('A6:'.$this->conv->toStr($col).'8')->getFont()->setBold( true );
                    $sheet->getStyle('A6:'.$this->conv->toStr($col).'8')->getAlignment()->setWrapText(true);
                    $style = array(
                        'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        )
                    );
                    $sheet->getStyle('A6:'.$this->conv->toStr($col).'8')->applyFromArray($style);

                    //PESERTA
                    if (strlen($dtMapel->kk_id) > 0){
                        $sql_kk = " AND s.kk_id = '".$dtMapel->kk_id."' ";
                    } else {
                        $sql_kk = "";
                    }
                    if (strlen($sr_id) > 0){
                        $dtPeserta = $this->dbase->sqlResult('default',"
                            SELECT    s.sis_nopes,s.sis_fullname,s.sis_id,s.sis_sex,s.sis_kelas
                            FROM      tb_ruang_member AS rm
                            LEFT JOIN tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1 
                            LEFT JOIN tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                            WHERE     s.sis_tingkat = '".$dtMapel->mapel_tingkat."' AND sr.sr_id = '".$sr_id."'
                                      ".$sql_kk."
                            ORDER BY  s.sis_kelas,s.sis_nopes,s.sis_fullname ASC
                        ");
                    } else {
                        $dtPeserta = $this->dbase->sqlResult('default',"
                            SELECT    s.sis_nopes,s.sis_fullname,s.sis_id,s.sis_sex,s.sis_kelas
                            FROM      tb_ruang_member AS rm
                            LEFT JOIN tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1 
                            LEFT JOIN tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                            WHERE     s.sis_tingkat = '".$dtMapel->mapel_tingkat."' 
                                      ".$sql_kk."
                            ORDER BY  s.sis_kelas,s.sis_nopes,s.sis_fullname ASC
                        ");
                    }

                    if ($dtPeserta){
                        $row    = 9; $nomor = 1;
                        foreach ($dtPeserta as $valPes){
                            ini_set('max_execution_time',10000);
                            $sheet->setCellValue('A'.$row, $nomor)
                                ->setCellValue('B'.$row, $valPes->sis_nopes)
                                ->setCellValue('C'.$row, $valPes->sis_fullname)
                                ->setCellValue('D'.$row, $valPes->sis_sex)
                                ->setCellValue('E'.$row, $valPes->sis_kelas);
                            if ($dtSoal){
                                $col = 6;
                                foreach ($dtSoal as $valSoal){
                                    $dtHasil = $this->dbase->sqlRow('default',"
                                        SELECT      pg.pg_nomor
                                        FROM        tb_quiz_hasil AS qh
                                        LEFT JOIN tb_soal_pg AS pg ON qh.pg_id = pg.pg_id
                                        WHERE       qh.sis_id = '".$valPes->sis_id."' AND qh.quiz_id = '".$quiz_id."'
                                                    AND qh.mapel_id = '".$dtMapel->mapel_id."' AND qh.soal_id = '".$valSoal->soal_id."'
                                    ");
                                    if ($dtHasil){
                                        $sheet->setCellValue($this->conv->toStr($col).$row, $this->conv->toStr($dtHasil->pg_nomor));
                                    } else {
                                        $sheet->setCellValue($this->conv->toStr($col).$row, '');
                                    }
                                    $col++;
                                }
                            }
                            $skor = $this->dbase->dataRow('default','quiz_hasil',array('sis_id'=>$valPes->sis_id,'quiz_id'=>$quiz_id,'mapel_id'=>$dtMapel->mapel_id),'SUM(qh_score) AS cnt')->cnt;
                            $sheet->setCellValue($this->conv->toStr($col).$row, $skor);
                            $row++; $nomor++;
                        }
                    }
                    $sheet->getStyle('A9:B'.$row)->applyFromArray($style);
                    $sheet->getStyle('D9:'.$this->conv->toStr($col).$row)->applyFromArray($style);
                    $sheet->getStyle('A6:'.$this->conv->toStr($col).$row)->applyFromArray($styleBorders);
                    $sheetIndex++;

                    $jmlAllPeserta  = count($dtPeserta);


                // ANALISIS BUTIR SOAL
                $sheet = $objPHPExcel->createSheet($sheetIndex);
                $sheet->setTitle('Analisis');
                $objPHPExcel->setActiveSheetIndex($sheetIndex);
                $sheet->setCellValue('A1', 'ANALISIS BUTIR SOAL DAN DAYA PEMBEDA')
                    ->setCellValue('A2', 'Nama Tes')
                    ->setCellValue('C2', ': '.$dtQuiz->quiz_name)
                    ->setCellValue('A3', 'Tahun Pelajaran')
                    ->setCellValue('C3', ': '.$dtQuiz->quiz_tapel)
                    ->setCellValue('A4', 'Mata Pelajaran')
                    ->setCellValue('C4', ': '.$dtMapel->mapel_name)
                    ->setCellValue('A7', 'No')
                    ->setCellValue('B7', 'Nomor Peserta')
                    ->setCellValue('C7', 'Nama Peserta')
                    ->setCellValue('D7', 'L/ P')
                    ->setCellValue('E7', 'Kelas')
                    ->setCellValue('F7', 'Nomor Soal / Kunci Jawaban / Jawaban');
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(4);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->mergeCells('A7:A9');
                $sheet->mergeCells('B7:B9');
                $sheet->mergeCells('C7:C9');
                $sheet->mergeCells('D7:D9');
                $sheet->mergeCells('E7:E9');
                $dtSoal = $this->dbase->sqlResult('default',"
                        SELECT    s.soal_id,s.soal_nomor,pg.pg_nomor
                        FROM      tb_soal AS s
                        LEFT JOIN tb_soal_pg AS pg ON pg.soal_id = s.soal_id 
                        WHERE     s.mapel_id = '".$dtMapel->mapel_id."' AND s.soal_status = 1 AND s.soal_type = 'pg'
                                  AND pg.pg_is_right = 1 AND pg.pg_status = 1
                        ORDER BY  s.soal_nomor ASC
                    ");
                //die(var_dump($dtSoal));
                if ($dtSoal){
                    $col = 6; $iSoal = 0;
                    foreach ($dtSoal as $valSoal){
                        $dtSoal[$iSoal]->jml_atas = $dtSoal[$iSoal]->jml_bawah = 0;
                        $sheet->setCellValue($this->conv->toStr($col).'8', $valSoal->soal_nomor)
                            ->setCellValue($this->conv->toStr($col).'9', $this->conv->toStr($valSoal->pg_nomor));
                        $sheet->getColumnDimension($this->conv->toStr($col))->setWidth(6);
                        $col++; $iSoal++;
                    }
                }
                $sheet->setCellValue($this->conv->toStr($col).'7', 'JML BENAR');
                $sheet->mergeCells($this->conv->toStr($col).'7:'.$this->conv->toStr($col).'9');
                $sheet->mergeCells('F7:'.$this->conv->toStr(($col-1)).'7');
                $sheet->getStyle('A7:'.$this->conv->toStr($col).'9')->getFont()->setBold( true );
                $sheet->getStyle('A7:'.$this->conv->toStr($col).'9')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A7:'.$this->conv->toStr($col).'9')->applyFromArray($style);
                //PESERTA
                if (strlen($dtMapel->kk_id) > 0){
                    $sql_kk = " AND s.kk_id = '".$dtMapel->kk_id."' ";
                } else {
                    $sql_kk = "";
                }
                $dtPeserta = $this->dbase->sqlResult('default',"
                    SELECT      s.sis_id,s.sis_nopes,s.sis_fullname,s.sis_sex,s.sis_kelas,qn.qn_nilai
                    FROM        tb_siswa AS s
                    LEFT JOIN   tb_quiz_nilai AS qn ON qn.sis_id = s.sis_id AND qn.quiz_id = '".$quiz_id."' AND qn.mapel_id = '".$dtMapel->mapel_id."'
                    WHERE       s.sis_tingkat = '".$dtMapel->mapel_tingkat."' AND qn.qn_nilai > 0  ".$sql_kk."
                    ORDER BY    qn.qn_nilai DESC
                ");
                if ($dtPeserta){
                    $jmlAtas    = floor((27/100)*count($dtPeserta));
                    $jmlBawah   = 0 - $jmlAtas;
                    $group_atas = array_slice($dtPeserta,0,$jmlAtas);
                    $sheet->setCellValue('A6', 'Kelompok Atas');
                    $row        = 10; $nomor = 1;
                    foreach ($group_atas as $valPes){
                        ini_set('max_execution_time',10000);
                        $sheet->setCellValue('A'.$row, $nomor)
                            ->setCellValue('B'.$row, $valPes->sis_nopes)
                            ->setCellValue('C'.$row, $valPes->sis_fullname)
                            ->setCellValue('D'.$row, $valPes->sis_sex)
                            ->setCellValue('E'.$row, $valPes->sis_kelas);
                        if ($dtSoal){
                            $col = 6; $iSoal = 0;
                            foreach ($dtSoal as $valSoal){
                                $dtHasil = $this->dbase->sqlRow('default',"
                                        SELECT      pg.pg_nomor,pg.pg_is_right
                                        FROM        tb_quiz_hasil AS qh
                                        LEFT JOIN tb_soal_pg AS pg ON qh.pg_id = pg.pg_id
                                        WHERE       qh.sis_id = '".$valPes->sis_id."' AND qh.quiz_id = '".$quiz_id."'
                                                    AND qh.mapel_id = '".$dtMapel->mapel_id."' AND qh.soal_id = '".$valSoal->soal_id."'
                                    ");
                                if ($dtHasil){
                                    if ($dtHasil->pg_is_right == 1){
                                        $dtSoal[$iSoal]->jml_atas++;
                                    }
                                    $sheet->setCellValue($this->conv->toStr($col).$row, $this->conv->toStr($dtHasil->pg_nomor));
                                } else {
                                    $sheet->setCellValue($this->conv->toStr($col).$row, '');
                                }
                                $col++; $iSoal++;
                            }
                        }
                        $sheet->setCellValue($this->conv->toStr($col).$row, $valPes->qn_nilai);
                        $row++; $nomor++;
                    }
                    $sheet->setCellValue('A'.$row, 'Jumlah Peserta Menjawab Benar');
                    $sheet->mergeCells('A'.$row.':E'.$row);
                    if ($dtSoal){
                        $col = 6;
                        foreach ($dtSoal as $valSoal){
                            $sheet->setCellValue($this->conv->toStr($col).$row, $valSoal->jml_atas);
                            $col++;
                        }
                    }
                    $sheet->getStyle('F7:'.$this->conv->toStr($col).$row)->applyFromArray($style);
                    $sheet->getStyle('A7:'.$this->conv->toStr($col).$row)->applyFromArray($styleBorders);
                    $row++; $row++;


                    //KELOMPOK BAWAH
                    $sheet->setCellValue('A'.$row, 'Kelompok Bawah');
                    $row++;
                    $sheet->setCellValue('A'.$row, 'No')
                        ->setCellValue('B'.$row, 'Nomor Peserta')
                        ->setCellValue('C'.$row, 'Nama Peserta')
                        ->setCellValue('D'.$row, 'L/ P')
                        ->setCellValue('E'.$row, 'Kelas')
                        ->setCellValue('F'.$row, 'Nomor Soal / Kunci Jawaban / Jawaban');
                    $sheet->mergeCells('A'.$row.':A'.($row+2));
                    $sheet->mergeCells('B'.$row.':B'.($row+2));
                    $sheet->mergeCells('C'.$row.':C'.($row+2));
                    $sheet->mergeCells('D'.$row.':D'.($row+2));
                    $sheet->mergeCells('E'.$row.':E'.($row+2));
                    $sheet->mergeCells('F'.$row.':'.$this->conv->toStr(($col-1)).$row);
                    $sheet->getStyle('A'.$row.':'.$this->conv->toStr($col).($row+2))->getFont()->setBold( true );
                    $sheet->getStyle('A'.$row.':'.$this->conv->toStr($col).($row+2))->getAlignment()->setWrapText(true);
                    $sheet->getStyle('A'.$row.':'.$this->conv->toStr($col).($row+2))->applyFromArray($style);
                    $row_atas = $row;
                    $row++;
                    if ($dtSoal){
                        $col = 6;
                        foreach ($dtSoal as $valSoal){
                            $sheet->setCellValue($this->conv->toStr($col).$row, $valSoal->soal_nomor)
                                ->setCellValue($this->conv->toStr($col).($row+1), $this->conv->toStr($valSoal->pg_nomor));
                            $col++;
                        }
                    }
                    $sheet->setCellValue($this->conv->toStr($col).($row-1), 'JML BENAR');
                    $sheet->mergeCells($this->conv->toStr($col).($row-1).':'.$this->conv->toStr($col).($row+1));
                    $row++; $row++;

                    $group_bawah = array_slice($dtPeserta,$jmlBawah);
                    $nomor = 1;
                    foreach ($group_bawah as $valPes){
                        ini_set('max_execution_time',10000);
                        $sheet->setCellValue('A'.$row, $nomor)
                            ->setCellValue('B'.$row, $valPes->sis_nopes)
                            ->setCellValue('C'.$row, $valPes->sis_fullname)
                            ->setCellValue('D'.$row, $valPes->sis_sex)
                            ->setCellValue('E'.$row, $valPes->sis_kelas);
                        if ($dtSoal){
                            $col = 6; $iSoal = 0;
                            foreach ($dtSoal as $valSoal){
                                $dtHasil = $this->dbase->sqlRow('default',"
                                        SELECT      pg.pg_nomor,pg.pg_is_right
                                        FROM        tb_quiz_hasil AS qh
                                        LEFT JOIN tb_soal_pg AS pg ON qh.pg_id = pg.pg_id
                                        WHERE       qh.sis_id = '".$valPes->sis_id."' AND qh.quiz_id = '".$quiz_id."'
                                                    AND qh.mapel_id = '".$dtMapel->mapel_id."' AND qh.soal_id = '".$valSoal->soal_id."'
                                    ");
                                if ($dtHasil){
                                    if ($dtHasil->pg_is_right == 1){
                                        $dtSoal[$iSoal]->jml_bawah++;
                                    }
                                    $sheet->setCellValue($this->conv->toStr($col).$row, $this->conv->toStr($dtHasil->pg_nomor));
                                } else {
                                    $sheet->setCellValue($this->conv->toStr($col).$row, '');
                                }
                                $col++; $iSoal++;
                            }
                        }
                        $sheet->setCellValue($this->conv->toStr($col).$row, $valPes->qn_nilai);
                        $row++; $nomor++;
                    }
                    $sheet->setCellValue('A'.$row, 'Jumlah Peserta Menjawab Benar');
                    $sheet->mergeCells('A'.$row.':E'.$row);
                    if ($dtSoal){
                        $col = 6;
                        foreach ($dtSoal as $valSoal){
                            $sheet->setCellValue($this->conv->toStr($col).$row, $valSoal->jml_bawah);
                            $col++;
                        }
                    }
                    $sheet->getStyle('F'.$row_atas.':'.$this->conv->toStr($col).$row)->applyFromArray($style);
                    $sheet->getStyle('A'.$row_atas.':'.$this->conv->toStr($col).$row)->applyFromArray($styleBorders);
                }
                $row++; $row++;
                $row_atas = $row;
                $sheet->setCellValue('C'.$row, 'Nomor Soal')
                    ->setCellValue('C'.($row+1), 'Daya Pembeda (2(KA-KB))/n')
                    ->setCellValue('C'.($row+2), 'Keterangan')
                    ->setCellValue('C'.($row+3), 'Tingkat Kesulitan JB/n');
                $sheet->mergeCells('C'.$row.':E'.$row);
                $sheet->mergeCells('C'.($row+1).':E'.($row+1));
                $sheet->mergeCells('C'.($row+2).':E'.($row+2));
                $sheet->mergeCells('C'.($row+3).':E'.($row+3));
                if ($dtSoal){
                    $col = 6; $nomor = 1;
                    foreach ($dtSoal as $valSoal){
                        $dp = ( 2 * ($valSoal->jml_atas - $valSoal->jml_bawah) ) / (2 * $jmlAtas);
                        if ($dp < 0){
                            $dpket = 'Ditolak';
                        } elseif ($dp >= 0 && $dp < 0.25 ){
                            $dpket = 'Diperbaiki';
                        } else {
                            $dpket = 'Diterima';
                        }
                        $tk = ( $valSoal->jml_atas + $valSoal->jml_bawah ) / ( 2 * $jmlAtas );
                        if ($tk < 0.3){
                            $tk_text = 'SUKAR';
                        } elseif ($tk >= 0.3 && $tk <= 0.7){
                            $tk_text = 'SEDANG';
                        } else {
                            $tk_text = 'MUDAH';
                        }
                        $sheet->setCellValue($this->conv->toStr($col).$row, $nomor)
                            ->setCellValue($this->conv->toStr($col).($row+1), number_format($dp,2,",","."))
                            ->setCellValue($this->conv->toStr($col).($row+2), $dpket)
                            ->setCellValue($this->conv->toStr($col).($row+3), $tk_text);
                        $sheet->getStyle($this->conv->toStr($col).($row+2))->getAlignment()->setTextRotation(90);
                        $sheet->getStyle($this->conv->toStr($col).($row+3))->getAlignment()->setTextRotation(90);
                        $col++; $nomor++;
                    }
                    $sheet->getRowDimension(($row+2))->setRowHeight(70);
                    $sheet->getRowDimension(($row+3))->setRowHeight(70);
                }
                $sheet->getStyle('C'.$row_atas.':'.$this->conv->toStr($col).($row+3))->applyFromArray($style);
                $sheet->getStyle('C'.$row_atas.':'.$this->conv->toStr($col).($row+3))->applyFromArray($styleBorders);





                //}
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                if (strlen($sr_id) > 0 && $dtRuang){
                    header('Content-Disposition: attachment;filename="HASIL '.$dtQuiz->quiz_name.' - '.$dtMapel->mapel_name.' - '.$dtRuang->sr_name.'.xlsx"');
                } else {
                    header('Content-Disposition: attachment;filename="HASIL '.$dtQuiz->quiz_name.' - '.$dtMapel->mapel_name.' - ALL.xlsx"');
                }
                header('Cache-Control: max-age=0');
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                exit;
            }
        }
    }
    function cetak_hadir_peserta_all(){
	    $tapel      = $this->uri->segment(3);
	    $jn_id      = $this->uri->segment(4);

	    $dtPeserta  = $this->dbase->sqlResult('default',"
	        SELECT    s.sis_fullname,s.sis_nis,s.sis_nopes,s.sis_id,s.kk_id,s.erapor_id,s.sis_kelas
            FROM      tb_server AS sv
            LEFT JOIN tb_server_ruang AS sr ON sr.server_id = sv.server_id
            LEFT JOIN tb_ruang_member AS rm ON rm.sr_id = sr.sr_id
            LEFT JOIN tb_siswa AS s ON rm.sis_id = s.sis_id
            WHERE     sv.server_tapel = '".$tapel."' AND sv.jn_id = '".$jn_id."'
            ORDER BY  sr.sr_name,s.sis_fullname ASC
	    ");
	    if (!$dtPeserta){
	        die('Tidak ada data peserta');
        } else {
	        //$data['peserta']    = $dtPeserta;
	        $i = 0;
	        foreach ($dtPeserta as $valPes){
	            $dtPeserta[$i]      = $valPes;
	            $nisn = $this->dbase->dataRow('server','siswa',array('sis_id'=>$valPes->erapor_id),'sis_nisn');
	            if ($nisn){
	                $dtPeserta[$i]->sis_nisn = $nisn->sis_nisn;
                } else {
	                $dtPeserta[$i]->sis_nisn = '';
                }
	            $dtPeserta[$i]->jadwal = $this->dbase->sqlResult('default',"
                    SELECT    m.mapel_name,q.quiz_start
                    FROM      tb_quiz AS q
                    LEFT JOIN tb_quiz_mapel AS qm ON qm.quiz_id = q.quiz_id AND qm.qm_status = 1
                    LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id 
                    WHERE     ( m.kk_id IS NULL OR m.kk_id = '".$valPes->kk_id."') 
                              AND q.quiz_tapel = '".$tapel."' AND q.jn_id = '".$jn_id."'
                    ORDER BY  q.quiz_start ASC
                ");
	            $i++;
            }
            $this->load->library('conv');
            $data['tapel']  = $tapel;
	        $data['data']   = $dtPeserta;
	        $this->load->view('quiz/cetak_hadir_peserta_all',$data);
        }
    }
    function cetak_kartu_soal(){
	    $tapel      = $this->uri->segment(3);
	    $jn_id      = $this->uri->segment(4);
        $mapel_id   = $this->uri->segment(5);
        $dtMapel    = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
        if (!$dtMapel){
            die('Invalid MAPEL');
        } else {
            $dtSoal = $this->dbase->dataResult('default','soal',array('mapel_id'=>$mapel_id,'soal_status'=>1),'soal_score,soal_content,soal_type,soal_id','soal_nomor','ASC');
            if (!$dtSoal){
                die('Tidak ada Soal');
            } else {
                $i = 0;
                foreach ($dtSoal as $valSoal){
                    $dtSoal[$i]     = $valSoal;
                    $dtPG = $this->dbase->dataResult('default','soal_pg',array('soal_id'=>$valSoal->soal_id,'pg_status'=>1),'pg_content,pg_score,pg_is_right','pg_nomor','ASC');
                    if ($dtPG){
                        $dtSoal[$i]->pg = $dtPG;
                    }
                    $i++;
                }
                $data['JN']     = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
                $data['sch']    = $this->dbase->dataRow('default','school',array());
                $data['data']   = $dtSoal;
                $data['mapel']  = $dtMapel;
                $data['tapel']  = $tapel;
                $this->load->library('conv');
                $this->load->view('quiz/cetak_kartu_soal',$data);
            }
        }
    }
}
