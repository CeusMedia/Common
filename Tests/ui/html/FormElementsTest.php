<?php
/**
 *	TestUnit of Tag.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_FormElements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.ui.html.FormElements' );
/**
 *	TestUnit of Gauss Blur.
 *	@package		Tests.ui.html
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_HTML_FormElements
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.04.2008
 *	@version		0.1
 */
class Tests_UI_HTML_FormElementsTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Tests Method 'Button'.
	 *	@access		public
	 *	@return		void
	 */
	public function testButton()
	{
		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass"><span>testLabel</span></button>';
		$creation	= UI_HTML_FormElements::Button( "testButton", "testLabel", "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass" onclick="return confirm(\'testConfirm\');"><span>testLabel</span></button>';
		$creation	= UI_HTML_FormElements::Button( "testButton", "testLabel", "testClass", "testConfirm" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass" readonly="readonly"><span>testLabel</span></button>';
		$creation	= UI_HTML_FormElements::Button( "testButton", "testLabel", "testClass", FALSE, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<button type="submit" name="testButton" value="1" class="testClass" onclick="alert(\'testDisabled\');" readonly="readonly"><span>testLabel</span></button>';
		$creation	= UI_HTML_FormElements::Button( "testButton", "testLabel", "testClass", NULL, "testDisabled" );
		$this->assertEquals( $assertion, $creation );
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
		$creation	= UI_HTML_FormElements::CheckBox( "testName", "", "", "", "" );
		$this->assertEquals( $assertion, $creation );
*/
		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass"/>';
		$creation	= UI_HTML_FormElements::CheckBox( "testName", "testValue", FALSE, "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass" checked="checked"/>';
		$creation	= UI_HTML_FormElements::CheckBox( "testName", "testValue", TRUE, "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass" disabled="disabled" readonly="readonly"/>';
		$creation	= UI_HTML_FormElements::CheckBox( "testName", "testValue", NULL, "testClass", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="checkbox" name="testName" value="testValue" class="testClass" disabled="disabled" readonly="readonly" onclick="alert(\'testDisabled\');"/>';
		$creation	= UI_HTML_FormElements::CheckBox( "testName", "testValue", NULL, "testClass", "testDisabled" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'HiddenField'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHiddenField()
	{
		$assertion	= '<input id="testName" type="hidden" name="testName" value="testValue"/>';
		$creation	= UI_HTML_FormElements::HiddenField( "testName", "testValue" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Input'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInput()
	{
		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass"/>';
		$creation	= UI_HTML_FormElements::Input( "testName", "testValue", "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass" readonly="readonly"/>';
		$creation	= UI_HTML_FormElements::Input( "testName", "testValue", "testClass", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass" readonly="readonly" onclick="alert(\'testDisabled\');"/>';
		$creation	= UI_HTML_FormElements::Input( "testName", "testValue", "testClass", "testDisabled" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="text" name="testName" value="testValue" class="testClass" tabindex="10" maxlength="20" onkeyup="allowOnly(this,\'numeric\');"/>';
		$creation	= UI_HTML_FormElements::Input( "testName", "testValue", "testClass", FALSE, 10, 20, "numeric" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'File'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFile()
	{
		$assertion	= '<input id="testName" type="file" name="testName" value="testValue" class="testClass" tabindex="10" maxlength="20"/>';
		$creation	= UI_HTML_FormElements::File( "testName", "testValue", "testClass", FALSE, 10, 20 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName" type="file" name="testName" value="testValue" readonly="readonly" onclick="alert(\'testDisabled\');"/>';
		$creation	= UI_HTML_FormElements::File( "testName", "testValue", FALSE, "testDisabled" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Form'.
	 *	@access		public
	 *	@return		void
	 */
	public function testForm()
	{
		$assertion	= '<form method="POST">';
		$creation	= UI_HTML_FormElements::Form();
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<form id="form_testName" name="testName" action="testURL" target="testTarget" method="POST" enctype="testEnctype" onsubmit="testSubmit">';
		$creation	= UI_HTML_FormElements::Form( "testName", "testURL", "testTarget", "testEnctype", "testSubmit" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Label'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLabel()
	{
		$assertion	= '<label for="testId">testLabel</label>';
		$creation	= UI_HTML_FormElements::Label( "testId", "testLabel" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<label for="testId" class="testClass">testLabel</label>';
		$creation	= UI_HTML_FormElements::Label( "testId", "testLabel", "testClass" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'LinkButton'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLinkButton()
	{
		$assertion	= '<button id="button_423d7f72ed90277acca9dab9098f12a7" type="button" onclick="document.location.href=\'testURL\';"><span>testLabel</span></button>';
		$creation	= UI_HTML_FormElements::LinkButton( "testURL", "testLabel" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<button id="button_423d7f72ed90277acca9dab9098f12a7" type="button" class="testClass" onclick="if(confirm(\'testConfirm\')){document.location.href=\'testURL\';};"><span>testLabel</span></button>';
		$creation	= UI_HTML_FormElements::LinkButton( "testURL", "testLabel", "testClass", "testConfirm" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<button id="button_423d7f72ed90277acca9dab9098f12a7" type="button" disabled="disabled" onclick="alert(\'testDisabled\');" readonly="readonly"><span>testLabel</span></button>';
		$creation	= UI_HTML_FormElements::LinkButton( "testURL", "testLabel", NULL, "testConfirm", "testDisabled" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Option'.
	 *	@access		public
	 *	@return		void
	 */
	public function testOption()
	{
		$assertion	= '<option value="testValue">testLabel</option>';
		$creation	= UI_HTML_FormElements::Option( "testValue", "testLabel" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" selected="selected">testLabel</option>';
		$creation	= UI_HTML_FormElements::Option( "testValue", "testLabel", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" disabled="disabled">testLabel</option>';
		$creation	= UI_HTML_FormElements::Option( "testValue", "testLabel", NULL, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" selected="selected" disabled="disabled">testLabel</option>';
		$creation	= UI_HTML_FormElements::Option( "testValue", "testLabel", TRUE, TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" class="testClass">testLabel</option>';
		$creation	= UI_HTML_FormElements::Option( "testValue", "testLabel", NULL, NULL, "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<option value="testValue" selected="selected" disabled="disabled" class="testClass">testLabel</option>';
		$creation	= UI_HTML_FormElements::Option( "testValue", "testLabel", TRUE, TRUE, "testClass" );
		$this->assertEquals( $assertion, $creation );
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
		$creation	= UI_HTML_FormElements::Options( $options, $selected );
		$this->assertEquals( $assertion, $creation );

		$selected	= array( "value1", "value2" );
		$assertion	= '<option value="value1" selected="selected">label1</option><option value="value2" selected="selected">label2</option>';
		$creation	= UI_HTML_FormElements::Options( $options, $selected );
		$this->assertEquals( $assertion, $creation );

		$options	= array(
			array(
				'_groupname'	=> "group1",
				'value11'	=> "label11",
			),
		);
		$selected	= "value11";
		$assertion	= '<optgroup label="group1"><option value="value11" selected="selected">label11</option></optgroup>';
		$creation	= UI_HTML_FormElements::Options( $options, $selected );
		$this->assertEquals( $assertion, $creation );

		$options	= array(
			'_selected'		=> "value11",
			array(
				'_groupname'	=> "group1",
				'value11'		=> "label11",
			),
		);
		$assertion	= '<optgroup label="group1"><option value="value11" selected="selected">label11</option></optgroup>';
		$creation	= UI_HTML_FormElements::Options( $options );
		$this->assertEquals( $assertion, $creation );

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
		$creation	= UI_HTML_FormElements::Options( $options );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Password'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPassword()
	{
		$assertion	= '<input id="testName" type="password" name="testName" class="testClass" tabindex="10" maxlength="20" readonly="readonly" onclick="alert(\'testDisabled\');"/>';
		$creation	= UI_HTML_FormElements::Password( "testName", "testClass", "testDisabled", 10, 20 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'Radio'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRadio()
	{
		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass"/>';
		$creation	= UI_HTML_FormElements::Radio( "testName", "testValue", FALSE, "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass" checked="checked"/>';
		$creation	= UI_HTML_FormElements::Radio( "testName", "testValue", TRUE, "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass" disabled="disabled" readonly="readonly"/>';
		$creation	= UI_HTML_FormElements::Radio( "testName", "testValue", NULL, "testClass", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<input id="testName_testValue" type="radio" name="testName" value="testValue" class="testClass" disabled="disabled" readonly="readonly" onclick="alert(\'testDisabled\');"/>';
		$creation	= UI_HTML_FormElements::Radio( "testName", "testValue", NULL, "testClass", "testDisabled" );
		$this->assertEquals( $assertion, $creation );
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
		$fieldRadio	= UI_HTML_FormElements::Radio( 'testName', 'value1', FALSE, 'testClass' );
		$spanRadio	= '<span class="radio">'.$fieldRadio.'</span>';
		$spanLabel	= '<span class="label"><label for="testName_value1">label1</label></span>';
		$assertion	= '<span class="radiolabel">'.$spanRadio.$spanLabel.'</span>';

		$fieldRadio	= '<input id="testName_value2" type="radio" name="testName" value="value2" class="testClass" checked="checked"/>';
		$spanRadio	= '<span class="radio">'.$fieldRadio.'</span>';
		$spanLabel	= '<span class="label"><label for="testName_value2">label2</label></span>';
		$assertion	.= '<span class="radiolabel">'.$spanRadio.$spanLabel.'</span>';
		
		$creation	= UI_HTML_FormElements::RadioGroup( "testName", $options, "testClass" );
		$this->assertEquals( $assertion, $creation );


		$options	= array( 'value1' => 'label1' );
		$fieldRadio	= UI_HTML_FormElements::Radio( 'testName', 'value1', FALSE, NULL, 'testDisabled' );
		$spanRadio	= '<span class="radio">'.$fieldRadio.'</span>';
		$spanLabel	= '<span class="label"><label for="testName_value1">label1</label></span>';
		$assertion	= '<span class="radiolabel">'.$spanRadio.$spanLabel.'</span>';
		$creation	= UI_HTML_FormElements::RadioGroup( "testName", $options, FALSE, "testDisabled" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'ResetButton'.
	 *	@access		public
	 *	@return		void
	 */
	public function testResetButton()
	{
		$assertion	= '<button type="reset" class="testClass">testLabel</button>';
		$creation	= UI_HTML_FormElements::ResetButton( "testLabel", "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<button type="reset" class="testClass" onclick="return confirm(\'testConfirm\');">testLabel</button>';
		$creation	= UI_HTML_FormElements::ResetButton( "testLabel", "testClass", "testConfirm" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<button type="reset" class="testClass" onclick="alert(\'testDisabled\');" readonly="readonly">testLabel</button>';
		$creation	= UI_HTML_FormElements::ResetButton( "testLabel", "testClass", NULL, "testDisabled" );
		$this->assertEquals( $assertion, $creation );
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
		$creation	= UI_HTML_FormElements::Select( "testName", $options, "testClass" );
		$this->assertEquals( $assertion, $creation );

		$options	= UI_HTML_FormElements::Options( $options );
		$assertion	= '<select id="testName" name="testName" class="testClass">'.$options.'</select>';
		$creation	= UI_HTML_FormElements::Select( "testName", $options, "testClass" );
		$this->assertEquals( $assertion, $creation );

		$options	= array(
			'value1'	=> 'label1',
		);
		$assertion	= '<select id="testName" name="testName" onchange="document.getElementById(\'testFocus\').focus();document.getElementById(\'form_testSubmit\').submit();testChange"><option value="value1">label1</option></select>';
		$creation	= UI_HTML_FormElements::Select( "testName", $options, NULL, NULL, "testSubmit", "testFocus", "testChange" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<select id="testName" name="testName" readonly="readonly" onclick="alert(\'testDisabled\');"><option value="value1">label1</option></select>';
		$creation	= UI_HTML_FormElements::Select( "testName", $options, NULL, "testDisabled" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'TextArea'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTextArea()
	{
		$assertion	= '<textarea id="testName" name="testName" class="testClass">testContent</textarea>';
		$creation	= UI_HTML_FormElements::TextArea( "testName", "testContent", "testClass" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<textarea id="testName" name="testName" class="testClass" readonly="readonly">testContent</textarea>';
		$creation	= UI_HTML_FormElements::TextArea( "testName", "testContent", "testClass", TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<textarea id="testName" name="testName" class="testClass" readonly="readonly" onclick="alert(\'testDisabled\');">testContent</textarea>';
		$creation	= UI_HTML_FormElements::TextArea( "testName", "testContent", "testClass", "testDisabled" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '<textarea id="testName" name="testName" class="testClass" onkeyup="allowOnly(this,\'all\');">testContent</textarea>';
		$creation	= UI_HTML_FormElements::TextArea( "testName", "testContent", "testClass", NULL, "all" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>