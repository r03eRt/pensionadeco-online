<?
	/**
	 * sysem base config setting
	 * @author Logan Cai (cailongqun@yahoo.com.cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */
	define('DATE_TIME_FORMAT', 'd/M/Y H:i:s');
	//Label
		//Top Action
		define('LBL_ACTION_REFRESH', 'Refresh');
		define("LBL_ACTION_DELETE", 'Delete');
		//File Listing
	define('LBL_NAME', 'Name');
	define('LBL_SIZE', 'Size');
	define('LBL_MODIFIED', 'Modified At');
		//File Information
	define('LBL_FILE_INFO', 'File Information:');
	define('LBL_FILE_NAME', 'Name:');	
	define('LBL_FILE_CREATED', 'Created At:');
	define("LBL_FILE_MODIFIED", 'Modified At:');
	define("LBL_FILE_SIZE", 'File Size:');
	define('LBL_FILE_TYPE', 'File Type:');
	define("LBL_FILE_WRITABLE", 'Writable?');
	define("LBL_FILE_READABLE", 'Readable?');
		//Folder Information
	define('LBL_FOLDER_INFO', 'Folder Information');
	define("LBL_FOLDER_PATH", 'Path:');
	define("LBL_FOLDER_CREATED", 'Created At:');
	define("LBL_FOLDER_MODIFIED", 'Modified At:');
	define('LBL_FOLDER_SUDDIR', 'Subfolders:');
	define("LBL_FOLDER_FIELS", 'Files:');
	define("LBL_FOLDER_WRITABLE", 'Writable?');
	define("LBL_FOLDER_READABLE", 'Readable?');
		//Preview
	define("LBL_PREVIEW", 'Preview');
	//Buttons
	define('LBL_BTN_SELECT', 'Select');
	define('LBL_BTN_CANCEL', 'Cancel');
	define("LBL_BTN_UPLOAD", 'Upload');
	define('LBL_BTN_CREATE', 'Create');
	define("LBL_BTN_NEW_FOLDER", 'New Folder');
	//ERROR MESSAGES
		//deletion
	define('ERR_NOT_FILE_SELECTED', 'Please select a file.');
	define('ERR_NOT_DOC_SELECTED', 'No document(s) selected for deletion.');
	define('ERR_DELTED_FAILED', 'Unable to delete selected document(s).');
	define('ERR_FOLDER_PATH_NOT_ALLOWED', 'The folder path is not allowed.');
		//class manager
	define("ERR_FOLDER_NOT_FOUND", 'Unable to locate the specific folder: ');
		//rename
	define('ERR_RENAME_FORMAT', 'Please give it a name which only contain letters, digits, space, hyphen and underscore.');
	define('ERR_RENAME_EXISTS', 'Please give it a name which is unique under the folder.');
	define('ERR_RENAME_FILE_NOT_EXISTS', 'The file/folder does not exist.');
	define('ERR_RENAME_FAILED', 'Unable to rename it, please try again.');
	define('ERR_RENAME_EMPTY', 'Please give it a name.');
	define("ERR_NO_CHANGES_MADE", 'No changes has been made.');
	define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'You are not permitted to change the file to such extension.');
		//folder creation
	define('ERR_FOLDER_FORMAT', 'Please give it a name which only contain letters, digits, space, hyphen and underscore.');
	define('ERR_FOLDER_EXISTS', 'Please give it a name which is unique under the folder.');
	define('ERR_FOLDER_CREATION_FAILED', 'Unable to create a folder, please try again.');
	define('ERR_FOLDER_NAME_EMPTY', 'Please give it  a name.');
	
		//file upload
	define("ERR_FILE_NAME_FORMAT", 'Please give it a name which only contain letters, digits, space, hyphen and underscore.');
	define('ERR_FILE_NOT_UPLOADED', 'No file has been selected for uploading.');
	define('ERR_FILE_TYPE_NOT_ALLOWED', 'You are not allowed to upload such file type.');
	define('ERR_FILE_MOVE_FAILED', 'Failed to move the file.');
	define('ERR_FILE_NOT_AVAILABLE', 'The file is unavailable.');
	define('ERROR_FILE_TOO_BID', 'File too large. (max: %s)');
	

	//Tips
	define('TIP_FOLDER_GO_DOWN', 'Single Click to get to this folder...');
	define("TIP_DOC_RENAME", 'Double Click to edit...');
	define('TIP_FOLDER_GO_UP', 'Single Click to get to the parent folder...');
	define("TIP_SELECT_ALL", 'Select All');
	define("TIP_UNSELECT_ALL", 'Unselect All');
	//WARNING
	define('WARNING_DELETE', 'Are you sure to delete selected files.');
	//Preview
	define('PREVIEW_NOT_PREVIEW', 'No preview available.');
	define('PREVIEW_OPEN_FAILED', 'Unable to open the file.');
	define('PREVIEW_IMAGE_LOAD_FAILED', 'Unable to load the image');

	//Login
	define('LOGIN_PAGE_TITLE', 'Ajax File Manager Login Form');
	define('LOGIN_FORM_TITLE', 'Login Form');
	define('LOGIN_USERNAME', 'Username:');
	define('LOGIN_PASSWORD', 'Password:');
	define('LOGIN_FAILED', 'Invalid username/password.');
	
	
?>