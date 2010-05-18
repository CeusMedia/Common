<?php
/**
 *	Description of Panel
 *	@category		cmClasses
 *	@package		UI.HTML.Layout
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@version		$Id$
 */
/**
 *	Description of Panel
 *	@category		cmClasses
 *	@package		UI.HTML.Layout
 *	@extends		UI_HTML_Element_Abstract
 *	@author			martin
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@version		$Id$
 */
class UI_HTML_Layout_Panel extends UI_HTML_Element_Abstract
{
    protected $oLayout		= NULL;

    public function __construct( $oLayout )
    {
        $this->oLayout = $oLayout;

    }
    
    public function getLayout()
    {
    	return $this->oLayout;
    }

    public function add( $mElement )
    {
        $this->oLayout->add( $mElement );
    }

    public function render()
    {
		$aAttributes	= array(
			'id'	=> $this->sId,
			'class'	=> $this->sClass,
		);
		return $this->renderTag( 'div', $this->oLayout->render(), $aAttributes );
    }
}
?>
