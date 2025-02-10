<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Time;

use Exception;
use InvalidArgumentException;
use OutOfBoundsException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Doc
 */
class DurationPhraser
{
	protected string $patternLabel	= '@(.*){([smhDWMY])}(.*)::([0-9]+)$@';
	protected string $patternData	= '@::[0-9]+$@';
	protected DurationPhraseRanges $ranges;

	public function __construct( DurationPhraseRanges $ranges )
	{
		$this->ranges	= $ranges;
	}

	public static function fromArray( array $ranges = [] ): self
	{
		$ranges	= new DurationPhraseRanges( $ranges );
		return new self( $ranges );
	}

	public function getPhraseFromSeconds( int $seconds ): string
	{
		if( !count( $this->ranges ) )
			throw new Exception( 'No ranges defined' );
		$callback	= [$this, 'insertDates'];
		$ranges		= $this->ranges->getRanges();
		krsort( $ranges );
		foreach( $ranges as $from => $label ){
			if( $from > $seconds )
				continue;
			$value	= $label."::".$seconds;
			$label	= preg_replace_callback( $this->patternLabel, $callback, $value );
			return preg_replace( $this->patternData, "", $label );
		}
		throw new OutOfBoundsException( 'No range defined for '.$seconds.' seconds' );
	}

	public function getPhraseFromTimestamp(int $timestamp ): string
	{
		$seconds	= time() - $timestamp;
		if( $seconds < 0 )
			throw new InvalidArgumentException( 'Timestamp must lay in past' );
		return $this->getPhraseFromSeconds( $seconds );
	}

	protected static function insertDates( array $matches ): string
	{
		$value	= $matches[4];
		$format	= $matches[2];
		if( $format == "m" )
			$value	= floor( $value / 60 );
		else if( $format == "h" )
			$value	= floor( $value / 60 / 60 );
		else if( $format == "D" )
			$value	= floor( $value / 60 / 60 / 24 );
		else if( $format == "W" )
			$value	= floor( $value / 60 / 60 / 24 / 7 );
		else if( $format == "M" )
			$value	= floor( $value / 60 / 60 / 24 / 30.4375 );
		else if( $format == "Y" )
			$value	= floor( $value / 60 / 60 / 24 / 365.25 );
		else if( $format !== "s" )
			throw new Exception( 'Unknown date format "'.$format.'"' );

		return $matches[1].(int) $value.$matches[3];
	}
}
