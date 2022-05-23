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
class ADT_URL_Compare extends \CeusMedia\Common\ADT\URL\Compare{};
class ADT_URL_Inference extends \CeusMedia\Common\ADT\URL\Inference{};
class ADT_Bitmask extends \CeusMedia\Common\ADT\Bitmask{};
class ADT_Constant extends \CeusMedia\Common\ADT\Constant{};
class ADT_List extends \CeusMedia\Common\ADT\Collection{};
class ADT_Multiplexer extends \CeusMedia\Common\ADT\Multiplexer{};
class ADT_Null extends \CeusMedia\Common\ADT\Null_{};
class ADT_Object extends \CeusMedia\Common\ADT\Object_{};
class ADT_Registry extends \CeusMedia\Common\ADT\Registry{};
class ADT_Singleton extends \CeusMedia\Common\ADT\Singleton{};
class ADT_String extends \CeusMedia\Common\ADT\String_{};
class ADT_StringBuffer extends \CeusMedia\Common\ADT\StringBuffer{};
class ADT_URL extends \CeusMedia\Common\ADT\URL{};
class ADT_URN extends \CeusMedia\Common\ADT\URN{};
class ADT_VCard extends \CeusMedia\Common\ADT\VCard{};

class Alg_Text_CamelCase extends \CeusMedia\Common\Alg\Text\CamelCase{};
class Alg_Sort_Bubble extends \CeusMedia\Common\Alg\Sort\Bubble{};
class Alg_Sort_Gnome extends \CeusMedia\Common\Alg\Sort\Gnome{};
class Alg_Sort_Insertion extends \CeusMedia\Common\Alg\Sort\Insertion{};
class Alg_Sort_MapList extends \CeusMedia\Common\Alg\Sort\MapList{};
class Alg_Sort_Quick extends \CeusMedia\Common\Alg\Sort\Quick{};
class Alg_Sort_Selection extends \CeusMedia\Common\Alg\Sort\Selection{};
class Alg_UnitFormater extends \CeusMedia\Common\Alg\UnitFormater{};




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

class UI_DevOutput extends \CeusMedia\Common\UI\DevOutput{};


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
