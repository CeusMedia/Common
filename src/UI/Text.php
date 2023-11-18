<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\UI;

use CeusMedia\Common\Alg\UnitFormater;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Text
{
	public static int $defaultLineLength		= 76;

	public static function line( string $sign, int $length = NULL ): string
	{
		$length	= self::realizeLength( $length ?? 0, 1 );
		return str_repeat( $sign, $length );
	}

	public static function char( string $number ): string
	{
		return html_entity_decode( '&#'.$number.';', ENT_NOQUOTES, 'UTF-8' );
	}

	protected static function realizeLength( int $length, int $min = NULL, int $max = NULL ): int
	{
		$length	= abs( $length );
		$length	= $length ?: self::$defaultLineLength;
		if( abs( $min ) !== 0 )
			$length		= max( $min, $length );
		if( abs( $max ) !== 0 )
			$length		= min( $max, $length );
		return $length;
	}

	public static function formatBytes( int $bytes ): string
	{
		return UnitFormater::formatBytes( $bytes );
	}

	public static function pad( string $string, int $length, string $sign = ' ', bool $alignRight = FALSE ): string
	{
		$string	= substr( $string, 0, $length );
		$sign	= substr( trim( $sign ), 0, 1 );
		$sign	= !strlen( $sign ) ? ' ' : $sign;
		$align	= $alignRight ? STR_PAD_LEFT : STR_PAD_RIGHT;
		return str_pad( $string, $length, $sign, $align );
	}

	public static function padLeft( string $string, int $length, string $sign = ' ' ): string
	{
		return self::pad( $string, $length, $sign, TRUE );
	}

	public static function padRight( string $string, int $length, string $sign = ' ' ): string
	{
		/** @noinspection PhpRedundantOptionalArgumentInspection */
		return self::pad( $string, $length, $sign, FALSE );
	}
}
