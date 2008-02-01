<?php
/**
 *	Enhanced Statement for PDO Connections.
 *	@package		database.pdo
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
/**
 *	Enhanced Statement for PDO Connections.
 *	@package		database.pdo
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Database_PDO_Statement implements IteratorAggregate
{
	protected $PDOS;
	protected $PDOp;
	public function __construct( $PDOp, $PDOS )
	{
		$this->PDOp = $PDOp;
		$this->PDOS = $PDOS;
	}

	public function __call($func, $args)
	{
		return call_user_func_array( array( &$this->PDOS, $func ), $args );
	}

	public function bindColumn( $column, &$param, $type=NULL )
	{
		if( $type === NULL )
			$this->PDOS->bindColumn( $column, $param );
		else
			$this->PDOS->bindColumn( $column, $param, $type );
	}

	public function bindParam( $column, &$param, $type=NULL )
	{
		if( $type === NULL )
			$this->PDOS->bindParam( $column, $param );
		else
			$this->PDOS->bindParam( $column, $param, $type );
	}

	public function execute()
	{
		$this->PDOp->numExecutes++;
		$args = func_get_args();
		return call_user_func_array( array( &$this->PDOS, 'query' ), $args );
	}

	public function __get( $property )
	{
		return $this->PDOS->$property;
	}

	public function getIterator()
	{
		return $this->PDOS;
	}
}
?>