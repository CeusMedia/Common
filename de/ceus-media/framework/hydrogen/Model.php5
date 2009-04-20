<?php
/**
 *	Abstract Model Class of Framework Hydrogen.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		framework.hydrogen
 *	@uses			Database_TableWriter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		0.4
 */
import( 'de.ceus-media.database.TableWriter' );
/**
 *	Abstract Model Class of Framework Hydrogen.
 *	@package		framework.hydrogen
 *	@uses			Database_TableWriter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.09.2006
 *	@version		0.4
 */
class Framework_Hydrogen_Model
{
	/**	@var		string						$name			Name of Model */
	var $name		= "";
	/**	@var		array						$field			Array of Table Field */
	var $fields		= array();
	/**	@var		array						$name			Array of foreign Keys of Table */
 	var $foreign_keys	= array();
	/**	@var		string						$primary_key	Primary Key of Table*/
	var $primary_key	= "";
	/**	@var		Database_TableWriter		$table			TableWriter for accessing Database Table */
	var $table;
	/**	@var		Database_MySQL_Connection	$dbc			Database Connection  */
	var $_dbc;
	/**	@var		string						$_prefix		Table Prefix */
	var $_prefix;


	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Framework_Hydrogen_Framework	$application		Instance of Framework
	 *	@param		int								$id				ID to focus on
	 *	@return		void
	 */
	public function __construct( $application, $id = false )
	{
		$this->setEnv( $application );
		$this->table	= new Database_TableWriter( $this->_dbc, $this->_table, $this->fields, $this->primary_key, $id );
		$this->table->setForeignKeys( $this->foreign_keys );
	}
	
	//  --  PUBLIC METHODS  --  //
	/**
	 *	Returns Data of single Line by ID.
	 *	@access		public
	 *	@param		array		$data			Data to add to Table
	 *	@return		int
	 */
	public function add( $data )
	{
		$id	= $this->table->addData( $data );
		return $id;
	}
	
	/**
	 *	Returns Data of single Line by ID.
	 *	@access		public
	 *	@param		int			$id				ID to focus on
	 *	@param		array		$data			Data to edit
	 *	@return		bool
	 */
	public function edit( $id, $data )
	{
		$this->table->focusPrimary( $id );
		$result	= false;
		if( count( $this->table->getData() ) )
		{
			$this->table->modifyData( $data );
			$result	= true;
		}
		$this->table->defocus();
		return $result;
	}
	
	/**
	 *	Returns Data of single Line by ID.
	 *	@access		public
	 *	@param		int			$id				ID to focus on
	 *	@return		bool
	 */
	public function remove( $id )
	{
		$this->table->focusPrimary( $id );
		$result	= false;
		if( count( $this->table->getData() ) )
		{
			$this->table->deleteData();
			$result	= true;
		}
		$this->table->defocus();
		return $result;
	}
	
	/**
	 *	Returns Data of single Line by ID.
	 *	@access		public
	 *	@param		int			$id				ID to focus on
	 *	@param		string		$field			Single Field to return
	 *	@return		mixed
	 */
	public function get( $id, $field = "" )
	{
		$this->table->focusPrimary( $id );
		$data	= $this->table->getData( true );
		$this->table->defocus();
		if( $field )
			return $data[$field];
		return $data;
	}
	
	/**
	 *	Returns Data of all Lines.
	 *	@access		public
	 *	@param		array		$conditions		Array of Conditions to include in SQL Query
	 *	@param		array		$orders			Array of Orders to include in SQL Query
	 *	@param		array		$limits			Array of Limits to include in SQL Query
	 *	@return		array
	 */
	public function getAll( $conditions = array(), $orders = array(), $limits = array() )
	{
		$data	= $this->table->getAllData( array(), $conditions, $orders, $limits );
		return $data;
	}

	/**
	 *	Returns Data of all Lines selected by Foreign Key.
	 *	@access		public
	 *	@param		string		$key			Field Name of Foreign Key
	 *	@param		string		$value			Value of Foreign Key
	 *	@param		array		$conditions		Array of Conditions to include in SQL Query
	 *	@param		array		$orders			Array of Orders to include in SQL Query
	 *	@param		array		$limits			Array of Limits to include in SQL Query
	 *	@return		array
	 */
	public function getAllByForeignKey( $key, $value, $conditions = array(), $orders = array(), $limits = array() )
	{
		$this->table->focusForeign( $key, $value );
		$data	= $this->table->getData( array(), $conditions, $orders, $limits );
		$this->table->defocus();
		return $data;
	}

	/**
	 *	Returns Data of all Lines selected by Foreign Keys.
	 *	@access		public
	 *	@param		array		$keys			Array of Foreign Keys
	 *	@param		array		$conditions		Array of Conditions to include in SQL Query
	 *	@param		array		$orders			Array of Orders to include in SQL Query
	 *	@param		array		$limits			Array of Limits to include in SQL Query
	 *	@return		array
	 */
	public function getAllByForeignKeys( $keys = array(), $conditions = array(), $orders = array(), $limits = array() )
	{
		foreach( $keys as $key => $value )
			$this->table->focusForeign( $key, $value );
		$data	= $this->table->getData( array(), $conditions, $orders, $limits );
		$this->table->defocus();
		return $data;
	}

	/**
	 *	Returns Data of single Line by ID selected by Foreign Key.
	 *	@access		public
	 *	@param		string		$key			Field Name of Foreign Key
	 *	@param		string		$value			Value of Foreign Key
	 *	@param		string		$field			Single Field to return
	 *	@return		mixed
	 */
	public function getByForeignKey( $key, $value, $field = "" )
	{
		$this->table->focusForeign( $key, $value );
		$data	= $this->table->getData( true );
		$this->table->defocus();
		if( $field )
			return $data[$field];
		return $data;
	}
	
	/**
	 *	Returns Data of single Line selected by Foreign Keys.
	 *	@access		public
	 *	@param		array		$keys			Array of Foreign Keys
	 *	@param		string		$field			Single Field to return
	 *	@return		mixed
	 */
	public function getByForeignKeys( $keys = array(), $field = "" )
	{
		foreach( $keys as $key => $value )
			$this->table->focusForeign( $key, $value );
		$data	= $this->table->getData( true );
		$this->table->defocus();
		if( $field )
			return $data[$field];
		return $data;
	}
	
	//  --  PRIVATE METHODS  --  //
	/**
	 *	Sets Environment of Controller by copying Framework Member Variables.
	 *	@access		protected
	 *	@param		Framework_Hydrogen_Framework	$application		Instance of Framework
	 *	@return		void
	 */
	protected function setEnv( $application )
	{
		$this->_prefix	= $application->config['config']['table_prefix'];
		$this->_dbc	= $application->dbc;
		$this->_table	= $this->_prefix.$this->name;
	}
}
?>