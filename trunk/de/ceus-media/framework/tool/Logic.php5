<?php
/**
 *	Abstract Business Logic, Access to Database or other Resources.
 *	@package		framework.tool
 *	@uses			Database_PDO_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
/**
 *	Abstract Business Logic, Access to Database or other Resources.
 *	@package		framework.tool
 *	@uses			Database_PDO_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.05.2008
 *	@version		0.1
 */
abstract class Framework_Tool_Logic
{
	/**	@var		File_Configuration_Reader	$config			Configuration Object */
	protected $config;
	/**	@var		object						$database		Database Connection Object, is used */
	protected $database	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		File_Configuration_Reader	$config			Configuration Object
	 *	@return		void
	 */
	public function __construct( File_Configuration_Reader $config )
	{
		$this->config	= $config;
		if( $config['database'] )
			$this->connectDatabase( $config['database'] );
	}

	/** 
	 *	Connects to Database using PDO.
	 *	@access		protected
	 *	@param		File_Configuration_Reader	$config			Configuration Object
	 *	@return		void
	 */
	protected function connectDatabase( $config )
	{
		import( 'de.ceus-media.database.pdo.Connection' );
		$dataSourceName	= "mysql:host=".$config['hostname'].";dbname=".$config['database'];
		$this->database	= new Database_PDO_Connection( $dataSourceName, $config['username'], $config['password'] );
		
		if( $this->config['errorlog'] )
			$this->database->setErrorLog( $config['errorlog'] );

		if( $this->config['querylog'] )
			$this->database->setStatementLog( $config['querylog'] );
	}
}
?>