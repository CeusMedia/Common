<?php
/**
 *	Turing Machine with 1 Band.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		alg.turing
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			30.4.2005
 *	@version		0.6
 */
/**
 *	Turing Machine with 1 Band.
 *	@package		alg.turing
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			30.4.2005
 *	@version		0.6
 */
class Alg_Turing_Machine
{
	/**	@var	array		$states			States of Machine */
	protected $states;
	/**	@var	array		$alphabet		Alphabet of Machine Language */
	protected $alphabet;
	/**	@var	array		$transition		Transitions of Machine */
	protected $transition;
	/**	@var	array		$start			Start State */
	protected $start;
	/**	@var	array		$blank			Blank Sign of Machine Language */
	protected $blank;
	/**	@var	array		$finals			Final States */
	protected $finals;
	/**	@var	array		$state			Current State of Machine */
	protected $state;
	/**	@var	int			$pointer		Current Pointer of Machine */
	protected $pointer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$states			States of Machine
	 *	@param		array		$alphabet		Alphabet of Machine Language
	 *	@param		array		$transition		Transitions of Machine
	 *	@param		array		$start			Start State
	 *	@param		array		$blank			Blank Sign of Machine Language
	 *	@param		array		$finals			Final States
	 *	@return		void
	 */
 	public function __construct( $states, $alphabet, $transition, $start, $blank, $finals )
	{
		$this->states		= $states;
		$this->alphabet		= $alphabet;
		$this->transition	= $transition;
		$this->start		= $start;
		$this->blank		= $blank;
		$this->finals		= $finals;
	}
	
	/**
	 *	Deletes not needed Blanks at start and end of the Band.
	 *	@access		private
	 *	@param		string		$band			current Band to be cleaned up
	 *	@return		string
	 */
	private function cleanBand( &$band )
	{
		while( substr( $band, 0, 1 ) == $this->blank )
			$band = substr( $band, 1 );
		while( substr( $band, -1 ) == $this->blank )
			$band = substr( $band, 0, -1 );
	}

	/**
	 *	Returns current Sign.
	 *	@access		private
	 *	@param		string		$band			current Band to be cleaned up
	 *	@param		string		$pointer		current Position on Band
	 *	@return		string
	 */
	private function getCurrent( &$band, $pointer )
	{
		if( $pointer < 0 || $pointer >= strlen( $band ) )
		{
			$current = $this->blank;
			$this->extendBand( $band, $pointer );
		}
		else $current = substr( $band, $pointer, 1 );
		return $current;
	}
	
	/**
	 *	Checks and extends the pseudo infinite Band.
	 *	@access		private
	 *	@param		string		$band			current Band to be cleaned up
	 *	@param		string		$pointer		current Position on Band
	 *	@return		string
	 */
	private function extendBand( &$band, $pointer )
	{
		if( $pointer < 0 )
			$band = $this->blank.$band;
		else if( $pointer >= strlen( $band ) )
			$band .= $this->blank;
	}
	
	/**
	 *	Runs the Machine.
	 *	@access		public
	 *	@param		string		$input			Input to be worked
	 *	@return		string
	 */
	public function run( $input )
	{
		$this->state = $this->start;
		$this->pointer = 0;
		$output = $input;
		$this->wrapBand( $output );
		while( !in_array( $this->state, $this->finals ) )
		{
			if( $_counter > 200 )
				break;
			$_counter++;
			$_current = $this->getCurrent( $output, $this->pointer );
			reset( $this->transition );
			foreach( $this->transition as $trans )
			{
				if( $trans[0] == array( $this->state, $_current ) )
				{
					$value = $trans[1];
					$state = $value[0];
					$this->state = $state;
					$write = $value[1];
					$left = substr( $output, 0, $this->pointer );
					$right = substr( $output, $this->pointer+1 );
					$output = $left.$write.$right;
					$direction = $value[2];
					if( $direction == "l" )
						$this->pointer--;
					else if( $direction == "r" )
						$this->pointer++;
					$this->extendBand( $output, $this->pointer );
					$this->wrapBand( $output );
					break;
				}
			}
			echo $this->showBand( $output );
		}
		$this->cleanBand( $output );
		return $output;
	}

	/**
	 *	Generates HTML Visualisation of current Band.
	 *	@access		public
	 *	@param		string		$band			current Band
	 *	@return		string
	 */
	public function showBand( $band )
	{
		for( $i=0; $i<strlen( $band ); $i++ )
		{
			$sign = substr( $band, $i, 1 );
			if( $i == $this->pointer )
				$lines[] = "<td style='background: #FF7F7F'>".$sign."</td>";
			else
				$lines[] = "<td>".$sign."</td>";
		}
//		return "<code>(".$this->state.") ".implode( "", $lines)."</code><br>"; 
		return "<tr><td>(".$this->state.")</td>".implode( "", $lines )."</tr>"; 
	}

	/**
	 *	Adds Blanks at start and end of the Band.
	 *	@access		private
	 *	@param		string		$band			current Band to be cleaned up
	 *	@return		string
	 */
	private function wrapBand( &$band )
	{
		if( substr( $band, 0, 1 ) != $this->blank )
			$band = $this->blank.$band;	
		if( substr( $band, -1 ) != $this->blank )
			$band = $band.$this->blank;	
	}
}
?>