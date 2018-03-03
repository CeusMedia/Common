<?php
abstract class FS_AbstractNode{

	protected $pathName;

	public function getName( $strict = TRUE ){
		return pathinfo( $this->pathName, PATHINFO_BASENAME );
	}

	public function getPathName(){
		return $this->pathName;
	}

	public function setPathName( $pathName ){
		$pathName	= trim( $pathName );
		if( $pathName !== '/' )
			$pathName	= rtrim( $pathName, '/' );
		$this->pathName	= $pathName;
	}
}
