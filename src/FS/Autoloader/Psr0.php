<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * PSR0 autoloader.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Autoloader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		http://www.opensource.org/licenses/mit-license.html  MIT License
 *	@link			https://github.com/CeusMedia/Common
 *	@author			Jonathan H. Wage <jonwage@gmail.com>
 *	@author			Roman S. Borschel <roman@code-factory.org>
 *	@author			Matthew Weier O'Phinney <matthew@zend.com>
 *	@author			Kris Wallsmith <kris.wallsmith@gmail.com>
 *	@author			Fabien Potencier <fabien.potencier@symfony-project.org>
 */

namespace CeusMedia\Common\FS\Autoloader;

/**
 *	PSR0 autoloader.
 *
 *	SplClassLoader implementation that implements the technical interoperability
 *	standards for PHP 5.3 namespaces and class names.
 *
 *	http://groups.google.com/group/php-standards/web/psr-0-final-proposal?pli=1
 *
 *	// Example which loads classes for the Doctrine Common package in the
 *	// Doctrine\Common namespace.
 *
 *	use \CeusMedia\Common\FS\Autoloader\Psr0;
 *
 *	$loader = new Psr0('Doctrine\Common', '/path/to/doctrine');
 *	$loader->register();
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_Autoloader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2024 Christian Würker
 *	@license		http://www.opensource.org/licenses/mit-license.html  MIT License
 *	@link			https://github.com/CeusMedia/Common
 *	@author			Jonathan H. Wage <jonwage@gmail.com>
 *	@author			Roman S. Borschel <roman@code-factory.org>
 *	@author			Matthew Weier O'Phinney <matthew@zend.com>
 *	@author			Kris Wallsmith <kris.wallsmith@gmail.com>
 *	@author			Fabien Potencier <fabien.potencier@symfony-project.org>
 *
 *	Long syntax:
 *
 *		$loader	= new Psr0( 'Foo\Bar' );
 *		$loader->setIncludePath( '/path/to/packages/foo-bar/src' );
 *		$loader->setFileExtension( 'php8' );
 *		$loader->register();
 *
 *	Method chaining:
 *
 *		Psr0::getInstance( 'Foo\Bar' )
 *			->setIncludePath( '/path/to/packages/foo-bar/src' )
 *			->setFileExtension( 'php8' );
 *			->register();
 *
 *	Short syntax:
 *
 *		Psr0::getInstance( 'Foo\Bar', '/path/to/packages/foo-bar/src' )->register();
 */
class Psr0
{
	protected string $fileExtension 		= 'php';

	protected ?string $namespace			= NULL;

	protected ?string $includePath			= NULL;

	protected string $namespaceSeparator	= '\\';

	/**
	 *	Creates a new SplClassLoader that loads classes of the specified namespace, statically.
	 *	The loader itself will not be registered, right now.
	 *
	 *	@param		string|NULL		$namespace		The namespace to use
	 *	@param		string|NULL		$includePath	Root location of class files
	 *	@param		string|NULL		$fileExtension	Supported extension of class files
	 *	@return		self
	 */
	public static function getInstance( ?string $namespace = NULL, ?string $includePath = NULL, ?string $fileExtension = NULL ): self
	{
		return new self( $namespace, $includePath, $fileExtension );
	}

	/**
	 *	Creates a new SplClassLoader that loads classes of the specified namespace.
	 *
	 *	@param		string|NULL		$namespace		The namespace to use
	 *	@param		string|NULL		$includePath	Root location of class files
	 *	@param		string|NULL		$fileExtension	Supported extension of class files
	 */
	public function __construct( ?string $namespace = NULL, ?string $includePath = NULL, ?string $fileExtension = NULL )
	{
		$this->namespace		= $namespace;
		$this->includePath		= $includePath;
		$this->fileExtension	= $fileExtension ?? $this->fileExtension;
	}

