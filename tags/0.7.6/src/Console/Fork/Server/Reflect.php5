<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Console.Fork.Server
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		Console.Fork.Server
 *	@extends		Console_Fork_Server_Abstract
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Console_Fork_Server_Reflect extends Console_Fork_Server_Abstract
{
	protected function handleRequest( $request )
	{
		$buffer		= array( "\n" );
		$buffer[]	= "Total requests: ".$this->statSeenTotal;
		$buffer[]	= "Maximum simultaneous: ".$this->statSeenMax;
		$buffer[]	= "Currently active: " . ( count( $this->childrenMap ) + 1 );
		$buffer[]	= "Running since: " . date( "D, d M Y H:i:s T", $this->timeStarted );
		$buffer[]	= "Server time: " . date( "D, d M Y H:i:s T", time() );
		$buffer[]	= "";
		$buffer[]	= "Your request: ".$request;
		$buffer		= implode( "\n", $buffer );
		return $buffer;
	}
}
?>