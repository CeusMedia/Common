<?php
import( 'de.ceus-media.framework.krypton.core.View' );
class Framework_Krypton_View_Component_Navigation extends Framework_Krypton_Core_View
{
	/**
	 *	Builds Navigation View.
	 *	@access		private
	 *	@return		string
	 */
	public function buildNavigation()
	{
		$config		= $this->registry->get( 'config' );
		$request	= $this->registry->get( 'request' );
		$auth		= $this->registry->get( 'auth' );
		$controller	= $this->registry->get( 'controller' );

		$keys	= array( "public" );
		if( $auth->isAuthenticated() )
		{
			$keys[]	= "inside";
			$roles	= Logic_Role::getUserRoles( FALSE, $auth->getCurrentUser() );
			foreach( $roles as $role )
				$keys[]	= strtolower( str_replace( " ", "_", $role ) );
		}
		else
			$keys[]	= "outside";

		$keys	= array_map( create_function( '$key', 'return "roles/".$key;' ), $keys );
		$keys	= implode( " or ", $keys );

		//  --  LOAD XSL TRANSFORMATION DOCUMENT  --  //
		$xml		= $controller->getDocument();
		$template	= $config['paths.templates']."interface/navigation.xslt";
		$xsl		= file_get_contents( $template );
		$xsl		= str_replace( "{#keys#}", $keys, $xsl );
		$xsl		= DOMDocument::loadXML( $xsl );

		//  --  XSL TRANSFORMATION  --  //
		$processor	= new XSLTProcessor();
		$processor->importStyleSheet( $xsl );
		$result 	= $processor->transformToXML( $xml );

		foreach( $this->words['main']['links_main'] as $linkKey => $linkLabel )
			$result	= str_replace( ">".$linkKey."<", ">__".$linkLabel."__<", $result );
		$result	= str_replace( "__", "", $result );

		$ui	= array(
			'link'		=> $request->get( 'link' ),
			'list'		=> $result,
		);
		$userId	= $auth->getCurrentUser();
		if( $userId )
			$ui['username']	= Logic_User::getUsername( $userId );
		return $this->loadTemplate( "interface.navigation", $ui );
	}
}
?>