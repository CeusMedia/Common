<?php
$isComposer		= file_exists( "vendor" );
$legacy			= TRUE;

$config		= parse_ini_file( __DIR__.'/Common.ini', TRUE );
$version	= $config['project']['version'];

//print_m( $version );

abstract class ADT_Cache_StaticStore extends \CeusMedia\Common\ADT\Cache\StaticStore{}
abstract class ADT_Cache_Store extends \CeusMedia\Common\ADT\Cache\Store{}
class ADT_List_Dictionary extends \CeusMedia\Common\ADT\Collection\Dictionary{};
class ADT_List_LevelMap extends \CeusMedia\Common\ADT\Collection\LevelMap{};
class ADT_List_Queue extends \CeusMedia\Common\ADT\Collection\Queue{};
class ADT_List_SectionList extends \CeusMedia\Common\ADT\Collection\SectionList{};
class ADT_List_Stack extends \CeusMedia\Common\ADT\Collection\Stack{};
class ADT_CSS_Property extends \CeusMedia\Common\ADT\CSS\Property{};
class ADT_CSS_Rule extends \CeusMedia\Common\ADT\CSS\Rule{};
class ADT_CSS_Sheet extends \CeusMedia\Common\ADT\CSS\Sheet{};
class ADT_Event_Callback extends \CeusMedia\Common\ADT\Event\Callback{};
class ADT_Event_Data extends \CeusMedia\Common\ADT\Event\Data{};
class ADT_Event_Handler extends \CeusMedia\Common\ADT\Event\Handler{};
class ADT_JSON_Builder extends \CeusMedia\Common\ADT\JSON\Builder{};
class ADT_JSON_Converter extends \CeusMedia\Common\ADT\JSON\Converter{};
class ADT_JSON_Formater extends \CeusMedia\Common\ADT\JSON\Formater{};
class ADT_JSON_Parser extends \CeusMedia\Common\ADT\JSON\Parser{};
class ADT_OptionObject extends \CeusMedia\Common\ADT\OptionObject{};
class ADT_Time_Delay extends \CeusMedia\Common\ADT\Time\Delay{};
class ADT_Tree_Menu_Item extends \CeusMedia\Common\ADT\Tree\Menu\Item{};
class ADT_Tree_Menu_List extends \CeusMedia\Common\ADT\Tree\Menu\Collection{};
class ADT_URL_Compare extends \CeusMedia\Common\ADT\URL\Compare{};
class ADT_URL_Inference extends \CeusMedia\Common\ADT\URL\Inference{};
class ADT_Bitmask extends \CeusMedia\Common\ADT\Bitmask{};
class ADT_Constant extends \CeusMedia\Common\ADT\Constant{};
class ADT_List extends \CeusMedia\Common\ADT\Collection{};
class ADT_Multiplexer extends \CeusMedia\Common\ADT\Multiplexer{};
class ADT_Registry extends \CeusMedia\Common\ADT\Registry{};
abstract class ADT_Singleton extends \CeusMedia\Common\ADT\Singleton{};
class ADT_StringBuffer extends \CeusMedia\Common\ADT\StringBuffer{};
class ADT_URL extends \CeusMedia\Common\ADT\URL{};
class ADT_URN extends \CeusMedia\Common\ADT\URN{};
class ADT_VCard extends \CeusMedia\Common\ADT\VCard{};

class ADT_Tree_BinaryNode extends \CeusMedia\Common\ADT\Tree\BinaryNode{};
class ADT_Tree_BalanceBinaryNode extends \CeusMedia\Common\ADT\Tree\BalanceBinaryNode{};
class ADT_Tree_AvlNode extends \CeusMedia\Common\ADT\Tree\AvlNode{};
class ADT_Tree_Node extends \CeusMedia\Common\ADT\Tree\Node{};
class ADT_Tree_MagicNode extends \CeusMedia\Common\ADT\Tree\MagicNode{};
class ADT_Object extends \CeusMedia\Common\ADT\Object_{};
class ADT_String extends \CeusMedia\Common\ADT\String_{};
class ADT_Null extends \CeusMedia\Common\ADT\Null_{};
class ADT_Graph_NodeSet extends \CeusMedia\Common\ADT\Graph\NodeSet{};
class ADT_Graph_Weighted extends \CeusMedia\Common\ADT\Graph\Weighted{};
class ADT_Graph_Node extends \CeusMedia\Common\ADT\Graph\Node{};
class ADT_Graph_Edge extends \CeusMedia\Common\ADT\Graph\Edge{};
class ADT_Graph_DirectedWeighted extends \CeusMedia\Common\ADT\Graph\DirectedWeighted{};
class ADT_Graph_EdgeSet extends \CeusMedia\Common\ADT\Graph\EdgeSet{};
class ADT_Graph_DirectedAcyclicWeighted extends \CeusMedia\Common\ADT\Graph\DirectedAcyclicWeighted{};

