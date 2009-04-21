<?php
require_once( "MockExceptions.php" );
class Tests_MockAntiProtection
{
	public static function createMockClass( $className )
	{
		if( class_exists( 'Tests_'.$className.'_MockAntiProtection' ) )
			return;
		$classCode	= '
			class Tests_'.$className.'_MockAntiProtection extends '.$className.'
			{
				public function executeProtectedMethod( $method, $content, $comment = NULL )
				{
					if( !method_exists( $this, $method ) )
						throw new MockBadMethodCallException( \'Method "\'.$method.\'" is not callable.\' );
					return $this->$method( $content, $comment );
				}
				
				public static function executeProtectedStaticMethod( $method, $content, $comment = NULL )
				{
					if( !is_callable( self::$method ) )
						throw new MockBadStaticMethodCallException( \'Static method "\'.$method.\'" is not callable.\' );
					return self::$method( $content, $comment );
				}
				
				public function getProtectedVar( $varName )
				{
					if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
						throw new MockBadVarCallException( \'Variable  "\'.$varName.\'" is not declared.\' );
					return $this->$varName;
				}

				public static function getProtectedStaticVar( $varName )
				{
					if( !isset( self::$$varName ) )
						throw new MockBadStaticVarCallException( \'Static variable "\'.$varName.\'" is not declared.\' );
					return self::$$varName;
				}

				public function setProtectedVar( $varName, $varValue )
				{
					if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
						throw new MockBadVarCallException( \'Variable  "\'.$varName.\'" is not declared.\' );
					$this->$varName	= $varValue;
				}

				public static function setProtectedStaticVar( $varName, $varValue )
				{
					if( !isset( self::$$varName ) )
						throw new MockBadStaticVarCallException( \'Static variable "\'.$varName.\'" is not declared.\' );
					self::$$varName	= $varValue;
				}
				
			}';
		eval( $classCode );
	}

	public static function getInstance( $className )
	{
		self::createMockClass( $className );
		$mockClass	= "Tests_".$className."_MockAntiProtection";
		return new $mockClass;
	}
}
?>