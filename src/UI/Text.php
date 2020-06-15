<?php
class UI_Text
{
	static public $defaultLineLength		= 76;

	static public function line( string $sign, int $length = NULL ): string
	{
		$length	= self::realizeLength( (int) $length, 1 );
		return str_repeat( $sign, (int) $length );
	}

	static public function char( string $number ): string
	{
		return html_entity_decode( '&#'.$number.';', ENT_NOQUOTES, 'UTF-8' );
	}

	static protected function realizeLength( int $length, int $min = NULL, int $max = NULL ): int
	{
		$length	= abs( (int) $length );
		$length	= $length ? $length : self::$defaultLineLength;
		if( abs( $min ) )
			$length		= max( $min, $length );
		if( abs( $max ) )
			$length		= min( $max, $length );
		return $length;
	}

	static public function formatBytes( int $bytes ): string
	{
		return UnitFormater::formatBytes( $bytes );
	}

	static public function pad( string $string, int $length, string $sign = ' ', bool $alignRight = FALSE ): string
	{
		$string	= substr( $string, 0, $length );
		$sign	= substr( trim( $sign ), 0, 1 );
		$sign	= !strlen( $sign ) ? ' ' : $sign;
		$align	= $alignRight ? STR_PAD_LEFT : STR_PAD_RIGHT;
		return str_pad( $string, $length, $sign, $align );
	}

	static public function padLeft( string $string, int $length, string $sign = ' ' ): string
	{
		return self::pad( $string, $length, $sign, TRUE );
	}

	static public function padRight( string $string, int $length, string $sign = ' ' ): string
	{
		return self::pad( $string, $length, $sign, FALSE );
	}
}
