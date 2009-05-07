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
class Framework_Neon_Models_RightObject extends Framework_Neon_Model
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $rightObjectId = false )
	{
		$tableName	= "rightobjects";
		$fieldList	= array (
			"rightObjectId",
			"title",
			"description",
			);
		$primaryKey	= "rightObjectId";
		$foreignKeys	= array( "title" );

		parent::__construct( $tableName, $fieldList, $primaryKey, $rightObjectId );
		$this->setForeignKeys ( $foreignKeys );
	}

	/**
	 *	Returns Array of Right Objects.
	 *	@access		public
	 *	@return		array
	 */
	public function getObjects()
	{
		$list = $this->getAllData();
		$objects	= array();
		foreach( $list as $entry )
			$objects[$entry['rightObjectId']]	= $entry['title'];
		return $objects;
	}

	/**
	 *	Returns Object Name of Object ID.
	 *	@access		public
	 *	@param		int			rightObjectId		Object ID
	 *	@return		string
	 */
	public function getObject( $rightObjectId )
	{
		$this->focusPrimary( $rightObjectId );
		$data	= $this->getData( true );
		$this->defocus();
		if( isset( $data['title'] ) )
			return $data['title'];
		return "";
	}

	/**
	 *	Returns Action ID from Action Name.
	 *	@access		public
	 *	@param		string		title			Object Title
	 *	@return		int
	 */
	public function getObjectID( $title )
	{
		if( isset( $this->_cache[$title] ) )
			return $this->_cache[$title];
		$this->defocus();
		$this->focusForeign( "title", $title );
		$data	= $this->getData( true );
		$this->defocus();
		
		if( isset( $data['rightObjectId'] ) )
			return $this->_cache[$title] = $data['rightObjectId'];
		return 0;
	}
}
?>
