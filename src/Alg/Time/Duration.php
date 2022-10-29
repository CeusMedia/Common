<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\Alg\Time;

class Duration
{
	protected bool $shortMode	= FALSE;

	public function __construct()
	{
	}

	public function convertDurationToSeconds( string $duration ): int
	{
		return self::parse( $duration );
	}

	public function convertSecondsToDuration( int $seconds, string $space ): string
	{
		return self::render( $seconds, $space, $this->shortMode );
	}

	public static function parse( string $duration ): int
	{
		$regexWeeks	= '@([0-9]+)w\s*@';
		$regexDays	= '@([0-9]+)d\s*@';
		$regexHours	= '@([0-9]+)h\s*@';
		$regexMins	= '@([0-9]+)m\s*@';
		$regexSecs	= '@([0-9]+)s\s*@';
		$seconds	= 0;
		$matches	= [];
		if( preg_match( $regexWeeks, $duration, $matches ) ){
			$duration	= preg_replace( $regexWeeks, '', $duration );
			$seconds	+= (int) $matches[1] * 7 * 24 * 60 * 60;
		}
		if( preg_match( $regexDays, $duration, $matches ) ){
			$duration	= preg_replace( $regexDays, '', $duration );
			$seconds	+= (int) $matches[1] * 24 * 60 * 60;
		}
		if( preg_match( $regexHours, $duration, $matches ) ){
			$duration	= preg_replace( $regexHours, '', $duration );
			$seconds	+= (int) $matches[1] * 60 * 60;
		}
		if( preg_match( $regexMins, $duration, $matches ) ){
			$duration	= preg_replace( $regexMins, '', $duration );
			$seconds	+= (int) $matches[1] * 60;
		}
		if( preg_match( $regexSecs, $duration, $matches ) ){
			$duration	= preg_replace( $regexSecs, '', $duration );
			$seconds	+= (int) $matches[1];
		}
		return $seconds;
	}

	static public function render( int $seconds, string $space = ' ', bool $shorten = FALSE ): string
	{
		$remaining	= abs( $seconds );
		$secs	 	= $remaining % 60;
		$remaining	= ( $remaining - $secs ) / 60;
		$minutes	= $remaining % 60;
		$remaining	= ( $remaining - $minutes ) / 60;
		$hours		= $remaining % 24;
		$remaining	= ( $remaining - $hours ) / 24;
		$days		= $remaining % 7;
		$weeks		= ( $remaining - $days ) / 7;

		if( $shorten && $weeks )
			$days = $minutes = $seconds = 0;
		else if( $shorten && $days )
			$hours = $minutes = $seconds = 0;
		else if( $shorten && $hours )
			$minutes = $seconds = 0;
		else if( $shorten && $minutes )
			$seconds = 0;

//		$duration	= ( $seconds ? $space.str_pad( $secs, 2, 0, STR_PAD_LEFT ).'s' : '' );
//		$duration	= ( $minutes ? $space.( $hours ? str_pad( $minutes, 2, 0, STR_PAD_LEFT ).'m' : $minutes.'m' ) : '' ).$duration;
//		$duration	= ( $hours ? $space.( $days ? str_pad( $hours, 2, 0, STR_PAD_LEFT ).'h' : $hours.'h' ) : '' ).$duration;

		$duration	= ( $secs ? $space.$secs.'s' : '' );
		$duration	= ( $minutes ? $space.( $hours ? $minutes.'m' : $minutes.'m' ) : '' ).$duration;
		$duration	= ( $hours ? $space.( $days ? $hours.'h' : $hours.'h' ) : '' ).$duration;
		$duration	= ( $days ? $space.$days.'d' : '' ).$duration;
		$duration	= ( $weeks ? $space.$weeks.'w' : '' ).$duration;
		return ltrim( $duration, $space );
	}

	public function sanitize( string $duration, string $space = ' ' ): string
	{
		return self::render( self::parse( $duration ), ' ' );
	}

	public function setShortMode( bool $enableShortMode = TRUE ): self
	{
		$this->shortMode	= (bool) $enableShortMode;
		return $this;
	}
}
