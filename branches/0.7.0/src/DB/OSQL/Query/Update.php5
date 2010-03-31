<?php
class DB_OSQL_Query_Update extends DB_OSQL_Query_Abstract
{
	protected $conditions	= array();
	protected $fields		= array();
	protected $table		= NULL;
	
	public function __construct( Database $dbc )
	{
		$this->dbc	= $dbc;
	}
	
	public function in( DB_OSQL_Table $table )
	{
		$this->table	= $table;
		return $this;
	}

	protected function checkSetup()
	{
		if( !$this->table )
			throw new Exception( 'No table clause set' );
	}

	protected function renderFields( & $parameters )
	{
		if( !$this->fields )
			return '';
		$list	= array();
		foreach( $this->fields as $name => $value )
			$list[]	= $name.' = :'.$name;
		$parameters[$name]	= array(
			'type'	=> PDO::PARAM_STR,
			'value'	=> $value
		);
		return ' SET '.implode( ', ', $list );
	}

	public function render()
	{
		$clock	= new Alg_Time_Clock();
		$this->checkSetup();
		$parameters	= array();
		$fields		= $this->renderFields( $parameters );
		$table		= $this->table->render();
		$conditions	= $this->renderConditions( $parameters );
		$limit		= $this->renderLimit( $parameters );
		$offset		= $this->renderOffset( $parameters );
		$query		= 'UPDATE '.$table.$fields.$conditions.$limit.$offset;
		return array( $query, $parameters, $clock->stop( 6, 0 ) );
	}

	public function set( $name, $value )
	{
		$this->fields[$name]	 = $value;
		return $this;
	}
}
?>