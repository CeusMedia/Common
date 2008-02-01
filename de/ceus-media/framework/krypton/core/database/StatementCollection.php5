<?php
/**
 *	Collection of Statement Components for Statement Builder.
 *	@package		framework.krypton.core.database 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.04
 *	@version		0.6
 */
/**
 *	Collection of Statement Components for Statement Builder.
 *	@package		mv2.core.database 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@author			Michael Martin <Michael.Martin@CeuS-Media.de>
 *	@since			26.11.04
 *	@version		0.6
 *	@deprecated		use Database_StatementCollection instead
 *	@todo			to be deleted
 */
class Framework_Krypton_Core_Database_StatementCollection
{
	/**	@var		StatementBuilder	$builder		Reference to a Statement Builder Object */		
	private $builder;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		StatementBuilder	$builder		Reference to a Statement Builder Object
	 *	@return		void
	 */
	public function __construct( &$builder )
	{
		$this->builder =& $builder;
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
	 *	@param		array	$data		Pairs of Offset and Limit
	 *	@return		array
	 */
	public function Limit( $data )
	{
		$offset	= 0;
		$rows	= 10;
		if( isset( $data[0] ) && (int)$data[0] && $data[0] == abs( $data[0] ) )
			$offset	= (int)$data[0];
		if( isset( $data[1] ) && (int)$data[1] && $data[1] == abs( $data[1] ) )
			$rows	= (int)$data[1];
		$this->builder->setLimit( $rows, $offset );	
		return array ();
	}

	/**
	 *	Base Statement Component for Ordering.
	 *	@access		public
	 *	@param		array		$data		Pair of Column and Direction
	 *	@return		array
	 */
	public function Order( $data )
	{
		$column		= $data[0];
		$direction	= strtoupper( $data[1] );
		$this->builder->addOrder( $column, $direction );	
		return array();
	}
}
?>
