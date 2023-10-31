<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for XML Result File written by PHPUnit.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML;

use Exception;

/**
 *	Reader for XML Result File written by PHPUnit.
 *	@category		Library
 *	@package		CeusMedia_Common_XML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Code Documentation
 *	@todo			Unit Test
 */
class UnitTestResultReader
{
	/**	@var		int			$date			Date of XML File */
	protected $date;

	/**	@var		Element		$tree			XML Element Tree from XML File */
	protected $tree;

	/**
	 *	Constructor, reads XML.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML File
	 *	@return		void
	 *	@throws		Exception
	 */
	public function __construct( string $fileName )
	{
		$this->tree	= ElementReader::readFile( $fileName );
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
	public function getErrorCount(): int
	{
		return (int) $this->tree->testsuite[0]->getAttribute( 'errors' );
	}

	/**
	 *	Returns List of Error Messages.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors(): array
	{
		$list	= [];
		foreach( $this->tree->children() as $testSuite )
			$this->getMessagesRecursive( $testSuite, $list, "error" );
		return $list;
	}

	/**
	 *	Collects Error or Failure Messages by iterating Tree recursive and returns Lists.
	 *	Works in-situ on 2nd parameter $list.
	 *	@access		private
	 *	@param		Element	$element		Current XML Element
	 *	@param		array				$list			Reference to Result List
	 *	@param		string				$type			Message Type (error|failure)
	 *	@param		string				$testSuite		Current Test Suite
	 *	@return		void
	 */
	private function getMessagesRecursive( Element $element, array &$list, string $type, string $testSuite = "" ): void
	{
		if( $element->getName() == "testcase" && $element->$type ){
			$list[]	= [
				'suite'		=> $testSuite,
				'case'		=> $element->getAttribute( 'name' ),
				'error'		=> $element->$type,
				'type'		=> $element->$type->getAttribute( 'type' ),
			];
			return;
		}
		foreach( $element->children() as $child )
			$this->getMessagesRecursive( $child, $list, $type, $element->getAttribute( 'name' ) );
	}

	/**
	 *	Returns Number of Failures.
	 *	@access		public
	 *	@return		int
	 */
	public function getFailureCount(): int
	{
		return (int) $this->tree->testsuite[0]->getAttribute( 'failures' );
	}

	/**
	 *	Returns List of Failure Messages.
	 *	@access		public
	 *	@return		array
	 */
	public function getFailures(): array
	{
		$list	= [];
		foreach( $this->tree->children() as $testSuite )
			$this->getMessagesRecursive( $testSuite, $list, "failure" );
		return $list;
	}

	/**
	 *	Returns Number of Tests.
	 *	@access		public
	 *	@return		int
	 */
	public function getTestCount(): int
	{
		return (int) $this->tree->testsuite[0]->getAttribute( 'tests' );
	}

	public function getTestSuiteCount( ?Element $element = NULL ): int
	{
		$count		= 1;
		$element	??= $this->tree;
		foreach( $element->testsuite as $testSuite )
			$count	+= $this->getTestSuiteCount( $testSuite );
		return $count;
	}

	public function getTestCaseCount( ?Element $element = NULL ): int
	{
		$count		= 0;
		$element	??= $this->tree;
		foreach( $element->testsuite as $testSuite )
			$count	+= $this->getTestCaseCount( $testSuite );
		$count	+= count( $element->testcase );
		return $count;
	}

	public function getTime(): string
	{
		return $this->tree->testsuite[0]->getAttribute( 'time' );
	}
}
