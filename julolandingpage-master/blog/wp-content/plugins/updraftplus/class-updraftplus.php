<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

class UpdraftPlus {

	public $version;

	public $plugin_title = 'UpdraftPlus Backup/Restore';

	// Choices will be shown in the admin menu in the order used here
	public $backup_methods = array(
		'updraftvault' => 'UpdraftPlus Vault',
		'dropbox' => 'Dropbox',
		's3' => 'Amazon S3',
		'cloudfiles' => 'Rackspace Cloud Files',
		'googledrive' => 'Google Drive',
		'onedrive' => 'Microsoft OneDrive',
		'ftp' => 'FTP',
		'azure' => 'Microsoft Azure',
		'sftp' => 'SFTP / SCP',
		'googlecloud' => 'Google Cloud',
		'backblaze'    => 'Backblaze',
		'webdav' => 'WebDAV',
		's3generic' => 'S3-Compatible (Generic)',
		'openstack' => 'OpenStack (Swift)',
		'dreamobjects' => 'DreamObjects',
		'email' => 'Email'
	);

	public $errors = array();

	public $nonce;

	public $file_nonce;

	public $logfile_name = "";

	public $logfile_handle = false;

	public $backup_time;

	public $job_time_ms;

	public $opened_log_time;

	private $backup_dir;

	private $jobdata;

	public $something_useful_happened = false;

	public $have_addons = false;

	// Used to schedule resumption attempts beyond the tenth, if needed
	public $current_resumption;

	public $newresumption_scheduled = false;

	public $cpanel_quota_readable = false;

	public $error_reporting_stop_when_logged = false;
	
	private $combine_jobs_around;

	/**
	 * Class constructor
	 */
	public function __construct() {
		global $pagenow;
		// Initialisation actions - takes place on plugin load

		if ($fp = fopen(UPDRAFTPLUS_DIR.'/updraftplus.php', 'r')) {
			$file_data = fread($fp, 1024);
			if (preg_match("/Version: ([\d\.]+)(\r|\n)/", $file_data, $matches)) {
				$this->version = $matches[1];
			}
			fclose($fp);
		}

		$load_classes = array(
			'UpdraftPlus_Backup_History' => 'includes/class-backup-history.php',
			'UpdraftPlus_Encryption' => 'includes/class-updraftplus-encryption.php',
			'UpdraftPlus_Manipulation_Functions' => 'includes/class-manipulation-functions.php',
			'UpdraftPlus_Filesystem_Functions' => 'includes/class-filesystem-functions.php',
			'UpdraftPlus_Storage_Methods_Interface' => 'includes/class-storage-methods-interface.php',
			'UpdraftPlus_Job_Scheduler' => 'includes/class-job-scheduler.php'
		);
		
		foreach ($load_classes as $class => $relative_path) {
			if (!class_exists($class)) include_once(UPDRAFTPLUS_DIR.'/'.$relative_path);
		}
		
		// Create admin page
		add_action('init', array($this, 'handle_url_actions'));
		// Run earlier than default - hence earlier than other components
		// admin_menu runs earlier, and we need it because options.php wants to use $updraftplus_admin before admin_init happens
		add_action(apply_filters('updraft_admin_menu_hook', 'admin_menu'), array($this, 'admin_menu'), 9);
		// Not a mistake: admin-ajax.php calls only admin_init and not admin_menu
		add_action('admin_init', array($this, 'admin_menu'), 9);

		// The two actions which we schedule upon
		add_action('updraft_backup', array($this, 'backup_files'));
		add_action('updraft_backup_database', array($this, 'backup_database'));

		// The three actions that can be called from "Backup Now"
		add_action('updraft_backupnow_backup', array($this, 'backupnow_files'));
		add_action('updraft_backupnow_backup_database', array($this, 'backupnow_database'));
		add_action('updraft_backupnow_backup_all', array($this, 'backup_all'));

		// backup_all as an action is legacy (Oct 2013) - there may be some people who wrote cron scripts to use it
		add_action('updraft_backup_all', array($this, 'backup_all'));

		// This is our runs-after-backup event, whose purpose is to see if it succeeded or failed, and resume/mom-up etc.
		add_action('updraft_backup_resume', array($this, 'backup_resume'), 10, 3);

		// If files + db are on different schedules but are scheduled for the same time, then combine them
		add_filter('schedule_event', array($this, 'schedule_event'));
		
		add_action('plugins_loaded', array($this, 'plugins_loaded'));

		// Auto update plugin
		add_filter('auto_update_plugin', array($this, 'maybe_auto_update_plugin'), 20, 2);

		// Prevent iThemes Security from telling people that they have no backups (and advertising them another product on that basis!)
		add_filter('itsec_has_external_backup', '__return_true', 999);
		add_filter('itsec_external_backup_link', array($this, 'itsec_external_backup_link'), 999);
		add_filter('itsec_scheduled_external_backup', array($this, 'itsec_scheduled_external_backup'), 999);

		// Prevent people upgrading from being baffled by WP's obscure error message. See: https://core.trac.wordpress.org/ticket/27196
		add_filter('upgrader_source_selection', array($this, 'upgrader_source_selection'), 10, 4);
		
		// register_deactivation_hook(__FILE__, array($this, 'deactivation'));
		if (!empty($_POST) && !empty($_GET['udm_action']) && 'vault_disconnect' == $_GET['udm_action'] && !empty($_POST['udrpc_message']) && !empty($_POST['reset_hash'])) {
			add_action('wp_loaded', array($this, 'wp_loaded_vault_disconnect'), 1);
		}
		
		// Remove the notice on the Updates page that confuses users who already have backups installed
		if ('update-core.php' == $pagenow) {
			// added filter here instead of admin.php because the  jetpack_just_in_time_msgs filter applied in init hook
			add_filter('jetpack_just_in_time_msgs', '__return_false', 20);
		}
	}

