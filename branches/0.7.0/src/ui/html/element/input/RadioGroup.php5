<?php
/**
 *	HTML Radio Group wrapped in Fieldset.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Radio Group wrapped in Fieldset.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Input_RadioGroup extends UI_HTML_Element_Abstract
{
	protected $name		= NULL;
	protected $value	= NULL;
	protected $radios	= array();
	protected $fieldset	= NULL;

	public function __construct( $name, $label = NULL )
	{
		$this->fieldset	= new UI_HTML_Element_List_Fieldset;
		$this->setName( $name );
		if( !is_null( $label ) )
			$this->setLabel( $label );
	}
	
	public function setCurrentValue( $value )
	{
		$this->value	= $value;
	}
	
	public function setLabel( $label )
	{
		$this->fieldset->setLegendLabel( $label );	
	}
	
	public function setName( $name )
	{
		$this->name	= $name;	
	}
	
	public function addRadio( UI_HTML_Element_Input $radio, $label = NULL )
	{
		if( $radio->getValue() && $radio->getValue() == $this->value )
			$radio->setSelected();
		if( !is_null( $label ) )
			$radio			= new UI_HTML_Element_Input_Label( $radio->render().$label );
		$this->radios[]	= $radio;
	}
	
	public function add( $value, $label )
	{
		$radio		= new UI_HTML_Element_Input_Radio( $this->name, $value );
		if( $this->value == $value )
			$radio->setChecked();
		$label		= new UI_HTML_Element_Input_Label( $radio->render().$label );
		$this->radios[]	= $label;
	}
	
	public function render()
	{
		$list	= array();
		foreach( $this->radios as $label )
			$list[]	= $label->render();
		$this->fieldset->setContent( join( $list ) );
		return $this->fieldset->render();
	}
}
?>