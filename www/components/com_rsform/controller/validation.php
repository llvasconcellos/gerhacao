<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/
	require_once('functions.php');
	function none($value)
	{
		return true;
	}
	function email($email)
	{
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
		{
			return false;
		}
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++)
		{
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
			{
			return false;
			}
		}
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
		{
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2)
			{
			return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i]))
				{
				return false;
				}
			}
		}
		return true;
	}
	function numeric($param)
	{
		return is_numeric($param);
	}
	function alphanumeric($param)
	{
		if(eregi('[^a-zA-Z0-9 ]', $param )){
			return false;
		}else{
			return true;
		}
	}
	function alpha($param)
	{
		if(eregi('[^a-zA-Z ]', $param )){
			return false;
		}else{
			return true;
		}
	}
?>