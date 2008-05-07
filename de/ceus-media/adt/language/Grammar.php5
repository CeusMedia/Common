<?php
import ("de.ceus-media.adt.set.SetOperation");
/**
 *	Grammar for formal Languages
 *	@package		adt.language
 *	@uses			SetOperation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Grammar for formal Languages
 *	@package		adt.language
 *	@uses			SetOperation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class ADT_Language_Grammar
{
	/**	@var		Set			$variables		Alphabet of variable Symbols */
	protected $variables;
	/**	@var		Set			$terminals		Alphabet of terminal Symbols */
	protected $terminals;	
	/**	@var		array		$rules			Array of Pairs with production rules */
	protected $rules;	
	/**	@var		string		$start			Start symbol */
	protected $start;	
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		ADT_Language_Alphabet	$variables		Alphabet of variable Symbols
	 *	@param		ADT_Language_Alphabet	$terminals		Alphabet of terminal Symbols
	 *	@param		array					$variables		Array of Pairs with production rules
	 *	@param		string					$start			Start symbol
	 *	@return		void
	 */
	public function __construct( $variables, $terminals, $rules, $start = FALSE )
	{
		$this->variables	= $variables;
		$this->terminals	= $terminals;
		$this->rules		= $rules;
		if( $start )
			$this->start	= $start;
			
		$so = new SetOperation();
		$inter = $so->intersect( $variables, $terminals );
		if( !$inter->isEmpty() )
			throw new Exception( 'Intersection between Variables and Terminals must be empty.' );
	}
	
	/**
	 *	Returns an array of Pairs with production rules.
	 *	@access		public
	 *	@return		array
	 */
	public function getRules()
	{
		return $this->rules;
	}
	
	/**
	 *	Returns the Chomsky-Type of Grammar.
	 *	@access		public
	 *	@return		int
	 */
	public function getType()
	{
		$types = array_reverse( array( 0, 1, 2, 3 ) );
		foreach( $types as $type )
			if( $this->isType( $type ) )
				return $type;
	}
	
	/**
	 *	Indicates whether Grammar is of a Chomsky-Type.
	 *	@access		public
	 *	@param		int		$type		Chomsky-Type (0-3)
	 *	@return		bool
	 */
	public function isType( $type )
	{
		$return = FALSE;
		switch( $type )
		{
			case 0:												//  Phasenstrukturgrammatik (aufzählbar, semi-entscheidbar mit Turing-Maschine)
				return TRUE;
			case 1:												//  kontextsensitive Grammatik (endscheidbar
				if( $this->isType( 0 ) )
				{
					$return = TRUE;
					foreach( $rules = $this->getRules() as $rule )
						if( strlen( $rule->getKey() ) > strlen( $rule->getValue() ) )
							$return = FALSE;
				}
				break;
			case 2:												//  kontextfreie Grammatik
				if( $this->isType( 0 ) && $this->isType( 1 ) )
				{
					$return = TRUE;
					foreach( $this->getRules() as $rule )
						if( !$this->variables->has( $rule->getKey() ) )
							$return = FALSE;
				}
				break;
			case 3:												//  reguläre Grammatik
				if( $this->isType( 0 ) && $this->isType( 1 ) && $this->isType( 2 ) )
				{
					$return = TRUE;
					$so = new SetOperation();
					$cross	= $so->produceCross( $this->terminals, $this->variables );
					$rules	= $this->getRules();
					foreach( $rules as $rule )
						if( !$this->terminals->has( $rule->getValue() ) )
							if( !$cross->has( $rule->getValue() ) )
								$return = FALSE;
				}
				break;
		}
		return $return;
	}
}
?>