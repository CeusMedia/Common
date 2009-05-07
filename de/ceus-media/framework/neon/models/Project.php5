<?php
/**
 *	Data Model of Projects.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.03.2007
 *	@version		0.2
 */
import( 'de.ceus-media.framework.neon.Model' );
/**
 *	Data Model of Projects.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.03.2007
 *	@version		0.2
 */
class Framework_Neon_Models_Project extends Framework_Neon_Model
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$projectId		ID of Project
	 *	@return		void
	 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		26.03.2007
	 */
	public function __construct( $projectId = false )
	{
		$tableName	= "projects";
		$fieldList	= array (
			"projectId",
			"title",
			"created",
			"modified",
			);
		$primaryKey	= "projectId";
		$foreignKeys	= array( "title" );
		parent::__construct( $tableName, $fieldList, $primaryKey, $$primaryKey );
		$this->setForeignKeys ( $foreignKeys );
	}
}
?>