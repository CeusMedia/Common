<?php
/**
 *	Configuration using JSON file and structure of magic nodes.
 *
 *	Copyright (c) 2015-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
/**
 *	Configuration using JSON file and structure of magic nodes.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
class FS_File_JSON_Config{

	/**	@var		string				$fileName		Name of JSON file */
	protected $fileName;

	/**	@var		ADT_Tree_MagicNode	$data			Node structure */
	protected $data;

	/**	@var		boolean				$format			Flag: format JSON on save */
	protected $format;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		Name of JSON file
	 *	@param		boolean		$format			Flag: format JSON on save
	 *	@return		void
	 */
	public function __construct( $fileName, $format = TRUE ){
		$this->fileName	= $fileName;
		$this->data		= new ADT_Tree_MagicNode();
		$this->format	= $format;
		if( file_exists( $fileName ) )
			$this->data->fromJson( FS_File_Reader::load( $fileName ) );
	}

	/**
	 *	Magic function get value.
	 *	@access		public
	 *	@param		string		$key		Key of node to get value for
	 *	@return		ADT_Tree_MagicNode
	 */
	public function __get( $key ){
		return $this->data->__get( $key );
	}

	/**
	 *	Magic function set value.
	 *	@access		public
	 *	@param		string		$key		Key of to set value for
	 *	@param		string		$value		Value to set
	 *	@return		ADT_Tree_MagicNode
	 */
	public function __set( $key, $value ){
		$this->data->__set( $key, $value );
	}

	/**
	 *	Save node structure to JSON file.
	 *	@access		public
	 *	@return		integer		Number of saved bytes
	 */
	public function save(){
		$json	= $this->data->toJson();
		if( $this->format )
			$json	= ADT_JSON_Formater::format( $json );
		return FS_File_Writer::save( $this->fileName, $json );
	}

	/**
	 *	Returns node structure as array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(){
		return $this->data->toArray();
	}
}