	/**
	 * Enables automatic updates for the plugin.
	 *
	 * Enables automatic updates for the plugin..
	 *
	 * @access public
	 * @see __construct
	 * @internal uses auto_update_plugin filter
	 *
	 * @param bool   $update Whether the item has automatic updates enabled
	 * @param object $item   Object holding the asset to be updated
	 * @return bool True of automatic updates enabled, false if not
	 */
	public function maybe_auto_update_plugin($update, $item) {
		if (!isset($item->plugin) || basename(UPDRAFTPLUS_DIR).'/updraftplus.php' !== $item->plugin) return $update;
		$option_auto_update_settings = UpdraftPlus_Options::get_updraft_option('updraft_auto_updates');
		return (1 == $option_auto_update_settings);
	}
	
	/**
	 * WP filter upgrader_source_selection. We use it to tweak the error message shown when an install of a new version is prevented by the existence of an existing version (i.e. us!), to give the user some actual useful information instead of WP's default.
	 *
	 * @param String	  $source		   File source location.
	 * @param String	  $remote_source   Remote file source location.
	 * @param WP_Upgrader $upgrader_object WP_Upgrader instance.
	 * @param Array		  $hook_extra	   Extra arguments passed to hooked filters.
	 *
	 * @return String - filtered value
	 */
	public function upgrader_source_selection($source, $remote_source, $upgrader_object, $hook_extra = array()) {// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Filter use

		static $been_here_already = false;
	
		if ($been_here_already || !is_array($hook_extra) || empty($hook_extra['type']) || 'plugin' !== $hook_extra['type'] || empty($hook_extra['action']) || 'install' !== $hook_extra['action'] || empty($source) || 'updraftplus' !== basename(untrailingslashit($source)) || !class_exists('ReflectionObject')) return $source;
		
		$been_here_already = true;
		
		$reflect = new ReflectionObject($upgrader_object);
		
		$properties = $reflect->getProperty('strings');
		
		if (!$properties->isPublic() || !is_array($upgrader_object->strings) || empty($upgrader_object->strings['folder_exists'])) return $source;

		$upgrader_object->strings['folder_exists'] .= ' '.__('A version of UpdraftPlus is already installed. WordPress will only allow you to install your new version after first de-installing the existing one. That is safe - all your settings and backups will be retained. So, go to the "Plugins" page, de-activate and de-install UpdraftPlus, and then try again.', 'updraftplus');

		return $source;

	}
	
	/**
	 * WordPress filter itsec_scheduled_external_backup - from iThemes Security
	 *
	 * @param Boolean $x - whether a backup is scheduled
	 *
	 * @return Boolean - filtered value
	 */
	public function itsec_scheduled_external_backup($x) {// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Filter use
		return wp_next_scheduled('updraft_backup') ? true : false;
	}
	
