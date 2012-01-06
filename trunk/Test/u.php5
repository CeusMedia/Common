<?php
require_once 'cmClasses/trunk/autoload.php5';
function u( $path ){
	$index	= new DirectoryIterator( $path );
	foreach( $index as $item ){
		if( $item->isDot() )
			continue;
		else if( $item->getFilename() == 'AllTests.php' ){
//			print( "\n".$item->getPathname() );
//			rename( $item->getPathname(), $item->getPathname().".bak" );
			unlink( $item->getPathname() );
		}
		else if( $item->isDir() && $item->getFilename() != '.svn' )
			u( $item->getPathname() );
	}
}
u( 'Test' );
?>
