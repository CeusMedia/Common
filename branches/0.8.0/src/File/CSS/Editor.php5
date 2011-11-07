<?php
/**
 *	Editor for CSS files or given sheet structures.
 *
 *	@author		Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since		10.10.2011
 *	@version	$Id$
 */
/**
 *	Editor for CSS files.
 *
 *	@author		Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since		10.10.2011
 *	@version	$Id$
 */
class File_CSS_Editor{

	public function __construct( $fileName = NULL ){
		if( $fileName )
			$this->setFileName( $fileName );
	}

	public function addRuleBySelector( $selector, $properties = array() ){
		$rule	= new ADT_CSS_Rule( $selector, $properties );
		$this->sheet->addRule( $rule );
		return $this->save();
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
	 *	Writes current sheet to CSS file and returns number of written bytes.
	 *	@access		protected
	 *	@return		integer		Number of written bytes
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	protected function save(){
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' );
		return File_CSS_Writer::save( $this->fileName, $this->sheet );
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

	public function set( $selector, $key, $value ){
		if( !$this->sheet )
			throw new RuntimeException( 'No CSS sheet loaded' );
		$result	= $this->sheet->set( $selector, $key, $value );
		return $this->save();
		return $result;
	}

	public function setFileName( $fileName ){
		$this->fileName	= $fileName;
		$this->sheet	= File_CSS_Parser::parseFile( $fileName );
	}

	public function setSheet( ADT_CSS_Sheet $sheet ){
		$this->sheet	= $sheet;
	}
}
?>
