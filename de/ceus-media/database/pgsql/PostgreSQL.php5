<?php
import ("de.ceus-media.database.BaseConnection");
import ("de.ceus-media.database.pgsql.PostgreSQLResult");
import ("de.ceus-media.database.pgsql.PostgreSQLRow");
/**
 *	PostgreSQL Connection
 *
 *	Is a mySQL Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		BaseConnection
 *	@uses			PostgreSQLResult
 *	@uses			PostgreSQLRow
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 *	@todo			Dokumentation beenden
 */
/**
 *	PostgreSQL Connection
 *
 *	Is a mySQL Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		BaseConnection
 *	@uses			PostgreSQLResult
 *	@uses			PostgreSQLRow
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 *	@todo			Dokumentation beenden
 */
class PostgreSQL extends BaseConnection
{
	var $_dbc;
	var $_database;
	var $_insert_id;
	var $countTime;
	var $countQueries;

	public function __construct()
	{
		parent::__construct();
		$this->_insert_id = false;
	}

	function Connect ($host, $user, $pass, $database)
	{
		return $this->connectDatabase ("connect", $host, $user, $pass, $database);
	}

	function connectDatabase ($type, $host, $user, $pass, $database)
	{
		$this->_database = $database;
		if ($type == "connect")
		{
			if ($this->_dbc = mysql_connect ($host, $user, $pass))
			{
				if ($this->Execute ("use $database")) return true;
			}
		}
		else if ($type == "pconnect")
		{
			if ($this->_dbc = mysql_pconnect ($host, $user, $pass))
			{
				if ($this->Execute ("use $database")) return true;
			}
		}
		return false;
	}

	function Execute ($query)
	{
		$result = new PostgreSQLResult ();
		if ($query)
		{
			$this->countQueries++;
			$start = $this->getMicroTime ();
			if (eregi("^( |\t)*(INSERT)", $query))
			{
				if (mysql_query ($query, $this->_dbc))
				{
					$this->_insert_id = (int) mysql_insert_id ($this->_dbc);
					$result = $this->_insert_id;
				}
			}
			else if (eregi("^( |\t)*(SELECT)", $query))
			{
				if ($q = mysql_query ($query, $this->_dbc))
				{
					while ($d = mysql_fetch_array ($q))
					{
						$row = new PostgreSQLRow ();
						foreach ($d as $key => $value) $row->$key = $value;
						$result->objects[] = $row;
					}
				}
			}
			else
			{
				$result = mysql_query ($query, $this->_dbc);
			}
			if (mysql_errno()) $this->handleError (mysql_errno(), mysql_error(), $query);
			$this->countTime += $this->getTimeDifference ($start);
			return $result;
		}
	}

	function Insert_ID ()
	{
		return $this->_insert_id;
	}

	function InsertId ()
	{
		return $this->_insert_id;
	}
	
	function MetaTables ()
	{
		$q_tab = mysql_list_tables ($this->_database, $this->_dbc);
		$i=0;
		while ($i < mysql_num_rows ($q_tab))
		{
			$tables[] = mysql_tablename ($q_tab, $i);
			$i++;
		}
		return $tables;
	}

	function PConnect ($host, $user, $pass, $database)
	{
		return $this->connectDatabase ("pconnect", $host, $user, $pass, $database);
	}

	function affectedRows()
	{
		return mysql_affected_rows();
	}

	function Affected_Rows()
	{
		return $this->affectedRows();
	}
}
?>