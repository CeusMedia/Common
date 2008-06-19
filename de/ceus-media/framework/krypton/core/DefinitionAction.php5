<?php
import( 'de.ceus-media.framework.krypton.core.Action' );
/**
 *	Generic Definition Action Handler.
 *	@package		framework.krypton.core
 *	@extends		Framework_Krypton_Core_Action
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.6
 */
/**
 *	Generic Definition Action Handler.
 *	@package		framework.krypton.core
 *	@extends		Framework_Krypton_Core_Action
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.6
 */
class Framework_Krypton_Core_DefinitionAction extends Framework_Krypton_Core_Action
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $useWikiParser = false )
	{
		parent::__construct( $useWikiParser );
		$this->loadLanguage( 'validator', false, false );
	}
}
?>