class Alg_Object_Constant extends \CeusMedia\Common\Alg\Obj\Constant{};
class Alg_Object_Delegation extends \CeusMedia\Common\Alg\Obj\Delegation{};
class Alg_Object_EventHandler extends \CeusMedia\Common\Alg\Obj\EventHandler{};
class Alg_Object_Factory extends \CeusMedia\Common\Alg\Obj\Factory{};
class Alg_Object_MethodFactory extends \CeusMedia\Common\Alg\Obj\MethodFactory{};

class Alg_Text_CamelCase extends \CeusMedia\Common\Alg\Text\CamelCase{};
class Alg_Sort_Bubble extends \CeusMedia\Common\Alg\Sort\Bubble{};
class Alg_Sort_Gnome extends \CeusMedia\Common\Alg\Sort\Gnome{};
class Alg_Sort_Insertion extends \CeusMedia\Common\Alg\Sort\Insertion{};
class Alg_Sort_MapList extends \CeusMedia\Common\Alg\Sort\MapList{};
class Alg_Sort_Quick extends \CeusMedia\Common\Alg\Sort\Quick{};
class Alg_Sort_Selection extends \CeusMedia\Common\Alg\Sort\Selection{};
class Alg_UnitFormater extends \CeusMedia\Common\Alg\UnitFormater{};

class Alg_Validation_PredicateValidator extends \CeusMedia\Common\Alg\Validation\PredicateValidator{};
class Alg_Validation_LanguageValidator extends \CeusMedia\Common\Alg\Validation\LanguageValidator{};
class Alg_Validation_DefinitionValidator extends \CeusMedia\Common\Alg\Validation\DefinitionValidator{};
class Alg_Validation_Predicates extends \CeusMedia\Common\Alg\Validation\Predicates{};
class Alg_Tree_Menu_Converter extends \CeusMedia\Common\Alg\Tree\Menu\Converter{};
class Alg_UnitParser extends \CeusMedia\Common\Alg\UnitParser{};
class Alg_Parcel_Factory extends \CeusMedia\Common\Alg\Parcel\Factory{};
class Alg_Parcel_Packer extends \CeusMedia\Common\Alg\Parcel\Packer{};
class Alg_Parcel_Packet extends \CeusMedia\Common\Alg\Parcel\Packet{};
class Alg_SgmlTagReader extends \CeusMedia\Common\Alg\SgmlTagReader{};
class Alg_Randomizer extends \CeusMedia\Common\Alg\Randomizer{};
class Alg_JS_Minifier extends \CeusMedia\Common\Alg\JS\Minifier{};
class Alg_Crypt_Rot13 extends \CeusMedia\Common\Alg\Crypt\Rot13{};
class Alg_Crypt_Caesar extends \CeusMedia\Common\Alg\Crypt\Caesar{};
class Alg_Crypt_PasswordStrength extends \CeusMedia\Common\Alg\Crypt\PasswordStrength{};
class Alg_Search_Binary extends \CeusMedia\Common\Alg\Search\Binary{};
class Alg_Search_Interpolation extends \CeusMedia\Common\Alg\Search\Interpolation{};
class Alg_Search_Strange extends \CeusMedia\Common\Alg\Search\Strange{};
class Alg_Text_SnakeCase extends \CeusMedia\Common\Alg\Text\SnakeCase{};
class Alg_Text_Extender extends \CeusMedia\Common\Alg\Text\Extender{};
class Alg_Text_EncodingConverter extends \CeusMedia\Common\Alg\Text\EncodingConverter{};
class Alg_Text_Trimmer extends \CeusMedia\Common\Alg\Text\Trimmer{};
class Alg_Text_PascalCase extends \CeusMedia\Common\Alg\Text\PascalCase{};
class Alg_Text_TermExtractor extends \CeusMedia\Common\Alg\Text\TermExtractor{};
class Alg_Text_Filter extends \CeusMedia\Common\Alg\Text\Filter{};
class Alg_Text_Unicoder extends \CeusMedia\Common\Alg\Text\Unicoder{};
class Alg_Turing_Machine extends \CeusMedia\Common\Alg\Turing\Machine{};
class Alg_ColorConverter extends \CeusMedia\Common\Alg\ColorConverter{};
class Alg_HtmlParser extends \CeusMedia\Common\Alg\HtmlParser{};
class Alg_HtmlMetaTagReader extends \CeusMedia\Common\Alg\HtmlMetaTagReader{};
class Alg_ID extends \CeusMedia\Common\Alg\ID{};
class Alg_UnusedVariableFinder extends \CeusMedia\Common\Alg\UnusedVariableFinder{};
class Alg_Time_Converter extends \CeusMedia\Common\Alg\Time\Converter{};
class Alg_Time_DurationPhraser extends \CeusMedia\Common\Alg\Time\DurationPhraser{};
class Alg_Time_Clock extends \CeusMedia\Common\Alg\Time\Clock{};
class Alg_Time_Duration extends \CeusMedia\Common\Alg\Time\Duration{};
class Alg_Time_DurationPhraseRanges extends \CeusMedia\Common\Alg\Time\DurationPhraseRanges{};


