<?php
/**
 *	Build SQL Statement from given Statement Component.
 *	@package		database 
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.2004
 *	@version		0.6
 */
/**
 *	Build SQL Statement from given Statement Component.
 *	@package		database 
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.2004
 *	@version		0.6
 */
class Database_StatementBuilder
{
	/**	@var		array		$keys 			Array of Keys */	
	protected $keys				= array();
	/**	@var		array		$tables 		Array of Tables */	
	protected $tables			= array();
	/**	@var		array		$conditions 	Array of Conditions */	
	protected $conditions		= array();
	/**	@var		array		$groupings		Array of Groupings */	
	protected $groupings		= array();
	/**	@var		array		$orders			Array of Order Conditions */	
	protected $orders			= array();
	/**	@var		array		$limits 		Array of Limit Conditions */	
	protected $limits			= array();
	/**	@var		string		$prefix			Prefix of Tables */	
	protected $prefix			= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$prefix			Table Prefix
	 *	@param		array		$keys			Array of columns to search for
	 *	@param		array		$tables			Array of Tables
	 *	@param		array		$conditions		Array of Condition Pairs
	 *	@return		void
	 */
	public function __construct( $prefix = "", $keys = array(), $tables = array(), $conditions = array(), $groupings = array() )
	{
		$this->prefix	= $prefix;
		$this->addKeys( $keys );
		$this->addTables( $tables );
		$this->addConditions( $conditions );
		$this->addGroupings( $groupings );
	}

	/**
	 *	Adds a search condition.
	 *	@access		public
	 *	@param		string		$condition		Search condition
	 *	@return		void
	 */
	public function addCondition( $condition )
	{
		if( !in_array( $condition, $this->conditions ) )
			$this->conditions[] = $condition;
	}

	/**
	 *	Adds search conditions.
	 *	@access		public
	 *	@param		array		$conditions		Search conditions
	 *	@return		void
	 */
	public function addConditions( $conditions )
	{
		if( !is_array( $conditions ) )
			throw new InvalidArgumentException( 'An Array should be given.' );
		foreach( $conditions as $condition )
			$this->addCondition( $condition );
	}
	 
	/**
	 *	Adds a search condition.
	 *	@access		public
	 *	@param		string		$grouping		Search grouping
	 *	@return		void
	 */
	public function addGrouping( $grouping )
	{
		if( !in_array( $grouping, $this->groupings ) )
			$this->groupings[] = $grouping;
	}

	/**
	 *	Adds search conditions.
	 *	@access		public
	 *	@param		array		$groupings		Search groupings
	 *	@return		void
	 */
	public function addGroupings( $groupings )
	{
		if( !is_array( $groupings ) )
			throw new InvalidArgumentException( 'An Array should be given.' );
		foreach( $groupings as $grouping )
			$this->addGrouping( $grouping );
	}
	 
	/**
	 *	Adds a key to search for.
	 *	@access		public
	 *	@param		string		$key			Key to search for
	 *	@return		void
	 */
	public function addKey( $key )
	{
		if( !in_array( $key, $this->keys ) )
			$this->keys[] = $key;
	}
	
	/**
	 *	Adds keys to search for.
	 *	@access		public
	 *	@param		array		$keys			Keys to search for
	 *	@return		void
	 */
	public function addKeys( $keys )
	{
		if( !is_array( $keys ) )
			throw new InvalidArgumentException( 'An Array should be given.' );
		foreach( $keys as $key )
			$this->addKey( $key );
	}
	
	/**
	 *	Adds a sort condition.
	 *	@access		public
	 *	@param		string		$column			Column to sort
	 *	@param		string		$sort			Direction of order
	 *	@return		void
 	 */	
	public function addOrder( $column, $direction )
	{
		$this->orders[$column] = $direction;
	}
	
	/**
	 *	Adds sort conditions.
	 *	@access		public
	 *	@param		array		$orders			Sort conditions
	 *	@return		void
	 */
	public function addOrders( $orders )
	{
		if( !is_array( $orders ) )
			throw new InvalidArgumentException( 'An Array should be given.' );
		foreach( $orders as $column => $direction )
			$this->addOrder( $column, $direction );
	}
		
