<?php
class DB_OSQL_Query_Delete extends DB_OSQL_Query_Abstract
{
	protected $conditions	= array();
	protected $table		= NULL;
	
	public function from( DB_OSQL_Table $table )
	{
		$this->table	= $table;
		return $this;
	}

	protected function checkSetup()
	{
		if( !$this->table )
			throw new Exception( 'No table clause set' );
	}

	public function render()
	{
		$clock	= new Alg_Time_Clock();
		$this->checkSetup();
		$parameters	= array();
		$table		= $this->table->render();
		$conditions	= $this->renderConditions( $parameters );
		$limit		= $this->renderLimit( $parameters );
		$offset		= $this->renderOffset( $parameters );
		$query		= 'DELETE FROM '.$table.$conditions.$limit.$offset;
		$this->timeRender	= $clock->stop( 6, 0 );
		return array( $query, $parameters );
	}
}
?>