<?php
abstract class DB_OSQL_Client_Abstract
{
	public function __construct( $dbc )
	{
		$this->dbc	= $dbc;
	}

	public function select()
	{
		return new DB_OSQL_Query_Select();
	}

	public function update()
	{
		return new DB_OSQL_Query_Update();
	}

	public function delete()
	{
		return new DB_OSQL_Query_Delete();
	}
	
	abstract function execute( DB_OSQL_Query $query );
}
?>