class UI_JS_CodeMirror extends \CeusMedia\Common\UI\JS\CodeMirror{};

class CLI_Command_ArgumentParser extends \CeusMedia\Common\CLI\Command\ArgumentParser{};
abstract class CLI_Command_Program extends \CeusMedia\Common\CLI\Command\Program{};
abstract class CLI_Fork_Server_Client_Abstract extends \CeusMedia\Common\CLI\Fork\Server\Client\Abstraction{};
class CLI_Fork_Server_Client_WebProxy extends \CeusMedia\Common\CLI\Fork\Server\Client\WebProxy{};
abstract class CLI_Fork_Server_Abstraction extends \CeusMedia\Common\CLI\Fork\Server\Abstraction{};
class CLI_Fork_Server_Dynamic extends \CeusMedia\Common\CLI\Fork\Server\Dynamic{};
class CLI_Fork_Server_Exception extends \CeusMedia\Common\CLI\Fork\Server\Exception{};
class CLI_Fork_Server_SocketException extends \CeusMedia\Common\CLI\Fork\Server\SocketException{};
class CLI_Fork_Server_Reflection extends \CeusMedia\Common\CLI\Fork\Server\Reflect{};
abstract class CLI_Fork_Worker_Abstract extends \CeusMedia\Common\CLI\Fork\Worker\Abstraction{};
abstract class CLI_Fork_Abstract extends \CeusMedia\Common\CLI\Fork\Abstraction{};
class CLI_Output_Progress extends \CeusMedia\Common\CLI\Output\Progress{};
class CLI_Output_Table extends \CeusMedia\Common\CLI\Output\Table{};
class CLI_Server_Cron_Daemon extends \CeusMedia\Common\CLI\Server\Cron\Daemon{};
class CLI_Server_Cron_Job extends \CeusMedia\Common\CLI\Server\Cron\Job{};
class CLI_Server_Cron_Parser extends \CeusMedia\Common\CLI\Server\Cron\Parser{};
class CLI_Server_Daemon extends \CeusMedia\Common\CLI\Server\Daemon{};
class CLI_Application extends \CeusMedia\Common\CLI\Application{};
class CLI_ArgumentParser extends \CeusMedia\Common\CLI\ArgumentParser{};
class CLI_Color extends \CeusMedia\Common\CLI\Color{};
class CLI_Dimensions extends \CeusMedia\Common\CLI\Dimensions{};
class CLI_Downloader extends \CeusMedia\Common\CLI\Downloader{};
class CLI_Output extends \CeusMedia\Common\CLI\Output{};
class CLI_Prompt extends \CeusMedia\Common\CLI\Prompt{};
class CLI_Question extends \CeusMedia\Common\CLI\Question{};
class CLI_RequestReceiver extends \CeusMedia\Common\CLI\RequestReceiver{};
class CLI_Shell extends \CeusMedia\Common\CLI\Shell{};

class Deprecation extends \CeusMedia\Common\Deprecation{};

class FS_File_Editor extends \CeusMedia\Common\FS\File\Editor{};
class FS_File_Reader extends \CeusMedia\Common\FS\File\Reader{};
class FS_File_Writer extends \CeusMedia\Common\FS\File\Writer{};

class FS_File_RegexFilter extends \CeusMedia\Common\FS\File\RegexFilter{};
class FS_File_RecursiveRegexFilter extends \CeusMedia\Common\FS\File\RecursiveRegexFilter{};

class FS_Folder_Editor extends \CeusMedia\Common\FS\Folder\Editor{};
class FS_Folder_Lister extends \CeusMedia\Common\FS\Folder\Lister{};
class FS_Folder_RecursiveLister extends \CeusMedia\Common\FS\Folder\RecursiveLister{};

