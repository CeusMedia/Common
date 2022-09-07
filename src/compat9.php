<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
/** @noinspection PhpIllegalPsrClassPathInspection */

namespace CeusMedia\Common{
	class Loader extends \Loader{}
	class FS extends \FS{}
	class Deprecation extends \Deprecation{}
	class CLI extends \CLI{}
	interface Renderable extends \Renderable{}}
namespace CeusMedia\Common\ADT{
	class URN extends \ADT_URN{}
	class Constant extends \ADT_Constant{}
	class Multiplexer extends \ADT_Multiplexer{}
	class Object_ extends \ADT_Object{}
	class String_ extends \ADT_String{}
	abstract class Singleton extends \ADT_Singleton{}
	class URL extends \ADT_URL{}
	class StringBuffer extends \ADT_StringBuffer{}
	class Registry extends \ADT_Registry{}
	class Bitmask extends \ADT_Bitmask{}
	class Collection extends \ADT_List{}
	class OptionObject extends \ADT_OptionObject{}
	class Null_ extends \ADT_Null{}
	class VCard extends \ADT_VCard{}}
namespace CeusMedia\Common\ADT\CSS{
	class Rule extends \ADT_CSS_Rule{}
	class Property extends \ADT_CSS_Property{}
	class Sheet extends \ADT_CSS_Sheet{}}
namespace CeusMedia\Common\ADT\Cache{
	abstract class Store extends \ADT_Cache_Store{}
	abstract class StaticStore extends \ADT_Cache_StaticStore{}}
namespace CeusMedia\Common\ADT\Collection{
	class Stack extends \ADT_List_Stack{}
	class SectionList extends \ADT_List_SectionList{}
	class Dictionary extends \ADT_List_Dictionary{}
	class LevelMap extends \ADT_List_LevelMap{}
	class Queue extends \ADT_List_Queue{}}
namespace CeusMedia\Common\ADT\Event{
	class Data extends \ADT_Event_Data{}
	class Callback extends \ADT_Event_Callback{}
	class Handler extends \ADT_Event_Handler{}}
namespace CeusMedia\Common\ADT\Graph{
	class NodeSet extends \ADT_Graph_NodeSet{}
	class Weighted extends \ADT_Graph_Weighted{}
	class Node extends \ADT_Graph_Node{}
	class Edge extends \ADT_Graph_Edge{}
	class DirectedWeighted extends \ADT_Graph_DirectedWeighted{}
	class EdgeSet extends \ADT_Graph_EdgeSet{}
	class DirectedAcyclicWeighted extends \ADT_Graph_DirectedAcyclicWeighted{}}
namespace CeusMedia\Common\ADT\JSON{
	class Converter extends \ADT_JSON_Converter{}
	class Builder extends \ADT_JSON_Builder{}
	class Pretty extends \ADT_JSON_Formater{}
	class Parser extends \ADT_JSON_Parser{}}
namespace CeusMedia\Common\ADT\Time{
	class Delay extends \ADT_Time_Delay{}}
namespace CeusMedia\Common\ADT\Tree{
	class BinaryNode extends \ADT_Tree_BinaryNode{}
	class BalanceBinaryNode extends \ADT_Tree_BalanceBinaryNode{}
	class AvlNode extends \ADT_Tree_AvlNode{}
	class Node extends \ADT_Tree_Node{}
	class MagicNode extends \ADT_Tree_MagicNode{}}
namespace CeusMedia\Common\ADT\Tree\Menu{
	class Item extends \ADT_Tree_Menu_Item{}
	class Collection extends \ADT_Tree_Menu_List{}}
namespace CeusMedia\Common\ADT\URL{
	class Compare extends \ADT_URL_Compare{}
	class Inference extends \ADT_URL_Inference{}}
namespace CeusMedia\Common\Alg{
	class UnitParser extends \Alg_UnitParser{}
	class SgmlTagReader extends \Alg_SgmlTagReader{}
	class Randomizer extends \Alg_Randomizer{}
	class UnitFormater extends \Alg_UnitFormater{}
	class ColorConverter extends \Alg_ColorConverter{}
	class HtmlParser extends \Alg_HtmlParser{}
	class HtmlMetaTagReader extends \Alg_HtmlMetaTagReader{}
	class ID extends \Alg_ID{}
	class UnusedVariableFinder extends \Alg_UnusedVariableFinder{}}
