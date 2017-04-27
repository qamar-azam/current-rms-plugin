<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.doddletech.com
 * @since      1.0.0
 *
 * @package    D_crms
 * @subpackage D_crms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    D_crms
 * @subpackage D_crms/admin
 * @author     Qamar <qamar065@gmail.com>
 */
class D_crms_Admin {

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

	private $option_name = 'current_rms';
	
    private $product_api_url = 'https://api.current-rms.com/api/v1/products?per_page=0&filtermode=all';



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
		 * defined in D_crms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The D_crms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/d_crms-admin.css', array(), $this->version, 'all' );
		
		/**
		 * Jquery Progress bar CSS
		 */
		wp_enqueue_style( 'jquery-progressbar', ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . '://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
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
		 * defined in D_crms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The D_crms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		/**
		 * Jquery progress bar Js		 		 
		 */
		

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/d_crms-admin.js', array( 'jquery' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));   

		wp_enqueue_script( $this->plugin_name );     


	}

	public function add_menu_page(){
		add_menu_page(
			'Current RMS',
			'Current RMS',
			'manage_options',
			$this->plugin_name,
			array(
				$this,
				'display_menu_page'
			),
			'dashicons-chart-pie',
			75
		);
	}

	public function add_api_sub_menu_page(){

		add_submenu_page( 
			'd_crms',
			'Credentials', 
			'Credentials',
    		'manage_options',
    		$this->plugin_name."_api_sub_page",
    		array(
				$this,
				'display_api_submenu_page'
			)
		);
	}

	

	public function register_setting(){

		add_settings_section(
			$this->option_name . '_general',
			__( '', 'outdated-notice' ),
			'',
			$this->plugin_name."_api_sub_page"
		);
		

		add_settings_field(
			$this->option_name . '_api_key',
			__( 'Current RMS API Key:', 'current-rms' ),
			array( $this, $this->option_name . '_apikey_field' ),
			$this->plugin_name."_api_sub_page",
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_api_key' )
		);

		add_settings_field(
			$this->option_name . '_subdomain',
			__( 'Current RMS subdomain:', 'current-rms' ),
			array( $this, $this->option_name . '_subdomain_field' ),
			$this->plugin_name."_api_sub_page",
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_subdomain' )
		);

		
		register_setting( $this->plugin_name."_api_sub_page", $this->option_name . '_api_key' );
		register_setting( $this->plugin_name."_api_sub_page", $this->option_name . '_subdomain' );

	}



	public function display_api_submenu_page() {
		include_once 'partials/d_crms-admin-display.php';
	}

	function display_menu_page(){
		include_once 'partials/d_crms-admin-page-display.php';
	}

	

	public function current_rms_apikey_field() {
		$api_key_val = get_option( $this->option_name . '_api_key' );		

		echo '<div class="form-field">';
		echo '<input type="text" name="' . $this->option_name . '_api_key' . '" id="' . $this->option_name . '_api_key' . '" 
		value="'.$api_key_val.'">';
		echo '</div>';
	}

	public function current_rms_subdomain_field() {
		$subdomain_val = get_option( $this->option_name . '_subdomain' );

		echo '<div class="form-field">';
		echo '<input type="text" name="' . $this->option_name . '_subdomain' . '" id="' . $this->option_name . '_subdomain' . '" 
		value="'.$subdomain_val.'">';
		echo '</div>';
	}

	public function fetch_rms_porducts( $url, $api_key, $subdomain ) {

        $headers = array(                        
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "X-AUTH-TOKEN : ".$api_key,
            "X-SUBDOMAIN : ".$subdomain
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            return "Error: " . curl_error($ch);
        } else {
            return json_decode( $data );
            curl_close($ch);
        }

    }

    public function processes_product_data(){


    	if ( !wp_verify_nonce( $_REQUEST['nonce'], "insert_rms_products")) {
      		exit("No naughty business please");
   		} 

    	$product = $_POST['product_data'];    	

		$args = array(
	            'fields' 	=> 'ids',
	            'post_type' => 'product',
	            'meta_key'  => 'crms_product_id',
	        	'meta_value'=> $product['id']
	        );
        
        $query = new WP_QUERY( $args );  

        if( $query->have_posts() ){
            $woo_product_id = $query->posts[0];
            
            $this->create_woo_product( $woo_product_id, $product );
        }
        else{        	
            $this->create_woo_product( 0, $product );
        }
        
        return $product;


        die();
    }


    public function create_woo_product( $id, $product ){

    	global $wpdb;

    	$post = array(
    		'ID'		   => $id,
	        'post_title'   => $product['name'],
	        'post_content' => $product['description'],
	        'post_status'  => "publish",	        	       
	        'post_type'    => "product"
	    );

	    $new_product_id 	= wp_insert_post( $post, $wp_error );
	    $post_thumbnail_id  = get_post_thumbnail_id( $id ); 

	    update_post_meta( $new_product_id, '_regular_price',  $product['rental_rate']['price'] );
	    update_post_meta( $new_product_id, '_weight',  		  $product['weight'] );
	    update_post_meta( $new_product_id, 'crms_product_id', $product['id'] );	    


	    $term = term_exists( $product['custom_fields']['parent_category'], 'product_cat' );	    
	    
	    // Creating Parent Cateogry 

	    if( $term == 0 ){
	    	
	    	$new_term_id = wp_insert_term( $product['custom_fields']['parent_category'], 'product_cat' );
    		wp_set_object_terms( $new_product_id, $new_term_id, 'product_cat' );
    	}else{    		
    		
    		
    		$term_ids = array_map( 'intval', array( $term['term_id'] ) );
			
    		wp_set_object_terms( $new_product_id, $term_ids, 'product_cat' );
    	}

    	// Creating Child Cateogry 
    	if( $product['custom_fields']['sub_category'] ){

    		$sub_term = term_exists( $product['custom_fields']['sub_category'], 'product_cat' );

    		if( $sub_term == 0 ){
    			
    			$new_sub_term_id = wp_insert_term( $product['custom_fields']['sub_category'], 'product_cat', array( 'parent' => $term['term_id']) );
    			wp_set_object_terms( $new_product_id, $new_sub_term_id, 'product_cat' );

    		}else{

    			$term_ids = array_map( 'intval', array( $sub_term_id['term_id'] ) );
    			wp_set_object_terms( $new_product_id, $term_ids, 'product_cat' );
    		}

    	}

	    

	    // checking product already have featured image or not 

	    if( $post_thumbnail_id ):
	    	wp_delete_attachment( $post_thumbnail_id, true);
	    endif;	    

	    $image_src = media_sideload_image( $product['icon']['url'], $new_product_id, '', 'src' );

	    $image_id = $this->get_image_id( $image_src );

	    set_post_thumbnail($new_product_id, $image_id);

    }
    

    public function get_image_id($image_url) {
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
	    
	    return $attachment[0]; 
	}
    

    public function get_product_api_url(){
    	return $this->product_api_url;
    }


    function run_import_script(){
    	$subject = "Cron job";
    	$message = "Hello, from RMS";

    	die("buyuk");
    	
    	mail( 'andy.maray@gmail.com', $subject, $message );

    	die("buyuk");
    }

    public function cron_add_minute( $schedules ){

		$schedules['weekly'] = array(
	        'interval' => 30, // 1 week in seconds
	        'display'  => __( 'Once Weekly' ),
	    );
 
    return $schedules;

		
	} 
}
