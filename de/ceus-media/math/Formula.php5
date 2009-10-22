<?php
/**
 *	...
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
 *	@category		cmClasses
 *	@package		math
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		math
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class Math_Formula
{
	/**	@var		string		$name			Formular Name (default 'f') */
	protected $name	= "f";
	/**	@var		string		$expression		Formular Expression */
	protected $expression;
	/**	@var		array		$variables		List of Variables in Formula */
	protected $variables		= array();

	/**
	 *	Constuctor.
	 *	@access		public
	 *	@param		string		$expression		Formula Expression
	 *	@param		array		$variables		Array of Variables Names
	 *	@return		void
	 */
	public function __construct( $expression, $variables = array(), $name = NULL )
	{
		$this->expression = $expression;
		if( !is_array( $variables ) )
			if( is_string( $variables ) && $variables )
				$variables = array( $variables );
			else
				$variables = array();
		foreach( $variables as $variable )
		{
			if( in_array( $variable, $this->variables ) )
				throw new InvalidArgumentException( 'Variable "'.$variable.'" is already defined for Formula "'.$this->expression.'"' );
			$this->variables[]	= $variable;
		}
		if( $name )
			$this->name	= $name;
	}

	/**
	 *	Returns  Formula Expression.
	 *	@access		public
	 *	@return		string
	 */
	public function getExpression()
	{
		return $this->expression;
	}

	/**
	 *	Returns a Value of Formula Expression with an Arguments.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getValue()
	{
		$arguments	= func_get_args();
		$expression	= $this->insertValues( $arguments );
		$value		= $this->evaluateExpression( $expression, $arguments );
		return $value;
	}
	
	/**
	 *	Returns Variables Names.
	 *	@access		public
	 *	@return		array
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 *	Returns Formula Expression with Varaibles as mathematical String.
	 *	@access		public
	 *	@param		string	$name			Name of Formula
	 *	@return		string
	 */
	public function __toString()
	{
		$string	= $this->name."(".implode( ", ", $this->variables ).") = ".$this->expression;
		return $string;
	}

	/**
	 *	Resolves Formula Expression and returns Value.
	 *	@access		protected
	 *	@param		string	$exp			Formula Expression with inserted Arguments
	 *	@param		array	$variables			Array of Arguments
	 *	@return		mixed
	 */
	protected function evaluateExpression( $exp, $args )
	{
		if( false  === ( $value = @eval( $exp ) ) )
			trigger_error( "Formula '".$this->getExpression()."' is incorrect or not defined for (".implode( ", ", $args ).")", E_USER_WARNING );
		return $value;
	}

	/**
	 *	Inserts Arguments into Formula Expression and returns evalatable Code.
	 *	@access		protected
	 *	@return		string
	 */
	protected function insertValues( $args )
	{
		$variables = $this->getVariables();
		if( count( $args ) < count( $variables ) )
			trigger_error( "to less arguments, more variables used", E_USER_WARNING );
		$exp = str_replace( $variables, $args, $this->getExpression() );
		$eval_code = "return (".$exp.");";
		return $eval_code;
	}
}
?>