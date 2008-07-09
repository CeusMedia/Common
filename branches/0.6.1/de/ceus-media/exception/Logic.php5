<?php
/**
 *	Logic Exception with Message Key for Language Support.
 *	@package		exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.02.2007
 *	@version		0.1
 */
/**
 *	Logic Error.
 *	@package		exception
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.02.2007
 *	@version		0.1
 */
class Exception_Logic extends Exception
{
	/**	@var		string		$messageKey		Message Key */
	public $messageKey;
	/**	@var		string		$subjectValue	Subject Value to be filled in */
	public $subjectValue;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$messageKey		Message Key
	 *	@param		string		$subjectValue	Subject Value to be filled in
	 *	@return		void
	 */
	public function __construct( $messageKey, $subjectValue = "" )
	{
		parent::__construct( $messageKey );
		$this->messageKey	= $messageKey;
		$this->subjectValue	= $subjectValue;
	}

	/**
	 *	Builds Message from given Exception Messages and sets in Subject Value.
	 *	@access		public
	 *	@param		array		$errorMessages	Array of Exception Messages
	 *	@return		string
	 */
	public function getMessage( $errorMessages )
	{
		if( !array_key_exists( $this->messageKey, $errorMessages ) )
			return $this->messageKey;
		$message	= $errorMessages[$this->messageKey];
		$message	= sprintf( $message, $this->subjectValue );
		return $message;
	}
		
	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getMessageKey()
	{
		return $this->messageKey;	
	}

	/**
	 *	Returns Name of Source which was not fully accessible.
	 *	@access		public
	 *	@return		string
	 */
	public function getSubject()
	{
		return $this->subjectValue;	
	}
}
?>