<?php
class FS_File extends FS_AbstractNode{

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
