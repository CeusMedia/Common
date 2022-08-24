<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/*
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
 */

namespace CeusMedia\Common\FS\Autoloader;

/**
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
 *	@license	http://www.opensource.org/licenses/mit-license.html  MIT License
 *	@author		Jonathan H. Wage <jonwage@gmail.com>
 *	@author		Roman S. Borschel <roman@code-factory.org>
 *	@author		Matthew Weier O'Phinney <matthew@zend.com>
 *	@author		Kris Wallsmith <kris.wallsmith@gmail.com>
 *	@author		Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class Psr0
{
	/**	@var		string		$fileExtension */
	private $fileExtension = '.php';

	/**	@var		string		$namespace */
	private $namespace;

	/**	@var		string		$includePath */
	private $includePath;

	/**	@var		string		$namespaceSeparator */
	private $namespaceSeparator = '\\';

	/**
	 *	Creates a new <tt>SplClassLoader</tt> that loads classes of the
	 *	specified namespace.
	 *
	 *	@param		string|NULL		$ns		The namespace to use.
	 */
	public function __construct( ?string $ns = NULL, $includePath = NULL )
	{
		$this->namespace = $ns;
		$this->includePath = $includePath;
	}

	/**
	 * Sets the namespace separator used by classes in the namespace of this class loader.
	 *
	 *	@param			string		$sep		The separator to use.
	 *	@noinspection	PhpUnused
	 */
	public function setNamespaceSeparator( string $sep ): self
	{
		$this->namespaceSeparator = $sep;
		return $this;
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
	 *	Sets the base include path for all class files in the namespace of this class loader.
	 *
	 *	@param			string		$includePath
	 *	@return			self
	 *	@noinspection	PhpUnused
	 */
	public function setIncludePath( string $includePath ): self
	{
		$this->includePath = $includePath;
		return $this;
	}

	/**
	 *	Gets the base include path for all class files in the namespace of this class loader.
	 *
	 *	@return			string		$includePath
	 *	@noinspection	PhpUnused
	 */
	public function getIncludePath(): string
	{
		return $this->includePath;
	}

	/**
	 *	Sets the file extension of class files in the namespace of this class loader.
	 *
	 *	@param			string		$fileExtension
	 *	@return			self
	 *	@noinspection	PhpUnused
	 */
	public function setFileExtension( string $fileExtension ): self
	{
		$this->fileExtension = $fileExtension;
		return $this;
	}

	/**
	 *	Gets the file extension of class files in the namespace of this class loader.
	 *
	 * @return			string		$fileExtension
	 * @noinspection	PhpUnused
	 */
	public function getFileExtension(): string
	{
		return $this->fileExtension;
	}

	/**
	 *	Installs this class loader on the SPL autoload stack.
	 *
	 *	@return			self
	 */
	public function register(): self
	{
		spl_autoload_register( array( $this, 'loadClass' ) );
		return $this;
	}

	/**
	 *	Uninstalls this class loader from the SPL autoloader stack.
	 *
	 *	@return			self
	 */
	public function unregister(): self
	{
		spl_autoload_unregister( array( $this, 'loadClass' ) );
		return $this;
	}

	/**
	 *	Loads the given class or interface.
	 *
	 *	@param			string		$className		The name of the class to load.
	 *	@return			void
	 */
	public function loadClass( string $className )
	{
		if (NULL === $this->namespace || $this->namespace.$this->namespaceSeparator === substr($className, 0, strlen($this->namespace.$this->namespaceSeparator))) {
			$fileName = '';
			$namespace = '';
			if (false !== ($lastNsPos = strripos($className, $this->namespaceSeparator))) {
				$namespace = substr($className, 0, $lastNsPos);
				$className = substr($className, $lastNsPos + 1);
				$fileName = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}
			$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->fileExtension;
			$filePath = ($this->includePath !== NULL ? $this->includePath . DIRECTORY_SEPARATOR : '') . $fileName;

			if (file_exists($filePath)) {
				require $filePath;
			}
		}
	}
}
