<?php
require_once( "../../../../../useClasses.php5" );
import( 'de.ceus-media.ui.DevOutput' );
import( 'de.ceus-media.xml.opml.FileReader' );


$parser	= new XML_OPML_FileReader();
$parser->read( "example.opml" );


print_m( $parser->getOutlines() );


?>
