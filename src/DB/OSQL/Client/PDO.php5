<?php
class DB_OSQL_Client_PDO extends DB_OSQL_Client_Abstract
{
	protected $fetchMode;
	public static $defaultFetchMode	= PDO::FETCH_OBJ;

	public function __construct( $dbc )
	{
		parent::__construct( $dbc );
		$this->setFetchMode( self::$defaultFetchMode );
	}

	public function execute( DB_OSQL_Query $query )
	{
		$clock	= new Alg_Time_Clock();
		$parts	= $query->render();
		$query->timeRender	= $clock->stop( 6, 0 );

		$clock->start();
		$stmt	= $this->dbc->prepare( $parts[0] );
		foreach( $parts[1] as $name => $parameter )
			$stmt->bindParam( $name, $parameter['value'], $parameter['type'] );
		$query->timePrepare	= $clock->stop( 6, 0 );

		$clock->start();
		$result	= $stmt->execute();
		if( !$result )
		{
			$info	= $stmt->errorInfo();
			throw new Exception( $info[2], $info[1] );
		}
		$query->timeExecute	= $clock->stop( 6, 0 );

		if( $query instanceof DB_OSQL_Query_Select )
			return $stmt->fetchAll( $this->fetchMode );
		return $result;
	}

	public function getLastInsertId()
	{
		return $this->dbc->lastInsertId();
	}

	public function setFetchMode( $mode )
	{
		$this->fetchMode	= $mode;
	}
}
?>