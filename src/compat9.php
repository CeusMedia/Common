<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common{
	class CLI extends \CLI{}
	class Deprecation extends \Deprecation{}
	class Env extends \Env{}
	class FS extends \FS{}
	class Loader extends \Loader{}
	interface Renderable extends \Renderable{}}
namespace CeusMedia\Common\ADT{
	abstract class Singleton extends \ADT_Singleton{}
	class Bitmask extends \ADT_Bitmask{}
	class Collection extends \ADT_List{}
	class Constant extends \ADT_Constant{}
	class Multiplexer extends \ADT_Multiplexer{}
	class Null_ extends \ADT_Null{}
	class Object_ extends \ADT_Object{}
	class OptionObject extends \ADT_OptionObject{}
	class Registry extends \ADT_Registry{}
	class StringBuffer extends \ADT_StringBuffer{}
	class String_ extends \ADT_String{}
	class URL extends \ADT_URL{}
	class URN extends \ADT_URN{}
	class VCard extends \ADT_VCard{}}
namespace CeusMedia\Common\ADT\CSS{
	class Property extends \ADT_CSS_Property{}
	class Rule extends \ADT_CSS_Rule{}
	class Sheet extends \ADT_CSS_Sheet{}}
namespace CeusMedia\Common\ADT\Collection{
	class Dictionary extends \ADT_List_Dictionary{}
	class LevelMap extends \ADT_List_LevelMap{}
	class Queue extends \ADT_List_Queue{}
	class SectionList extends \ADT_List_SectionList{}
	class Stack extends \ADT_List_Stack{}}
namespace CeusMedia\Common\ADT\Event{
	class Callback extends \ADT_Event_Callback{}
	class Data extends \ADT_Event_Data{}
	class Handler extends \ADT_Event_Handler{}}
namespace CeusMedia\Common\ADT\Graph{
	class DirectedAcyclicWeighted extends \ADT_Graph_DirectedAcyclicWeighted{}
	class DirectedWeighted extends \ADT_Graph_DirectedWeighted{}
	class Edge extends \ADT_Graph_Edge{}
	class EdgeSet extends \ADT_Graph_EdgeSet{}
	class Node extends \ADT_Graph_Node{}
	class NodeSet extends \ADT_Graph_NodeSet{}
	class Weighted extends \ADT_Graph_Weighted{}}
namespace CeusMedia\Common\ADT\JSON{
	class Builder extends \ADT_JSON_Builder{}
	class Converter extends \ADT_JSON_Converter{}
	class Parser extends \ADT_JSON_Parser{}
	class Pretty extends \ADT_JSON_Pretty{}}
namespace CeusMedia\Common\ADT\Time{
	class Delay extends \ADT_Time_Delay{}}
namespace CeusMedia\Common\ADT\Tree{
	class MagicNode extends \ADT_Tree_MagicNode{}
	class Node extends \ADT_Tree_Node{}}
namespace CeusMedia\Common\ADT\Tree\Menu{
	class Collection extends \ADT_Tree_Menu_List{}
	class Item extends \ADT_Tree_Menu_Item{}}
namespace CeusMedia\Common\ADT\URL{
	class Compare extends \ADT_URL_Compare{}
	class Inference extends \ADT_URL_Inference{}}
namespace CeusMedia\Common\Alg{
	class ColorConverter extends \Alg_ColorConverter{}
	class HtmlMetaTagReader extends \Alg_HtmlMetaTagReader{}
	class HtmlParser extends \Alg_HtmlParser{}
	class ID extends \Alg_ID{}
	class Randomizer extends \Alg_Randomizer{}
	class SgmlTagReader extends \Alg_SgmlTagReader{}
	class UnitFormater extends \Alg_UnitFormater{}
	class UnitParser extends \Alg_UnitParser{}
	class UnusedVariableFinder extends \Alg_UnusedVariableFinder{}}
namespace CeusMedia\Common\Alg\Crypt{
	class Caesar extends \Alg_Crypt_Caesar{}
	class PasswordStrength extends \Alg_Crypt_PasswordStrength{}
	class Rot13 extends \Alg_Crypt_Rot13{}}
namespace CeusMedia\Common\Alg\JS{
	class Minifier extends \Alg_JS_Minifier{}}
