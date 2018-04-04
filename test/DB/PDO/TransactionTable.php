<?php
class Test_DB_PDO_TransactionTable extends DB_PDO_Table{

	protected $name									= "transactions";
	protected $columns								= array( 'id', 'topic', 'label', 'timestamp' );
	protected $indices								= array( 'topic', 'label' );
	protected $primaryKey							= "id";
	protected $prefix;
	protected $fetchMode							= \PDO::FETCH_OBJ;
	public static $cacheClass						= 'ADT_List_Dictionary';
}
