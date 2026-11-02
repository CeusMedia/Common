<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\Log\Reader as LogReader;
use CeusMedia\Common\FS\File\PHP\Check\MethodOrder;
use CeusMedia\Common\FS\File\PHP\Lister;
use CeusMedia\Common\FS\File\SyntaxChecker;
use CeusMedia\Common\FS\File\TodoLister;
use CeusMedia\Common\FS\Folder\CodeLineCounter;
use CeusMedia\Common\FS\Folder\Treeview\Json;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\FS\TempFolderTrait;
use InvalidArgumentException;

/**
 *	TestUnit of several code and log tools in FS.
 *	@package		Tests.FS.File
 */
class CodeToolsTest extends BaseCase
{
	use TempFolderTrait;

	public function setUp(): void
	{
		$this->createTempFolder();
	}

	public function tearDown(): void
	{
		$this->removeTempFolder();
	}

	public function testSyntaxCheckerEscapesFileName(): void
	{
		//  the shell runs in the current working directory, so the marker would be created there
		$marker	= getcwd().'/CeusMediaCommonTestInjected';
		$fileName	= $this->tempPath.'x$(touch CeusMediaCommonTestInjected).php';
		file_put_contents( $fileName, '<?php echo 1;' );
		$checker	= new SyntaxChecker();
		self::assertTrue( $checker->checkFile( $fileName ) );
		$injected	= file_exists( $marker );
		@unlink( $marker );
		self::assertFalse( $injected, 'Shell command in file name has been executed' );

		file_put_contents( $this->tempPath.'broken.php', '<?php echo ;' );
		self::assertFalse( $checker->checkFile( $this->tempPath.'broken.php' ) );
		self::assertNotSame( '', $checker->getError() );
	}

	public function testMethodOrderWithEmptyComment(): void
	{
		file_put_contents( $this->tempPath.'Test.php', "<?php\nclass Test{\n/**/\npublic function beta(){}\n/* x */\npublic function alpha(){}\n}" );
		$check	= new MethodOrder( $this->tempPath.'Test.php' );
		self::assertFalse( $check->compare() );
		self::assertSame( ['beta', 'alpha'], $check->getOriginalList() );
	}

	public function testTodoListerMatchesExtensionOnly(): void
	{
		file_put_contents( $this->tempPath.'a.php', "<?php\n// @todo fix\n" );
		file_put_contents( $this->tempPath.'b.notphp', "@todo ignored\n" );
		$lister	= new TodoLister();
		$lister->scan( $this->tempPath );
		self::assertSame( 1, $lister->getNumberScanned() );
		self::assertSame( 1, $lister->getNumberTodos() );
	}

	public function testPhpListerSkipsDots(): void
	{
		touch( $this->tempPath.'a.php' );
		$names	= [];
		foreach( new Lister( $this->tempPath, [], [], [], FALSE ) as $entry )
			$names[]	= $entry->getFilename();
		self::assertSame( ['a.php'], $names );
	}

	public function testLogReaderOffsetAndLimit(): void
	{
		file_put_contents( $this->tempPath.'log', "1\n2\n3\n4\n5" );
		self::assertSame( ['4', '5'], LogReader::load( $this->tempPath.'log', -2 ) );
		self::assertSame( ['2', '3'], LogReader::load( $this->tempPath.'log', 1, 2 ) );
		self::assertSame( ['1', '2'], LogReader::load( $this->tempPath.'log', 0, 2 ) );
	}

	public function testCodeLineCounterGetData(): void
	{
		file_put_contents( $this->tempPath.'a.php', "<?php\n/**\n *	Doc\n */\necho 1;\n" );
		$counter	= new CodeLineCounter();
		$counter->readFolder( $this->tempPath, ['php'] );
		self::assertSame( 1, $counter->getData( 'numberFiles' ) );
		self::assertSame( 1, $counter->getData( 'number' )['codes'] );
		self::assertIsFloat( $counter->getData( 'ratioLinesPerFile' ) );
		$this->expectException( InvalidArgumentException::class );
		$counter->getData( 'unknown' );
	}

	public function testTreeviewEscapesAndRejectsTraversal(): void
	{
		mkdir( $this->tempPath.'<b>folder' );
		touch( $this->tempPath.'<i>file.txt' );
		$json	= ( new Json( $this->tempPath ) )->buildJson();
		self::assertStringNotContainsString( '<b>', $json );
		self::assertStringNotContainsString( '<i>', $json );
		$this->expectException( InvalidArgumentException::class );
		( new Json( $this->tempPath ) )->buildJson( '../' );
	}
}