namespace CeusMedia\Common\Alg\Obj{
	class Constant extends \Alg_Object_Constant{}
	class Delegation extends \Alg_Object_Delegation{}
	class EventHandler extends \Alg_Object_EventHandler{}
	class Factory extends \Alg_Object_Factory{}
	class MethodFactory extends \Alg_Object_MethodFactory{}}
namespace CeusMedia\Common\Alg\Parcel{
	class Factory extends \Alg_Parcel_Factory{}
	class Packer extends \Alg_Parcel_Packer{}
	class Packet extends \Alg_Parcel_Packet{}}
namespace CeusMedia\Common\Alg\Search{
	class Binary extends \Alg_Search_Binary{}
	class Interpolation extends \Alg_Search_Interpolation{}
	class Strange extends \Alg_Search_Strange{}}
namespace CeusMedia\Common\Alg\Sort{
	class Bubble extends \Alg_Sort_Bubble{}
	class Gnome extends \Alg_Sort_Gnome{}
	class Insertion extends \Alg_Sort_Insertion{}
	class MapList extends \Alg_Sort_MapList{}
	class Quick extends \Alg_Sort_Quick{}
	class Selection extends \Alg_Sort_Selection{}}
namespace CeusMedia\Common\Alg\Text{
	class CamelCase extends \Alg_Text_CamelCase{}
	class EncodingConverter extends \Alg_Text_EncodingConverter{}
	class Extender extends \Alg_Text_Extender{}
	class Filter extends \Alg_Text_Filter{}
	class PascalCase extends \Alg_Text_PascalCase{}
	class SnakeCase extends \Alg_Text_SnakeCase{}
	class TermExtractor extends \Alg_Text_TermExtractor{}
	class Trimmer extends \Alg_Text_Trimmer{}
	class Unicoder extends \Alg_Text_Unicoder{}}
namespace CeusMedia\Common\Alg\Time{
	class Clock extends \Alg_Time_Clock{}
	class Converter extends \Alg_Time_Converter{}
	class Duration extends \Alg_Time_Duration{}
	class DurationPhraseRanges extends \Alg_Time_DurationPhraseRanges{}
	class DurationPhraser extends \Alg_Time_DurationPhraser{}}
namespace CeusMedia\Common\Alg\Tree\Menu{
	class Converter extends \Alg_Tree_Menu_Converter{}}
namespace CeusMedia\Common\Alg\Turing{
	class Machine extends \Alg_Turing_Machine{}}
namespace CeusMedia\Common\Alg\Validation{
	class DefinitionValidator extends \Alg_Validation_DefinitionValidator{}
	class LanguageValidator extends \Alg_Validation_LanguageValidator{}
	class PredicateValidator extends \Alg_Validation_PredicateValidator{}
	class Predicates extends \Alg_Validation_Predicates{}}
namespace CeusMedia\Common\CLI{
	class Application extends \CLI_Application{}
	class ArgumentParser extends \CLI_ArgumentParser{}
	class Color extends \CLI_Color{}
	class Dimensions extends \CLI_Dimensions{}
	class Downloader extends \CLI_Downloader{}
	class Output extends \CLI_Output{}
	class Prompt extends \CLI_Prompt{}
	class Question extends \CLI_Question{}
	class RequestReceiver extends \CLI_RequestReceiver{}
	class Shell extends \CLI_Shell{}}
namespace CeusMedia\Common\CLI\Command{
	abstract class Program extends \CLI_Command_Program{}
	class ArgumentParser extends \CLI_Command_ArgumentParser{}
	class BackgroundProcess extends \CLI_Command_BackgroundProcess{}}
namespace CeusMedia\Common\CLI\Exception{
	class View extends \CLI_Exception_View{}}
namespace CeusMedia\Common\CLI\Fork{
	abstract class Abstraction extends \CLI_Fork_Abstract{}}
namespace CeusMedia\Common\CLI\Fork\Server{
	abstract class Abstraction extends \CLI_Fork_Server_Abstract{}
	class Dynamic extends \CLI_Fork_Server_Dynamic{}
	class Exception extends \CLI_Fork_Server_Exception{}
	class Reflect extends \CLI_Fork_Server_Reflection{}
	class SocketException extends \CLI_Fork_Server_SocketException{}}
