<?php
import( 'de.ceus-media.database.StatementBuilder' );
/**
 *	Logic for Data Lists from Database.
 *	@package		framework.krypton.logic
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			23.02.2007
 *	@version		0.1
 */
/**
 *	Logic for Data Lists from Database.
 *	@package		framework.krypton.logic
 *	@uses			Database_StatementBuilder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			23.02.2007
 *	@version		0.1
 */
class Framework_Krypton_Logic_List
{
	/**	@var		StatementBuilder	$builder		Statement Builder Object */
	private $builder	= NULL;
	/**	@var		StatementCollection	$collection		Statement Collection Object */
	private $collection	= NULL;
	
	/**
	 *	Constructor, build Statement Builder and Statement Collection.
	 * 	@access		public
	 *	@param		mixed		$collection		Class Name or Object of Statement Collection to use
	 *	@param		string		$tablePrefix	Prefix of Tables
	 *	@return		void
	 */
	public function __construct( $collection, $tablePrefix = "" )
	{
		$this->builder		= new Database_StatementBuilder( $tablePrefix );
		if( !is_object( $collection ) )
		{
			if( !class_exists( $collection ) )
				throw new Exception( 'Collection Class "'.$collection.'" was not been loaded.' );
			$collection	= new $collection( $this->builder );
		}
		$this->collection	= $collection;
	}

	/**
	 *	Adds a Component by Name and Arguments.
	 * 	@access		public
	 *	@param		string		$name			Name of Component
	 *	@param		array		$arguments		Component Arguments
	 *	@return		void
	 */
	public function __call( $component, $arguments )
	{
		if( !method_exists( $this->collection, $component ) )
			throw new BadMethodCallException( 'Collection Component "'.$component.'" is not defined.' );
		call_user_func_array( array( $this->collection, $component ), $arguments );
	}

	/**
	 *	Adds a Component by Name and Arguments.
	 * 	@access		public
	 *	@param		string		$component		Name of Component
	 *	@param		array		$arguments		Component Arguments
	 *	@return		void
	 */
	public function addComponent( $component, $arguments )
	{
		if( !method_exists( $this->collection, $component ) )
			throw new BadMethodCallException( 'Collection Component "'.$component.'" is not defined.' );
		call_user_func_array( array( $this->collection, $component ), $arguments );
	}

	/**
	 *	Returns Rowcount of Statement Result.
	 * 	@access		public
	 *	@param		Database_PDO_Connection	$dbc			Database Connection
	 *	@param		bool					$verbose		Show Query Information
	 *	@return		int
	 */
	public function getCount( $dbc, $verbose = FALSE )
	{
		$query	= $this->builder->buildCountStatement();
		try{
			$result	= $dbc->query( $query );
			$result	= $result->fetch( FALSE );
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
	 *	@param		Database_PDO_Connection	$dbc			Database Connection
	 *	@param		bool					$verbose		Show Query Information
	 *	@return		array
	 */
	public function getList( $dbc, $verbose = FALSE )
	{
		$list	= array();
		$query	= $this->builder->buildStatement();
		if( $verbose )
		{
			remark( "Query: ".$query );
		}
		$result	= $dbc->query( $query );
		return $result->fetchAll( PDO::FETCH_ASSOC );
	}
}
?>