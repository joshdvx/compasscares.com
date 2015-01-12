<?php
/***
 *  admin environment where core admin roles has been defined . 
 * Since 1.0.13 
 * */
class WP_E_Adminenvironment {
	
	//instance of this class 
	protected static $instance = null;
	
	public function __construct(){
		
		$this->view = new WP_E_View();
		$this->invite = new WP_E_Invite;
		$this->document = new WP_E_Document;
		$this->signature = new WP_E_Signature;
		$this->user = new WP_E_User;
		$this->setting = new WP_E_Setting;	
		$this->error= new WP_E_Esigerror;	
		
	} 
	
	/***
	 * self instance calling 
	 * 
	 * */
	public static function get_instance()
	{
		// If the single instance hasn't been set, set it now.
		if(null == self::$instance){
			self::$instance=new self() ; 
		}
		return self::$instance ; 
	}
	
	 /**
	   * wp-config modification
	   **/

	/**
	 * Enables WP E-signature main admin . 
	 *
	 * @throws FilesystemOperationException with S/FTP form if it can't get the required filesystem credentials
	 * @throws FileOperationException
	 * Since 1.0.13 
	 */
	public function esign_config_add_directive() {
		
		$config_path = $this->esign_wp_config_path();
		
		$config_data = @file_get_contents($config_path);
		if ($config_data === false)
		return;
		
		$new_config_data = $this->esign_config_remove_from_content($config_data);
		$new_config_data = preg_replace(
			'~<\?(php)?~', 
			"\\0\r\n" . $this->esign_config_first_addon(), 
			$new_config_data, 
			1);

		if ($new_config_data != $config_data) {
			try {
				$this->esign_write_to_file($config_path, $new_config_data);
			} catch (FilesystemOperationException $ex) {
				throw new FilesystemModifyException(
					$ex->getMessage(), $ex->credentials_form(),
					'Edit file <strong>' . $config_path . 
						'</strong> and add next lines:', $config_path, 
					$this->wp_config_addon());
			}
		}
	}
	
	/**
	 * Enables WP E-signature main admin . 
	 * Second time saving wp config as wp admin saving in database . 
	 * @throws FilesystemOperationException with S/FTP form if it can't get the required filesystem credentials
	 * @throws FileOperationException
	 * Since 1.0.13 
	 */
	public function esign_config_save_directive() {
		
		$config_path = $this->esign_wp_config_path();
		
		$config_data = @file_get_contents($config_path);
		if ($config_data === false)
		return;
		
		$new_config_data = $this->esign_config_remove_from_content($config_data);
		$new_config_data = preg_replace(
			'~<\?(php)?~', 
			"\\0\r\n" . $this->esign_config_addon(), 
			$new_config_data, 
			1);

		if ($new_config_data != $config_data) {
			try {
				$this->esign_write_to_file($config_path, $new_config_data);
			} catch (FilesystemOperationException $ex) {
				throw new FilesystemModifyException(
					$ex->getMessage(), $ex->credentials_form(),
					'Edit file <strong>' . $config_path . 
						'</strong> and add next lines:', $config_path, 
					$this->wp_config_addon());
			}
		}
	}
	/**
	    * @throws FilesystemOperationException with S/FTP form if it can't get the required filesystem credentials
	    * @throws FileOperationException
	    */
	public function esign_config_remove_directive() {
		
		$config_path = $this->esign_wp_config_path();
		

		$config_data = @file_get_contents($config_path);
		if ($config_data === false)
		return;
		
		$new_config_data = $this->esign_config_remove_from_content($config_data);
		if ($new_config_data != $config_data) {
			try {
				$this->esign_write_to_file($config_path, $new_config_data);
			} catch (FilesystemOperationException $ex) {
				throw new FilesystemModifyException(
					$ex->getMessage(), $ex->credentials_form(),
					'Edit file <strong>' . $config_path . 
						'</strong> and remove next lines:', 
					$config_path,  $this->wp_config_addon());
			}
		}
	}

	/**
	 * @return string Addon required for plugin in wp-config
	 **/
	
	private function esign_config_addon() {
		return "/** Enable Esign Super Admin */\r\n" .
			"define('ESIGN_DOC_SUPERADMIN_USERID', true); // Added by E-signature\r\n";
	}

	/**
	 * @return string Addon required for plugin in wp-config
	 **/
	
	private function esign_config_first_addon() {
		return "/** Enable Esign Super Admin */\r\n" .
			"define('ESIGN_DOC_SUPERADMIN_USERID', false); // Added by E-signature\r\n";
	}
	/**
	 * @param string $config_data wp-config.php content
	 * @return string
	 * @throws FilesystemOperationException with S/FTP form if it can't get the required filesystem credentials
	 * @throws FileOperationException
	 */
	