namespace CeusMedia\Common\CLI\Fork\Server\Client{
	abstract class Abstraction extends \CLI_Fork_Server_Client_Abstract{}
	class WebProxy extends \CLI_Fork_Server_Client_WebProxy{}}
namespace CeusMedia\Common\CLI\Fork\Worker{
	abstract class Abstraction extends \CLI_Fork_Worker_Abstract{}}
namespace CeusMedia\Common\CLI\Output{
	class Progress extends \CLI_Output_Progress{}
	class Table extends \CLI_Output_Table{}
	class TableBorderTheme extends \CLI_Output_TableBorderTheme{}}
namespace CeusMedia\Common\CLI\Server{
	class Daemon extends \CLI_Server_Daemon{}}
namespace CeusMedia\Common\CLI\Server\Cron{
	class Daemon extends \CLI_Server_Cron_Daemon{}
	class Job extends \CLI_Server_Cron_Job{}
	class Parser extends \CLI_Server_Cron_Parser{}}
namespace CeusMedia\Common\Exception{
	class Conversion extends \Exception_Conversion{}
	class Deprecation extends \Exception_Deprecation{}
	class FileNotExisting extends \Exception_FileNotExisting{}
	class IO extends \Exception_IO{}
	class Logic extends \Exception_Logic{}
	class MissingExtension extends \Exception_MissingExtension{}
	class Runtime extends \Exception_Runtime{}
	class SQL extends \Exception_SQL{}
	class Serializable extends \Exception_Serializable{}
	class Template extends \Exception_Template{}
	class Validation extends \Exception_Validation{}}
namespace CeusMedia\Common\Exception\Data{
	class Ambiguous extends \Exception_Data_Ambiguous{}}
namespace CeusMedia\Common\Exception\Traits{
	trait Creatable{use \Exception_Traits_Creatable;}
	trait Descriptive{use \Exception_Traits_Descriptive;}
	trait Jsonable{use \Exception_Traits_Jsonable;}
	trait Serializable{use \Exception_Traits_Serializable;}}
namespace CeusMedia\Common\FS{
	class AbstractNode extends \FS_AbstractNode{}
	class File extends \FS_File{}
	class Folder extends \FS_Folder{}
	class Link extends \FS_Link{}}
namespace CeusMedia\Common\FS\Autoloader{
	class Psr0 extends \FS_Autoloader_Psr0{}
	class Psr4 extends \FS_Autoloader_Psr4{}}
namespace CeusMedia\Common\FS\File{
	class Backup extends \FS_File_Backup{}
	class BackupCleaner extends \FS_File_BackupCleaner{}
	class CodeLineCounter extends \FS_File_CodeLineCounter{}
	class Editor extends \FS_File_Editor{}
	class INI extends \FS_File_INI{}
	class Iterator extends \FS_File_Iterator{}
	class Lock extends \FS_File_Lock{}
	class NameFilter extends \FS_File_NameFilter{}
	class PdfToImage extends \FS_File_PdfToImage{}
	class Permissions extends \FS_File_Permissions{}
	class Reader extends \FS_File_Reader{}
	class RecursiveIterator extends \FS_File_RecursiveIterator{}
	class RecursiveNameFilter extends \FS_File_RecursiveNameFilter{}
	class RecursiveRegexFilter extends \FS_File_RecursiveRegexFilter{}
	class RecursiveTodoLister extends \FS_File_RecursiveTodoLister{}
	class RegexFilter extends \FS_File_RegexFilter{}
	class SyntaxChecker extends \FS_File_SyntaxChecker{}
	class TodoLister extends \FS_File_TodoLister{}
	class Unicoder extends \FS_File_Unicoder{}
	class Writer extends \FS_File_Writer{}}
namespace CeusMedia\Common\FS\File\Arc{
	class Bzip extends \FS_File_Arc_Bzip{}
	class Gzip extends \FS_File_Arc_Gzip{}
	class Tar extends \FS_File_Arc_Tar{}
	class TarBzip extends \FS_File_Arc_TarBzip{}
	class TarGzip extends \FS_File_Arc_TarGzip{}
	class Zip extends \FS_File_Arc_Zip{}}
namespace CeusMedia\Common\FS\File\Block{
	class Reader extends \FS_File_Block_Reader{}
	class Writer extends \FS_File_Block_Writer{}}
