<?php
import( 'de.ceus-media.framework.krypton.core.DefinitionView' );
/**
 *	Base Class for Panels.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_DefinitionView
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.03.2007
 *	@version		0.1
 */
/**
 *	Base Class for Panels.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_DefinitionView
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.03.2007
 *	@version		0.1
 */
abstract class Framework_Krypton_View_Component_Panel extends Framework_Krypton_Core_DefinitionView
{
	abstract public function getContent();
}
?>
