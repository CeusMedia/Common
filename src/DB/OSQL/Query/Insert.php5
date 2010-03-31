<?php
class DB_OSQL_Query_Insert extends DB_OSQL_Query_Abstract
{
	protected $fields;
	protected $table		= NULL;
	
	protected function checkSetup()
	{
		if( !$this->table )
			throw new Exception( 'No table clause set' );
	}

	public function into( DB_OSQL_Table $table )
	{
		$this->table	= $table;
		return $this;
	}

	protected function renderFields( & $parameters )
	{
		if( !$this->fields )
			return '';
		$listKeys	= array();
		$listVals	= array();
		foreach( $this->fields as $name => $value )
		{
			$key	= 'value_'.str_replace( '.', '_', $name );
			$listKeys[]	= $name;
			$listVals[]	= ':'.$key;
			$parameters[$key]	= array(
				'type'	=> PDO::PARAM_STR,
				'value'	=> $value
			);
		}
		return '( '.implode( ', ', $listKeys ).' ) VALUE ( '.implode( ', ', $listVals ).' )';
	}

	public function render()
	{
		$clock	= new Alg_Time_Clock();
		$this->checkSetup();
		$parameters	= array();
		$table		= $this->table->render();
		$fields		= $this->renderFields( $parameters );
		$conditions	= $this->renderConditions( $parameters );
		$limit		= $this->renderLimit( $parameters );
		$offset		= $this->renderOffset( $parameters );
		$query		= 'INSERT INTO '.$table.$fields.$conditions.$limit.$offset;
		return array( $query, $parameters, $clock->stop( 6, 0 ) );
	}

	public function set( $name, $value )
	{
		$this->fields[$name]	 = $value;
		return $this;
	}
}
?>