namespace CeusMedia\Common\FS\File\CSS{
	class Combiner extends \FS_File_CSS_Combiner{}
	class Compressor extends \FS_File_CSS_Compressor{}
	class Converter extends \FS_File_CSS_Converter{}
	class Editor extends \FS_File_CSS_Editor{}
	class Parser extends \FS_File_CSS_Parser{}
	class Reader extends \FS_File_CSS_Reader{}
	class Relocator extends \FS_File_CSS_Relocator{}
	class Writer extends \FS_File_CSS_Writer{}}
namespace CeusMedia\Common\FS\File\CSS\Theme{
	class Combiner extends \FS_File_CSS_Theme_Combiner{}
	class Finder extends \FS_File_CSS_Theme_Finder{}
	class Minimizer extends \FS_File_CSS_Theme_Minimizer{}}
namespace CeusMedia\Common\FS\File\CSV{
	class Iterator extends \FS_File_CSV_Iterator{}
	class Reader extends \FS_File_CSV_Reader{}
	class Writer extends \FS_File_CSV_Writer{}}
namespace CeusMedia\Common\FS\File\Collection{
	class Editor extends \FS_File_List_Editor{}
	class Reader extends \FS_File_List_Reader{}
	class SectionReader extends \FS_File_List_SectionReader{}
	class SectionWriter extends \FS_File_List_SectionWriter{}
	class Writer extends \FS_File_List_Writer{}}
namespace CeusMedia\Common\FS\File\Configuration{
	class Converter extends \FS_File_Configuration_Converter{}
	class Reader extends \FS_File_Configuration_Reader{}}
namespace CeusMedia\Common\FS\File\Gantt{
	class CalendarBuilder extends \FS_File_Gantt_CalendarBuilder{}
	class MeetingCollector extends \FS_File_Gantt_MeetingCollector{}
	class MeetingReader extends \FS_File_Gantt_MeetingReader{}}
namespace CeusMedia\Common\FS\File\ICal{
	class Builder extends \FS_File_ICal_Builder{}
	class Parser extends \FS_File_ICal_Parser{}}
namespace CeusMedia\Common\FS\File\INI{
	class Creator extends \FS_File_INI_Creator{}
	class Editor extends \FS_File_INI_Editor{}
	class Reader extends \FS_File_INI_Reader{}
	class SectionEditor extends \FS_File_INI_SectionEditor{}
	class SectionReader extends \FS_File_INI_SectionReader{}}
namespace CeusMedia\Common\FS\File\JSON{
	class Config extends \FS_File_JSON_Config{}
	class Reader extends \FS_File_JSON_Reader{}
	class Writer extends \FS_File_JSON_Writer{}}
namespace CeusMedia\Common\FS\File\Log{
	class File extends \FS_File_Log_File{}
	class Reader extends \FS_File_Log_Reader{}
	class ShortReader extends \FS_File_Log_ShortReader{}
	class ShortWriter extends \FS_File_Log_ShortWriter{}
	class Writer extends \FS_File_Log_Writer{}}
namespace CeusMedia\Common\FS\File\Log\JSON{
	class Reader extends \FS_File_Log_JSON_Reader{}
	class Writer extends \FS_File_Log_JSON_Writer{}}
namespace CeusMedia\Common\FS\File\Log\Tracker{
	class Reader extends \FS_File_Log_Tracker_Reader{}
	class ShortReader extends \FS_File_Log_Tracker_ShortReader{}}
namespace CeusMedia\Common\FS\File\PHP{
	class Encoder extends \FS_File_PHP_Encoder{}
	class Lister extends \FS_File_PHP_Lister{}}
namespace CeusMedia\Common\FS\File\PHP\Check{
	class MethodOrder extends \FS_File_PHP_Check_MethodOrder{}
	class MethodVisibility extends \FS_File_PHP_Check_MethodVisibility{}}
namespace CeusMedia\Common\FS\File\PHP\Test{
	class Creator extends \FS_File_PHP_Test_Creator{}}
namespace CeusMedia\Common\FS\File\VCard{
	class Builder extends \FS_File_VCard_Builder{}
	class Parser extends \FS_File_VCard_Parser{}
	class Reader extends \FS_File_VCard_Reader{}
	class Writer extends \FS_File_VCard_Writer{}}
