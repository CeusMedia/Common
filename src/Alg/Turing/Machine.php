<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Turing Machine with one tape.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Turing
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2005-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Turing;

/**
 *	Turing Machine with one tape.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Turing
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2005-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
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
	private function cleanTape( string &$tape )
	{
		while( substr( $tape, 0, 1 ) == $this->blank )
			$tape = substr( $tape, 1 );
		while( substr( $tape, -1 ) === $this->blank )
			$tape = substr( $tape, 0, -1 );
	}

	/**
	 *	Checks and extends the pseudo infinite tape.
	 *	@access		private
	 *	@param		string		$tape			current tape to be cleaned up
	 *	@param		int			$pointer		current Position on tape
	 *	@return		void
	 */
	private function extendTape( string &$tape, int $pointer )
	{
		if( $pointer < 0 )
			$tape = $this->blank.$tape;
		else if( $pointer >= strlen( $tape ) )
			$tape .= $this->blank;
	}

	/**
	 *	Returns current Sign.
	 *	@access		private
	 *	@param		string		$tape			current tape to be cleaned up
	 *	@param		int			$pointer		current Position on tape
	 *	@return		string
	 */
	private function getCurrent( string &$tape, int $pointer ): string
	{
		if( $pointer < 0 || $pointer >= strlen( $tape ) ){
			$current = $this->blank;
			$this->extendTape( $tape, $pointer );
		}
		else $current = substr( $tape, $pointer, 1 );
		return $current;
	}

	/**
	 *	Runs the Machine.
	 *	@access		public
	 *	@param		string		$input			Input to be worked
	 *	@return		string
	 */
	public function run( string $input ): string
	{
		$this->state = $this->start;
		$this->pointer = 0;
		$output		= $input;
		$_counter	= 0;
		$this->wrapTape( $output );
		while( !in_array( $this->state, $this->finals ) ){
			if( $_counter > 200 )
				break;
			$_counter++;
			$_current = $this->getCurrent( $output, $this->pointer );
			reset( $this->transition );
			foreach( $this->transition as $trans ){
				if( $trans[0] == array( $this->state, $_current ) ){
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
					$this->extendTape( $output, $this->pointer );
					$this->wrapTape( $output );
					break;
				}
			}
			echo $this->showTape( $output );
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

	/**
	 *	Adds Blanks at start and end of the tape.
	 *	@access		private
	 *	@param		string		$tape			current tape to be cleaned up
	 *	@return		void
	 */
	private function wrapTape( string &$tape )
	{
		if( substr( $tape, 0, 1 ) != $this->blank )
			$tape = $this->blank.$tape;
		if( substr( $tape, -1 ) != $this->blank )
			$tape = $tape.$this->blank;
	}
}
