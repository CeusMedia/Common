<?php
/**
 *	Collector of Ranges for Duration Phrase.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		alg.time
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		0.1
 */
/**
 *	Collector of Ranges for Duration Phrase.
 *	@package		alg.time
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		0.1
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
		$keys	= array( 'from', 'to', 'label' );
		foreach( $ranges as $range )
		{
			foreach( $keys as $key )
				if( !isset( $range[$key] ) )
					throw new InvalidArgumentException( 'Missing attribute "'.$key.'"' );
			$this->addRange( $range['from'], $range['to'], $range['label'] );
		}
	}

	/**
	 *	Adds a Range.
	 *	@access		public
	 *	@param		string		$from		Start of Range, eg. 0
	 *	@param		string		$to			End of Range, eg. 10
	 *	@param		string		$label		Range Label, eg. "less than 10 seconds, exactly: {s} seconds"
	 *	@return		void
	 */
	public function addRange( $from, $to, $label )
	{
		$from	= preg_replace_callback( $this->regExp, array( $this, 'calculateSeconds' ), $from );
		$to		= preg_replace_callback( $this->regExp, array( $this, 'calculateSeconds' ), $to );
		$this->ranges[]	= array(
			'from'	=> $from,
			'to'	=> $to,
			'label'	=> $label
		);
		$this->sortRanges();
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
	 *	Exports collected Ranges to List.
	 *	@access		public
	 *	@param		string		$separator		Separator Sign between Range Values
	 *	@return		array
	 */
	public function exportToList( $separator = "|" )
	{
		$list	= array();
		foreach( $this->ranges as $range )
			$list[]	= $range['from'].$separator.$range['to'].$separator.$range['label'];
		return $list;
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

	/**
	 *	Imports Ranges from a List with Entries like "from|to|label" with definable Separator.
	 *	@access		public
	 *	@param		array		$list		List of Range Strings like "from|to|label"
	 *	@param		string		$separator	Separator between 'from', 'to' and 'label'
	 *	@return		void
	 */
	public function importFromList( $list, $separator = "|" )
	{
		foreach( $list as $range )
		{
			if( substr_count( $range, $separator ) !== 2 )
				throw new Exception( 'Invalid range "'.$range.'" using separator "'.$separator.'"' );
			list( $from, $to, $label )	= explode( $separator, $range );
			$this->addRange( $from, $to, $label );
		}
	}

	/**
	 *	Imports Ranges from an associative Array (=Map) like "from|to => label" with definable Separator.
	 *	@access		public
	 *	@param		array		$array		Map of Ranges and Labels, like "from|to => label"
	 *	@param		string		$separator	Separator between 'from' and 'to' in Map Key
	 *	@return		void
	 */
	public function importFromMap( $array, $separator = "|" )
	{
		foreach( $array as $range => $label )
		{
			if( substr_count( $range, $separator ) !== 1 )
				throw new Exception( 'Invalid range "'.$range.'" using separator "'.$separator.'"' );
			list( $from, $to )	= explode( $separator, $range );
			$this->addRange( $from, $to, $label );
		}
	}

	protected function sortRanges()
	{
/*		$ranges	= $this->ranges;
		$list	= array();
		while( $ranges )
		{
			$lowest	= array();
			foreach( $ranges as $range )
			{
				if( 		
			}
		}*/
	}
}
?>