namespace CeusMedia\Common\FS\File\YAML{
	class Reader extends \FS_File_YAML_Reader{}
	class Spyc extends \FS_File_YAML_Spyc{}
	class Writer extends \FS_File_YAML_Writer{}}
namespace CeusMedia\Common\FS\Folder{
	class CodeLineCounter extends \FS_Folder_CodeLineCounter{}
	class Editor extends \FS_Folder_Editor{}
	class Iterator extends \FS_Folder_Iterator{}
	class Lister extends \FS_Folder_Lister{}
	class MethodSortCheck extends \FS_Folder_MethodSortCheck{}
	class MethodVisibilityCheck extends \FS_Folder_MethodVisibilityCheck{}
	class Reader extends \FS_Folder_Reader{}
	class RecursiveIterator extends \FS_Folder_RecursiveIterator{}
	class RecursiveLister extends \FS_Folder_RecursiveLister{}
	class RecursiveRegexFilter extends \FS_Folder_RecursiveRegexFilter{}
	class RegexFilter extends \FS_Folder_RegexFilter{}
	class SyntaxChecker extends \FS_Folder_SyntaxChecker{}}
namespace CeusMedia\Common\FS\Folder\Treeview{
	class Json extends \FS_Folder_Treeview_Json{}
	class JsonExtended extends \FS_Folder_Treeview_JsonExtended{}}
namespace CeusMedia\Common\Net{
	class AtomServerTime extends \Net_AtomServerTime{}
	class AtomTime extends \Net_AtomTime{}
	class CURL extends \Net_CURL{}
	class Connectivity extends \Net_Connectivity{}
	class Reader extends \Net_Reader{}}
namespace CeusMedia\Common\Net\API{
	class DDNSS extends \Net_API_DDNSS{}
	class Dyn extends \Net_API_Dyn{}
	class Gravatar extends \Net_API_Gravatar{}
	class Premailer extends \Net_API_Premailer{}}
namespace CeusMedia\Common\Net\API\Google{
	class ClosureCompiler extends \Net_API_Google_ClosureCompiler{}
	class Request extends \Net_API_Google_Request{}}
namespace CeusMedia\Common\Net\API\Google\Maps{
	class Geocoder extends \Net_API_Google_Maps_Geocoder{}}
namespace CeusMedia\Common\Net\API\Google\Sitemap{
	class Submit extends \Net_API_Google_Sitemap_Submit{}}
namespace CeusMedia\Common\Net\FTP{
	class Client extends \Net_FTP_Client{}
	class Connection extends \Net_FTP_Connection{}
	class Reader extends \Net_FTP_Reader{}
	class Writer extends \Net_FTP_Writer{}}
namespace CeusMedia\Common\Net\HTTP{
	class Cookie extends \Net_HTTP_Cookie{}
	class CrossDomainProxy extends \Net_HTTP_CrossDomainProxy{}
	class Download extends \Net_HTTP_Download{}
	class Method extends \Net_HTTP_Method{}
	class PartitionCookie extends \Net_HTTP_PartitionCookie{}
	class PartitionSession extends \Net_HTTP_PartitionSession{}
	class Post extends \Net_HTTP_Post{}
	class Reader extends \Net_HTTP_Reader{}
	class Request extends \Net_HTTP_Request{}
	class Response extends \Net_HTTP_Response{}
	class Session extends \Net_HTTP_Session{}
	class Status extends \Net_HTTP_Status{}
	class UploadErrorHandler extends \Net_HTTP_UploadErrorHandler{}}
namespace CeusMedia\Common\Net\HTTP\Header{
	class Field extends \Net_HTTP_Header_Field{}
	class Parser extends \Net_HTTP_Header_Parser{}
	class Renderer extends \Net_HTTP_Header_Renderer{}
	class Section extends \Net_HTTP_Header_Section{}}
namespace CeusMedia\Common\Net\HTTP\Header\Field{
	class Parser extends \Net_HTTP_Header_Field_Parser{}}
namespace CeusMedia\Common\Net\HTTP\Request{
	class QueryParser extends \Net_HTTP_Request_QueryParser{}
	class Receiver extends \Net_HTTP_Request_Receiver{}}
