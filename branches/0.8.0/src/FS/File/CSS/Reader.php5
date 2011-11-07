<?php
/**
 *	Reads CSS files and returns a structure of ADT_CSS_* objects or an array.
 *
 *	@author		Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since		10.10.2011
 *	@version	$Id$
 */
/**
 *	Reads CSS files and returns a structure of ADT_CSS_* objects or an array.
 *
 *	@author		Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since		10.10.2011
 *	@version	$Id$
 */
class File_CSS_Reader{

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
	 *	Points reader to a CSS file which will be parsed and stored internally.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		void
	 */
	public function setFileName( $fileName ){
		$this->fileName	= $fileName;
		$this->sheet	= self::load( $fileName );
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
}
?>
