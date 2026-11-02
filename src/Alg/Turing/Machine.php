<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Turing Machine with one tape.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Turing
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2005-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Turing;

/**
 *	Turing Machine with one tape.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Turing
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2005-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Machine
{
	/**	@var	array			$states			States of Machine */
	protected $states;

	/**	@var	array			$alphabet		Alphabet of Machine Language */
	protected $alphabet;

	/**	@var	array			$transition		Transitions of Machine */
	protected $transition;

	/**	@var	string			$start			Start State */
	protected $start;

	/**	@var	string			$blank			Blank Sign of Machine Language */
	protected $blank;

	/**	@var	array			$finals			Final States */
	protected $finals;

	/**	@var	string			$state			Current State of Machine */
	protected $state;

	/**	@var	int				$pointer		Current Pointer of Machine */
	protected $pointer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$states			States of Machine
	 *	@param		array		$alphabet		Alphabet of Machine Language
	 *	@param		array		$transition		Transitions of Machine
	 *	@param		string		$start			Start State
	 *	@param		string		$blank			Blank Sign of Machine Language
	 *	@param		array		$finals			Final States
	 *	@return		void
	 */
 	public function __construct( array $states, array $alphabet, array $transition, string $start, string $blank, array $finals )
	{
		$this->states		= $states;
		$this->alphabet		= $alphabet;
		$this->transition	= $transition;
		$this->start		= $start;
		$this->blank		= $blank;
		$this->finals		= $finals;
	}

	/**
	 *	Deletes not needed Blanks at start and end of the tape.
	 *	@access		private
	 *	@param		string		$tape			current tape to be cleaned up
	 *	@return		void
	 */
	private function cleanTape( string &$tape ): void
	{
		while( substr( $tape, 0, 1 ) == $this->blank )
			$tape = substr( $tape, 1 );
		while( substr( $tape, -1 ) === $this->blank )
			$tape = substr( $tape, 0, -1 );
	}

	/**
	 *	Runs the machine on an input and returns the tape (without leading and trailing blanks).
	 *	Transitions are lists of [[state, symbol], [newState, symbolToWrite, direction (l|r|n)]].
	 *	The machine halts in a final state or if no transition matches.
	 *	@access		public
	 *	@param		string		$input			Input written on tape
	 *	@param		int			$maxSteps		Maximum number of steps, machine stops silently after these
	 *	@param		bool		$verbose		Flag: print tape as HTML table row after each step
	 *	@return		string
	 */
	public function run( string $input, int $maxSteps = 200, bool $verbose = FALSE ): string
	{
		$this->state	= $this->start;
		$this->pointer	= 0;
		$output			= '' !== $input ? $input : $this->blank;
		$steps			= 0;
		while( !in_array( $this->state, $this->finals ) ){
			if( ++$steps > $maxSteps )
				break;
			$current	= substr( $output, $this->pointer, 1 );
			$found		= FALSE;
			foreach( $this->transition as $trans ){
				if( $trans[0] != [$this->state, $current] )
					continue;
				[$state, $write, $direction]	= $trans[1];
				$this->state	= $state;
				$output			= substr( $output, 0, $this->pointer ).$write.substr( $output, $this->pointer + 1 );
				if( $direction == "l" )
					$this->pointer--;
				else if( $direction == "r" )
					$this->pointer++;
				//  extend tape by blanks, keeping the head on its symbol
				if( $this->pointer < 0 ){
					$output			= $this->blank.$output;
					$this->pointer	= 0;
				}
				else if( $this->pointer >= strlen( $output ) )
					$output	.= $this->blank;
				$found	= TRUE;
				break;
			}
			if( $verbose )
				echo $this->showTape( $output );
			if( !$found )
				break;
		}
		$this->cleanTape( $output );
		return $output;
	}

	/**
	 *	Generates HTML Visualisation of current tape.
	 *	@access		public
	 *	@param		string		$tape			current tape
	 *	@return		string
	 */
	public function showTape( string $tape ): string
	{
		$lines	= [];
		for( $i=0; $i<strlen( $tape ); $i++ ){
			$sign = substr( $tape, $i, 1 );
			if( $i == $this->pointer )
				$lines[] = "<td style='background: #FF7F7F'>".$sign."</td>";
			else
				$lines[] = "<td>".$sign."</td>";
		}
//		return "<code>(".$this->state.") ".implode( "", $lines)."</code><br>";
		return "<tr><td>(".$this->state.")</td>".implode( "", $lines )."</tr>";
	}
}
