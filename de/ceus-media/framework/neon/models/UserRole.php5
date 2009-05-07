<?php
import( 'de.ceus-media.framework.neon.Model' );
/**
 *	Data Model of User Roles.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
/**
 *	Data Model of User Roles.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
class Framework_Neon_Models_UserRole extends Framework_Neon_Model
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$userRoleId			ID of User Role
	 *	@return		void
	 */
	public function __construct( $userRoleId = false )
	{
		$tableName	= "userroles";
		$fieldList	= array (
			"userRoleId",
			"userId",
			"roleId",
			);
		$primaryKey	= "userRoleId";
		$foreignKeys	= array( "userId", "roleId" );

		parent::__construct( $tableName, $fieldList, $primaryKey, $userRoleId );
		$this->setForeignKeys ( $foreignKeys );
	}
}
?>