	/**
	 *	Adds a table to search in.
	 *	@access		public
	 *	@param		string		$table			Table to search in
	 *	@return		void
	 */
	public function addTable( $table )
	{
		if( !in_array( $this->prefix.$table, $this->tables ) )
			$this->tables[] = $this->prefix.$table;	
	}

	/**
	 *	Adds tables to search in.
	 *	@access		public
	 *	@param		array		$tables			Tables to search in
	 *	@return		void
	 */
	public function addTables( $tables )
	{
		if( !is_array( $tables ) )
			throw new InvalidArgumentException( 'An Array should be given.' );
		$tables	= (array) $tables;
		foreach( $tables as $table )
			$this->addTable( $table );
	}

	/**
	 *	Builds SQL Statement for counting only.
	 *	@access		public
	 *	@return		string
	 */
	public function buildCountStatement()
	{
		if( !$this->keys )
			throw new Exception( 'No Columns defined.' );
		if( !$this->tables )
			throw new Exception( 'No Tables defined.' );

		$tables		= array();
		$tables		= "\nFROM\n\t".implode( ",\n\t", $this->tables );
		$conditions	= "";
		$groupings	= "";
		if( $this->conditions )
			$conditions	= "\nWHERE\n\t".implode( " AND\n\t", $this->conditions );
		if( $this->groupings )
			$groupings	= "\nGROUP BY\n\t".implode( ",\n\t", $this->groupings );
		$statement = "SELECT COUNT(".$this->keys[0].") as rowcount".$tables.$conditions.$groupings;
		return $statement;
	}
	
	/**
	 *	Builds SQL Statement.
	 *	@access		public
	 *	@return		string
	 */
	public function buildSelectStatement()
	{
		if( !$this->keys )
			throw new Exception( 'No Columns defined.' );
		if( !$this->tables )
			throw new Exception( 'No Tables defined.' );

		$tables		= array();
		$keys		= "SELECT\n\t".implode( ",\n\t", $this->keys );
		$tables		= "\nFROM\n\t".implode( ",\n\t", $this->tables );
		$conditions	= "";
		$groupings	= "";
		$limits		= "";

		if( $this->conditions )
			$conditions	= "\nWHERE\n\t".implode( " AND\n\t", $this->conditions );
		if( $this->groupings )
			$groupings	= "\nGROUP BY\n\t".implode( ",\n\t", $this->groupings );
		$orders		= "";
		if( count( $this->orders ) )
		{
			$orders	= array();
			foreach( $this->orders as $column => $direction )
				$orders[] = $column." ".$direction;			
			$orders		= "\nORDER BY\n\t".implode( ",\n\t", $orders );
		}
		if( count( $this->limits ) && isset( $this->limits['rows'] ) )
		{
			$limits = "\nLIMIT ".$this->limits['rows'];
			if( isset( $this->limits['offset'] ) )
				$limits .= "\nOFFSET ".$this->limits['offset'];
		}
		
		$statement = $keys.$tables.$conditions.$groupings.$orders.$limits;
		return $statement;
	}

	/**
	 *	Alias for buildSelectStatement.
	 *	@access		public
	 *	@return 	string
	 */
	public function buildStatement()
	{
		return $this->buildSelectStatement();
	}

	/**
	 *	Returns Table Prefix.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 *	Adds Limit Conditions.
	 *	@access		public
	 *	@param		int			$rowCount		Rows to limit
	 *	@return		void
 	 */	
	public function setLimit( $rowCount )
	{
		$this->limits['rows']	= (int) $rowCount;
	}
	
	/**
	 *	Adds Offset Condition.
	 *	@access		public
	 *	@param		int			$offset			Offset to start at
	 *	@return		void
 	 */	
	public function setOffset( $offset )
	{
		$this->limits['offset']	= (int) $offset;
	}
}
?>