<?php
import( 'de.ceus-media.framework.krypton.core.database.pdo.Statement' );
import( 'de.ceus-media.framework.krypton.exception.SQL' );
/**
 *	Enhanced PDO Connection.
 *	@package		mv2.core.database.pdo
 *	@uses			Core_Database_PDO_Statement
 *	@uses			Exception_SQL
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
/**
 *	Enhanced PDO Connection.
 *	@package		mv2.core.database.pdo
 *	@uses			Core_Database_PDO_Statement
 *	@uses			Exception_SQL
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Framework_Krypton_Core_Database_PDO_Connection
{
	private $cwd;
	protected $PDO;
	public $numExecutes;
	public $numStatements;
	protected $logfile	= "db_error.log";
	private $queries	= array();
	
	public function __construct( $dsn, $user = NULL, $pass = NULL, $driver_options = NULL )
	{
		$this->PDO = new PDO( $dsn, $user, $pass, $driver_options );
		$this->numExecutes = 0;
		$this->numStatements = 0;
		$this->cwd	= getCwd();
	}

	public function __destruct()
	{
		$this->PDO	= null;
		if( $this->queries )
			file_put_contents( $this->cwd."/logs/database/queries_".getEnv( 'REMOTE_ADDR' ).".log", implode( "\n".str_repeat( "-", 80 )."\n", $this->queries ) );
	}
	public function __call( $func, $args )
	{
		return call_user_func_array( array( &$this->PDO, $func ), $args );
	}

	public function exec( $query, $verbose = 1 )
	{
		$this->queries[] = $query;
		try
		{
			$this->numExecutes++;
			return call_user_func_array( array( &$this->PDO, 'exec' ), array( $query ) );
		}
		catch( PDOException $e )
		{
			$this->logError( $e );
			return false;
		}
	}
	
	protected function logError( $error )
	{
		$info	= $this->errorInfo();
		error_log( time().":".$info[2]."\n", 3, $this->logfile );
		throw new Framework_Krypton_Exception_SQL( "SQL Error - Database Action broken up.", $info[2] );
	}
	
	public function prepare()
	{
		$this->numStatements++;
		$args = func_get_args();
		$PDOS = call_user_func_array( array( &$this->PDO, 'prepare' ), $args );
		return new Framework_Krypton_Core_Database_PDO_Statement( $this, $PDOS );
	}

	public function query( $query, $verbose = 1, $fetchMode = 1 )
	{
		$this->queries[] = $query;
		try
		{
			if( $verbose )
			{
				$this->numExecutes++;
				$this->numStatements++;
				if( $verbose == 2 )
					echo $query;
				if( $verbose == 4 )
					remark( $query );
				if( $verbose == 5 )
					die( $query );
			}
			$PDOS = call_user_func_array( array( &$this->PDO, 'query' ), array( $query ) );
			return new Framework_Krypton_Core_Database_PDO_Statement( $this, $PDOS );
		}
		catch( PDOException $e )
		{
			$this->logError( $e );
			return false;
		}
	}

	public function setLogFile( $filename )
	{
		$this->logfile	= $filename;
	}
}
?>