<?php
/*
*/
/**
 *	Indicator for deprecated methods.
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Indicator for deprecated methods.
 *
 *	Example:
 *		Deprecation::getInstance()
 *			->setErrorVersion( '0.9' )
 *			->ExceptionVersion( '0.9' )
 *			->message(  'Use method ... instead' );
 *
 *	@category		Library
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Deprecation{

	protected $version;
	protected $errorVersion;
	protected $exceptionVersion;
	protected $phpVersion;

	/**
	 *	Contructor, needs to be called statically by getInstance.
	 *	Will detect library version.
	 *	Will set error version to curent library version by default.
	 *	Will not set an exception version.
	 *	@access		protected
	 *	@return		void
	 */
	protected function __construct(){
		$iniFilePath		= dirname( __DIR__ ).'/Common.ini';
		$iniFileData		= parse_ini_file( $iniFilePath, TRUE );
		$this->version		= $iniFileData['project']['version'];
		$this->phpVersion	= phpversion();
		$this->errorVersion	= $this->version;
	}

	/**
	 *	Creates a new deprection object.
	 *	@static
	 *	@access		public
	 *	@return		Deprecation
	 */
	static public function getInstance(){
		return new static();
	}

	/**
	 *	Show message as exception or deprecation error, depending on set versions and PHP version.
	 *	Will throw an exception if set exception version reached detected library version.
	 *	Will throw a deprecation error if set error version reached detected library version using PHP 5.3+.
	 *	Will throw a deprecation notice if set error version reached detected library version using PHP lower 5.3.
	 *	@access		public
	 *	@param		string		$version	Library version to start showing deprecation error or notice
	 *	@return		void
	 *	@throws		Exception				if set exception version reached detected library version
	 */
	public function message( $message ){
		if( $this->exceptionVersion )
			if( version_compare( $this->version, $this->exceptionVersion ) >= 0 )
				throw new Exception( 'Deprecated: '.$message );
		if( version_compare( $this->version, $this->errorVersion ) >= 0 ){
			self::notify( $message );
		}
	}

	static public function notify( $message ){
		if( version_compare( phpversion(), "5.3.0" ) >= 0 )
			trigger_error( $message, E_USER_DEPRECATED );
		else
			trigger_error( 'Deprecated: '.$message, E_USER_NOTICE );
	}

	/**
	 *	Set library version to start showing deprecation error or notice.
	 *	Returns deprecation object for chainability.
	 *	@access		public
	 *	@param		string		$version	Library version to start showing deprecation error or notice
	 *	@return		Deprecation
	 */
	public function setErrorVersion( $version ){
		$this->errorVersion		= $version;
		return $this;
	}

	/**
	 *	Set library version to start throwing deprecation exception.
	 *	Returns deprecation object for chainability.
	 *	@access		public
	 *	@param		string		$version	Library version to start throwing deprecation exception
	 *	@return		Deprecation
	 */
	public function setExceptionVersion( $version ){
		$this->exceptionVersion		= $version;
		return $this;
	}
}
?>
