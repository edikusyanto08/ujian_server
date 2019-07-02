<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller {
	public function index(){
	    if(!$this->session->userdata('login')){
	        redirect(base_url('login'));
        } elseif ($this->session->userdata('user_level') == 1){
	        redirect(base_url('quiz/landing'));
        }
        $data['body']   = 'media/home';
	    $data['menu']   = 'media';
	    if ($this->input->is_ajax_request()){
	        $this->load->view($data['body'],$data);
        } else {
            $this->load->view('home',$data);
        }
	}
	function data_home(){
        $json['t'] = 0; $json['msg'] = '';
        $keyword        = $this->input->post('keyword');
        $dtMedia        = $this->dbase->sqlResult('default',"
            SELECT    m.media_id,m.media_url,m.media_type,m.media_name
            FROM      tb_media AS m
            WHERE     m.media_status = 1 AND m.media_name LIKE '%".$keyword."%'
            ORDER BY  m.media_name ASC
        ");
        if (!$dtMedia){
            $json['msg'] = 'Tidak ada data';
        } else {
            $data['data']   = $dtMedia;
            $json['t']      = 1;
            $json['html']   = $this->load->view('media/data_home',$data,TRUE);
        }
        die(json_encode($json));
    }
}
