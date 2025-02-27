<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Created Test Class for PHP Unit Tests using Class Parser and two Templates.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\PHP\Test;

use CeusMedia\Common\FS\Folder\Editor as FolderEditor;
use CeusMedia\Common\FS\Folder\RecursiveRegexFilter as RecursiveFolderRegexFilter;
use CeusMedia\PhpParser\Parser\Regular as RegularParser;
use RuntimeException;

/**
 *	Created Test Class for PHP Unit Tests using Class Parser and two Templates.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP_Test
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Creator
{
	/**	@var		string					$className			Class Name, e.g. Package_Class */
	protected string $className				= '';

	/**	@var		string					$classFile			Class Name, eg. de/ceus-media/package/Class.php */
	protected string $classFile				= '';

	/**	@var		string					$classPath			Class Path, eg. de.ceus-media.package.Class */
	protected string $classPath				= '';

	/**	@var		string					$fileName			File Name of Class */
	protected string $fileName				= '';

	/**	@var		array					$pathParts			Split Path Parts in lower Case */
	protected array $pathParts				= [];

	/**	@var		string					$pathParts			... */
	protected string $pathTemplates			= '';

	/**	@var		string					$templateClass		File Name of Test Class Template */
	protected string $templateClass			= 'Creator_class.tmpl';

	/**	@var		string					$templateClass		File Name of Exception Test Method Template */
	protected string $templateException		= 'Creator_exception.tmpl';

	/**	@var		string					$templateClass		File Name of Test Method Template */
	protected string $templateMethod		= 'Creator_method.tmpl';

	protected array $data					= [];

	protected ?string $targetFile			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function  __construct()
	{
		$this->pathTemplates	= __DIR__.'/';
	}

	/**
	 *	Creates and returns array of Exception Test Methods from Method Name, Method Content and a Template.
	 *	@access		protected
	 *	@param		string		$methodName
	 *	@param		string		$content
	 *	@return		array
	 */
	protected function buildExceptionTestMethod( string $methodName, string $content ): array
	{
		$methods	= [];
		$exceptions	= $this->getExceptionsFromMethodContent( $content );
		$counter	= 0;
		foreach( $exceptions as $exception ){
			$counter	= count( $exceptions ) > 1 ? $counter + 1 : "";
			$template	= file_get_contents( $this->templateException );
			$template	= str_replace( '{methodName}', $methodName, $template );
			$template	= str_replace( '{MethodName}', ucFirst( $methodName ), $template );
			$template	= str_replace( '{className}', $this->className, $template );
			$template	= str_replace( '{exceptionClass}', $exception, $template );
			$template	= str_replace( '{counter}', $counter, $template );
			$methods[]	= $template;
		}
		return $methods;
	}

	/**
	 *	Creates Test Class from Class Data and a Template.
	 *	@access		protected
	 *	@return		void
	 */
	protected function buildTestClass(): void
	{
		$methods	= $this->buildTestMethods();

		$template	= file_get_contents( $this->templateClass );
		$template	= str_replace( '{methodTests}', implode( '', $methods ), $template );
		$template	= str_replace( '{className}', $this->className, $template );
		$template	= str_replace( '{classFile}', $this->classFile, $template );
		$template	= str_replace( '{classPath}', $this->classPath, $template );
		$template	= str_replace( '{classPackage}', $this->data['package'], $template );
		$template	= str_replace( '{date}', date( 'd.m.Y' ), $template );
		$template	= "<?php".PHP_EOL.$template.PHP_EOL."?>";

		FolderEditor::createFolder( dirname( $this->targetFile ) );
		file_put_contents( $this->targetFile, $template );
	}

	/**
	 *	Creates and returns array of Test Methods from Class Data and a Template.
	 *	@access		protected
	 *	@return		array
	 */
	protected function buildTestMethods(): array
	{
		$lastMethod	= NULL;
		$methods	= [];
		foreach( $this->data['methods'] as $methodName => $methodData ){
			if( $methodData['access'] == 'protected' )
				continue;
			if( $methodData['access'] == 'private' )
				continue;
			if( $lastMethod ){
				$pattern	= '@.*function '.$lastMethod.'(.*)function '.$methodName.'.*@si';
				$content	= file_get_contents( $this->classFile );
				$content	= preg_replace( $pattern, '\\1', $content );
				$exceptions	= $this->buildExceptionTestMethod( $lastMethod, $content );
				foreach( $exceptions as $exception )
					$methods[]	= $exception;
			}
			$methodNames	= array_keys( $this->data['methods'] );
			$methodNames	= array_slice( $methodNames, -1 );
			if( $methodName == array_pop( $methodNames ) ){
				$pattern	= '@.*function '.$methodName.'(.*)$@si';
				$content	= file_get_contents( $this->classFile );
				$content	= preg_replace( $pattern, '\\1', $content );
				$exceptions	= $this->buildExceptionTestMethod( $methodName, $content );
				foreach( $exceptions as $exception )
					$methods[]	= $exception;
			}
			$template	= file_get_contents( $this->templateMethod );
			$template	= str_replace( '{methodName}', $methodName, $template );
			$template	= str_replace( '{MethodName}', ucFirst( $methodName ), $template );
			$template	= str_replace( '{className}', $this->className, $template );
			$methods[]	= $template;
			$lastMethod	= $methodName;
		}
		return $methods;
	}

	/**
	 *	Reads and stores all Class Information and creates Test Class.
	 *	@access		public
	 *	@param		string		$className		Name of Class to create Test Class for.
	 *	@param		bool		$force			Flag: overwrite Test Class File if already existing
	 *	@return		void
	 */
	public function createForFile( string $className, bool $force = FALSE ): void
	{
		$this->templateClass		= $this->pathTemplates.$this->templateClass;
		$this->templateException	= $this->pathTemplates.$this->templateException;
		$this->templateMethod		= $this->pathTemplates.$this->templateMethod;

		$this->className	= $className;
		$this->readPath();
		$this->classFile	= 'src/'.$this->getPath( '/' ).'.php';
		$this->classPath	= $this->getPath( '.' );
		$this->targetFile	= 'test/'.$this->getPath( '/' ).'Test.php';

		if( file_exists( $this->targetFile ) && !$force )
			throw new RuntimeException( 'Test Class for Class "'.$this->className.'" is already existing.' );

		$parser	= new RegularParser();
		$data	= $parser->parseFile( $this->classFile, '' );
		$this->data	= $data['class'];

#		$parser				= new ClassParser( $this->classFile );
#		$this->data			= $parser->getClassData();
#		print_m( $data );
#		die;
		$this->dumpClassData();
		$this->buildTestClass();
	}

	/**
	 *	Indexes a Path and calls Test Case Creator for all found Classes.
	 *	@access		public
	 *	@param		string		$path		Path to index
	 *	@param		boolean		$force		Flag: overwrite Test Class if already existing
	 *	@return		int
	 */
	public function createForFolder( string $path, bool $force ): int
	{
		$counter	= 0;
		$fullPath	= 'src/'.str_replace( '_', '/', $path ).'/';
		if( file_exists( $fullPath ) && is_dir( $fullPath ) ){
			$filter	= new RecursiveFolderRegexFilter( $fullPath, '@\.php$@i', TRUE, FALSE );
			foreach( $filter as $entry ){
				$counter++;
				$className	= $entry->getPathname();
				$className	= substr( $className, strlen( $fullPath ) );
				$className	= preg_replace( '@\.php$@i', '', $className );
				$className	= str_replace( '/', '_', $className );
				$creator	= new Creator();
				$creator->createForFile( $path.'_'.$className, $force );
			}
		}
		return $counter;
	}

	/**
	 *	Dumps all Class Data into an HTML File, for Testing and Development of this Creator Class.
	 *	@access		private
	 *	@return		void
	 */
	private function dumpClassData(): void
	{
		ob_start();
		print_m( $this->data );
		$data	= ob_get_clean();
		file_put_contents( 'lastCreatedTest.cache', '<xmp>'.$data.'</xmp>' );
	}

	/**
	 *	Reads and returns thrown Exception Classes from Method Content.
	 *	@access		protected
	 *	@param		string		$content		Method Content
	 *	@return		array
	 */
	protected function getExceptionsFromMethodContent( string $content ): array
	{
		$exceptions	= [];
		$content	= preg_replace( '@/\*(.*)\*/@si', '', $content );
		$lines		= explode( "\n", $content );
		foreach( $lines as $line ){
			$matches	= [];
			$match		= preg_match( '@throw new (\w+)Exception@', $line, $matches );
			if( 0 !== $match )
				$exceptions[]	= $matches[1].'Exception';
		}
		return $exceptions;
	}

	/**
	 *	Combines and returns Path Parts and File Nane with a Delimiter.
	 *	@access		protected
	 *	@param		string		$delimiter
	 *	@return		string
	 */
	protected function getPath( string $delimiter ): string
	{
		$path	= implode( $delimiter, $this->pathParts );
		return $path.$delimiter.$this->fileName;
	}

	/**
	 *	Splits Path to Class and stores Parts in lower Case.
	 *	@access		protected
	 *	@return		void
	 */
	protected function readPath(): void
	{
		$this->pathParts	= explode( '_', $this->className );
		$this->fileName		= array_pop( $this->pathParts );
		for( $i=0; $i<count( $this->pathParts ); $i++ )
			$this->pathParts[$i]	= $this->pathParts[$i];
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
}
