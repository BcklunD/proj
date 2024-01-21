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

class Dummy
{
	function __get($name)
	{
		return null;
	}
}