namespace CeusMedia\Common\Alg\Crypt{
	class Rot13 extends \Alg_Crypt_Rot13{}
	class Caesar extends \Alg_Crypt_Caesar{}
	class PasswordStrength extends \Alg_Crypt_PasswordStrength{}}
namespace CeusMedia\Common\Alg\JS{
	class Minifier extends \Alg_JS_Minifier{}}
namespace CeusMedia\Common\Alg\Obj{
	class Constant extends \Alg_Object_Constant{}
	class MethodFactory extends \Alg_Object_MethodFactory{}
	class Factory extends \Alg_Object_Factory{}
	class Delegation extends \Alg_Object_Delegation{}
	class EventHandler extends \Alg_Object_EventHandler{}}
namespace CeusMedia\Common\Alg\Parcel{
	class Factory extends \Alg_Parcel_Factory{}
	class Packer extends \Alg_Parcel_Packer{}
	class Packet extends \Alg_Parcel_Packet{}}
namespace CeusMedia\Common\Alg\Search{
	class Binary extends \Alg_Search_Binary{}
	class Interpolation extends \Alg_Search_Interpolation{}
	class Strange extends \Alg_Search_Strange{}}
namespace CeusMedia\Common\Alg\Sort{
	class Selection extends \Alg_Sort_Selection{}
	class Insertion extends \Alg_Sort_Insertion{}
	class Gnome extends \Alg_Sort_Gnome{}
	class Quick extends \Alg_Sort_Quick{}
	class MapList extends \Alg_Sort_MapList{}
	class Bubble extends \Alg_Sort_Bubble{}}
namespace CeusMedia\Common\Alg\Text{
	class SnakeCase extends \Alg_Text_SnakeCase{}
	class CamelCase extends \Alg_Text_CamelCase{}
	class Extender extends \Alg_Text_Extender{}
	class EncodingConverter extends \Alg_Text_EncodingConverter{}
	class Trimmer extends \Alg_Text_Trimmer{}
	class PascalCase extends \Alg_Text_PascalCase{}
	class TermExtractor extends \Alg_Text_TermExtractor{}
	class Filter extends \Alg_Text_Filter{}
	class Unicoder extends \Alg_Text_Unicoder{}}
namespace CeusMedia\Common\Alg\Time{
	class Converter extends \Alg_Time_Converter{}
	class DurationPhraser extends \Alg_Time_DurationPhraser{}
	class Clock extends \Alg_Time_Clock{}
	class Duration extends \Alg_Time_Duration{}
	class DurationPhraseRanges extends \Alg_Time_DurationPhraseRanges{}}
namespace CeusMedia\Common\Alg\Tree\Menu{
	class Converter extends \Alg_Tree_Menu_Converter{}}
namespace CeusMedia\Common\Alg\Turing{
	class Machine extends \Alg_Turing_Machine{}}
namespace CeusMedia\Common\Alg\Validation{
	class PredicateValidator extends \Alg_Validation_PredicateValidator{}
	class LanguageValidator extends \Alg_Validation_LanguageValidator{}
	class DefinitionValidator extends \Alg_Validation_DefinitionValidator{}
	class Predicates extends \Alg_Validation_Predicates{}}
namespace CeusMedia\Common\CLI{
	class Downloader extends \CLI_Downloader{}
	class Question extends \CLI_Question{}
	class Application extends \CLI_Application{}
	class ArgumentParser extends \CLI_ArgumentParser{}
	class Prompt extends \CLI_Prompt{}
	class Dimensions extends \CLI_Dimensions{}
	class Shell extends \CLI_Shell{}
	class Color extends \CLI_Color{}
	class Output extends \CLI_Output{}
	class RequestReceiver extends \CLI_RequestReceiver{}}
namespace CeusMedia\Common\CLI\Command{
	class BackgroundProcess extends \CLI_Command_BackgroundProcess{}
	abstract class Program extends \CLI_Command_Program{}
	class ArgumentParser extends \CLI_Command_ArgumentParser{}}
namespace CeusMedia\Common\CLI\Exception{
	class View extends \CLI_Exception_View{}}
namespace CeusMedia\Common\CLI\Fork{
	abstract class Abstraction extends \CLI_Fork_Abstract{}}
