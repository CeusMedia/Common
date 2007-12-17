<?php
import( 'de.ceus-media.database.mysql.Connection' );
/**
 *	Database_MySQL_Connection Connection with Transaction Support.
 *	@package		database.mysql
 *	@extends		Database_MySQL_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.5
 */
/**
 *	Database_MySQL_Connection Connection with Transaction Support.
 *	@package		database.mysql
 *	@extends		Database_MySQL_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.5
 */
class Database_MySQL_ConnectionWithTransactions extends Database_MySQL_Connection
{
	/**	@var	int		$openTransactions	Counter for open Transactions */	
	var $openTransactions = 0;

	/**
	 *	Opens a Transaction and sets auto commission.
	 *	@access		public
	 *	@param		bool		$autoCommit		Flag for setting auto commission
	 *	@return		void
	 */
	public function start( $autoCommit = 1 )
	{
		$this->openTransactions ++;
		if( $this->openTransactions == 1 )
		{
			if( $autoCommit )
			{
				$query = "SET AUTOCOMMIT=0";
			}
			$this->Execute( $query );
			$query = "START TRANSACTION";
			$this->Execute ($query);
		}
	}

	/**
	 *	Commits all modifications of Transaction.
	 *	@access		public
	 *	@param		bool		$autoCommit		Flag for setting auto commission
	 *	@return		void
	 */
	public function commit( $autoCommit = 1 )
	{
		if( $this->openTransactions == 1 )
		{
			$query = "COMMIT";
			$this->Execute( $query );
			if( $autoCommit )
				$this->Execute( "SET AUTOCOMMIT=1" );
		}
		$this->openTransactions--;
		if( $this->openTransactions < 0 )
			$this->openTransactions = 0;
	}

	/**
	 *	Cancels Transaction by rolling back all modifications.
	 *	@access		public
	 *	@param		bool		$autoCommit		Flag for setting auto commission
	 *	@return		bool
	 */
	public function rollback( $autoCommit = 1 )
	{
		if( $this->openTransactions == 0 )
			return false
		$query = "ROLLBACK";
		$this->Execute( $query );
		$this->openTransactions = 0;
		if( $autoCommit )
			$this->Execute( "SET AUTOCOMMIT=1" );
		return true;
	}
}
?>