	public function esign_config_remove_from_content($config_data) {
		$config_data = preg_replace(
			"~\\/\\*\\* Enable Esign Super Admin \\*\\*?\\/.*?\\/\\/ Added by E-signature(\r\n)*~s", 
			'', $config_data);
		$config_data = preg_replace(
			"~(\\/\\/\\s*)?define\\s*\\(\\s*['\"]?ESIGN_DOC_SUPERADMIN_USERID['\"]?\\s*,.*?\\)\\s*;+\\r?\\n?~is", 
			'', $config_data);

		return $config_data;
	}
	
	/***
	 * Return wp config file path . 
	 * Since 1.0.13
	 * */

	public function esign_wp_config_path(){
		$base =ABSPATH ; // dirname(__FILE__);
		$path = false;
		
		if (@file_exists($base ."/wp-config.php"))
		{
			$path = $base ."/wp-config.php" ;
		}
		else
			if (@file_exists("../../../../wp-config.php"))
			{
				$path ="../../../../wp-config.php";
			}
			else 
			{
				$path = false;
			}

			
			return $path;
		}
	
	
	
	/***
	 * Tries to write file content
	 *
	 * @param string $filename path to file
	 * @param string $content data to write
	 * @param string $method Which method to use when creating
	 * @param string $url Where to redirect after creation
	 * @param bool|string $context folder in which to write file
	 * @throws FilesystemWriteException
	 * @return void
	 **/
	
	public function esign_write_to_file($filename, $content) {
		if (@file_put_contents($filename, $content))
			return;

		try {
			esign_request_filesystem_credentials();
		} catch (FilesystemOperationException $ex) {
			throw new FilesystemWriteException($ex->getMessage(), 
				$ex->credentials_form(), $filename, $content);
		}

		global $wp_filesystem;
		if (!$wp_filesystem->put_contents($filename, $content)) {
			throw new FilesystemWriteException(
				'FTP credentials don\'t allow to write to file <strong>' . 
				$filename . '</strong>', esign_filesystem_credentials_form(),
				$filename, $content);
		}
	}
	
	/***
	 * Get WordPress filesystems credentials. Required for WP filesystem usage.
	 * @param string $method Which method to use when creating
	 * @param string $url Where to redirect after creation
	 * @param bool|string $context path to folder that should be have filesystem credentials.
	 * If false WP_CONTENT_DIR is assumed
	 * @throws FilesystemOperationException with S/FTP form if it can't get the required filesystem credentials
	 */
	public function esign_request_filesystem_credentials($method = '', $url = '', $context = false) {
		if (strlen($url) <= 0)
			$url = $_SERVER['REQUEST_URI'];
		$url = preg_replace("/&w3tc_note=([^&]+)/", '', $url);


		$success = true;
		ob_start();
		if (false === ($creds = request_filesystem_credentials($url, $method, false, $context, array()))) {
			$success =  false;
		}
		$form = ob_get_contents();
		ob_end_clean();

		ob_start();
		// If first check failed try again and show error message
		if (!WP_Filesystem($creds) && $success) {
			request_filesystem_credentials($url, $method, true, false, array());
			$success =  false;
			$form = ob_get_contents();
		}
		ob_end_clean();

		$error = '';
		if (preg_match("/<div([^c]+)class=\"error\">(.+)<\/div>/", $form, $matches)) {
			$error = $matches[2];
			$form = str_replace($matches[0], '', $form);
		}

		if (!$success) {
			throw new FilesystemOperationException($error, $form);
		}
	}
	
		/**
	 * Tries to read file content
	 * @param string $filename path to file
	 * @param string $method Which method to use when creating
	 * @param string $url Where to redirect after creation
	 * @param bool|string $context folder to read from
	 * @return mixed
	 * @throws FilesystemOperationException with S/FTP form if it can't get the required filesystem credentials
	 * @throws FileOperationException
	 */
	public function esig_read_from_file($filename) {
		$content = @file_get_contents($filename);
		if ($content)
			return $content;

		esign_request_filesystem_credentials();

		global $wp_filesystem;
		if (!($content = $wp_filesystem->get_contents($filename))) {
			throw new FileOperationException('Could not read file: ' . $filename, 'write', 'file', $filename);
		}
		return $content;
	}
	
		/**
	 * @param string $method
	 * @param string $url
	 * @param bool|string $context
	 * @return FilesystemOperationException with S/FTP form
	 */
	function esign_filesystem_credentials_form($method = '', $url = '', 
			$context = false) {
		ob_start();
		// If first check failed try again and show error message
		request_filesystem_credentials($url, $method, true, false, array());
		$success =  false;
		$form = ob_get_contents();

		ob_end_clean();

		$error = '';
		if (preg_match("/<div([^c]+)class=\"error\">(.+)<\/div>/", $form, $matches)) {
			$form = str_replace($matches[0], '', $form);
		}

		return $form;
	}
	
	
}
