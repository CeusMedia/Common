<?php
/**
 *	Simple Example
 *	@author		Christian Wuerker <Christian.Wuerker@CeuS-Media.de>
 */
require_once( 'useClasses.php5' );					// sets Environment ( Class Path + ClassImport )
import( 'de.ceus-media.ui.DevOutput' );				// loads Output Helper for Developement
import( 'de.ceus-media.StopWatch' );				// loads Stopwatch
import( 'de.ceus-media.file.ini.Reader' );		// loads Reader for .ini Files
 
$st			= new Stopwatch();
$file		= 'config.ini';							// URI of Configuration File
$sections	= true;									// use Section in Configuration File
$active		= true;									// read only active Properties
$ir			= new File_INI_Reader( $file, $sections );	// read Configuration File
$array		= $ir->toArray( $active );				// get Properties as array

echo "<h3>Demo Application</h3>";
echo "These are configuration properties:</br>";
print_m( $array );
echo "<hr/>".$st->stop()." msec";

?>