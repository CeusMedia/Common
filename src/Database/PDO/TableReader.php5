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
	protected $columns = array( );
	/**	@var	array			$indices			List of indices of table */
	protected $indices = array( );
	/**	@var	string			$focusedIndices		List of focused indices */
	protected $focusedIndices = array( );
	/**	@var	string			$primaryKey			Primary key of this table */
	protected $primaryKey;
	/**	@var	string			$tableName			Name of this table */
	protected $tableName;
	/** 	@var 	string 			$Alias				Alias name fur Table*/
	protected $alias = Null;
	/**	@var	int			$fetchMode			Name of this table */
	protected $fetchMode;
	/**	@var 	array			list of (Model,TableLink=array(file1,file2),Mode=string(Join ,LeftJoin,RightJoin))						*/
	protected $JoinList = Null;
	/**	@var	int				$defaultFetchMode	Default fetch mode, can be set statically */
	public static $defaultFetchMode = PDO::FETCH_ASSOC;

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

	public function __construct( &$dbc, $tableName, $columns, $primaryKey, $focus = NULL, $alias = NULL )
	{
		$this->alias = $alias;
		$this->setDbConnection( $dbc );
		$this->setTableName( $tableName );
		$this->setColumns( $columns );
		$this->setPrimaryKey( $primaryKey );
		$this->fetchMode = self::$defaultFetchMode;
		$this->defocus( );

		if ( $focus )
			$this->focusPrimary( $focus );
	}

	/**
	 *	@author		Saeid:29.Sep.2011	
	 *	
	 *	@param		Model				$ObjTableReader
	 *	@param		array				$TableLink		ex. array(file1,file2) file1 of tables1 and file2 of table2
	 *	@param		string				$Mode
	 *	@return		Database_PDO_TableReader
	 */

	public function Join( $ObjTableReader, $TableLink, $Mode = 'JOIN' )
	{
		$obj = clone $this;
		$fild1 = $TableLink[ 0 ];
		if ( !$obj->JoinList )
		{
			if ( !$obj->getAlias( ) )
			{
				foreach ( $obj->columns as $key => $value )
				{
					$obj->columns[ $key ] = $obj->getTableName( ) . '.' . $value;
				}

				foreach ( $this->indices as $key => $value )
				{
					$obj->indices[ $key ] = $obj->getTableName( ) . '.' . $value;
				}
				$obj->primaryKey = $obj->getAlias( true ) . '.' . $obj->primaryKey;
			}
			$obj->JoinList = array( );
		}
		if ( !stripos( $TableLink[ 0 ], '.' ) )
		{
			$TableLink[ 0 ] = $obj->getAlias( true ) . '.' . $TableLink[ 0 ];
		}
		$fild = $TableLink[ 0 ];
		$obj->validateColumns( $fild );

		$fild = $TableLink[ 1 ];

		if ( !stripos( $TableLink[ 1 ], '.' ) )
		{
			$TableLink[ 1 ] = $ObjTableReader->getAlias( true ) . '.' . $TableLink[ 1 ];
		}
		if ( $ObjTableReader->JoinList || $ObjTableReader->getAlias( ) )
			$fild = $TableLink[ 1 ];
		$ObjTableReader->validateColumns( $fild );

		$obj->JoinList[ ] = array(
				'ObjTableReader' => $ObjTableReader,
				'TableLink' => $TableLink,
				'Mode' => $Mode
		);
		if ( $ObjTableReader->JoinList )
		{
			$obj->JoinList = array_merge( $obj->JoinList, $ObjTableReader->JoinList );
		}

		if ( !$this->getAlias( ) )
		{
			$obj->focusedIndices = array( );

			foreach ( $this->focusedIndices as $key => $value )
			{
				//$obj->focusedIndices[ $obj->getAlias( ) . '.' . $key ] = $value;
				$obj->focusedIndices[ $key ] = array(
						$obj->getAlias( ) . '.' . $value[ 0 ],
						$value[ 1 ]
				);
			}
		}

		if ( $ObjTableReader->getAlias( true ) == $obj->getAlias( ) )
			throw new InvalidArgumentException( 'Not unique table/alias:' . $ObjTableReader->getAlias( true ));

		if ( $this->JoinList )
		{
			foreach ( $this->JoinList as $Join )
				if ( $Join[ 'ObjTableReader' ]->getAlias( true ) == $ObjTableReader->getAlias( true ) )
					throw new InvalidArgumentException( 'Not unique table/alias:' . $ObjTableReader->getAlias( true ));
		}

		return $obj;

	}

	/**
	 * @author		Saeid:06.Okt.2011
	 * Deleting JoinList and delete all getTableName  of columns , indices und focusedIndices
	 * 
	 */

	public function dejoin( )
	{
		$this->JoinList = NULL;
		if ( !$this->getAlias( ) )
		{
			foreach ( $this->columns as $key => $value )
			{
				$this->columns[ $key ] = str_replace( $this->getTableName( ) . '.', '', $value );
			}

			foreach ( $this->indices as $key => $value )
			{
				$this->indices[ $key ] = str_replace( $this->getTableName( ) . '.', '', $value );
			}
			$this->primaryKey = str_replace( $this->getTableName( ) . '.', '', $this->primaryKey );
			$Bobj = array( );
			foreach ( $this->focusedIndices as $key => $value )
			{
				//$Bobj[ str_replace( $this->getTableName( ) . '.', '', $key ) ] = $value;
				$Bobj[ $key ] = array(
						str_replace( $this->getTableName( ) . '.', '', $value[ 0 ] ),
						$value[ 1 ]
				);
			}
			$this->focusedIndices = $Bobj;
		}
	}

	/**
	 * @author		Saeid:06.Okt.2011
	 * This function controls whether or not join
	 */

	public function isJoin( )
	{
		if ( $this->JoinList )
			return true;
		else
			return false;
	}

	/**
	 * @author		Saeid:10.Okt.2011
	 * This function return  array of JoinList
	 * @return 		array 
	 */

	public function getJoinList( )
	{
		return $this->JoinList;
	}

	/** saeid	
	 *  @since 		10.10.2011
	 *	Returns count of all entries of this table covered by conditions.
	 *	@access		public
	 *	@param		array		$conditions		Map of columns and values to filter by
	 *	@return		integer
	 */

	public function count( $conditions = array( ) )
	{
		$conditions = $this->getConditionQuery( $conditions, FALSE, TRUE );
		$conditions = $conditions ? ' WHERE ' . $conditions : '';
		if ( $this->getAlias( ) )
			$query = 'SELECT COUNT(' . str_replace( '.', '.`', $this->primaryKey ) . '`) as count FROM ' . $this->getTables( ) . $conditions;
		else
			$query = 'SELECT COUNT(`' . $this->primaryKey . '`) as count FROM ' . $this->getTables( ) . $conditions;

		$result = $this->dbc->query( $query );

		$count = $result->fetch( $this->getFetchMode( ) );
		switch ( $this->fetchMode )
		{
			case PDO::FETCH_NUM:
			case PDO::FETCH_BOTH:
				return $count[ 0 ];
			case PDO::FETCH_INTO:
			case PDO::FETCH_LAZY:
			case PDO::FETCH_OBJ:
			case PDO::FETCH_SERIALIZE:
				return $count->count;
			case PDO::FETCH_ASSOC:
			case PDO::FETCH_NAMED:
				return $count[ 'count' ];
			default:
				throw new RuntimeException( 'Unsupported fetch mode');
		}
	}

	/**
	 *	Deleting current focus on indices (including primary key).
	 *	@access		public
	 *	@param		bool		$primaryOnly		Flag: delete focus on primary key only
	 *	@return		bool
	 */

	public function defocus( $primaryOnly = FALSE )
	{
		if ( !$this->focusedIndices )
			return FALSE;
		if ( $primaryOnly )
		{
			//if ( !array_key_exists( $this->primaryKey, $this->focusedIndices ) )
			//	return FALSE;
			//unset( $this->focusedIndices[ $key ] );
			//return TRUE;
			foreach ( $this->focusedIndices as $key => $value )
			{
				if ( $this->primaryKey == $value[ 0 ] )
				{
					unset( $this->focusedIndices[ $key ] );
					return TRUE;
				}
			}
			return FALSE;
		}
		$this->focusedIndices = array( );
		return TRUE;
	}

	/**	saeid
	 *	Returns all entries of this table in an array.
	 *	@access		public
	 *	@param		array		$columns		Array of table columns
	 *	@param		array		$conditions		Array of condition pairs additional to focuses indices
	 *	@param		array		$orders			Array of order relations
	 *	@param		array		$limits			Array of limit conditions
	 *	@return		array
	 */

	public function find( $columns = array( ), $conditions = array( ), $orders = array( ), $limits = array( ) )
	{
		$this->validateColumns( $columns );
		$Conditions = $this->getConditionQuery( $conditions, FALSE, FALSE );
		$Conditions = $Conditions ? ' WHERE ' . $Conditions : '';
		$orders = $this->getOrderCondition( $orders );
		$limits = $this->getLimitCondition( $limits );
		$query = 'SELECT ' . implode( ', ', $columns ) . ' FROM ' . $this->getTables( ) . $Conditions . $orders . $limits;

		$resultSet = $this->dbc->query( $query );

		if ( $resultSet )
			return $resultSet->fetchAll( $this->getFetchMode( ) );
		return array( );
	}

	/**	saeid
	 *	Returns all entries of this table in an array.
	 *	@access		public
	 *	@param		array		$columns		Array of table columns
	 *	@param		string		$column			String of column name
	 *	@param		array 		$values			values 
	 *	@param		array		$orders			Array of order relations
	 *	@param		array		$limits			Array of limit conditions
	 *	@return		array
	 */

	public function findWhereIn( $columns = array( ), $column, $values, $orders = array( ), $limits = array( ) )
	{
		$this->validateColumns( $columns );

		$columnB = $column;
		$this->validateColumns( $columnB );

		$orders = $this->getOrderCondition( $orders );
		$limits = $this->getLimitCondition( $limits );
		for ( $i = 0; $i < count( $values ); $i++ )
			$values[ $i ] = $this->secureValue( $values[ $i ] );

		$query = 'SELECT ' . implode( ', ', $columns ) . ' FROM ' . $this->getTables( ) . ' WHERE ' . $column . ' IN (' . implode( ', ', $values ) . ') ' . $orders . $limits;

		$resultSet = $this->dbc->query( $query );
		if ( $resultSet )
			return $resultSet->fetchAll( $this->getFetchMode( ) );
		return array( );
	}

	/**
	 * 
	 * Returns all entries of this table in an array.
	 * @param 	array 	$columns		Array of table columns
	 * @param 	string	$column			String of column name
	 * @param 	array 	$values			values
	 * @param 	array 	$conditions		Array of condition pairs additional to focuses indices
	 * @param 	array 	$orders			Array of order relations
	 * @param 	array	$limits			Array of limit conditions
	 */

	public function findWhereInAnd( $columns = array( ), $column, $values, $conditions = array( ), $orders = array( ), $limits = array( ) )
	{
		$this->validateColumns( $columns );

		$fild = $column;
		$this->validateColumns( $fild );

		$Conditions = $this->getConditionQuery( $conditions, FALSE, FALSE );

		$orders = $this->getOrderCondition( $orders );
		$limits = $this->getLimitCondition( $limits );
		for ( $i = 0; $i < count( $values ); $i++ )
			$values[ $i ] = $this->secureValue( $values[ $i ] );

		if ( $Conditions )
			$Conditions .= ' AND ';
		$query = 'SELECT ' . implode( ', ', $columns ) . ' FROM ' . $this->getTables( ) . ' WHERE ' . $Conditions . $column . ' IN (' . implode( ', ', $values ) . ') ' . $orders . $limits;

		$resultSet = $this->dbc->query( $query );
		if ( $resultSet )
			return $resultSet->fetchAll( $this->getFetchMode( ) );
		return array( );
	}

	/**	saeid	10.10.2011
	 *	Setting focus on an index.
	 *	@access		public
	 *	@param		string		$column			Index column name
	 *	@param		int			$value			Index to focus on
	 *	@param		bool		$duplicate		If this variable is true, You can focus on a field a few times.
	 *	@return		void
	 */

	public function focusIndex( $column, $value, $duplicate = false )
	{

		if ( $this->getAlias( ) && !stripos( $column, '.' ) )
			$columnB = $this->getAlias( ) . '.' . $column;
		else
			$columnB = $column;
		//  set Focus

		if ( !in_array( $columnB, $this->indices ) && $columnB != $this->primaryKey )
		{ //  check column name
			if ( $this->isJoin( ) )
			{
				$sw = false;
				foreach ( $this->JoinList as $join )
				{
					$columnB = str_replace( $join[ 'ObjTableReader' ]->getAlias( true ) . '.', '', $column );
					if ( !$join[ 'ObjTableReader' ]->getAlias() )
					{
						if ( in_array( $columnB, $join[ 'ObjTableReader' ]->indices ) || $columnB == $join[ 'ObjTableReader' ]->primaryKey )
						{
							$columnB = $join[ 'ObjTableReader' ]->getAlias( true ) . '.' . $columnB;
							$sw = true;
							break;
						}
					}else{
						$columnB = $join[ 'ObjTableReader' ]->getAlias().'.'.$columnB;
						if(in_array( $columnB, $join[ 'ObjTableReader' ]->indices ) || $columnB == $join[ 'ObjTableReader' ]->primaryKey){
							$sw = true;
							break;
						}
					}
				}
				if ( !$sw )
					throw new InvalidArgumentException( 'Column "' . $column . '" is neither an index nor primary key and cannot be focused');
			}
			else
				throw new InvalidArgumentException( 'Column "' . $column . '" is neither an index nor primary key and cannot be focused');
		}
		if ( !$duplicate )
		{
			if ( !is_null( $key = $this->array_search_column_Key( $columnB, $this->focusedIndices ) ) )
			{
				$this->focusedIndices[ $key ] = array(
						$columnB,
						$value
				); //  set Focus
				return;
			}
		}
		$this->focusedIndices[ ] = array(
				$columnB,
				$value
		); //  set Focus
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
		if ( $clearIndices )
			$this->focusedIndices = array( );
		$this->focusedIndices[ ] = array(
				$this->primaryKey,
				$id
		);
	}

	/**	saeid
	 *	Returns data of focused keys.
	 *	@access		public
	 *	@param		bool	$first		Extract first entry of result
	 *	@param		array	$orders		Associative array of orders
	 *	@param		array	$limits		Array of offset and limit
	 *	@return		array
	 */

	public function get( $first = TRUE, $orders = array( ), $limits = array( ) )
	{

		$this->validateFocus( );
		$data = array( );
		$conditions = $this->getConditionQuery( array( ) );
		$orders = $this->getOrderCondition( $orders );
		$limits = $this->getLimitCondition( $limits );
		$query = 'SELECT * FROM ' . $this->getTables( ) . ' WHERE ' . $conditions . $orders . $limits;
		$resultSet = $this->dbc->query( $query );
		if ( !$resultSet )
			return NULL;
		$resultList = $resultSet->fetchAll( $this->getFetchMode( ) );
		if ( $first && !$resultList )
			return NULL;
		if ( $first )
			return $resultList[ 0 ];
		return $resultList;
	}

	/**
	 *	Returns a list of all table columns.
	 *	@access		public
	 *	@return		array
	 */

	public function getColumns( )
	{
		return $this->columns;
	}

	/**	saeid
	 *	Builds and returns WHERE statement component.
	 *	@access		protected
	 *	@param		array		$conditions		Array of conditions
	 *	@param		bool		$usePrimary		Flag: use focused primary key
	 *	@param		bool		$useIndices		Flag: use focused indices
	 *	@return		string
	 */

	protected function getConditionQuery( $conditions, $usePrimary = TRUE, $useIndices = TRUE )
	{
		$inputconditions = $conditions;
		$new = array( );
		$alias = $this->getAlias( );
		foreach ( $this->columns as $column )
		{ //  iterate all columns
			if ( isset( $conditions[ $column ] ) ) //  if condition given
				$new[ ] = array(
						$column,
						$conditions[ $column ]
				);
			//  note condition pair		
			else if ( $alias && isset( $conditions[ substr( stristr( $column, '.' ), 1 ) ] ) )
				$new[ ] = array(
						$column,
						$conditions[ substr( stristr( $column, '.' ), 1 ) ]
				);
		}
		if ( $usePrimary && $this->isFocused( $this->primaryKey ) ) //  if using primary key & is focused primary
		{
			if ( !$this->array_search_column( $this->primaryKey, $new ) ) //  if primary key is not already in conditions
				$new = $this->getFocus( );
			//  note primary key pair
		}
		if ( $useIndices && count( $this->focusedIndices ) ) //  if using indices
		{
			foreach ( $this->focusedIndices as $index => $value ) //  iterate focused indices
				if ( $value[ 0 ] != $this->primaryKey ) //  skip primary key
					if ( !array_search( $value, $new ) ) //  if index column is not already in conditions
						$new[ ] = $value;
			//  note index pair
		}

		$conditions = array( );
		foreach ( $new as $column => $value ) //  iterate all noted Pairs
		{

			if ( is_array( $value[ 1 ] ) )
			{
				foreach ( $value[ 1 ] as $nr => $part )
					$value[ 1 ][ $nr ] = $this->realizeConditionQueryPart( $column, $part );
				$part = '(' . implode( ' OR ', $value[ 1 ] ) . ')';
			}
			else
				$part = $this->realizeConditionQueryPart( $value[ 0 ], $value[ 1 ] );
			$conditions[ ] = $part;

		}
		$conditions = implode( ' AND ', $conditions ); //  combine Conditions with AND
		if ( $this->JoinList )
		{
			foreach ( $this->JoinList as $Join )
			{
				if ( !$Join[ 'ObjTableReader' ]->getAlias( ) )
					$Join[ 'ObjTableReader' ]->setAlias( $Join[ 'ObjTableReader' ]->getTableName( ) );
				$joincondition = $Join[ 'ObjTableReader' ]->getConditionQuery( $inputconditions, FALSE, FALSE );
				if ( $joincondition )
				{
					if ( $conditions && $conditions != '' )
					{
						$conditions .= ' AND ';
					}
					$conditions .= $joincondition;
				}
			}
		}
		return $conditions;
	}

	/**
	 *	Returns reference the database connection.
	 *	@access		public
	 *	@return		Object
	 */

	public function &getDBConnection( )
	{
		return $this->dbc;
	}

	/**
	 *	Returns set fetch mode.
	 *	@access		public
	 *	@return		int			$fetchMode		Currently set fetch mode
	 */

	protected function getFetchMode( )
	{
		return $this->fetchMode;
	}

	/**
	 *	Returns current primary focus or index focuses.
	 *	@access		public
	 *	@return		array
	 */

	public function getFocus( )
	{
		return $this->focusedIndices;
	}

	/**
	 *	Returns all Indices of this Table.
	 *	@access		public
	 *	@return		array
	 */

	public function getIndices( )
	{
		return $this->indices;
	}

	/**
	 *	Builds and returns ORDER BY Statement Component.
	 *	@access		protected
	 *	@param		array		$limits			Array of Offset and Limit
	 *	@return		string
	 */

	protected function getLimitCondition( $limits = array( ) )
	{
		if ( !is_array( $limits ) )
			return;
		$limit = !isset( $limits[ 1 ] ) ? 0 : abs( $limits[ 1 ] );
		$offset = !isset( $limits[ 0 ] ) ? 0 : abs( $limits[ 0 ] );
		if ( $limit )
			return ' LIMIT ' . $limit . ' OFFSET ' . $offset;
	}

	/**
	 *	Builds and returns ORDER BY Statement Component.
	 *	@access		protected
	 *	@param		array		$orders			Associative Array with Orders
	 *	@return		string
	 */

	protected function getOrderCondition( $orders = array( ) )
	{
		$order = '';
		if ( is_array( $orders ) && count( $orders ) )
		{
			$list = array( );
			foreach ( $orders as $column => $direction )
				$list[ ] = $column . ' ' . strtoupper( $direction );
			$order = ' ORDER BY ' . implode( ', ', $list );
		}
		return $order;
	}

	/**
	 *	Returns the name of the primary key column.
	 *	@access		public
	 *	@return		string
	 */

	public function getPrimaryKey( )
	{
		return $this->primaryKey;
	}

	/**
	 *	
	 *	Returns the name of the table.
	 *	@access		public
	 *	@return		string
	 */

	public function getTableName( )
	{
		return $this->tableName;
	}

	/**
	 *	@author		Saeid
	 *	
	 *	Returns the name of the tables in Join list.
	 *	@access		public
	 *	@return		string
	 */

	public function getTables( $withLink = TRUE )
	{
		$tablesNames = '';
		if ( !$this->alias )
			$tablesNames = $this->getTableName( );
		else
			$tablesNames = $this->getTableName( ) . ' AS ' . $this->getAlias( );
		if ( $this->JoinList )
		{
			foreach ( $this->JoinList as $join )
			{
				if ( $withLink )
					$tablesNames .= ' ' . $join[ 'Mode' ] . ' ';
				else
					$tablesNames .= ' , ';
				$ObjTableReader = $join[ 'ObjTableReader' ];
				$tablesNames .= $ObjTableReader->getTableName( );
				if ( $join[ 'ObjTableReader' ]->getAlias( ) )
				{
					$tablesNames .= ' AS ' . $ObjTableReader->getAlias( );
				}
				if ( $withLink )
					$tablesNames .= ' ON ' . $this->getTableLink( $ObjTableReader, $join[ 'TableLink' ] );

			}
		}
		return $tablesNames;
	}

	/**
	 *	@author		Saeid
	 *	
	 *	Convert the TableLink array to string .
	 *	@access		protected
	 *	@return		string
	 */

	protected function getTableLink( $ObjTableReader, $TableLink )
	{
		return $TableLink[ 0 ] . ' = ' . $TableLink[ 1 ];
	}

	protected function getTableLinks( )
	{}

	/**
	 *	@author		Saeid
	 *	
	 *	get Aliasname , if ist Join and dont have Alisa get TableName .
	 *	@access		protected
	 *	@return		string 
	 */

	public function getAlias( $join = false )
	{
		if ( $this->alias )
			return $this->alias;
		else if ( $this->JoinList || $join )
			return $this->getTableName( );
		else
			return NULL;
	}

	/** 
	 * @author		Saeid	10.Okt.2011
	 * 
	 * set Alisa name for table
	 * @param 	string 	$AliasName	
	 */

	public function setAlias( $AliasName )
	{
		$alias = $this->getAlias( );

		foreach ( $this->columns as $key => $value )
		{
			$this->columns[ $key ] = $alias ? str_replace( $alias . '.', $AliasName . '.', $value ) : $AliasName . '.' . $value;
		}

		foreach ( $this->indices as $key => $value )
		{
			$this->indices[ $key ] = $alias ? str_replace( $alias . '.', $AliasName . '.', $value ) : $AliasName . '.' . $value;
		}
		$this->primaryKey = $alias ? str_replace( $alias . '.', $AliasName . '.', $this->primaryKey ) : $AliasName . '.' . $this->primaryKey;

		$Bobj = array( );
		foreach ( $this->focusedIndices as $key => $value )
		{
			$Bobj[ ] = array(
					$alias ? str_replace( $alias . '.', $AliasName . '.', $value[ 0 ] ) : $AliasName . '.' . $value[ 0 ],
					$value[ 1 ]
			);
		}
		$this->focusedIndices = $Bobj;
		if ( $this->JoinList )
			foreach ( $this->JoinList as $key => $Join )
			{
				$this->JoinList[ $key ][ 'TableLink' ][ 0 ] = str_replace( $alias . '.', $AliasName . '.', $Join[ 'TableLink' ][ 0 ] );
			}
		$this->alias = $AliasName;

	}

	/**
	 * @author		Saeid
	 * 
	 * delete Alias for table
	 */

	public function deAlias( )
	{
		if ( $this->JoinList || !$this->getAlias( ) )
			return FALSE;

		$alias = $this->getAlias( );

		foreach ( $this->columns as $key => $value )
		{
			$this->columns[ $key ] = str_replace( $alias . '.', '', $value );
		}

		foreach ( $this->indices as $key => $value )
		{
			$this->indices[ $key ] = str_replace( $alias . '.', '', $value );
		}
		$this->primaryKey = str_replace( $alias . '.', '', $this->primaryKey );

		$Bobj = array( );
		foreach ( $this->focusedIndices as $key => $value )
		{
			$Bobj[ $key ] = array(
					str_replace( $alias . '.', '', $value[ 0 ] ),
					$value[ 1 ]
			);
		}
		$this->focusedIndices = $Bobj;

		$this->alias = NULL;
	}

	/**	saeid
	 *	Indicates wheter the focus on a index (including primary key) is set.
	 *	@access		public
	 *	@return		string
	 */

	public function isFocused( $index = NULL )
	{
		if ( $this->JoinList )
			foreach ( $this->JoinList as $Join )
				if ( count( $Join[ 'ObjTableReader' ]->focusedIndices ) )
					if ( !$index || array_key_exists( $index, $Join[ 'ObjTableReader' ]->focusedIndices ) )
						return true;

		if ( !count( $this->focusedIndices ) )
			return FALSE;
		if ( $index && !$this->array_search_column( $index, $this->focusedIndices ) )
			return FALSE;
		return TRUE;
	}

	/**
	 * @author	saeid
	 * @since	10.10.2011
	 * Searches the array for a given value in the first column and returns true if successful.
	 * @param 	string 	$value		The searched value. 
	 * @param 	array 	$new	  	haystack array
	 * @return	bool 	
	 */

	private function array_search_column( $value, $new )
	{
		if ( $new && $value )
			foreach ( $new as $values )
			{
				if ( $values[ 0 ] == $value )
					return true;
			}
		return false;
	}

	/**
	 * @author	saeid
	 * @since	10.10.2011
	 * Searches the array for a given value in the first column and returns key if successful.
	 * @param 	string 	$value		The searched value. 
	 * @param 	array 	$new	  	haystack array
	 * @return	string				key
	 */

	private function array_search_column_Key( $value, $new )
	{
		if ( $new && $value )
			foreach ( $new as $key => $values )
			{
				if ( $values[ 0 ] == $value )
					return $key;
			}
		return NULL;
	}

	protected function realizeConditionQueryPart( $column, $value )
	{
		$pattern = '/^(<=|>=|<|>|!=)(.+)/';
		if ( preg_match( '/^%/', $value ) || preg_match( '/%$/', $value ) )
		{
			$operation = ' LIKE ';
			$value = $this->secureValue( $value );
		}
		else if ( preg_match( $pattern, $value, $result ) )
		{
			$matches = array( );
			preg_match_all( $pattern, $value, $matches );
			$operation = ' ' . $matches[ 1 ][ 0 ] . ' ';
			$value = $this->secureValue( $matches[ 2 ][ 0 ] );
		}
		else
		{
			if ( strtolower( $value ) == 'is null' || strtolower( $value ) == 'is not null' )
				$operation = ' ';
			else if ( $value === NULL )
			{
				$operation = ' is ';
				$value = 'NULL';
			}
			else
			{
				$operation = ' = ';
				$value = $this->secureValue( $value );
			}
		}
		//saeid:{
		if ( $this->getAlias( ) )
		{
			$column = str_replace( '.', '.`', $column ) . '`';
		}
		else
			$column = '`' . $column . '`';
		//}
		return $column . $operation . $value;
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
		if ( $value === NULL )
			return "NULL";
		$value = $this->dbc->quote( $value );
		return $value;
	}

	/**	saeid
	 *	Setting all columns of the table.
	 *	@access		public
	 *	@param		array		$columns	List of table columns
	 *	@return		void
	 *	@throws		Exception
	 */

	public function setColumns( $columns )
	{
		if ( !( is_array( $columns ) && count( $columns ) ) )
			throw new InvalidArgumentException( 'Column array must not be empty');
		if ( $Alias = $this->getAlias( ) )
			foreach ( $columns as $key => $value )
			{
				if ( !stripos( $value, '.' ) )
					$columns[ $key ] = $Alias . '.' . $value;
				else
					$columns[ $key ] = $value;
			}

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
		if ( !is_object( $dbc ) )
			throw new InvalidArgumentException( 'Database connection resource must be an object');
		if ( !is_a( $dbc, 'PDO' ) )
			throw new InvalidArgumentException( 'Database connection resource must be a direct or inherited PDO object');
		$this->dbc = $dbc;
	}

	/**
	 *	Sets fetch mode.
	 *	Mode is a mandatory integer representing a PDO fetch mode.
	 *	@access		public
	 *	@param		int			$mode			PDO fetch mode
	 *	@see		http://www.php.net/manual/en/pdo.constants.php
	 *	@return		void
	 */

	public function setFetchMode( $mode )
	{
		$this->fetchMode = $mode;
	}

	/**	saeid
	 *	Setting all indices of this table.
	 *	@access		public
	 *	@param		array		$indices		List of table indices
	 *	@return		bool
	 */

	public function setIndices( $indices )
	{
		$Alias = $this->getAlias( );
		$this->indices = array( );
		foreach ( $indices as $index )
		{
			$this->validateColumns( $index );
			if ( !$index || $index == array( ) )
				throw new InvalidArgumentException( 'Column "' . $index[ 0 ] . '" is not existing in table "' . $this->tableName . '" and cannot be an index');
			$index = $index[ 0 ];

			if ( $index === $this->primaryKey || $this->getAlias( ) . '.' . $index === $this->primaryKey )
				throw new InvalidArgumentException( 'Column "' . $index . '" is already primary key and cannot be an index');
			if ( $Alias && !stripos( $index, '.' ) )
				$this->indices[ ] = $Alias . '.' . $index;
			else
				$this->indices[ ] = $index;
		}
		array_unique( $this->indices );
	}

	/**	saeid
	 *	Setting the name of the primary key.
	 *	@access		public
	 *	@param		string		$column		Pimary key column of this table
	 *	@return		void
	 */

	public function setPrimaryKey( $column )
	{

		if ( !in_array( $column, $this->columns ) && !in_array( $this->getAlias( ) . '.' . $column, $this->columns ) )
		{
			throw new InvalidArgumentException( 'Column "' . $column . '" is not existing and can not be primary key');
		}
		if ( !$this->getAlias( ) || stripos( $column, '.' ) )
			$this->primaryKey = $column;
		else
			$this->primaryKey = $this->getAlias( ) . '.' . $column;

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

	protected function validateFocus( )
	{
		if ( !$this->isFocused( ) )
			throw new RuntimeException( 'No Primary Key or Index focused for Table "' . $this->tableName . '"');
	}

	/**	saeid
	 *	Checks columns names for querying methods (find,get), sets wildcard if empty or throws an exception if inacceptable.
	 *	@access		protected
	 *	@param		mixed		$columns			String or array of column names to validate
	 *	@return		void
	 */

	protected function validateColumns( &$columns )
	{
		if ( is_string( $columns ) && $columns )
			$columns = array(
					$columns
			);
		else if ( ( is_array( $columns ) && !count( $columns ) ) || ( $columns === NULL || $columns == FALSE ) )
		{
			if ( $this->JoinList )
			{
				$columns[ ] = $this->getAlias( ) . '.*';
				foreach ( $this->JoinList as $Join )
				{
					$columns[ ] = $Join[ 'ObjTableReader' ]->getAlias( true ) . '.*';
				}
			}
			else
				$columns = $this->getAlias( ) ? array(
						$this->getAlias( ) . '.*'
				) : array(
						'*'
				);
		}

		if ( !is_array( $columns ) )
			throw new InvalidArgumentException( 'Column keys must be an array of column names, a column name string or "*"');

		foreach ( $columns as $key => $column )
		{
			if ( $column != $this->getAlias( ) . '.*' && !in_array( $column, $this->columns ) && $column != '*' && !in_array( $this->getAlias( ) . '.' . $column, $this->columns ) )
			{
				$sw = false;
				if ( $this->JoinList )
					foreach ( $this->JoinList as $Join )
					{
						if ( $column == $Join[ 'ObjTableReader' ]->getAlias( true ) . '.*' )
						{
							$sw = true;
							break;
						}
						else if ( in_array( str_replace( $Join[ 'ObjTableReader' ]->getAlias( true ) . '.', '', $column ), $Join[ 'ObjTableReader' ]->columns ) )
						{
							if ( !stripos( $column, '.' ) )
							{
								$columns[ $key ] = $Join[ 'ObjTableReader' ]->getAlias( true ) . '.' . $column;
							}
							$sw = true;
							break;
						}
						else if ( $Join[ 'ObjTableReader' ]->getAlias( ) )
						{
							if ( !stripos( $column, '.' ) )
							{
								$column = $Join[ 'ObjTableReader' ]->getAlias( ) . '.' . $column;
							}
							if ( in_array( $column, $Join[ 'ObjTableReader' ]->columns ) )
							{
								$columns[ $key ] = $column;
								$sw = true;
								break;
							}
						}
					}
				if ( !$sw )
				{
					throw new InvalidArgumentException( 'Column key "' . $column . '" is not a valid column of tables Join');
				}
			}
			elseif ( in_array( $this->getAlias( ) . '.' . $column, $this->columns ) )
			{
				$columns[ $key ] = $this->getAlias( ) . '.' . $column;
			}

		}
	}
}
?>