	/**
	 * WordPress filter itsec_external_backup_link - from iThemes security
	 *
	 * @param String $x - link
	 *
	 * @return String - filtered value
	 */
	public function itsec_external_backup_link($x) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Filter use
			return UpdraftPlus_Options::admin_page_url().'?page=updraftplus';
	}

	/**
	 * This method will disconnect UpdraftVault accounts.
	 *
	 * @return Array - returns the saved options if an error is encountered.
	 */
	public function wp_loaded_vault_disconnect() {
		$opts = UpdraftPlus_Storage_Methods_Interface::update_remote_storage_options_format('updraftvault');
			
		if (is_wp_error($opts)) {
			if ('recursion' !== $opts->get_error_code()) {
				$msg = "UpdraftVault (".$opts->get_error_code()."): ".$opts->get_error_message();
				$this->log($msg);
				error_log("UpdraftPlus: $msg");
			}
			// The saved options had a problem; so, return the new ones
			return $opts;
		} elseif (!empty($opts['settings'])) {

			foreach ($opts['settings'] as $instance_id => $storage_options) {
				if (!empty($storage_options['token']) && $storage_options['token']) {
					$site_id = $this->siteid();
					$hash = hash('sha256', $site_id.':::'.$storage_options['token']);
					if ($hash == $_POST['reset_hash']) {
						$this->log('This site has been remotely disconnected from UpdraftPlus Vault');
						include_once(UPDRAFTPLUS_DIR.'/methods/updraftvault.php');
						$vault = new UpdraftPlus_BackupModule_updraftvault();
						$vault->ajax_vault_disconnect();
						// Die, as the vault method has already sent output
						die;
					} else {
						$this->log('An invalid request was received to disconnect this site from UpdraftPlus Vault');
					}
				}
				echo json_encode(array('disconnected' => 0));
			}
		}
		die;
	}

	/**
	 * Gets an RPC object, and sets some defaults on it that we always want
	 *
	 * @param  string $indicator_name indicator name
	 * @return array
	 */
	public function get_udrpc($indicator_name = 'migrator.updraftplus.com') {
		if (!class_exists('UpdraftPlus_Remote_Communications')) include_once(apply_filters('updraftplus_class_udrpc_path', UPDRAFTPLUS_DIR.'/includes/class-udrpc.php', $this->version));
		$ud_rpc = new UpdraftPlus_Remote_Communications($indicator_name);
		$ud_rpc->set_can_generate(true);
		return $ud_rpc;
	}

	/**
	 * Ensure that the indicated phpseclib classes are available
	 *
	 * @param String|Array $classes		- a class, or list of classes
	 * @param String|Array $class_paths - paths to include
	 *
	 * @return Boolean|WP_Error
	 */
	public function ensure_phpseclib($classes = array(), $class_paths = array()) {

		$this->no_deprecation_warnings_on_php7();

		if (!empty($classes)) {
			$any_missing = false;
			if (is_string($classes)) $classes = array($classes);
			foreach ($classes as $cl) {
				if (!class_exists($cl)) $any_missing = true;
			}
			if (!$any_missing) return true;
		}

		$ret = true;
		
		// From phpseclib/phpseclib/phpseclib/bootstrap.php - we nullify it there, but log here instead
		if (extension_loaded('mbstring')) {
			// 2 - MB_OVERLOAD_STRING
			// @codingStandardsIgnoreLine
			if (ini_get('mbstring.func_overload') & 2) {
				// We go on to try anyway, in case the caller wasn't using an affected part of phpseclib
				// @codingStandardsIgnoreLine
				$ret = new WP_Error('mbstring_func_overload', 'Overloading of string functions using mbstring.func_overload is not supported by phpseclib.');
			}
		}
		
		if (!empty($class_paths)) {
			$phpseclib_dir = UPDRAFTPLUS_DIR.'/vendor/phpseclib/phpseclib/phpseclib';
			if (false === strpos(get_include_path(), $phpseclib_dir)) set_include_path(get_include_path().PATH_SEPARATOR.$phpseclib_dir);
			if (is_string($class_paths)) $class_paths = array($class_paths);
			foreach ($class_paths as $cp) {
				include_once($phpseclib_dir.'/'.$cp.'.php');
			}
		}
		
		return $ret;
	}

	/**
	 * Ugly, but necessary to prevent debug output breaking the conversation when the user has debug tu