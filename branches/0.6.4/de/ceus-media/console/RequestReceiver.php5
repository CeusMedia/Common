<?php
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Handler for Console Requests.
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
 *	@package		console
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.02.2007
 *	@version		0.1
 */
/**
 *	Handler for Console Requests.
 *	@package		console
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.02.2007
 *	@version		0.1
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