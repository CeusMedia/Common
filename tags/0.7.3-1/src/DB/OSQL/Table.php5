<?php
class DB_OSQL_Table
{
	protected $name;
	protected $alias;
	protected $joins	= array();

	public function __construct( $name = NULL, $alias = NULL )
	{
		if( $alias )
			$this->setAlias( $alias );
		if( $name )
			$this->setName( $name );
	}

	public function getAlias()
	{
		return $this->alias;
	}

	public function getName()
	{
		return $this->name;
	}
	
	public function render()
	{
		if( !$this->name )
			throw new Exception( 'No table name set' );
		$joins	= '';
		if( $this->joins )
		{
			$joins	= array();
			foreach( $this->joins as $join )
			{
				$tableName	= $join['table']->render();
				$equiJoin	= $join['left'].' = '.$join['right'];
				$joins[]	= ' LEFT OUTER JOIN '.$tableName.' ON ( '.$equiJoin.' )';
			}
			$joins	= join( $joins );
		}
		if( $this->alias && $this->alias !== $this->name )
			return $this->name.' AS '.$this->alias.$joins;		
		return $this->name;
	}

	public function setAlias( $alias )
	{
		$this->alias	= $alias;
	}

	public function setName( $name )
	{
		$this->name	= $name;
	}
	
	public function join( DB_OSQL_Table $table, $keyLeft, $keyRight )
	{
		$this->joins[]	= array(
			'table'	=> $table,
			'left'	=> $keyLeft,
			'right'	=> $keyRight
		);
	}
}