	/**
	 *	Gets the file extension of class files in the namespace of this class loader.
	 *	Typically, this will be 'php'.
	 *
	 * @return			string
	 * @noinspection	PhpUnused
	 */
	public function getFileExtension(): string
	{
		return $this->fileExtension;
	}

	/**
	 *	Gets the base include path for all class files in the namespace of this class loader.
	 *
	 *	@return			string|NULL		$includePath
	 *	@noinspection	PhpUnused
	 */
	public function getIncludePath(): ?string
	{
		return $this->includePath;
	}

	/**
	 *	Gets the namespace separator used by classes in the namespace of this class loader.
	 *
	 *	@return			string
	 *	@noinspection	PhpUnused
	 */
	public function getNamespaceSeparator(): string
	{
		return $this->namespaceSeparator;
	}

	/**
	 *	Loads the given class or interface.
	 *
	 *	@param			string		$className		The name of the class to load.
	 *	@return			void
	 */
	public function loadClass( string $className ): void
	{
		if( !$this->isInNamespace( $className ) )
			return;

		$filePath	= $this->mapClassNameToFilePath( $className );
		if( file_exists( $filePath ) )
			require $filePath;
	}

	/**
	 *	Installs this class loader on the SPL autoload stack.
	 *
	 *	@return			self
	 */
	public function register(): self
	{
		spl_autoload_register( [$this, 'loadClass'] );
		return $this;
	}

	/**
	 *	Sets the file extension of class files in the namespace of this class loader.
	 *	Typically, this will be 'php'.
	 *
	 *	@param			string		$fileExtension
	 *	@return			self
	 *	@noinspection	PhpUnused
	 */
	public function setFileExtension( string $fileExtension ): self
	{
		$this->fileExtension	= $fileExtension;
		return $this;
	}

	/**
	 *	Sets the base include path for all class files in the namespace of this class loader.
	 *
	 *	@param			string		$includePath
	 *	@return			self
	 *	@noinspection	PhpUnused
	 */
	public function setIncludePath( string $includePath ): self
	{
		$this->includePath	= $includePath;
		return $this;
	}

	/**
	 * Sets the namespace separator used by classes in the namespace of this class loader.
	 *
	 *	@param			string		$separator		The separator to use, \\ for namespaces, / for
	 *	@noinspection	PhpUnused
	 */
	public function setNamespaceSeparator( string $separator ): self
	{
		$this->namespaceSeparator	= $separator;
		return $this;
	}

	/**
	 *	Uninstalls this class loader from the SPL autoloader stack.
	 *
	 *	@return			self
	 */
	public function unregister(): self
	{
		spl_autoload_unregister( [$this, 'loadClass'] );
		return $this;
	}

	/**
	 *	@param		string		$className
	 *	@return		bool
	 */
	protected function isInNamespace( string $className ): bool
	{
		if( NULL !== $this->namespace )
			if( !str_starts_with( $className, $this->namespace.$this->namespaceSeparator ) )
				return FALSE;
		return TRUE;
	}

	/**
	 *	@param		string		$className
	 *	@return		string
	 */
	protected function mapClassNameToFilePath( string $className ): string
	{
		$namespacePath	= '';
		$lastNsPos		= strripos( $className, $this->namespaceSeparator );
		if( FALSE !== $lastNsPos ){
			$namespace		= substr( $className, 0, $lastNsPos );
			$className		= substr( $className, $lastNsPos + 1 );
			$namespacePath	= str_replace(
				$this->namespaceSeparator,
				DIRECTORY_SEPARATOR,
				$namespace
			).DIRECTORY_SEPARATOR;
		}
		$classPath		= str_replace( '_', DIRECTORY_SEPARATOR, $className );
		$fileName		= $classPath.'.'.$this->fileExtension;
		$basePath		= NULL !== $this->includePath ? $this->includePath.DIRECTORY_SEPARATOR : '';
		return $basePath.$namespacePath.$fileName;
	}
}
