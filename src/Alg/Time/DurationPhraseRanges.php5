<?php
/**
 *	Collector of Ranges for Duration Phrase.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Alg.Time
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		$Id$
 */
/**
 *	Collector of Ranges for Duration Phrase.
 *	@category		cmClasses
 *	@package		Alg.Time
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		$Id$
 */
class Alg_Time_DurationPhraseRanges implements Countable
{
	protected $ranges	= array();
	protected $regExp	= '@^([0-9]+)(s|m|h|D|W|M|Y)$@';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$ranges		Ranges to import from associative Array with Keys 'from', 'to' and 'label'.
	 *	@return		void
	 */
	public function __construct( $ranges = array() )
	{
		foreach( $ranges as $from => $label )
			$this->addRange( $from, $label );
	}

	/**
	 *	Adds a Range.
	 *	@access		public
	 *	@param		string		$from		Start of Range, eg. 0
	 *	@param		string		$label		Range Label, eg. "{s} seconds"
	 *	@return		void
	 */
	public function addRange( $from, $label )
	{
		$from	= preg_replace_callback( $this->regExp, array( $this, 'calculateSeconds' ), $from );
		$this->ranges[(int) $from]	= $label;
		ksort( $this->ranges );
	}

	/**
	 *	Callback to replace Time Units by factorized Value.
	 *	@access		protected
	 *	@param		array		$matches		Array of Matches of regular Expression in 'addRange'.
	 *	@return		mixed
	 */
	protected function calculateSeconds( $matches )
	{
		$value	= $matches[1];
		$format	= $matches[2];
		switch( $format )
		{
			case 's': 	return $value;
			case 'm': 	return $value * 60;
			case 'h': 	return $value * 60 * 60;
			case 'D': 	return $value * 60 * 60 * 24;
			case 'W': 	return $value * 60 * 60 * 24 * 7;
			case 'M': 	return $value * 60 * 60 * 24 * 30.4375;
			case 'Y': 	return $value * 60 * 60 * 24 * 365.25;
		}
		throw new Exception( 'Unknown date format "'.$format.'"' );
	}

	/**
	 *	Returns number of collected Ranges.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return count( $this->ranges );
	}

	/**
	 *	Returns Array of collected Ranges.
	 *	@access		public
	 *	@return		array
	 */
	public function getRanges()
	{
		return $this->ranges;
	}
}
?>