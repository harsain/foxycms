<?php

Class Uploads {
	
	public $upload_dir = 'uploads';
	
	function createthumb($name,$filename,$new_w,$new_h){

		
		$extention = pathinfo($name, PATHINFO_EXTENSION);
		if (preg_match('/jpg|jpeg/',$extention)){
			$src_img=imagecreatefromjpeg($name);
		}
		if (preg_match('/png/',$extention)){
			$src_img=imagecreatefrompng($name);
		}
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
			if (preg_match("/png/",$extention))
		{
			imagepng($dst_img,$filename); 
		} else {
			imagejpeg($dst_img,$filename); 
		}
		imagedestroy($dst_img); 
		imagedestroy($src_img); 
	}
	
	function upload($tmp_name,$name,$type, $dir = 'root', $control = false) {
		global $time,$user,$db;
		
		//settings
		$exts['image'] = array('jpg', 'gif', 'png', 'bmp');
		$exts['sound'] = array('mp3', 'amr', 'oga', 'wav');
		$exts['video'] = array('mp4', '3gp', 'flv', 'ogv', 'avi');
		
		//upload operation
		$extension = substr($name, strrpos($name, '.')+1);
        if(in_array($extension, $exts[$type])) $prefix = $type.'_';
        if ($prefix != '') {
        	if($control !== false) {
        		$new_name = '../';
        		$thumb_name = '../';
        	}
        	$rand = rand(11,99);
        	$final_name = $prefix.time().$rand.'.'.$extension;
            $new_name .= $this->upload_dir.'/'.$final_name;
            $thumb_name .= $this->upload_dir.'/thumb_'.$final_name;
            if (move_uploaded_file($tmp_name, $new_name)) {
            	$this->createthumb($new_name,$thumb_name,200,200);
            	$db->insert('uploads',array('time','file_name','final_name','user_id','type','extention','dir'),
            	array($time,$name,$final_name,$user['id'],$type,$extension,$dir));
				return $new_name;
			}
            else return false;
        } else return false;
	}
	
	function get_thumb($file_id,$theme_url = false) {
		global $db;
		$image_data = $db->fetch('uploads','id',$file_id);
		$r = $image_data[0];
		
		if($theme_url == 'control') {
			$images_prefix = 'images/';
			$uploads_prefix = '../uploads/';
		}
		
		switch($r['type']) {
			case 'image':
				return $uploads_prefix.'thumb_'.$r['final_name'];
				break;
			case 'sound':
				return $images_prefix.'sound_file.png';
				break;
			case 'video':
				return $images_prefix.'video_file.png';
				break;
		}
		
	}
	
	function delete($upload_id,$theme_url = false) {
		global $db;
		$file = $db('uploads','id',$upload_id);
		$file_link = $theme_url.'uploads/'.$file['final_name'];
		$thumb_link = $theme_url.'uploads/thumb_'.$file['final_name'];
		
		@unlink($file_link); @unlink($thumb_link);
		
		$db->delete('uploads','id',$upload_id);
		
	}
	
	
	
}

?>
