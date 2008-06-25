<?php
/**
 *	Environment Test
 *	@author		Christian Wuerker <Christian.Wuerker@CeuS-Media.de>
 */
require_once( 'useClasses.php5' );				// sets Environment ( Class Path + ClassImport )
import( 'de.ceus-media.ui.DevOutput' );			// loads Output Helper for Developement
if( !headers_sent() )
	echo "cmClasses successfully installed and environmental inclusion tested.<br/><br/>Have fun !";
?>