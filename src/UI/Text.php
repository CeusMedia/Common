<?php
class UI_Text{

	static public $defaultLineLength		= 76;

	static public function line( $sign, $length = NULL ){
		$length	= self::realizeLength( $length, 1 );
		return str_repeat( $sign, (int) $length );
	}

	static public function char( $number ){
		return html_entity_decode( '&#'.$number.';', ENT_NOQUOTES, 'UTF-8' );
	}

	static protected function realizeLength( $length, $min = NULL, $max = NULL ){
		$length	= abs( (int) $length );
		$length	= $length ? $length : self::$defaultLineLength;
		if( abs( $min ) )
			$length		= max( $min, $length );
		if( abs( $max ) )
			$length		= min( $max, $length );
		return $length;
	}

	static public function formatBytes( $bytes ){
		return Alg_UnitFormater::formatBytes( $bytes );
	}

	static public function pad( $string, $length, $sign = ' ', $alignRight = FALSE ){
		$string	= substr( $string, 0, $length );
		$sign	= substr( trim( $sign ), 0, 1 );
		$sign	= !strlen( $sign ) ? ' ' : $sign;
		$align	= $alignRight ? STR_PAD_LEFT : STR_PAD_RIGHT;
		return str_pad( $string, $length, $sign, $align );
	}

	static public function padLeft( $string, $length, $sign = ' ' ){
		return self::pad( $string, $length, $sign, TRUE );
	}

	static public function padRight( $string, $length, $sign = ' ' ){
		return self::pad( $string, $length, $sign, FALSE );
	}
}
