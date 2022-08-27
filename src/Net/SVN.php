<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace CeusMedia\Common\Net;

class SVN
{
	static public $nodes	= [
		SVN_NODE_NONE			=> [
			'label'	=> 'none',
			'text'	=> 'Absent'
		],
		SVN_NODE_FILE			=> [
			'label'	=> 'file',
			'text'	=> 'File'
		],
		SVN_NODE_DIR			=> [
			'label'	=> 'directory',
			'text'	=> 'Directory'
		],
		SVN_NODE_UNKNOWN		=> [
			'label'	=> 'unknown',
			'text'	=> 'Something Subversion cannot identify'
		]
	];
	static public $states	= [
		SVN_WC_STATUS_NONE			=> [
			'label'	=> 'none',
			'text'	=> 'Status does not exist'
		],
		SVN_WC_STATUS_UNVERSIONED	=> [
			'label'	=> 'unversioned',
			'text'	=> 'Item is not versioned in working copy'
		],
		SVN_WC_STATUS_NORMAL		=> [
			'label'	=> 'normal',
			'text'	=> 'Item exists, nothing else is happening'
		],
		SVN_WC_STATUS_ADDED			=> [
			'label'	=> 'added',
			'text'	=> 'Item is scheduled for addition'
		],
		SVN_WC_STATUS_MISSING		=> [
			'label'	=> 'missing',
			'text'	=> 'Item is versioned but missing from the working copy'
		],
		SVN_WC_STATUS_DELETED		=> [
			'label'	=> 'deleted',
			'text'	=> 'Item is scheduled for deletion'
		],
		SVN_WC_STATUS_REPLACED		=> [
			'label'	=> 'replaced',
			'text'	=> 'Item was deleted and then re-added'
		],
		SVN_WC_STATUS_MODIFIED		=> [
			'label'	=> 'modified',
			'text'	=> 'Item (text or properties) was modified'
		],
		SVN_WC_STATUS_MERGED		=> [
			'label'	=> 'merged',
			'text'	=> 'Item\'s local modifications were merged with repository modifications'
		],
		SVN_WC_STATUS_CONFLICTED	=> [
			'label'	=> 'conflicted',
			'text'	=> 'Item\'s local modifications conflicted with repository modifications'
		],
		SVN_WC_STATUS_IGNORED		=> [
			'label'	=> 'ignored',
			'text'	=> 'Item is unversioned but configured to be ignored'
		],
		SVN_WC_STATUS_OBSTRUCTED	=> [
			'label'	=> 'obstructed',
			'text'	=> 'Unversioned item is in the way of a versioned resource'
		],
		SVN_WC_STATUS_EXTERNAL		=> [
			'label'	=> 'external',
			'text'	=> 'Unversioned path that is populated using svn:externals'
		],
		SVN_WC_STATUS_INCOMPLETE	=> [
			'label'	=> 'incomplete',
			'text'	=> 'Directory does not contain complete entries list'
		]
	];

	static public $schedules	= [
		SVN_WC_SCHEDULE_NORMAL		=> [
			'label'	=> 'normal',
			'text'	=> 'nothing special'
		],
		SVN_WC_SCHEDULE_ADD		=> [
			'label'	=> 'add',
			'text'	=> 'item will be added'
		],
		SVN_WC_SCHEDULE_DELETE		=> [
			'label'	=> 'delete',
			'text'	=> 'item will be deleted'
		],
		SVN_WC_SCHEDULE_REPLACE		=> [
			'label'	=> 'replace',
			'text'	=> 'item will be added and deleted'
		],
	];
}
