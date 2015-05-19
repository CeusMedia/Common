<?php
class Alg_Text_Extender{

	static $encoding	= "UTF-8";

	static public function extend( $text, $toLength, $fromLeft = FALSE, $withString = ' ' ){
		if( !function_exists( 'mb_strlen' ) )
			return str_pad( $text, $toLength, $withString, $fromLeft ? STR_PAD_LEFT : STR_PAD_RIGHT );
		$textLength			= mb_strlen( $text, self::$encoding );
		$withStringLength	= mb_strlen( $withString, self::$encoding );
		if( !$toLength || !$withStringLength || !$textLength || $toLength <= $textLength )
			return $text;
		$repeat	= ceil( $textLength - $withStringLength + $toLength );
		if( $fromLeft ){
			$result	= str_repeat( $withString, $repeat );
			$pos	= $toLength - ( ( $textLength - $withStringLength ) + $withStringLength );
			$result	= mb_substr( $result, 0, $pos, self::$encoding ).$text;
		}
		else{
			$result	= $text.str_repeat( $withString, $repeat );
			$result	= mb_substr( $result, 0, $toLength, self::$encoding );
		}
		return $result;
	}

	static public function extendCentric( $text, $toLength, $withString = ' ' ){
		if( !function_exists( 'mb_strlen' ) )
			return str_pad( $text, $toLength, $withString, STR_PAD_BOTH );
		$textLength			= mb_strlen( $text );
		$withStringLength	= mb_strlen( $withString );
		if( !$toLength || !$withStringLength || !$textLength || $toLength <= $textLength )
			return $text;

		$length	= ( $toLength - $textLength ) / 2;
		$repeat	= ceil( $length / $withStringLength );
		$left	= mb_substr( str_repeat( $withString, $repeat ), 0, floor( $length ) );
		$right	= mb_substr( str_repeat( $withString, $repeat ), 0, ceil( $length ) );
		return $left.$text.$right;
	}
}
?>

