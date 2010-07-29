<?php
class DB_OSQL_Query_Select extends DB_OSQL_Query_Abstract
{
	protected $conditions	= array();
	protected $fields		= '*';
	protected $tables		= array();
	protected $groupBy		= NULL;
	
	public function get( $field )
	{
		if( is_string( $field ) )
			$fields	= array( $field );
		else if( is_array( $field ) )
			$fields	= $field;
		else
			throw new InvalidArgumentException( 'Must be array or string' );
		$this->fields	= implode( ', ', $fields );
		return $this;
	}

	protected function checkSetup()
	{
		if( !$this->tables )
			throw new Exception( 'No from clause set' );
	}

	public function from( DB_OSQL_Table $table )
	{
		$this->tables[]	= $table;
		return $this;
	}

	public function groupBy( $name )
	{
		$this->groupBy	= $name;
	}

/*	public function having( $name, $value )
	{
		$this->having	= array( $name, $value );
	}
*/
	public function join( DB_OSQL_Table $table, $keyLeft, $keyRight )
	{
		if( !$this->tables )
			throw new Exception( 'No table to join set' );
		$lastTable	= array_pop( $this->tables );
		$lastTable->join( $table, $keyLeft, $keyRight );
		array_push( $this->tables, $lastTable );
		return $this;
	}

	protected function renderFrom()
	{
		if( !$this->tables )
			throw new RuntimeException( 'No table set' );
		$list	= array();
		foreach( $this->tables as $table )
			$list[]	= $table->render();
		return ' FROM '.implode( ', ', $list );
	}

	protected function renderGrouping()
	{
		if( !$this->groupBy )
			return '';
		return ' GROUP BY '.$this->groupBy;
	}

	public function render()
	{
		$clock	= new Alg_Time_Clock();
		$this->checkSetup();
		$parameters	= array();
		$from		= $this->renderFrom();
		$conditions	= $this->renderConditions( $parameters );
		$limit		= $this->renderLimit( $parameters );
		$offset		= $this->renderOffset( $parameters );
		$group		= $this->renderGrouping();
		$query		= 'SELECT '.$this->fields.$from.$conditions.$limit.$offset.$group;
		$this->timeRender	= $clock->stop( 6, 0 );
		return array( $query, $parameters );
	}
}
?>