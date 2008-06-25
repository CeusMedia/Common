<?php
/**
 *	Business Logic, Access to Database.
 *	@package		tool
 *	@uses			Database_PDO_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
/**
 *	Business Logic, Access to Database.
 *	@package		tool
 *	@uses			Database_PDO_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
class Logic
{
	/**	@var		object		$database		Database Connection Object, is used */
	private $database	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		File_Configuration_Reader	$config		Configuration Object
	 *	@return		void
	 */
	public function __construct( File_Configuration_Reader $config )
	{
		if( isset( $config['database'] ) )
			$this->connectDatabase( $config['database'] );
	}
	
	private function connectDatabase( $config )
	{
		import( 'de.ceus-media.database.pdo.Connection' );
		$dataSourceName	= "mysql:host=".$config['hostname'].";dbname=".$config['database'];
		$this->database	= new Database_PDO_Connection( $dataSourceName, $config['username'], $config['password'] );
	}
}
?>