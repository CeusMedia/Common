<?php
class DB_DAO_Object
{
	protected $table		= NULL;
	protected $primaryKey	= NULL;

	public function __construct( DataAccess_Table $table )
	{
		$this->table	= $table;
	}

	public function __get( $key )
	{
		throw new Exception( get_class( $this ).': Field "'.$key.'" is not available' );
	}

	public function __set( $key, $value )
	{
		if( !in_array( $key, $this->table->getFieldNames() ) )
			throw new Exception( get_class( $this ).': Field "'.$key.'" is not available' );
		$this->$key	= $value;
	}

	public function getFieldNames()
	{
		return $this->table->getFieldNames();
	}

	/**
	 *	@todo		Should this be public?
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 *	Set Data Access Table.
	 *	@access		public
	 *	@param		DataAccess_Table	$table		Database Access Table
	 *	@return		void
	 */
	public function setTable( DB_DAO_Table $table )
	{
		$this->table	= $table;
	}

	public function updateField( $key, $value )
	{
		return $this->table->updateById( $this->table->getPrimaryKey(), array( $key => $value ) );
	}

	public function updateFields( $fields )
	{
		return $this->table->updateById( $this->table->getPrimaryKey(), $fields );
	}
}
?>