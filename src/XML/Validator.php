<?php
/**
 *	Validates XML.
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
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Validates XML.
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Unit Test
 */
class XML_Validator
{
	/**	@var		array		$error		Array of Error Information */
	protected $error	= array();

	/**
	 *	Returns last error line.
	 *	@access		public
	 *	@return		int
	 */
	public function getErrorLine()
	{
		if( $this->error )
			return $this->error['line'];
		return -1;
	}

	/**
	 *	Returns last error message.
	 *	@access		public
	 *	@return		string
	 */
	public function getErrorMessage()
	{
		if( $this->error )
			return $this->error['message'];
		return '';
	}

	/**
	 *	Validates XML File.
	 *	@access		public
	 *	@return		bool
	 */
	public function validateFile( $fileName )
	{
		$xml = FS_File_Reader::load( $fileName );
		return $this->validate( $xml );
	}

	/**
	 *	Validates XML URL.
	 *	@access		public
	 *	@return		bool
	 */
	public function validateUrl( $url)
	{
		$xml	= Net_Reader::readUrl( $url );
		return $this->validate( $xml );
	}

	/**
	 *	Validates XML File.
	 *	@access		public
	 *	@param		string		$xml		XML String to validate
	 *	@return		bool
	 */
	public function validate( $xml )
	{
		$parser	= xml_parser_create();
		$dummy	= create_function( '', '' );
		xml_set_element_handler( $parser, $dummy, $dummy );
		xml_set_character_data_handler( $parser, $dummy );
		if( !xml_parse( $parser, $xml ) )
		{
			$msg	= "%s at line %d";
			$error	= xml_error_string( xml_get_error_code( $parser ) );
			$line	= xml_get_current_line_number( $parser );
			$this->error['message']	= sprintf( $msg, $error, $line );
			$this->error['line']	= $line;
			$this->error['xml']		= $xml;
			xml_parser_free( $parser );
			return FALSE;
		}
		xml_parser_free( $parser );
		return TRUE;
	}
}
