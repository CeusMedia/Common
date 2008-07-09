<?php
/**
 *	Table with Column Definition and Keys.
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Table with column definition and keys.
 *	@package		database
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			finish Code Documentation (@param at methods)
 */
class Database_TableReader
{
	/**	@var	Object			$dbc			Database Connection */
	protected $dbc;
	/**	@var	array			$fields			List of Table Fields / columns */
	protected $fields			= array();
	/**	@var	int				$focus			focused Primary Key */
	protected $focus			= false;
	/**	@var	string			$focusKey		Name of Primary Key */
	protected $focusKey;
	/**	@var	array			$foreignKeys	List of Foreign Keys of Table */
	protected $foreignKeys		= array();
	/**	@var	array			$foreignFocuses	List of focussed Keys */
	protected $foreignFocuses	= array();
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
	 *	@param		array		$fields			All Fields / Columns of this Table
	 *	@param		string		$primaryKey		Name of the Primary Keys of this Table
	 *	@param		int			$focus			Focused Primary Key of this Table
	 *	@return		void
	 */
	public function __construct( $dbc, $tableName, $fields, $primaryKey, $focus = false )
	{
		$this->setDBConnection( $dbc );
		$this->setTableName( $tableName );
		$this->setFields( $fields);
		$this->setPrimaryKey( $primaryKey );
		$this->defocus();
		if( $focus )
			$this->focusPrimary( $focus );
	}

	/**
	 *	Deleting current focus on a primary/foreign key ID.
	 *	@access		public
	 *	@return		bool
	 */
	public function defocus()
	{
		$this->focus		= false;
		$this->focusKey	= false;
		$this->foreignFocuses = array();
		return true;
	}

