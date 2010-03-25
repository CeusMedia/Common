<?php
/**
 *	HTML Checkbox Group wrapped in Fieldset.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Checkbox Group wrapped in Fieldset.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Input_CheckboxGroup extends UI_HTML_Element_Abstract
{
	protected $name			= NULL;
	protected $value		= NULL;
	protected $checkboxes	= array();

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
		if( !preg_match( '/\[\]$/', $name ) )
			$name	.= '[]';
		$this->name	= $name;	
	}
	
	public function addCheckbox( UI_HTML_Element_Input_Checkbox $checkbox, $label = NULL )
	{
		if( $checkbox->getValue() && $this->compareValue( $checkbox->getValue() ) )
			$checkbox->setSelected();
		if( !is_null( $label ) )
			$checkbox			= new UI_HTML_Element_Input_Label( $checkbox->render().$label );
		$this->checkboxes[]	= $checkbox;
	}

	protected function compareValue( $value )
	{
		if( is_array( $this->value ) )
			return in_array( $value, $this->value );
		else
			return $this->value === $value;
	}
	
	public function add( $value, $label )
	{
		$checkbox	= new UI_HTML_Element_Input_Checkbox( $this->name, $value );
		if( $this->compareValue( $value ) )
			$checkbox->setChecked();
		$label		= new UI_HTML_Element_Input_Label( $checkbox->render().$label );
		$this->checkboxes[]	= $label;
	}
	
	public function render()
	{
		$list	= array();
		foreach( $this->checkboxes as $checkbox )
			$list[]	= $checkbox->render();
		$this->fieldset->setContent( join( $list ) );
		return $this->fieldset->render();
	}
}
?>