namespace CeusMedia\Common\CLI\Fork\Server{
	class SocketException extends \CLI_Fork_Server_SocketException{}
	class Exception extends \CLI_Fork_Server_Exception{}
	class Reflect extends \CLI_Fork_Server_Reflection{}
	abstract class Abstraction extends \CLI_Fork_Server_Abstract{}
	class Dynamic extends \CLI_Fork_Server_Dynamic{}}
namespace CeusMedia\Common\CLI\Fork\Server\Client{
	class WebProxy extends \CLI_Fork_Server_Client_WebProxy{}
	abstract class Abstraction extends \CLI_Fork_Server_Client_Abstract{}}
namespace CeusMedia\Common\CLI\Fork\Worker{
	abstract class Abstraction extends \CLI_Fork_Worker_Abstract{}}
namespace CeusMedia\Common\CLI\Output{
	class Progress extends \CLI_Output_Progress{}
	class Table extends \CLI_Output_Table{}}
namespace CeusMedia\Common\CLI\Server{
	class Daemon extends \CLI_Server_Daemon{}}
namespace CeusMedia\Common\CLI\Server\Cron{
	class Parser extends \CLI_Server_Cron_Parser{}
	class Daemon extends \CLI_Server_Cron_Daemon{}
	class Job extends \CLI_Server_Cron_Job{}}
namespace CeusMedia\Common\Exception{
	class Validation extends \Exception_Validation{}
	class SQL extends \Exception_SQL{}
	class Template extends \Exception_Template{}
	class IO extends \Exception_IO{}
	class Serializable extends \Exception_Serializable{}
	class Runtime extends \Exception_Runtime{}
	interface Interface_ extends \Exception_Interface{}
	abstract class Abstraction extends \Exception_Abstract{}
	class Logic extends \Exception_Logic{}}
namespace CeusMedia\Common\FS{
	class File extends \FS_File{}
	class AbstractNode extends \FS_AbstractNode{}
	class Folder extends \FS_Folder{}
	class Link extends \FS_Link{}}
namespace CeusMedia\Common\FS\Autoloader{
	class Psr0 extends \FS_Autoloader_Psr0{}
	class Psr4 extends \FS_Autoloader_Psr4{}}
namespace CeusMedia\Common\FS\File{
	class Backup extends \FS_File_Backup{}
	class Permissions extends \FS_File_Permissions{}
	class INI extends \FS_File_INI{}
	class BackupCleaner extends \FS_File_BackupCleaner{}
	class RegexFilter extends \FS_File_RegexFilter{}
	class NameFilter extends \FS_File_NameFilter{}
	class CodeLineCounter extends \FS_File_CodeLineCounter{}
	class TodoLister extends \FS_File_TodoLister{}
	class Iterator extends \FS_File_Iterator{}
	class Reader extends \FS_File_Reader{}
	class Writer extends \FS_File_Writer{}
	class Editor extends \FS_File_Editor{}
	class RecursiveIterator extends \FS_File_RecursiveIterator{}
	class RecursiveRegexFilter extends \FS_File_RecursiveRegexFilter{}
	class RecursiveNameFilter extends \FS_File_RecursiveNameFilter{}
	class PdfToImage extends \FS_File_PdfToImage{}
	class Lock extends \FS_File_Lock{}
	class StaticCache extends \FS_File_StaticCache{}
	class SyntaxChecker extends \FS_File_SyntaxChecker{}
	class RecursiveTodoLister extends \FS_File_RecursiveTodoLister{}
	class Cache extends \FS_File_Cache{}
	class Unicoder extends \FS_File_Unicoder{}}
namespace CeusMedia\Common\FS\File\Arc{
	class Gzip extends \FS_File_Arc_Gzip{}
	class TarGzip extends \FS_File_Arc_TarGzip{}
	class Zip extends \FS_File_Arc_Zip{}
	class TarBzip extends \FS_File_Arc_TarBzip{}
	class Bzip extends \FS_File_Arc_Bzip{}
	class Tar extends \FS_File_Arc_Tar{}}
namespace CeusMedia\Common\FS\File\Block{
	class Reader extends \FS_File_Block_Reader{}
	class Writer extends \FS_File_Block_Writer{}}
