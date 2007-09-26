<?php
/**
 *	Multiplexer.
 *	@package		adt
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			23.08.2005
 *	@version		0.1
 */
/**
 *	Multiplexer.
 *	@package		adt
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			23.08.2005
 *	@version		0.1
 */
class Multiplexer
{
	/**	@var	int		_type		Type (1,2,4) */
	var $_type;
	/**	@var	int		_type		Type (1,2,4) */
	var $_controls	= array();
	/**	@var	int		_type		Type (1,2,4) */
	var $_inputs	= array();

	/**
	 *	Contructor.
	 *	@access		public
	 *	@param		int		type			Type (1,2,4)
	 *	@return		void
	 */
	public function __construct( $type = 1 )
	{
		$this->_type = $type;
		$this->setControls();
		$this->setInputs();
	}

	/**
	 *	Sets Controls from Method Arguments.
	 *	@access		public
	 *	@return		void
	 */
	function setControls()
	{
		$this->_controls	= array();
		$args	= func_get_args();
		for( $i = 0; $i < $this->_type; $i ++ )
			if( isset( $args[$i] ) )
				$this->_controls[$i]	= $args[$i];
	}

	/**
	 *	Sets Inputs from Method Arguments.
	 *	@access		public
	 *	@return		void
	 */
	function setInputs()
	{
		$this->_inputs	= array();
		$len		= pow( 2, $this->_type );
		$args	= func_get_args();
		for( $i = 0; $i < $len; $i ++ )
			if( isset( $args[$i] ) )
				$this->_inputs[$i] = $args[$i];
	}
	
	/**
	 *	Returns Controls.
	 *	@access		public
	 *	@return		array
	 */
	function getControls()
	{
		return $this->_controls;
	}
	
	/**
	 *	Returns Inputs.
	 *	@access		public
	 *	@return		array
	 */
	function getInputs()
	{
		return $this->_inputs;
	}
	
	/**
	 *	Returns Type of Multiplexer.
	 *	@access		public
	 *	@return		int
	 */
	function getType()
	{
		return $this->_type;
	}
	
	/**
	 *	Runs Multiplexer.
	 *	@access		public
	 *	@return		mixed
	 */
	function proceed()
	{
		if( $this->getType() == 1 )
		{
			$output = $this->_controls[0] ? $this->_inputs[1] : $this->_inputs[0];
		}
		else if( $this->getType() == 2 )
		{
			$mux = new Multiplexer();
			$mux->setControls( $this->_controls[0] );
			$mux->setInputs( $this->_inputs[0], $this->_inputs[1] );
			$input0 = $mux->proceed();
			$mux->setInputs( $this->_inputs[2], $this->_inputs[3] );
			$input1 = $mux->proceed();
			$mux->setControls( $this->_controls[1] );
			$mux->setInputs( $input0, $input1 );
			$output = $mux->proceed();
		}
		else if( $this->getType() == 4)
		{
			$mux2 = new Multiplexer( 2 );
			$mux2->setControls( $this->_controls[0], $this->_controls[1] );
			$mux2->setInputs( $this->_inputs[0], $this->_inputs[1], $this->_inputs[2], $this->_inputs[3] );
			$input0 = $mux2->proceed();
			$mux2->setInputs( $this->_inputs[4], $this->_inputs[5], $this->_inputs[6], $this->_inputs[7] );
			$input1 = $mux2->proceed();
			$mux2->setInputs( $this->_inputs[8], $this->_inputs[9], $this->_inputs[10], $this->_inputs[11] );
			$input2 = $mux2->proceed();
			$mux2->setInputs( $this->_inputs[12], $this->_inputs[13], $this->_inputs[14], $this->_inputs[15] );
			$input3 = $mux2->proceed();
			$mux2->setControls( $this->_controls[2], $this->_controls[3] );
			$mux2->setInputs( $input0, $input1, $input2, $input3 );
			$output = $mux2->proceed();
		}
		return $output;
	}
}
?>