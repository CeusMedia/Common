<?php
import( 'de.ceus-media.framework.krypton.core.Template' );
/**
 *	Template Component.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_Template
 *	@uses			Framework_Krypton_Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.03.2007
 *	@version		0.2
 */
/**
 *	Template Component.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_Template
 *	@uses			Framework_Krypton_Core_Registry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.03.2007
 *	@version		0.2
 */
class Framework_Krypton_View_Component_Template extends Framework_Krypton_Core_Template
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filename		File Name of Template
	 *	@param		array		$elements		Array of Elements to set in Template
	 *	@return		void
	 */
	public function __construct( $filename, $elements = null )
	{
		$config		= Framework_Krypton_Core_Registry::getStatic( 'config' );

		//  --  BASICS  --  //
		$basepath	= $config['paths']['templates'];
		$basename	= str_replace( ".", "/", $filename ).".html";

		//  --  FILE URI CHECK  --  //
		$uri	= $filename = $basepath.$basename;
		if( !file_exists( $uri ) )							//  check file
			throw new Framework_Krypton_Exception_IO( "Template '".$filename."' is existing in '".$uri."'." );	
		parent::__construct( $uri, $elements );
	}
}  
?>
