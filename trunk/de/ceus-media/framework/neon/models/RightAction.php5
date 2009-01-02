<?php
/**
 *	Data Model of Right Actions.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
import( 'de.ceus-media.framework.neon.Model' );
/**
 *	Data Model of Right Actions.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
class Framework_Neon_Models_RightAction extends Framework_Neon_Model
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $rightActionId = false )
	{
		$tableName	= "rightactions";
		$fieldList	= array (
			"rightActionId",
			"title",
			"description",
			);
		$primaryKey	= "rightActionId";
		$foreignKeys	= array( "title" );

		parent::__construct( $tableName, $fieldList, $primaryKey, $rightActionId );
		$this->setForeignKeys ( $foreignKeys );
	}

	/**
	 *	Returns Array of Right Actions.
	 *	@access		public
	 *	@return		array
	 */
	public function getActions()
	{
		$list	= $this->getAllData();
		$actions	= array();
		foreach( $list as $entry )
			$actions[$entry['rightActionId']]	= $entry['title'];
		return $actions;
	}

	/**
	 *	Returns Action Name of Action ID.
	 *	@access		public
	 *	@param		int			rightActionId		Action ID
	 *	@return		string
	 */
	public function getAction( $rightActionId )
	{
		$this->focusPrimary( $rightActionId );
		$data	= $this->getData( true );
		$this->defocus();
		if( isset( $data['title'] ) )
			return $data['title'];
		return "";
	}
	
	/**
	 *	Returns Action ID from Action Name.
	 *	@access		public
	 *	@param		string		title			Action Name
	 *	@return		int
	 */
	public function getActionID( $title )
	{
		if( isset( $this->_cache[$title] ) )
			return $this->_cache[$title];
		$this->focusForeign( "title", $title );
		$data	= $this->getData( true );
		$this->defocus();
		if( isset( $data['rightActionId'] ) )
			return $this->_cache[$title] = $data['rightActionId'];
		return 0;
	}
}
?>
