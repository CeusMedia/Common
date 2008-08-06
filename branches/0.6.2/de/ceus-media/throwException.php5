<?php
/**
 *	Extension of throw command for lazy load of Exception Class, throws new Exception of given Type.
 *	@param			string		$type		Exception Type (eg. IO for Exception_IO)
 *	@param			string		$message	Exception Message
 *	@return			void
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.06.2008
 *	@version		0.1
 */
/**
 *	Extension of throw command for lazy load of Exception Class, throws new Exception of given Type.
 *	@param			string		$type		Exception Type (eg. IO for Exception_IO)
 *	@param			string		$message	Exception Message
 *	@return			void
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.06.2008
 *	@version		0.1
 */
function throwException()
{
	$arguments	= func_get_args();												//  get Exception Arguments
	if( count( $arguments ) < 1 )												//  no Exception Type given
		throw new Exception( 'No Exception Type given.' );						//  throw simple Exception
	if( count( $arguments ) < 2 )												//  no Exception Message given
		throw new Exception( 'No Exception Message given.' );					//  throw simple Exception
	$type		= array_shift( $arguments );									//  get Exception Type
	$nativeClassName	= $type."Exception";									//  build native Exception Class Name
	$extendedClassName	= "Exception_".$type;									//  build extended Exception Class Name
	if( class_exists( $nativeClassName ) )										//  check native Exception Class
		$className	= $nativeClassName;											//  set native Exception Class for final Class Name
	else
	{
		$className	= $extendedClassName;										//  set extended Exception Class for final Class Name
		if( !class_exists( $extendedClassName ) )								//  check extended Exception Class
		{
			$classFile	= "de.ceus-media.exception.".$type;						//  build Class File Name of extended Exception Class
			import( $classFile );												//  import extended Exception Class
		}
	}
	$list	= array();
	for( $i=0; $i<count( $arguments ); $i++ )									//  iterate Arguments
		$list[]	= ' $arguments['.$i.']';										//  add Parameter
	$list	= implode( ", ", $list );											//  build Parameter List
	$code	= 'return new '.$className.'( '.$list.' );';						//  build Exception Instance Call Code
	$exception	= eval( $code );												//  call new Exception Instance
	throw $exception;															//  throw this new Exception
}
?>