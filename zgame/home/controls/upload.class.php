<?php
/**
 * FileName: upload.class.php
 * Description:文件上传处理函数
 * Author: xiaochengcheng,tanjianchengcc@gmail.com
 * Date:2013-4-1 下午3:40:29
 * Version:1.00
 */
class upload{
	/**
	 * 文件处理
	 */
	public function upload(){
		$error = "";
		$msg = "";
		$fileElementName = get_var_value("name");
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{
		
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;
		
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else
		{
			$msg = file_get_contents($_FILES[$fileElementName]['tmp_name']);
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES[$fileElementName]);
		}
		$result = array(
				"error"=>$error,
				"msg" =>$msg
		);
		echo json_encode($result);
		exit;
	}
}