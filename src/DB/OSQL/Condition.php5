<?php
class DB_OSQL_Condition
{
	protected $type			= NULL;
	protected $fieldName	= NULL;
	protected $operation	= '=';
	protected $value		= NULL;
	protected $joins		= array();

	public function __construct( $fieldName = NULL, $value = NULL, $operation = NULL )
	{
		if( $fieldName )
			$this->setFieldName( $fieldName );
		if( $operation )
			$this->setOperation( $operation );
		if( $value )
			$this->setValue( $value );
	}

	public function getFieldName()
	{
		return $this->name;
	}

	public function getOperation()
	{
		return $this->operation;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function join( DB_OSQL_Condition $condition )
	{
		$this->joins[]	= $condition;
	}
	
	public function render( & $parameters )
	{
		$counter	= 0;
		do
		{
			$key	= 'condition_'.str_replace( '.', '_', $this->name ).'_'.$counter;
			$counter++;
		}
		while( isset( $parameters[$key] ) );
		$parameters[$key]	= array(
			'type'	=> $this->type,
			'value'	=> $this->value
		);
		$condition	= $this->name.' '.$this->operation.' :'.$key;

		if( !$this->joins )
			return $condition;
		$joins	= array( $condition );
		foreach( $this->joins as $join )
			$joins[]	= $join->render( $parameters );
		return '( '.implode( ' OR ', $joins ).' )'; 
	}

	public function setFieldName( $fieldName )
	{
		$this->name		= $fieldName;
	}

	public function setOperation( $operation )
	{
		$this->operation	= $operation;
	}

	public function setValue( $value )
	{
		$this->value	= $value;
	}
	
	
}
?>