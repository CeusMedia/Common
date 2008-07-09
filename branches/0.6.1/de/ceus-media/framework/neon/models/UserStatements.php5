<?php
import( 'de.ceus-media.database.StatementCollection' );
/**
 *	User Collection of SQL Statement Components.
 *	@package		framework.neon.models
 *	@extends		Database_StatementCollection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.03.2007
 *	@version		0.2
 */
/**
 *	User Collection of SQL Statement Components.
 *	@package		framework.neon.models
 *	@extends		Database_StatementCollection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.03.2007
 *	@version		0.2
 */
class Framework_Neon_Models_UserStatements extends Database_StatementCollection
{
	/**
	 *	Constructor, sets Base Component.
	 *	@access		public
	 *	@param		StatementBuilder	$statementBuilder		Instance of Statement Builder
	 *	@return		void
	 */
	public function __construct( $statementBuilder )
	{
		parent::__construct( $statementBuilder );
		$this->collectUser();
	}

	/**
	 *	Base Component.
	 *	@access		private
	 *	@return		void
	 */
	private function collectUser()
	{
		$keys	= array(
			"u.userId",
			"u.username",
			"u.email",
			"u.notify",
			"u.created",
			"u.modified",
		);
		$table	= "users as u";
		$this->builder->addKeys( $keys );
		$this->builder->addTable( $table );
	}
	
	/**
	 *	Username Component.
	 *	@access		public
	 *	@param		string		$username		Username to be found
	 *	@return		void
	 */
	public function withUsername( $username )
	{
		$condition	= "u.username LIKE '%".$username."%'";
		$this->builder->addCondition( $condition );
	}
	
	/**
	 *	Mail Component.
	 *	@access		public
	 *	@param		string		$email		Mail to be found
	 *	@return		void
	 */
	public function withEmail( $email )
	{
		$condition	= "u.email LIKE '%".$email."%'";
		$this->builder->addCondition( $condition );
	}
}
?>