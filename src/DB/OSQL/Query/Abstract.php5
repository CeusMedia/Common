<?php
abstract class DB_OSQL_Query_Abstract implements DB_OSQL_Query
{
	protected $fields;
	protected $limit;
	protected $offset;
	protected $query;
	protected $conditions	= array();

	
	public function __construct( Database $dbc )
	{
		$this->dbc	= $dbc;
	}
	
	public function execute()
	{
		return $this->dbc->execute( $this );
	}
	
#	abstract protected function checkSetup();

	abstract public function render();

	public function where( DB_OSQL_Condition $conditions )
	{
		return $this->andWhere( $conditions );
	}

	public function andWhere( DB_OSQL_Condition $conditions )
	{
		$this->conditions[]	= $conditions;
		return $this;
	}

	public function orWhere( DB_OSQL_Condition $conditions )
	{
		if( !$this->conditions )
			throw new Exception( 'No condition set yet' );
		$last	= array_pop( $this->conditions );
		$last->join( $conditions );
		return $this->andWhere( $last );
	}

	public function join( DB_OSQL_Table $table, $keyLeft, $keyRight )
	{
		array_push( $this->tables, $lastTable );
		return $this;
	}

	
	public function limit( $limit = NULL )
	{
		if( !is_null( $limit ) )
		{
			if( !is_int( $limit ) )
				throw new InvalidArgumentException( 'Must be integer or NULL' );
			if( $limit <= 0 )
				throw new InvalidArgumentException( 'Must greater than 0' );
			$this->limit	= $limit;
		}
		else
			$this->limit	= NULL;
		return $this;
	}
	
	public function offset( $offset = NULL )
	{
		if( !is_null( $offset ) )
		{
			if( !is_int( $offset ) )
				throw new InvalidArgumentException( 'Must be integer or NULL' );
			if( $offset <= 0 )
				throw new InvalidArgumentException( 'Must greater than 0' );
			$this->offset	= $offset;
		}
		else
			$this->offset	= NULL;
		return $this;
	}
	
	protected function renderConditions( & $parameters )
	{
		if( !$this->conditions )
			return '';
		$list	= array();
		foreach( $this->conditions as $condition )
			$list[]	= $condition->render( & $parameters );
		return ' WHERE '.implode( ' AND ', $list );
	}

	protected function renderLimit( & $parameters )
	{
		if( !$this->limit )
			return '';
		$limit		= ' LIMIT :limit';
		$parameters['limit']	= array(
			'type'	=> PDO::PARAM_INT,
			'value'	=> $this->limit,
		);
		return $limit;
	}

	protected function renderOffset( & $parameters )
	{
		if( !$this->offset )
			return '';
		$offset		= ' OFFSET :offset';
		$parameters['offset']	= array(
			'type'	=> PDO::PARAM_INT,
			'value'	=> $this->offset,
		);
		return $offset;
	}
}
?>