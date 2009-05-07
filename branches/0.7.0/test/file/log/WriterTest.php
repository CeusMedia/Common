<?php
/**
 *	TestUnit of File_Log_Writer.
 *	@package		Tests.file.log
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.file.log.Writer' );
/**
 *	TestUnit of File_Log_Writer.
 *	@package		Tests.file.log
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
class File_Log_WriterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ );
		$this->fileName	= $this->path."/writer.log";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	#	@unlink( $this->fileName );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$writer	= new File_Log_Writer( $this->path."writer.test" );
		$writer->note( 1 );
		
		$assertion	= TRUE;
		$creation	= file_exists( $this->path."writer.test" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'note'.
	 *	@access		public
	 *	@return		void
	 */
	public function testNote()
	{
		$writer	= new File_Log_Writer( $this->fileName );
		
		$assertion	= TRUE;
		$creation	= $writer->note( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$content	= file_get_contents( $this->fileName );
		$pattern	= "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n@s";
		$assertion	= TRUE;
		$creation	= preg_match( "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n@s", file_get_contents( $this->fileName ) );
		$creation	= (bool) preg_match( $pattern, $content );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $writer->note( 2 );
		$this->assertEquals( $assertion, $creation );

		$content	= file_get_contents( $this->fileName );
		$pattern	= "@^[0-9]+ \[([0-9]|[.: -])+\] 1\\n[0-9]+ \[([0-9]|[.: -])+\] 2\\n@s";
		$assertion	= TRUE;
		$creation	= (bool) preg_match( $pattern, $content );
		$this->assertEquals( $assertion, $creation );
	}
}
?>