namespace CeusMedia\Common\FS\File\CSS{
	class Converter extends \FS_File_CSS_Converter{}
	class Compressor extends \FS_File_CSS_Compressor{}
	class Reader extends \FS_File_CSS_Reader{}
	class Writer extends \FS_File_CSS_Writer{}
	class Editor extends \FS_File_CSS_Editor{}
	class Parser extends \FS_File_CSS_Parser{}
	class Combiner extends \FS_File_CSS_Combiner{}
	class Relocator extends \FS_File_CSS_Relocator{}}
namespace CeusMedia\Common\FS\File\CSS\Theme{
	class Minimizer extends \FS_File_CSS_Theme_Minimizer{}
	class Combiner extends \FS_File_CSS_Theme_Combiner{}
	class Finder extends \FS_File_CSS_Theme_Finder{}}
namespace CeusMedia\Common\FS\File\CSV{
	class Iterator extends \FS_File_CSV_Iterator{}
	class Reader extends \FS_File_CSV_Reader{}
	class Writer extends \FS_File_CSV_Writer{}}
namespace CeusMedia\Common\FS\File\Collection{
	class Reader extends \FS_File_List_Reader{}
	class Writer extends \FS_File_List_Writer{}
	class Editor extends \FS_File_List_Editor{}
	class SectionReader extends \FS_File_List_SectionReader{}
	class SectionWriter extends \FS_File_List_SectionWriter{}}
namespace CeusMedia\Common\FS\File\Configuration{
	class Converter extends \FS_File_Configuration_Converter{}
	class Reader extends \FS_File_Configuration_Reader{}}
namespace CeusMedia\Common\FS\File\Gantt{
	class MeetingReader extends \FS_File_Gantt_MeetingReader{}
	class MeetingCollector extends \FS_File_Gantt_MeetingCollector{}
	class CalendarBuilder extends \FS_File_Gantt_CalendarBuilder{}}
namespace CeusMedia\Common\FS\File\ICal{
	class Builder extends \FS_File_ICal_Builder{}
	class Parser extends \FS_File_ICal_Parser{}}
namespace CeusMedia\Common\FS\File\INI{
	class Reader extends \FS_File_INI_Reader{}
	class Editor extends \FS_File_INI_Editor{}
	class SectionReader extends \FS_File_INI_SectionReader{}
	class SectionEditor extends \FS_File_INI_SectionEditor{}
	class Creator extends \FS_File_INI_Creator{}}
namespace CeusMedia\Common\FS\File\JSON{
	class Config extends \FS_File_JSON_Config{}
	class Reader extends \FS_File_JSON_Reader{}
	class Writer extends \FS_File_JSON_Writer{}}
namespace CeusMedia\Common\FS\File\Log{
	class File extends \FS_File_Log_File{}
	class ShortReader extends \FS_File_Log_ShortReader{}
	class ShortWriter extends \FS_File_Log_ShortWriter{}
	class Reader extends \FS_File_Log_Reader{}
	class Writer extends \FS_File_Log_Writer{}}
namespace CeusMedia\Common\FS\File\Log\JSON{
	class Reader extends \FS_File_Log_JSON_Reader{}
	class Writer extends \FS_File_Log_JSON_Writer{}}
namespace CeusMedia\Common\FS\File\Log\Tracker{
	class ShortReader extends \FS_File_Log_Tracker_ShortReader{}
	class Reader extends \FS_File_Log_Tracker_Reader{}}
namespace CeusMedia\Common\FS\File\PHP{
	class Encoder extends \FS_File_PHP_Encoder{}
	class Lister extends \FS_File_PHP_Lister{}}
namespace CeusMedia\Common\FS\File\PHP\Check{
	class MethodOrder extends \FS_File_PHP_Check_MethodOrder{}
	class MethodVisibility extends \FS_File_PHP_Check_MethodVisibility{}}
namespace CeusMedia\Common\FS\File\PHP\Test{
	class Creator extends \FS_File_PHP_Test_Creator{}}
namespace CeusMedia\Common\FS\File\VCard{
	class Reader extends \FS_File_VCard_Reader{}
	class Writer extends \FS_File_VCard_Writer{}
	class Builder extends \FS_File_VCard_Builder{}
	class Parser extends \FS_File_VCard_Parser{}}
