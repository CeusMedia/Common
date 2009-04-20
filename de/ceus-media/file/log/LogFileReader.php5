<?php
/**
 *	Reader for Log File.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		file.log
 *	@extends		LogFile
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
import( 'de.ceus-media.file.log.LogFile' );
/**
 *	Reader for Log File.
 *	@package		file.log
 *	@extends		LogFile
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
class LogFileReader extends LogFile
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@return		void
	 */
	public function __construct( $uri )
	{
		parent::__construct( $uri );
	}


	/**
	 *	Reads Log File and returns Lines.
	 *	@access		public
	 *	@return		array
	 */
	public function read()
	{
		if( !file_exists( $this->uri ) )
			throw new Exception( "Log File '".$this->uri."' is not existing." );
		if( $fcont = file( $this->uri ) )
		{
			$array = array();
			foreach( $fcont as $line )
				$array[] = trim( $line );
			return $array;
		}
		return array();
	}
}
?>