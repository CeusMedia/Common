<?php
$isComposer		= file_exists( "vendor" );
$isFromGithub	= !function_exists( "print_m" );

if( $isComposer && $isFromGithub ){
    class Database_PDO_Connection extends DB_PDO_Connection{}
    class Database_PDO_DataSourceName extends DB_PDO_DataSourceName{}
	class File_JSON_Reader extends FS_File_JSON_Reader{}
	class File_Reader extends FS_File_Reader{}
	class Folder_Lister extends FS_Folder_Lister{}
	class Folder_RecursiveLister extends FS_Folder_RecursiveLister{}
	class CMM_Bootstrap_PageControl extends \CeusMedia\Bootstrap\PageControl{}
	class File_RecursiveRegexFilter extends FS_File_RecursiveRegexFilter{}
	class CMM_OSQL_Client{}
	class CMC_Loader extends Loader{}
	new UI_DevOutput;
}

