<?php
/**
 *	Importing Class like in Java with Method 'import'
 *	@uses			TreeFolder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2005
 *	@version		0.4
 */
/**
 *	Importing Class like in Java with Method 'import'
 *	@uses			TreeFolder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.06.2005
 *	@version		0.4
 */
class ClassImport
{
	/**
	 *	Java-like import of Classes.
	 *	@access		public
	 *	@param		string		$classPath		Java-formated URI of Class
	 *	@return		void
	 */	 
	public static function import( $classPath )
	{
		if( substr( $classPath, -1 ) == "*" )
		{
			$classpath = substr( $classPath, 0, -1 );
			self::importPackage( $classPath );
		}
		else
		{
			self::importClass( $classPath );
		}
	}
	
	/**
	 *	Java-like import of Classes in Folders.
	 *	@access		protected
	 *	@param		string		$classPath		Java-formated URI of Folder with Classes
	 *	@return		void
	 */	 
	protected static function importPackage( $classPath, $report = false )
	{
		$classPath = str_replace( IMPORT_SEPARATOR, FOLDER_SEPARATOR, $classPath );
		import( "de.ceus-media.file.folder.TreeFolder" );
		$paths	= explode( PATH_SEPARATOR, ini_get('include_path') );
		foreach( $paths as $path )
		{
			if(is_dir( $path.FOLDER_SEPARATOR.$classPath ) )
			{
				$tf = new TreeFolder( $path.FOLDER_SEPARATOR.$classPath, array ("php5") );
				$files = $tf->getTotalFiles();
				foreach( $files as $file )
				{
					$file = substr( $classPath.$file, 0, -4 );
					$file = str_replace( FOLDER_SEPARATOR, IMPORT_SEPARATOR, $file );
					self::importClass( $file );
				}
			}
		}
	}
	
	/**
	 *	Java-like import of Classes in Folders.
	 *	@access		protected
	 *	@param		string		$className		Java-formated URI of Class
	 *	@return		void
	 */	 
	protected static function importClass( $className )
	{
		$fileName	= ___getFileFromClass( $className);
		if( !in_array( $fileName, $GLOBALS['imported'] ) )
		{
			if( !@include_once( $fileName ) )
				throw new Exception( 'Class "'.$fileName.'" could not be loaded.' );
			$GLOBALS['imported'][] = $fileName;
		}
	}
}
?>