	/**
	 *	Setting focus on a foreign key ID.
	 *	@access		public
	 *	@param		string		$key			Foreign Key Name
	 *	@param		int			$id				Foreign Key ID to focus on
	 *	@return		bool
	 */
	public function focusForeign( $key, $id )
	{
		if( in_array( $key, $this->foreignKeys ) )
		{
			$this->foreignFocuses[$key] = $id;
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
		$this->focusKey = $this->primaryKey;
		return true;
	}

	/**
	 *	Returns count of all entries of this Table.
	 *	@access		public
	 *	@param		array		$conditions		Array of Condition Strings
	 *	@param		bool		$verbose		Flag: print Query
	 *	@return		int
	 */
	public function getAllCount( $conditions = array(), $verbose = false )
	{
		if( sizeof( $this->fields ) )
		{
			$conditions	= $this->getConditionQuery( $conditions, false, false );
			$conditions 	= $conditions ? " WHERE ".$conditions : "";
			$query = "SELECT COUNT(".$this->primaryKey.") FROM ".$this->getTableName().$conditions;
			if( $verbose )
				echo "<br/>".$query;
			$q	= $this->dbc->Execute( $query );
			$d	= $q->FetchRow();
			return $d[0];
		}
		return -1;
	}

	/**
	 *	Returns all entries of this Table in an array.
	 *	@access		public
	 *	@param		array		$keys			Array of Table Keys
	 *	@param		array		$conditions		Array of Condition Strings
	 *	@param		array		$orders			Array of Order Relations
	 *	@param		array		$limit			Array of Limit Conditions
	 *	@param		bool		$verbose		Flag: print Query
	 *	@return		array
	 */
	public function getAllData( $keys = array(), $conditions = array(), $orders = array(), $limit = array(), $verbose = false )
	{
		if( sizeof( $this->fields ) )
		{
			if( ( is_array( $keys ) && !count( $keys ) ) || ( is_string( $keys ) && !$keys ) )
				$keys	= array( "*" );
			$conditions	= $this->getConditionQuery( $conditions, false, false );
			$conditions 	= $conditions ? " WHERE ".$conditions : "";
			$orders		= $this->getOrderQuery( $orders );
			$limit		= $this->getLimitQuery( $limit );
		
			$list	= array();
			$query = "SELECT ".implode( ", ", $keys )." FROM ".$this->getTableName().$conditions.$orders.$limit;
			if( $verbose )
				echo "<br/>".$query;
			$q	= $this->dbc->Execute( $query );
			while( $d = $q->FetchNextObject( false ) )
			{
				$data	= array();
				foreach( $this->fields as $field )
					if( in_array( "*", $keys ) || in_array( $field, $keys ) )
						$data[$field] = $d->$field;
				$list[] = $data;
			}
		}
		return $list;
	}

	/**
	 *	Builds SQL of given and set Conditions.
	 *	@access		protected
	 *	@param		array		$conditions		Array of Query Conditions
	 *	@param		bool		$usePrimary		Flag: use focused Primary Key
	 *	@param		bool		$useForeign		Flag: use focused Foreign Keys
	 *	@return		string
	 */
	protected function getConditionQuery( $conditions, $usePrimary = true, $useForeign = true )
	{
		$new = array();
		foreach( $this->fields as $field )									//  iterate all Fields
			if( isset( $conditions[$field] ) )								//  if Condition given
				$new[$field] = $conditions[$field];							//  note Condition Pair
		if( $useForeign && count( $this->foreignFocuses ) )					//  if using Foreign Keys
			foreach( $this->foreignFocuses as $key => $value )				//  iterate focused Foreign Keys & is focused Foreign
				$new[$key] = $value; 										//  note foreign Key Pair

		if( $usePrimary && $this->isFocused() == "primary" )				//  if using foreign Keys & is focused primary
			$new[$this->focusKey] = $this->focus;							//  note primary Key Pair

		$conditions = array();
		foreach( $new as $key => $value )									//  iterate all noted Pairs
//			$conditions[] =  $key.'="'.$value.'"';							//  create SQL WHERE Condition
			if( preg_match( "/%/", $value ) )
				$conditions[] = $key." LIKE '".$value."'";
			else if( preg_match( "/^<|=|>|!=/", $value ) )
				$conditions[] = $key.$value;
			else
				$conditions[] = $key."='".$value."'";
		$conditions = implode( " AND ", $conditions );						//  combine Conditions with AND
		return $conditions;
	}

	/**
	 *	Returns data of focused primary key.
	 *	@access		public
	 *	@param		array	$data		array of data to store
	 *	@return		bool
	 */
	public function getData( $first = false, $orders = array(), $limit = array(), $verbose = false )
	{
		$data = array();
		if( $this->isFocused() && sizeof( $this->fields ) )
		{
			$conditions	= $this->getConditionQuery( array() );
			$orders		= $this->getOrderQuery( $orders );
			$limit		= $this->getLimitQuery( $limit );

		 	$query = "SELECT * FROM ".$this->getTableName()." WHERE ".$conditions.$orders.$limit;
			if( $verbose )
				echo "<br/>".$query;
			$q	= $this->dbc->Execute( $query );
			if( $q->RecordCount() )
			{
				while( $d = $q->FetchNextObject( false ) )
				{
					$line = array();
					foreach( $this->fields as $field )
						$line[$field] = $d->$field;
					$data[] = $line;
				}
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
	 *	Returns current primary focus or foreign focuses.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getFocus()
	{
		if( $this->isFocused() == "primary" )
			return $this->focus;
		else if( $this->isFocused() == "foreign" )
			return $this->foreignFocuses;
		return FALSE;
	}

	/**
	 *	Returns all foreign keys of this Table.
	 *	@access		public
	 *	@return		array
	 */
	public function getForeignKeys()
	{
		return $this->foreignKeys;
	}

	/**
	 *	Builds Query Limit.
	 *	@access		protected
	 *	@param		array		$limit			Array of Offet and Limit
	 *	@return		string
	 */
	protected function getLimitQuery( $limit = array() )
	{
		if( is_array( $limit ) && count( $limit ) == 2 ) 
			$limit = " LIMIT ".$limit[0].", ".$limit[1];
		else
			$limit = "";
		return $limit;
	}

	/**
	 *	Builds Query Order.
	 *	@access		protected
	 *	@param		array		$orders			Array of Orders, like array('field1'=>"ASC",'field'=>"DESC") 
	 *	@return		string
	 */
	protected function getOrderQuery( $orders = array() )
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
	 *	Returns the name of the primary key.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 *	Returns the name of the Table.
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
		if( $this->focus !== false && $this->focusKey )
			return "primary";
		if( count( $this->foreignFocuses ) )
			return "foreign";
		return false;
	}

	/**
	 *	Setting a reference to a database connection.
	 *	@access		public
	 *	@param		Object		$dbc		Database Connection
	 *	@return		void
	 */
	public function setDBConnection( $dbc )
	{
		$this->dbc = $dbc;
	}

	/**
	 *	Setting all Fields / Columns of the Table.
	 *	@access		public
	 *	@param		array		$fields		all Fields / Columns of the Table
	 *	@return		void
	 */
	public function setFields( $fields )
	{
		if( is_array( $fields ) && count( $fields ) )
			$this->fields = $fields;
		else
			trigger_error( "Field Array of Table Definition must no be empty.", E_USER_ERROR );
	}

	/**
	 *	Setting all foreign keys of this Table.
	 *	@access		public
	 *	@param		array	$keys			all foreign keys of the Table
	 *	@return		bool
	 */
	public function setForeignKeys( $keys )
	{
		$found = true;
		$this->foreignKeys	= array();
		foreach( $keys as $key )
		{
			if( !in_array( $key, $this->foreignKeys ) )
				$this->foreignKeys[] = $key;
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
	 */
	public function setTableName( $tableName )
	{
		$this->tableName = $tableName;
	}
}
?>