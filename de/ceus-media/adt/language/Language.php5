<?php
/**
 *	Formal Language Implementation.
 *	@package		adt.language
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Formal Language Implementation.
 *	@package		adt.language
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class ADT_Language_Language
{
	/**	@var		ADT_Language_Grammar		$grammar		Grammar of Language */
	protected $grammar;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Grammar		$grammar		Grammar of Language
	 *	@return		void
	 */
	public function __construct( $grammar )
	{
		$this->grammar	= $grammar;
	}
	
	/**
	 *	Returns the Chomsky-Type of Language.
	 *	@access		public
	 *	@return		int
	 */
	public function getType()
	{
		return $this->grammar->getType();
	}
	
	/**
	 *	Indicates whether Language is of a Chomsky-Type.
	 *	@access		public
	 *	@param		int			$type			Chomsky-Type (0-3)
	 *	@return		bool
	 */
	public function isType( $type )
	{
		return $this->grammar->isType( $type );
	}

	/**
	 *	Returns an array of rules used to evolute of a word to another word if possible.
	 *	@access		public
	 *	@param		string	$wordStart		Start word to be evolved
	 *	@param		string	$wordEnd		End word to to evolved to
	 *	@param		int		$maxDepth		Maximum depth of recursion
	 *	@param		array	$usedRules		Array of rules used before
	 *	@param		int		$depth			Current depth of recursion
	 *	@return		array
	 */
	public function evolve( $wordStart, $wordEnd, $maxDepth = 4, $usedRules = array(), $depth = 0)
	{
		if( !$this->grammar->isType( 1 ) )
			throw new Exception( 'Grammar is not determinable and can not be evolved. Grammar must be at least Chomsky Type 1.' );

		$depth++;
		if( $depth > $maxDepth )
			return;
		$rules = $this->grammar->getRules();
		foreach( $rules as $rule )
		{
			$ruleVar	= $rule->getKey();
			$ruleTerm	= $rule->getValue();
//			echo "<br/>d: ".$depth." | w: ".$wordStart."  |  ".$ruleVar." => ".$ruleTerm;
			$ruleList	= $usedRules;
			$ruleList[]	= array( $ruleVar => $ruleTerm );
			if( FALSE === strpos( $wordStart, $ruleVar ) )
				continue;
			$first	= substr( $wordStart, 0, strpos( $wordStart, $ruleVar ) );
			$last	= substr( $wordStart, strpos( $wordStart, $ruleVar ) + strlen( $ruleVar ) );
			$maybe	= $first.$ruleTerm.$last;
			if( $maybe == $wordEnd )
				return $ruleList;
			else
			{
				$way = $this->evolve( $maybe, $wordEnd, $maxDepth, $ruleList, $depth );
				if( count( $way ) )
					return $way;
			}
		}
	}

	/**
	 *	Indicates whether a word is evolvable to another word.
	 *	@access		public
	 *	@param		string	$wordStart		Start word to be evolved
	 *	@param		string	$wordEnd		End word to to evolved to
	 *	@param		int		$maxDepth		Maximum depth of recursion
	 *	@return		bool
	 */
	public function isEvolvable( $wordStart, $wordEnd, $maxDepth = 4 )
	{
		$way	= $this->evolve( $wordStart, $wordEnd, $maxDepth );
		$count	= count( $way );
		return $count > 0;
	}

}
?>