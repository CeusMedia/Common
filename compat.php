<?php
$isComposer		= file_exists( "vendor" );
$isFromGithub	= !function_exists( "print_m" );

if( $isComposer && $isFromGithub ){
    class Database_PDO_Connection extends DB_PDO_Connection{}
    class Database_PDO_DataSourceName extends DB_PDO_DataSourceName{}
	class File_JSON_Reader extends FS_File_JSON_Reader{}
	class File_Editor extends FS_File_Editor{}
	class File_Reader extends FS_File_Reader{}
	class File_Writer extends FS_File_Writer{}
	class File_RecursiveRegexFilter extends FS_File_RecursiveRegexFilter{}
	class File_RegexFilter extends FS_File_RegexFilter{}
	class File_CSS_Compressor extends FS_File_CSS_Compressor{}
	class Folder_Editor extends FS_Folder_Editor{}
	class Folder_Lister extends FS_Folder_Lister{}
	class Folder_RecursiveLister extends FS_Folder_RecursiveLister{}
	class CMM_Bootstrap_PageControl extends \CeusMedia\Bootstrap\PageControl{}
	class CMM_Mail_Parser extends \CeusMedia\Mail\Parser{}
	class CMM_OSQL_Client{}
	class CMC_Loader extends Loader{}
	class Console_RequestReceiver extends CLI_RequestReceiver{}
	class Console_Command_ArgumentParser extends CLI_Command_ArgumentParser{}
	new UI_DevOutput;
}