class FS_Folder_RegexFilter extends \CeusMedia\Common\FS\Folder\RegexFilter{};
class FS_Folder_CodeLineCounter extends \CeusMedia\Common\FS\Folder\CodeLineCounter{};
class FS_Folder_MethodVisibilityCheck extends \CeusMedia\Common\FS\Folder\MethodVisibilityCheck{};
class FS_Folder_Iterator extends \CeusMedia\Common\FS\Folder\Iterator{};
class FS_Folder_Reader extends \CeusMedia\Common\FS\Folder\Reader{};
class FS_Folder_MethodSortCheck extends \CeusMedia\Common\FS\Folder\MethodSortCheck{};
class FS_Folder_RecursiveIterator extends \CeusMedia\Common\FS\Folder\RecursiveIterator{};
class FS_Folder_RecursiveRegexFilter extends \CeusMedia\Common\FS\Folder\RecursiveRegexFilter{};
class FS_Folder_Treeview_JsonExtended extends \CeusMedia\Common\FS\Folder\Treeview\JsonExtended{};
class FS_Folder_Treeview_Json extends \CeusMedia\Common\FS\Folder\Treeview\Json{};
class FS_Folder_SyntaxChecker extends \CeusMedia\Common\FS\Folder\SyntaxChecker{};
class FS_File extends \CeusMedia\Common\FS\File{};
class FS_AbstractNode extends \CeusMedia\Common\FS\AbstractNode{};
class FS_File_Backup extends \CeusMedia\Common\FS\File\Backup{};
class FS_File_Permissions extends \CeusMedia\Common\FS\File\Permissions{};
class FS_File_INI extends \CeusMedia\Common\FS\File\INI{};
class FS_File_BackupCleaner extends \CeusMedia\Common\FS\File\BackupCleaner{};
class FS_File_VCard_Reader extends \CeusMedia\Common\FS\File\VCard\Reader{};
class FS_File_VCard_Writer extends \CeusMedia\Common\FS\File\VCard\Writer{};
class FS_File_VCard_Builder extends \CeusMedia\Common\FS\File\VCard\Builder{};
class FS_File_VCard_Parser extends \CeusMedia\Common\FS\File\VCard\Parser{};
class FS_File_NameFilter extends \CeusMedia\Common\FS\File\NameFilter{};
class FS_File_CodeLineCounter extends \CeusMedia\Common\FS\File\CodeLineCounter{};
class FS_File_CSV_Iterator extends \CeusMedia\Common\FS\File\CSV\Iterator{};
class FS_File_CSV_Reader extends \CeusMedia\Common\FS\File\CSV\Reader{};
class FS_File_CSV_Writer extends \CeusMedia\Common\FS\File\CSV\Writer{};
class FS_File_TodoLister extends \CeusMedia\Common\FS\File\TodoLister{};
class FS_File_Iterator extends \CeusMedia\Common\FS\File\Iterator{};
class FS_File_PHP_Encoder extends \CeusMedia\Common\FS\File\PHP\Encoder{};
class FS_File_PHP_Test_Creator extends \CeusMedia\Common\FS\File\PHP\Test\Creator{};
class FS_File_PHP_Lister extends \CeusMedia\Common\FS\File\PHP\Lister{};
class FS_File_PHP_Check_MethodOrder extends \CeusMedia\Common\FS\File\PHP\Check\MethodOrder{};
class FS_File_PHP_Check_MethodVisibility extends \CeusMedia\Common\FS\File\PHP\Check\MethodVisibility{};
class FS_File_RecursiveIterator extends \CeusMedia\Common\FS\File\RecursiveIterator{};
class FS_File_Log_File extends \CeusMedia\Common\FS\File\Log\File{};
class FS_File_Log_JSON_Reader extends \CeusMedia\Common\FS\File\Log\JSON\Reader{};
class FS_File_Log_JSON_Writer extends \CeusMedia\Common\FS\File\Log\JSON\Writer{};
class FS_File_Log_ShortReader extends \CeusMedia\Common\FS\File\Log\ShortReader{};
class FS_File_Log_ShortWriter extends \CeusMedia\Common\FS\File\Log\ShortWriter{};
class FS_File_Log_Reader extends \CeusMedia\Common\FS\File\Log\Reader{};
class FS_File_Log_Writer extends \CeusMedia\Common\FS\File\Log\Writer{};
class FS_File_Log_Tracker_ShortReader extends \CeusMedia\Common\FS\File\Log\Tracker\ShortReader{};
class FS_File_Log_Tracker_Reader extends \CeusMedia\Common\FS\File\Log\JSON\Reader{};
class FS_File_RecursiveNameFilter extends \CeusMedia\Common\FS\File\RecursiveNameFilter{};
class FS_File_PdfToImage extends \CeusMedia\Common\FS\File\PdfToImage{};
class FS_File_Lock extends \CeusMedia\Common\FS\File\Lock{};
class FS_File_StaticCache extends \CeusMedia\Common\FS\File\StaticCache{};
class FS_File_SyntaxChecker extends \CeusMedia\Common\FS\File\SyntaxChecker{};
class FS_File_YAML_Spyc extends \CeusMedia\Common\FS\File\YAML\Spyc{};
class FS_File_YAML_Reader extends \CeusMedia\Common\FS\File\YAML\Reader{};
class FS_File_YAML_Writer extends \CeusMedia\Common\FS\File\YAML\Writer{};
class FS_File_RecursiveTodoLister extends \CeusMedia\Common\FS\File\RecursiveTodoLister{};
class FS_File_Cache extends \CeusMedia\Common\FS\File\Cache{};
class FS_File_Unicoder extends \CeusMedia\Common\FS\File\Unicoder{};
class FS_Folder extends \CeusMedia\Common\FS\Folder{};
class FS_Link extends \CeusMedia\Common\FS\Link{};
class FS extends \CeusMedia\Common\FS{};

