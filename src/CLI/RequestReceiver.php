<?php
/**
 *	Handler for Console Requests.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Handler for Console Requests.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CLI_RequestReceiver extends ADT_List_Dictionary
{
	public static $delimiterAssign	= "=";

	protected $pairs				= array();

	/**
	 *	Constructor, receives Console Arguments.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( bool $fallBackOnEmptyPair = FALSE )
	{
		parent::__construct();
		$count	= 0;
		global $argv;
		if( !is_array( $argv ) )
			throw new RuntimeException( 'Missing arguments' );
		if( !$fallBackOnEmptyPair && in_array( 'fallBackOnEmptyPair', $argv, TRUE ) )
			$fallBackOnEmptyPair	= TRUE;
		foreach( $argv as $argument ){
			if( substr_count( $argument, self::$delimiterAssign ) || $fallBackOnEmptyPair ){
				$parts	= explode( self::$delimiterAssign, $argument, 2 );
				$key	= array_shift( $parts );
				$value	= $parts ? $parts[0] : NULL;
				$this->pairs[$key]	= $value;
			}
			else
				$this->pairs[$count++]	= $argument;
		}
	}
}