namespace CeusMedia\Common\FS\File\YAML{
	class Spyc extends \FS_File_YAML_Spyc{}
	class Reader extends \FS_File_YAML_Reader{}
	class Writer extends \FS_File_YAML_Writer{}}
namespace CeusMedia\Common\FS\Folder{
	class RegexFilter extends \FS_Folder_RegexFilter{}
	class CodeLineCounter extends \FS_Folder_CodeLineCounter{}
	class MethodVisibilityCheck extends \FS_Folder_MethodVisibilityCheck{}
	class Iterator extends \FS_Folder_Iterator{}
	class RecursiveLister extends \FS_Folder_RecursiveLister{}
	class Reader extends \FS_Folder_Reader{}
	class MethodSortCheck extends \FS_Folder_MethodSortCheck{}
	class Editor extends \FS_Folder_Editor{}
	class RecursiveIterator extends \FS_Folder_RecursiveIterator{}
	class Lister extends \FS_Folder_Lister{}
	class RecursiveRegexFilter extends \FS_Folder_RecursiveRegexFilter{}
	class SyntaxChecker extends \FS_Folder_SyntaxChecker{}}
namespace CeusMedia\Common\FS\Folder\Treeview{
	class JsonExtended extends \FS_Folder_Treeview_JsonExtended{}
	class Json extends \FS_Folder_Treeview_Json{}}
namespace CeusMedia\Common\Net{
	class AtomServerTime extends \Net_AtomServerTime{}
	class Reader extends \Net_Reader{}
	class Connectivity extends \Net_Connectivity{}
	class SVN extends \Net_SVN{}
	class AtomTime extends \Net_AtomTime{}
	class CURL extends \Net_CURL{}}
namespace CeusMedia\Common\Net\API{
	class DDNSS extends \Net_API_DDNSS{}
	class Premailer extends \Net_API_Premailer{}
	class Gravatar extends \Net_API_Gravatar{}
	class Dyn extends \Net_API_Dyn{}}
namespace CeusMedia\Common\Net\API\Google{
	class Request extends \Net_API_Google_Request{}
	class ClosureCompiler extends \Net_API_Google_ClosureCompiler{}}
namespace CeusMedia\Common\Net\API\Google\Maps{
	class Geocoder extends \Net_API_Google_Maps_Geocoder{}}
namespace CeusMedia\Common\Net\API\Google\Sitemap{
	class Submit extends \Net_API_Google_Sitemap_Submit{}}
namespace CeusMedia\Common\Net\FTP{
	class Connection extends \Net_FTP_Connection{}
	class Reader extends \Net_FTP_Reader{}
	class Writer extends \Net_FTP_Writer{}
	class Client extends \Net_FTP_Client{}}
namespace CeusMedia\Common\Net\HTTP{
	class Download extends \Net_HTTP_Download{}
	class UploadErrorHandler extends \Net_HTTP_UploadErrorHandler{}
	class Reader extends \Net_HTTP_Reader{}
	class PartitionCookie extends \Net_HTTP_PartitionCookie{}
	class CrossDomainProxy extends \Net_HTTP_CrossDomainProxy{}
	class Cookie extends \Net_HTTP_Cookie{}
	class Response extends \Net_HTTP_Response{}
	class PartitionSession extends \Net_HTTP_PartitionSession{}
	class Session extends \Net_HTTP_Session{}
	class Request extends \Net_HTTP_Request{}
	class Post extends \Net_HTTP_Post{}
	class Status extends \Net_HTTP_Status{}
	class Method extends \Net_HTTP_Method{}}
namespace CeusMedia\Common\Net\HTTP\Header{
	class Renderer extends \Net_HTTP_Header_Renderer{}
	class Section extends \Net_HTTP_Header_Section{}
	class Parser extends \Net_HTTP_Header_Parser{}
	class Field extends \Net_HTTP_Header_Field{}}
namespace CeusMedia\Common\Net\HTTP\Header\Field{
	class Parser extends \Net_HTTP_Header_Field_Parser{}}
namespace CeusMedia\Common\Net\HTTP\Request{
	class QueryParser extends \Net_HTTP_Request_QueryParser{}
	class Receiver extends \Net_HTTP_Request_Receiver{}}
