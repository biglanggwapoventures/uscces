<?php

if(!function_exists('preset'))
{
	function preset($arr, $key, $default = FALSE)
	{
		return isset($arr[$key]) ? $arr[$key] : $default;
	}
}


if(!function_exists('course_dropdown'))
{
	function course_dropdown($name, $default, $attrs = '')
	{
		$options = ['' => '', 'ict' => 'B.S. Information and Communications Technology', 'it' => 'B.S. Information Technology', 'cs' => 'B.S. Computer Science'];
		return form_dropdown($name, $options, $default, $attrs);
	}
}


if(!function_exists('course'))
{
	function course($code, $reduced = FALSE)
	{
		switch ($code) {
			case 'ict':
				return $reduced ? 'BS ICT' : 'B.S. Information and Communications Technology';
			case 'it':
				return $reduced ? 'BS IT' : 'B.S. Information Technology';
			case 'cs':
				return $reduced ? 'BS CS' : 'B.S. Computer Science';
			default:
				return '';
		}
	}
}