namespace CeusMedia\Common\Net\HTTP\Response{
	class Compressor extends \Net_HTTP_Response_Compressor{}
	class Decompressor extends \Net_HTTP_Response_Decompressor{}
	class Parser extends \Net_HTTP_Response_Parser{}
	class Sender extends \Net_HTTP_Response_Sender{}}
namespace CeusMedia\Common\Net\HTTP\Sniffer{
	class Charset extends \Net_HTTP_Sniffer_Charset{}
	class Client extends \Net_HTTP_Sniffer_Client{}
	class Encoding extends \Net_HTTP_Sniffer_Encoding{}
	class Language extends \Net_HTTP_Sniffer_Language{}
	class MimeType extends \Net_HTTP_Sniffer_MimeType{}}
namespace CeusMedia\Common\Net\SVN{
	class Client extends \Net_SVN_Client{}}
namespace CeusMedia\Common\Net\Site{
	class Crawler extends \Net_Site_Crawler{}
	class MapBuilder extends \Net_Site_MapBuilder{}
	class MapCreator extends \Net_Site_MapCreator{}
	class MapWriter extends \Net_Site_MapWriter{}}
namespace CeusMedia\Common\Net\XMPP{
	class JID extends \Net_XMPP_JID{}
	class MessageSender extends \Net_XMPP_MessageSender{}}
namespace CeusMedia\Common\Net\XMPP\XMPPHP{
	class BOSH extends \Net_XMPP_XMPPHP_BOSH{}
	class Exception extends \Net_XMPP_XMPPHP_Exception{}
	class Log extends \Net_XMPP_XMPPHP_Log{}
	class Roster extends \Net_XMPP_XMPPHP_Roster{}
	class XMLObj extends \Net_XMPP_XMPPHP_XMLObj{}
	class XMLStream extends \Net_XMPP_XMPPHP_XMLStream{}
	class XMPP extends \Net_XMPP_XMPPHP_XMPP{}}
namespace CeusMedia\Common\UI{
	class DevOutput extends \UI_DevOutput{}
	class Image extends \UI_Image{}
	class OutputBuffer extends \UI_OutputBuffer{}
	class Template extends \UI_Template{}
	class Text extends \UI_Text{}
	class VariableDumper extends \UI_VariableDumper{}}
namespace CeusMedia\Common\UI\HTML{
	class CollapsePanel extends \UI_HTML_CollapsePanel{}
	class ContextMenu extends \UI_HTML_ContextMenu{}
	class Elements extends \UI_HTML_Elements{}
	class FormElements extends \UI_HTML_FormElements{}
	class Index extends \UI_HTML_Index{}
	class Indicator extends \UI_HTML_Indicator{}
	class JQuery extends \UI_HTML_JQuery{}
	class Ladder extends \UI_HTML_Ladder{}
	class Options extends \UI_HTML_Options{}
	class PageFrame extends \UI_HTML_PageFrame{}
	class Pagination extends \UI_HTML_Pagination{}
	class Paging extends \UI_HTML_Paging{}
	class Panel extends \UI_HTML_Panel{}
	class Table extends \UI_HTML_Table{}
	class Tabs extends \UI_HTML_Tabs{}
	class Tag extends \UI_HTML_Tag{}}
namespace CeusMedia\Common\UI\HTML\Exception{
	class Page extends \UI_HTML_Exception_Page{}
	class Trace extends \UI_HTML_Exception_Trace{}
	class TraceViewer extends \UI_HTML_Exception_TraceViewer{}
	class View extends \UI_HTML_Exception_View{}}
namespace CeusMedia\Common\UI\HTML\Tree{
	class ArrayView extends \UI_HTML_Tree_ArrayView{}
	class FolderCheckView extends \UI_HTML_Tree_FolderCheckView{}
	class FolderView extends \UI_HTML_Tree_FolderView{}
	class LayerMenu extends \UI_HTML_Tree_LayerMenu{}
	class Menu extends \UI_HTML_Tree_Menu{}
	class VariableDump extends \UI_HTML_Tree_VariableDump{}}
