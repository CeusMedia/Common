<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Collector of Ranges for Duration Phrase.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Time;

use Countable;
use Exception;

/**
 *	Collector of Ranges for Duration Phrase.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class DurationPhraseRanges implements Countable
{
	protected array $ranges		= [];
	protected string $regExp	= '@^([0-9]+)(s|m|h|D|W|M|Y)$@';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$ranges		Ranges to import from associative Array with Keys 'from', 'to' and 'label'.
	 *	@return		void
	 */
	public function __construct( array $ranges = [] )
	{
		foreach( $ranges as $from => $label )
			$this->addRange( $from, $label );
	}

	/**
	 *	Adds a Range.
	 *	@access		public
	 *	@param		string		$from		Start of Range, eg. 0
	 *	@param		string		$label		Range Label, eg. "{s} seconds"
	 *	@return		self
	 */
	public function addRange( string $from, string $label ): self
	{
		$from	= preg_replace_callback( $this->regExp, [$this, 'calculateSeconds'], $from );
		$this->ranges[(int) $from]	= $label;
		ksort( $this->ranges );
		return $this;
	}

	/**
	 *	Returns number of collected Ranges.
	 *	@access		public
	 *	@return		int
	 */
	public function count(): int
	{
		return count( $this->ranges );
	}

	/**
	 *	Callback to replace Time Units by factorized Value.
	 *	@access		protected
	 *	@param		array		$matches		Array of Matches of regular Expression in 'addRange'.
	 *	@return		int
	 *	@throws		Exception
	 */
	protected function calculateSeconds( array $matches ): int
	{
		$value	= (int) $matches[1];
		$format	= $matches[2];
		switch( $format ){
			case 's': 	return $value;
			case 'm': 	return $value * 60;
			case 'h': 	return $value * 60 * 60;
			case 'D': 	return $value * 60 * 60 * 24;
			case 'W': 	return $value * 60 * 60 * 24 * 7;
			case 'M': 	return (int) floor( $value * 60 * 60 * 24 * 30.4375 );
			case 'Y': 	return (int) floor( $value * 60 * 60 * 24 * 365.25 );
		}
		throw new Exception( 'Unknown date format "'.$format.'"' );
	}

	/**
	 *	Returns Array of collected Ranges.
	 *	@access		public
	 *	@return		array
	 */
	public function getRanges(): array
	{
		return $this->ranges;
	}
}
