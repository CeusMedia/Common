<?php
/**
 *	View of static Contents.
 *	@package		framework.neon.views
 *	@extends		Framework_Neon_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.10.2006
 *	@version		0.2
 */
import( 'de.ceus-media.framework.neon.View' );
/**
 *	View of static Contents.
 *	@package		framework.neon.views
 *	@extends		Framework_Neon_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.10.2006
 *	@version		0.2
 */
class Framework_Neon_Views_ContentViews extends Framework_Neon_View
{
	public function buildContent()
	{
		$config		= $this->ref->get( 'config' );
		$session	= $this->ref->get( 'session' );
		$request	= $this->ref->get( 'request' );
		
		$link		= $request->get( 'link' );
		$lan		= $session->get( 'language' );
		$file		= $config['paths']['html'].$lan."/".$link.".html";
		if( file_exists( $file ) )
			return implode( "", file( $file ) );
		else
//			header( "Location: ./" );
			$this->messenger->noteFailure( str_replace( "#URI#", $file, $this->words['main']['msg']['error_no_content'] ) );
	}
}
?>