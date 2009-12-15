<?php
require_once( "trunk/useClasses.php5" );
import( 'de.ceus-media.ui.DevOutput' );
require_once( "MockAntiProtection.php" );
class A
{
	protected $value		= "value1";
	protected static $valueStatic	= 2;

}
//  --  DYNAMIC ACCESS  --  //
$mock	= MockAntiProtection::getInstance( 'A' );
$value	= $mock->getProtectedVar( 'value' );
remark( "getProtectedVar( 'value' ): ".$value );
try
{
	$value	= $mock->getProtectedVar( 'valueStatic' );
	throw new Exception( 'Invalid acces to static variable' );
}
catch( MockBadVarCallException $e )
{
	remark( "Access denied for static variable" );
}

$mock->setProtectedVar( 'value', 'newValue' );
$value	= $mock->getProtectedVar( 'value' );
remark( "getProtectedVar( 'value' ): ".$value );
try
{
	$value	= $mock->setProtectedVar( 'valueStatic', 'newValue' );
	throw new Exception( 'Invalid acces to static variable' );
}
catch( MockBadVarCallException $e )
{
	remark( "Access denied for static variable" );
}




//  --  STATIC ACCESS  --  //
MockAntiProtection::createMockClass( 'A' );
$value	= Test_A_MockAntiProtection::getProtectedStaticVar( 'valueStatic' );
remark( "getProtectedStaticVar( 'valueStatic' ): ".$value );
try
{
	$value	= A_MockAntiProtection::getProtectedStaticVar( 'value' );
	throw new Exception( 'Invalid acces to non-static variable' );
}
catch( MockBadStaticVarCallException $e )
{
	remark( "Access denied for non-static variable" );
}


A_MockAntiProtection::setProtectedStaticVar( 'valueStatic', 'newValue' );
$value	= A_MockAntiProtection::getProtectedStaticVar( 'valueStatic' );
remark( "getProtectedVar( 'value' ): ".$value );
try
{
	$value	= $mock->getProtectedStaticVar( 'value' );
	throw new Exception( 'Invalid acces to static variable' );
}
catch( MockBadStaticVarCallException $e )
{
	remark( "Access denied for static variable" );
}
?>