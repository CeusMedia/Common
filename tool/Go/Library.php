<?php
/**
 *	@deorecated not needed once Go is gone
 */
class Go_Library
{
	public static $configFile	= 'Common.ini';

	public function getConfigData()
	{
		return parse_ini_file( self::getConfigFile(), TRUE );
	}

	public static function getConfigFile()
	{
		return dirname( dirname( __DIR__ ) ).'/'.self::$configFile;
	}

	public static function listClasses( $path )
	{
		$count	= 0;
		$size	= 0;
		$list	= array();
		self::listClassesRecursive( $path, $list, $count, $size );
		return array(
			'path'	=> $path,
			'count'	=> $count,
			'size'	=> $size,
			'files'	=> $list
		);
	}

	public static function getGoPath()
	{
		return dirname( __FILE__ ).'/';
	}

	public static function getLibraryPath()
	{
		return dirname( dirname( __FILE__ ) ).'/';
	}

	public static function getSourcePath()
	{
		return self::getLibraryPath().'/src/';
	}

	protected static function listClassesRecursive( $path, &$list, &$count , &$size )
	{
		$index	= new DirectoryIterator( $path );
		foreach( $index as $entry ){
			$pathName	= $entry->getPathname();
			if( $entry->isDot() )
				continue;
			if( $entry->getFilename() == ".git" )
				continue;
			if( $entry->isDir() ){
		#		echo "Path: ".$entry->getPath()."\n";
				self::listClassesRecursive( $pathName, $list, $count, $size );
			}
			else if( $entry->isFile() ){
				$info	= pathinfo( $pathName );
				if( $info['extension'] !== "php" )
					continue;
				if( !preg_match( '/^[A-Z]/', $info['basename'] ) )
					continue;
				$list[] = $pathName;
				$size	+= filesize( $pathName );
				$count++;
			}
		}
	}

	public static function showMemoryUsage(){
		$number	= ceil( memory_get_usage() / 1024 );
		print( "\nmemory: ".$number."KB" );
	}

	/**
	 *	@deprecated	since CMC_Loader
	 */
	public static function testImports( $files )
	{
		remark( "Checking nested imports\n" );
		$count	= 0;
		$path	= dirname( __FILE__ )."/";
		$line	= str_repeat( "-", 79 );
		$list	= array();
		foreach( $files as $file ){
			$relative	= str_replace( $path, "", $file );
			if( $count && !( $count % 60 ) )
				echo " ".$count."/".count( $files )."\n";
			try{
				@require_once( $relative );
				echo ".";
			}
			catch( Exception $e ){
				$list[$file]	= $e;
				echo "E";
			}
			$count++;
		}
		echo "  ".$count."/".count( $files )."\n";
		if( $list ){
			remark( "\n! Invalid files:" );
			foreach( $list as $file => $exception ){
				$relative	= str_replace( $path, "", $file );
				remark( "File: ".$relative );
				remark( $exception->getMessage() );
				remark( $line );
			}
		}
	}

	public static function testSyntax( $files )
	{
		remark( "Checking class file syntax\n" );
		$count	= 0;
		$path	= dirname( __FILE__ )."/";
		$line	= str_repeat( "-", 79 );
		$list	= array();
		$progress	= new CLI_Output_Progress();
		$progress->setTotal( count( $files ) );
		$progress->start();
		foreach( $files as $file ){
			$code	= 0;
			$output	= array();
			exec( 'php -l "'.$file.'" 2>&1', $output, $code );
			if( !preg_match( '/^No syntax errors detected/', join( PHP_EOL, $output ) ) )
				$list[$file]	= join( PHP_EOL, $output );
			$count++;
			$progress->update( $count );
		}
		$progress->finish();
		if( $list ){
			remark( "\n! Invalid files:" );
			foreach( $list as $file => $message )
			{
				$relative	= str_replace( $path, "", $file );
				remark( "File:  ".$relative );
				remark( "Error: ".$message.PHP_EOL );
			}
		}
	}
}