namespace CeusMedia\Common\Net\HTTP\Response{
	class Compressor extends \Net_HTTP_Response_Compressor{}
	class Parser extends \Net_HTTP_Response_Parser{}
	class Sender extends \Net_HTTP_Response_Sender{}
	class Decompressor extends \Net_HTTP_Response_Decompressor{}}
namespace CeusMedia\Common\Net\HTTP\Sniffer{
	class Charset extends \Net_HTTP_Sniffer_Charset{}
	class OS extends \Net_HTTP_Sniffer_OS{}
	class MimeType extends \Net_HTTP_Sniffer_MimeType{}
	class Client extends \Net_HTTP_Sniffer_Client{}
	class Encoding extends \Net_HTTP_Sniffer_Encoding{}
	class Browser extends \Net_HTTP_Sniffer_Browser{}
	class Language extends \Net_HTTP_Sniffer_Language{}}
namespace CeusMedia\Common\Net\Memory{
	class StaticCache extends \Net_Memory_StaticCache{}
	class Cache extends \Net_Memory_Cache{}}
namespace CeusMedia\Common\Net\SVN{
	class Client extends \Net_SVN_Client{}}
namespace CeusMedia\Common\Net\Site{
	class MapBuilder extends \Net_Site_MapBuilder{}
	class MapWriter extends \Net_Site_MapWriter{}
	class Crawler extends \Net_Site_Crawler{}
	class MapCreator extends \Net_Site_MapCreator{}}
namespace CeusMedia\Common\Net\XMPP{
	class JID extends \Net_XMPP_JID{}
	class MessageSender extends \Net_XMPP_MessageSender{}}
namespace CeusMedia\Common\Net\XMPP\XMPPHP{
	class BOSH extends \Net_XMPP_XMPPHP_BOSH{}
	class Log extends \Net_XMPP_XMPPHP_Log{}
	class XMPP extends \Net_XMPP_XMPPHP_XMPP{}
	class Exception extends \Net_XMPP_XMPPHP_Exception{}
	class XMLStream extends \Net_XMPP_XMPPHP_XMLStream{}
	class XMLObj extends \Net_XMPP_XMPPHP_XMLObj{}
	class Roster extends \Net_XMPP_XMPPHP_Roster{}}
namespace CeusMedia\Common\UI{
	class Template extends \UI_Template{}
	class VariableDumper extends \UI_VariableDumper{}
	class Text extends \UI_Text{}
	class OutputBuffer extends \UI_OutputBuffer{}
	class Image extends \UI_Image{}
	class DevOutput extends \UI_DevOutput{}}
namespace CeusMedia\Common\UI\HTML{
	class PageFrame extends \UI_HTML_PageFrame{}
	class JQuery extends \UI_HTML_JQuery{}
	class Pagination extends \UI_HTML_Pagination{}
	class CollapsePanel extends \UI_HTML_CollapsePanel{}
	class ContextMenu extends \UI_HTML_ContextMenu{}
	class Index extends \UI_HTML_Index{}
	class Ladder extends \UI_HTML_Ladder{}
	class Paging extends \UI_HTML_Paging{}
	class FormElements extends \UI_HTML_FormElements{}
	class Options extends \UI_HTML_Options{}
	class Elements extends \UI_HTML_Elements{}
	class Tag extends \UI_HTML_Tag{}
	class Table extends \UI_HTML_Table{}
	class Indicator extends \UI_HTML_Indicator{}
	class Tabs extends \UI_HTML_Tabs{}
	class Panel extends \UI_HTML_Panel{}}
namespace CeusMedia\Common\UI\HTML\Exception{
	class Trace extends \UI_HTML_Exception_Trace{}
	class View extends \UI_HTML_Exception_View{}
	class TraceViewer extends \UI_HTML_Exception_TraceViewer{}
	class Page extends \UI_HTML_Exception_Page{}}
