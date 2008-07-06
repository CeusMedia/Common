<?php
/**
 *	Table with Column Definition and Keys.
 *	@package		database.pdo
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Table with column definition and keys.
 *	@package		database.pdo
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Database_PDO_TableReader
{
	/**	@var	BaseConnection	$dbc			Database Connection */
	protected $dbc;
	/**	@var	array			$columns		List of Table Columns */
	protected $columns			= array();
	/**	@var	array			$indices		List of Indices of Table */
	protected $indices			= array();
	/**	@var	string			$focusedIndices	List of focused Index Keys */
	protected $focusedIndices	= array();
	/**	@var	string			$primaryKey		Primary Key of this Table */
	protected $primaryKey;
	/**	@var	string			$tableName		Name of this Table */
	protected $tableName;

	/**
	 *	Constructor.
	 *
	 *	@access		public
	 *	@param		Object		$dbc			Database Connection
	 *	@param		string		$tableName		Table Name
	 *	@param		array		$columns		All Columns of this Table
	 *	@param		string		$primaryKey		Name of the Primary Keys of this Table
	 *	@param		int			$focus			Focused Primary Key of this Table
	 *	@return		void
	 */
	public function __construct( &$dbc, $tableName, $columns, $primaryKey, $focus = FALSE )
	{
		$this->setDBConnection( $dbc );
		$this->setTableName( $tableName );
		$this->setColumns( $columns );
		$this->setPrimaryKey( $primaryKey );
		$this->defocus();
		if( $focus )
			$this->focusPrimary( $focus );
	}

	/**
	 *	Constructor.
	 *	@access		protected
	 *	@param		string		$type			Type of Check (columns|focus)
	 *	@throws		Exception
	 *	@return		void
	 */
	protected function validateFocus()
	{
		if( !$this->isFocused() )
			throw new RuntimeException( "No Primary Key or Index focused for Table '".$this->tableName."'." );
	}	

	/**
	 *	Returns count of all entries of this Table covered by conditions.
	 *	@access		public
	 *	@param		array		$conditions		Array of Condition Strings
	 *	@return		int
	 */
	public function count( $conditions = array() )
	{
		$conditions	= $this->getConditionQuery( $conditions, FALSE, TRUE );
		$conditions	= $conditions ? " WHERE ".$conditions : "";
		$query	= "SELECT COUNT(".$this->primaryKey.") as count FROM ".$this->getTableName().$conditions;
		$result	= $this->dbc->query( $query );
		$count	= $result->fetch( PDO::FETCH_ASSOC );
		return $count['count'];
	}

	/**
	 *	Deleting current focus on a primary Key/Index.
	 *	@access		public
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
	 *	Returns all entries of this Table in an array.
	 *	@access		public
	 *	@param		array		$keys			Array of Table Keys
	 *	@param		array		$conditions		Array of Condition Strings
	 *	@param		array		$orders			Array of Order Relations
	 *	@param		array		$limit			Array of Limit Conditions
	 *	@return		array
	 */
	public function find( $keys = array(), $conditions = array(), $orders = array(), $limit = array() )
	{
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$conditions	= $this->getConditionQuery( $conditions, FALSE, TRUE );		
		$conditions = $conditions ? " WHERE ".$conditions : "";
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$query		= "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName().$conditions.$orders.$limit;
		$resultSet	= $this->dbc->query( $query );

		return $resultSet->fetchAll( PDO::FETCH_ASSOC );
	
#		$list		= array();
#		while( $d = $resultSet->fetch( PDO::FETCH_OBJ ) )
#		{
#			$data	= array();
#			foreach( $this->columns as $column )
#				if( in_array( "*", $keys ) || in_array( $column, $keys ) )
#					$data[$column] = stripslashes( $d->$column );
#			$list[] = $data;
#		}
#		return $list;
	}
	
	public function findWhereIn( $keys = array(), $column, $values, $orders = array(), $limit = array() )
	{
		if( $column != $this->getPrimaryKey() && !in_array( $column, $this->getIndices() ) )
			throw new Exception( "Field of WHERE IN-Statement must be an Index." );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		for( $i=0; $i<count( $values ); $i++ )
			$values[$i]	= $this->secureValue( $values[$i] );

		$query		= "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName()." WHERE ".$column." IN (".implode( ", ", $values ).") ".$orders.$limit;
		$resultSet	= $this->dbc->query( $query );

		return $resultSet->fetchAll( PDO::FETCH_ASSOC );

		$list		= array();
		while( $d = $resultSet->fetch( PDO::FETCH_OBJ ) )
		{
			$data	= array();
			foreach( $this->columns as $column )
				if( in_array( "*", $keys ) || in_array( $column, $keys ) )
					$data[$column] = stripslashes( $d->$column );
			$list[] = $data;
		}
		return $list;
	}

	public function findWhereInAnd( $keys = array(), $column, $values, $conditions, $orders = array(), $limit = array() )
	{
		if( $column != $this->getPrimaryKey() && !in_array( $column, $this->getIndices() ) )
			throw new Exception( "Field of WHERE IN-Statement must be an Index." );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$conditions	= $this->getConditionQuery( $conditions, FALSE, TRUE );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		for( $i=0; $i<count( $values ); $i++ )
			$values[$i]	= $this->secureValue( $values[$i] );
		
		if( $conditions )
			$conditions	.= " AND ";
		$query		= "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName()." WHERE ".$conditions.$column." IN (".implode( ", ", $values ).") ".$orders.$limit;
		$resultSet	= $this->dbc->query( $query );

		return $resultSet->fetchAll( PDO::FETCH_ASSOC );

		$list	= array();
		while( $d = $q->fetch( PDO::FETCH_OBJ ) )
		{
			$data	= array();
			foreach( $this->columns as $column )
				if( in_array( "*", $keys ) || in_array( $column, $keys ) )
					$data[$column] = stripslashes( $d->$column );
			$list[] = $data;
		}
		return $list;
	}

	/**
	 *	Setting focus on an Index.
	 *	@access		public
	 *	@param		string		$key			Index Key Name
	 *	@param		int			$value			Index to focus on
	 *	@return		void
	 */
	public function focusIndex( $key, $value )
	{
		if( isset( $this->focusedIndices[$this->primaryKey] ) )									//  focused on primary Key
			unset( $this->focusedIndices[$this->primaryKey] );									//  remove primary Key Focus
		if( !in_array( $key, $this->indices ) )													//  check Column Name
			throw new InvalidArgumentException( 'Column "'.$key.'" is not an Index and cannot be focused.' );
		$this->focusedIndices[$key] = $value;													//  set Focus
	}

	/**
	 *	Setting focus on a primary key ID.
	 *	@access		public
	 *	@param		int			$id				Primary Key ID to focus on
	 *	@param		bool		$clearIndices	Flag: clear all previously focuses Indices
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
	 *	@param		bool	$first		Extract first entry of Result
	 *	@param		array	$orders		Associative Array of Orders
	 *	@param		array	$limit		Array of Offset and Limit
	 *	@return		array
	 */
	public function get( $first = TRUE, $orders = array(), $limit = array() )
	{
		$this->validateFocus();
		$data = array();
		$conditions	= $this->getConditionQuery( array() );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$query = "SELECT * FROM ".$this->getTableName()." WHERE ".$conditions.$orders.$limit;

		$resultSet	= $this->dbc->query( $query );
		$resultList	= $resultSet->fetchAll( PDO::FETCH_ASSOC );

		if( count( $resultList ) && $first )
			return $resultList[0];
		return $resultList;
		
		if( $q->columnCount() )
		{
			while( $d = $q->fetch( PDO::FETCH_OBJ ) )
			{
				$line = array();
				foreach( $this->columns as $column )
					$line[$column] = stripslashes( $d->$column );
				$data[] = $line;
			}
		}
		if( count( $data ) && $first )
			$data	= $data[0];
		return $data;
	}

	/**
	 *	Returns all Columns of the Table.
	 *	@access		public
	 *	@return		array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 *	Builds and returns WHERE Statement Component.
	 *	@access		protected
	 *	@param		array		$conditions		Array of Conditions
	 *	@param		bool		$usePrimary		Flag: use focused primary key
	 *	@param		bool		$useIndices		Flag: use focused indices
	 *	@return		string
	 */
	protected function getConditionQuery( $conditions, $usePrimary = TRUE, $useIndices = TRUE )
	{
		$new = array();
		foreach( $this->columns as $column )								//  iterate all Columns
			if( isset( $conditions[$column] ) )								//  if Condition given
				$new[$column] = $conditions[$column];						//  note Condition Pair

		if( $usePrimary && $this->isFocused() == "primary" )				//  if using primary Key & is focused primary
		{
			if( !array_key_exists( $this->primaryKey, $new ) )				//  if primary Key is not already in Conditions
				$new = $this->getFocus();									//  note primary Key Pair
		}
		else if( $useIndices && count( $this->focusedIndices ) )			//  if using Indices
		{
			foreach( $this->focusedIndices as $key => $value )				//  iterate focused Indices
				if( !array_key_exists( $key, $new ) )						//  if Index Key is not already in Conditions
					$new[$key] = $value; 									//  note Index Pair
		}

		$conditions = array();
		foreach( $new as $key => $value )									//  iterate all noted Pairs
		{
			if( preg_match( "/%/", $value ) )
				$conditions[] = $key." LIKE '".$value."'";
			else if( preg_match( "/^<|=|>|!=/", $value ) )
				$conditions[] = $key.$value;
			else
			{
				if( strtolower( $value ) == 'is null' || strtolower( $value ) == 'is not null')
					$conditions[] = $key.' '.$value;
				else if( $value === null )
					$conditions[] = $key.' is NULL';
				else
					$conditions[] = $key."=".$this->secureValue( $value );
			}
		}
		$conditions = implode( " AND ", $conditions );						//  combine Conditions with AND
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
		$condition	= "";
		if( is_array( $limit ) && count( $limit ) == 2 ) 
			$condition = " LIMIT ".$limit[0].", ".$limit[1];
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
		$order	= "";
		if( is_array( $orders ) && count( $orders ) )
		{
			$list	= array();
			foreach( $orders as $key => $value )
				$list[] = $key." ".strtoupper( $value );
			$order	= " ORDER BY ".implode( ", ", $list );
		}
		return $order;
	}

	/**
	 *	Returns the Name of the primary key.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 *	Returns the Name of the Table.
	 *	@access		public
	 *	@return		string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 *	Indicates wheter the focus on a key is set.
	 *	@access		public
	 *	@return		string
	 */
	public function isFocused()
	{
		if( !count( $this->focusedIndices ) )
			return "";
		if( array_keys( $this->focusedIndices ) == array( $this->primaryKey ) )
			return "primary";
		return "index";
	}
	
	/**
	 *	Secures Conditions Value by adding Slashes or quoting.
	 *	@access		protected
	 *	@param		string		$value		String to be secured
	 *	@return		string
	 */
	protected function secureValue( $value )
	{
#		if( !ini_get( 'magic_quotes_gpc' ) )
#			$value = addslashes( $value );
		$value	= $this->dbc->quote( $value );
		return $value;
	}
				
	/**
	 *	Setting all Columns of the Table.
	 *	@access		public
	 *	@param		array		$columns	all Columns of the Table
	 *	@return		void
	 *	@throws		Exception
	 */
	public function setColumns( $columns )
	{
		if( !( is_array( $columns ) && count( $columns ) ) )
			throw new InvalidArgumentException( 'Column Array must not be empty.' );
		$this->columns = $columns;
	}

	/**
	 *	Setting a reference to a database connection.
	 *	@access		public
	 *	@param		Object		$dbc		Database Connection
	 *	@return		void
	 */
	public function setDBConnection( $dbc )
	{
		if( !is_object( $dbc ) )
			throw new InvalidArgumentException( 'Database Connection Resource must be an Object' );
		if( !is_a( $dbc, 'PDO' ) )
			throw new InvalidArgumentException( 'Database Connection Resource must be a direct or inherited PDO Object' );
		$this->dbc = $dbc;
	}

	/**
	 *	Setting all indices of this Table.
	 *	@access		public
	 *	@param		array		$keys		all indices of the Table
	 *	@return		bool
	 */
	public function setIndices( $keys )
	{
		foreach( $keys as $key )
			if( !in_array( $key, $this->columns ) )
				throw new InvalidArgumentException( 'Column "'.$key.'" is not existing in Table "'.$this->tableName.'" and cannot be an Index.' );
		$this->indices	= $keys;
		array_unique( $this->indices );
	}

	/**
	 *	Setting the name of the primary key.
	 *	@access		public
	 *	@param		string		$key		primary Key of this Table
	 *	@return		void
	 */
	public function setPrimaryKey( $key )
	{
		if( !in_array( $key, $this->columns ) )
			throw new InvalidArgumentException( 'Column "'.$key.'" is not existing and can not be primary Key.' );
		$this->primaryKey = $key;
	}

	/**
	 *	Setting the name of the Table.
	 *	@access		public
	 *	@param		string		$tableName		Database Connection
	 *	@return		void
	 */
	public function setTableName( $tableName )
	{
		$this->tableName = $tableName;
	}
}
?>