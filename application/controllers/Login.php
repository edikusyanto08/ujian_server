<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function index(){
	    if($this->session->userdata('login')){
	        redirect(base_url(''));
        }
		$this->load->view('login');
	}
	function submit(){
	    $json['t']  = 0; $json['msg'] = '';
	    $username   = $this->input->post('username');
	    $password   = $this->input->post('password');
	    $dtUser     = $this->dbase->dataRow('default','user',array('user_name'=>$username,'user_status'=>1));
	    if (strlen(trim($username)) == 0){
	        $json['msg'] = 'Isikan username';
        } elseif (!$dtUser){
	        $json['msg'] = 'Username tidak ditemukan';
        } elseif (strlen(trim($password)) == 0){
	        $json['msg'] = 'Isikan password';
        } elseif ($password != $dtUser->user_password){
	        $json['msg'] = 'Password salah';
        } else {
            $arr = array('login'=>true,'user_id'=>$dtUser->user_id,'user_fullname'=>$dtUser->user_fullname,'user_level'=>$dtUser->user_level);
            $this->session->set_userdata($arr);
            $json['t'] = 1;
        }
	    die(json_encode($json));
    }
}
