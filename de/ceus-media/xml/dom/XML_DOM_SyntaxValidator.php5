<?php
/**
 *	Validator for XML Syntax.
 *	@package		xml
 *	@subpackage		dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
/**
 *	Validator for XML Syntax.
 *	@package		xml
 *	@subpackage		dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
class XML_DOM_SyntaxValidator
{
	/**	@var	DOMDocument		$_doc		DOM Document of Syntax is valid */
	var $_doc	= false;
	/**	@var	array			$_errors	Parsing Errors if Syntax is invalid */
	var $_errors;
		
	/**
	 *	Validates XML Document.
	 *	@access		public
	 *	@param		string		xml			XML to be validated
	 *	@return		bool
	 */
	function validate( $xml )
	{
		$this->_doc	= new DOMDocument();
		ob_start();
		$this->_doc->loadXML( $xml );
		$this->_errors	= ob_get_contents();
		ob_end_clean();
		if( !$this->_errors )
			return true;
		return false;
	}

	/**
	 *	Returns DOM Document Object of XML Document if Syntax is valid.
	 *	@access		public
	 *	@return		DOMDocument
	 */
	function & getDocument()
	{
		return $this->_doc;
	}
	
	/**
	 *	Returns Array of parsing Errors.
	 *	@access		public
	 *	@return		string
	 */
	function getErrors()
	{
		return $this->_errors;
	}
}
?>