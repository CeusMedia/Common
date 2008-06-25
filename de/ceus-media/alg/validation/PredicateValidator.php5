<?php
/**
 *	Validator for Predicates on Strings.
 *	@package		alg.validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2007
 *	@version		0.6
 */
/**
 *	Validator for Predicates on Strings.
 *	@package		alg.validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2007
 *	@version		0.6
 */
class Alg_Validation_PredicateValidator
{
	/**	@var		Object		Predicate Class Instance */
	protected $validator;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$predicateClassName		Class Name of Predicate Class
	 *	@return		void
	 */
	public function __construct( $predicateClassName = "Alg_Validation_Predicates" )
	{
		$this->validator	= new $predicateClassName();
	}

	/**
	 *	Indicates whether a String is of a specific Character Class.
	 *	@access		public
	 *	@param		string		$value		String to be checked
	 *	@param		string		$class		Key of Character Class
	 *	@return		bool
	 */
	public function isClass( $value, $class )
	{
		$method	= "is".ucFirst( $class );
		if( !method_exists( $this->validator, $method ) )
			throw new BadMethodCallException( 'Predicate "'.$method.'" is not defined.' );
		return $this->validator->$method( $value, $method );
	}
	
	/**
	 *	Indicates whether a String validates against a Predicate.
	 *	@access		public
	 *	@param		string		$value		String to be checked
	 *	@param		string		$predicate	Method Name of Predicate
	 *	@param		string		$argument	Argument for Predicate
	 *	@return		bool
	 */
	public function validate( $value, $predicate, $argument = NULL )
	{
		if( !method_exists( $this->validator, $predicate ) )
			throw new BadMethodCallException( 'Predicate "'.$predicate.'" is not defined.' );
		try
		{
			return $this->validator->$predicate( $value, $argument );
		}
		catch( InvalidArgumentException $e )
		{
			return false;
		}
		catch( Exception $e )
		{
			throw $e;
		}
	}
}
?>