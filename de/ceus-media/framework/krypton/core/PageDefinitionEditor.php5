<?php
/**
 *	Editor for XML Page Definitions.
 *	@package		framework.krypton.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.03.2007
 *	@version		0.6
 */
class Framework_Krypton_Core_PageDefinitionEditor
{
	/**	@var		string		$fileName		File Name of Page Definition XML File */
	protected $fileName;
	/**	@var		DOMDocument	$document		DOM Document of Page Definition XML File */
	protected $document;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Page Definition XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Removes 'disabled' Attribute of a Page Node in Page Definition File.
	 *	@access		public
	 *	@param		string		$pageId			Page ID
	 */
	public function enablePage( $pageId )
	{
		return $this->setPageAttribute( 'disabled', 0 );
	}

	/**
	 *	Sets 'disabled' Attribute of a Page Node in Page Definition File.
	 *	@access		public
	 *	@param		string		$pageId			Page ID
	 */
	public function disablePage( $pageId )
	{
		return $this->setPageAttribute( 'disabled', 1 );
	}

	/**
	 *	Loads XML Page Definitions.
	 *	@access		private
	 *	@return		void
	 */
	private function loadDocument()
	{
		if( !$this->document )
		{
			$this->document	= new DOMDocument();
			$this->document->preserveWhiteSpace	= true;
			$this->document->validateOnParse	= true;
			$this->document->formatOutput		= true;
			$this->document->load( $this->fileName );
		}
	}

	/**
	 *	Adds a Role to a Page.
	 *	@access		public
	 *	@param		string		$role			Role Name
	 *	@param		string		$pageId			Page ID
	 *	@return		bool
	 */
	public function addRoleToPage( $role, $pageId )
	{
		$this->loadDocument();
		$pageNode	= $this->document->getElementById( $pageId );
		if( !$pageNode )
			throw new Framework_Krypton_Exception_IO( 'page_not_existing' );
		$accessNode	= $pageNode->getElementsByTagName( 'access' )->item( 0 );
		$newNode	= $this->document->createElement( $role );
		$accessNode->appendChild( $newNode );
		return $this->saveDocument();
	}

	/**
	 *	Removes a Role from a Page.
	 *	@access		public
	 *	@param		string		$role			Role Name
	 *	@param		string		$pageId			Page ID
	 *	@return		bool
	 */
	public function removeRoleFromPage( $role, $pageId )
	{
		$this->loadDocument();
		$pageNode	= $this->document->getElementById( $pageId );
		if( !$pageNode )
			throw new Framework_Krypton_Exception_IO( 'page_not_existing' );
		$accessNode	= $pageNode->getElementsByTagName( 'access' )->item( 0 );
		$oldNode	= $accessNode->getElementsByTagName( $role )->item( 0 );
		if( !$oldNode )
			throw new Framework_Krypton_Exception_IO( 'page_role_not_existing' );
		$accessNode->removeChild( $oldNode );
		return $this->saveDocument();
	}

	/**
	 *	Saves XML Page Definitions.
	 *	@access		private
	 *	@return		bool
	 */
	private function saveDocument()
	{
		$result	= $this->document->save( $this->fileName );
		return $result !== FALSE;
	}

	/**
	 *	Sets Attribute of Page Node in Page Definition File.
	 *	@access		public
	 *	@param		string		$pageId			Page ID
	 *	@param		string		$key			Attribute Key
	 *	@param		string		$value			Attribute Value
	 */
	public function setPageAttribute( $pageId, $key, $value )
	{
		$this->loadDocument();
		$pageNode	= $this->document->getElementById( $pageId );
		if( !$pageNode )
			throw new Framework_Krypton_Exception_IO( 'page_not_existing' );
		$pageNode->setAttribute( $key, $value );
		return $this->saveDocument();

	}

	/**
	 *	Removes 'hidden' Attribute of a Page Node in Page Definition File.
	 *	@access		public
	 *	@param		string		$pageId			Page ID
	 */
	public function showPage( $pageId )
	{
		return $this->setPageAttribute( 'hidden', 0 );
	}
}
?>