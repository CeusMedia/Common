<?php
class Net_SVN{
	
	static public $nodes	= array(
		SVN_NODE_NONE			=> array(
			'label'	=> 'none',
			'text'	=> 'Absent'
		),
		SVN_NODE_FILE			=> array(
			'label'	=> 'file',
			'text'	=> 'File'
		),
		SVN_NODE_DIR			=> array(
			'label'	=> 'directory',
			'text'	=> 'Directory'
		),
		SVN_NODE_UNKNOWN		=> array(
			'label'	=> 'unknown',
			'text'	=> 'Something Subversion cannot identify'
		)		
	);
	static public $states	= array(
		SVN_WC_STATUS_NONE			=> array(
			'label'	=> 'none',
			'text'	=> 'Status does not exist'
		),
		SVN_WC_STATUS_UNVERSIONED	=> array(
			'label'	=> 'unversioned',
			'text'	=> 'Item is not versioned in working copy'
		),
		SVN_WC_STATUS_NORMAL		=> array(
			'label'	=> 'normal',
			'text'	=> 'Item exists, nothing else is happening'
		),
		SVN_WC_STATUS_ADDED			=> array(
			'label'	=> 'added',
			'text'	=> 'Item is scheduled for addition'
		),
		SVN_WC_STATUS_MISSING		=> array(
			'label'	=> 'missing',
			'text'	=> 'Item is versioned but missing from the working copy'
		),
		SVN_WC_STATUS_DELETED		=> array(
			'label'	=> 'deleted',
			'text'	=> 'Item is scheduled for deletion'
		),
		SVN_WC_STATUS_REPLACED		=> array(
			'label'	=> 'replaced',
			'text'	=> 'Item was deleted and then re-added'
		),
		SVN_WC_STATUS_MODIFIED		=> array(
			'label'	=> 'modified',
			'text'	=> 'Item (text or properties) was modified'
		),
		SVN_WC_STATUS_MERGED		=> array(
			'label'	=> 'merged',
			'text'	=> 'Item\'s local modifications were merged with repository modifications'
		),
		SVN_WC_STATUS_CONFLICTED	=> array(
			'label'	=> 'conflicted',
			'text'	=> 'Item\'s local modifications conflicted with repository modifications'
		),
		SVN_WC_STATUS_IGNORED		=> array(
			'label'	=> 'ignored',
			'text'	=> 'Item is unversioned but configured to be ignored'
		),
		SVN_WC_STATUS_OBSTRUCTED	=> array(
			'label'	=> 'obstructed',
			'text'	=> 'Unversioned item is in the way of a versioned resource'
		),
		SVN_WC_STATUS_EXTERNAL		=> array(
			'label'	=> 'external',
			'text'	=> 'Unversioned path that is populated using svn:externals'
		),
		SVN_WC_STATUS_INCOMPLETE	=> array(
			'label'	=> 'incomplete',
			'text'	=> 'Directory does not contain complete entries list'
		)
	);

	static public $schedules	= array(
		SVN_WC_SCHEDULE_NORMAL		=> array(
			'label'	=> 'normal',
			'text'	=> 'nothing special'
		),
		SVN_WC_SCHEDULE_ADD		=> array(
			'label'	=> 'add',
			'text'	=> 'item will be added'
		),
		SVN_WC_SCHEDULE_DELETE		=> array(
			'label'	=> 'delete',
			'text'	=> 'item will be deleted'
		),
		SVN_WC_SCHEDULE_REPLACE		=> array(
			'label'	=> 'replace',
			'text'	=> 'item will be added and deleted'
		),
	);
}
?>