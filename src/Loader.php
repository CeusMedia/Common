<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	A configured Loader for Classes and Scripts, which can be registered as Autoloader.
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common;

use InvalidArgumentException;
use RuntimeException;

/**
 *	A configured Loader for Classes and Scripts, which can be registered as Autoloader.
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
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
	 *	@param		string|NULL		$prefix				Allowed Class Name Prefix
	 *	@param		string|NULL		$path				Path to load Files from, empty to remove set Path
	 *	@param		string|NULL		$logFile			Path Name of Log File
	 *	@return		void
	 */
	public function __construct( $extensions = NULL, ?string $prefix = NULL, ?string $path = NULL, ?string $logFile = NULL )
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
		$this->registerAutoloader();
	}

	/**
	 *	Register new Autoloader statically.
	 *	@static
	 *	@access		public
	 *	@param		mixed			$extensions		String or List of supported Class File Extensions
	 *	@param		string|NULL		$prefix			Prefix of Classes
	 *	@param		string|NULL		$path			Path to Classes
	 *	@param		string|NULL		$logFile		Path to autoload log file
	 *	@param		integer			$verbose		Verbosity: 0 - quiet | 1 - show load | 2 - show scan (default: 0 - quiet)
	 *	@return		Loader
	 *	@deprecated	not working in PHP 5.2
	 */
	public static function registerNew( $extensions = NULL, ?string $prefix = NULL, ?string $path = NULL, ?string $logFile = NULL, int $verbose = 0 ): self
	{
		$loader	= new Loader( $extensions, $prefix, $path, $logFile );
		$loader->setVerbose( $verbose );
		return $loader;
	}

	/**
	 *	Try to load a Class by its Class Name.
	 *	@access		public
	 *	@param		string			$className			Class Name with encoded Path
	 *	@return		bool
	 */
	public function loadClass( string $className ): bool
	{
		if( $this->prefix ){
			$prefix	= strtolower( substr( $className, 0, strlen( $this->prefix ) ) );
			if( $prefix != $this->prefix )
				return FALSE;
			$className	= str_ireplace( $this->prefix, '', $className );
		}
		$basePath		= $this->path ?: '';
		if( $this->lowerPath ){
			$matches	= [];
			preg_match_all( '/^(.*)([a-z0-9]+)$/iU', $className, $matches );
			$fileName	= $matches[2][0];
			$pathName	= str_replace( "_","/", strtolower( $matches[1][0] ) );
			$fileName	= $pathName.$fileName;
		}
		else
			$fileName	= str_replace( "_","/", $className );
		foreach( $this->extensions as $extension ){
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
	public function loadFile( string $fileName, bool $once = FALSE )
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
	public function logLoadedFile( string $fileName )
	{
		if( $this->logFile )
			error_log( $fileName."\n", 3, $this->logFile );
	}

	/**
	 *	Registers this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 */
	public function registerAutoloader(): bool
	{
		return spl_autoload_register( array( $this, 'loadClass' ) );
	}

	/**
	 *	Sets possible File Extensions, default: php,inc.
	 *	@access		public
	 *	@param		array|string	$extensions			List of possible File Extensions
	 *	@return		self
	 *	@throws		InvalidArgumentException if given List is not an Array
	 *	@throws		InvalidArgumentException if given List is empty
	 */
	public function setExtensions( $extensions ): self
	{
		if( is_string( $extensions ) )
			$extensions	= explode( ',', $extensions );
		if( !is_array( $extensions ) )
			throw new InvalidArgumentException( 'Must be an array or string' );
		if( empty( $extensions ) )
			throw new InvalidArgumentException( 'At least one extension must be given' );
		$this->extensions	= [];
		foreach( $extensions as $extension )
			$this->extensions[]	= trim( $extension );
		return $this;
	}

	public function setLowerPath( $bool ): self
	{
		$this->lowerPath	= (bool) $bool;
		return $this;
	}

	public function setVerbose( $verbosity ): self
	{
		$this->verbose	= (int) $verbosity;
		return $this;
	}

	/**
	 *	Sets Log File Name.
	 *	@access		public
	 *	@param		string			$pathName			Path Name of Log File
	 *	@return		self
	 */
	public function setLogFile( string $pathName ): self
	{
		$this->logFile	= $pathName;
		return $this;
	}

	/**
	 *	Sets Path to load Files from to force absolute File Names.
	 *	@access		public
	 *	@param		string			$path				Path to load Files from, empty to remove set Path
	 *	@return		self
	 *	@throws		RuntimeException if Path is not existing
	 */
	public function setPath( string $path ): self
	{
#		if( $path && !file_exists( $path ) )
#			throw new RuntimeException( 'Invalid path' );
		$path	= str_replace( DIRECTORY_SEPARATOR, "/", $path );
		$path	= preg_replace( "@(.+)/$@", "\\1", $path )."/";
		$this->path	= $path;
		return $this;
	}

	/**
	 *	@access		public
	 *	@param		string			$prefix				Allowed Class Name Prefix
	 *	@return		self
	 */
	public function setPrefix( string $prefix ): self
	{
		$this->prefix	= strtolower( $prefix );
		return $this;
	}

	/**
	 *	Unregisters this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 */
	public function unregisterAutoloader(): bool
	{
		return spl_autoload_unregister( array( $this, 'loadClass' ) );
	}
}