namespace CeusMedia\Common\UI\Image{
	class Captcha extends \UI_Image_Captcha{}
	class Creator extends \UI_Image_Creator{}
	class Drawer extends \UI_Image_Drawer{}
	class Error extends \UI_Image_Error{}
	class Exif extends \UI_Image_Exif{}
	class Filter extends \UI_Image_Filter{}
	class Histogram extends \UI_Image_Histogram{}
	class Modifier extends \UI_Image_Modifier{}
	class Printer extends \UI_Image_Printer{}
	class Processing extends \UI_Image_Processing{}
	class Rotator extends \UI_Image_Rotator{}
	class ThumbnailCreator extends \UI_Image_ThumbnailCreator{}
	class TransparentWatermark extends \UI_Image_TransparentWatermark{}
	class Watermark extends \UI_Image_Watermark{}}
namespace CeusMedia\Common\UI\Image\Graphviz{
	class Graph extends \UI_Image_Graphviz_Graph{}
	class Renderer extends \UI_Image_Graphviz_Renderer{}}
namespace CeusMedia\Common\UI\JS{
	class CodeMirror extends \UI_JS_CodeMirror{}}
namespace CeusMedia\Common\UI\SVG{
	class BarAcross extends \UI_SVG_BarAcross{}
	class Chart extends \UI_SVG_Chart{}
	class ChartData extends \UI_SVG_ChartData{}
	class PieGraph extends \UI_SVG_PieGraph{}}
namespace CeusMedia\Common\XML{
	class Converter extends \XML_Converter{}
	class Element extends \XML_Element{}
	class ElementReader extends \XML_ElementReader{}
	class FeedIdentifier extends \XML_FeedIdentifier{}
	class Namespaces extends \XML_Namespaces{}
	class Parser extends \XML_Parser{}
	class UnitTestResultReader extends \XML_UnitTestResultReader{}
	class Validator extends \XML_Validator{}}
namespace CeusMedia\Common\XML\Atom{
	class Parser extends \XML_Atom_Parser{}
	class Reader extends \XML_Atom_Reader{}
	class Validator extends \XML_Atom_Validator{}}
namespace CeusMedia\Common\XML\DOM{
	class Builder extends \XML_DOM_Builder{}
	class FeedIdentifier extends \XML_DOM_FeedIdentifier{}
	class FileEditor extends \XML_DOM_FileEditor{}
	class FileReader extends \XML_DOM_FileReader{}
	class FileWriter extends \XML_DOM_FileWriter{}
	class Formater extends \XML_DOM_Formater{}
	class GoogleSitemapBuilder extends \XML_DOM_GoogleSitemapBuilder{}
	class GoogleSitemapWriter extends \XML_DOM_GoogleSitemapWriter{}
	class Node extends \XML_DOM_Node{}
	class ObjectDeserializer extends \XML_DOM_ObjectDeserializer{}
	class ObjectFileDeserializer extends \XML_DOM_ObjectFileDeserializer{}
	class ObjectFileSerializer extends \XML_DOM_ObjectFileSerializer{}
	class ObjectSerializer extends \XML_DOM_ObjectSerializer{}
	class Parser extends \XML_DOM_Parser{}
	class Storage extends \XML_DOM_Storage{}
	class SyntaxValidator extends \XML_DOM_SyntaxValidator{}
	class UrlReader extends \XML_DOM_UrlReader{}
	class XPathQuery extends \XML_DOM_XPathQuery{}}
namespace CeusMedia\Common\XML\DOM\PEAR{
	class PackageReader extends \XML_DOM_PEAR_PackageReader{}}
namespace CeusMedia\Common\XML\OPML{
	class Builder extends \XML_OPML_Builder{}
	class FileReader extends \XML_OPML_FileReader{}
	class FileWriter extends \XML_OPML_FileWriter{}
	class Outline extends \XML_OPML_Outline{}
	class Parser extends \XML_OPML_Parser{}}
namespace CeusMedia\Common\XML\RPC{
	class Client extends \XML_RPC_Client{}}
namespace CeusMedia\Common\XML\RSS{
	class Builder extends \XML_RSS_Builder{}
	class GoogleBaseBuilder extends \XML_RSS_GoogleBaseBuilder{}
	class Parser extends \XML_RSS_Parser{}
	class Reader extends \XML_RSS_Reader{}
	class SimpleParser extends \XML_RSS_SimpleParser{}
	class SimpleReader extends \XML_RSS_SimpleReader{}
	class Writer extends \XML_RSS_Writer{}}
namespace CeusMedia\Common\XML\XSL{
	class Transformator extends \XML_XSL_Transformator{}}
