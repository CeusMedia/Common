<?php
/**
 *	Converts CSS between:
 *	- a string representation, typically content from a CSS file
 *	- a list of rules meaning an array represenation containering rules and their properties
 *	- a structure out of ADT_CSS_Sheet, ADT_CSS_Rule and ADT_CSS_Property objects
 *	- a file for input and output
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
 *	Converts CSS between.
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
class File_CSS_Converter{

	protected $sheet	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		ADT_CSS_Sheet	$sheet		Sheet structure
	 *	@return		void
	 */
	public function __construct( ADT_CSS_Sheet $sheet = NULL ){
		if( $sheet )
			$this->fromSheet( $sheet );
	}

	/**
	 *	
	 *	@access		public
	 *	@static
	 *	@param		array			$rules		List of CSS rules
	 *	@return		ADT_CSS_Sheet
	 */
	static public function convertArrayToSheet( $rules ){
		$sheet	= new ADT_CSS_Sheet;
		foreach( $rules as $selector => $properties )
			$sheet->addRule( new ADT_CSS_Rule( $selector, $properties ) );
		return $sheet;
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		array			$rules		List of CSS rules
	 *	@return		string
	 */
	static public function convertArrayToString( $rules ){
		$sheet	= self::convertArrayToSheet( $rules );
		return self::convertSheetToString( $sheet );
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		ADT_CSS_Sheet	$sheet		CSS structure
	 *	@return		array
	 */
	static public function convertSheetToArray( ADT_CSS_Sheet $sheet ){
		$level0	= array();
		foreach( $sheet->getRules() as $rule ){
			$level1	= array();
			foreach( $rule->getProperties() as $property )
				$level1[$property->getKey()]	= $property->getValue();
			$level0[$rule->getSelector()]	= $level1;
		}
		return $level0;
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		ADT_CSS_Sheet	$sheet		CSS structure
	 *	@return		string
	 */
	static public function convertSheetToString( ADT_CSS_Sheet $sheet ){
		$lines	= array();
		foreach( $sheet->getRules() as $rule ){
			array_push( $lines, $rule->getSelector().' {' );
			foreach( $rule->getProperties() as $property )
				array_push( $lines, "\t".$property->getKey().': '.$property->getValue().';' );		//  
			$lines[]	= "\t".'}';
		}
		array_push( $lines, '' );
		$css	= implode( "\n", $lines );
		return $css;
	}

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		string			$string		CSS string
	 *	@return		array
	 */
	static public function convertStringToArray( $css ){
		$sheet	= File_CSS_Parser::parseString( $css );
		return self::convertSheetToArray( $sheet );
	} 

	/**
	 *
	 *	@access		public
	 *	@static
	 *	@param		string			$string		CSS structure
	 *	@return		ADT_CSS_Sheet
	 */
	static public function convertStringToSheet( $css ){
		return File_CSS_Parser::parseString( $css );
	} 

	/**
	 *	Reads sheet from array.
	 *	@access		public
	 *	@param		array			$rules		List of CSS rules
	 *	@return		void
	 */
	public function fromArray( $rules ){
		$this->sheet	= self::convertArrayToSheet( $rules );
	}

	/**
	 *	Reads sheet from file.
	 *	@access		public
	 *	@param		string			$fileName	Realtive or absolute file URI
	 *	@return		void
	 */
	public function fromFile( $fileName ){
		$this->sheet	= File_CSS_Parser::parseFile( $fileName );
	}
	
	/**
	 *	Reads sheet.
	 *	@access		public
	 *	@param		ADT_CSS_Sheet	$sheet		CSS structure
	 *	@return		void
	 */
	public function fromSheet( ADT_CSS_Sheet $sheet ){
		$this->sheet	= $sheet;
	}

	/**
	 *	Reads sheet from string.
	 *	@access		public
	 *	@param		string			$string		CSS structure
	 *	@return		void
	 */
	public function fromString( $string ){
		$this->sheet	= File_CSS_Parser::parseString( $string );
	}
	
	/**
	 *	Returns current sheet as list of rules.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(){
		return File_CSS_Converter::convertSheetToArray( $this->sheet );
	}

	/**
	 *	Writes sheet into file and returns number of written bytes.
	 *	@access		public
	 *	@param		string			$fileName	Realtive or absolute file URI
	 *	@return		integer			Number of bytes written.
	 */
	public function toFile( $fileName ){
		$css	= File_CSS_Converter::convertSheetToString( $this->sheet );
		return File_Writer::save( $fileName, $css );
	}

	/**
	 *	Returns current sheet.
	 *	@access		public
	 *	@return		ADT_CSS_Sheet
	 */
	public function toSheet(){
		return $this->sheet;
	}

	/**
	 *	Returns current sheet as CSS string.
	 *	@access		public
	 *	@return		string
	 */
	public function toString(){
		return File_CSS_Converter::convertSheetToString( $this->sheet );
	}
}
?>
