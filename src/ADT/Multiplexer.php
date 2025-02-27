<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Multiplexer.
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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

use RangeException;

/**
 *	Multiplexer.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Multiplexer
{
	/**	@var		int			$type			Type (1,2,4) */
	protected int $type;

	/**	@var		array		$controls		Controls */
	protected array $controls;

	/**	@var		array		$inputs			Inputs */
	protected array $inputs;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$type			Type (1,2,4)
	 *	@return		void
	 */
	public function __construct( int $type = 1 )
	{
		$this->type = $type;
		$this->setControls();
		$this->setInputs();
	}

	/**
	 *	Returns Controls.
	 *	@access		public
	 *	@return		array
	 */
	public function getControls(): array
	{
		return $this->controls;
	}

	/**
	 *	Returns Inputs.
	 *	@access		public
	 *	@return		array
	 */
	public function getInputs(): array
	{
		return $this->inputs;
	}

	/**
	 *	Returns Type of Multiplexer.
	 *	@access		public
	 *	@return		int
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 *	Runs Multiplexer.
	 *	@access		public
	 *	@return		mixed
	 */
	public function proceed()
	{
		switch( $this->getType() ){
			case 1:
				return $this->controls[0] ? $this->inputs[1] : $this->inputs[0];
			case 2:
				$mux = new Multiplexer();
				$mux->setControls( $this->controls[0] );
				$mux->setInputs( $this->inputs[0], $this->inputs[1] );
				$input0 = $mux->proceed();
				$mux->setInputs( $this->inputs[2], $this->inputs[3] );
				$input1 = $mux->proceed();
				$mux->setControls( $this->controls[1] );
				$mux->setInputs( $input0, $input1 );
				return $mux->proceed();
			case 4:
				$mux2 = new Multiplexer( 2 );
				$mux2->setControls( $this->controls[0], $this->controls[1] );
				$mux2->setInputs( $this->inputs[0], $this->inputs[1], $this->inputs[2], $this->inputs[3] );
				$input0 = $mux2->proceed();
				$mux2->setInputs( $this->inputs[4], $this->inputs[5], $this->inputs[6], $this->inputs[7] );
				$input1 = $mux2->proceed();
				$mux2->setInputs( $this->inputs[8], $this->inputs[9], $this->inputs[10], $this->inputs[11] );
				$input2 = $mux2->proceed();
				$mux2->setInputs( $this->inputs[12], $this->inputs[13], $this->inputs[14], $this->inputs[15] );
				$input3 = $mux2->proceed();
				$mux2->setControls( $this->controls[2], $this->controls[3] );
				$mux2->setInputs( $input0, $input1, $input2, $input3 );
				return $mux2->proceed();
			default:
				throw new RangeException( 'Invalid type' );
		}
	}

	/**
	 *	Sets Controls from Method Arguments.
	 *	@access		public
	 *	@return		void
	 */
	public function setControls()
	{
		$this->controls	= [];
		$args	= func_get_args();
		for( $i = 0; $i < $this->type; $i ++ )
			if( isset( $args[$i] ) )
				$this->controls[$i]	= $args[$i];
	}

	/**
	 *	Sets Inputs from Method Arguments.
	 *	@access		public
	 *	@return		void
	 */
	public function setInputs()
	{
		$this->inputs	= [];
		$len	= 2 ** $this->type;
		$args	= func_get_args();
		for( $i = 0; $i < $len; $i ++ )
			if( isset( $args[$i] ) )
				$this->inputs[$i] = $args[$i];
	}
}
