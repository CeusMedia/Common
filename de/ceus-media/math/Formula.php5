<?php
/**
 *	@package		math
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package		math
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo			finish Documentation
 */
class Formula
{
	/**
	 *	Constuctor.
	 *	@access		public
	 *	@param		string	$expression		Formula Expression
	 *	@param		array	$vars			Array of Variables Names
	 *	@return		void
	 */
	public function __construct( $expression, $vars = array() )
	{
		$this->_setExpression( $expression );
		$this->_setVars( $vars );
	}

	/**
	 *	Returns  Formula Expression.
	 *	@access		public
	 *	@return		string
	 */
	function getExpression()
	{
		return $this->_expression;
	}

	/**
	 *	Returns a Value of Formula Expression with an Arguments.
	 *	@access		public
	 *	@return		mixed
	 */
	function getValue()
	{
		$args	= func_get_args();
		$exp	= $this->_insertValues( $args );
		$value	= $this->_evaluateExpression( $exp, $args );
		return	$value;
	}
	
	/**
	 *	Returns Variables Names.
	 *	@access		public
	 *	@return		array
	 */
	function getVars()
	{
		return $this->_vars;
	}

	/**
	 *	Returns Formula Expression with Varaibles as mathematical String.
	 *	@access		public
	 *	@param		string	$name			Name of Formula
	 *	@return		string
	 */
	function toString( $name = "f" )
	{
		$string = $name."(".implode( ", ", $this->getVars() ).") = ".$this->getExpression();
		return $string;
	}

	//  --  PRIVATE METHODS  --  //	
	/**
	 *	Resolves Formula Expression and returns Value.
	 *	@access		private
	 *	@param		string	$exp			Formula Expression with inserted Arguments
	 *	@param		array	$vars			Array of Arguments
	 *	@return		mixed
	 */
	function _evaluateExpression( $exp, $args )
	{
		if( false  === ( $value = @eval( $exp ) ) )
			trigger_error( "Formula '".$this->getExpression()."' is incorrect or not defined for (".implode( ", ", $args ).")", E_USER_WARNING );
		return $value;
	}

	/**
	 *	Inserts Arguments into Formula Expression and returns evalatable Code.
	 *	@access		private
	 *	@return		string
	 */
	function _insertValues( $args )
	{
		$vars = $this->getVars();
		if( count( $args ) < count( $vars ) )
			trigger_error( "to less arguments, more variables used", E_USER_WARNING );
		$exp = str_replace( $vars, $args, $this->getExpression() );
		$eval_code = "return (".$exp.");";
		return $eval_code;
	}

	/**
	 *	Sets Formula Expression.
	 *	@access		private
	 *	@param		string	$expression		Formula Expression
	 *	@return		void
	 */
	function _setExpression( $expression )
	{
		$this->_expression = $expression;
	}


	function setVar( $var, $overwrite = false )
	{
		if( in_array( $var, $this->_vars ) )
		{
			if( !$overwrite )
				trigger_error( "Variable '".$var."' is already defined for Formula '".$this->getExpression()."'", E_USER_ERROR );
		}
		else
			$this->_vars[]	= $var;
	}
	

	/**
	 *	Sets Variables Names.
	 *	@access		private
	 *	@param		array	$vars			Array of Variables Names
	 *	@return		void
	 */
	function _setVars( $vars )
	{
		if( !is_array ($vars ) )
			if( is_string( $vars ) && $vars )
				$vars = array( $vars );
			else
				$vars = array();
		$this->_vars	= $vars;
	}
}
?>