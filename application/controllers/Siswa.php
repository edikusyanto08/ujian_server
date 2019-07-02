<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {
	public function index(){
	    if(!$this->session->userdata('login')){
	        redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
	        redirect(base_url('quiz/landing'));
        }
        $data['body']   = 'siswa/home';
	    $data['menu']   = 'siswa';
	    $tapel          = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MIN(sis_tapel) AS tapel')->tapel;
	    if ($tapel){ $data['tapel'] = $tapel; } else { $data['tapel'] = date('Y'); }
	    $data['kk']     = $this->dbase->dataResult('default','keahlian_kompetensi',array('kk_status'=>1),'kk_id,kk_name,kk_kode');
	    if ($this->input->is_ajax_request()){
	        $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
	}
	function data_home(){
	    $json['t'] = 0; $json['msg'] = '';
	    $tapel      = $this->input->post('tapel');
	    $kk_id      = $this->input->post('kk_id');
	    $keyword    = $this->input->post('keyword');
	    $sql_kk     = "";
	    if ($kk_id){ $sql_kk = " AND kk.kk_id = '".$kk_id."' "; }
	    $dtSiswa    = $this->dbase->sqlResult('default',"
	        SELECT    s.sis_id,s.sis_nis,s.sis_nopes,s.sis_fullname,s.sis_sex,kk.kk_name,s.sis_tingkat,s.sis_kelas,s.sis_password,s.sis_username
            FROM      tb_siswa AS s
            LEFT JOIN tb_keahlian_kompetensi AS kk ON s.kk_id = kk.kk_id
            WHERE     (
                        kk.kk_name LIKE '%".$keyword."%' OR
                        s.sis_nis LIKE '%".$keyword."%' OR
                        s.sis_username LIKE '%".$keyword."%' OR
                        s.sis_fullname LIKE '%".$keyword."%' OR
                        s.sis_kelas LIKE '%".$keyword."%' OR
                        s.sis_tingkat LIKE '%".$keyword."%'
                      )
                      AND s.sis_status = 1 ".$sql_kk." AND s.sis_tapel = '".$tapel."'
            ORDER BY  kk.kk_name,s.sis_kelas,s.sis_fullname ASC     
	    ");

	    if (!$dtSiswa){
	        $json['msg'] = 'Tidak ada data';
        } else {
	        $json['t']      = 1;
	        $data['data']   = $dtSiswa;
	        $json['html']   = $this->load->view('siswa/data_home',$data,true);
        }
	    die(json_encode($json));
    }
    function import_from_server(){
        $tapel          = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MIN(sis_tapel) AS tapel')->tapel;
        if ($tapel){ $data['tapel'] = $tapel; } else { $data['tapel'] = date('Y'); }
        $data['kk']     = $this->dbase->dataResult('default','keahlian_kompetensi',array('kk_status'=>1),'kk_id,kk_name,kk_kode');
        $data['conn']   = $this->dbase->dataRow('server','siswa',array(),'sis_id');
	    $this->load->view('siswa/import_from_server',$data);
    }
    function import_from_server_submit(){
        $json['t'] = 0; $json['msg'] = '';
        $tapel      = $this->input->post('tapel');
        $kk_id      = $this->input->post('kk_id');
        $tingkat    = $this->input->post('tingkat');
        $sql_kk = $sql_tingkat = "";
        if ($kk_id){ $sql_kk = " AND k.kmp_id = '".$kk_id."' "; }
        if ($tingkat){ $sql_tingkat = " AND k.kel_tingkat = '".$tingkat."' "; }
        $dtSiswa = $this->dbase->sqlResult('server',"
            SELECT    k.kmp_id,k.kel_tapel,k.kel_name,k.kel_tingkat,s.sis_nis,s.sis_fullname,s.sis_sex,s.sis_bdate,
                      s.sis_bplace,s.sis_agama,kk.kmp_kode,s.sis_id,s.sis_nopes_smk
            FROM      tb_kelas AS k
            LEFT JOIN tb_kelas_member AS km ON km.kel_id = k.kel_id
            LEFT JOIN tb_siswa AS s ON km.sis_id = s.sis_id
            LEFT JOIN tb_keahlian_kompetensi AS kk ON kk.kmp_id = k.kmp_id
            WHERE     k.kel_tapel = '".$tapel."'  AND k.kel_semester = 1 ".$sql_tingkat.$sql_kk."
            ORDER BY  k.kmp_id,k.kel_name,s.sis_fullname ASC
        ");
        if ($dtSiswa == 404) {
            $json['msg'] = 'Anda tidak terhubung ke server ERAPOR';
        } elseif (!$dtSiswa){
            $json['msg'] = 'Tidak ada data siswa';
        } else {
            $json['t'] = 1;
            $json['data']   = $dtSiswa;
        }
        die(json_encode($json));
    }
    function import_from_server_proses(){
        $json['t'] = 0; $json['msg'] = '';
        $datanya    = $this->input->post('data');
        if (!$datanya){
            $json['msg'] = 'Tidak ada data';
        } else {
            $kk_id      = $datanya['kmp_id'];
            $sis_tapel      = $datanya['kel_tapel'];
            $sis_kelas      = $datanya['kel_name'];
            $sis_tingkat    = $datanya['kel_tingkat'];
            $sis_nis        = $datanya['sis_nis'];
            $sis_fullname   = $datanya['sis_fullname'];
            $sis_sex        = $datanya['sis_sex'];
            $sis_bdate      = $datanya['sis_bdate'];
            $sis_bplace     = $datanya['sis_bplace'];
            $sis_agama      = $datanya['sis_agama'];
            $kk_kode        = $datanya['kmp_kode'];
            $erapor_id      = $datanya['sis_id'];
            $nopes_smk      = $datanya['sis_nopes_smk'];
            $chkSiswa       = $this->dbase->dataRow('default','siswa',array('sis_tapel'=>$sis_tapel,'kk_id'=>$kk_id,'sis_nis'=>$sis_nis,'sis_status'=>1),'sis_id');
            if ($chkSiswa){
                $json['msg']= 'Sudah ada nama siswa';
            } else {
                $this->load->helper('string');
                $nomor      = $this->dbase->dataRow('default','siswa',array('sis_tapel'=>$sis_tapel,'sis_status'=>1),'COUNT(sis_id) AS cnt')->cnt;
                $nomor      = $nomor + 1;
                $sis_id     = date('Y').$kk_kode.'_'.str_pad($nomor,6,"0",STR_PAD_LEFT);
                $user_name  = str_pad($nomor,4,"0",STR_PAD_LEFT);
                $user_name  = 'K'.date('y').$kk_kode.str_pad($sis_tingkat,2, '0',STR_PAD_LEFT).$user_name;
                $password   = random_string('numeric',6).'*';
                $arr        = array(
                    'kk_id' => $kk_id, 'sis_nopes' => $nopes_smk, 'sis_tapel' => $sis_tapel, 'sis_kelas' => $sis_kelas, 'sis_tingkat' => $sis_tingkat,
                    'sis_nis' => $sis_nis, 'sis_username' => $user_name, 'sis_password' => $password, 'sis_fullname' => strtoupper($sis_fullname),
                    'sis_sex' => $sis_sex, 'sis_bdate' => $sis_bdate, 'sis_bplace' => $sis_bplace, 'sis_agama' => $sis_agama, 'sis_id' => $sis_id,
                    'erapor_id' => $erapor_id
                );
                $this->dbase->dataInsert('default','siswa',$arr);
                if (!$sis_id){
                    $json['msg'] = 'DB Error';
                } else {
                    $json['t']   = 1;
                    $json['msg'] = 'Sukses';
                }
            }
        }
        die(json_encode($json));
    }
    function bulk_delete(){
	    $json['t'] = 0; $json['msg'] = '';
	    $sis_id     = $this->input->post('sis_id');
	    if (!$sis_id){
	        $json['msg'] = 'Pilih data lebih dulu';
        } elseif (count($sis_id) == 0) {
            $json['msg'] = 'Pilih data lebih dulu';
        } else {
	        foreach ($sis_id as $val){
	            ini_set('max_execution_time',1000);
	            $this->dbase->dataUpdate('default','siswa',array('sis_id'=>$val),array('sis_status'=>0));
            }
            $json['t'] = 1;
	        $json['data']   = $sis_id;
	        $json['msg'] = count($sis_id).' data siswa berhasil dihapus';
        }
	    die(json_encode($json));
    }

}
