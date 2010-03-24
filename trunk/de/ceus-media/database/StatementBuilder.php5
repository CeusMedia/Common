<?php
/**
 *	Build SQL Statement from given Statement Component.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		database 
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.11.2004
 *	@version		$Id$
 */
/**
 *	Build SQL Statement from given Statement Component.
 *	@category		cmClasses
 *	@package		database 
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.11.2004
 *	@version		$Id$
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
	/**	@var		array		$havings		Array of Havings */	
	protected $havings			= array();
	/**	@var		array		$orders			Array of Order Conditions */	
	protected $orders			= array();
	/**	@var		array		$limit 			Limit Value */	
	protected $limit			= 0;
	/**	@var		array		$string 		Offset Value */	
	protected $offset			= 0;
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
	 *	Adds a Where Condition.
	 *	@access		public
	 *	@param		string		$condition		Where Condition
	 *	@return		void
	 */
	public function addCondition( $condition )
	{
		if( is_array( $condition ) )
			return $this->addConditions( $condition );
		if( in_array( $condition, $this->conditions ) )
			return FALSE;
		$this->conditions[] = $condition;
		return TRUE;
	}

	/**
	 *	Adds Where Conditions.
	 *	@access		public
	 *	@param		array		$conditions		Where Conditions
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
	 *	Adds a Group Condition.
	 *	@access		public
	 *	@param		string		$grouping		Group Condition
	 *	@return		void
	 */
	public function addGrouping( $grouping )
	{
		if( is_array( $grouping ) )
			return $this->addGroupings( $grouping );
		if( in_array( $grouping, $this->groupings ) )
			return FALSE;
		$this->groupings[] = $grouping;
		return TRUE;
	}

	/**
	 *	Adds Group Conditions.
	 *	@access		public
	 *	@param		array		$groupings		Group Conditions
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
	 *	Adds a Having condition.
	 *	@access		public
	 *	@param		string		$having			Having Condition
	 *	@return		void
	 */
	public function addHaving( $having )
	{
		if( is_array( $having ) )
			return $this->addHavings( $having );
		if( in_array( $having, $this->havings ) )
			return FALSE;
		$this->havings[] = $having;
		return TRUE;
	}

	/**
	 *	Adds Havings Conditions.
	 *	@access		public
	 *	@param		array		$havings		Having Conditions
	 *	@return		void
	 */
	public function addHavings( $havings )
	{
		if( !is_array( $havings ) )
			throw new InvalidArgumentException( 'An Array should be given.' );
		foreach( $havings as $having )
			$this->addHaving( $havings );
	}
	 
	/**
	 *	Adds a Key to search for.
	 *	@access		public
	 *	@param		string		$key			Key to search for
	 *	@return		void
	 */
	public function addKey( $key )
	{
		if( is_array( $key ) )
			return $this->addKeys( $key );
		if( in_array( $key, $this->keys ) )
			return FALSE;
		$this->keys[] = $key;
		return TRUE;
	}
	
	/**
	 *	Adds Keys to search for.
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
		if( is_array( $table ) )
			return $this->addTables( $table );
		if( in_array( $this->prefix.$table, $this->tables ) )
			return FALSE;
		$this->tables[] = $this->prefix.$table;	
		return TRUE;
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
		$havings	= "";
		if( $this->conditions )
			$conditions	= "\nWHERE\n\t".implode( " AND\n\t", $this->conditions );
		if( $this->groupings )
			$groupings	= "\nGROUP BY\n\t".implode( ",\n\t", $this->groupings );
		if( $this->havings )
			$havings	= "\nHAVING\n\t".implode( ",\n\t", $this->havings );
		$statement = "SELECT COUNT(".$this->keys[0].") as rowcount".$tables.$conditions.$groupings.$havings;
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
		$havings	= "";
		$limit		= "";

		if( $this->conditions )
			$conditions	= "\nWHERE\n\t".implode( " AND\n\t", $this->conditions );
		if( $this->groupings )
			$groupings	= "\nGROUP BY\n\t".implode( ",\n\t", $this->groupings );
		if( $this->havings )
			$havings	= "\nHAVING\n\t".implode( ",\n\t", $this->havings );
		$orders		= "";
		if( count( $this->orders ) )
		{
			$orders	= array();
			foreach( $this->orders as $column => $direction )
				$orders[] = $column." ".$direction;			
			$orders		= "\nORDER BY\n\t".implode( ",\n\t", $orders );
		}
		if( $this->limit )
		{
			$limit = "\nLIMIT ".$this->limit;
			if( $this->offset )
				$limit .= "\nOFFSET ".$this->offset;
		}		
		$statement = $keys.$tables.$conditions.$groupings.$havings.$orders.$limit;
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
		if( $rowCount > 0 )
			$this->limit	= (int) $rowCount;
	}
	
	/**
	 *	Adds Offset Condition.
	 *	@access		public
	 *	@param		int			$offset			Offset to start at
	 *	@return		void
 	 */	
	public function setOffset( $offset )
	{
		$this->offset	= (int) $offset;
	}
}
?>