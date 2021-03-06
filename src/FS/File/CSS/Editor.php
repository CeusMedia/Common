<?php
/**
 *	Editor for CSS files or given sheet structures.
 *
 *	Copyright (c) 2011-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			10.10.2011
 */
/**
 *	Editor for CSS files.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_CSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			10.10.2011
 */
class FS_File_CSS_Editor{

	public function __construct( $fileName = NULL ){
		if( $fileName )
			$this->setFileName( $fileName );
	}

	public function addRuleBySelector( $selector, $properties = array() ){
		$rule	= new ADT_CSS_Rule( $selector, $properties );
		$this->sheet->addRule( $rule );
		return $this->save();
	}

	public function changePropertyKey( $selector, $keyOld, $keyNew ){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		$rule	= $this->sheet->getRuleBySelector( $selector );
		if( !$rule->hasPropertyByKey( $keyOld ) )
			throw new OutOfRangeException( 'Property with key "'.$keyOld.'" is not existing' );
		$property	= $rule->getPropertyByKey( $keyOld );
		$property->setKey( $keyNew );
		$this->save();
	}

	public function changeRuleSelector( $selectorOld, $selectorNew ){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		$rule	= $this->sheet->getRuleBySelector( $selectorOld );
		if( !$rule )
			throw new OutOfRangeException( 'Rule with selector "'.$selectorOld.'" is not existing' );
		$rule->setSelector( $selectorNew );
		$this->save();
	}

	/**
	 *
	 *	@access		public
	 *	@param		string		$selector		Rule selector
	 *	@param		string		$key			Property key
	 *	@return		string|NULL
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function get( $selector, $key = NULL ){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		return $this->sheet->get( $selector, $key );
	}

	/**
	 *	Returns a list of CSS property objects by a rule selector.
	 *	@access		public
	 *	@param		string		$selector		Rule selector
	 *	@return		array
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function getProperties( $selector ){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		$rule	= $this->sheet->getRuleBySelector( $selector );
		if( !$rule )
			return array();
		return $rule->getProperties();
	}

	/**
	 *	Returns list of found rule selectors.
	 *	@access		public
	 *	@return		array
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function getSelectors(){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		return $this->sheet->getSelectors();
	}

	/**
	 *
	 */
	public function getSheet(){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		return $this->sheet;
	}

	/**
	 *	Removes a rule property by rule selector and property key.
	 *	@access		public
	 *	@param		string		$selector		Rule selector
	 *	@param		string		$key			Property key
	 *	@return		boolean
	 *	@throws		RuntimeException	if no CSS sheet is loaded, yet.
	 */
	public function remove( $selector, $key = NULL ){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		$result	= $this->sheet->remove( $selector, $key );
		$this->save();
		return $result;
	}

	/**
	 *	Writes current sheet to CSS file and returns number of written bytes.
	 *	@access		protected
	 *	@return		integer		Number of written bytes
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	protected function save(){
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return FS_File_CSS_Writer::save( $this->fileName, $this->sheet );
	}

	public function set( $selector, $key, $value ){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		$result	= $this->sheet->set( $selector, $key, $value );
		return $this->save();
		return $result;
	}

	public function setFileName( $fileName ){
		$this->fileName	= $fileName;
		$this->sheet	= FS_File_CSS_Parser::parseFile( $fileName );
	}

	public function setSheet( ADT_CSS_Sheet $sheet ){
		$this->sheet	= $sheet;
	}
}