class FS_File_List_Editor extends \CeusMedia\Common\FS\File\Collection\Editor{};
class FS_File_List_Reader extends \CeusMedia\Common\FS\File\Collection\Reader{};
class FS_File_List_SectionReader extends \CeusMedia\Common\FS\File\Collection\SectionReader{};
class FS_File_List_SectionWriter extends \CeusMedia\Common\FS\File\Collection\SectionWriter{};
class FS_File_List_Writer extends \CeusMedia\Common\FS\File\Collection\Writer{};
class FS_File_JSON_Config extends \CeusMedia\Common\FS\File\JSON\Config{};
class FS_File_JSON_Reader extends \CeusMedia\Common\FS\File\JSON\Reader{};
class FS_File_JSON_Writer extends \CeusMedia\Common\FS\File\JSON\Writer{};

class FS_File_INI_Creator extends \CeusMedia\Common\FS\File\INI\Creator{};
class FS_File_INI_Editor extends \CeusMedia\Common\FS\File\INI\Editor{};
class FS_File_INI_Reader extends \CeusMedia\Common\FS\File\INI\Reader{};
class FS_File_INI_SectionEditor extends \CeusMedia\Common\FS\File\INI\SectionEditor{};
class FS_File_INI_SectionReader extends \CeusMedia\Common\FS\File\INI\SectionReader{};

class FS_File_Gantt_MeetingReader extends \CeusMedia\Common\FS\File\Gantt\MeetingReader{};
class FS_File_Gantt_MeetingCollector extends \CeusMedia\Common\FS\File\Gantt\MeetingCollector{};
class FS_File_Gantt_CalendarBuilder extends \CeusMedia\Common\FS\File\Gantt\CalendarBuilder{};
class FS_File_CSS_Compressor extends \CeusMedia\Common\FS\File\CSS\Compressor{};
class FS_File_CSS_Converter extends \CeusMedia\Common\FS\File\CSS\Converter{};
class FS_File_CSS_Reader extends \CeusMedia\Common\FS\File\CSS\Reader{};
class FS_File_CSS_Writer extends \CeusMedia\Common\FS\File\CSS\Writer{};
class FS_File_CSS_Editor extends \CeusMedia\Common\FS\File\CSS\Editor{};
class FS_File_CSS_Parser extends \CeusMedia\Common\FS\File\CSS\Parser{};
class FS_File_CSS_Combiner extends \CeusMedia\Common\FS\File\CSS\Combiner{};
class FS_File_CSS_Theme_Minimizer extends \CeusMedia\Common\FS\File\CSS\Theme\Minimizer{};
class FS_File_CSS_Theme_Combiner extends \CeusMedia\Common\FS\File\CSS\Theme\Combiner{};
class FS_File_CSS_Theme_Finder extends \CeusMedia\Common\FS\File\CSS\Theme\Finder{};
class FS_File_CSS_Relocator extends \CeusMedia\Common\FS\File\CSS\Relocator{};

class FS_Autoloader_Psr0 extends \CeusMedia\Common\FS\Autoloader\Psr0{};
class FS_Autoloader_Psr4 extends \CeusMedia\Common\FS\Autoloader\Psr4{};

class FS_File_Arc_Gzip extends \CeusMedia\Common\FS\File\Arc\Gzip{};
class FS_File_Arc_TarGzip extends \CeusMedia\Common\FS\File\Arc\TarGzip{};
class FS_File_Arc_TarBzip extends \CeusMedia\Common\FS\File\Arc\TarBzip{};
class FS_File_Arc_Bzip extends \CeusMedia\Common\FS\File\Arc\Bzip{};
class FS_File_Arc_Tar extends \CeusMedia\Common\FS\File\Arc\Tar{};
class FS_File_Arc_Zip extends \CeusMedia\Common\FS\File\Arc\Zip{};

class FS_File_Block_Reader extends \CeusMedia\Common\FS\File\Block\Reader{};
class FS_File_Block_Writer extends \CeusMedia\Common\FS\File\Block\Writer{};

class FS_File_ICal_Builder extends \CeusMedia\Common\FS\File\ICal\Builder{};
class FS_File_ICal_Parser extends \CeusMedia\Common\FS\File\ICal\Parser{};
class FS_File_Configuration_Reader extends \CeusMedia\Common\FS\File\Configuration\Reader{};
class FS_File_Configuration_Converter extends \CeusMedia\Common\FS\File\Configuration\Converter{};

