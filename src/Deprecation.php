<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Indicator for deprecated methods.
 *	@category		Library
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common;

use CeusMedia\Common\Exception\Deprecation as DeprecationException;

/**
 *	Indicator for deprecated methods.
 *
 *	Example:
 *		Deprecation::getInstance()
 *			->setErrorVersion( '0.9' )
 *			->setExceptionVersion( '0.9.1' )
 *			->message(  'Use method ... instead' );
 *
 *	@category		Library
 *	@package		CeusMedia_Common
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@phpstan-consistent-constructor
 */
class Deprecation
{
	protected string $version;
	protected string $errorVersion;
	protected string $exceptionVersion;
	protected string $phpVersion;
	protected string $warnVersion;

	/**
	 *	Creates a new deprecation object.
	 *	@access		public
	 *	@static
	 *	@return		Deprecation
	 */
	public static function getInstance(): self
	{
		return new static();
	}

	/**
	 *	Show message as exception or deprecation error, depending on set versions and PHP version.
	 *	Will throw an exception if set exception version reached detected library version.
	 *	Will throw a deprecation error if set error version reached detected library version using PHP 5.3+.
	 *	Will throw a deprecation notice if set error version reached detected library version using PHP lower 5.3.
	 *	@access		public
	 *	@param		string		$message	Message to show
	 *	@return		void
	 *	@throws		DeprecationException	if set exception version reached detected library version
	 */
	public function message( string $message ): void
	{
		$trace	= debug_backtrace();
		$caller	= next( $trace );
		if( isset( $caller['file'] ) )
			$message .= ', invoked in '.$caller['file'].' on line '.$caller['line'];

		if( '' !== $this->exceptionVersion )
			if( version_compare( $this->version, $this->exceptionVersion ) >= 0 )
				throw new DeprecationException( 'Deprecated: '.$message );

		if( '' !== $this->errorVersion )
			if( version_compare( $this->version, $this->errorVersion ) >= 0 ){
				trigger_error( $message.', triggered', E_USER_DEPRECATED );
				return;
			}

		if( '' !== $this->warnVersion )
			if( version_compare( $this->version, $this->warnVersion ) >= 0 )
				trigger_error( 'Deprecated: '.$message.', triggered', E_USER_WARNING );
	}

	/**
	 *	Set library version to start showing deprecation error or notice.
	 *	Returns deprecation object for method chaining.
	 *	@access		public
	 *	@param		string		$version	Library version to start showing deprecation error or notice
	 *	@return		Deprecation
	 */
	public function setErrorVersion( string $version ): self
	{
		$this->errorVersion	= $version;
		return $this;
	}

	/**
	 *	Set library version to start throwing deprecation exception.
	 *	Returns deprecation object for method chaining.
	 *	@access		public
	 *	@param		string		$version	Library version to start throwing deprecation exception
	 *	@return		Deprecation
	 */
	public function setExceptionVersion( string $version ): self
	{
		$this->exceptionVersion		= $version;
		return $this;
	}

	/**
	 *	Set library version to start triggering a warning.
	 *	Returns deprecation object for method chaining.
	 *	@access		public
	 *	@param		string		$version	Library version to start triggering a warning
	 *	@return		Deprecation
	 */
	public function setWarningVersion( string $version ): self
	{
		$this->warnVersion		= $version;
		return $this;
	}

	//  --  PROTECTED  --  //

	/**
	 *	Constructor, needs to be called statically by getInstance.
	 *	Will call onInit at the end to handle self detection.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct()
	{
		$this->phpVersion	= (string) phpversion();
		$this->onInit();
	}

	/**
	 *	Event to handle self detection on end of static construction.
	 *	ATTENTION: Must be set in inheriting classes, at least as an empty method!
	 *
	 *	Will detect library version.
	 *	Will set error version to current library version by default.
	 *	Will not set an exception version.
	 *	@access		protected
	 *	@return		void
	 */
	protected function onInit(): void
	{
		$iniFilePath			= dirname( __DIR__ ).'/Common.ini';
		$iniFileData			= parse_ini_file( $iniFilePath, TRUE );
		$this->version			= $iniFileData['project']['version'];
		$this->errorVersion		= '';
		$this->exceptionVersion	= '';
		$this->warnVersion		= '';
	}
}
