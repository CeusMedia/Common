<?php
/**
 *	Handler for Console Requests.
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
 *	@package		Console
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.02.2007
 *	@version		$Id$
 */
/**
 *	Handler for Console Requests.
 *	@category		cmClasses
 *	@package		Console
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.02.2007
 *	@version		$Id$
 */
class Console_RequestReceiver extends ADT_List_Dictionary
{
	public static $delimiterAssign	= "=";

	/**
	 *	Constructor, receives Console Arguments.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $fallBackOnEmptyPair = FALSE )
	{
		$count	= 0;
		global $argv;
		//$argv = array("runJob.php5", "Job_SaleTermination" , "cmd=");	
		if( !is_array( $argv ) )
			throw new RuntimeException( 'Missing arguments' );
		if( !$fallBackOnEmptyPair && in_array( 'fallBackOnEmptyPair', $argv ) )
			$fallBackOnEmptyPair	= TRUE;
		foreach( $argv as $argument )
		{
			if( !( $fallBackOnEmptyPair && !substr_count( $argument, self::$delimiterAssign ) ) )
			{
				$parts	= explode( self::$delimiterAssign, $argument );
				$key	= array_shift( $parts );
				$this->pairs[$key]	= (string)implode( "=", $parts );
			}
			else
				$this->pairs[$count++]	= $argument;
		}
	}
}
?>