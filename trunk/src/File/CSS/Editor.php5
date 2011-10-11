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
class File_CSS_Editor{

	public function __construct( $fileName ){
		$reader	= new File_CSS_Reader( $fileName );
		$this->sheet	= $reader->toSheet();
	}

	public function get( $selector, $key = NULL ){
		return $this->sheet->set( $selector, $key, $value );
	}

	public function getSelectors(){
		return $this->sheet->getSelectors();
	}

	public function getProperties( $selector ){
		$rule	= $this->sheet->getRuleBySelector( $selector );
		if( !$rule )
			return array();
		return $rule->getProperties();
	}

	public function save(){
		$converter	= new File_CSS_Converter( $this->sheet );
		return $converter->toString();
	}

	public function set( $selector, $key, $value ){
		return $this->sheet->set( $selector, $key, $value );
	}

	public function remove( $selector, $key = NULL ){
		return $this->sheet->remove( $selector, $key );
	}
}
?>
