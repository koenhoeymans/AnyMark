<?php

error_reporting(-1);

require_once __DIR__
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'AnyMark'
	. DIRECTORY_SEPARATOR . 'Autoload.php';

function AnyMark_EndToEndTests_Autoload($className)
{
	$classNameFile = __DIR__
		. DIRECTORY_SEPARATOR . '..'
		. DIRECTORY_SEPARATOR . '..'
		. DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className)
		. '.php';

	if (file_exists($classNameFile))
	{
		require_once $classNameFile;
	}
}

spl_autoload_register('AnyMark_EndToEndTests_Autoload');