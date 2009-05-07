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
 *	@package		file.log
 *	@uses			TimeConverter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import ("de.ceus-media.alg.TimeConverter"); 
/**
 *	Writer for Log File.
 *	@package		file.log
 *	@uses			TimeConverter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class File_Log_Writer
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
	 *	Adds an Note to Log File.
	 *
	 *	@access		public
	 *	@param		string		$line		Entry to add to Log File
	 *	@return		bool
	 */
	public function note( $line, $format = "datetime" )
	{
		$converter 	= new Alg_TimeConverter();
		$time		= $format ? " [".$converter->convertToHuman( time(), $format )."]" : "";
		$message	= time().$time." ".$line."\n";
		return error_log( $message, 3, $this->uri );
	}
}
?>