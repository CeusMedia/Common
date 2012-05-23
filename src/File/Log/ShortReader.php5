<?php
/**
 *	Reader for short Log Files.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		File.Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.12.2006
 *	@version		$Id$
 */
/**
 *	Reader for short Log Files.
 *	@category		cmClasses
 *	@package		File.Log
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.12.2006
 *	@version		$Id$
 *	@todo			Prove File for Existence
 */
class File_Log_ShortReader
{
	/*	@var		array		$data		Array of Data in Lines */
	protected $data	= FALSE;
	/*	@var		bool		$open		Status: Log File is read */
	protected $open	= FALSE;
	/*	@var		string		$patterns	Pattern Array filled with Logging Information */
	protected $patterns	= array();
	/**	@var		string		$uri		URI of Log File */
	protected $uri;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of short Log File
	 *	@return		void
	 */
	public function __construct( $uri )
	{
		$this->uri	= $uri;
		$patterns	= array(
			'timestamp',
			'remote_addr',
			'request_uri',
			'http_referer',
			'http_user_agent'
		);
		$this->setPatterns( $patterns );
	}
	
	/**
	 *	Returns parsed Log Data as Array.
	 *	@access		public
	 *	@return		array
	 *	@version	0.1
	 */
	public function getData()
	{
		if( $this->open )
			return $this->data;
		trigger_error( "Log File not read", E_USER_ERROR );
		return array();
	}
	
	/**
	 *	Indicated whether Log File is already opened and read.
	 *	@access		protected
	 *	@return		bool
	 */
	protected function isOpen()
	{
		return $this->open;
	}

	/**
	 *	Reads Log File.
	 *	@access		public
	 *	@return		bool
	 */
	public function read()
	{
		if( $fcont = @file( $this->uri ) )
		{
			$this->data = array();
			foreach( $fcont as $line )
				$this->data[] = explode( "|", trim( $line ) );
			$this->open	= true;
			return true;
		}
		return false;
	}

	/**
	 *	Sets Pattern Array filled with Logging Information.
	 *
	 *	@access		public
	 *	@param		array		$array		Array of Patterns.
	 *	@return		void
	 */
	public function setPatterns( $array )
	{
		if( is_array( $array ) )
			$this->patterns	= $array;
	}
}
?>