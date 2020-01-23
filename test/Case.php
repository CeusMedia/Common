<?php
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

class Test_Case extends TestCase{
	protected static $_config;
	protected static $_pathLib;

	public function __construct(){
		parent::__construct();
		self::$_pathLib	= realpath( dirname( __DIR__ ) ).'/';
		self::$_config	= parse_ini_file( self::$_pathLib.'Common.ini', TRUE );
	}
}

return;

//require_once 'PHPUnit/Framework/TestCase.php';
if( !class_exists( 'PHPUnit_Framework_TestCase' ) ){
	class PHPUnit_Framework_TestCase extends PHPUnit\Framework\TestCase{}
}
//	require_once 'PHPUnit/Framework/TestCase.php';
class Test_Case extends PHPUnit_Framework_TestCase{
	static public $config;
	static public $pathLib;
}
//Test_Case::$pathLib	= dirname( __DIR__  ).'/';
error_reporting( error_reporting() || ~E_USER_DEPRECATED );
