<?php
import ("de.ceus-media.file.File");
/**
 *	Creator of Test Suites.
 *	@package		test
 *	@subpackage		suite
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Creator of Test Suites.
 *	@package		test
 *	@subpackage		suite
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class TestSuiteCreator
{
	/**	@var	string		_class		Class name to create Test Suite for */
	var $_class;
	/**	@var	string		_class		Template to use */
	var $_template;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	class		Class name to create Test Suite for
	 *	@param		string	template 		Template to use
	 *	@return		void
	 */
	public function __construct ($class, $template = false)
	{
		if (!$template)
			$template = dirname(__FILE__)."/TestSuiteCreator.tpl";
		$this->_class = $class;
		$f = new File ($template);
		$this->_template = $f->readString ();
	}
	
	function isnochfraglich ()
	{
		//  soll $testcases füllen.
	}
	
	/**
	 *	Creates TestSuite by realizing the Template.
	 *	@access		public
	 *	@return		string
	 */
	function create ()
	{
		$testcases = '$'.'this->addTestCase ("", "example", 1, 1);';
		$code = $this->_template;
		$code = str_replace (array ("#class#", "#testcases#"), array ($this->_class, $testcases), $this->_template);	
		return $code;
	}
	
	/**
	 *	Runs TestSuite by calling a temporary File.
	 *	@access		public
	 *	@return		void
	 */
	function run ()
	{
		$filename = "_tmp_".md5(time()).".php";
		$code = $this->create ();
		$tmp = new File ($filename);
		$tmp->writeString ($code);
		require_once ($filename);
		@unlink ($filename);
	}
}
?>