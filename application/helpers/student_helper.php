<?php

if(!function_exists('user_full_name')){
	function user_full_name()
	{
		$CI =& get_instance();
		return $CI->session->userdata('firstname').' '.$CI->session->userdata('middlename').' '.$CI->session->userdata('lastname');
	}
}

if(!function_exists('user_id_number')){
	function user_id_number()
	{
		$CI =& get_instance();
		return $CI->session->userdata('username');
	}
}

if(!function_exists('pk')){
	function pk()
	{
		$CI =& get_instance();
		return $CI->session->userdata('id');
	}
}


if(!function_exists('user_type')){
	function user_type($type = FALSE)
	{
		$CI =& get_instance();
		if($type){
			return $type === $CI->session->userdata('type');
		}
		return $CI->session->userdata('type');
	}
}