abstract class UI_HTML_Abstract extends \CeusMedia\Common\UI\HTML\Abstraction{};
class UI_HTML_Buffer extends \CeusMedia\Common\UI\HTML\Buffer{};
class UI_HTML_CollapsePanel extends \CeusMedia\Common\UI\HTML\CollapsePanel{};
class UI_HTML_ContextMenu extends \CeusMedia\Common\UI\HTML\ContextMenu{};
class UI_HTML_CountryFlagIcon extends \CeusMedia\Common\UI\HTML\CountryFlagIcon{};
class UI_HTML_Elements extends \CeusMedia\Common\UI\HTML\Elements{};
class UI_HTML_EventMonthCalendar extends \CeusMedia\Common\UI\HTML\EventMonthCalendar{};
class UI_HTML_Fieldset extends \CeusMedia\Common\UI\HTML\Fieldset{};
class UI_HTML_Form extends \CeusMedia\Common\UI\HTML\Form{};
class UI_HTML_FormElements extends \CeusMedia\Common\UI\HTML\FormElements{};
class UI_HTML_Image extends \CeusMedia\Common\UI\HTML\Image{};
class UI_HTML_Index extends \CeusMedia\Common\UI\HTML\Index{};
class UI_HTML_Indicator extends \CeusMedia\Common\UI\HTML\Indicator{};
class UI_HTML_Informant extends \CeusMedia\Common\UI\HTML\Informant{};
class UI_HTML_Input extends \CeusMedia\Common\UI\HTML\Input{};
class UI_HTML_JQuery extends \CeusMedia\Common\UI\HTML\JQuery{};
class UI_HTML_Ladder extends \CeusMedia\Common\UI\HTML\Ladder{};
class UI_HTML_Label extends \CeusMedia\Common\UI\HTML\Label{};
class UI_HTML_Legend extends \CeusMedia\Common\UI\HTML\Legend{};
class UI_HTML_Link extends \CeusMedia\Common\UI\HTML\Link{};
class UI_HTML_List extends \CeusMedia\Common\UI\HTML\UnorderedList {};
class UI_HTML_ListItem extends \CeusMedia\Common\UI\HTML\ListItem{};
class UI_HTML_MonthCalendar extends \CeusMedia\Common\UI\HTML\MonthCalendar{};
class UI_HTML_Options extends \CeusMedia\Common\UI\HTML\Options{};
class UI_HTML_OrderedList extends \CeusMedia\Common\UI\HTML\OrderedList{};
class UI_HTML_PageFrame extends \CeusMedia\Common\UI\HTML\PageFrame{};
class UI_HTML_Pagination extends \CeusMedia\Common\UI\HTML\Pagination{};
class UI_HTML_Paging extends \CeusMedia\Common\UI\HTML\Paging{};
class UI_HTML_Panel extends \CeusMedia\Common\UI\HTML\Panel{};
class UI_HTML_Table extends \CeusMedia\Common\UI\HTML\Table{};
class UI_HTML_Tabs extends \CeusMedia\Common\UI\HTML\Tabs{};
class UI_HTML_Tag extends \CeusMedia\Common\UI\HTML\Tag{};
class UI_DevOutput extends \CeusMedia\Common\UI\DevOutput{};


class UI_HTML_Tree_ArrayView extends \CeusMedia\Common\UI\HTML\Tree\ArrayView{};
class UI_HTML_Tree_VariableDump extends \CeusMedia\Common\UI\HTML\Tree\VariableDump{};
class UI_HTML_Tree_FolderCheckView extends \CeusMedia\Common\UI\HTML\Tree\FolderCheckView{};
class UI_HTML_Tree_Menu extends \CeusMedia\Common\UI\HTML\Tree\Menu{};
class UI_HTML_Tree_LayerMenu extends \CeusMedia\Common\UI\HTML\Tree\LayerMenu{};
class UI_HTML_Tree_FolderView extends \CeusMedia\Common\UI\HTML\Tree\FolderView{};
class UI_HTML_AHAH_Link extends \CeusMedia\Common\UI\HTML\AHAH\Link{};
class UI_HTML_CSS_TreeMenu extends \CeusMedia\Common\UI\HTML\CSS\TreeMenu{};
class UI_HTML_CSS_LinkSelect extends \CeusMedia\Common\UI\HTML\CSS\LinkSelect{};
class UI_HTML_CSS_LanguageSwitch extends \CeusMedia\Common\UI\HTML\CSS\LanguageSwitch{};
class UI_HTML_Exception_Trace extends \CeusMedia\Common\UI\HTML\Exception\Trace{};
class UI_HTML_Exception_View extends \CeusMedia\Common\UI\HTML\Exception\View{};
class UI_HTML_Exception_TraceViewer extends \CeusMedia\Common\UI\HTML\Exception\TraceViewer{};
class UI_HTML_Exception_Page extends \CeusMedia\Common\UI\HTML\Exception\Page{};
class UI_HTML_Button_Submit extends \CeusMedia\Common\UI\HTML\Button\Submit{};
class UI_HTML_Button_Container extends \CeusMedia\Common\UI\HTML\Button\Container{};
class UI_HTML_Button_Cancel extends \CeusMedia\Common\UI\HTML\Button\Cancel{};
class UI_HTML_Button_Link extends \CeusMedia\Common\UI\HTML\Button\Link{};
class UI_HTML_Button_Abstract extends \CeusMedia\Common\UI\HTML\Button\Abstraction{};
class UI_HTML_Button_Reset extends \CeusMedia\Common\UI\HTML\Button\Reset{};

