<?php
declare( strict_types = 1 );

class Test_Case extends \PHPUnit\Framework\TestCase{
	static public $__config;
	static public $__pathLib;
}

Test_Case::$__pathLib	= dirname( __DIR__  ).'/';
Test_Case::$__config = parse_ini_file( Test_Case::$__pathLib.'/Common.ini', TRUE );
class_exists( 'UI_DevOutput' );

#error_reporting( error_reporting() || ~E_USER_DEPRECATED );
