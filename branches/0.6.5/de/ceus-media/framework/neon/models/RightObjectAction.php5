<?php
/**
 *	Data Model of Right Objects.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
import( 'de.ceus-media.framework.neon.Model' );
/**
 *	Data Model of Right Objects.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
class Framework_Neon_Models_RightObjectAction extends Framework_Neon_Model
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $rightobjectactionId = false )
	{
		$tableName	= "rightobjectactions";
		$fieldList	= array (
			"rightObjectActionId",
			"rightObjectId",
			"rightActionId",
			);
		$primaryKey	= "rightobjectactionId";
		$foreignKeys	= array( "rightObjectId", "rightActionId" );

		parent::__construct( $tableName, $fieldList, $primaryKey, $rightobjectactionId );
		$this->setForeignKeys ( $foreignKeys );
	}

	/**
	 *	Indicates whether an Object has an Action.
	 *	@access		public
	 *	@param		int		objectId		Object ID
	 *	@param		int		actionId		Action ID
	 *	@return		bool
	 */
	public function isObjectActionByID( $objectId, $actionId )
	{
		$this->defocus();
		$this->focusForeign( "rightObjectId", $objectId );
		$this->focusForeign( "rightActionId", $actionId );
		$data	= $this->getData();
		if( count( $data ) != 0 )
			return true;
		return false;
	}
}
?>
