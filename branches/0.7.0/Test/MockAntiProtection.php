<?php
require_once( "MockExceptions.php" );
class Test_MockAntiProtection
{
	public static function createMockClass( $className )
	{
		if( class_exists( 'Test_'.$className.'_MockAntiProtection' ) )
			return;
		if( !class_exists( $className ) )
			throw new InvalidArgumentException( 'Class "'.$className.'" is not existing' );
		$codeFile	= dirname( __FILE__ ).'/MockAntiProtection.tmpl';
		$codeClass	= UI_Template::render( $codeFile, array( 'className' => $className ) );
		eval( $codeClass );
	}

	public static function getInstance( $className )
	{
		self::createMockClass( $className );
		$mockClass	= "Test_".$className."_MockAntiProtection";
		$arguments	= array_slice( func_get_args(), 1 );
		$mockObject	= Alg_Object_Factory::createObject( $mockClass, $arguments );
		return $mockObject;
	}
}
?>