<?php
class Go_Library
{
	public function listClasses( $path )
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

	public function ensureSvnSupport()
	{
		exec( "svn", $return );
		if( !$return )
			throw new RuntimeException( "SVN seems to be not installed." );
	}

	protected static function listClassesRecursive( $path, &$list, &$count , &$size )
	{
		$index	= new DirectoryIterator( $path );
		foreach( $index as $entry )
		{
			$pathName	= $entry->getPathname();
			if( $entry->isDot() )
				continue;
			if( $entry->getFilename() == ".svn" )
				continue;
			if( $entry->isDir() )
			{
	#			echo "Path: ".$entry->getPath()."\n";
				self::listClassesRecursive( $pathName, $list, $count, $size );
			}
			else if( $entry->isFile() )
			{
				$info	= pathinfo( $pathName );
				if( $info['extension'] !== "php5" )
					continue;
				if( !preg_match( '/^[A-Z]/', $info['basename'] ) )
					continue;
				if( preg_match( '/hydrogen/', $pathName )  )
					continue;
				$list[] = $pathName;
				$size	+= filesize( $pathName );
				$count++;
			}
		}
	}	
	
	public function runSvn( $command )
	{
		passthru( "svn ".$command, $return );
	}
 	
	public function showMemoryUsage()
	{
		$number	= ceil( memory_get_usage() / 1024 );
		print( "\nmemory: ".$number."KB" );
	}
	
	public function testImports( $files )
	{
		remark( "Checking nested imports\n" );
		$count	= 0;
		$path	= dirname( __FILE__ )."/";
		$line	= str_repeat( "-", 79 );
		$list	= array();
		foreach( $files as $file )
		{
			$relative	= str_replace( $path, "", $file );
			if( $count && !( $count % 60 ) )
				echo " ".$count."/".count( $files )."\n";
			try
			{
				@require_once( $relative );
				echo ".";
			}
			catch( Exception $e )
			{
				$list[$file]	= $e;
				echo "E";
			}
			$count++;
		}
		echo "  ".$count."/".count( $files )."\n";
		if( $list )
		{
			remark( "\n! Invalid files:" );
			foreach( $list as $file => $exception )
			{
				$relative	= str_replace( $path, "", $file );
				remark( "File: ".$relative );
				remark( $exception->getMessage() );
				remark( $line );
			}
		}
	}

	public function testSyntax( $files )
	{
		remark( "Checking file syntax\n" );
		$count	= 0;
		$path	= dirname( __FILE__ )."/";
		$line	= str_repeat( "-", 79 );
		$list	= array();
		foreach( $files as $file )
		{
			if( $count && !( $count % 60 ) )
				echo " ".$count."/".count( $files )."\n";
			$output = shell_exec('php -l "'.$file.'"');
			if( !preg_match( '/^No syntax errors detected/', $output ) )
			{
				$list[$file]	= $output;
				echo "E";
			}
			else
				echo ".";
			
			$count++;
		}
		echo "  ".$count."/".count( $files )."\n";
		if( $list )
		{
			remark( "\n! Invalid files:" );
			foreach( $list as $file => $message )
			{
				$relative	= str_replace( $path, "", $file );
				remark( "File: ".$relative.$message.$line );
			}
		}
	}
}
?>