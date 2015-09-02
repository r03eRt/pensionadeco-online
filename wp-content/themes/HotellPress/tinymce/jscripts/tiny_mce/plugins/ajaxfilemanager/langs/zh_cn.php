<?
	/**
	 * sysem base config setting
	 * @author Logan Cai (cailongqun@yahoo.com.cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */
	define('DATE_TIME_FORMAT', 'Y/m/d H:i:s');
	//Label
		//Top Action
		define('LBL_ACTION_REFRESH', '刷新');
		define("LBL_ACTION_DELETE", '删除');
		//File Listing
	define('LBL_NAME', '文件名称');
	define('LBL_SIZE', '大小');
	define('LBL_MODIFIED', '更改于');
		//File Information
	define('LBL_FILE_INFO', '文件信息:');
	define('LBL_FILE_NAME', '名称:');	
	define('LBL_FILE_CREATED', '创建于:');
	define("LBL_FILE_MODIFIED", '更改于:');
	define("LBL_FILE_SIZE", '大小:');
	define('LBL_FILE_TYPE', '种类:');
	define("LBL_FILE_WRITABLE", '可写?');
	define("LBL_FILE_READABLE", '可读?');
		//Folder Information
	define('LBL_FOLDER_INFO', '目录信息');
	define("LBL_FOLDER_PATH", '路径:');
	define("LBL_FOLDER_CREATED", '创建于:');
	define("LBL_FOLDER_MODIFIED", '更改于:');
	define('LBL_FOLDER_SUDDIR', '子目录:');
	define("LBL_FOLDER_FIELS", '文件:');
	define("LBL_FOLDER_WRITABLE", '可写?');
	define("LBL_FOLDER_READABLE", '可读?');
		//Preview
	define("LBL_PREVIEW", '预览');
	//Buttons
	define('LBL_BTN_SELECT', '选择');
	define('LBL_BTN_CANCEL', '取消');
	define("LBL_BTN_UPLOAD", '上传');
	define('LBL_BTN_CREATE', '创建');
	define("LBL_BTN_NEW_FOLDER", '新目录');
	//ERROR MESSAGES
		//deletion
	define('ERR_NOT_FILE_SELECTED', '请选择文件.');
	define('ERR_NOT_DOC_SELECTED', '请选择需要删除的文件或者目录.');
	define('ERR_DELTED_FAILED', '无法删除所选择的文件或者目录.');
	define('ERR_FOLDER_PATH_NOT_ALLOWED', '无法访问此目录.');
		//class manager
	define("ERR_FOLDER_NOT_FOUND", '无法找到指定的目录.');
		//rename
	define('ERR_RENAME_FORMAT','文件名称只允许包含字母，数字，空格，连字号与下划线');
	define('ERR_RENAME_EXISTS', '相同名称的目录或者文件已存在');
	define('ERR_RENAME_FILE_NOT_EXISTS', '文件或者目录不存在.');
	define('ERR_RENAME_FAILED', '重命名失败，请重试.');
	define('ERR_RENAME_EMPTY', '请输入名称.');
	define("ERR_NO_CHANGES_MADE", '未有更新.');
	define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', '无权限更改文件成此类扩展名.');
		//folder creation
	define('ERR_FOLDER_FORMAT', '目录名称只允许包含字母，数字，空格，连字号与下划线');
	define('ERR_FOLDER_EXISTS', '相同名称的目录已存在');
	define('ERR_FOLDER_CREATION_FAILED', '目录创建失败，请重试');
	define('ERR_FOLDER_NAME_EMPTY', '请输入目录名称.');
	
		//file upload
	define("ERR_FILE_NAME_FORMAT", '文件名称只允许包含字母，数字，空格，连字号与下划线');
	define('ERR_FILE_NOT_UPLOADED', '请选择所要上传的文件');
	define('ERR_FILE_TYPE_NOT_ALLOWED', '此类文件不允许上传.');
	define('ERR_FILE_MOVE_FAILED', '无法移动已上传的文件.');
	define('ERR_FILE_NOT_AVAILABLE', '文件不存在.');
	define('ERROR_FILE_TOO_BID', '文件太大. (最大允许: %s)');
	

	//Tips
	define('TIP_FOLDER_GO_DOWN', '单击进入此目录...');
	define("TIP_DOC_RENAME", '双击重命名...');
	define('TIP_FOLDER_GO_UP', '单击返回上级目录...');
	define("TIP_SELECT_ALL", '全选择');
	define("TIP_UNSELECT_ALL", '全取消');
	//WARNING
	define('WARNING_DELETE', '确定要删除所选择的文件或者目录?');
	//Preview
	define('PREVIEW_NOT_PREVIEW', '无预览.');
	define('PREVIEW_OPEN_FAILED', '无法打开文件.');
	define('PREVIEW_IMAGE_LOAD_FAILED', '无法载入图像');

	//Login
	define('LOGIN_PAGE_TITLE', 'Ajax File Manager 登录窗口');
	define('LOGIN_FORM_TITLE', '登录窗口');
	define('LOGIN_USERNAME', '用户名:');
	define('LOGIN_PASSWORD', '密码:');
	define('LOGIN_FAILED', '无效用户名或者密码.');	
	
	
	
?>