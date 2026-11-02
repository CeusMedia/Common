<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Creates test classes for PHPUnit using the class parser and templates.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP_Test
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\PHP\Test;

use CeusMedia\Common\FS\Folder\Editor as FolderEditor;
use CeusMedia\Common\FS\Folder\RecursiveRegexFilter as RecursiveFolderRegexFilter;
use CeusMedia\PhpParser\Parser\Regular as RegularParser;
use CeusMedia\PhpParser\Structure\Class_;
use CeusMedia\PhpParser\Structure\Method_;
use RuntimeException;

/**
 *	Creates test classes for PHPUnit using the class parser and templates.
 *
 *	Classes are given by their namespaced name, relative to the source namespace (e.g. FS\File\Backup)
 *	or absolute (e.g. CeusMedia\Common\FS\File\Backup). Test classes are created in the test path,
 *	mirroring the folder structure of the source path, within the test namespace.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP_Test
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Creator
{
	/**	@var		string					$className			Short class name, e.g. Backup */
	protected string $className				= '';

	/**	@var		string					$classFile			Class file, e.g. src/FS/File/Backup.php */
	protected string $classFile				= '';

	/**	@var		string					$classPath			Class path, e.g. FS.File.Backup */
	protected string $classPath				= '';

	/**	@var		string					$fileName			File name of class, without extension */
	protected string $fileName				= '';

	/**	@var		string[]				$pathParts			Namespace parts relative to source namespace */
	protected array $pathParts				= [];

	/**	@var		string					$pathTemplates		Path to templates */
	protected string $pathTemplates			= '';

	/**	@var		string					$templateClass		File name of test class template */
	protected string $templateClass			= 'Creator_class.tmpl';

	/**	@var		string					$templateException	File name of exception test method template */
	protected string $templateException		= 'Creator_exception.tmpl';

	/**	@var		string					$templateMethod		File name of test method template */
	protected string $templateMethod		= 'Creator_method.tmpl';

	/**	@var		Class_|NULL				$class				Parsed class */
	protected ?Class_ $class				= NULL;

	/**	@var		string|NULL				$targetFile			Test class file to create */
	protected ?string $targetFile			= NULL;

	/**	@var		string					$sourceNamespace	Namespace of source classes */
	protected string $sourceNamespace;

	/**	@var		string					$testNamespace		Namespace of test classes */
	protected string $testNamespace;

	/**	@var		string					$sourcePath			Path of source classes */
	protected string $sourcePath;

	/**	@var		string					$testPath			Path of test classes */
	protected string $testPath;

	/**	@var		string					$baseClass			Namespaced name of test case base class */
	protected string $baseClass				= 'PHPUnit\\Framework\\TestCase';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$sourceNamespace	Namespace of source classes
	 *	@param		string		$testNamespace		Namespace of test classes
	 *	@param		string		$sourcePath			Path of source classes, mapped to source namespace (PSR-4)
	 *	@param		string		$testPath			Path of test classes, mapped to test namespace (PSR-4)
	 *	@return		void
	 */
	public function __construct( string $sourceNamespace = 'CeusMedia\\Common', string $testNamespace = 'CeusMedia\\CommonTest', string $sourcePath = 'src/', string $testPath = 'test/' )
	{
		$this->pathTemplates	= __DIR__.'/';
		$this->sourceNamespace	= trim( $sourceNamespace, '\\' );
		$this->testNamespace	= trim( $testNamespace, '\\' );
		$this->sourcePath		= rtrim( $sourcePath, '/' ).'/';
		$this->testPath			= rtrim( $testPath, '/' ).'/';
	}

	/**
	 *	Reads and stores all class information and creates test class.
	 *	@access		public
	 *	@param		string		$className		Name of class to create test class for, relative to source namespace or absolute
	 *	@param		bool		$force			Flag: overwrite test class file if already existing
	 *	@return		void
	 *	@throws		RuntimeException			if test class is already existing and force is not set
	 *	@throws		RuntimeException			if class file is not existing or does not contain the class
	 */
	public function createForFile( string $className, bool $force = FALSE ): void
	{
		$this->readPath( $className );
		if( file_exists( $this->targetFile ) && !$force )
			throw new RuntimeException( 'Test Class for Class "'.$this->getNamespacedClassName().'" is already existing.' );
		$this->readClass();
		$this->buildTestClass();
	}

	/**
	 *	Indexes a path and creates test classes for all found classes.
	 *	Existing test classes are skipped, unless force is set. Files without class (e.g. interfaces or traits) are skipped.
	 *	@access		public
	 *	@param		string		$path		Path to index, relative to source path, e.g. FS/File or FS\File
	 *	@param		boolean		$force		Flag: overwrite test class if already existing
	 *	@return		int						Number of created test classes
	 */
	public function createForFolder( string $path, bool $force = FALSE ): int
	{
		$counter	= 0;
		$path		= trim( str_replace( '\\', '/', $path ), '/' );
		$fullPath	= $this->sourcePath.( '' !== $path ? $path.'/' : '' );
		if( !is_dir( $fullPath ) )
			return 0;
		$filter	= new RecursiveFolderRegexFilter( $fullPath, '@\.php$@i', TRUE, FALSE );
		foreach( $filter as $entry ){
			$className	= substr( $entry->getPathname(), strlen( $this->sourcePath ) );
			$className	= (string) preg_replace( '@\.php$@i', '', $className );
			$className	= str_replace( '/', '\\', $className );
			$this->readPath( $className );
			if( file_exists( (string) $this->targetFile ) && !$force )
				continue;
			if( !$this->readClass( FALSE ) )
				continue;
			$this->buildTestClass();
			$counter++;
		}
		return $counter;
	}

	/**
	 *	Returns path of test class file created last.
	 *	@access		public
	 *	@return		string|NULL
	 */
	public function getTargetFile(): ?string
	{
		return $this->targetFile;
	}

	/**
	 *	Sets namespaced name of base class of created test classes.
	 *	@access		public
	 *	@param		string		$baseClass		Namespaced name of test case base class, e.g. PHPUnit\Framework\TestCase
	 *	@return		self
	 */
	public function setBaseClass( string $baseClass ): self
	{
		$this->baseClass	= trim( $baseClass, '\\' );
		return $this;
	}

	/**
	 *	Sets individual Test Class Template.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Test Class Template
	 *	@return		self
	 */
	public function setClassTemplate( string $fileName ): self
	{
		$this->templateClass	= $fileName;
		return $this;
	}

	/**
	 *	Sets individual Test Class Exception Template.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Test Class Exception Template
	 *	@return		self
	 */
	public function setExceptionTemplate( string $fileName ): self
	{
		$this->templateException	= $fileName;
		return $this;
	}

	/**
	 *	Sets individual Test Class Method Template.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Test Class Method Template
	 *	@return		self
	 */
	public function setMethodTemplate( string $fileName ): self
	{
		$this->templateMethod	= $fileName;
		return $this;
	}

	/**
	 *	Sets Path to individual Templates.
	 *	@access		public
	 *	@param		string		$pathTemplates		Path to Templates.
	 *	@return		self
	 */
	public function setTemplatePath( string $pathTemplates ): self
	{
		$this->pathTemplates	= $pathTemplates;
		return $this;
	}

	//  --  PROTECTED  --  //

	/**
	 *	Creates and returns list of exception test methods for a method, using a template.
	 *	@access		protected
	 *	@param		Method_		$method		Parsed method
	 *	@return		string[]
	 */
	protected function buildExceptionTestMethod( Method_ $method ): array
	{
		$methods	= [];
		$methodName	= (string) $method->getName();
		$exceptions	= $this->getExceptionsOfMethod( $method );
		$counter	= 0;
		foreach( $exceptions as $exception ){
			$counter	= count( $exceptions ) > 1 ? $counter + 1 : '';
			$methods[]	= $this->renderTemplate( $this->templateException, [
				'methodName'		=> $methodName,
				'MethodName'		=> ucfirst( $methodName ),
				'exceptionClass'	=> $exception,
				'counter'			=> (string) $counter,
			] );
		}
		return $methods;
	}

	/**
	 *	Creates test class from class data and a template.
	 *	@access		protected
	 *	@return		void
	 */
	protected function buildTestClass(): void
	{
		$baseClassParts	= explode( '\\', $this->baseClass );
		$template	= $this->renderTemplate( $this->templateClass, [
			'methodTests'			=> implode( '', $this->buildTestMethods() ),
			'testNamespace'			=> $this->getTestNamespace(),
			'testClassName'			=> $this->className.'Test',
			'baseClass'				=> $this->baseClass,
			'baseClassName'			=> end( $baseClassParts ),
			'classPackage'			=> implode( '.', $this->pathParts ),
			'date'					=> date( 'd.m.Y' ),
		] );
		if( !str_starts_with( ltrim( $template ), '<?php' ) )
			$template	= '<?php'.PHP_EOL.$template;

		FolderEditor::createFolder( dirname( (string) $this->targetFile ) );
		if( FALSE === file_put_contents( (string) $this->targetFile, $template ) )
			throw new RuntimeException( 'Test class file "'.$this->targetFile.'" could not be written' );
	}

	/**
	 *	Creates and returns list of test methods for all public methods, using templates.
	 *	@access		protected
	 *	@return		string[]
	 */
	protected function buildTestMethods(): array
	{
		$methods	= [];
		if( NULL === $this->class )
			return $methods;
		foreach( $this->class->getMethods( FALSE ) as $method ){
			if( 'public' !== ( $method->getAccess() ?? 'public' ) )
				continue;
			$methodName	= (string) $method->getName();
			$methods[]	= $this->renderTemplate( $this->templateMethod, [
				'methodName'	=> $methodName,
				'MethodName'	=> ucfirst( $methodName ),
			] );
			foreach( $this->buildExceptionTestMethod( $method ) as $exceptionTest )
				$methods[]	= $exceptionTest;
		}
		return $methods;
	}

	/**
	 *	Returns list of exception classes thrown by a method, collected from doc block and source code.
	 *	@access		protected
	 *	@param		Method_		$method		Parsed method
	 *	@return		string[]
	 */
	protected function getExceptionsOfMethod( Method_ $method ): array
	{
		$exceptions	= [];
		foreach( $method->getThrows() as $throws )
			if( NULL !== $throws->getName() && '' !== $throws->getName() )
				$exceptions[]	= ltrim( $throws->getName(), '\\' );
		$content	= implode( "\n", $method->getSourceCode() );
		foreach( $this->getExceptionsFromMethodContent( $content ) as $exception )
			$exceptions[]	= $exception;
		return array_values( array_unique( $exceptions ) );
	}

	/**
	 *	Reads and returns thrown exception classes from method content.
	 *	@access		protected
	 *	@param		string		$content		Method content
	 *	@return		string[]
	 */
	protected function getExceptionsFromMethodContent( string $content ): array
	{
		$exceptions	= [];
		$content	= (string) preg_replace( '@/\*.*?\*/@s', '', $content );
		$matches	= [];
		if( 0 < (int) preg_match_all( '@throw\s+new\s+\\\\?([\w\\\\]+)@', $content, $matches ) )
			foreach( $matches[1] as $exception )
				$exceptions[]	= $exception;
		return array_values( array_unique( $exceptions ) );
	}

	/**
	 *	Returns namespaced name of class to test.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getNamespacedClassName(): string
	{
		return implode( '\\', array_merge( [$this->sourceNamespace], $this->pathParts, [$this->className] ) );
	}

	/**
	 *	Combines and returns path parts and file name with a delimiter.
	 *	@access		protected
	 *	@param		string		$delimiter
	 *	@return		string
	 */
	protected function getPath( string $delimiter ): string
	{
		return implode( $delimiter, array_merge( $this->pathParts, [$this->fileName] ) );
	}

	/**
	 *	Returns namespace of test class.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getTestNamespace(): string
	{
		return implode( '\\', array_merge( [$this->testNamespace], $this->pathParts ) );
	}

	/**
	 *	Parses class file and stores parsed class.
	 *	@access		protected
	 *	@param		bool		$strict		Flag: throw exception if class file or class is not existing
	 *	@return		bool
	 *	@throws		RuntimeException		if class file or class is not existing and strict mode is enabled
	 */
	protected function readClass( bool $strict = TRUE ): bool
	{
		$this->class	= NULL;
		if( !file_exists( $this->classFile ) ){
			if( $strict )
				throw new RuntimeException( 'Class file "'.$this->classFile.'" is not existing' );
			return FALSE;
		}
		$file	= ( new RegularParser() )->parseFile( $this->classFile, '' );
		foreach( $file->getClasses() as $class ){
			if( $class->getName() === $this->className ){
				$this->class	= $class;
				return TRUE;
			}
		}
		if( $strict )
			throw new RuntimeException( 'Class "'.$this->getNamespacedClassName().'" not found in file "'.$this->classFile.'"' );
		return FALSE;
	}

	/**
	 *	Splits class name into namespace parts and class name and sets file names.
	 *	Accepts namespaced names (absolute or relative to source namespace) and underscored names (e.g. FS_File_Backup).
	 *	@access		protected
	 *	@param		string		$className		Name of class to test
	 *	@return		void
	 */
	protected function readPath( string $className ): void
	{
		$className	= trim( $className, '\\' );
		if( !str_contains( $className, '\\' ) )
			$className	= str_replace( '_', '\\', $className );
		if( str_starts_with( $className, $this->sourceNamespace.'\\' ) )
			$className	= substr( $className, strlen( $this->sourceNamespace ) + 1 );
		$this->pathParts	= explode( '\\', $className );
		$this->fileName		= (string) array_pop( $this->pathParts );
		$this->className	= $this->fileName;
		$this->classFile	= $this->sourcePath.$this->getPath( '/' ).'.php';
		$this->classPath	= $this->getPath( '.' );
		$this->targetFile	= $this->testPath.$this->getPath( '/' ).'Test.php';
	}

	/**
	 *	Loads template and replaces placeholders, including the general ones for class name, file and path.
	 *	@access		protected
	 *	@param		string					$template		File name of template, relative to template path or absolute
	 *	@param		array<string,string>	$data			Map of placeholders and values
	 *	@return		string
	 */
	protected function renderTemplate( string $template, array $data ): string
	{
		$fileName	= str_contains( $template, '/' ) ? $template : $this->pathTemplates.$template;
		$content	= file_get_contents( $fileName );
		if( FALSE === $content )
			throw new RuntimeException( 'Template "'.$fileName.'" could not be read' );
		$data	= array_merge( [
			'className'				=> $this->className,
			'classNamespacedName'	=> $this->getNamespacedClassName(),
			'classFile'				=> $this->classFile,
			'classPath'				=> $this->classPath,
		], $data );
		foreach( $data as $key => $value )
			$content	= str_replace( '{'.$key.'}', $value, $content );
		return $content;
	}
}
