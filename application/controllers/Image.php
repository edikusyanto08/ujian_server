<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller {
	public function upload_img(){
	    $json['t'] = 0; $json['msg'] = '';
	    $mapel_id       = $this->input->post('mapel_id');
	    $dtMapel        = $this->dbase->dataRow('default','mapel',array('mapel_id'=>$mapel_id),'mapel_id');
	    if (!$dtMapel || !$mapel_id){
	        $json['msg'] = 'Invalid parameter MAPEL';
        } else {
            $source         = $_FILES['file']['tmp_name'];
            $file_name      = $_FILES['file']['name'];
            $src_name       = $file_name;
            $ext            = explode(".",$file_name);
            $ext            = end($ext);
            $ext            = strtolower($ext);
            $allowed        = array('jpg','png','bmp','gif');
            if (!$file_name){
                $json['msg'] = 'Tidak ada file';
            } elseif (!in_array($ext,$allowed)){
                $json['msg'] = 'File yang diperbolehkan adalah jpg, png, bmp, dan gif';
            } else {
                $this->load->helper('string');
                $file_name  = random_string('alnum',10).date('YmdHis');
                $file_name  = md5($file_name).'.'.$ext;
                $dest_dir   = FCPATH . 'assets/upload/';
                $destination= $dest_dir.$file_name;
                if (!file_exists($dest_dir)){ @mkdir($dest_dir,0777,true); }
                @move_uploaded_file($source,$destination);
                $json['t']          = 1;
                $json['url']        = '../../assets/upload/'.$file_name;
                $json['file_name']  = $file_name;
                $this->dbase->dataInsert('default','media',array('mapel_id'=>$mapel_id,'media_name'=>$src_name,'media_url'=>$file_name,'media_type'=>'image'));
            }
        }
	    die(json_encode($json));
	}
}
