<?php
/**
 *	Validator for Service Parameters.
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
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.01.2008
 *	@version		0.6
 */
/**
 *	Validator for Service Parameters.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			08.01.2008
 *	@version		0.6
 */
class Net_Service_ParameterValidator
{
	/**
	 *	Validates a Parameter Value from Request by calling Validator Methods for Parameter Rules and throwing Exceptions.
	 *	@access		public
	 *	@param		array		$rules			Parameter Rules
	 *	@param		string		$value			Parameter Value from Request
	 *	@return		void
	 */
	public static function validateParameterValue( $rules, $value )
	{
		try
		{
			foreach( $rules as $ruleName => $ruleValue )
			{
				if( $ruleValue )
					self::callMethod( "check".ucFirst( $ruleName ), $value, $ruleValue );
			}
		}
		catch( InvalidArgumentException $e )
		{
			throw new InvalidArgumentException( $ruleName );
		}
	}
	
	/**
	 *	Calls Validator Method and throws Exception if Validation failed.
	 *	@access		protected
	 *	@param		string		$method			Validation Method to call
	 *	@param		string		$value			Value to validate
	 *	@param		string		$measure		Measure to validate against
	 *	@return		bool
	 */
	protected static function callMethod( $method, $value, $measure = NULL )
	{
		if( !method_exists( __CLASS__, $method ) )
			throw new BadMethodCallException( "Service Parameter Validator Method '".$method."' is not existing." );
		if( !self::$method( $value, $measure ) )
			throw new InvalidArgumentException( $method );
		return true;
	}
	
	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkMandatory( $value )
	{
		if( is_string( $value ) && strlen( $value ) )
			return TRUE;
		else if( is_array( $value ) && count( $value ) )
			return TRUE;
		return FALSE;
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkMinlength( $value, $measure )
	{
		if( strlen( $value ) >= $measure )
			return TRUE;
		return FALSE;
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkMaxlength( $value, $measure )
	{
		if( strlen( $value ) <= $measure )
			return TRUE;
		return FALSE;
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkPreg( $value, $measure )
	{
		return preg_match( $measure, $value );
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$value			Value to validate
	 *	@return		bool
	 */
	protected static function checkType( $value, $measure )
	{
		$type	= gettype( $value );
		if( $type == $measure )
			return TRUE;
		switch( $measure )
		{
			case 'boolean':	return $value == NULL;
		}
		return FALSE;
	}
}
?>