namespace CeusMedia\Common\UI\Image{
	class Rotator extends \UI_Image_Rotator{}
	class Captcha extends \UI_Image_Captcha{}
	class Histogram extends \UI_Image_Histogram{}
	class Printer extends \UI_Image_Printer{}
	class Modifier extends \UI_Image_Modifier{}
	class PieGraph extends \UI_Image_PieGraph{}
	class Processing extends \UI_Image_Processing{}
	class Drawer extends \UI_Image_Drawer{}
	class Watermark extends \UI_Image_Watermark{}
	class EvolutionGraph extends \UI_Image_EvolutionGraph{}
	class Exif extends \UI_Image_Exif{}
	class Creator extends \UI_Image_Creator{}
	class Error extends \UI_Image_Error{}
	class ThumbnailCreator extends \UI_Image_ThumbnailCreator{}
	class FormulaDiagram extends \UI_Image_FormulaDiagram{}
	class Filter extends \UI_Image_Filter{}
	class TransparentWatermark extends \UI_Image_TransparentWatermark{}}
namespace CeusMedia\Common\UI\Image\Graph{
	class LinePlot extends \UI_Image_Graph_LinePlot{}
	class Builder extends \UI_Image_Graph_Builder{}
	abstract class Generator extends \UI_Image_Graph_Generator{}
	class Components extends \UI_Image_Graph_Components{}}
namespace CeusMedia\Common\UI\Image\Graphviz{
	class Renderer extends \UI_Image_Graphviz_Renderer{}
	class Graph extends \UI_Image_Graphviz_Graph{}}
namespace CeusMedia\Common\XML{
	class Element extends \XML_Element{}
	class Converter extends \XML_Converter{}
	class ElementReader extends \XML_ElementReader{}
	class FeedIdentifier extends \XML_FeedIdentifier{}
	class UnitTestResultReader extends \XML_UnitTestResultReader{}
	class Parser extends \XML_Parser{}
	class Validator extends \XML_Validator{}
	class Namespaces extends \XML_Namespaces{}}
namespace CeusMedia\Common\XML\Atom{
	class Reader extends \XML_Atom_Reader{}
	class Parser extends \XML_Atom_Parser{}
	class Validator extends \XML_Atom_Validator{}}
namespace CeusMedia\Common\XML\DOM{
	class GoogleSitemapWriter extends \XML_DOM_GoogleSitemapWriter{}
	class FeedIdentifier extends \XML_DOM_FeedIdentifier{}
	class Storage extends \XML_DOM_Storage{}
	class ObjectSerializer extends \XML_DOM_ObjectSerializer{}
	class GoogleSitemapBuilder extends \XML_DOM_GoogleSitemapBuilder{}
	class Builder extends \XML_DOM_Builder{}
	class ObjectDeserializer extends \XML_DOM_ObjectDeserializer{}
	class SyntaxValidator extends \XML_DOM_SyntaxValidator{}
	class ObjectFileDeserializer extends \XML_DOM_ObjectFileDeserializer{}
	class Formater extends \XML_DOM_Formater{}
	class Parser extends \XML_DOM_Parser{}
	class FileWriter extends \XML_DOM_FileWriter{}
	class Node extends \XML_DOM_Node{}
	class ObjectFileSerializer extends \XML_DOM_ObjectFileSerializer{}
	class UrlReader extends \XML_DOM_UrlReader{}
	class FileEditor extends \XML_DOM_FileEditor{}
	class FileReader extends \XML_DOM_FileReader{}
	class XPathQuery extends \XML_DOM_XPathQuery{}}
namespace CeusMedia\Common\XML\DOM\PEAR{
	class PackageReader extends \XML_DOM_PEAR_PackageReader{}}
namespace CeusMedia\Common\XML\OPML{
	class Outline extends \XML_OPML_Outline{}
	class Builder extends \XML_OPML_Builder{}
	class Parser extends \XML_OPML_Parser{}
	class FileWriter extends \XML_OPML_FileWriter{}
	class FileReader extends \XML_OPML_FileReader{}}
namespace CeusMedia\Common\XML\RPC{
	class Client extends \XML_RPC_Client{}}
namespace CeusMedia\Common\XML\RSS{
	class Reader extends \XML_RSS_Reader{}
	class Writer extends \XML_RSS_Writer{}
	class Builder extends \XML_RSS_Builder{}
	class Parser extends \XML_RSS_Parser{}
	class SimpleParser extends \XML_RSS_SimpleParser{}
	class GoogleBaseBuilder extends \XML_RSS_GoogleBaseBuilder{}
	class SimpleReader extends \XML_RSS_SimpleReader{}}
namespace CeusMedia\Common\XML\XSL{
	class Transformator extends \XML_XSL_Transformator{}}
