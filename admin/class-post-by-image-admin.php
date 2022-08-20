<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://in.linkedin.com/in/manoj-kumar-kumawat-6bb66117a
 * @since      1.0.0
 *
 * @package    Post_By_Image
 * @subpackage Post_By_Image/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Post_By_Image
 * @subpackage Post_By_Image/admin
 * @author     Manoj Kumawat <manoj@racksoftwares.com>
 */
class Post_By_Image_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	
	private $option_name = 'pbi_setting'; 
	private $folders = [];
	private $parent = [];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_By_Image_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_By_Image_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-by-image-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_By_Image_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_By_Image_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/post-by-image-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_options_post_by_image()
    {
		add_menu_page( "Post By Image", "Post By Image", 'manage_options', $this->plugin_name . '-postbyimage', array( $this, 'post_by_image_add_menu' ),'dashicons-images-alt');
		add_submenu_page($this->plugin_name . '-postbyimage', 'Add Post', 'Add Post', 'manage_options', $this->plugin_name . '-add-post', array($this, 'add_post_by_image') );
    }

	public function post_by_image_add_menu() {
        include( plugin_dir_path( __FILE__ ) . 'partials/post-by-image-admin-display.php' );
    }

	public function add_post_by_image(){
		$path = ABSPATH.get_option( $this->option_name . 'folder_path' );
		$rootFolderName = ucFirst(end(explode('/',$path)));
		$this->folders[$rootFolderName] = $path;
		$folders = $this->getFolderList($path);
		$folderList = [];
		foreach($folders as $folder){
			$folderList[] = str_replace(str_replace(get_option( $this->option_name . 'folder_path' ),'',$path),'',$folder);
		}

		include( plugin_dir_path( __FILE__ ) . 'partials/post-by-image-admin-add.php' );
	}

	public function getFolderList($dir){
		$cdir = scandir($dir);
		foreach ($cdir as $key => $value)
		{
		   	if (!in_array($value,array(".","..")))
		   	{
			  	if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
			  	{
					$this->folders[$value] = $dir . DIRECTORY_SEPARATOR . $value;
					$this->getFolderList($dir . DIRECTORY_SEPARATOR . $value);
			  	}
		   	}
		}
		return $this->folders;
	}

	public function getFilesWithCategory($files,$folderList,$status){
		foreach($files as $key => $file){
            if(is_array($file)){
                $this->getFilesWithCategory($file,$folderList,$status);
            }
			else{
				$categoryIDs = [];
				$file_url = '';
				$fileInfo = pathinfo($file);
				$title = $fileInfo['filename'];
				foreach($folderList as $list){
					$path = ABSPATH.''.$list.'/'.$file;
					if(file_exists($path)){
						$file_url = $path;
						$category = array_diff(explode('\\',$list),['']);
						$parent = 0;
						foreach($category as $key => $cate){
							//check category exists
							$checkCategory = get_category_by_slug(strtolower($cate));
							
							if(isset($checkCategory) && !empty($checkCategory)){
								$parent = $checkCategory->cat_ID;
							}

							if($parent > 0){
								$category_parent = $parent;
							}else{
								$category_parent = '';
							}
							// Create the category
							if(!$checkCategory){
								$wpdocs_cat = array(
									'cat_name' => ucFirst($cate),
									'category_description' => ucFirst($cate),
									'category_nicename' => strtolower($cate),
									'category_parent' => $category_parent
								);
								$wpdocs_cat_id = wp_insert_category($wpdocs_cat);
							}else{
								$wpdocs_cat_id = $checkCategory->cat_ID;
							}
							$parent = $wpdocs_cat_id;
							$categoryIDs[] = $wpdocs_cat_id;
						}
					}
				}
				
				$args=array(
					's'           => $title,
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'order'          => 'Desc',
					'posts_per_page' => 1
				);
				$post = get_posts( $args );
				if(isset($post) && !empty($post)){
					$end = (int) end(explode("-",$post[0]->post_name));
					if(is_numeric($end)){
						$title = rtrim(str_replace("-"," ",str_replace($end,'',$post[0]->post_name))).'-'. ++$end;
					}else{
						$title = str_replace("-"," ",$title).'-1';
					}
				}

				$postArr = [
					'post_title'    => ucFirst(wp_strip_all_tags( $title )),
					'post_content'  => ucFirst($title),
					'post_status'   => $status,
					'post_author'   => get_current_user_id(),
					'post_category' => $categoryIDs
				];
				
				$post_id = wp_insert_post($postArr);
				
				if($file_url != ''){
					$upload = wp_upload_bits($file, null, file_get_contents($file_url, FILE_USE_INCLUDE_PATH));
					$filename = $upload['file'];
					$wp_filetype = wp_check_filetype($filename, null);
					$attachment = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => $title,
						'post_excerpt' => sanitize_text_field( $title ),
						'post_content' => sanitize_text_field( $title ),
						'post_status' => 'inherit'
					);
					
					$attachment_id = wp_insert_attachment( $attachment, $filename, $post_id );
					update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

					if ( ! is_wp_error( $attachment_id ) ) {
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						
						$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
						
						wp_update_attachment_metadata( $attachment_id, $attachment_data );
						
						set_post_thumbnail( $post_id, $attachment_id );
					}
				}
			}
        }
	}
	
	public function dirToArray($dir) {
		$result = array();
		$allowedExt = explode(',',get_option( $this->option_name . 'image_type'));
		$cdir = scandir($dir);
		foreach ($cdir as $key => $value)
		{
		   	if (!in_array($value,array(".","..")))
		   	{
			  	if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
			  	{
					$result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
			  	}
			  	else
			  	{
					$ext = pathinfo($value, PATHINFO_EXTENSION);
					if(in_array($ext, $allowedExt)){
						$result[] = $value;
					}
			  	}
		   	}
		}
		
		return $result;
	}
	
	public function register_post_by_image_setting(){
		add_settings_section(
			$this->option_name. '_general',
			__( 'General', 'pbi' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);
		
		add_settings_field(
			$this->option_name . 'folder_path',
			__( 'Folder path', 'pbi' ),
			array( $this, $this->option_name . '_folder_path_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . 'folder_path' )
		);

		add_settings_field(
			$this->option_name . 'image_type',
			__( 'File Allowed Extention', 'pbi' ),
			array( $this, $this->option_name . '_image_type_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . 'image_type' )
		);
		
		register_setting( $this->plugin_name, $this->option_name . 'folder_path', 'string' );
		register_setting( $this->plugin_name, $this->option_name . 'image_type', 'string' );
	}


	public function pbi_setting_general_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', 'pbi' ) . '</p>';
	} 

	public function pbi_setting_folder_path_cb() {
		$val = get_option( $this->option_name . 'folder_path' );
		?>
			<fieldset>
				<label>
					<input type="test" name="<?php echo $this->option_name . 'folder_path' ?>" id="<?php echo $this->option_name . 'folder_path' ?>" value="<?php echo $val; ?>">
				</label>
			</fieldset>
		<?php
	}

	public function pbi_setting_image_type_cb() {
		$val = get_option( $this->option_name . 'image_type' );
		?>
			<fieldset>
				<label>
					<input type="test" name="<?php echo $this->option_name . 'image_type' ?>" id="<?php echo $this->option_name . 'image_type' ?>" value="<?php echo $val; ?>">
				</label>
				<p>Please add extention with comma seperated like, jpg,png,jpeg</p>
			</fieldset>
		<?php
	}

}
