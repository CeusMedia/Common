<?php
/**
 *
 *	@category		cmClasses
 *	@package		UI.HTML.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *
 *	@category		cmClasses
 *	@package		UI.HTML.element
 *	@extends		UI_HTML_Element_Abstract
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Form extends UI_HTML_Element_Abstract
{
	protected $methods	= array( 'head', 'get', 'post', 'put', 'delete' );
	protected $method	= 'post';
	protected $enctype	= NULL;#'text/plain';
	protected $action	= NULL;
	protected $name		= NULL;

	public function __construct( $action = NULL, $method = NULL, $enctype = NULL )
	{
		if( !is_null( $action ) )
			$this->setAction( $action );
		if( !is_null( $action ) )
			$this->setMethod( $method );
		if( !is_null( $action ) )
			$this->setEnctype( $enctype );
	}

	public function render()
	{
		$attributes	= array(
			'id'		=> $this->id,
			'name'		=> $this->name,
			'class'		=> $this->class,
			'action'	=> $this->action,
			'method'	=> $this->method,
			'enctype'	=> $this->enctype,
		);
		return $this->renderTag( 'form', $this->renderContent(), $attributes );
	}

	public function setAction( $href )
	{
		$this->action	= $href;
		return $this;
	}

	public function setEnctype( $enctype )
	{
		$this->enctype	= $enctype;
		return $this;
	}

	public function setMethod( $method )
	{
		$method	= strtolower( $method );
		if( !in_array( $method, $this->methods ) )
			throw new InvalidArgumentException( 'Invalid method "'.$method.'"' );
		$this->action	= $href;
		return $this;
	}

	public function setName( $name )
	{
		$this->name	= $name;
		return $this;
	}
}
?>