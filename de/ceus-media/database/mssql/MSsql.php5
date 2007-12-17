<?php
import ("de.ceus-media.database.mssql.MSsqlResult");
import ("de.ceus-media.database.mssql.MSsqlRow");
import ("de.ceus-media.file.log.Writer");
/**
 *	MSsql Connection
 *
 *	Is a MSsql Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		Object
 *	@uses			MySQLResult
 *	@uses			MySQLRow
 *	@uses			File_Log_Writer
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
/**
 *	MSsql Connection
 *
 *	Is a MSsql Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		Object
 *	@uses			MySQLResult
 *	@uses			MySQLRow
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 *	@todo			Dokumentation beenden
 *	@todo			Testen
 */
class MSsql
{
	var $_dbc;
	var $_database;
	var $_connected;
	var $_insert_id;
	var $countTime;
	var $countQueries;

	public function __construct ()
	{
		$this->_connected = false;
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
			if ($this->_dbc = mssql_connect ($host, $user, $pass))
			{
				if ($this->Execute ("use $database")) return true;
			}
		}
		else if ($type == "pconnect")
		{
			if ($this->_dbc = mssql_pconnect ($host, $user, $pass))
			{
				if ($this->Execute ("use $database")) return true;
			}
		}
		return false;
	}

	function Execute ($query)
	{
		$result = new MySQLResult ();
		if ($query)
		{
			$this->countQueries++;
			$start = $this->getMicroTime ();
			if (eregi("^( |\t)*(INSERT)", $query))
			{
				if (mssql_query ($query, $this->_dbc))
				{
					$this->_insert_id = (int) mssql_insert_id ($this->_dbc);
					$result = $this->_insert_id;
				}
			}
			else if (eregi("^( |\t)*(SELECT)", $query))
			{
				if ($q = mssql_query ($query, $this->_dbc))
				{
					while ($d = mssql_fetch_array ($q))
					{
						$row = new MySQLRow ();
						foreach ($d as $key => $value) $row->$key = $value;
						$result->objects[] = $row;
					}
				}
			}
			else
			{
				$result = mssql_query ($query, $this->_dbc);
			}
			if (mssql_errno()) $this->handleError (mssql_errno(), mssql_error(), $query);
			$this->countTime += $this->getTimeDifference ($start);
			return $result;
		}
	}

	function getMicroTime ($end = false)
	{
		$arrTime = explode(" ", microtime());
		$time = (doubleval($arrTime[0]) + $arrTime[1]) * 1000;
		//echo sprintf ("%1.".$this->decimal_places."f", $time).($end?"<br>":" - ");
		return $time;
	}

	function getTimeDifference ($start)
	{
		return sprintf ("%1.4f", $this->getMicroTime (true) - $start);
	}

	function handleError ($error_code, $error_msg, $query)
	{
	//	$mandant	=& $this->ref->getRef ("mandant");
	//	$debug		=& $this->ref->getRef ("debug");

		$log = new File_Log_Writer ("db_error.log");
		$log->note ("(".date("Y-m-d H:i:s").") mysql error: [".$error_code.": ".$error_msg." in EXECUTE (\"".$query."\")");
	//	$debug->abort ("Eine Datenbankabfrage war fehlerhaft.", $query, $error_msg);
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
		$q_tab = mssql_list_tables ($this->_database, $this->_dbc);
		$i=0;
		while ($i < mssql_num_rows ($q_tab))
		{
			$tables[] = mssql_tablename ($q_tab, $i);
			$i++;
		}
		return $tables;
	}

	function PConnect ($host, $user, $pass, $database)
	{
		return $this->connectDatabase ("pconnect", $host, $user, $pass, $database);
	}
}
?>