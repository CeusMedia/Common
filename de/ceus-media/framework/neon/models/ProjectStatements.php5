<?php
import( 'de.ceus-media.database.StatementCollection' );
/**
 *	Project Collection of SQL Statement Components.
 *	@package		framework.neon.models
 *	@extends		Database_StatementCollection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.03.2007
 *	@version		0.2
 */
/**
 *	Project Collection of SQL Statement Components.
 *	@package		framework.neon.models
 *	@extends		Database_StatementCollection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.03.2007
 *	@version		0.2
 */
class Framework_Neon_Models_ProjectStatements extends Database_StatementCollection
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
		$this->collectProjects();
	}

	/**
	 *	Base Component.
	 *	@access		private
	 *	@return		void
	 */
	private function collectProjects()
	{
		$keys	= array(
			"p.projectId",
			"p.title",
			"p.created",
			"p.modified",
		);
		$table	= "projects as p";
		$this->builder->addKeys( $keys );
		$this->builder->addTable( $table );
	}
	
	/**
	 *	Title Component.
	 *	@access		public
	 *	@param		string		$title		Title to be found
	 *	@return		void
	 */
	public function withTitle( $title )
	{
		$condition	= "p.title LIKE '%".$title."%'";
		$this->builder->addCondition( $condition );
	}
}
?>