class Exception_Validation extends \CeusMedia\Common\Exception\Validation{};
class Exception_SQL extends \CeusMedia\Common\Exception\SQL{};
class Exception_Template extends \CeusMedia\Common\Exception\Template{};
class Exception_IO extends \CeusMedia\Common\Exception\IO{};
class Exception_Serializable extends \CeusMedia\Common\Exception\Serializable{};
class Exception_Runtime extends \CeusMedia\Common\Exception\Runtime{};
interface Exception_Interface extends \CeusMedia\Common\Exception\Interface_{};
class Exception_Abstract extends \CeusMedia\Common\Exception\Abstraction{};
class Exception_Logic extends \CeusMedia\Common\Exception\Logic{};

class UI_SVG_ChartData extends \CeusMedia\Common\UI\SVG\ChartData{};
class UI_SVG_PieGraph extends \CeusMedia\Common\UI\SVG\PieGraph{};
class UI_SVG_BarAcross extends \CeusMedia\Common\UI\SVG\BarAcross{};
class UI_SVG_Chart extends \CeusMedia\Common\UI\SVG\Chart{};
class UI_Image_Rotator extends \CeusMedia\Common\UI\Image\Rotator{};
class UI_Image_Captcha extends \CeusMedia\Common\UI\Image\Captcha{};
class UI_Image_Histogram extends \CeusMedia\Common\UI\Image\Histogram{};
class UI_Image_Printer extends \CeusMedia\Common\UI\Image\Printer{};
class UI_Image_Modifier extends \CeusMedia\Common\UI\Image\Modifier{};
class UI_Image_PieGraph extends \CeusMedia\Common\UI\Image\PieGraph{};
class UI_Image_Processing extends \CeusMedia\Common\UI\Image\Processing{};
class UI_Image_Drawer extends \CeusMedia\Common\UI\Image\Drawer{};
class UI_Image_Watermark extends \CeusMedia\Common\UI\Image\Watermark{};
class UI_Image_EvolutionGraph extends \CeusMedia\Common\UI\Image\EvolutionGraph{};
class UI_Image_Exif extends \CeusMedia\Common\UI\Image\Exif{};
class UI_Image_Creator extends \CeusMedia\Common\UI\Image\Creator{};
class UI_Image_Error extends \CeusMedia\Common\UI\Image\Error{};
class UI_Image_Graphviz_Renderer extends \CeusMedia\Common\UI\Image\Graphviz\Renderer{};
class UI_Image_Graphviz_Graph extends \CeusMedia\Common\UI\Image\Graphviz\Graph{};
class UI_Image_ThumbnailCreator extends \CeusMedia\Common\UI\Image\ThumbnailCreator{};
class UI_Image_Graph_LinePlot extends \CeusMedia\Common\UI\Image\Graph\LinePlot{};
class UI_Image_Graph_Builder extends \CeusMedia\Common\UI\Image\Graph\Builder{};
abstract class UI_Image_Graph_Generator extends \CeusMedia\Common\UI\Image\Graph\Generator{};
class UI_Image_Graph_Components extends \CeusMedia\Common\UI\Image\Graph\Components{};
class UI_Image_FormulaDiagram extends \CeusMedia\Common\UI\Image\FormulaDiagram{};
class UI_Image_Filter extends \CeusMedia\Common\UI\Image\Filter{};
class UI_Image_TransparentWatermark extends \CeusMedia\Common\UI\Image\TransparentWatermark{};

class UI_XML_Elements extends \CeusMedia\Common\UI\XML\Elements{};
class UI_Text extends \CeusMedia\Common\UI\Text{};
class UI_OutputBuffer extends \CeusMedia\Common\UI\OutputBuffer{};
class UI_ClassParser extends \CeusMedia\Common\UI\ClassParser{};
class UI_Template extends \CeusMedia\Common\UI\Template{};
class UI_Image extends \CeusMedia\Common\UI\Image{};

