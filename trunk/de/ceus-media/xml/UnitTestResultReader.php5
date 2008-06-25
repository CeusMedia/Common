<?php
import( 'de.ceus-media.xml.ElementReader' );
/**
 *	Reader for XML Result File written by PHPUnit.
 *	@package		xml
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.04.2008
 *	@version		0.6
 */
/**
 *	Reader for XML Result File written by PHPUnit.
 *	@package		xml
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.04.2008
 *	@version		0.6
 *	@todo			Code Documentation
 *	@todo			Unit Test
 */
class XML_UnitTestResultReader
{
	/**	@var		int			$date			Date of XML File */
	protected $date;
	/**	@var		XML_Element	$tree			XML Element Tree from XML File */	
	protected $tree;
	/**
	 *	Constructor, reads XML.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->tree	= XML_ElementReader::readFile( $fileName );
		$this->date	= filemtime( $fileName );
	}
	
	/**
	 *	Returns Date of XML File.
	 *	@access		public
	 *	@return		int
	 */
	public function getDate()
	{
		return $this->date;
	}
	
	/**
	 *	Returns Number of Errors.
	 *	@access		public
	 *	@return		int
	 */
	public function getErrorCount()
	{
		return $this->tree->testsuite[0]->getAttribute( 'errors' );
	}
	
	/**
	 *	Returns List of Error Messages.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors()
	{
		$list	= array();
		foreach( $this->tree->children() as $testSuite )
			$this->getMessagesRecursive( $testSuite, $list, "error" );
		return $list;
	}
	
	/**
	 *	Collects Error or Failure Messages by iterating Tree recursive and returns Lists.
	 *	@access		private
	 *	@param		XML_Element	$element		Current XML Element
	 *	@param		array		$list			Reference to Result List
	 *	@param		string		$type			Message Type (error|failure)
	 *	@param		string		$testSuite		Current Test Suite
	 *	@return		array
	 */
	private function getMessagesRecursive( SimpleXMLElement $element, &$list, $type, $testSuite = "" )
	{
		if( $element->getName() == "testcase" && $element->$type )
		{
			return $list[]	= array(
				'suite'		=> $testSuite,
				'case'		=> $element->getAttribute( 'name' ),
				'error'		=> $element->$type,
				'type'		=> $element->$type->getAttribute( 'type' ),
			);
		}
		foreach( $element->children() as $child )
			$this->getMessagesRecursive( $child, $list, $type, $element->getAttribute( 'name' ) );
	}

	/**
	 *	Returns Number of Failures.
	 *	@access		public
	 *	@return		int
	 */
	public function getFailureCount()
	{
		return $this->tree->testsuite[0]->getAttribute( 'failures' );
	}
	
	/**
	 *	Returns List of Failure Messages.
	 *	@access		public
	 *	@return		array
	 */
	public function getFailures()
	{
		$list	= array();
		foreach( $this->tree->children() as $testSuite )
			$this->getMessagesRecursive( $testSuite, $list, "failure" );
		return $list;
	}
		
	/**
	 *	Returns Number of Tests.
	 *	@access		public
	 *	@return		int
	 */
	public function getTestCount()
	{
		return $this->tree->testsuite[0]->getAttribute( 'tests' );
	}
	
	public function getTestSuiteCount( $element = NULL )
	{
		$count		= 1;
		$element	= $element === NULL ? $this->tree : $element;
		foreach( $element->testsuite as $testSuite )
			$count	+= $this->getTestSuiteCount( $testSuite );
		return $count;
	}
	
	public function getTestCaseCount( $element = NULL )
	{
		$count		= 0;
		$element	= $element === NULL ? $this->tree : $element;
		foreach( $element->testsuite as $testSuite )
			$count	+= $this->getTestCaseCount( $testSuite );
		$count	+= count( $element->testcase );
		return $count;
	}
	
	public function getTime()
	{
		return $this->tree->testsuite[0]->getAttribute( 'time' );
	}
}
?>