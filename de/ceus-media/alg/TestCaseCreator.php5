<?php
import( 'de.ceus-media.folder.Editor' );
import( 'de.ceus-media.folder.RecursiveRegexFilter' );
/**
 *	Created Test Class for PHP Unit Tests using Class Parser and two Templates.
 *	@package		alg
 *	@uses			UI_ClassParser
 *	@uses			Folder_Editor
 *	@uses			Folder_RecursiveRegexFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Created Test Class for PHP Unit Tests using Class Parser and two Templates.
 *	@package		alg
 *	@uses			UI_ClassParser
 *	@uses			Folder_Editor
 *	@uses			Folder_RecursiveRegexFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Alg_TestCaseCreator
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
	/**	@var		string			$templateClass		File Name of Test Class Template */
	protected $templateClass		= "TestCaseCreator_class.tmpl";
	/**	@var		string			$templateClass		File Name of Exception Test Method Template */
	protected $templateException	= "TestCaseCreator_exception.tmpl";
	/**	@var		string			$templateClass		File Name of Test Method Template */
	protected $templateMethod		= "TestCaseCreator_method.tmpl";

	/**
	 *	Constructor, reads and stores all Class Information and creates Test Class.
	 *	@access		public
	 *	@param		string		$className		Name of Class to create Test Class for.
	 *	@param		bool		$force			Flag: overwrite Test Class File if already existing
	 *	@return		void
	 */
	public function createForFile( $className, $force = FALSE )
	{
		$this->templateClass		= dirname( __FILE__ )."/".$this->templateClass;
		$this->templateException	= dirname( __FILE__ )."/".$this->templateException;
		$this->templateMethod		= dirname( __FILE__ )."/".$this->templateMethod;
			
		$this->className	= $className;
		$this->readPath();
		$this->classFile	= "de/ceus-media/".$this->getPath( "/" ).".php5";
		$this->classPath	= "de.ceus-media.".$this->getPath( "." );
		$this->targetFile	= "Tests/".$this->getPath( "/" )."Test.php";
		
		if( file_exists( $this->targetFile ) && !$force )
			die( 'Test Class for Class "'.$this->className.'" is already existing.' );
		
		$parser				= new ClassParser( $this->classFile );
		$this->data			= $parser->getClassData();
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
				$creator	= new TestCaseCreator();
				$creator->createForFile( $path."_".$className, $force );
			}
		}
		return $counter;
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
		$template	= str_replace( "{classPackage}", implode( ".", $this->data['class']['package'] ), $template );
		$template	= str_replace( "{date}", date( "d.m.Y" ), $template );

		Folder_Editor::createFolder( dirname( $this->targetFile ) );
		file_put_contents( $this->targetFile, $template );
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
	 *	Dumps all Class Data into an HTML File, for Testing and Development of this Creator Class.
	 *	@access		private
	 *	@return		void
	 */
	private function dumpClassData()
	{
		ob_start();
		print_m( $this->data );
		$data	= ob_get_clean();
		file_put_contents( "data.html", "<xmp>".$data."</xmp>" );
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
			$this->pathParts[$i]	= strtolower( $this->pathParts[$i] );
	}
}
?>