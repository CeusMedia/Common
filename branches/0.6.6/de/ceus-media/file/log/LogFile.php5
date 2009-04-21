<?php
/**
 *	Writer for Log File.
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
 *	@package		file
 *	@subpackage		log
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
import ("de.ceus-media.alg.TimeConverter"); 
/**
 *	Writer for Log File.
 *	@package		file
 *	@subpackage		log
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
class LogFile
{
	/**	@var		string		$uri		URI of Log File */
	protected $uri;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of Log File
	 *	@return		void
	 */
	public function __construct( $uri )
	{
		$this->uri = $uri;
	}

	/**
	 *	Adds an entry to the logfile.
	 *
	 *	@access		public
	 *	@param		string		$line		Entry to add to Log File
	 *	@return		bool
	 */
	public function addEntry( $line )
	{
		$tc = new Alg_TimeConverter();
		$entry = time()." [".$tc->convertToHuman( time(), "datetime" )."] ".$line."\n";

		$fp = @fopen( $this->uri, "ab" );
		if( $fp )
		{
			@fwrite( $fp, $entry );
			@fclose( $fp );
			return true;
		}
		return false;
	}
}
?>