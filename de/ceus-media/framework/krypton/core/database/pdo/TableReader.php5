<?php
/**
 *	Table with Column Definition and Keys.
 *	@package		framework.krypton.core.database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Table with column definition and keys.
 *	@package		framework.krypton.core.database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Framework_Krypton_Core_Database_PDO_TableReader
{
	/**	@var	BaseConnection	$dbc				Database Connection */
	protected $dbc;
	/**	@var	array			$columns			List of Table Columns */
	protected $columns			= array();
	/**	@var	int				$focus				focused Primary Key */
	protected $focus			= false;
	/**	@var	string			$focusedPrimaryKey	Type of focused Key */
	protected $focusedPrimaryKey;
	/**	@var	array			$indices			List of Indices of Table */
	protected $indices			= array();
	/**	@var	string			$focusedIndices		List of focused Index Keys */
	protected $focusedIndices	= array();
	/**	@var	string			$primaryKey			Primary Key of this Table */
	protected $primaryKey;
	/**	@var	string			$tableName			Name of this Table */
	protected $tableName;

	/**
	 *	Constructor.
	 *
	 *	@access		public
	 *	@param		Object		$dbc			Database Connection
	 *	@param		string		$table			Table Name
	 *	@param		array		$columns		All Columns of this Table
	 *	@param		string		$primaryKey		Name of the Primary Keys of this Table
	 *	@param		int			$focus			Focused Primary Key of this Table
	 *	@return		void
	 */
	public function __construct( &$dbc, $tableName, $columns, $primaryKey, $focusedPrimaryKey = false )
	{
		$this->setDBConnection( $dbc );
		$this->tableName	= $tableName;
		$this->setColumns( $columns );
		$this->setPrimaryKey( $primaryKey );
		$this->defocus();
		if( $focusedPrimaryKey )
			$this->focusPrimary( $focusedPrimaryKey );
	}

	protected function logQuery( $query )
	{
#		error_log( $query."\n".str_repeat( "-", 80 )."\n", 3, "pdo_queries.log" );
	}

	/**
	 *	Constructor.
	 *	@access		protected
	 *	@param		string		$type			Type of Check (columns|focus)
	 *	@throws		Exception
	 *	@return		void
	 */
	protected function check( $type )
	{
		switch( $type )
		{
			case 'columns':
				if( !sizeof( $this->columns ) )
					throw new Exception( "No Fields defined for Table '".$this->tableName."'." );
				break;
			case 'focus':
				if( !$this->isFocused() )
					throw new Exception( "No Primary Key or Index focused for Table '".$this->tableName."'." );
				break;
			default:
				throw new Exception( "No Check Type defined in Model of Table '".$this->tableName."'." );
		}
	}	

	/**
	 *	Returns count of all entries of this Table covered by conditions.
	 *	@access		public
	 *	@param		array		$conditions		Array of Condition Strings
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		int
	 */
	public function count( $conditions = array() )
	{
		$this->check( 'columns' );
		$conditions	= $this->getConditionQuery( $conditions, false, true );
		$conditions	= $conditions ? " WHERE ".$conditions : "";
		$query	= "SELECT * FROM ".$this->getTableName().$conditions;
		$query = "SELECT COUNT(".$this->primaryKey.") as count FROM ".$this->getTableName().$conditions;
		$this->logQuery( $query );
		$q	= $this->dbc->query( $query );
		$d	= $q->fetch( PDO::FETCH_ASSOC );
		return $d['count'];
	}

	/**
	 *	Deleting current focus on a primary Key/Index.
	 *	@access		public
	 *	@return		bool
	 */
	public function defocus()
	{
		$this->focus		= false;
		$this->focusedPrimaryKey	= false;
		$this->focusedIndices = array();
		return true;
	}

	/**
	 *	Returns all entries of this Table in an array.
	 *	@access		public
	 *	@param		array		$keys			Array of Table Keys
	 *	@param		array		$conditions		Array of Condition Strings
	 *	@param		array		$orders			Array of Order Relations
	 *	@param		array		$limit			Array of Limit Conditions
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		array
	 */
	function find( $keys = array(), $conditions = array(), $orders = array(), $limit = array() )
	{
		$this->check( 'columns' );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$conditions		= $this->getConditionQuery( $conditions, false, true );
		$conditions 	= $conditions ? " WHERE ".$conditions : "";
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$list		= array();
		$query		= "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName().$conditions.$orders.$limit;
		$this->logQuery( $query );
		$q	= $this->dbc->query( $query, PDO::FETCH_ASSOC );
		foreach( $q as $d )
			$list[]	= $d;
		return $list;
	}
	
	function findWhereIn( $keys = array(), $column, $values, $orders = array(), $limit = array() )
	{
		$this->check( 'columns' );
		if( $column != $this->getPrimaryKey() && !in_array( $column, $this->getIndices() ) )
			throw new Exception( "Column of WHERE IN-Statement must be an Index." );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$list		= array();
		$query		= "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName()." WHERE ".$column." IN (".implode( ", ", $values ).") ".$orders.$limit;
		$this->logQuery( $query );
		$q	= $this->dbc->query( $query, PDO::FETCH_ASSOC );
		foreach( $q as $d )
			$list[]	= $d;
		return $list;
	}

	function findWhereInAnd( $keys = array(), $column, $values, $conditions, $orders = array(), $limit = array() )
	{
		$this->check( 'columns' );
		if( $column != $this->getPrimaryKey() && !in_array( $column, $this->getIndices() ) )
			throw new Exception( "Column of WHERE IN-Statement must be an Index." );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$conditions	= $this->getConditionQuery( $conditions, false, true );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		if( $conditions )
			$conditions	.= " AND ";
		$list		= array();
		$query		= "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName()." WHERE ".$conditions.$column." IN (".implode( ", ", $values ).") ".$orders.$limit;
		$this->logQuery( $query );
		$q	= $this->dbc->query( $query, PDO::FETCH_ASSOC );
		foreach( $q as $d )
			$list[]	= $d;
		return $list;
	}

	function getFields()
	{
		return $this->getColumns();
	}

	/**
	 *	Setting focus on an Index.
	 *	@access		public
	 *	@param		string		$key			Index Key Name
	 *	@param		int			$value			Index to focus on
	 *	@return		bool
	 */
	public function focusIndex( $key, $value )
	{
		if( in_array( $key, $this->indices ) )
		{
			$this->focusedIndices[$key] = $value;
			return true;
		}
		return false;
	}

	/**
	 *	Setting focus on a primary key ID.
	 *	@access		public
	 *	@param		int			$id				Primary Key ID to focus on
	 *	@return		bool
	 */
	public function focusPrimary( $id )
	{
		$this->focus = $id;
		$this->focusedPrimaryKey = $this->primaryKey;
		return true;
	}

	/**
	 *	Returns data of focused keys.
	 *	@access		public
	 *	@param		bool	$first		Extract first entry of Result
	 *	@param		array	$orders		Associative Array of Orders
	 *	@param		array	$limit		Array of Offset and Limit
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		array
	 */
	public function get( $first = true, $orders = array(), $limit = array() )
	{
		$this->check( 'columns' );
		$this->check( 'focus' );

		if( $this->isFocused() == "primary" )
		{
			$query	= "SELECT * FROM ".$this->getTableName()." WHERE ".$this->primaryKey."=?";
			$stmt	= $this->dbc->prepare( $query );
			$stmt->execute( array( $this->focus ) );
			$result	= $stmt->fetchAll( PDO::FETCH_ASSOC );
			foreach( $result as $entry )
				return $entry;
			return array();
		}
		
		$data = array();
		$conditions	= $this->getConditionQuery( array() );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$query	= "SELECT * FROM ".$this->getTableName()." WHERE ".$conditions.$orders.$limit;
		$this->logQuery( $query );
		$q	= $this->dbc->query( $query, PDO::FETCH_ASSOC );
		if( $q->columnCount() )
		{
			if( $first )
				foreach( $q as $d )
					return $d;
		}
		return $q->fetchAll( PDO::FETCH_ASSOC );
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
	 *	Returns all Columns of the Table.
	 *	@access		public
	 *	@return		array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 *	Returns current primary focus or index focuses.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getFocus()
	{
		if( $this->isFocused() == "primary" )
			return $this->focus;
		else if( $this->isFocused() == "index" )
			return $this->focusedIndices;
		return false;
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
	 *	@return		bool
	 */
	public function isFocused()
	{
		if( $this->focus !== false && $this->focusedPrimaryKey )
			return "primary";
		if( count( $this->focusedIndices ) )
			return "index";
		return false;
	}
	
	/**
	 *	Builds and returns WHERE Statement Component.
	 *	@access		protected
	 *	@param		array		$conditions		Array of Conditions
	 *	@param		bool		$use_primary	Flag: use focused primary key
	 *	@param		bool		$use_indices	Flag: use focused indices
	 *	@return		string
	 */
	protected function getConditionQuery( $conditions, $use_primary = true, $use_indices = true )
	{
		$new = array();
		foreach( $this->columns as $column )									//  iterate all Columns
			if( isset( $conditions[$column] ) )								//  if Condition given
				$new[$column] = $conditions[$column];							//  note Condition Pair
		if( $use_primary && $this->isFocused() == "primary" )				//  if using primary Key & is focused primary
			$new[$this->focusedPrimaryKey] = $this->focus;							//  note primary Key Pair
		else if( $use_indices && count( $this->focusedIndices ) )					//  if using Indices
			foreach( $this->focusedIndices as $key => $value )				//  iterate focused Indices
				$new[$key] = $value; 										//  note Index Pair


		$conditions = array();
		foreach( $new as $key => $value )									//  iterate all noted Pairs
			if( preg_match( "/%/", $value ) )
				$conditions[] = $key." LIKE '".$value."'";
			else if( preg_match( "/^<|=|>|!=/", $value ) )
				$conditions[] = $key.$value;
			else
			{
				if( strtolower( $value ) == 'is null' || strtolower( $value ) == 'is not null')
					$conditions[] = $key.' '.addslashes( $value );
				else if( $value === null )
					$conditions[] = $key.' is NULL';
				else
					$conditions[] = $key."='".addslashes( $value )."'";
			}
		$conditions = implode( " AND ", $conditions );						//  combine Conditions with AND
		return $conditions;
	}

	/**
	 *	Builds and returns ORDER BY Statement Component.
	 *	@access		protected
	 *	@param		array		$limit			Array of Offset and Limit
	 *	@return		string
	 */
	protected function getLimitCondition( $limit = array() )
	{
		if( is_array( $limit ) && count( $limit ) == 2 ) 
			$limit = " LIMIT ".$limit[0].", ".$limit[1];
		else
			$limit = "";
		return $limit;
	}
	
	/**
	 *	Builds and returns ORDER BY Statement Component.
	 *	@access		protected
	 *	@param		array		$orders			Associative Array with Orders
	 *	@return		string
	 */
	protected function getOrderCondition( $orders = array() )
	{
		if( is_array( $orders ) && count( $orders ) )
		{
			$order = array();
			foreach( $orders as $key => $value )
				$order[] = $key." ".$value;
			$orders = " ORDER BY ".implode( ", ", $order );
		}
		else
			$orders = "";
		return $orders;
	}

	/**
	 *	Setting a reference to a database connection.
	 *	@access		public
	 *	@param		Object	$dbc		Database Connection
	 *	@return		void
	 */
	public function setDBConnection( &$dbc )
	{
		$this->dbc =& $dbc;
	}

	/**
	 *	Setting all Columns of the Table.
	 *	@access		public
	 *	@param		array		$columns		all Columns of the Table
	 *	@return		void
	 *	@throws		Exception
	 */
	public function setColumns( $columns )
	{
		if( is_array( $columns ) && count( $columns ) )
			$this->columns = $columns;
		else
			throw new Exception( "Column Array of Table Definition must no be empty." );
	}

	/**
	 *	Setting all indices of this Table.
	 *	@access		public
	 *	@param		array	$keys			all indices of the Table
	 *	@return		bool
	 */
	public function setIndices( $keys )
	{
		$found = true;
		foreach( $keys as $key )
		{
			if( !in_array( $key, $this->indices ) )
				$this->indices[] = $key;
			else $found = false;
		}
		return $found;
	}

	/**
	 *	Setting the name of the primary key.
	 *	@access		public
	 *	@param		string		$key			primary Key of this Table
	 *	@return		bool
	 */
	public function setPrimaryKey( $key )
	{
		if( in_array( $key, $this->columns ) )
		{
			$this->primaryKey = $key;
			return true;
		}
		return false;
	}

	/**
	 *	Setting the name of the Table.
	 *	@access		public
	 *	@param		string		$tableName		Database Connection
	 *	@return		void
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@version	0.4
	 */
	public function setTableName( $tableName )
	{
		$this->tableName = $tableName;
	}
}
?>
