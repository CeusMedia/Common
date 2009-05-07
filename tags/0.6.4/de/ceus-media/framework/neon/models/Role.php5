<?php
import( 'de.ceus-media.framework.neon.Model' );
import( 'de.ceus-media.framework.neon.models.RoleRight' );
/**
 *	Data Model of Roles.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@extends		RoleRight
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
/**
 *	Data Model of Roles.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@extends		RoleRight
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
class Framework_Neon_Models_Role extends Framework_Neon_Model
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		roleId		ID of Role
	 *	@return		void
	 */
	public function __construct( $roleId = false )
	{
		$tableName	= "roles";
		$fieldList	= array (
			"roleId",
			"title",
			"description",
			);
		$primaryKey	= "roleId";
		$foreignKeys	= array();

		parent::__construct( $tableName, $fieldList, $primaryKey, $roleId );
		$this->setForeignKeys ( $foreignKeys );
	}
	
	/**
	 *	Indicates whether a RoleRight is set by Object ID and Action ID.
	 *	@access		public
	 *	@param		int			roleId		Role ID
	 *	@param		string		object		Object Name
	 *	@param		string		action		Action Name
	 *	@return		bool
	 */
	public function hasRightByID( $roleId, $objectId, $actionId )
	{
		$right	= new Framework_Neon_Models_RoleRight();
		return $right->hasRightByID( $roleId, $objectId, $actionId );
	}
	
	/**
	 *	Indicates whether a Role Right is set by Right Object Name and Right Action Name.
	 *	@access		public
	 *	@param		int			roleId		Role ID
	 *	@param		string		object		Right Object Name
	 *	@param		string		action		Right Action Name
	 *	@return		bool
	 */
	public function hasRight( $roleId, $object, $action )
	{
		$right	= new Framework_Neon_Models_RoleRight();
		return $right->hasRight( $roleId, $object, $action );
	}
}
?>