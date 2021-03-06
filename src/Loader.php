<?php
/**
 *	A configured Loader for Classes and Scripts, which can be registered as Autoloader.
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
/**
 *	A configured Loader for Classes and Scripts, which can be registered as Autoloader.
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 */
class Loader
{
	protected $extensions	= array(
		'php',
		'php5',
		'inc'
	);
	protected $logFile		= NULL;
	protected $path			= NULL;
	protected $prefix		= NULL;
	protected $lowerPath	= FALSE;
	protected $verbose		= 0;
	protected $lineBreak	= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array|string	$extensions			List of possible File Extensions
	 *	@param		string			$prefix				Allowed Class Name Prefix
	 *	@param		string			$path				Path to load Files from, empty to remove set Path
	 *	@param		string			$logFile			Path Name of Log File
	 *	@return		void
	 */
	public function __construct( $extensions = NULL, $prefix = NULL, $path = NULL, $logFile = NULL )
	{
		if( !empty( $extensions ) )
			$this->setExtensions( $extensions );
		if( is_string( $prefix ) && !empty( $prefix ) )
			$this->setPrefix( $prefix );
		if( is_string( $path ) && !empty( $path ) )
			$this->setPath( $path );
		if( !empty( $logFile ) )
			$this->setLogFile( $logFile );
		$this->lineBreak		= "<br/>";
		if( getEnv( 'PROMPT' ) || getEnv( 'SHELL' ) )
			$this->lineBreak		= "\n";
		return spl_autoload_register( array( $this, 'loadClass' ) );
	}

	/**
	 *	Register new Autoloader statically.
	 *	@static
	 *	@access		public
	 *	@param		mixed		$extensions		String or List of supported Class File Extensions
	 *	@param		string		$prefix			Prefix of Classes
	 *	@param		string		$path			Path to Classes
	 *	@param		string		$logFile		Path to autoload log file
	 *	@param		boolean		$verbose		Verbosity: 0 - quiet | 1 - show load | 2 - show scan (default: 0 - quiet)
	 *	@return		Loader
	 *	@deprecated	not working in PHP 5.2
	 */
	public static function registerNew( $extensions = NULL, $prefix = NULL, $path = NULL, $logFile = NULL, $verbose = 0 )
	{
		$loader	= new Loader( $extensions, $prefix, $path, $logFile );
		$loader->setVerbose( (int) $verbose );
		$loader->registerAutoloader();
		return $loader;
	}

	/**
	 *	Try to load a Class by its Class Name.
	 *	@access		public
	 *	@param		string			$className			Class Name with encoded Path
	 *	@return		bool
	 */
	public function loadClass( $className )
	{
		if( $this->prefix )
		{
			$prefix	= strtolower( substr( $className, 0, strlen( $this->prefix ) ) );
			if( $prefix != $this->prefix )
				return FALSE;
			$className	= str_ireplace( $this->prefix, '', $className );
		}
		$basePath		= $this->path ? $this->path : "";
		if( $this->lowerPath )
		{
			$matches	= array();
			preg_match_all( '/^(.*)([a-z0-9]+)$/iU', $className, $matches );
			$fileName	= $matches[2][0];
			$pathName	= str_replace( "_","/", strtolower( $matches[1][0] ) );
			$fileName	= $pathName.$fileName;
		}
		else
			$fileName	= str_replace( "_","/", $className );
		foreach( $this->extensions as $extension )
		{
			$filePath	= $basePath.$fileName.".".$extension;
			if( $this->verbose > 1 )
				echo $this->lineBreak."autoload: ".$filePath;
			if( defined( 'LOADER_LOG' ) && LOADER_LOG )
				error_log( $filePath."\n", 3, LOADER_LOG );
#			if( !@fopen( $filePath, "r", TRUE ) )
			if( !file_exists( $filePath ) )
#			if( !is_readable( $filePath ) )
				continue;
			$this->loadFile( $filePath, TRUE );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Try to load a File by its File Name.
	 *	@access		public
	 *	@param		string		$fileName				File Name, absolute or relative
	 *	@param		bool		$once					Flag: Load once only
	 *	@return		void
	 */
	public function loadFile( $fileName, $once = FALSE )
	{
		$this->logLoadedFile( $fileName );
		if( $once )
			include_once $fileName;
		else
			include $fileName;
		if( $this->verbose > 0 )
			echo $this->lineBreak."load: ".$fileName;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$fileName				Name of loaded File
	 *	@return		void
	 */
	public function logLoadedFile( $fileName )
	{
		if( $this->logFile )
			error_log( $fileName."\n", 3, $this->logFile );
	}

	/**
	 *	Registers this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 */
	public function registerAutoloader()
	{
	#	return spl_autoload_register( array( $this, 'loadClass' ) );
	}

	/**
	 *	Sets possible File Extensions, default: php,inc.
	 *	@access		public
	 *	@param		array|string	$extensions			List of possible File Extensions
	 *	@return		void
	 *	@throws		InvalidArgumentException if given List is not an Array
	 *	@throws		InvalidArgumentException if given List is empty
	 */
	public function setExtensions( $extensions )
	{
		if( is_string( $extensions ) )
			$extensions	= explode( ',', $extensions );
		if( !is_array( $extensions ) )
			throw new InvalidArgumentException( 'Must be an array or string' );
		if( empty( $extensions ) )
			throw new InvalidArgumentException( 'Atleast one extension must be given' );
		$this->extensions	= array();
		foreach( $extensions as $extension )
			$this->extensions[]	= trim( $extension );
	}

	public function setLowerPath( $bool )
	{
		$this->lowerPath	= (bool) $bool;	
	}

	public function setVerbose( $verbosity )
	{
		$this->verbose	= (int) $verbosity;
	}

	/**
	 *	Sets Log File Name.
	 *	@access		public
	 *	@param		string			$pathName			Path Name of Log File
	 *	@return		void
	 */
	public function setLogFile( $pathName )
	{
		$this->logFile	= $pathName;
	}

	/**
	 *	Sets Path to load Files from to force absolute File Names.
	 *	@access		public
	 *	@param		string			$path				Path to load Files from, empty to remove set Path
	 *	@return		void
	 *	@throws		RuntimeException if Path is not existing
	 */
	public function setPath( $path )
	{
#		if( $path && !file_exists( $path ) )
#			throw new RuntimeException( 'Invalid path' );
		$path	= str_replace( DIRECTORY_SEPARATOR, "/", $path );
		$path	= preg_replace( "@(.+)/$@", "\\1", $path )."/";
		$this->path	= $path;
	}

	/**
	 *	@access		public
	 *	@param		string			$prefix				Allowed Class Name Prefix
	 *	@return		void
	 */
	public function setPrefix( $prefix )
	{
		$this->prefix	= strtolower( $prefix );
	}

	/**
	 *	Unregisters this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 */
	public function unregisterAutoloader()
	{
		return spl_autoload_unregister( array( $this, 'loadClass' ) );
	}
}
