<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Tag.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\UI\HTML;

use CeusMedia\Common\UI\HTML\FormElements;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.html
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class FormElementsTest extends BaseCase
{
	/**
	 *	Tests Method 'Button'.
	 *	@access		public
	 *	@return		void
	 */
	public function testButton()
	{
		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass"><span>testLabel</span></button>';
		$creation	= FormElements::Button( "testButton", "testLabel", "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass" onclick="return confirm(&#039;testConfirm&#039;);"><span>testLabel</span></button>';
		$creation	= FormElements::Button( "testButton", "testLabel", "testClass", "testConfirm" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass" disabled="disabled"><span>testLabel</span></button>';
		$creation	= FormElements::Button( "testButton", "testLabel", "testClass", NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass" onclick="alert(&#039;testDisabled&#039;);" readonly="readonly"><span>testLabel</span></button>';
		$creation	= FormElements::Button( "testButton", "testLabel", "testClass", NULL, "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'CheckBox'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckBox()
	{
/*		XHTML 1.1
		$assertion	= '<input id="testName" type="checkbox" name="testName"/>';
		$creation	= FormElements::CheckBox( "testName", "", "", "", "" );
		self::assertEquals( $assertion, $creation );
*/
		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass"/>';
		$creation	= FormElements::CheckBox( "testName", "testValue", FALSE, "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass" checked="checked"/>';
		$creation	= FormElements::CheckBox( "testName", "testValue", TRUE, "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass" disabled="disabled" readonly="readonly"/>';
		$creation	= FormElements::CheckBox( "testName", "testValue", FALSE, "testClass", TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass" readonly="readonly" onclick="alert(&#039;testDisabled&#039;);"/>';
		$creation	= FormElements::CheckBox( "testName", "testValue", FALSE, "testClass", "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'HiddenField'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHiddenField()
	{
		$assertion	= '<input id="testName" type="hidden" name="testName" value="testValue"/>';
		$creation	= FormElements::HiddenField( "testName", "testValue" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Input'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInput()
	{
		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass"/>';
		$creation	= FormElements::Input( "testName", "testValue", "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass" readonly="readonly"/>';
		$creation	= FormElements::Input( "testName", "testValue", "testClass", TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass" readonly="readonly" onclick="alert(&#039;testDisabled&#039;);"/>';
		$creation	= FormElements::Input( "testName", "testValue", "testClass", "testDisabled" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass" tabindex="10" maxlength="20" onkeyup="allowOnly(this,&#039;numeric&#039;);"/>';
		$creation	= FormElements::Input( "testName", "testValue", "testClass", FALSE, 10, 20, "numeric" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'File'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFile()
	{
		$assertion	= '<input id="testName" type="file" name="testName" value="testValue" class="testClass" tabindex="10" maxlength="20"/>';
		$creation	= FormElements::File( "testName", "testValue", "testClass", FALSE, 10, 20 );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="file" name="testName" value="testValue" readonly="readonly" onclick="alert(&#039;testDisabled&#039;);"/>';
		$creation	= FormElements::File( "testName", "testValue", NULL, "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Form'.
	 *	@access		public
	 *	@return		void
	 */
	public function testForm()
	{
		$assertion	= '<form method="post">';
		$creation	= FormElements::Form();
		self::assertEquals( $assertion, $creation );

		$assertion	= '<form id="form_testName" name="testName" action="testURL" target="testTarget" method="post" enctype="testEnctype" onsubmit="testSubmit">';
		$creation	= FormElements::Form( "testName", "testURL", "testTarget", "testEnctype", "testSubmit" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Label'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLabel()
	{
		$assertion	= '<label for="testId">testLabel</label>';
		$creation	= FormElements::Label( "testId", "testLabel" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<label for="testId" class="testClass">testLabel</label>';
		$creation	= FormElements::Label( "testId", "testLabel", "testClass" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'LinkButton'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLinkButton()
	{
		$assertion	= '<button id="button_423d7f72ed90277acca9dab9098f12a7" type="button" onclick="document.location.href=&#039;testURL&#039;;"><span>testLabel</span></button>';
		$creation	= FormElements::LinkButton( "testURL", "testLabel" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<button id="button_423d7f72ed90277acca9dab9098f12a7" type="button" class="testClass" onclick="if(confirm(&#039;testConfirm&#039;)){document.location.href=&#039;testURL&#039;;};"><span>testLabel</span></button>';
		$creation	= FormElements::LinkButton( "testURL", "testLabel", "testClass", "testConfirm" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<button id="button_423d7f72ed90277acca9dab9098f12a7" type="button" onclick="alert(&#039;testDisabled&#039;);" readonly="readonly"><span>testLabel</span></button>';
		$creation	= FormElements::LinkButton( "testURL", "testLabel", NULL, "testConfirm", "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Option'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOption()
	{
		$assertion	= '<option value="testValue">testLabel</option>';
		$creation	= FormElements::Option( "testValue", "testLabel" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" selected="selected">testLabel</option>';
		$creation	= FormElements::Option( "testValue", "testLabel", TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" disabled="disabled">testLabel</option>';
		$creation	= FormElements::Option( "testValue", "testLabel", FALSE, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" selected="selected" disabled="disabled">testLabel</option>';
		$creation	= FormElements::Option( "testValue", "testLabel", TRUE, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" class="testClass">testLabel</option>';
		$creation	= FormElements::Option( "testValue", "testLabel", FALSE, FALSE, "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" selected="selected" disabled="disabled" class="testClass">testLabel</option>';
		$creation	= FormElements::Option( "testValue", "testLabel", TRUE, TRUE, "testClass" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Options'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOptions()
	{
		$options	= array(
			'value1'	=> "label1",
			'value2'	=> "label2",
		);
		$selected	= "value2";
		$assertion	= '<option value="value1">label1</option><option value="value2" selected="selected">label2</option>';
		$creation	= FormElements::Options( $options, $selected );
		self::assertEquals( $assertion, $creation );

		$selected	= array( "value1", "value2" );
		$assertion	= '<option value="value1" selected="selected">label1</option><option value="value2" selected="selected">label2</option>';
		$creation	= FormElements::Options( $options, $selected );
		self::assertEquals( $assertion, $creation );

		$options	= array(
			array(
				'_groupname'	=> "group1",
				'value11'	=> "label11",
			),
		);
		$selected	= "value11";
		$assertion	= '<optgroup label="group1"><option value="value11" selected="selected">label11</option></optgroup>';
		$creation	= FormElements::Options( $options, $selected );
		self::assertEquals( $assertion, $creation );

		$options	= array(
			'_selected'		=> "value11",
			array(
				'_groupname'	=> "group1",
				'value11'		=> "label11",
			),
		);
		$assertion	= '<optgroup label="group1"><option value="value11" selected="selected">label11</option></optgroup>';
		$creation	= FormElements::Options( $options );
		self::assertEquals( $assertion, $creation );

		$options	= array(
			'_selected'		=> array( "value11", "value22" ),
			array(
				'_groupname'	=> "group1",
				'value11'		=> "label11",
				'value12'		=> "label12",
			),
			array(
				'_groupname'	=> "group2",
				'value21'		=> "label21",
				'value22'		=> "label22",
			),
		);
		$assertion	= '<optgroup label="group1"><option value="value11" selected="selected">label11</option><option value="value12">label12</option></optgroup>'.
					  '<optgroup label="group2"><option value="value21">label21</option><option value="value22" selected="selected">label22</option></optgroup>';
		$creation	= FormElements::Options( $options );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Password'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPassword()
	{
		$assertion	= '<input id="testName" type="password" name="testName" class="testClass" tabindex="10" maxlength="20" readonly="readonly" onclick="alert(&#039;testDisabled&#039;);"/>';
		$creation	= FormElements::Password( "testName", "testClass", "testDisabled", 10, 20 );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Radio'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRadio()
	{
		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass"/>';
		$creation	= FormElements::Radio( "testName", "testValue", FALSE, "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass" checked="checked"/>';
		$creation	= FormElements::Radio( "testName", "testValue", TRUE, "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass" disabled="disabled" readonly="readonly"/>';
		$creation	= FormElements::Radio( "testName", "testValue", FALSE, "testClass", TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass" disabled="disabled" readonly="readonly" onclick="alert(&#039;testDisabled&#039;);"/>';
		$creation	= FormElements::Radio( "testName", "testValue", FALSE, "testClass", "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'RadioGroup'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRadioGroup()
	{
		$options	= array(
			'value1'	=> 'label1',
			'value2'	=> 'label2',
			'_selected'	=> 'value2',
		);

		$fieldRadio	= '<input id="testName_value1" type="radio" name="testName" value="value1" class="testClass"/>';
		$fieldRadio	= FormElements::Radio( 'testName', 'value1', FALSE, 'testClass' );
		$spanRadio	= '<span class="radio">'.$fieldRadio.'</span>';
		$spanLabel	= '<span class="label"><label for="testName_value1">label1</label></span>';
		$assertion	= '<span class="radiolabel">'.$spanRadio.$spanLabel.'</span>';

		$fieldRadio	= '<input id="testName_value2" type="radio" name="testName" value="value2" class="testClass" checked="checked"/>';
		$spanRadio	= '<span class="radio">'.$fieldRadio.'</span>';
		$spanLabel	= '<span class="label"><label for="testName_value2">label2</label></span>';
		$assertion	.= '<span class="radiolabel">'.$spanRadio.$spanLabel.'</span>';

		$creation	= FormElements::RadioGroup( "testName", $options, "testClass" );
		self::assertEquals( $assertion, $creation );


		$options	= array( 'value1' => 'label1' );
		$fieldRadio	= FormElements::Radio( 'testName', 'value1', FALSE, NULL, 'testDisabled' );
		$spanRadio	= '<span class="radio">'.$fieldRadio.'</span>';
		$spanLabel	= '<span class="label"><label for="testName_value1">label1</label></span>';
		$assertion	= '<span class="radiolabel">'.$spanRadio.$spanLabel.'</span>';
		$creation	= FormElements::RadioGroup( "testName", $options, NULL, "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'ResetButton'.
	 *	@access		public
	 *	@return		void
	 */
	public function testResetButton()
	{
		$assertion	= '<button type="reset" class="testClass">testLabel</button>';
		$creation	= FormElements::ResetButton( "testLabel", "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<button type="reset" class="testClass" onclick="return confirm(&#039;testConfirm&#039;);">testLabel</button>';
		$creation	= FormElements::ResetButton( "testLabel", "testClass", "testConfirm" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<button type="reset" class="testClass" onclick="alert(&#039;testDisabled&#039;);" readonly="readonly">testLabel</button>';
		$creation	= FormElements::ResetButton( "testLabel", "testClass", NULL, "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Select'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSelect()
	{
		$options	= array(
			''			=> '- none -',
			'value1'	=> 'label1',
			'value2'	=> 'label2',
			'_selected'	=> 'value2',
		);
		$assertion	= '<select id="testName" name="testName" class="testClass"><option value="">- none -</option><option value="value1">label1</option><option value="value2" selected="selected">label2</option></select>';
		$creation	= FormElements::Select( "testName", $options, "testClass" );
		self::assertEquals( $assertion, $creation );

		$options	= FormElements::Options( $options );
		$assertion	= '<select id="testName" name="testName" class="testClass">'.$options.'</select>';
		$creation	= FormElements::Select( "testName", $options, "testClass" );
		self::assertEquals( $assertion, $creation );

		$options	= array(
			'value1'	=> 'label1',
		);
		$assertion	= '<select id="testName" name="testName" onchange="document.getElementById(&#039;testFocus&#039;).focus();document.getElementById(&#039;form_testSubmit&#039;).submit();testChange"><option value="value1">label1</option></select>';
		$creation	= FormElements::Select( "testName", $options, NULL, NULL, "testSubmit", "testFocus", "testChange" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<select id="testName" name="testName" readonly="readonly" disabled="disabled"><option value="value1">label1</option></select>';
		$creation	= FormElements::Select( "testName", $options, NULL, TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<select id="testName" name="testName" readonly="readonly" onmousedown="alert(&#039;testDisabled&#039;); return false;"><option value="value1">label1</option></select>';
		$creation	= FormElements::Select( "testName", $options, NULL, "testDisabled" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'TextArea'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTextArea()
	{
		$assertion	= '<textarea id="testName" name="testName" class="testClass">testContent</textarea>';
		$creation	= FormElements::TextArea( "testName", "testContent", "testClass" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<textarea id="testName" name="testName" class="testClass" readonly="readonly">testContent</textarea>';
		$creation	= FormElements::TextArea( "testName", "testContent", "testClass", TRUE );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<textarea id="testName" name="testName" class="testClass" readonly="readonly" onclick="alert(&#039;testDisabled&#039;);">testContent</textarea>';
		$creation	= FormElements::TextArea( "testName", "testContent", "testClass", "testDisabled" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '<textarea id="testName" name="testName" class="testClass" onkeyup="allowOnly(this,&#039;all&#039;);">testContent</textarea>';
		$creation	= FormElements::TextArea( "testName", "testContent", "testClass", NULL, "all" );
		self::assertEquals( $assertion, $creation );
	}
}
