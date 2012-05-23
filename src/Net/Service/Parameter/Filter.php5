<?php
/**
 *	Filter for Service Parameters.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		Net.Service.Parameter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		$Id$
 */
/**
 *	Filter for Service Parameters.
 *	@category		cmClasses
 *	@package		Net.Service.Parameter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		$Id$
 *	@todo			Code Doc
 *	@deprecated		moved to cmModules::ENS
 *	@todo			to be removed in 0.7.3
 */
class Net_Service_Parameter_Filter
{
	/**
	 *	...
	 *	@access		public
	 *	@param		array		$rules			Parameter Rules
	 *	@param		string		$value			Parameter Value from Request
	 *	@return		void
	 */
	public function applyFilter( $filterKey, $parameterValue )
	{
		if( !method_exists( __CLASS__, $filterKey ) )
			throw new BadMethodCallException( 'Filter "'.$filterKey.'" is not existing' );
		try
		{
			return self::$filterKey( $parameterValue );
		}
		catch( Exception $e )
		{
			throw new InvalidArgumentException( $filterKey.': '.$e->getMessage() );
		}
	}
	
	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function stripHtml( $value )
	{
		return strip_tags( $value );
	}
	
	protected static function decodeBase64( $value )
	{
		$code	= @base64_decode( $value, TRUE );
		if( !$code )
			throw new InvalidArgumentException( 'Could not decode Base64' );
		return $code;
	}
	
	protected static function encodeBase64( $value )
	{
		return base64_encode( $value );
	}
	
	protected static function encodeMD5( $value )
	{
		return md5( $value );
	}
	
	protected static function test( $value )
	{
		return $value." [tested]";
	}
		
	protected static function trimString( $value )
	{
		if( !is_string( $value ) )
			throw new InvalidArgumentException( 'Must be string' );
		return trim( $value );
	}
}
?>