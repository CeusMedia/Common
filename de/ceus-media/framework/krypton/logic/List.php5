<?php
import( 'de.ceus-media.database.StatementBuilder' );
/**
 *	Logic for Data Lists from Database.
 *	@package		mv2.logic
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			23.02.2007
 *	@version		0.1
 */
/**
 *	Logic for Data Lists from Database.
 *	@package		mv2.logic
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			23.02.2007
 *	@version		0.1
 */
class Framework_Krypton_Logic_List
{
	/**	@var	StatementBuilder	$builder		Statement Builder Object */
	private $builder	= null;
	/**	@var	StatementCollection	$collection		Statement Collection Object */
	private $collection	= null;
	
	/**
	 *	Constructor, build Statement Builder and Statement Collection.
	 * 	@access		public
	 *	@param		string		$collection		Class Name of Statement Collection to use
	 *	@param		string		$tablePrefix	Prefix of Tables
	 *	@return		void
	 */
	public function __construct( $collection, $tablePrefix = "" )
	{
		$this->builder		= new Database_StatementBuilder( $tablePrefix );
		if( !class_exists( $collection ) )
			throw new RuntimeException( 'Collection Class "'.$collection.'" was not been loaded.' );
		$this->collection	= new $collection( $this->builder );
	}

	/**
	 *	Adds a Component by Name and Arguments.
	 * 	@access		public
	 *	@param		string		$name			Name of Component
	 *	@param		array		$arguments		Component Arguments
	 *	@return		void
	 */
	public function addComponent( $name, $arguments )
	{
		$this->collection->addComponent( $name, $arguments );
	}

	/**
	 *	Returns Rowcount of Statement Result.
	 * 	@access		public
	 *	@param		Connection	$dbc			Database Connection
	 *	@param		bool		$verbose		Show Query Information
	 *	@return		int
	 */
	public function getCount( $dbc, $verbose = false )
	{
		$query	= $this->builder->buildCountStatement();
		try{
			$result	= $dbc->query( $query );
			$result	= $result->fetch( false );
			$count	= $result['rowcount'];
			if( $verbose )
			{
				remark( "Query: ".$query );
				remark( "Count: ".$count );
			}
			return $count;
		}
		catch( Framework_Krypton_Exception_SQL $e )
		{
			die( $e->getMessage().": ".$e->getError() );
		}
	}
	
	/**
	 *	Returns resulting List of Statement.
	 * 	@access		public
	 *	@param		Connection	$dbc			Database Connection
	 *	@param		bool		$verbose		Show Query Information
	 *	@return		array
	 */
	public function getList( $dbc, $verbose = false )
	{
		$list	= array();
		$query	= $this->builder->buildStatement();
		if( $verbose )
		{
			remark( "Query: ".$query );
		}
		$result	= $dbc->query( $query );
		while( $entry = $result->fetch() )
		{
			$list[]	= $entry;
		}
		return $list;	
	}
}
?>