<?php
/**
 *	...
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
 *	...
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
class UI_HTML_Element_Input_Form extends UI_HTML_Element_Abstract
{
	protected $action		= './';
	protected $method		= 'post';
	protected $encType		= NULL;

	public function __construct( $id = NULL )
	{
		if( !is_null( $id ) )
			$this->setId( $id );
	}

	public function & getNewButtonBar( $class = NULL )
	{
		$buttonBar			= new UI_HTML_Element_Button_Container();
		if( $class )
			$buttonBar->setClass( $class );
		$this->content[]	=& $buttonBar;
		return $buttonBar;
	}

	public function & getNewFieldset( $useList = TRUE )
	{
		$fieldset			= $useList ? new UI_HTML_Element_List_Fieldset : new UI_HTML_Element_Fieldset;
		$this->content[]	=& $fieldset;
		return $fieldset;
	}

	public function render()
	{
		$attributes	= $this->renderAttributes();
		$list		= array();
		foreach( $this->content as $content )
		{
			if( $content instanceof UI_HTML_Element_Abstract )
				$content	= $content->render();
			$list[]	= $content;
		}
		$content	= join( $list );
		return '<form'.$attributes.'>'.$content.'</form>';
	}

	public function setAction( $action )
	{
		$this->action = $action;
		return $this;
	}

	public function setEncodingType( $type )
	{
		$this->encType = $type;
		return $this;
	}

	public function setMethod( $method )
	{
		$method	= strtolower( $method );
		if( !in_array( $method, array( 'get', 'post', 'put', 'delete' ) ) )
			throw new InvalidArgumentException( 'Invalid method "'.$method.'"' );
		$this->method = $method;
		return $this;
	}
}
?>