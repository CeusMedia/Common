<?php
/**
 *	Extension of throw command for lazy load of Exception Class, throws new Exception of given Type.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@param			string		$type		Exception Type (eg. IO for Exception_IO)
 *	@param			string		$message	Exception Message
 *	@return			void
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.06.2008
 *	@version		0.1
 */
/**
 *	Extension of throw command for lazy load of Exception Class, throws new Exception of given Type.
 *	@param			string		$type		Exception Type (eg. IO for Exception_IO)
 *	@param			string		$message	Exception Message
 *	@return			void
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
	if( count( $arguments ) == 4 )
		$exception	= new $className( $arguments[0], $arguments[1], $arguments[2], $arguments[3] );
	else if( count( $arguments ) == 3 )
		$exception	= new $className( $arguments[0], $arguments[1], $arguments[2] );
	else if( count( $arguments ) == 2 )
		$exception	= new $className( $arguments[0], $arguments[1] );
	else if( count( $arguments ) == 1 )
		$exception	= new $className( $arguments[0] );
	
	
/*	$list	= array();
	for( $i=0; $i<count( $arguments ); $i++ )									//  iterate Arguments
		$list[]	= ' $arguments['.$i.']';										//  add Parameter
	$list	= implode( ", ", $list );											//  build Parameter List
	$code	= 'return new '.$className.'( '.$list.' );';						//  build Exception Instance Call Code
	$exception	= eval( $code );												//  call new Exception Instance
*/	throw $exception;															//  throw this new Exception
}
?>