<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Server extends CI_Controller {
	public function index(){
	    if(!$this->session->userdata('login')){
	        redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
	        redirect(base_url('quiz/landing'));
        }
        $data['body']   = 'server/home';
	    $data['menu']   = 'server';
	    $tapel          = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MIN(sis_tapel) AS tapel')->tapel;
	    if ($tapel){ $data['tapel'] = $tapel; } else { $data['tapel'] = date('Y'); }
        $data['jn']     = $this->dbase->dataResult('default','jenis_nilai',array('jn_status'=>1),'jn_id,jn_name');
	    if ($this->input->is_ajax_request()){
	        $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
	}
	function data_home(){
	    $json['t'] = 0; $json['msg'] = '';
	    $tapel      = $this->input->post('tapel');
	    $keyword    = $this->input->post('keyword');
	    $jn_id      = $this->input->post('jn_id');
	    $sql_jn     = "";
	    if ($jn_id){ $sql_jn = " AND s.jn_id = '".$jn_id."' "; }
	    $dtServer   = $this->dbase->sqlResult('default',"
            SELECT    s.server_jml_client,s.server_id,s.server_kode,s.server_name,s.server_tapel,Count(sr.sr_id) AS jml_ruang,Count(srm.rm_id) AS jml_pes,MAX(rm_sesi) AS jml_sesi
            FROM      tb_server AS s
            LEFT JOIN tb_server_ruang AS sr ON sr.server_id = s.server_id AND sr.sr_status = 1
            LEFT JOIN tb_ruang_member AS srm ON srm.sr_id = sr.sr_id AND srm.rm_status = 1
            WHERE     s.server_tapel = '".$tapel."' AND s.server_status = 1 AND s.server_name LIKE '%".$keyword."%' ".$sql_jn."
            GROUP BY  s.server_id
            ORDER BY  s.server_kode ASC
	    ");
	    if (!$dtServer){
	        $json['msg'] = 'Tidak ada data';
        } else {
	        $json['t']      = 1;
	        $data['data']   = $dtServer;
	        $json['html']   = $this->load->view('server/data_home',$data,true);
        }
	    die(json_encode($json));
    }
    function add_data(){
	    $tapel  = $this->uri->segment(3);
	    $jn_id  = $this->uri->segment(4);
	    $dtJN   = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
	    if (!$jn_id || !$dtJN){
	        die('Invalid parameter JENIS PENILAIAN');
        } else {
            $this->load->library('conv');
            $this->load->helper('string');
            $nomor_server = $this->dbase->dataRow('default','server',array('jn_id'=>$jn_id,'server_tapel'=>$tapel,'server_status'=>1),'COUNT(server_id) AS cnt')->cnt;
            $nomor_server = $nomor_server + 1;
            $kode_server  = 'K'.str_pad($dtJN->jn_id,3,"0",STR_PAD_LEFT).date('Y').'-'.$this->conv->toStr($nomor_server).random_string('numeric',1).strtoupper(random_string('alpha',2));
            $data['server_kode']= $kode_server;
            $data['server_name']= $dtJN->jn_sing.' '.$tapel.' - SMK MUHAMMADIYAH KANDANGHAUR - '.str_pad($nomor_server,2,"0",STR_PAD_LEFT);
	        $data['tapel']      = $tapel;
	        $data['jn']         = $dtJN;
            $this->load->view('server/add_data',$data);
        }
    }
    function add_data_submit(){
	    $json['t'] = 0; $json['msg'] = '';
	    $tapel          = $this->input->post('tapel');
	    $jml_ruang      = $this->input->post('jml_ruang');
	    $jml_client     = $this->input->post('jml_client');
	    $jn_id          = $this->input->post('jn_id');
	    $server_name    = $this->input->post('server_name');
	    $server_kode    = $this->input->post('server_kode');
	    if (!$tapel){
	        $json['msg'] = 'Invalid tahun pelajaran';
        } elseif (!$jml_ruang || $jml_ruang == 0){
	        $json['msg'] = 'Masukkan jumlah ruang';
        } elseif (!$jml_client || $jml_client == 0){
	        $json['msg'] = 'Masukkan jumlah client';
        } else {
	        $arr = array(
	            'server_kode' => $server_kode, 'server_name' => $server_name, 'jn_id' => $jn_id,
                'server_tapel' => $tapel, 'server_jml_client' => $jml_client
            );
	        $server_id = $this->dbase->dataInsert('default','server',$arr);
	        if (!$server_id){
	            $json['msg'] = 'DB ERROR';
            } else {
	            for($i = 1; $i <= $jml_ruang; $i++){
	                $this->dbase->dataInsert('default','server_ruang',array('server_id'=>$server_id,'sr_name'=>'Ruang '.$i));
                }
                $json['t'] = 1;
	            $json['msg'] = 'Server dan Ruang berhasil ditambahkan';
	            $data['data'] = $this->dbase->sqlResult('default',"
                    SELECT    s.server_jml_client,s.server_id,s.server_kode,s.server_name,s.server_tapel,Count(sr.sr_id) AS jml_ruang,Count(srm.rm_id) AS jml_pes,MAX(rm_sesi) AS jml_sesi
                    FROM      tb_server AS s
                    LEFT JOIN tb_server_ruang AS sr ON sr.server_id = s.server_id AND sr.sr_status = 1
                    LEFT JOIN tb_ruang_member AS srm ON srm.sr_id = sr.sr_id AND srm.rm_status = 1
                    WHERE     s.server_id = '".$server_id."'
                    GROUP BY  s.server_id
	            ");
	            $json['html'] = $this->load->view('server/data_home',$data,TRUE);
            }
        }
	    die(json_encode($json));
    }
    function edit_data(){
	    $server_id  = $this->uri->segment(3);
	    $dtServer   = $this->dbase->dataRow('default','server',array('server_id'=>$server_id));
	    if (!$dtServer || !$server_id){
	        die('Invalid parameter');
        } else {
	        $data['data'] = $dtServer;
	        $data['data']->jml_ruang = $this->dbase->dataRow('default','server_ruang',array('server_id'=>$server_id,'sr_status'=>1),'COUNT(sr_id) AS cnt')->cnt;
	        $this->load->view('server/edit_data',$data);
        }
    }
    function edit_data_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $server_id  = $this->input->post('server_id');
        $dtServer   = $this->dbase->dataRow('default','server',array('server_id'=>$server_id));
        $tapel      = $this->input->post('tapel');
        $jml_ruang  = $this->input->post('jml_ruang');
        $jml_client = $this->input->post('jml_client');
        if (!$server_id || !$dtServer){
            $json['msg'] = 'Invalid parameter';
        } elseif (!$tapel){
            $json['msg'] = 'Invalid tahun pelajaran';
        } elseif (!$jml_ruang || $jml_ruang == 0){
            $json['msg'] = 'Masukkan jumlah ruang';
        } elseif (!$jml_client || $jml_client == 0){
            $json['msg'] = 'Masukkan jumlah client';
        } else {
            $arr = array(
                'server_tapel' => $tapel, 'server_jml_client' => $jml_client
            );
            $this->dbase->dataUpdate('default','server',array('server_id'=>$server_id),$arr);
            $svjml_ruang = $this->dbase->dataRow('default','server_ruang',array('server_id'=>$server_id,'sr_status'=>1),'COUNT(sr_id) AS cnt')->cnt;
            if ($jml_ruang < $svjml_ruang){
                for($i = $jml_ruang + 1; $i <= $svjml_ruang; $i++){
                    $this->dbase->dataUpdate('default','server_ruang',array('server_id'=>$server_id,'sr_status'=>1,'sr_name'=>'Ruang '.$i),array('sr_status'=>0));
                }
            } elseif ($jml_ruang > $svjml_ruang){
                for($i = $svjml_ruang + 1; $i <= $jml_ruang; $i++){
                    $this->dbase->dataInsert('default','server_ruang',array('server_id'=>$server_id,'sr_name'=>'Ruang '.$i));
                }
            }
            $json['t']      = 1;
            $json['msg']    = 'Server berhasil dirubah';
        }
        die(json_encode($json));
    }
    function bulk_delete(){
        $json['t'] = 0; $json['msg'] = '';
        $sis_id     = $this->input->post('server_id');
        if (!$sis_id){
            $json['msg'] = 'Pilih data lebih dulu';
        } elseif (count($sis_id) == 0) {
            $json['msg'] = 'Pilih data lebih dulu';
        } else {
            foreach ($sis_id as $val){
                ini_set('max_execution_time',1000);
                $this->dbase->dataUpdate('default','server',array('server_id'=>$val),array('server_status'=>0));
            }
            $json['t'] = 1;
            $json['data']   = $sis_id;
            $json['msg'] = count($sis_id).' data berhasil dihapus';
        }
        die(json_encode($json));
    }
    function tapel_selected(){
	    $json['t'] = 0; $json['msg'] = '';
	    $tapel      = $this->input->post('tapel');
	    if (!$tapel){
	        $json['msg'] = 'Invalid parameter';
        } else {
	        $dtServer = $this->dbase->dataResult('default','server',array('server_status'=>1,'server_tapel'=>$tapel),'server_id,server_kode,server_name');
	        if (!$dtServer){
	            $json['msg'] = 'Tidak ada data server';
            } else {
	            $json['t'] = 1;
	            $json['data'] = $dtServer;
            }
        }
	    die(json_encode($json));
    }
    function server_selected(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $server_id  = $this->input->post('server_id');
        $dtServer   = $this->dbase->dataRow('default','server',array('server_id'),'server_id');
        if (!$tapel) {
            $json['msg'] = 'Invalid parameter';
        } elseif (!$server_id || !$dtServer){
            $json['msg'] = 'Invalid parameter SERVER';
        } else {
            $dtRuang    = $this->dbase->dataResult('default','server_ruang',array('server_id'=>$server_id,'sr_status'=>1),'sr_id,sr_name','sr_name','ASC');
            if (!$dtRuang){
                $json['msg'] = 'Tidak ada data Ruang';
            } else {
                $json['t']  = 1;
                $json['data'] = $dtRuang;
            }
        }
        die(json_encode($json));
    }
    function ruang(){
        if(!$this->session->userdata('login')){
            redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
            redirect(base_url('quiz/landing'));
        }
        $server_id  = $this->uri->segment(3);
        $dtServer   = $this->dbase->dataRow('default','server',array('server_id'=>$server_id));
        if (!$server_id || !$dtServer){
            $data['body'] = 'errors/404';
        } else {
            $data['body']   = 'server/ruang';
            $data['menu']   = 'server';
            $tapel          = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MIN(sis_tapel) AS tapel')->tapel;
            if ($tapel){ $data['tapel'] = $tapel; } else { $data['tapel'] = date('Y'); }
            $data['sr_tapel'] = $dtServer->server_tapel;
            $data['server'] = $this->dbase->dataResult('default','server',array('server_status'=>1,'server_tapel'=>$dtServer->server_tapel),'server_id,server_kode,server_name');
            $data['server_id'] = $server_id;
        }
        if ($this->input->is_ajax_request()){
            $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
    }
    function data_ruang(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $keyword    = $this->input->post('keyword');
        $server_id  = $this->input->post('server_id');
        $sql_server = "";
        if ($server_id){ $sql_server = " AND sr.server_id = '".$server_id."' "; }
        $dtServer   = $this->dbase->sqlResult('default',"
            SELECT    sr.sr_id,sr.sr_name,s.server_kode,Max(sesi.rm_sesi) AS jml_sesi
            FROM      tb_server_ruang AS sr
            LEFT JOIN tb_server AS s ON sr.server_id = s.server_id AND s.server_status = 1
            LEFT JOIN tb_ruang_member AS sesi ON sesi.sr_id = sr.sr_id AND sesi.rm_status = 1
            WHERE     (
                        sr.sr_name LIKE '%".$keyword."%'
                      )
                      AND sr.sr_status = 1 AND s.server_tapel = '".$tapel."' ".$sql_server." 
            GROUP BY  sr.sr_id,sr.sr_name
            ORDER BY  s.server_kode,sr.sr_name ASC
	    ");
        if (!$dtServer){
            $json['msg'] = 'Tidak ada data';
        } else {
            $i = 0;
            foreach ($dtServer as $val){
                $dtServer[$i]   = $val;
                $dtServer[$i]->jml_pes = $this->dbase->dataRow('default','ruang_member',array('sr_id'=>$val->sr_id,'rm_status'=>1),'COUNT(rm_id) AS cnt')->cnt;
                //$dtServer[$i]->jml_sesi= $this->dbase->dataRow('default','ruang_member',array('sr_id'=>$val->sr_id,'rm_status'=>1),'MAX(rm_sesi) AS cnt')->cnt;
                $i++;
            }
            $json['t']      = 1;
            $data['data']   = $dtServer;
            $json['html']   = $this->load->view('server/data_ruang',$data,true);
        }
        die(json_encode($json));
    }
    function add_ruang(){
        $json['t'] = 0; $json['msg'] = '';
	    $server_id  = $this->input->post('server_id');
	    $dtServer   = $this->dbase->dataRow('default','server',array('server_id'=>$server_id));
	    if (!$server_id || !$dtServer){
	        $json['msg'] = 'Invalid parameter';
        } else {
	        $jml_ruang = $this->dbase->dataRow('default','server_ruang',array('server_id'=>$server_id,'sr_status'=>1),'COUNT(sr_id) AS cnt')->cnt;
	        $jml_ruang = $jml_ruang + 1;
	        //die(var_dump($jml_ruang));
            $sr_id  = $this->dbase->dataInsert('default','server_ruang',array('server_id'=>$server_id,'sr_name'=>'Ruang '.$jml_ruang));
            if (!$sr_id){
                $json['msg'] = 'DB ERROR';
            } else {
                $data['data']   = $this->dbase->sqlResult('default',"
                    SELECT    sr.sr_id,sr.sr_name,s.server_kode,Max(sesi.rm_sesi) AS jml_sesi,Count(rm.rm_id) AS jml_pes
                    FROM      tb_server_ruang AS sr
                    LEFT JOIN tb_server AS s ON sr.server_id = s.server_id
                    LEFT JOIN tb_ruang_member AS sesi ON sesi.sr_id = sr.sr_id AND sesi.rm_status = 1
                    LEFT JOIN tb_ruang_member AS rm ON rm.sr_id = sr.sr_id AND rm.rm_status = 1
                    WHERE     sr.sr_id = '".$sr_id."'
                    GROUP BY  sr.sr_id,sr.sr_name
                    
                ");
                $json['t']      = 1;
                $json['html']   = $this->load->view('server/data_ruang',$data,TRUE);
                $json['msg']    = 'Ruang berhasil ditambahkan';
            }
        }
        die(json_encode($json));
    }
    function edit_ruang(){
        $sr_id      = $this->uri->segment(3);
        $dtRuang    = $this->dbase->dataRow('default','server_ruang',array('sr_id'=>$sr_id));
        if (!$sr_id || !$dtRuang){
            die('Invalid parameter RUANG');
        } else {
            $data['data']   = $dtRuang;
            $this->load->view('server/edit_ruang',$data);
        }
    }
    function edit_ruang_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $sr_id      = $this->input->post('sr_id');
        $sr_name    = $this->input->post('sr_name');
        $dtRuang    = $this->dbase->dataRow('default','server_ruang',array('sr_id'=>$sr_id));
        if (!$sr_id || !$dtRuang){
            $json['msg'] = 'Invalid parameter RUANG';
        } elseif (strlen(trim($sr_name)) == 0){
            $json['msg'] = 'Masukkan nama ruang';
        } else {
            $this->dbase->dataUpdate('default','server_ruang',array('sr_id'=>$sr_id),array('sr_name'=>$sr_name));
            $json['msg'] = 'Nama ruang berhasil dirubah';
            $json['t']      = 1;
        }
        die(json_encode($json));
    }
    function bulk_delete_ruang(){
	    $json['t'] = 0; $json['msg'] = '';
	    $sr_id      = $this->input->post('sr_id');
	    if (!$sr_id){
	        $json['msg'] = 'Pilih data lebih dulu';
        } elseif (count($sr_id) == 0){
	        $json['msg'] = 'Pilih data lebih dulu';
        } else {
	        foreach ($sr_id as $val){
	            $this->dbase->dataUpdate('default','server_ruang',array('sr_id'=>$val),array('sr_status'=>0));
            }
            $json['t'] = 1;
	        $json['msg'] = count($sr_id).' data berhasil dihapus';
	        $json['data'] = $sr_id;
        }
	    die(json_encode($json));
    }
    function peserta(){
        if(!$this->session->userdata('login')){
            redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
            redirect(base_url('quiz/landing'));
        }
        $sr_id      = $this->uri->segment(3);
        $dtRuang    = $this->dbase->dataRow('default','server_ruang',array('sr_id'=>$sr_id));
        if (!$sr_id || !$dtRuang){
            $data['body'] = 'errors/404';
        } else {
            $srtapel        = $this->dbase->dataRow('default','server',array('server_id'=>$dtRuang->server_id),'server_tapel');
            $data['body']   = 'server/peserta';
            $data['menu']   = 'server';
            $tapel          = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MIN(sis_tapel) AS tapel')->tapel;
            if ($tapel){ $data['tapel'] = $tapel; } else { $data['tapel'] = date('Y'); }
            $data['server'] = $this->dbase->dataResult('default','server',array('server_status'=>1,'server_tapel'=>$srtapel->server_tapel),'server_id,server_kode,server_name');
            $data['sr_id']  = $sr_id;
            $data['server_id'] = $dtRuang->server_id;
            $data['ruang']  = $this->dbase->dataResult('default','server_ruang',array('server_id'=>$dtRuang->server_id,'sr_status'=>1),'sr_id,sr_name');
        }
        if ($this->input->is_ajax_request()){
            $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
    }
    function data_peserta(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $keyword    = $this->input->post('keyword');
        $server_id  = $this->input->post('server_id');
        $sr_id      = $this->input->post('sr_id');
        $sesi       = $this->input->post('sesi');
        $sql_server = $sql_ruang = $sql_sesi = "";
        if ($server_id){ $sql_server = " AND sr.server_id = '".$server_id."' "; }
        if ($sr_id){ $sql_ruang = " AND rm.sr_id = '".$sr_id."' "; }
        if ($sesi){ $sql_sesi = " AND rm.rm_sesi = '".$sesi."' "; }
        $dtServer   = $this->dbase->sqlResult('default',"
            SELECT      rm.rm_id,s.sis_nopes,s.sis_fullname,sr.sr_name,rm.rm_sesi,s.sis_kelas
            FROM        tb_ruang_member AS rm
            LEFT JOIN   tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
            LEFT JOIN   tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1
            WHERE       (
                        sr.sr_name LIKE '%".$keyword."%' OR
                        s.sis_username LIKE '%".$keyword."%' OR
                        s.sis_fullname LIKE '%".$keyword."%'
                        ) AND rm.rm_status = 1 ".$sql_ruang.$sql_server.$sql_sesi."
            ORDER BY    sr.sr_name,s.sis_username,s.sis_fullname ASC
	    ");
        if (!$dtServer){
            $json['msg'] = 'Tidak ada data';
        } else {
            $json['t']      = 1;
            $data['data']   = $dtServer;
            $json['html']   = $this->load->view('server/data_peserta',$data,true);
        }
        die(json_encode($json));
    }
    function add_peserta(){
        $tapel          = $this->uri->segment(3);
        $server_id      = $this->uri->segment(4);
        $sr_id          = $this->uri->segment(5);
        $sesi           = $this->uri->segment(6);
        $dtServer       = $this->dbase->dataRow('default','server',array('server_id'=>$server_id),'server_id,server_kode');
        $dtRuang        = $this->dbase->dataRow('default','server_ruang',array('sr_id'=>$sr_id),'sr_id,sr_name');
        if (!$tapel){
            die('Invalid parameter TAPEL');
        } elseif (!$server_id || !$dtServer){
            die('Invalid parameter SERVER');
        } elseif (!$sr_id || !$dtRuang) {
            die('Invalid parameter RUANG');
        } elseif (!$sesi){
            die('Pilih sesi lebih dulu');
        } else {
            $data['kk']     = $this->dbase->dataResult('default','keahlian_kompetensi',array('kk_status'=>1));
            $data['ruang']  = $dtRuang;
            $data['server'] = $dtServer;
            $data['tapel']  = $tapel;
            $data['sesi']   = $sesi;
            $this->load->view('server/add_peserta',$data);
        }
    }
    function max_selected(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $keyword    = $this->input->post('keyword');
        $server_id  = $this->input->post('server_id');
        $sr_id      = $this->input->post('sr_id');
        $limit      = $this->input->post('limit');
        $page       = $this->input->post('page');
        if (!$page){ $page = 1; }
        $jmlData    = $this->dbase->sqlRow('default',"
            SELECT    COUNT(s1.sis_id) AS cnt
            FROM      tb_siswa AS s1
            WHERE     s1.sis_status = 1 
                      AND s1.sis_id NOT IN (
                        SELECT      rm.sis_id
                        FROM        tb_ruang_member AS rm
                        LEFT JOIN   tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                        LEFT JOIN   tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1
                        LEFT JOIN   tb_server AS sv ON sr.server_id = sv.server_id
                        WHERE       (
                                    sr.sr_name LIKE '%".$keyword."%' OR
                                    s.sis_username LIKE '%".$keyword."%' OR
                                    s.sis_fullname LIKE '%".$keyword."%'
                                    ) AND rm.rm_status = 1  AND sv.server_tapel = '".$tapel."'
                      )
              ORDER BY s1.sis_kelas,s1.sis_username,s1.sis_fullname ASC
        ")->cnt;
        if ($limit){ $nopage     = ceil($jmlData / $limit); } else { $nopage = 1; }
        $json['nopage'] = $nopage;
        $json['t'] = 1;
        die(json_encode($json));
    }
    function form_add_data_peserta(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $keyword    = $this->input->post('keyword');
        $server_id  = $this->input->post('server_id');
        $sr_id      = $this->input->post('sr_id');
        $limit      = $this->input->post('limit');
        $page       = $this->input->post('page');
        if (!$page){ $page = 1; }
        $sql_limit  = "";
        if (!$limit){ $limit = 1000000; }
        $offset = ($page - 1) * $limit;
        $json['offset'] = $offset;
        if ($limit){ $sql_limit = " LIMIT ".$offset.",".$limit; }
        $dtPes      = $this->dbase->sqlResult('default',"
            SELECT    s1.sis_id,s1.sis_nopes,s1.sis_fullname,s1.sis_sex,s1.sis_kelas
            FROM      tb_siswa AS s1
            LEFT JOIN tb_keahlian_kompetensi AS kk ON s1.kk_id = kk.kk_id
            WHERE     s1.sis_status = 1 
                      AND s1.sis_id NOT IN (
                        SELECT      rm.sis_id
                        FROM        tb_ruang_member AS rm
                        LEFT JOIN   tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                        LEFT JOIN   tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1
                        LEFT JOIN   tb_server AS sv ON sr.server_id = sv.server_id
                        WHERE       (
                                    sr.sr_name LIKE '%".$keyword."%' OR
                                    s.sis_username LIKE '%".$keyword."%' OR
                                    s.sis_fullname LIKE '%".$keyword."%'
                                    ) AND rm.rm_status = 1  AND sv.server_tapel = '".$tapel."'
                      )
              ORDER BY kk.kk_urut,s1.sis_kelas,s1.sis_username,s1.sis_fullname ASC
              ".$sql_limit."
        ");
        if (!$dtPes){
            $json['msg'] = 'Tidak ada data';
        } else {
            $json['t']      = 1;
            $data['data']   = $dtPes;
            $json['html']   = $this->load->view('server/form_add_data_peserta',$data,true);
        }
        die(json_encode($json));
    }
    function add_peserta_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel          = $this->input->post('tapel');
        $server_id      = $this->input->post('server_id');
        $sr_id          = $this->input->post('sr_id');
        $sesi           = $this->input->post('sesi');
        $data_siswa     = $this->input->post('sis_id');
        $dtServer       = $this->dbase->dataRow('default','server',array('server_id'=>$server_id),'server_id');
        $dtRuang        = $this->dbase->dataRow('default','server_ruang',array('sr_id'=>$sr_id),'sr_id');
        if (!$tapel){
            $json['msg'] = 'Invalid parameter TAPEL';
        } elseif (!$server_id || !$dtServer){
            $json['msg'] = 'Invalid parameter SERVER';
        } elseif (!$sr_id || !$dtRuang){
            $json['msg'] = 'Invalid parameter RUANG';
        } elseif (!$sesi){
            $json['msg'] = 'Invalid parameter SESI';
        } elseif (!$data_siswa) {
            $json['msg'] = 'Pilih siswa';
        } elseif (count($data_siswa) == 0){
            $json['msg'] = 'Pilih siswa';
        } else {
            foreach ($data_siswa as $sis_id){
                $chkRM = $this->dbase->dataRow('default','ruang_member',array('sr_id'=>$sr_id,'sis_id'=>$sis_id,'rm_sesi'=>$sesi),'rm_id');
                if ($chkRM){
                    $this->dbase->dataUpdate('default','ruang_member',array('rm_id'=>$chkRM->rm_id),array('rm_status'=>1));
                } else {
                    $this->dbase->dataInsert('default','ruang_member',array('sr_id'=>$sr_id,'sis_id'=>$sis_id,'rm_sesi'=>$sesi));
                }
            }
            $json['t']      = 1;
            $json['msg']    = count($data_siswa).' siswa berhasil dijadikan peserta';
        }
        die(json_encode($json));
    }
    function bulk_delete_peserta(){
	    $json['t'] = 0; $json['msg'] = '';
	    $data_pes   = $this->input->post('rm_id');
	    if (!$data_pes){
	        $json['msg'] = 'Pilih peserta';
        } elseif (count($data_pes) == 0){
            $json['msg'] = 'Pilih peserta';
        } else {
	        foreach ($data_pes as $rm_id){
	            $this->dbase->dataUpdate('default','ruang_member',array('rm_id'=>$rm_id),array('rm_status'=>0));
            }
            $json['t'] = 1;
	        $json['data'] = $data_pes;
	        $json['msg'] = 'Data berhasil dihapus';
        }
	    die(json_encode($json));
    }
    function cetak_kartu(){
        $tapel      = $this->uri->segment(3);
        $jn_id      = $this->uri->segment(4);
        $dtJN       = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
        if (!$jn_id || !$dtJN){
            die('Pilih Jenis Penilaian Lebih Dulu');
        } elseif (!$tapel){
            die('Pilih Tahun Pelajaran');
        } else {
            $dtServer   = $this->dbase->dataResult('default','server',array('server_tapel'=>$tapel,'jn_id'=>$jn_id,'server_status'=>1),'server_id,server_kode,server_tapel','server_kode','ASC');
            if (!$dtServer){
                die('Tidak ada SERVER LOKAL');
            } else {
                $s = 0;
                foreach ($dtServer as $valServer){
                    $dtServer[$s]   = $valServer;
                    $dtRuang        = $this->dbase->dataResult('default','server_ruang',array('server_id'=>$valServer->server_id,'sr_status'=>1),'sr_id,sr_name','sr_name','ASC');
                    if ($dtRuang){
                        $r = 0;
                        foreach ($dtRuang as $valRuang){
                            $dtRuang[$r]    = $valRuang;
                            $dtPes          = $this->dbase->sqlResult('default',"
                                SELECT    s.sis_nis,s.sis_nopes,s.sis_username,s.sis_password,s.sis_fullname
                                FROM      tb_ruang_member AS rm
                                LEFT JOIN tb_siswa AS s ON rm.sis_id = s.sis_id AND s.sis_status = 1
                                WHERE     rm.sr_id = '".$valRuang->sr_id."' AND rm.rm_status = 1
                                ORDER BY  s.sis_nopes ASC
                            ");
                            $dtRuang[$r]->peserta = $dtPes;
                            $r++;
                        }
                    }
                    $dtServer[$s]->ruang = $dtRuang;
                    $s++;
                }
                $data['jn']     = $dtJN;
                $data['data']   = $dtServer;
                $this->load->view('server/cetak_kartu',$data);
            }
        }

    }
    function pengawas(){
        if(!$this->session->userdata('login')){
            redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
            redirect(base_url('quiz/landing'));
        }
        $sr_id      = $this->uri->segment(3);
        $dtRuang    = $this->dbase->dataRow('default','server_ruang',array('sr_id'=>$sr_id));
        if (!$sr_id || !$dtRuang){
            $data['body'] = 'errors/404';
        } else {
            $srtapel        = $this->dbase->dataRow('default','server',array('server_id'=>$dtRuang->server_id),'server_tapel');
            $data['body']   = 'server/pengawas';
            $data['menu']   = 'server';
            $tapel          = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MIN(sis_tapel) AS tapel')->tapel;
            if ($tapel){ $data['tapel'] = $tapel; } else { $data['tapel'] = date('Y'); }
            $data['server'] = $this->dbase->dataResult('default','server',array('server_status'=>1,'server_tapel'=>$srtapel->server_tapel),'server_id,server_kode,server_name');
            $data['sr_id']  = $sr_id;
            $data['server_id'] = $dtRuang->server_id;
            $data['ruang']  = $this->dbase->dataResult('default','server_ruang',array('server_id'=>$dtRuang->server_id,'sr_status'=>1),'sr_id,sr_name');
        }
        if ($this->input->is_ajax_request()){
            $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
    }
    function data_pengawas(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $keyword    = $this->input->post('keyword');
        $server_id  = $this->input->post('server_id');
        $sr_id      = $this->input->post('sr_id');
        $sesi       = $this->input->post('sesi');
        $sql_server = $sql_ruang = $sql_sesi = "";
        if ($server_id){ $sql_server = " AND sr.server_id = '".$server_id."' "; }
        if ($sr_id){ $sql_ruang = " AND rm.sr_id = '".$sr_id."' "; }
        if ($sesi){ $sql_sesi = " AND rm.rm_sesi = '".$sesi."' "; }
        $dtServer   = $this->dbase->sqlResult('default',"
            SELECT    p.pn_nomor,p.pn_fullname,p.pn_id,rm.rm_id,sr.sr_name
            FROM      tb_ruang_member AS rm
            LEFT JOIN tb_pengawas AS p ON rm.pn_id = p.pn_id AND p.pn_status = 1
            LEFT JOIN tb_server_ruang AS sr ON rm.sr_id = sr.sr_id AND sr.sr_status = 1
            WHERE       (
                        sr.sr_name LIKE '%".$keyword."%' OR
                        p.p_username LIKE '%".$keyword."%' OR
                        p.p_fullname LIKE '%".$keyword."%'
                        ) AND rm.rm_status = 1 ".$sql_ruang.$sql_server.$sql_sesi."
            ORDER BY    sr.sr_name,p.p_fullname ASC
	    ");
        if (!$dtServer){
            $json['msg'] = 'Tidak ada data';
        } else {
            $json['t']      = 1;
            $data['data']   = $dtServer;
            $json['html']   = $this->load->view('server/data_pengawas',$data,true);
        }
        die(json_encode($json));
    }
}