class XML_Element extends \CeusMedia\Common\XML\Element{};
class XML_Converter extends \CeusMedia\Common\XML\Converter{};
class XML_ElementReader extends \CeusMedia\Common\XML\ElementReader{};
class XML_RPC_Client extends \CeusMedia\Common\XML\RPC\Client{};
class XML_XSL_Transformator extends \CeusMedia\Common\XML\XSL\Transformator{};
class XML_DOM_GoogleSitemapWriter extends \CeusMedia\Common\XML\DOM\GoogleSitemapWriter{};
class XML_DOM_FeedIdentifier extends \CeusMedia\Common\XML\DOM\FeedIdentifier{};
class XML_DOM_Storage extends \CeusMedia\Common\XML\DOM\Storage{};
class XML_DOM_ObjectSerializer extends \CeusMedia\Common\XML\DOM\ObjectSerializer{};
class XML_DOM_GoogleSitemapBuilder extends \CeusMedia\Common\XML\DOM\GoogleSitemapBuilder{};
class XML_DOM_Builder extends \CeusMedia\Common\XML\DOM\Builder{};
class XML_DOM_ObjectDeserializer extends \CeusMedia\Common\XML\DOM\ObjectDeserializer{};
class XML_DOM_SyntaxValidator extends \CeusMedia\Common\XML\DOM\SyntaxValidator{};
class XML_DOM_ObjectFileDeserializer extends \CeusMedia\Common\XML\DOM\ObjectFileDeserializer{};
class XML_DOM_Formater extends \CeusMedia\Common\XML\DOM\Formater{};
class XML_DOM_Parser extends \CeusMedia\Common\XML\DOM\Parser{};
class XML_DOM_FileWriter extends \CeusMedia\Common\XML\DOM\FileWriter{};
class XML_DOM_PEAR_PackageReader extends \CeusMedia\Common\XML\DOM\PEAR\PackageReader{};
class XML_DOM_Node extends \CeusMedia\Common\XML\DOM\Node{};
class XML_DOM_ObjectFileSerializer extends \CeusMedia\Common\XML\DOM\ObjectFileSerializer{};
class XML_DOM_UrlReader extends \CeusMedia\Common\XML\DOM\UrlReader{};
class XML_DOM_FileEditor extends \CeusMedia\Common\XML\DOM\FileEditor{};
class XML_DOM_FileReader extends \CeusMedia\Common\XML\DOM\FileReader{};
class XML_DOM_XPathQuery extends \CeusMedia\Common\XML\DOM\XPathQuery{};
class XML_FeedIdentifier extends \CeusMedia\Common\XML\FeedIdentifier{};
class XML_UnitTestResultReader extends \CeusMedia\Common\XML\UnitTestResultReader{};
class XML_Atom_Reader extends \CeusMedia\Common\XML\Atom\Reader{};
class XML_Atom_Parser extends \CeusMedia\Common\XML\Atom\Parser{};
class XML_Atom_Validator extends \CeusMedia\Common\XML\Atom\Validator{};
class XML_Parser extends \CeusMedia\Common\XML\Parser{};
class XML_RSS_Reader extends \CeusMedia\Common\XML\RSS\Reader{};
class XML_RSS_Writer extends \CeusMedia\Common\XML\RSS\Writer{};
class XML_RSS_Builder extends \CeusMedia\Common\XML\RSS\Builder{};
class XML_RSS_Parser extends \CeusMedia\Common\XML\RSS\Parser{};
class XML_RSS_SimpleParser extends \CeusMedia\Common\XML\RSS\SimpleParser{};
class XML_RSS_GoogleBaseBuilder extends \CeusMedia\Common\XML\RSS\GoogleBaseBuilder{};
class XML_RSS_SimpleReader extends \CeusMedia\Common\XML\RSS\SimpleReader{};
class XML_OPML_Outline extends \CeusMedia\Common\XML\OPML\Outline{};
class XML_OPML_Builder extends \CeusMedia\Common\XML\OPML\Builder{};
class XML_OPML_Parser extends \CeusMedia\Common\XML\OPML\Parser{};
class XML_OPML_FileWriter extends \CeusMedia\Common\XML\OPML\FileWriter{};
class XML_OPML_FileReader extends \CeusMedia\Common\XML\OPML\FileReader{};
class XML_Validator extends \CeusMedia\Common\XML\Validator{};
class XML_Namespaces extends \CeusMedia\Common\XML\Namespaces{};


#	class ADT_ extends \CeusMedia\Common\ADT\{};

spl_autoload_register(function($className){
	if( !preg_match('/\\\\/', $className ) ){
		$classPath	= __DIR__.'/src/'.str_replace( '_', '/', $className ).'.php';
		if( file_exists( $classPath ) ){
			include_once $classPath;
			return TRUE;
		}
	}
});


if( $isComposer ){
}

if( $legacy ){
//	class Database_PDO_Connection extends DB_PDO_Connection{}
//	class Database_PDO_DataSourceName extends DB_PDO_DataSourceName{}
	class File_JSON_Reader extends FS_File_JSON_Reader{}
	class File_Editor extends FS_File_Editor{}
	class File_Reader extends FS_File_Reader{}
	class File_Writer extends FS_File_Writer{}
	class File_RecursiveRegexFilter extends FS_File_RecursiveRegexFilter{}
	class File_RegexFilter extends FS_File_RegexFilter{}
	class File_CSS_Compressor extends FS_File_CSS_Compressor{}
	class File_List_SectionReader extends FS_File_List_SectionReader{}
	class Folder_Editor extends FS_Folder_Editor{}
	class Folder_Lister extends FS_Folder_Lister{}
	class Folder_RecursiveLister extends FS_Folder_RecursiveLister{}
	class CMM_OSQL_Client{}
	class CMC_Loader extends Loader{}
	class Console_RequestReceiver extends CLI_RequestReceiver{}
	class Console_Command_ArgumentParser extends CLI_Command_ArgumentParser{}
}
