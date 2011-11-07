<?php
class DB_OSQL_Connection extends DB_Connection
{
	public function select( $fields = NULL )
	{
		return new DB_OSQL_Query_Select( $fields );
	}
}
?>