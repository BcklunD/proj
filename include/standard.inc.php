<?php

function isEmpty($str)
{
	return ($str == null || strlen(trim($str)) == 0) || ($str == "null");
}

function notEmpty($str)
{
	return !isEmpty($str);
}

function getParam($name, $default = null) {
	if (array_key_exists($name, $_REQUEST)) {
		$param = $_REQUEST[$name];
		if ($default !== null && isEmpty($param)) {
			return $default;
		}
		$param = addslashes($param);
		return $param;
	} else {
		return $default;
	}
}

function isProd() {
	return $_SERVER['SERVER_NAME'] != 'localhost';
}

function utf8_enc($string)
{
	return mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
}

function utf8_dec($string)
{
	return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');	
}