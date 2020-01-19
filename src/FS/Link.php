<?php
/**
 *	@todo		finish implementation, seems to be not finished
 *	@todo		code doc
 */
class FS_Link extends FS_AbstractNode{

	protected $pathName;

	public function __construct( $pathName ){
		$this->setPathName( $pathName );
	}

	public function setPathName( $pathName ){
		$pathName	= trim( $pathName );
		if( $pathName !== '/' )
			$pathName	= rtrim( $pathName, '/' );
		$this->pathName	= $pathName;
	}
}
?>
