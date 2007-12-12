<?php
/**
 *	Validator for XML Syntax.
 *	@package		xml.dom
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
/**
 *	Validator for XML Syntax.
 *	@package		xml.dom
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.02.2006
 *	@version		0.1
 */
class XML_DOM_SyntaxValidator
{
	/**	@var	DOMDocument		$document	DOM Document of Syntax is valid */
	protected $document	= NULL;
	/**	@var	array			$errors		Parsing Errors if Syntax is invalid */
	protected $errors	= array();
		
	/**
	 *	Validates XML Document.
	 *	@access		public
	 *	@param		string		$xml		XML to be validated
	 *	@return		bool
	 */
	function validate( $xml )
	{
		$this->document	= new DOMDocument();
		ob_start();
		$this->document->validateOnParse	= true;
		$this->document->loadXML( $xml );
		$this->errors	= ob_get_contents();
		ob_end_clean();
		if( !$this->errors )
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
		return $this->document;
	}
	
	/**
	 *	Returns Array of parsing Errors.
	 *	@access		public
	 *	@return		string
	 */
	function getErrors()
	{
		return $this->errors;
	}
}
?>