<?php
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			BinaryTree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.adt.json.Builder' );
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.json
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_JSON_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_ADT_JSON_BuilderTest extends PHPUnit_Framework_TestCase
{
	public function testEncode()
	{
		$data		= array( 1, 2, "string", TRUE, NULL );
		$builder	= new ADT_JSON_Builder();
		$assertion	= '[1,2,"string",true,null]';
		$creation	= $builder->encode( $data );
		$this->assertEquals( $assertion, $creation );

		$data		= array( array( 1, 2 ), array( 3, 4 ) );
		$builder	= new ADT_JSON_Builder();
		$assertion	= "[[1,2],[3,4]]";
		$creation	= $builder->encode( $data );
		$this->assertEquals( $assertion, $creation );
	}

	public function testEncodeStatic()
	{
		$data		= array( 1, 2, "string", TRUE, NULL );
		$assertion	= '[1,2,"string",true,null]';
		$creation	= ADT_JSON_Builder::encode( $data );
		$this->assertEquals( $assertion, $creation );

		$data		= array( array( 1, 2 ), array( 3, 4 ) );
		$assertion	= "[[1,2],[3,4]]";
		$creation	= ADT_JSON_Builder::encode( $data );
		$this->assertEquals( $assertion, $creation );

	}
}
?>