<?php
/**
 *	Table with Column Definition and Keys.
 *	@package		mv2.core.database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Table with column definition and keys.
 *	@package		mv2.core.database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Framework_Krypton_Core_Database_TableReader
{
	/**	@var	BaseConnection	$dbc			Database Connection */
	protected $dbc;
	/**	@var	array			$fields			List of Table Fields / columns */
	protected $fields			= array();
	/**	@var	int				$focus			focused Primary Key */
	protected $focus			= false;
	/**	@var	string			$focus_key		Type of focused Key */
	protected $focus_key;
	/**	@var	array			$indices		List of Indices of Table */
	protected $indices			= array();
	/**	@var	string			$primary_key	Primary Key of this Table */
	protected $index_focuses	= array();
	/**	@var	string			$primary_key	Primary Key of this Table */
	protected $primary_key;
	/**	@var	string			$tablename		Name of this Table */
	protected $tablename;

	/**
	 *	Constructor.
	 *
	 *	@access		public
	 *	@param		Object		$dbc			Database Connection
	 *	@param		string		$table			Table Name
	 *	@param		array		$fields			All Fields / Columns of this Table
	 *	@param		string		$primary_key	Name of the Primary Keys of this Table
	 *	@param		int			$focus			Focused Primary Key of this Table
	 *	@return		void
	 */
	public function __construct( &$dbc, $table_name, $fields, $primary_key, $focus = false )
	{
		$this->setDBConnection( $dbc );
		$this->setTableName( $table_name );
		$this->setFields( $fields );
		$this->setPrimaryKey( $primary_key );
		$this->defocus();
		if( $focus )
			$this->focusPrimary( $focus );
	}

	/**
	 *	Constructor.
	 *	@access		protected
	 *	@param		string		$type			Type of Check (fields|focus)
	 *	@throws		Exception
	 *	@return		void
	 */
	protected function check( $type )
	{
		switch( $type )
		{
			case 'fields':
				if( !sizeof( $this->fields ) )
					throw new Exception( "No Fields defined for Table '".$this->tablename."'." );
				break;
			case 'focus':
				if( !$this->isFocused() )
					throw new Exception( "No Primary Key or Index focused for Table '".$this->tablename."'." );
				break;
			default:
				throw new Exception( "No Check Type defined in Model of Table '".$this->tablename."'." );
		}
	}	

	/**
	 *	Returns count of all entries of this Table covered by conditions.
	 *	@access		public
	 *	@param		array		$conditions		Array of Condition Strings
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		int
	 */
	public function count( $conditions = array(), $debug = false )
	{
		$this->check( 'fields' );
		$conditions	= $this->getConditionQuery( $conditions, false, true );
		$conditions	= $conditions ? " WHERE ".$conditions : "";
		$query	= "SELECT * FROM ".$this->getTableName().$conditions;
		$query = "SELECT COUNT(".$this->primary_key.") as count FROM ".$this->getTableName().$conditions;
		$q	= $this->dbc->query( $query, $debug );
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
		$this->focus_key	= false;
		$this->index_focuses = array();
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
	function find( $keys = array(), $conditions = array(), $orders = array(), $limit = array(), $debug = 1 )
	{
		$this->check( 'fields' );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$conditions		= $this->getConditionQuery( $conditions, false, true );
		$conditions 	= $conditions ? " WHERE ".$conditions : "";
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$all	= array();
		$query = "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName().$conditions.$orders.$limit;
		$q	= $this->dbc->query( $query, $debug );
		while( $d = $q->fetch( PDO::FETCH_OBJ ) )
		{
			$data	= array();
			foreach( $this->fields as $field )
				if( in_array( "*", $keys ) || in_array( $field, $keys ) )
					$data[$field] = $d->$field;
			$all[] = $data;
		}
		return $all;
	}
	
	function findWhereIn( $keys = array(), $field, $values, $orders = array(), $limit = array(), $debug = 1 )
	{
		$this->check( 'fields' );
		if( $field != $this->getPrimaryKey() && !in_array( $field, $this->getIndices() ) )
			throw new Exception( "Field of WHERE IN-Statement must be an Index." );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$all	= array();
		$query = "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName()." WHERE ".$field." IN (".implode( ", ", $values ).") ".$orders.$limit;
		$q	= $this->dbc->query( $query, $debug );
		while( $d = $q->fetch( PDO::FETCH_OBJ ) )
		{
			$data	= array();
			foreach( $this->fields as $field )
				if( in_array( "*", $keys ) || in_array( $field, $keys ) )
					$data[$field] = $d->$field;
			$all[] = $data;
		}
		return $all;
	}

	function findWhereInAnd( $keys = array(), $field, $values, $conditions, $orders = array(), $limit = array(), $debug = 1 )
	{
		$this->check( 'fields' );
		if( $field != $this->getPrimaryKey() && !in_array( $field, $this->getIndices() ) )
			throw new Exception( "Field of WHERE IN-Statement must be an Index." );
		if( !(is_array( $keys ) && count( $keys ) ) )
			$keys[]	= "*";
		$conditions	= $this->getConditionQuery( $conditions, false, true );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		if( $conditions )
			$conditions	.= " AND ";
		$all	= array();
		$query = "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName()." WHERE ".$conditions.$field." IN (".implode( ", ", $values ).") ".$orders.$limit;
		$q	= $this->dbc->query( $query, $debug );
		while( $d = $q->fetch( PDO::FETCH_OBJ ) )
		{
			$data	= array();
			foreach( $this->fields as $field )
				if( in_array( "*", $keys ) || in_array( $field, $keys ) )
					$data[$field] = $d->$field;
			$all[] = $data;
		}
		return $all;
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
			$this->index_focuses[$key] = $value;
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
		$this->focus_key = $this->primary_key;
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
	public function get( $first = true, $orders = array(), $limit = array(), $debug = 1 )
	{
		$this->check( 'fields' );
		$this->check( 'focus' );
		$data = array();
		$conditions	= $this->getConditionQuery( array() );
		$orders		= $this->getOrderCondition( $orders );
		$limit		= $this->getLimitCondition( $limit );
		$query = "SELECT * FROM ".$this->getTableName()." WHERE ".$conditions.$orders.$limit;
		$q	= $this->dbc->query( $query, $debug );
		if( $q->columnCount() )
		{
			while( $d = $q->fetch( PDO::FETCH_OBJ ) )
			{
				$line = array();
				foreach( $this->fields as $field )
					$line[$field] = $d->$field;
				$data[] = $line;
			}
		}
		if( count( $data ) && $first )
			$data	= $data[0];
		return $data;
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
	 *	Returns all Fields / Columns of the Table.
	 *	@access		public
	 *	@return		array
	 */
	public function getFields()
	{
		return $this->fields;
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
			return $this->index_focuses;
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
		return $this->primary_key;
	}

	/**
	 *	Returns the Name of the Table.
	 *	@access		public
	 *	@return		string
	 */
	public function getTableName()
	{
		return $this->tablename;
	}

	/**
	 *	Indicates wheter the focus on a key is set.
	 *	@access		public
	 *	@return		bool
	 */
	public function isFocused()
	{
		if( $this->focus !== false && $this->focus_key )
			return "primary";
		if( count( $this->index_focuses ) )
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
		foreach( $this->fields as $field )									//  iterate all Fields
			if( isset( $conditions[$field] ) )								//  if Condition given
				$new[$field] = $conditions[$field];							//  note Condition Pair
		if( $use_primary && $this->isFocused() == "primary" )				//  if using primary Key & is focused primary
			$new[$this->focus_key] = $this->focus;							//  note primary Key Pair
		else if( $use_indices && count( $this->index_focuses ) )					//  if using Indices
			foreach( $this->index_focuses as $key => $value )				//  iterate focused Indices
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
					$conditions[] = $key.' '.$value;
				else if( $value === null )
					$conditions[] = $key.' is NULL';
				else
					$conditions[] = $key."='".$value."'";
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
	 *	Setting all Fields / Columns of the Table.
	 *	@access		public
	 *	@param		array		$fields		all Fields / Columns of the Table
	 *	@return		void
	 *	@throws		Exception
	 */
	public function setFields( $fields )
	{
		if( is_array( $fields ) && count( $fields ) )
			$this->fields = $fields;
		else
			throw new Exception( "Field Array of Table Definition must no be empty." );
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
		if( in_array( $key, $this->fields ) )
		{
			$this->primary_key = $key;
			return true;
		}
		return false;
	}

	/**
	 *	Setting the name of the Table.
	 *	@access		public
	 *	@param		string		$table_name		Database Connection
	 *	@return		void
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@version	0.4
	 */
	public function setTableName( $table_name )
	{
		$this->tablename = $table_name;
	}
}
?>
