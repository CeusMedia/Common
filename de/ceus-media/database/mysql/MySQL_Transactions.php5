<?php
import( 'de.ceus-media.database.mysql.MySQL' );
/**
 *	MySQL Connection with Transaction Support.
 *	@package		database
 *	@subpackage		mysql
 *	@extends		MySQL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.4
 */
/**
 *	MySQL Connection with Transaction Support.
 *	@package		database
 *	@subpackage		mysql
 *	@extends		MySQL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.07.2005
 *	@version		0.4
 */
class MySQL_Transactions extends MySQL
{
	/**	@var	int	_open_transactions	Counter for open Transactions */	
	var $_open_transactions = 0;

	/**
	 *	Opens a Transaction and sets auto commission.
	 *	@access		public
	 *	@param		bool		auto_commit		Flag for setting auto commission
	 *	@return		void
	 */
	function startTrans( $auto_commit = 1 )
	{
		$this->_open_transactions ++;
		if( $this->_open_transactions == 1 )
		{
			if( $auto_commit )
			{
				$this->_transaction_opened = true;
				$query = "SET AUTOCOMMIT=0";
			}
			$this->Execute( $query );
			$query = "START TRANSACTION";
			$this->Execute ($query);
		}
	}

	/**
	 *	Commits all modifications ob Transaction.
	 *	@access		public
	 *	@param		bool		auto_commit		Flag for setting auto commission
	 *	@return		void
	 */
	function completeTrans( $auto_commit = 1 )
	{
		if( $this->_open_transactions == 1 )
		{
			$query = "COMMIT";
			$this->Execute( $query );
			if( $auto_commit )
				$this->Execute( "SET AUTOCOMMIT=1" );
		}
		$this->_open_transactions--;
		if( $this->_open_transactions < 0 )
			$this->_open_transactions = 0;
	}

	/**
	 *	Cancels Transaction by rolling back all modifications.
	 *	@access		public
	 *	@return		void
	 */
	function rollbackTrans( $auto_commit = 1 )
	{
		if( $this->_open_transactions > 0 )
		{
			$query = "ROLLBACK";
			$this->Execute( $query );
			$this->_open_transactions = 0;
			if( $auto_commit )
				$this->Execute( "SET AUTOCOMMIT=1" );
		}
	}
}
?>