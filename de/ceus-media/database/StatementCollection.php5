<?php
/**
 *	Collection of Statement Components for Statement Builder.
 *	@package		database 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.04
 *	@version		0.6
 */
/**
 *	Collection of Statement Components for Statement Builder.
 *	@package		database 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.04
 *	@version		0.6
 */
class Database_StatementCollection
{
	/**	@var	StatementBuilder	$builder		Reference to a Statement Builder Object */		
	protected $builder;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		StatementBuilder	$builder	Reference to a Statement Builder Object
	 *	@return		void
	 */
	public function __construct( $builder )
	{
		$this->builder	= $builder;
	}

	/**
	 *	Add a parameterized Component to Statement Builder.
	 *	@access		public
	 *	@param		string		$name		Name of collected Statement Component
	 *	@param		array		$data		Parameters for Statement Component
	 *	@return		void
	 */
	public function addComponent( $name, $data = array() )
	{
		if( !method_exists( $this, $name ) )
			throw new BadMethodCallException( 'Component "'.$name.'" is not defined.' );
		$array = $this->$name( $data );
		if( count( $array ) )
		{
			if( isset( $array[0] ) )
				$this->builder->addKeys( $array[0] );
			if( isset( $array[1] ) )
				$this->builder->addTables( $array[1] );
			if( isset( $array[2] ) )
				$this->builder->addConditions( $array[2] );
			if( isset( $array[3] ) )
				$this->builder->addGroupings( $array[3] );
		}
	}

	/**
	 *	Adds Column and Direction to order by.
	 *	@access		public
	 *	@param		string		$column		Column to order by	
	 *	@param		string		$direction	Direction (ASC|DESC)
	 *	@return		void
	 */
	public function addOrder( $column, $direction )
	{
		$this->builder->addOrder( $column, $direction );	
	}

	/**
	 *	Add a parameterized Component to Statement Builder.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrefix()
	{
		return $this->builder->getPrefix();
	}

	/**
	 *	Base Statement Component for Offseting and Limiting.
	 *	@access		public
	 *	@param		array		$data		Pair of Offset and Limit
	 *	@return		array
	 */
	public function Limit( $data )
	{
		$offset	= 0;
		$rows	= 10;
		if( isset( $data[0] ) && (int) $data[0] && $data[0] == abs( $data[0] ) )
			$offset	= (int) $data[0];
		if( isset( $data[1] ) && (int) $data[1] && $data[1] == abs( $data[1] ) )
			$rows	= (int) $data[1];
		$this->builder->setLimit ($rows, $offset);	
		return array();
	}

	/**
	 *	Base Statement Component for Ordering.
	 *	@access		public
	 *	@param		array		$data		Pair of Column and Direction
	 *	@return		array
	 */
	public function Order( $data )
	{
		$column		= $data[0];
		$direction	= strtoupper( $data[1] );
		$this->builder->addOrder( $column, $direction );	
		return array();
	}

	/**
	 *	Sets Offset and Limit.
	 *	@access		public
	 *	@param		array		$data		Pairs of Offset and Limit
	 *	@return		array
	 */
	public function setLimit( $offset = 0, $limit = 10 )
	{
		$offset	= abs( $offset );
		$limit	= abs( $limit );
		$this->builder->setLimit( $rows, $offset );	
		return array();
	}
}
?>
