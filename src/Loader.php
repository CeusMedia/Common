<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	A configured Loader for Classes and Scripts, which can be registered as Autoloader.
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common;

use CeusMedia\Common\Exception\Deprecation as DeprecationException;
use InvalidArgumentException;
use RuntimeException;

/**
 *	A configured Loader for Classes and Scripts, which can be registered as Autoloader.
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

class Loader
{
	protected array $extensions	= [
		'php',
		'php5',
		'inc'
	];

	protected ?string $logFile	= NULL;

	protected ?string $path		= NULL;

	protected ?string $prefix	= NULL;

	protected bool $lowerPath	= FALSE;

	protected int $verbose		= 0;

	protected string $lineBreak;

	protected bool $logLookup	= FALSE;

	/**
	 *	Static constructor.
	 *	@access		public
	 *	@param		array|string|NULL	$extensions			List of possible File Extensions
	 *	@param		string|NULL			$path				Path to load Files from, empty to remove set Path
	 *	@param		string|NULL			$prefix				Allowed Class Name Prefix
	 *	@param		string|NULL			$logFile			Path Name of Log File
	 *	@param		integer				$verbose			Verbosity: 0 - quiet | 1 - show load | 2 - show scan (default: 0 - quiet)
	 *	@param		bool				$register			Flag: register autoloader, default: no
	 *	@return		self
	 */
	public static function create(
		array|string $extensions = NULL,
		?string $path = NULL,
		?string $prefix = NULL,
		?string $logFile = NULL,
		int $verbose = 0,
		bool $register = FALSE
	): self
	{
		$instance	= new self( $extensions, $path, $prefix, $logFile, $register );
		$instance->setVerbose( $verbose );
		return $instance;
	}

	/**
	 *	Constructor.
	 *	Attention: This constructor will automatically register autoloader, if not switched off by 6th argument.
	 *	@access		public
	 *	@param		array|string|NULL	$extensions			List of possible File Extensions
	 *	@param		string|NULL			$path				Path to load Files from
	 *	@param		string|NULL			$prefix				Allowed Class Name Prefix
	 *	@param		string|NULL			$logFile			Path Name of Log File
	 *	@param		bool				$register			Flag: register autoloader, default: yes
	 *	@return		void
	 */
	public function __construct( array|string $extensions = NULL, ?string $path = NULL, ?string $prefix = NULL, ?string $logFile = NULL, bool $register = TRUE  )
	{
		if( !empty( $extensions ) )
			$this->setExtensions( $extensions );
		if( NULL !== $path && '' !== trim( $path ) )
			$this->setPath( $path );
		if( NULL !== $prefix && '' !== trim( $prefix ) )
			$this->setPrefix( $prefix );
		if( NULL !== $logFile && '' !== trim( $logFile ) )
			$this->setLogFile( $logFile );
		$this->lineBreak	= getEnv( 'PROMPT' ) || getEnv( 'SHELL' ) ? PHP_EOL : '<br/>';
		if( $register )
			$this->register();
	}

	/**
	 *	Register new Autoloader statically.
	 *	@static
	 *	@access		public
	 *	@param		array|string|NULL	$extensions		String or List of supported Class File Extensions
	 *	@param		string|NULL			$prefix			Prefix of Classes
	 *	@param		string|NULL			$path			Path to Classes
	 *	@param		string|NULL			$logFile		Path to autoload log file
	 *	@param		integer				$verbose		Verbosity: 0 - quiet | 1 - show load | 2 - show scan (default: 0 - quiet)
	 *	@return		Loader
	 *	@throws		DeprecationException
	 *	@deprecated	use constructor or Loader::create instead
	 */
	public static function registerNew( array|string $extensions = NULL, ?string $prefix = NULL, ?string $path = NULL, ?string $logFile = NULL, int $verbose = 0 ): self
	{
		Deprecation::getInstance()
			->setWarningVersion( '1.0' )
			->setExceptionVersion( '1.1' )
			->message( 'Loader::registerNew class is deprecated, please "new Loader(...)" or "Loader::create(...)->register()" or Loader::create with 6th argument instead!' );

		$loader	= new Loader( $extensions, $path, $prefix, $logFile, TRUE );
		$loader->setVerbose( $verbose );
		return $loader;
	}

	/**
	 *	Try to load a Class by its Class Name.
	 *	@access		public
	 *	@param		string			$className			Class Name with encoded Path
	 *	@return		void
	 */
	public function loadClass( string $className ): void
	{
		if( $this->prefix ){
			$prefix	= strtolower( substr( $className, 0, strlen( $this->prefix ) ) );
			if( $prefix != $this->prefix )
				return;
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
				echo $this->lineBreak."lookup: ".$filePath;
			$this->logLookup( $filePath );
			if( !file_exists( $filePath ) )
				continue;
			$this->loadFile( $filePath, TRUE );
			return;
		}
	}

	/**
	 *	Try to load a File by its File Name.
	 *	@access		public
	 *	@param		string		$fileName				File Name, absolute or relative
	 *	@param		bool		$once					Flag: Load once only
	 *	@return		void
	 */
	public function loadFile( string $fileName, bool $once = FALSE ): void
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
	public function logLookup( string $fileName ): void
	{
		if( !$this->logLookup || NULL === $this->logFile || '' === trim( $this->logFile ) )
			return;
		error_log( '? '.$fileName.PHP_EOL, 3, $this->logFile );
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$fileName				Name of loaded File
	 *	@return		void
	 */
	public function logLoadedFile( string $fileName ): void
	{
		if( NULL === $this->logFile || '' === trim( $this->logFile ) )
			return;
		$prefix	= $this->logLookup ? '! ' : '';
		error_log( $prefix.$fileName.PHP_EOL, 3, $this->logFile );
	}

	/**
	 *	Registers this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 */
	public function register(): bool
	{
		return spl_autoload_register( [$this, 'loadClass'] );
	}

	/**
	 *	Registers this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 *	@deprecated	use register instead
	 */
	public function registerAutoloader(): bool
	{
		return spl_autoload_register( [$this, 'loadClass'] );
	}

	/**
	 *	Sets possible File Extensions, default: php,inc.
	 *	@access		public
	 *	@param		array|string	$extensions			List of possible File Extensions
	 *	@return		self
	 *	@throws		InvalidArgumentException if given List is empty
	 */
	public function setExtensions( array|string $extensions ): self
	{
		if( is_string( $extensions ) )
			$extensions	= explode( ',', $extensions );
		$extensions	= array_map( static fn( string $extension): string => trim( $extension), $extensions );
		$extensions	= array_filter( $extensions );
		if( 0 === count( $extensions ) )
			throw new InvalidArgumentException( 'At least one extension must be given' );
		$this->extensions	= $extensions;
		return $this;
	}

	/**
	 *	Sets Log File Name.
	 *	@access		public
	 *	@param		string		$pathName		Path Name of Log File
	 *	@return		self
	 */
	public function setLogFile( string $pathName ): self
	{
		$this->logFile	= $pathName;
		return $this;
	}

	public function setLogLookup( bool $logLookup ): self
	{
		$this->logLookup	= $logLookup;
		return $this;
	}

	public function setLowerPath( bool $bool ): self
	{
		$this->lowerPath	= $bool;
		return $this;
	}

	/**
	 *	Sets Path to load Files from to force absolute File Names.
	 *	@access		public
	 *	@param		string		$path		Path to load Files from, empty to remove set Path
	 *	@return		self
	 *	@throws		RuntimeException		if Path is not existing
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
	 *	@param		string		$prefix			Allowed Class Name Prefix
	 *	@return		self
	 */
	public function setPrefix( string $prefix ): self
	{
		$this->prefix	= strtolower( $prefix );
		return $this;
	}

	/**
	 *	Set verbosity level.
	 *	@param		int			$verbosity
	 *	@return		$this
	 */
	public function setVerbose( int $verbosity ): self
	{
		$this->verbose	= $verbosity;
		return $this;
	}

	/**
	 *	Unregisters this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 */
	public function unregister(): bool
	{
		return spl_autoload_unregister( [$this, 'loadClass'] );
	}

	/**
	 *	Unregisters this Loader as Autoloader using SPL.
	 *	@access		public
	 *	@return		bool
	 *	@deprecated	use unregister instead
	 */
	public function unregisterAutoloader(): bool
	{
		return spl_autoload_unregister( [$this, 'loadClass'] );
	}
}
