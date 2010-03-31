<?php
class DB_OSQL_Client_PDO extends DB_OSQL_Client_Abstract
{
	public function getLastInsertId()
	{
		return $this->dbc->lastInsertId();
	}

	public function getStringFromQuery( $query )
	{
		$query	= $query->render();
		return $query[0];
	}

	public function execute( DB_OSQL_Query $query )
	{
		$parts	= $query->render();
		$stmt	= $this->dbc->prepare( $parts[0] );
		remark( 'Query Render Time ('.get_class( $query ).'): '.round( $parts[2] / 1000, 1 ).'ms' );
		foreach( $parts[1] as $name => $parameter )
			$stmt->bindParam( $name, $parameter['value'], $parameter['type'] );
		$clock	= new Alg_Time_Clock;
		$result	= $stmt->execute();
		if( !$result )
		{
			$info	= $stmt->errorInfo();
			throw new Exception( $info[2], $info[1] );
		}
		remark( 'DB Query Time: '.$clock->stop( 3, 1 ).'ms' );
		if( $query instanceof DB_OSQL_Query_Select )
			return $stmt->fetchAll( PDO::FETCH_ASSOC );
		return $result;
	}
}
?>