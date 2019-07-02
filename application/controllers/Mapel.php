<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapel extends CI_Controller {
	public function index(){
	    if(!$this->session->userdata('login')){
	        redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
	        redirect(base_url('quiz/landing'));
        }
        $data['body']   = 'mapel/home';
	    $data['menu']   = 'mapel';
	    $data['kk']     = $this->dbase->dataResult('default','keahlian_kompetensi',array('kk_status'=>1),'kk_id,kk_name,kk_kode');
	    if ($this->input->is_ajax_request()){
	        $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
	}
	function data_home(){
	    $json['t'] = 0; $json['msg'] = '';
	    $keyword    = $this->input->post('keyword');
	    $kk_id      = $this->input->post('kk_id');
	    $tingkat    = $this->input->post('tingkat');
	    $sql_kk = $sql_tingkat = "";
	    if ($kk_id){ $sql_kk = " AND m.kk_id = '".$kk_id."' "; }
	    if ($tingkat){ $sql_tingkat = " AND m.mapel_tingkat = '".$tingkat."' ";}

	    $dtMapel    = $this->dbase->sqlResult('default',"
	        SELECT      m.mapel_id,m.mapel_name,kk.kk_name,Count(s.soal_id) AS jml_soal
            FROM        tb_mapel AS m
            LEFT JOIN   tb_keahlian_kompetensi AS kk ON m.kk_id = kk.kk_id AND kk.kk_status = 1
            LEFT JOIN   tb_soal AS s ON s.mapel_id = m.mapel_id AND s.soal_status = 1
            WHERE       (
                          m.mapel_name LIKE '%".$keyword."%' OR
                          kk.kk_name LIKE '%".$keyword."%'
                        ) AND m.mapel_status = 1 ".$sql_tingkat.$sql_kk."
            GROUP BY    m.mapel_id,m.mapel_name
            ORDER BY    m.mapel_group,m.kk_id,m.mapel_name ASC
	    ");
	    if (!$dtMapel){
	        $json['msg'] = 'Tidak ada data';
        } else {
	        $data['data']   = $dtMapel;
	        $json['t']      = 1;
	        $json['html']   = $this->load->view('mapel/data_home',$data,TRUE);
        }
	    die(json_encode($json));
    }
    function cari_mapel(){
	    $json['t'] = 0; $json['msg'] = '';
	    $kk_id      = $this->input->post('kk_id');
	    $tingkat    = $this->input->post('tingkat');
	    $sql_tingkat = $sql_kk = "";
        if ($kk_id){ $sql_kk = " AND m.kk_id = '".$kk_id."' "; }
        if ($tingkat){ $sql_tingkat = " AND m.mapel_tingkat = '".$tingkat."' ";}
	    $dtMapel    = $this->dbase->sqlResult('default',"
	        SELECT    m.mapel_id,m.mapel_name
	        FROM      tb_mapel AS m
	        WHERE     m.mapel_status = 1 ".$sql_kk.$sql_tingkat."
	        ORDER BY  m.mapel_group,m.kk_id,m.mapel_name ASC
	    ");
        if (!$dtMapel){
            $json['msg'] = 'Tidak ada Mata Pelajaran';
        } else {
            $json['t']      = 1;
            $json['data']   = $dtMapel;
        }
	    die(json_encode($json));
    }
    function bank_soal(){
        if(!$this->session->userdata('login')){
            redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
            redirect(base_url('quiz/landing'));
        }
        $mapel_id       = $this->uri->segment(3);
        $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id),'mapel_id,mapel_name,mapel_tingkat,kk_id');
        if ($dtMapel){
            $data['data']   = $dtMapel;
        }
        $data['mapel']  = $this->dbase->dataResult('default','mapel',array('mapel_status'=>1),'mapel_id,mapel_name,mapel_tingkat','mapel_group,kk_id,mapel_name','ASC');
        $data['body']   = 'mapel/bank_soal';
        $data['menu']   = 'mapel';
        $data['kk']     = $this->dbase->dataResult('default','keahlian_kompetensi',array('kk_status'=>1),'kk_id,kk_name,kk_kode');
        if ($this->input->is_ajax_request()){
            $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
    }
    function data_bank_soal(){
	    $json['t'] = 0; $json['msg'] = '';
	    $mapel_id   = $this->input->post('mapel_id');
	    $keyword    = $this->input->post('keyword');
	    $dtSoal     = $this->dbase->sqlResult('default',"
	        SELECT    s.soal_nomor,s.soal_content,s.soal_id,s.soal_type,s.soal_score
            FROM      tb_soal AS s
            LEFT JOIN tb_soal_pg AS sp ON sp.soal_id = s.soal_id
            WHERE     (
                      s.soal_content LIKE '%".$keyword."%' OR
                      sp.pg_content LIKE '%".$keyword."%'
                      ) AND s.soal_status = 1 AND s.mapel_id = '".$mapel_id."'
            GROUP BY  s.soal_id
            ORDER BY  s.soal_nomor ASC
	    ");
	    if (!$dtSoal){
	        $json['msg'] = 'Tidak ada data';
        } else {
	        $i = 0;
	        foreach ($dtSoal as $val){
	            $dtSoal[$i]     = $val;
	            $dtSoal[$i]->pg = $this->dbase->dataResult('default','soal_pg',array('soal_id'=>$val->soal_id,'pg_status'=>1),'pg_id,pg_nomor,pg_content,pg_is_right,pg_score,soal_id','pg_nomor','ASC');
	            $i++;
            }
	        $this->load->library('conv');
	        $data['data']   = $dtSoal;
	        $json['t']      = 1;
	        $json['html']   = $this->load->view('mapel/data_bank_soal',$data,TRUE);
        }
	    die(json_encode($json));
    }
    function add_bank_soal(){
	    $kk_id      = (int)$this->uri->segment(5);
	    $tingkat    = $this->uri->segment(3);
	    $mapel_id   = $this->uri->segment(4);
	    $dtKK       = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$kk_id));
	    $dtMapel    = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
	    if (strlen($kk_id) > 0 && $kk_id > 0 && !$dtKK){
	        die('Invalid parameter KOMPETENSI');
        } elseif (!$tingkat){
	        die('Invalid parameter TINGKAT');
        } elseif (!$mapel_id || !$dtMapel){
	        die('Invalid parameter MAPEL');
        } else {
	        if ($dtKK){
	            $data['kk'] = $dtKK;
            }
	        $data['mapel']  = $dtMapel;
	        $data['tingkat']= $tingkat;
	        $this->load->view('mapel/add_bank_soal',$data);
        }
    }
    function add_bank_soal_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $tingkat        = $this->input->post('tingkat');
        $kk_id          = (int)$this->input->post('kk_id');
        $mapel_id       = $this->input->post('mapel_id');
        $soal_type      = $this->input->post('soal_type');
        $soal_score     = (int)$this->input->post('soal_score');
        $soal_content   = $this->input->post('soal_content');
        $dtKK           = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$kk_id));
        $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
        if (strlen($kk_id) && $kk_id > 0 && !$dtKK){
            $json['msg'] = 'Invalid parameter KOMPETENSI';
        } elseif (!$tingkat){
            $json['msg'] = 'Invalid parameter TINGKAT';
        } elseif (!$mapel_id || !$dtMapel){
            $json['msg'] = 'Invalid parameter MAPEL';
        } elseif (!$soal_score || $soal_score == 0){
            $json['msg'] = 'Skor maksimal belum diisi';
        } elseif (strlen(trim($soal_content)) == 0){
            $json['msg'] = 'Masukkan isi soal';
        } else {
            $nomor  = $this->dbase->dataRow('default','soal',array('mapel_id'=>$mapel_id,'soal_status'=>1),'COUNT(soal_id) AS cnt')->cnt;
            $nomor  = $nomor + 1;
            $soal_id= $this->dbase->dataRow('default','soal',array('mapel_id'=>$mapel_id),'COUNT(soal_id) AS cnt')->cnt;
            $soal_id= $soal_id + 1;
            $soal_id= 's_'.str_pad($mapel_id,10,"0",STR_PAD_LEFT).'_'.str_pad($soal_id,10,"0",STR_PAD_LEFT);
            $arr = array(
                'soal_id'=>$soal_id, 'mapel_id' => $mapel_id, 'soal_nomor' => $nomor, 'soal_content' => $soal_content, 'soal_type' => $soal_type, 'soal_score' => $soal_score
            );
            $this->dbase->dataInsert('default','soal',$arr);
            if (!$soal_id){
                $json['msg']    = 'DB Error';
            } else {
                $this->load->library('conv');
                $data['data']   = $this->dbase->sqlResult('default',"
                    SELECT    s.soal_nomor,s.soal_content,s.soal_id,s.soal_type,s.soal_score
                    FROM      tb_soal AS s
                    LEFT JOIN tb_soal_pg AS sp ON sp.soal_id = s.soal_id
                    WHERE     s.soal_id = '".$soal_id."'
                ");
                $data['data'][0]->pg = array();
                $json['t']      = 1;
                $json['html']   = $this->load->view('mapel/data_bank_soal',$data,TRUE);
                $json['msg']    = 'Soal berhasil ditambahkan';
            }
        }
        die(json_encode($json));
    }
    function edit_bank_soal(){
	    $soal_id    = $this->uri->segment(3);
	    $dtSoal     = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id));
	    if (!$soal_id || !$dtSoal){
	        die('Invalid parameter SOAL');
        } else {
	        $dtMapel    = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$dtSoal->mapel_id));
	        if (!$dtMapel){
	            die('Invalid parameter MAPEL');
            } else {
	            $dtKK   = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$dtMapel->kk_id));
	            if ($dtKK){ $data['kk'] = $dtKK; }
	            $data['mapel']  = $dtMapel;
	            $data['data']   = $dtSoal;
	            $data['tingkat']= $dtMapel->mapel_tingkat;
	            $this->load->view('mapel/edit_bank_soal',$data);
            }
        }
    }
    function edit_bank_soal_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $soal_id        = $this->input->post('soal_id');
        $tingkat        = $this->input->post('tingkat');
        $kk_id          = (int)$this->input->post('kk_id');
        $mapel_id       = $this->input->post('mapel_id');
        $soal_type      = $this->input->post('soal_type');
        $soal_score     = (int)$this->input->post('soal_score');
        $soal_content   = $this->input->post('soal_content');
        $dtSoal         = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id));
        $dtKK           = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$kk_id));
        $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
        if (!$soal_id || !$dtSoal){
            $json['msg'] = 'Invalid parameter SOAL';
        } elseif (strlen($kk_id) && $kk_id > 0 && !$dtKK){
            $json['msg'] = 'Invalid parameter KOMPETENSI';
        } elseif (!$tingkat){
            $json['msg'] = 'Invalid parameter TINGKAT';
        } elseif (!$mapel_id || !$dtMapel){
            $json['msg'] = 'Invalid parameter MAPEL';
        } elseif (!$soal_score || $soal_score == 0){
            $json['msg'] = 'Skor maksimal belum diisi';
        } elseif (strlen(trim($soal_content)) == 0){
            $json['msg'] = 'Masukkan isi soal';
        } else {
            $this->dbase->dataUpdate('default','soal',array('soal_id'=>$soal_id),array('soal_content'=>$soal_content,'soal_type'=>$soal_type,'soal_score'=>$soal_score));
            $json['t']          = 1;
            $json['content']    = $soal_content;
            $json['type']       = $soal_type;
            $json['id']         = $soal_id;
            $json['score']      = $soal_score;
        }
        die(json_encode($json));
    }
    function add_pg(){
	    $soal_id    = $this->uri->segment(3);
	    $dtSoal     = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id),'soal_content,soal_id,soal_score,mapel_id');
	    //die(var_dump($dtSoal));
        if (!$soal_id || !$dtSoal){
            die('Invalid parameter SOAL');
        } else {
            $data['data']   = $dtSoal;
            $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$dtSoal->mapel_id));
            if (!$dtMapel){
                die('Invalid parameter MAPEL');
            } else {
                $data['mapel'] = $dtMapel;
                $this->load->view('mapel/add_pg',$data);
            }
        }
    }
    function add_pg_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $soal_id        = $this->input->post('soal_id');
        $pg_score       = (int)$this->input->post('pg_score');
        $pg_is_right    = (int)$this->input->post('pg_is_right');
        $pg_content     = $this->input->post('pg_content');
        $dtSoal         = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id),'soal_score,soal_id');
        $max_score      = $this->dbase->dataRow('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1),'SUM(pg_score) AS nomor')->nomor;
        $max_score      = $max_score + $pg_score;
        if (!$dtSoal || !$soal_id) {
            $json['msg'] = 'Invalid parameter SOAL';
        } elseif ($max_score > $dtSoal->soal_score){
            $json['msg'] = 'Skor melebihi jumlah maksimal yang sudah ditentukan di data soal';
        } elseif (strlen($pg_content) == 0){
            $json['msg'] = 'Isi jawaban belum diisi';
        } else {
            $nomor = $this->dbase->dataRow('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1),'COUNT(pg_id) AS cnt')->cnt;
            $nomor = $nomor + 1;
            $nomorL = $this->dbase->dataRow('default','soal_pg',array('soal_id'=>$soal_id),'COUNT(pg_id) AS cnt')->cnt;
            $nomorL = $nomorL + 1;
            $pg_id = substr($soal_id,-21);
            $pg_id = 'pg_'.$pg_id.'_';
            $pg_id = $pg_id.str_pad($nomorL,10,"0",STR_PAD_LEFT);
            if ($pg_is_right == 1){
                $this->dbase->dataUpdate('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1,'pg_is_right'=>1),array('pg_is_right'=>0));
            }
            $arr = array(
                'pg_id' => $pg_id, 'soal_id' => $soal_id, 'pg_nomor' => $nomor, 'pg_content' => $pg_content, 'pg_score' => $pg_score, 'pg_is_right' => $pg_is_right
            );
            $this->dbase->dataInsert('default','soal_pg',$arr);
            if (!$pg_id){
                $json['msg'] = 'DB Error';
            } else {
                $this->load->library('conv');
                $data['data']   = $this->dbase->dataResult('default','soal_pg',array('pg_id'=>$pg_id),'pg_nomor,pg_id,pg_content,pg_is_right,pg_score,soal_id');
                $json['t']      = 1;
                $json['id']     = $soal_id;
                $json['html']   = $this->load->view('mapel/data_pg',$data,TRUE);
            }
        }
        die(json_encode($json));
    }
    function soal_bulk_delete(){
	    $json['t'] = 0; $json['msg'] = '';
	    $data_soal  = $this->input->post('soal_id');
	    if (!$data_soal){
	        $json['msg'] = 'Pilih data lebih dulu';
        } elseif (count($data_soal) == 0){
	        $json['msg'] = 'Pilih data lebih dulu';
        } else {
	        foreach ($data_soal as $soal_id){
	            $dtSoal = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id),'mapel_id');
	            if ($dtSoal){
	                $mapel_id   = $dtSoal->mapel_id;
                    $this->dbase->dataUpdate('default','soal',array('soal_id'=>$soal_id),array('soal_status'=>0));
                }
            }
            $this->update_nomor_soal($mapel_id);
            $json['t'] = 1;
	        $json['data'] = $data_soal;
	        $json['msg'] = 'Data berhasil dihapus';
        }
	    die(json_encode($json));
    }
    function delete_soal(){
        $json['t'] = 0; $json['msg'] = '';
        $data_soal  = $this->input->post('soal_id');
        $dtSoal     = $this->dbase->dataRow('default','soal',array('soal_id'=>$data_soal));
        if (!$data_soal || !$dtSoal){
            $json['msg'] = 'Pilih data lebih dulu';
        } else {
            $this->dbase->dataUpdate('default','soal',array('soal_id'=>$data_soal),array('soal_status'=>0));
            $this->update_nomor_soal($dtSoal->mapel_id,$dtSoal->soal_nomor);
            $json['t'] = 1;
            $json['msg'] = 'Data berhasil dihapus';
        }
        die(json_encode($json));
    }
    function update_nomor_soal($mapel_id,$past_nomor=FALSE){
	    $nomor  = 1;
	    if ($past_nomor){
            $prev   = $this->dbase->dataResult('default','soal',array('mapel_id'=>$mapel_id,'soal_status'=>1,'soal_nomor <'=>$past_nomor),'soal_id','soal_nomor','ASC');
            $next   = $this->dbase->dataResult('default','soal',array('mapel_id'=>$mapel_id,'soal_status'=>1,'soal_nomor >'=>$past_nomor),'soal_id','soal_nomor','ASC');
        } else {
            $prev   = $this->dbase->dataResult('default','soal',array('mapel_id'=>$mapel_id,'soal_status'=>1),'soal_id','soal_nomor','ASC');
            $next   = array();
        }
        if ($prev){
	        foreach ($prev as $val){
	            $this->dbase->dataUpdate('default','soal',array('soal_id'=>$val->soal_id),array('soal_nomor'=>$nomor));
	            $nomor++;
            }
        }
        if ($next){
	        foreach ($next as $val){
                $this->dbase->dataUpdate('default','soal',array('soal_id'=>$val->soal_id),array('soal_nomor'=>$nomor));
                $nomor++;
            }
        }
    }
    function delete_pg(){
        $json['t'] = 0; $json['msg'] = '';
        $data_soal  = $this->input->post('soal_id');
        $dtSoal     = $this->dbase->dataRow('default','soal_pg',array('pg_id'=>$data_soal));
        if (!$data_soal || !$dtSoal){
            $json['msg'] = 'Pilih data lebih dulu';
        } else {
            $this->dbase->dataUpdate('default','soal_pg',array('pg_id'=>$data_soal),array('pg_status'=>0));
            $this->update_nomor_pg($dtSoal->soal_id,$dtSoal->pg_nomor);
            $json['t'] = 1;
            $json['msg'] = 'Data berhasil dihapus';
        }
        die(json_encode($json));
    }
    function update_nomor_pg($soal_id,$past_nomor=FALSE){
        $nomor  = 1;
        if ($past_nomor){
            $prev   = $this->dbase->dataResult('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1,'pg_nomor <'=>$past_nomor),'pg_id','pg_nomor','ASC');
            $next   = $this->dbase->dataResult('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1,'pg_nomor >'=>$past_nomor),'pg_id','pg_nomor','ASC');
        } else {
            $prev   = $this->dbase->dataResult('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1),'pg_id','pg_nomor','ASC');
            $next   = array();
        }
        if ($prev){
            foreach ($prev as $val){
                $this->dbase->dataUpdate('default','soal_pg',array('pg_id'=>$val->pg_id),array('pg_nomor'=>$nomor));
                $nomor++;
            }
        }
        if ($next){
            foreach ($next as $val){
                $this->dbase->dataUpdate('default','soal_pg',array('pg_id'=>$val->pg_id),array('pg_nomor'=>$nomor));
                $nomor++;
            }
        }
    }
    function edit_pg(){
	    $pg_id      = $this->uri->segment(3);
	    $dtPG       = $this->dbase->dataRow('default','soal_pg',array('pg_id'=>$pg_id));
	    if (!$pg_id || !$dtPG){
	        die('Invalid parameter JAWABAN');
        } else {
	        $dtSoal = $this->dbase->dataRow('default','soal',array('soal_id'=>$dtPG->soal_id));
	        if (!$dtSoal){
	            die('Invalid parameter SOAL');
            } else {
	            $dtMapel    = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$dtSoal->mapel_id));
	            if (!$dtMapel){
	                die('Invalid parameter MAPEL');
                } else {
	                $data['mapel']  = $dtMapel;
                    $data['data']   = $dtPG;
                    $data['soal']   = $dtSoal;
                    $this->load->view('mapel/edit_pg',$data);
                }
            }
        }
    }
    function edit_pg_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $pg_id          = $this->input->post('pg_id');
        $dtPG           = $this->dbase->dataRow('default','soal_pg',array('pg_id'=>$pg_id));
        $soal_id        = $this->input->post('soal_id');
        $pg_score       = (int)$this->input->post('pg_score');
        $pg_is_right    = (int)$this->input->post('pg_is_right');
        $pg_content     = $this->input->post('pg_content');
        $dtSoal         = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id),'soal_score,soal_id');
        $max_score      = $this->dbase->dataRow('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1,'pg_id !='=>$pg_id),'SUM(pg_score) AS nomor')->nomor;
        $max_score      = $max_score + $pg_score;
        if (!$pg_id || !$dtPG){
            $json['msg'] = 'Invalid parameter JAWABAN';
        } elseif (!$dtSoal || !$soal_id) {
            $json['msg'] = 'Invalid parameter SOAL';
        } elseif ($pg_score > $dtSoal->soal_score){
            $json['msg'] = 'Skor melebihi jumlah maksimal yang sudah ditentukan di data soal';
        } elseif ($max_score > $dtSoal->soal_score){
            $json['msg'] = 'Skor melebihi jumlah maksimal yang sudah ditentukan di data soal';
        } elseif (strlen($pg_content) == 0){
            $json['msg'] = 'Isi jawaban belum diisi';
        } else {
            $this->dbase->dataUpdate('default','soal_pg',array('pg_id'=>$pg_id),array('pg_content'=>$pg_content,'pg_is_right'=>$pg_is_right,'pg_score'=>$pg_score));
            $json['t']          = 1;
            $json['id']         = $pg_id;
            $json['content']    = $pg_content;
            $json['score']      = $pg_score;
        }
        die(json_encode($json));
    }
    function set_jawaban(){
	    $json['t'] = 0; $json['msg'] = '';
	    $soal_id        = $this->input->post('soal_id');
	    $pg_id          = $this->input->post('pg_id');
	    $dtSoal         = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id));
	    $dtPG           = $this->dbase->dataRow('default','soal_pg',array('pg_id'=>$pg_id));
	    if (!$soal_id || !$dtSoal){
	        $json['msg'] = 'Invalid parameter SOAL';
        } elseif (!$pg_id || !$dtPG){
	        $json['msg'] = 'Invalid parameter JAWABAN';
        } else {
            $this->dbase->dataUpdate('default','soal_pg',array('soal_id'=>$soal_id,'pg_is_right'=>1),array('pg_is_right'=>0));
	        $this->dbase->dataUpdate('default','soal_pg',array('pg_id'=>$pg_id),array('pg_is_right'=>1));
	        $json['t'] = 1;
	        $json['msg'] = 'Jawaban berhasil diupdate';
        }
	    die(json_encode($json));
    }
    function download_format_soal(){
	    $mapel_id   = $this->uri->segment(3);
	    $dtMapel    = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id),'mapel_name,mapel_tingkat');
	    if (!$mapel_id || !$dtMapel){
	        die('Invalid parameter MAPEL');
        } else {
            $this->load->library(array('PHPExcel','PHPExcel/IOFactory','conv'));
            $excel2 = IOFactory::createReader('Excel2007');
            $excel2 = $excel2->load(FCPATH . 'assets/format/upload_soal.xlsx');
            $excel2->setActiveSheetIndex(0);
            $excel2->getActiveSheet()->setCellValue('C1',$mapel_id)
                ->setCellValue('C2',': '.$dtMapel->mapel_name)
                ->setCellValue('C4',': '.$dtMapel->mapel_tingkat);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Import Soal '.$dtMapel->mapel_name.'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = IOFactory::createWriter($excel2, 'Excel2007');
            $objWriter->save('php://output');
        }
    }
    function import_bank_soal(){
	    $tingkat        = $this->uri->segment(3);
	    $mapel_id       = $this->uri->segment(4);
	    $kk_id          = $this->uri->segment(5);
	    $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
        $dtKK           = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$kk_id));
	    if (!$mapel_id || !$dtMapel){
	        die('Invalid parameter MAPEL');
        } else {
	        if ($dtKK){ $data['kk'] = $dtKK; }
	        $data['mapel'] = $dtMapel;
	        $this->load->view('mapel/import_bank_soal',$data);
        }
    }
    function import_bank_soal_submit(){
	    $json['t'] = 0; $json['msg'] = '';
	    $mapel_id       = $this->input->post('mapel_id');
	    $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
	    if (!$mapel_id || !$dtMapel){
	        $json['msg'] = 'Invalid parameter MAPEL';
        } else {
            $this->load->library(array('PHPExcel','PHPExcel/IOFactory','conv'));
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array( 'memoryCacheSize' => '2GB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $inputFileName  = $_FILES["file"]["tmp_name"];
            $inputFileType 	= IOFactory::identify($inputFileName);
            $objReader 		= IOFactory::createReader($inputFileType);
            $objPHPExcel 	= $objReader->load($inputFileName);
            try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                $json['msg'] = 'Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage();
            }//end try
            $sheet          = $objPHPExcel->setActiveSheetIndex(0);
            $file_mapel_id  = $sheet->getCell('C1')->getValue();
            if ($mapel_id != $file_mapel_id){
                $json['msg'] = 'Format file tidak sama dengan formulir yang sedang dibuka';
            } else {
                $highestColumn 	= $sheet->getHighestColumn();
                $highestColumn  = $this->conv->toNum($highestColumn);
                $highestRow 	= $sheet->getHighestRow();
                $nomor          = $this->dbase->dataRow('default','soal',array('soal_status'=>1,'mapel_id'=>$mapel_id),'COUNT(soal_id) AS cnt')->cnt;
                $nomor          = $nomor + 1;
                $soal_id        = $this->dbase->dataRow('default','soal',array('mapel_id'=>$mapel_id),'COUNT(soal_id) AS cnt')->cnt;
                $soal_id        = $soal_id + 1;
                $data           = array();
                $dtCount        = 0;
                $allow_soal_type= array('pg','uraian','singkat');
                for($row = 8; $row <= $highestRow; $row++){
                    $soal_content   = $sheet->getCell('B'.$row)->getValue();
                    $soal_type      = $sheet->getCell('C'.$row)->getValue();
                    $soal_score     = $sheet->getCell('D'.$row)->getValue();
                    $soal_jawab     = $sheet->getCell('E'.$row)->getValue();
                    if (strlen($soal_content) > 0 && strlen($soal_type) > 0 && strlen($soal_score) > 0 && in_array($soal_type,$allow_soal_type)){
                        $data[$dtCount] = new stdClass();
                        $data[$dtCount]->soal_nomor     = $nomor;
                        $data[$dtCount]->soal_id        = $soal_id;
                        $data[$dtCount]->soal_type      = $soal_type;
                        $data[$dtCount]->soal_score     = $soal_score;
                        $data[$dtCount]->jawaban        = $soal_jawab;
                        $data[$dtCount]->soal_content   = $soal_content;
                        $dtPG                           = array();
                        $pgindex   = 0; $nomorPG = 1;
                        for($col = 6; $col <= $highestColumn; $col++){
                            $pg_now     = $sheet->getCell($this->conv->toStr($col).$row)->getValue();
                            $pg_score   = $sheet->getCell($this->conv->toStr(($col+1)).$row)->getValue();
                            if (strlen($pg_now) > 0){
                                $dtPG[$pgindex]             = new stdClass();
                                $dtPG[$pgindex]->pg_nomor   = $nomorPG;
                                $dtPG[$pgindex]->pg_content = $pg_now;
                                if (strlen($pg_score) == 0){ $pg_score = 0; }
                                $dtPG[$pgindex]->pg_score   = $pg_score;
                                $dtPG[$pgindex]->pg_is_right= 0;
                                if ($this->conv->toStr($nomorPG) == $soal_jawab){ $dtPG[$pgindex]->pg_is_right = 1; }
                                $pgindex++;
                            }
                            $col++; $nomorPG++;
                        }
                        $data[$dtCount]->pilihan_ganda  = $dtPG;
                        $nomor++; $dtCount++; $soal_id++;
                    }
                }//end for row
                if ($dtCount == 0){
                    $json['msg'] = 'Tidak ada soal pada file ini';
                } else {
                    $json['mapel_id'] = $mapel_id;
                    $json['t'] = 1;
                    $json['data'] = $data;
                }
            }
        }
	    die(json_encode($json));
    }
    function import_bank_soal_proses(){
	    $json['t'] = 0; $json['msg'] = '';
	    $datanya    = $this->input->post('data');
	    $mapel_id   = $this->input->post('mapel_id');
        $nomor      = $datanya['soal_nomor'];
        $soal_id    = 's_'.str_pad($mapel_id,10,"0",STR_PAD_LEFT).'_';
        $soal_id    = $soal_id.str_pad($datanya['soal_id'],10,"0",STR_PAD_LEFT);
        $arrInsert  = array(
            'mapel_id' => $mapel_id, 'soal_nomor' => $nomor, 'soal_content' => $datanya['soal_content'], 'soal_type' => $datanya['soal_type'],
            'soal_score' => $datanya['soal_score'], 'soal_id' => $soal_id
        );

        $this->dbase->dataInsert('default','soal',$arrInsert);
        if ($datanya['pilihan_ganda']){
            if (count($datanya['pilihan_ganda']) > 0){
                foreach ($datanya['pilihan_ganda'] as $valPG){
                    $pg_nomor   = $valPG['pg_nomor'];
                    $pg_id      = substr($soal_id,-21);
                    $pg_id      = 'pg_'.$pg_id.'_';
                    $pg_id      = $pg_id.str_pad($pg_nomor,10,"0",STR_PAD_LEFT);
                    $arrInsertPG= array(
                        'soal_id' => $soal_id, 'pg_nomor' => $pg_nomor, 'pg_content' => $valPG['pg_content'],
                        'pg_score' => $valPG['pg_score'], 'pg_is_right' => $valPG['pg_is_right'], 'pg_id' => $pg_id
                    );
                    $this->dbase->dataInsert('default','soal_pg',$arrInsertPG);
                }
            }
        }
        //die(var_dump($pg_id));
        if (!$soal_id){
            $json['msg'] = 'DB ERROR';
        } else {
            $json['t'] = 1;
        }
	    die(json_encode($json));
    }
    function copy_bank_soal(){
	    $tingkat        = $this->uri->segment(3);
	    $mapel_id       = $this->uri->segment(4);
	    $kk_id          = $this->uri->segment(5);
	    $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id),'kk_id,mapel_id,mapel_name,mapel_tingkat');
	    $dtKK           = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$kk_id),'kk_id,kk_name');
	    if (!$dtMapel || !$mapel_id){
	        die('Invalid parameter MAPEL');
        } elseif ($kk_id && !$dtKK){
	        die('Invalid parameter KOMPETENSI');
        } else {
	        if ($kk_id){
                $data['mapel']  = $this->dbase->dataResult('default','mapel',array('kk_id'=>$kk_id,'mapel_tingkat'=>$tingkat,'mapel_status'=>1),'mapel_id,mapel_name,mapel_tingkat');
            } else {
                $data['mapel']  = $this->dbase->dataResult('default','mapel',array('mapel_tingkat'=>$tingkat,'mapel_status'=>1),'mapel_id,mapel_name,mapel_tingkat');
            }

            $data['dtkk']   = $dtKK;
            $data['kk']     = $this->dbase->dataResult('default','keahlian_kompetensi',array('kk_status'=>1),'kk_id,kk_name');
            $data['data']   = $dtMapel;
            $this->load->view('mapel/copy_bank_soal',$data);
        }
    }
    function load_mapel(){
	    $json['t'] = 0; $json['msg'] = '';
	    $tingkat        = $this->input->post('tingkat');
	    $kk_id          = $this->input->post('kk_id');
	    $dtKK           = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$kk_id),'kk_id');
	    if (!$tingkat){
	        $json['msg'] = 'Invalid parameter TINGKAT';
        } elseif ($kk_id && !$dtKK){
	        $json['msg'] = 'Invalid parameter KOMPETENSI';
        } else {
	        if ($dtKK){
	            $dtMapel = $this->dbase->dataResult('default','mapel',array('mapel_tingkat'=>$tingkat,'mapel_status'=>1,'kk_id'=>$kk_id),'mapel_id,mapel_name');
            } else {
                $dtMapel = $this->dbase->dataResult('default','mapel',array('mapel_tingkat'=>$tingkat,'mapel_status'=>1),'mapel_id,mapel_name');
            }
            if (!$dtMapel){
	            $json['msg']    = 'Tidak ada data';
            } else {
	            $json['t']      = 1;
	            $json['data']   = $dtMapel;
            }
        }
	    die(json_encode($json));
    }
    function copy_bank_soal_submit(){
	    $json['t'] = 0; $json['msg'] = '';
	    $src_tingkat        = $this->input->post('src_tingkat');
        $src_kkid           = $this->input->post('src_kkid');
        $src_mapelid        = $this->input->post('src_mapelid');
        $dst_tingkat        = $this->input->post('dst_tingkat');
        $dst_kkid           = $this->input->post('dst_kkid');
        $dst_mapelid        = $this->input->post('dst_mapelid');
        $srcMapel           = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$src_mapelid),'mapel_id');
        $dstMapel           = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$dst_mapelid),'mapel_id');
        if (!$src_tingkat){
            $json['msg']    = 'Pilih source tingkat';
        } elseif (!$src_mapelid || !$srcMapel){
            $json['msg']    = 'Invalid parameter SOURCE MAPEL';
        } elseif (!$dst_mapelid || !$dstMapel){
            $json['msg']    = 'Invalid parameter DESTINATION MAPEL';
        } else {
            $dtSoal = $this->dbase->dataResult('default','soal',array('mapel_id'=>$src_mapelid,'soal_status'=>1),'soal_id','soal_nomor','ASC');
            //var_dump($src_mapelid);
            if (!$dtSoal){
                $json['msg'] = 'Tidak ada soal';
            } else {
                $i = 0;
                foreach ($dtSoal as $val){
                    $dtSoal[$i]     = $val;
                    $dtSoal[$i]->dst_mapelid    = $dst_mapelid;
                    $i++;
                }
                $json['t'] = 1;
                $json['data'] = $dtSoal;
            }
        }
	    die(json_encode($json));
    }
    function copy_bank_soal_proses(){
	    $json['t'] = 0; $json['msg'] = '';
	    $datanya        = $this->input->post('data');
	    $srcsoal_id     = $datanya['soal_id'];
        $dst_mapelid    = $datanya['dst_mapelid'];
        $dtSrcSoal      = $this->dbase->dataRow('default','soal',array('soal_id'=>$srcsoal_id),'soal_content,soal_type,soal_score');
        if (!$dtSrcSoal){
            $json['msg'] = 'Tidak ada soal';
        } else {
            $nomor      = $this->dbase->dataRow('default','soal',array('mapel_id'=>$dst_mapelid,'soal_status'=>1),'COUNT(soal_id) AS cnt')->cnt;
            $nomor      = $nomor + 1;
            $soal_id    = $this->dbase->dataRow('default','soal',array('mapel_id'=>$dst_mapelid),'COUNT(soal_id) AS cnt')->cnt;
            $soal_id    = $soal_id + 1;
            $soal_id    = 's_'.str_pad($dst_mapelid,10,"0",STR_PAD_LEFT).'_'.str_pad($soal_id,10,"0",STR_PAD_LEFT);
            $arr        = array(
                'soal_id' => $soal_id, 'mapel_id' => $dst_mapelid, 'soal_nomor' => $nomor, 'soal_content' => $dtSrcSoal->soal_content,
                'soal_type' => $dtSrcSoal->soal_type, 'soal_score' => $dtSrcSoal->soal_score
            );
            $this->dbase->dataInsert('default','soal',$arr);
            $dtSrcPG = $this->dbase->dataResult('default','soal_pg',array('soal_id'=>$srcsoal_id,'pg_status'=>1),'pg_content,pg_score,pg_is_right');
            if ($dtSrcPG){
                foreach ($dtSrcPG as $valPG){
                    $nomor  = $this->dbase->dataRow('default','soal_pg',array('soal_id'=>$soal_id,'pg_status'=>1),'COUNT(pg_id) AS cnt')->cnt;
                    $nomor  = $nomor + 1;
                    $nomorL = $this->dbase->dataRow('default','soal_pg',array('soal_id'=>$soal_id),'COUNT(pg_id) AS cnt')->cnt;
                    $nomorL = $nomorL + 1;
                    $pg_id  = substr($soal_id,-21);
                    $pg_id  = 'pg_'.$pg_id.'_';
                    $pg_id  = $pg_id.str_pad($nomorL,10,"0",STR_PAD_LEFT);
                    $arrPG  = array(
                        'pg_id' => $pg_id, 'soal_id' => $soal_id, 'pg_nomor' => $nomor, 'pg_content' => $valPG->pg_content,
                        'pg_score' => $valPG->pg_score, 'pg_is_right' => $valPG->pg_is_right
                    );
                    $this->dbase->dataInsert('default','soal_pg',$arrPG);
                }
            }
            if ($nomor == 0){
                $json['msg'] = 'UNKNOW ERROR';
            } else {
                $json['t'] = 1;
            }
        }
	    die(json_encode($json));
    }
    function add_data(){
        $kk_id      = $this->uri->segment(4);
        $tingkat    = $this->uri->segment(3);
        $dtKK       = $this->dbase->dataRow('default','keahlian_kompetensi',array('kk_id'=>$kk_id),'kk_id');
        if (strlen($kk_id) > 0 && !$dtKK){
            die('Invalid KOMPETENSI');
        } else {
            $data['kk']     = $this->dbase->dataResult('default','keahlian_kompetensi',array('kk_status'=>1),'kk_id,kk_name');
            $data['kk_id']  = $dtKK;
            $data['tingkat']= $tingkat;
            $this->load->view('mapel/add_data',$data);
        }
    }
    function analisis_soal(){
	    $soal_id    = $this->uri->segment(3);
	    $dtSoal     = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id),'mapel_id,soal_tuntas_min');
	    if (!$dtSoal){
	        die('Invalid SOAL');
        } else {
	        $data['quiz']   = $this->dbase->sqlResult('default',"
	            SELECT    q.quiz_name,q.jn_id,jn.jn_name,q.quiz_tapel,q.quiz_id
                FROM      tb_quiz_mapel AS qm
                LEFT JOIN tb_quiz AS q ON qm.quiz_id = q.quiz_id AND q.quiz_status = 1
                LEFT JOIN tb_jenis_nilai AS jn ON q.jn_id = jn.jn_id
                LEFT JOIN tb_mapel AS m ON qm.mapel_id = m.mapel_id AND m.mapel_status = 1
                WHERE     qm.mapel_id = '".$dtSoal->mapel_id."'
                GROUP BY  q.quiz_id
                ORDER BY  m.mapel_group,m.mapel_name ASC
	        ");
            $data['soal_id']    = $soal_id;
            $data['tuntas']     = $dtSoal->soal_tuntas_min;
            $data['mapel_id']   = $dtSoal->mapel_id;
	        $this->load->view('mapel/analisis_soal',$data);
        }
    }
    function cmp($a, $b){
        return strcmp($a->nilai, $b->nilai);
    }
    function analisis_table(){
	    $json['t'] = 0; $json['msg'] = 'TIDAK ADA DATA';
	    $quiz_id        = $this->input->post('quiz_id');
	    $dtQuiz         = $this->dbase->dataRow('default','quiz',array('quiz_id'=>$quiz_id),'quiz_id,quiz_jml_soal');
	    $soal_id        = $this->input->post('soal_id');
	    $dtSoal         = $this->dbase->dataRow('default','soal',array('soal_id'=>$soal_id),'soal_nomor,soal_id,soal_tuntas_min,soal_content');
	    if (!$dtQuiz){
	        $json['msg'] = 'Invalid TES';
        } elseif (!$dtSoal){
	        $json['msg'] = 'Invalid SOAL';
        } else {
	        $pg_id              = NULL;
	        $json['nomor_soal'] = $dtSoal->soal_nomor;
	        $json['isi_soal']   = $dtSoal->soal_content;
	        $dtPG   = $this->dbase->dataRow('default','soal_pg',array('soal_id'=>$soal_id,'pg_is_right'=>1),'pg_id,pg_nomor,pg_content');
	        if ($dtPG){
	            $json['nomor_pg']   = $dtPG->pg_nomor;
	            $json['pg_content'] = $dtPG->pg_content;
	            $pg_id              = $dtPG->pg_id;
            }
	        ini_set('max_execution_time',100000);
	        $jml_soal       = $dtQuiz->quiz_jml_soal;
	        $bobot          = 100 / $jml_soal;
	        $json['soal_tuntas_min'] = $dtSoal->soal_tuntas_min;
            $mapel_id       = $this->input->post('mapel_id');
            $distinct_soal  = $this->dbase->sqlResult('default',"
                SELECT DISTINCT   qs.soal_id,s.soal_nomor,pg.pg_nomor,pg.pg_id
                FROM              tb_quiz_soal AS qs
                LEFT JOIN         tb_soal AS s ON qs.soal_id = s.soal_id
                LEFT JOIN         tb_soal_pg AS pg ON pg.soal_id = s.soal_id AND pg.pg_is_right = 1
                WHERE             qs.mapel_id = '".$mapel_id."'
                ORDER BY          s.soal_nomor ASC
            ");
            if ($distinct_soal){
                $s = 0;
                foreach ($distinct_soal as $val){
                    $distinct_soal[$s] = $val;
                    $distinct_soal[$s]->jml_benar = 0;
                    $distinct_soal[$s]->kesimpulan = '<span class="label label-danger">DIBUANG</span>';
                    $s++;
                }
            }
            $json['data_soal']  = $distinct_soal;
            $jml_jawab_benar    = 0;
            $json['jml_jawab_benar']= $jml_jawab_benar;
            $json['dayapembeda']    = 0;
            $json['ket']            = 'Dibuang';
            $json['t']          = 1;
            /*$dtPes              = $this->dbase->sqlResult('default',"
                SELECT      s.sis_id,s.sis_fullname,SUM(qh.qh_score) AS nilai
                FROM        tb_quiz_hasil AS qh
                LEFT JOIN   tb_siswa AS s ON qh.sis_id = s.sis_id
                WHERE       qh.mapel_id = '".$mapel_id."' AND qh.quiz_id = '".$quiz_id."'
                GROUP BY    s.sis_id
                ORDER BY    s.sis_fullname ASC
            ");*/
            $dtPes      = $this->dbase->sqlResult('default',"
                SELECT    s.sis_id,s.sis_fullname,qn.qn_nilai AS nilai
                FROM      tb_quiz_nilai AS qn
                LEFT JOIN tb_siswa AS s ON qn.sis_id = s.sis_id
                WHERE     qn.mapel_id = '".$mapel_id."' AND qn.quiz_id = '".$quiz_id."'
                ORDER BY  qn.qn_nilai DESC
            ");
            if (!$dtPes){
                $json['msg'] = 'Tidak ada data peserta';
            } else {
                /*$totalPes   = $this->dbase->sqlResult('default',"
                    SELECT      DISTINCT qh.sis_id
                    FROM        tb_quiz_hasil AS qh
                    LEFT JOIN   tb_siswa AS s ON qh.sis_id = s.sis_id
                    WHERE       qh.mapel_id = '".$mapel_id."' AND qh.quiz_id = '".$quiz_id."'
                    GROUP BY    s.sis_id
                    ORDER BY    s.sis_fullname ASC
                ");*/
                $totalPes   = $dtPes;
                if ($totalPes){ $totalPes = count($totalPes); } else { $totalPes = 0; }
                $json['jml_pes'] = $totalPes;
                $jmlPesBenar= count($dtPes);
                $jmlAtas    = floor(( 27 / 100 ) * $jmlPesBenar);
                //var_dump($jmlAtas);
                /*uasort($dtPes, function($a, $b) {
                    return $b->nilai <=> $a->nilai;
                });*/
                //var_dump($dtPes);
                $batas_atas  = array_slice($dtPes,0,$jmlAtas);
                /*foreach ($batas_atas as $array_key => $array_item) {
                    $nilai = round($batas_atas[$array_key]->nilai * $bobot);
                    //var_dump($nilai);
                    if ($nilai < $dtSoal->soal_tuntas_min && $nilai > 0) {
                        unset($batas_atas[$array_key]);
                    }
                }*/
                //$jmlBawah    = count($batas_atas);
                //die(var_dump($batas_atas));
                $jmlBawah    = 0 - $jmlAtas;
                $batas_bawah = array_slice($dtPes,$jmlBawah);
                /*foreach ($batas_bawah as $array_key => $array_item) {
                    $nilai = round($batas_bawah[$array_key]->nilai * $bobot);
                    if ($nilai == 0){
                        unset($batas_bawah[$array_key]);
                    }
                }*/
                //both arrays will be merged including duplicates
                $dtPes = array_merge( $batas_atas, $batas_bawah );
                //duplicate objects will be removed
                $dtPes = array_map("unserialize", array_unique(array_map("serialize", $dtPes)));
                //array is sorted on the bases of id
                //sort( $dtPes );
                //var_dump($batas_atas);
                //var_dump($batas_bawah);
                $json['ba']     = count($batas_atas);
                $json['bb']     = count($batas_bawah);
                $BA             = count($batas_atas);
                $BB             = count($batas_bawah);
                $JA = $JB = 0;
                $i = 0;
                foreach ($dtPes as $valPes){
                    $dtPes[$i]      = $valPes;
                    $dtPes[$i]->batas   = 'atas';
                    if ($i + 1 > $jmlAtas){ $dtPes[$i]->batas = 'bawah'; }
                    $dtPesSoal      = $distinct_soal;
                    $jml_benar      = 0;
                    if ($dtPesSoal){
                        $s = 0;
                        foreach ($dtPesSoal as $valSoal){
                            $dtPesSoal[$s]  = $valSoal;
                            $hasil = $this->dbase->sqlRow('default',"
                                SELECT    pg.pg_nomor,pg.pg_is_right,qh.soal_id
                                FROM      tb_quiz_hasil AS qh
                                LEFT JOIN tb_soal_pg AS pg ON qh.pg_id = pg.pg_id
                                WHERE     qh.soal_id = '".$valSoal->soal_id."' AND qh.sis_id = '".$valPes->sis_id."'
                                          AND qh.quiz_id = '".$quiz_id."'
                            ");
                            $dtPesSoal[$s]  = $hasil;
                            if ($hasil){
                                if ($hasil->pg_is_right == 1){
                                    if ($hasil->soal_id == $soal_id){
                                        $jml_jawab_benar++;
                                        if ($dtPes[$i]->batas == 'atas'){
                                            $JA++;
                                        } else {
                                            $JB++;
                                        }
                                    }
                                    $jml_benar++;
                                    $distinct_soal[$s]->jml_benar = $distinct_soal[$s]->jml_benar + 1;
                                }
                            }
                            $s++;
                        }
                    }
                    $dtPes[$i]->ket         = 'Tidak Lulus';
                    $nilai_akhir            = $jml_benar * $bobot;
                    if ($nilai_akhir >= $dtSoal->soal_tuntas_min){
                        $dtPes[$i]->ket     = 'Lulus';
                    }
                    $dtPes[$i]->nilai_akhir = number_format($nilai_akhir,2,",",".");
                    $dtPes[$i]->jml_benar   = $jml_benar;
                    $dtPes[$i]->hasil       = $dtPesSoal;
                    $i++;
                }
                //hitung daya pembeda
                /*$jml_jawab_benar        = $this->dbase->sqlRow('default',"
                    SELECT    COUNT(qh.qh_id) AS cnt
                    FROM      tb_quiz_hasil AS qh
                    WHERE     qh.quiz_id = '".$quiz_id."' AND qh.soal_id = '".$soal_id."' AND qh.qh_score > 0
                ");
                if ($jml_jawab_benar){$jml_jawab_benar = $jml_jawab_benar->cnt; } else { $jml_jawab_benar = 0; }*/
                //$JA = $JB = 35;
                $PA     = $BA / $JA;
                $PB     = $BB / $JB;
                $json['ja'] = $JA; $json['jb'] = $JB;
                $json['pa'] = number_format($PA,2,",",".");
                $json['pb'] = number_format($PB,2,",",".");
                $dayapembeda            = $PA - $PB;
                //$dayapembeda            = ( 2 * $jml_jawab_benar ) / $totalPes;
                $json['dayapembeda']    = number_format($dayapembeda,2,",",".");
                if ($dayapembeda < 0) {
                    $json['ket'] = '<span class="label label-default">TIDAK BAIK</span>';
                } elseif ($dayapembeda >= 0 && $dayapembeda < 0.20){
                    $json['ket'] = '<span class="label label-danger">JELEK</span>';
                } elseif ($dayapembeda >= 0.20 && $dayapembeda < 0.40) {
                    $json['ket'] = '<span class="label label-primary">CUKUP</span>';
                } elseif ($dayapembeda >= 0.40 && $dayapembeda < 0.70){
                    $json['ket'] = '<span class="label label-info">BAIK</span>';
                } else {
                    $json['ket'] = '<span class="label label-success">BAIK SEKALI</span>';
                }
                //end hitugn daya pembeda
                //hitung tingkat kesukarang
                $sukar                  = $jml_jawab_benar / $totalPes;
                if ($sukar > 0.7){
                    $sukar_txt          = '<span class="label label-success">MUDAH</span>';
                } elseif($sukar >= 0.3 && $sukar < 0.7 ){
                    $sukar_txt          = '<span class="label label-warning">SEDANG</span>';
                } else {
                    $sukar_txt          = '<span class="label label-danger">SULIT</span>';
                }
                $json['sukar_txt']      = $sukar_txt;
                $json['sukar_int']      = number_format($sukar,2,",",".");
                $json['jml_jawab_benar']= $jml_jawab_benar;
                $this->load->library('conv');
                $json['data_soal']      = $distinct_soal;
                $json['t']              = 2;
                $data['data_soal']      = $distinct_soal;
                $data['data']           = $dtPes;
                $json['html']           = $this->load->view('mapel/analisis_table',$data,true);
            }
        }
	    die(json_encode($json));
    }

}
