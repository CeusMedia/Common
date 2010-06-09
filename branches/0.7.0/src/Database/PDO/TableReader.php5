<?php
/**
 *	Table with column definition and indices.
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
 *	@package		Database.PDO
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Table with column definition and indices.
 *	@category		cmClasses
 *	@package		Database.PDO
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Database_PDO_TableReader
{
	/**	@var	BaseConnection	$dbc				Database connection resource object */
	protected $dbc;
	/**	@var	array			$columns			List of table columns */
	protected $columns			= array();
	/**	@var	array			$indices			List of indices of table */
	protected $indices			= array();
	/**	@var	string			$focusedIndices		List of focused indices */
	protected $focusedIndices	= array();
	/**	@var	string			$primaryKey			Primary key of this table */
	protected $primaryKey;
	/**	@var	string			$tableName			Name of this table */
	protected $tableName;
	/**	@var	int				$fetchMode			Name of this table */
	protected $fetchMode;
	/**	@var	int				$defaultFetchMode	Default fetch mode, can be set statically */
	public static $defaultFetchMode	= PDO::FETCH_ASSOC;

	/**
	 *	Constructor.
	 *
	 *	@access		public
	 *	@param		PDO			$dbc			Database connection resource object
	 *	@param		string		$tableName		Table name
	 *	@param		array		$columns		List of table columns
	 *	@param		string		$primaryKey		Name of the primary key of this table
	 *	@param		int			$focus			Focused primary key on start up
	 *	@return		void
	 */
	public function __construct( &$dbc, $tableName, $columns, $primaryKey, $focus = NULL )
	{
		$this->setDbConnection( $dbc );
		$this->setTableName( $tableName );
		$this->setColumns( $columns );
		$this->setPrimaryKey( $primaryKey );
		$this->defocus();
		if( $focus )
			$this->focusPrimary( $focus );
	}

	/**
	 *	Returns count of all entries of this table covered by conditions.
	 *	@access		public
	 *	@param		array		$conditions		Array of condition strings
	 *	@return		int
	 */
	public function count( $conditions = array() )
	{
		$conditions	= $this->getConditionQuery( $conditions, FALSE, TRUE );
		$conditions	= $conditions ? ' WHERE '.$conditions : '';
		$query	= 'SELECT COUNT(`'.$this->primaryKey.'`) as count FROM '.$this->getTableName().$conditions;
		$result	= $this->dbc->query( $query );
		$count	= $result->fetch( $this->getFetchMode() );
		return $count['count'];
	}

	/**
	 *	Deleting current focus on indices (including primary key).
	 *	@access		public
	 *	@param		bool		$primaryOnly		Flag: delete focus on primary key only
	 *	@return		bool
	 */
	public function defocus( $primaryOnly = FALSE )
	{
		if( !$this->focusedIndices )
			return FALSE;
		if( $primaryOnly )
		{
			if( !array_key_exists( $this->primaryKey, $this->focusedIndices ) )
				return FALSE;
			unset( $this->focusedIndices[$this->primaryKey] );
			return TRUE;
		}
		$this->focusedIndices = array();
		return TRUE;
	}

	/**
	 *	Returns all entries of this table in an array.
	 *	@access		public
	 *	@param		array		$columns		Array of table columns
	 *	@param		array		$conditions		Array of condition pairs additional to focuses indices
	 *	@param		array		$orders			Array of order relations
	 *	@param		array		$limit			Array of limit conditions
	 *	@return		array
	 */
	public function find( $columns = array(), $conditions = array(), $orders = array(), $limit = array() )
	{
		$this->validateColumns( $columns );
			
		$conditions	= $this->getConditionQuery( $conditions, FALSE, FALSE );		
		$conditions = $conditions ? ' WHERE '.$conditions : '';
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$query		= 'SELECT '.implode( ', ', $columns ).' FROM '.$this->getTableName().$conditions.$orders.$limit;
		$resultSet	= $this->dbc->query( $query );

		return $resultSet->fetchAll( $this->getFetchMode() );
	}
	
	public function findWhereIn( $columns = array(), $column, $values, $orders = array(), $limit = array() )
	{
		$this->validateColumns( $columns );

		if( $column != $this->getPrimaryKey() && !in_array( $column, $this->getIndices() ) )
			throw new InvalidArgumentException( 'Field of WHERE IN-statement must be an index' );

		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		for( $i=0; $i<count( $values ); $i++ )
			$values[$i]	= $this->secureValue( $values[$i] );

		$query		= 'SELECT '.implode( ', ', $columns ).' FROM '.$this->getTableName().' WHERE '.$column.' IN ('.implode( ', ', $values ).') '.$orders.$limit;
		$resultSet	= $this->dbc->query( $query );

		return $resultSet->fetchAll( $this->getFetchMode() );
	}

	public function findWhereInAnd( $columns = array(), $column, $values, $conditions = array(), $orders = array(), $limit = array() )
	{
		$this->validateColumns( $columns );

		if( $column != $this->getPrimaryKey() && !in_array( $column, $this->getIndices() ) )
			throw new InvalidArgumentException( 'Field of WHERE IN-statement must be an index' );

		$conditions	= $this->getConditionQuery( $conditions, FALSE, FALSE );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		for( $i=0; $i<count( $values ); $i++ )
			$values[$i]	= $this->secureValue( $values[$i] );
		
		if( $conditions )
			$conditions	.= ' AND ';
		$query		= 'SELECT '.implode( ', ', $columns ).' FROM '.$this->getTableName().' WHERE '.$conditions.$column.' IN ('.implode( ', ', $values ).') '.$orders.$limit;
		$resultSet	= $this->dbc->query( $query );

		return $resultSet->fetchAll( $this->getFetchMode() );
	}

	/**
	 *	Setting focus on an index.
	 *	@access		public
	 *	@param		string		$column			Index column name
	 *	@param		int			$value			Index to focus on
	 *	@return		void
	 */
	public function focusIndex( $column, $value )
	{
		if( !in_array( $column, $this->indices ) && $column != $this->primaryKey )				//  check column name
			throw new InvalidArgumentException( 'Column "'.$column.'" is neither an index nor primary key and cannot be focused' );
		$this->focusedIndices[$column] = $value;													//  set Focus
	}

	/**
	 *	Setting focus on a primary key ID.
	 *	@access		public
	 *	@param		int			$id				Primary key ID to focus on
	 *	@param		bool		$clearIndices	Flag: clear all previously focuses indices
	 *	@return		void
	 */
	public function focusPrimary( $id, $clearIndices = TRUE )
	{
		if( $clearIndices )
			$this->focusedIndices	= array();
		$this->focusedIndices[$this->primaryKey] = $id;
	}

	/**
	 *	Returns data of focused keys.
	 *	@access		public
	 *	@param		bool	$first		Extract first entry of result
	 *	@param		array	$orders		Associative array of orders
	 *	@param		array	$limit		Array of offset and limit
	 *	@return		array
	 */
	public function get( $first = TRUE, $orders = array(), $limit = array() )
	{
		$this->validateFocus();
		$data = array();
		$conditions	= $this->getConditionQuery( array() );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$query = 'SELECT * FROM '.$this->getTableName().' WHERE '.$conditions.$orders.$limit;

		$resultSet	= $this->dbc->query( $query );
		$resultList	= $resultSet->fetchAll( $this->getFetchMode() );

		if( count( $resultList ) && $first )
			return $resultList[0];
		return $resultList;
	}

	/**
	 *	Returns a list of all table columns.
	 *	@access		public
	 *	@return		array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 *	Builds and returns WHERE statement component.
	 *	@access		protected
	 *	@param		array		$conditions		Array of conditions
	 *	@param		bool		$usePrimary		Flag: use focused primary key
	 *	@param		bool		$useIndices		Flag: use focused indices
	 *	@return		string
	 */
	protected function getConditionQuery( $conditions, $usePrimary = TRUE, $useIndices = TRUE )
	{
		$new = array();
		foreach( $this->columns as $column )														//  iterate all columns
			if( isset( $conditions[$column] ) )														//  if condition given
				$new[$column] = $conditions[$column];												//  note condition pair

		if( $usePrimary && $this->isFocused( $this->primaryKey ) )									//  if using primary key & is focused primary
		{
			if( !array_key_exists( $this->primaryKey, $new ) )										//  if primary key is not already in conditions
				$new = $this->getFocus();															//  note primary key pair
		}
		if( $useIndices && count( $this->focusedIndices ) )											//  if using indices
		{
			foreach( $this->focusedIndices as $index => $value )									//  iterate focused indices
				if( $index != $this->primaryKey )													//  skip primary key
					if( !array_key_exists( $index, $new ) )											//  if index column is not already in conditions
						$new[$index] = $value;														//  note index pair
		}

		$conditions = array();
		
		foreach( $new as $column => $value )															//  iterate all noted Pairs
		{
			if( is_array( $value ) )
			{
				foreach( $value as $nr => $part )
					$value[$nr]	= $this->realizeConditionQueryPart( $column, $part );
				$part	= '('.implode( ' OR ', $value ).')';
			}
			else
				$part	= $this->realizeConditionQueryPart( $column, $value );
			$conditions[]	= $part;

		}
		$conditions = implode( ' AND ', $conditions );												//  combine Conditions with AND
		return $conditions;
	}

	/**
	 *	Returns reference the database connection.
	 *	@access		public
	 *	@return		Object
	 */
	public function & getDBConnection()
	{
		return $this->dbc;
	}

	/**
	 *	Returns set fetch mode.
	 *	@access		public
	 *	@return		int			$fetchMode		Currently set fetch mode
	 */
	protected function getFetchMode()
	{
		return $this->fetchMode ? $this->fetchMode : self::$defaultFetchMode;
	}

	/**
	 *	Returns current primary focus or index focuses.
	 *	@access		public
	 *	@return		array
	 */
	public function getFocus()
	{
		return $this->focusedIndices;
	}

	/**
	 *	Returns all Indices of this Table.
	 *	@access		public
	 *	@return		array
	 */
	public function getIndices()
	{
		return $this->indices;
	}

	/**
	 *	Builds and returns ORDER BY Statement Component.
	 *	@access		protected
	 *	@param		array		$limit			Array of Offset and Limit
	 *	@return		string
	 */
	protected function getLimitCondition( $limit = array() )
	{
		$condition	= '';
		if( is_array( $limit ) && count( $limit ) == 2 ) 
			$condition = ' LIMIT '.$limit[0].', '.$limit[1];
		return $condition;
	}
	
	/**
	 *	Builds and returns ORDER BY Statement Component.
	 *	@access		protected
	 *	@param		array		$orders			Associative Array with Orders
	 *	@return		string
	 */
	protected function getOrderCondition( $orders = array() )
	{
		$order	= '';
		if( is_array( $orders ) && count( $orders ) )
		{
			$list	= array();
			foreach( $orders as $column => $direction )
				$list[] = $column.' '.strtoupper( $direction );
			$order	= ' ORDER BY '.implode( ', ', $list );
		}
		return $order;
	}

	/**
	 *	Returns the name of the primary key column.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 *	Returns the name of the table.
	 *	@access		public
	 *	@return		string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 *	Indicates wheter the focus on a index (including primary key) is set.
	 *	@access		public
	 *	@return		string
	 */
	public function isFocused( $index = NULL )
	{
		if( !count( $this->focusedIndices ) )
			return FALSE;
		if( $index && !array_key_exists( $index, $this->focusedIndices ) )
			return FALSE;
		return TRUE;
	}

	protected function realizeConditionQueryPart( $column, $value )
	{
		$pattern	= '/^(<=|>=|<|>|!=)(.+)/';
		if( preg_match( '/^%/', $value ) || preg_match( '/%$/', $value ) )
		{
			$operation	= ' LIKE ';
			$value		= $this->secureValue( $value );
		}
		else if( preg_match( $pattern, $value, $result ) )
		{
			$matches	= array();
			preg_match_all( $pattern, $value, $matches );
			$operation	= ' '.$matches[1][0].' ';
			$value		= $this->secureValue( $matches[2][0] );
		}
		else
		{
			if( strtolower( $value ) == 'is null' || strtolower( $value ) == 'is not null')
				$operation	= ' ';
			else if( $value === NULL )
			{
				$operation	= ' is ';
				$value		= 'NULL';
			}
			else
			{
				$operation	= ' = ';
				$value		= $this->secureValue( $value );
			}
		}
		$column	= '`'.$column.'`';
		return $column.$operation.$value;
	}
	
	/**
	 *	Secures Conditions Value by adding slashes or quoting.
	 *	@access		protected
	 *	@param		string		$value		String to be secured
	 *	@return		string
	 */
	protected function secureValue( $value )
	{
#		if( !ini_get( 'magic_quotes_gpc' ) )
#			$value = addslashes( $value );
#		$value	= htmlentities( $value );
		$value	= $this->dbc->quote( $value );
		return $value;
	}
				
	/**
	 *	Setting all columns of the table.
	 *	@access		public
	 *	@param		array		$columns	List of table columns
	 *	@return		void
	 *	@throws		Exception
	 */
	public function setColumns( $columns )
	{
		if( !( is_array( $columns ) && count( $columns ) ) )
			throw new InvalidArgumentException( 'Column array must not be empty' );
		$this->columns = $columns;
	}

	/**
	 *	Setting a reference to a database connection.
	 *	@access		public
	 *	@param		PDO		$dbc		Database connection resource object
	 *	@return		void
	 */
	public function setDbConnection( $dbc )
	{
		if( !is_object( $dbc ) )
			throw new InvalidArgumentException( 'Database connection resource must be an object' );
		if( !is_a( $dbc, 'PDO' ) )
			throw new InvalidArgumentException( 'Database connection resource must be a direct or inherited PDO object' );
		$this->dbc = $dbc;
	}

	/**
	 *	Sets fetch mode.
	 *	@access		public
	 *	@param		inst		$mode			Fetch mode
	 *	@see		http://www.php.net/manual/en/pdo.constants.php
	 *	@return		void
	 */
	public function setFetchMode( $mode )
	{
		$this->fetchMode	= $mode;
	}

	/**
	 *	Setting all indices of this table.
	 *	@access		public
	 *	@param		array		$indices		List of table indices
	 *	@return		bool
	 */
	public function setIndices( $indices )
	{
		foreach( $indices as $index )
		{
			if( !in_array( $index, $this->columns ) )
				throw new InvalidArgumentException( 'Column "'.$index.'" is not existing in table "'.$this->tableName.'" and cannot be an index' );
			if( $index === $this->primaryKey )
				throw new InvalidArgumentException( 'Column "'.$index.'" is already primary key and cannot be an index' );
		}
		$this->indices	= $indices;
		array_unique( $this->indices );
	}

	/**
	 *	Setting the name of the primary key.
	 *	@access		public
	 *	@param		string		$column		Pimary key column of this table
	 *	@return		void
	 */
	public function setPrimaryKey( $column )
	{
		if( !in_array( $column, $this->columns ) )
			throw new InvalidArgumentException( 'Column "'.$column.'" is not existing and can not be primary key' );
		$this->primaryKey = $column;
	}

	/**
	 *	Setting the name of the table.
	 *	@access		public
	 *	@param		string		$tableName		Name of this table
	 *	@return		void
	 */
	public function setTableName( $tableName )
	{
		$this->tableName = $tableName;
	}

	/**
	 *	Checks if a focus is set for following operation and throws an exception if not.
	 *	@access		protected
	 *	@throws		RuntimeException
	 *	@return		void
	 */
	protected function validateFocus()
	{
		if( !$this->isFocused() )
			throw new RuntimeException( 'No Primary Key or Index focused for Table "'.$this->tableName.'"' );
	}

	/**
	 *	Checks columns names for querying methods (find,get), sets wildcard if empty or throws an exception if inacceptable.
	 *	@access		protected
	 *	@param		mixed		$columns			String or array of column names to validate
	 *	@return		void
	 */
	protected function validateColumns( &$columns )
	{
		if( is_string( $columns ) && $columns )
			$columns	= array( $columns );
		else if( is_array( $columns ) && !count( $columns ) )
			$columns	= array( '*' );
		else if( $columns === NULL || $columns == FALSE )
			$columns	= array( '*' );

		if( !is_array( $columns ) )
			throw new InvalidArgumentException( 'Column keys must be an array of column names, a column name string or "*"' );
		foreach( $columns as $column )
			if( $column != '*' && !in_array( $column, $this->columns ) )
				throw new InvalidArgumentException( 'Column key "'.$column.'" is not a valid column of table "'.$this->tableName.'"' );
	}
}
?>