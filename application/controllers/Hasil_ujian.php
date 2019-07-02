<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil_ujian extends CI_Controller {
	public function index(){
        $data['body']   = 'hasil_ujian/home';
	    $data['menu']   = 'dashboard';
	    $data['tapel']  = $this->dbase->dataRow('default','siswa',array('sis_status'=>1),'MIN(sis_tapel) AS tapel')->tapel;
	    $data['mapel']  = $this->dbase->dataResult('default','mapel',array('mapel_status'=>1));
	    $data['jn']     = $this->dbase->dataResult('default','jenis_nilai',array('jn_status'=>1));
	    if ($this->input->is_ajax_request()){
	        $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
	}
	function data_home(){
	    $json['t'] = 0; $json['msg'] = 'NO DATA';
	    $tapel      = $this->input->post('tapel');
	    $jn_id      = $this->input->post('jn_id');
	    $mapel_id   = $this->input->post('mapel_id');
	    $keyword    = $this->input->post('keyword');
	    $ordering   = $this->input->post('ordering');
	    $order_by   = $this->input->post('order_by');
	    $dtJN       = $this->dbase->dataRow('default','jenis_nilai',array('jn_id'=>$jn_id));
	    if (!$dtJN){
	        $json['msg'] = 'Pilih jenis penilaian';
        } else {
	        $dtMapel = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id));
	        if (!$dtMapel){
	            $json['msg'] = 'Pilih mata pelajaran';
            } else {
	            $dtPes = $this->dbase->sqlResult('default',"
	                SELECT    qn.qn_nilai,s.sis_nopes,s.sis_fullname,qn.qn_rank,s.sis_kelas
                    FROM      tb_quiz_nilai AS qn
                    LEFT JOIN tb_siswa AS s ON qn.sis_id = s.sis_id
                    WHERE     qn.mapel_id = '".$mapel_id."' AND qn.jn_id = '".$jn_id."' AND qn.qn_tapel = '".$tapel."' AND qn.qn_status = 1
                              AND (
                              s.sis_nopes LIKE '%".$keyword."%' OR
                              s.sis_fullname LIKE '%".$keyword."%' OR
                              s.sis_kelas LIKE '%".$keyword."%' 
                              )
                    ORDER BY  ".$order_by." ".$ordering." 
	            ");
	            if (!$dtPes){
	                $json['msg'] = 'Data tidak ditemukan atau belum tersedia';
                } else {
	                $data['data']   = $dtPes;
	                $json['t']      = 1;
	                $json['html']   = $this->load->view('hasil_ujian/data_home',$data,true);
                }
            }
        }
	    die(json_encode($json));
    }
}
