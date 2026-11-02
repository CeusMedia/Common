<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\UI\HTML\Tree;

use ArrayObject;
use CeusMedia\Common\UI\HTML\Tree\ArrayView;
use CeusMedia\Common\UI\HTML\Tree\FolderCheckView;
use CeusMedia\Common\UI\HTML\Tree\FolderView;
use CeusMedia\Common\UI\HTML\Tree\VariableDump;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\FS\TempFolderTrait;

/**
 *	TestUnit of UI\HTML\Tree views.
 *	@package		Tests.UI.HTML.Tree
 */
class TreeViewsTest extends BaseCase
{
	use TempFolderTrait;

	public function testArrayViewTypes(): void
	{
		$nodes	= new ArrayObject( [
			['label' => 'Folder', 'children' => [['label' => 'Leaf']]],
			['label' => 'Single'],
		] );
		$html	= ( new ArrayView( 'index.php', '?id=' ) )->constructTree( $nodes );
		self::assertStringContainsString( 'class="node"', $html );
		self::assertStringContainsString( 'href="index.php?id=Single"', $html );
		self::assertStringContainsString( 'href="index.php?id=Folder%2FLeaf"', $html );
	}

	public function testFolderViewsEscapeNames(): void
	{
		$path	= $this->createTempFolder();
		mkdir( $path.'<b>dir' );
		touch( $path.'<b>dir/<i>file.txt' );
		$html	= ( new FolderView( $path, 'index.php', '?id=' ) )->getHtml( '' );
		$check	= ( new FolderCheckView( $path ) )->buildTree();
		$this->removeTempFolder();
		self::assertStringNotContainsString( '<b>', $html );
		self::assertStringNotContainsString( '<i>', $html );
		self::assertStringContainsString( '&lt;i&gt;file.txt', $html );
		self::assertStringContainsString( 'id='.rawurlencode( '<b>dir/<i>file.txt' ), str_replace( '&amp;', '&', $html ) );
		self::assertStringNotContainsString( '<b>', $check );
	}

	public function testVariableDumpNotes(): void
	{
		$html	= VariableDump::dumpVar( ['flag' => TRUE, 'nothing' => NULL, 'text' => '<b>'], FALSE );
		self::assertStringContainsString( 'flag -> <em>TRUE</em>', $html );
		self::assertStringContainsString( 'nothing -> <em>NULL</em>', $html );
		self::assertStringContainsString( 'text -> &lt;b&gt;', $html );
	}
}
