<?php
/**
 *	Build SQL Statement from given Statement Component.
 *	@package		mv2.core.database 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.04
 *	@version		0.5
 */
/**
 *	Build SQL Statement from given Statement Component.
 *	@package		mv2.core.database 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.04
 *	@version		0.5
 */
class Framework_Krypton_Core_Database_StatementBuilder
{
	/**	@var	array	$keys 			Array of Keys */	
	private $keys		= array();
	/**	@var	array	$tables 		Array of Tables */	
	private $tables		= array();
	/**	@var	array	$conditions 	Array of Conditions */	
	private $conditions	= array();
	/**	@var	array	$groupings		Array of Conditions */	
	private $groupings	= array();
	/**	@var	array	$orders			Array of Order Conditions */	
	private $orders		= array();
	/**	@var	array	$limit 			Array of Limit Conditions */	
	private $limit		= array();
	/**	@var	string	$prefix			Prefix of Tables */	
	private $prefix		= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	prefix		Table Prefix
	 *	@param		array	keys			Array of columns to search for
	 *	@param		array	tables		Array of Tables
	 *	@param		array	conditions	Array of Condition Pairs
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
	 *	Adds a key to search for.
	 *	@access		public
	 *	@param		string	key			Key to search for
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
	 *	@param		array	keys			Keys to search for
	 *	@return		void
	 */
	public function addKeys( $keys )
	{
		$key	= (array) $keys;
		foreach( $keys as $key )
			$this->addKey( $key );
	}
	
	/**
	 *	Adds a table to search in.
	 *	@access		public
	 *	@param		string	tablename	Table to search in
	 *	@return		void
	 */
	public function addTable( $tablename )
	{
		if( !in_array( $this->prefix.$tablename, $this->tables ) )
			$this->tables[] = $this->prefix.$tablename;	
	}

	/**
	 *	Adds tables to search in.
	 *	@access		public
	 *	@param		array	tables		Tables to search in
	 *	@return		void
	 */
	public function addTables( $tables )
	{
		$tables	= (array) $tables;
		foreach( $tables as $tablename )
			$this->addTable( $tablename );
	}
	
	/**
	 *	Adds a search condition.
	 *	@access		public
	 *	@param		string	condition		Search condition
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
	 *	@param		array	conditions	Search conditions
	 *	@return		void
	 */
	public function addConditions( $conditions )
	{
		$conditions	= (array) $conditions;
		foreach( $conditions as $condition )
			$this->addCondition( $condition );
	}
	 
	/**
	 *	Adds a search condition.
	 *	@access		public
	 *	@param		string	grouping		Search grouping
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
	 *	@param		array	groupings	Search groupings
	 *	@return		void
	 */
	public function addGroupings( $groupings )
	{
		$groupings	= (array) $groupings;
		foreach( $groupings as $grouping )
			$this->addGrouping( $grouping );
	}
	
	/**
	 *	Adds limit conditions.
	 *	@access		public
	 *	@param		string	rows		Rows to limit
	 *	@param		string	offset		Offset
	 *	@return		void
 	 */	
	public function setLimit( $rows, $offset = 0 )
	{
		$this->limit['rows']	= (int) $rows;
		$this->limit['offset']	= (int) $offset;
	}
	
	/**
	 *	Adds a sort condition.
	 *	@access		public
	 *	@param		string	column		Column to sort
	 *	@param		string	sort		Direction of order
	 *	@return		void
 	 */	
	public function addOrder( $column, $direction )
	{
		$this->orders[$column] = $direction;
	}
	
	/**
	 *	Adds sort conditions.
	 *	@access		public
	 *	@param		array	sorts		Sort conditions
	 *	@return		void
	 */
	public function addSorts( $sorts )
	{
		foreach( $sorts as $column => $direction )
			$this->addSort( $column, $direction );
	}

	public function buildStatement()
	{
		return $this->buildSelectStatement();
	}

	/**
	 *	Builds SQL Statement.
	 *	@access		public
	 *	@return		string
	 */
	public function buildSelectStatement()
	{
		$tables		= array();
		$keys		= "SELECT\n\t".implode( ",\n\t", $this->keys );
		$tables		= "\nFROM\n\t".implode( ",\n\t", $this->tables );
		$conditions	= "";
		$groupings	= "";
		$limits		= "";

		if( $this->conditions )
			$conditions	= "\nWHERE\n\t".implode( " AND\n\t", $this->conditions );
		if( $this->groupings )
			$groupings	= "\nGROUP BY\n".implode( "\n", $this->groupings );
		$sorts		= "";
		if( count( $this->orders ) )
		{
			$sorts	= array();
			foreach( $this->orders as $column => $direction )
				$sorts[] = $column." ".$direction;			
			$sorts		= "\nORDER BY\n\t".implode( ",\n\t", $sorts );
		}
		if( count( $this->limit ) && isset( $this->limit['rows'] ) )
		{
			$limits = "\nLIMIT ".$this->limit['rows'];
			if( isset( $this->limit['offset'] ) )
				$limits .= "\nOFFSET ".$this->limit['offset'];
		}
		
		$statement = $keys.$tables.$conditions.$groupings.$sorts.$limits;
		return $statement;
	}

	/**
	 *	Builds SQL Statement for counting only.
	 *	@access		public
	 *	@return		string
	 */
	public function buildCountStatement()
	{
		$tables		= array();
		$tables		= "\nFROM\n\t".implode( ",\n\t", $this->tables );
		$conditions	= "";
		$groupings	= "";
		if( $this->conditions )
			$conditions	= "\nWHERE\n\t".implode( " AND\n\t", $this->conditions );
		if( $this->groupings )
			$groupings	= "\nGROUP BY\n".implode( "\n", $this->groupings );
		$statement = "SELECT COUNT(".$this->keys[0].") as rowcount ".$tables.$conditions.$groupings;
		return $statement;
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
}
?>