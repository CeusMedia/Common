<?php
/**
 *	Reads CSS files and returns a structure of ADT_CSS_* objects or an array.
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
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
 *	@package		File.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.10.2011
 *	@version		$Id$
 */
/**
 *	Reads CSS files and returns a structure of ADT_CSS_* objects or an array.
 *
 *	@category		cmClasses
 *	@package		File.CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.10.2011
 *	@version		$Id$
 */
class File_CSS_Reader
{

	protected $fileName;

	protected $content;

	/**
	 *	Contructor.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		void
	 */
	public function __construct( $fileName = NULL ){
		if( $fileName )
			$this->setFileName( $fileName );
	}

	/**
	 *	Returns content of CSS file as list of rules.
	 *	@access		public
	 *	@return		array
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	public function getRules(){
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return File_CSS_Converter::convertSheetToArray( $this->sheet );
	}

	/**
	 *	Returns content of CSS file as sheet structure.
	 *	@access		public
	 *	@return		ADT_CSS_Sheet
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	public function getSheet(){
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return $this->sheet;
	}

	/**
	 *	Loads a CSS file and returns sheet structure statically.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		ADT_CSS_Sheet
	 */
	static public function load( $fileName ){
		return File_CSS_Parser::parseFile( $fileName );
	}

	/**
	 *	Points reader to a CSS file which will be parsed and stored internally.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		void
	 */
	public function setFileName( $fileName ){
		$this->fileName	= $fileName;
		$this->sheet	= self::load( $fileName );
	}
}
?>
