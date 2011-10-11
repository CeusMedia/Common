<?php
/**
 *	Editor for CSS files.
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
class File_CSS_Writer{

	protected $fileName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		void
	 */
	public function __construct( $fileName = NULL ){
		if( $fileName )
			$this->setFileName( $fileName );
	}

	/**
	 *	Returns name of current CSS File.
	 *	@access		public
	 *	@return		string
	 */
	public function getFileName(){
		return $this->fileName;
	}

	/**
	 *	Set name of CSS file.
	 *	@access		public
	 *	@param		string		$fileName		Relative or absolute file URI
	 *	@return		void
	 */
	public function setFileName( $fileName ){
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes a sheet structure to the current CSS file.
	 *	@access		public
	 *	@param		ADT_CSS_Sheet	$sheet		Sheet structure
	 *	@return		void
	 *	@throws		RuntimeException	if no CSS file is set, yet.
	 */
	public function write( ADT_CSS_Sheet $sheet ){
		if( !$this->fileName )
			throw new RuntimeException( 'No CSS file set yet' )
		return self::save( $this->fileName, $sheet );
	}

	/**
	 *	Save a sheet structure into a file statically.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName	Relative or absolute file URI
	 *	@param		ADT_CSS_Sheet	$sheet		Sheet structure
	 *	@return		void
	 */
	static public function save( $fileName, ADT_CSS_Sheet $sheet ){
		$css	= File_CSS_Converter::convertSheetToString( $sheet );								//  
		return File_Writer::save( $fileName, $css );												//  
	}
}
?>
