<?php
/**
 *	Collection of Statement Components for Statement Builder.
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
 *	@package		Database 
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.11.04
 *	@version		$Id$
 */
/**
 *	Collection of Statement Components for Statement Builder.
 *	@category		cmClasses
 *	@package		Database 
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.11.04
 *	@version		$Id$
 */
class Database_StatementCollection
{
	/**	@var	StatementBuilder	$builder		Reference to a Statement Builder Object */		
	protected $builder;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		StatementBuilder	$builder	Reference to a Statement Builder Object
	 *	@return		void
	 */
	public function __construct( $builder )
	{
		$this->builder	= $builder;
	}

	/**
	 *	Add a parameterized Component to Statement Builder.
	 *	@access		public
	 *	@param		string		$name		Name of collected Statement Component
	 *	@param		array		$data		Parameters for Statement Component
	 *	@return		void
	 */
	public function addComponent( $name, $data = array() )
	{
		if( !method_exists( $this, $name ) )
			throw new BadMethodCallException( 'Component "'.$name.'" is not defined.' );
		$array = $this->$name( $data );
		if( count( $array ) )
		{
			if( isset( $array[0] ) )
				$this->builder->addKeys( $array[0] );
			if( isset( $array[1] ) )
				$this->builder->addTables( $array[1] );
			if( isset( $array[2] ) )
				$this->builder->addConditions( $array[2] );
			if( isset( $array[3] ) )
				$this->builder->addGroupings( $array[3] );
		}
	}

	/**
	 *	Adds Column and Direction to order by.
	 *	@access		public
	 *	@param		string		$column		Column to order by	
	 *	@param		string		$direction	Direction (ASC|DESC)
	 *	@return		void
	 */
	public function addOrder( $column, $direction )
	{
		$this->builder->addOrder( $column, $direction );	
	}

	/**
	 *	Add a parameterized Component to Statement Builder.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrefix()
	{
		return $this->builder->getPrefix();
	}

	/**
	 *	Base Statement Component for Offseting and Limiting.
	 *	@access		public
	 *	@param		array		$data		Pair of Offset and Limit
	 *	@return		array
	 *	@deprecated use setLimit and setOffset instead
	 */
	public function Limit( $data )
	{
		remark( "deprecated: ".__METHOD__.":".__LINE__ );
		if( !is_array( $data ) )
			throw new InvalidArgumentException( 'Limit must be given as List of Offset and Row Limit.' );
		$offset	= 0;
		$rows	= 10;
		if( isset( $data[0] ) && (int) $data[0] && $data[0] == abs( $data[0] ) )
			$offset	= (int) $data[0];
		if( isset( $data[1] ) && (int) $data[1] && $data[1] == abs( $data[1] ) )
			$rows	= (int) $data[1];
		$this->builder->setLimit( $rows );	
		$this->builder->setOffset( $offset );	
		return array();
	}

	/**
	 *	Base Statement Component for Ordering.
	 *	@access		public
	 *	@param		array		$data		Pair of Column and Direction
	 *	@return		array
	 */
	public function Order( $data )
	{
		remark( "deprecated: ".__METHOD__.":".__LINE__ );
		if( !is_array( $data ) )
			throw new InvalidArgumentException( 'Orders must be given as List of Column and Direction.' );
		$column		= $data[0];
		$direction	= strtoupper( $data[1] );
		$this->builder->addOrder( $column, $direction );	
		return array();
	}

	/**
	 *	Base Statement Component for Ordering.
	 *	@access		public
	 *	@param		string		$column		Column to order by
	 *	@param		string		$direction	Direction of Order (ASC|DESC)
	 *	@return		array
	 */
	public function orderBy( $column, $direction )
	{
		$this->builder->addOrder( $column, strtoupper( $direction ) );	
		return array();
	}

	/**
	 *	Base Statement Component Grouping.
	 *	@access		public
	 *	@param		string		$column		Column to group by
	 *	@return		array
	 */
	public function groupBy( $column )
	{
		$this->builder->addGrouping( $column );
	}

	/**
	 *	Set Rows to limit.
	 *	@access		public
	 *	@param		int			$rowCount		Rows to limit
	 *	@return		void
 	 */	
	public function setLimit( $rowCount )
	{
		$this->builder->setLimit( $rowCount );	
	}
	
	/**
	 *	Sets Offset to start at.
	 *	@access		public
	 *	@param		int			$offset			Offset to start at
	 *	@return		void
 	 */	
	public function setOffset( $offset )
	{
		$this->builder->setOffset( $offset );	
	}
}
?>
