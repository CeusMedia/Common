<?php
class CLI_Size{

	static protected $width		= 0;

	static protected $height	= 0;

	static public function getSize( $force = FALSE ){
/*		preg_match_all("/rows.([0-9]+);.columns.([0-9]+);/", strtolower(exec('stty -a |grep columns')), $output);
		if(sizeof($output) == 3) {
			self::$width	= $output[2][0];
			self::$height	= $output[1][0];
		}*/
		return (object) array(
			'width'		=> self::getWidth(),
			'height'	=> self::getHeight(),
		);
	}

	static public function getHeight( $force = FALSE ){
		if( !self::$height || $force )
			self::$height	= exec( 'tput lines' );
		return self::$height;
	}

	static public function getWidth( $force = FALSE ){
		if( !self::$width || $force )
			self::$width	= exec( 'tput cols' );
		return self::$width;
	}
}
?>
