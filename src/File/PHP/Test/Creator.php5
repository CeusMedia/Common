<?php
/**
 *	Created Test Class for PHP Unit Tests using Class Parser and two Templates.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		file.php.test
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
import( 'de.ceus-media.ui.ClassParser' );
import( 'de.ceus-media.file.php.Parser' );
import( 'de.ceus-media.folder.Editor' );
import( 'de.ceus-media.folder.RecursiveRegexFilter' );
/**
 *	Created Test Class for PHP Unit Tests using Class Parser and two Templates.
 *	@category		cmClasses
 *	@package		file.php.test
 *	@uses			UI_ClassParser
 *	@uses			Folder_Editor
 *	@uses			Folder_RecursiveRegexFilter
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_PHP_Test_Creator
{
	/**	@var		string			$className			Class Name, eg. Package_Class */
	protected $className			= "";
	/**	@var		string			$classFile			Class Name, eg. de/ceus-media/package/Class.php5 */
	protected $classFile			= "";
	/**	@var		string			$classPath			Class Path, eg. de.ceus-media.package.Class */
	protected $classPath			= "";
	/**	@var		string			$fileName			File Name of Class */
	protected $fileName				= "";
	/**	@var		array			$pathParts			Splitted Path Parts in lower Case */
	protected $pathParts			= array();
	/**	@var		array			$pathParts			Splitted Path Parts in lower Case */
	protected $pathTemplates		= array();
	/**	@var		string			$templateClass		File Name of Test Class Template */
	protected $templateClass		= "Creator_class.tmpl";
	/**	@var		string			$templateClass		File Name of Exception Test Method Template */
	protected $templateException	= "Creator_exception.tmpl";
	/**	@var		string			$templateClass		File Name of Test Method Template */
	protected $templateMethod		= "Creator_method.tmpl";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function  __construct() {
		$this->pathTemplates	= dirname( __FILE__ ).'/';
	}

	/**
	 *	Creates and returns array of Exception Test Methods from Method Name, Method Content and a Template.
	 *	@access		protected
	 *	@return		array
	 */
	protected function buildExceptionTestMethod( $methodName, $content )
	{
		$methods	= array();
		$exceptions	= $this->getExceptionsFromMethodContent( $content );
		$counter	= 0;
		foreach( $exceptions as $exception )
		{
			$counter	= count( $exceptions ) > 1 ? $counter + 1 : "";
			$template	= file_get_contents( $this->templateException );
			$template	= str_replace( "{methodName}", $methodName, $template );
			$template	= str_replace( "{MethodName}", ucFirst( $methodName ), $template );
			$template	= str_replace( "{className}", $this->className, $template );
			$template	= str_replace( "{exceptionClass}", $exception, $template );
			$template	= str_replace( "{counter}", $counter, $template );
			$methods[]	= $template;
		}
		return $methods;
	}

	/**
	 *	Creates Test Class from Class Data and a Template.
	 *	@access		protected
	 *	@return		void
	 */
	protected function buildTestClass()
	{
		$methods	= $this->buildTestMethods();

		$template	= file_get_contents( $this->templateClass );
		$template	= str_replace( "{methodTests}", implode( "", $methods ), $template );
		$template	= str_replace( "{className}", $this->className, $template );
		$template	= str_replace( "{classFile}", $this->classFile, $template );
		$template	= str_replace( "{classPath}", $this->classPath, $template );
		$template	= str_replace( "{classPackage}", $this->data['package'], $template );
		$template	= str_replace( "{date}", date( "d.m.Y" ), $template );

		Folder_Editor::createFolder( dirname( $this->targetFile ) );
		file_put_contents( $this->targetFile, $template );
	}

	/**
	 *	Creates and returns array of Test Methods from Class Data and a Template.
	 *	@access		protected
	 *	@return		array
	 */
	protected function buildTestMethods()
	{
		$lastMethod	= NULL;
		$methods	= array();
		foreach( $this->data['methods'] as $methodName => $methodData )
		{
			if( $methodData['access'] == "protected" )
				continue;
			if( $methodData['access'] == "private" )
				continue;
			if( $lastMethod )
			{
				$pattern	= "@.*function ".$lastMethod."(.*)function ".$methodName.".*@si";
				$content	= file_get_contents( $this->classFile );
				$content	= preg_replace( $pattern, "\\1", $content );
				$exceptions	= $this->buildExceptionTestMethod( $lastMethod, $content );
				foreach( $exceptions as $exception )
					$methods[]	= $exception;
			}
			if( $methodName == array_pop( array_slice( array_keys( $this->data['methods'] ), -1 ) ) )
			{
				$pattern	= "@.*function ".$methodName."(.*)$@si";
				$content	= file_get_contents( $this->classFile );
				$content	= preg_replace( $pattern, "\\1", $content );
				$exceptions	= $this->buildExceptionTestMethod( $methodName, $content );
				foreach( $exceptions as $exception )
					$methods[]	= $exception;
			}
			$template	= file_get_contents( $this->templateMethod );
			$template	= str_replace( "{methodName}", $methodName, $template );
			$template	= str_replace( "{MethodName}", ucFirst( $methodName ), $template );
			$template	= str_replace( "{className}", $this->className, $template );
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
	public function createForFile( $className, $force = FALSE )
	{
		$this->templateClass		= $this->pathTemplates.$this->templateClass;
		$this->templateException	= $this->pathTemplates.$this->templateException;
		$this->templateMethod		= $this->pathTemplates.$this->templateMethod;
			
		$this->className	= $className;
		$this->readPath();
		$this->classFile	= "src/".$this->getPath( "/" ).".php5";
		$this->classPath	= $this->getPath( "." );
		$this->targetFile	= "Test/".$this->getPath( "/" )."Test.php";
		
		if( file_exists( $this->targetFile ) && !$force )
			throw new RuntimeException( 'Test Class for Class "'.$this->className.'" is already existing.' );
		
		$parser	= new File_PHP_Parser_Array();
		$data	= $parser->parseFile( $this->classFile, "" );
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
	 *	@param		string		$force		Flag: overwrite Test Class if already existing
	 *	@return		void
	 */
	public function createForFolder( $path, $force )
	{
		$counter	= 0;
		$fullPath	= "de/ceus-media/".str_replace( "_", "/", $path )."/";		
		if( file_exists( $fullPath ) && is_dir( $fullPath ) )
		{
			$filter	= new Folder_RecursiveRegexFilter( $fullPath, "@\.php5$@i", TRUE, FALSE );
			foreach( $filter as $entry )
			{
				$counter++;
				$className	= $entry->getPathname();
				$className	= substr( $className, strlen( $fullPath ) );
				$className	= preg_replace( "@\.php5$@i", "", $className );
				$className	= str_replace( "/", "_", $className );
				$creator	= new File_PHP_Test_Creator();
				$creator->createForFile( $path."_".$className, $force );
			}
		}
		return $counter;
	}

	/**
	 *	Dumps all Class Data into an HTML File, for Testing and Development of this Creator Class.
	 *	@access		private
	 *	@return		void
	 */
	private function dumpClassData()
	{
		ob_start();
		print_m( $this->data );
		$data	= ob_get_clean();
		file_put_contents( "lastCreatedTest.cache", "<xmp>".$data."</xmp>" );
	}
	
	/**
	 *	Reads and returns thrown Exception Classes from Method Content.
	 *	@access		protected
	 *	@param		string		$content		Method Content
	 *	@return		array
	 */
	protected function getExceptionsFromMethodContent( $content )
	{
		$exceptions	= array();
		$content	= preg_replace( "@/\*(.*)\*/@si", "", $content );
		$lines		= explode( "\n", $content );
		foreach( $lines as $line )
		{
			$matches	= array();
			if( preg_match( "@throw new (\w+)Exception@", $line, $matches ) )
				if( isset( $matches[1] ) )
					$exceptions[]	= $matches[1]."Exception";
		}
		return $exceptions;
	}
	
	/**
	 *	Combines and returns Path Parts and File Nanme with a Delimiter.
	 *	@access		protected
	 *	@return		string
	 */
	protected function getPath( $delimiter )
	{
		$path	= implode( $delimiter, $this->pathParts );
		$path	= $path.$delimiter.$this->fileName;
		return $path;
	}

	/**
	 *	Splits Path to Class and stores Parts in lower Case.
	 *	@access		protected
	 *	@return		void
	 */
	protected function readPath()
	{
		$this->pathParts	= explode( "_", $this->className );
		$this->fileName		= array_pop( $this->pathParts );
		for( $i=0; $i<count( $this->pathParts ); $i++ )
			$this->pathParts[$i]	= $this->pathParts[$i];
	}

	/**
	 *	Sets individual Test Class Template.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Test Class Template
	 *	@return		void
	 */
	public function setClassTemplate( $fileName )
	{
		$this->templateClass	= $fileName;
	}

	/**
	 *	Sets individual Test Class Exception Template.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Test Class Exception Template
	 *	@return		void
	 */
	public function setExceptionTemplate( $fileName )
	{
		$this->templateException	= $fileName;
	}

	/**
	 *	Sets individual Test Class Method Template.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Test Class Method Template
	 *	@return		void
	 */
	public function setMethodTemplate( $fileName )
	{
		$this->templateMethod	= $fileName;
	}

	/**
	 *	Sets Path to individual Templates.
	 *	@access		public
	 *	@param		string		$pathTemplates		Path to Templates.
	 *	@return		void
	 */
	public function setTemplatePath( $pathTemplates )
	{
		$this->pathTemplates	= $pathTemplates;
	}
}
?>