<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.doddletech.com
 * @since      1.0.0
 *
 * @package    D_crms
 * @subpackage D_crms/admin/partials
 */
?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php 
    
    $credential_error = false;
    $api_key    = get_option( $this->option_name . '_api_key' );
    $subdomain  = get_option( $this->option_name . '_subdomain' );

    if ( !class_exists( 'WooCommerce' ) ) {
        echo '<div class="notice notice-error"><p>Please, install <strong>Woocommerce</strong> plugin first</p></div>';
        $credential_error = true;
    }    
    
    if( empty( $api_key ) ){
        echo '<div class="notice notice-error"><p><strong>API Key</strong> is Required!</p></div>';
        $credential_error = true;
    }

    if( empty($subdomain) ){
        echo '<div class="notice notice-error"><p><strong>Subdomain</strong> is Required!</p></div>';
        $credential_error = true;
    }
?>



<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <p><?php echo esc_html( 'Import Products from the Current RMS' ); ?></p>

    <?php if( $_POST ): ?>
    <div id="progressbar"><div class="progress-label">Loading...</div></div>
    <?php endif; ?>

    <form action="" method="post" id="importdata">
        
        <input type="hidden" name="_wp_http_referer" value="/currentrms/wp-admin/admin.php?page=d_crms">        
            
        <?php 
            if( $credential_error ){
                $args = array('disabled' => true);
                submit_button('Import', 'button-primary', '', '', $args ); 
            }else{
                submit_button('Import'); 
            }
        ?>

    <?php if( $_POST ): ?>

        <p><input type="button" class="button" name="rmsImport-stop" id="rmsImport-stop" value="Abort Importing" /></p>
    <?php endif; ?>
    </form>
   
    <div class="inserted-produts">
        <ul id="rmsproduct-list">            
        </ul>
    </div>
</div>

 <!--script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script-->
 <link rel="stylesheet" href="http//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<?php 

    if( $_POST ){       

        $nonce        = wp_create_nonce("insert_rms_products");
        $rmsProducts  = $this->fetch_rms_porducts( $this->get_product_api_url() , $api_key, $subdomain );    
        $product_data = json_encode( $rmsProducts->products );   


        wp_enqueue_script( 'jquery-ui-progressbar' );       


        //echo "<pre>";
        //echo count( $rmsProducts );         

    ?>

        <script type="text/javascript">
            jQuery(document).ready(function($){
                

                var rms_products        = <?php echo $product_data; ?>;
                var rms_nonce           = "<?php echo $nonce; ?>";
                var rms_loop            = true;
                var rms_count           = 1;
                var rms_total           = rms_products.length;
                var rms_submit_btn      = jQuery( "#submit" );
                var rms_import_stop_btn = jQuery('#rmsImport-stop');
                

                var progressbar = jQuery( "#progressbar" ),
                progressLabel   = jQuery( ".progress-label" );
         
                progressbar.progressbar({
                  value: false,
                  change: function() {
                    progressLabel.text( progressbar.progressbar( "value" ) + "%" );
                  },
                  complete: function() {
                    progressLabel.text( "Complete!" );
                  }
                });
             
                function progress( val ) {
                    progressbar.progressbar( "value", val  );                          
                }

                progress( 0 );

                rms_submit_btn.attr('disabled',true);

                $("#rmsImport-stop").click(function() {
                    rms_loop = false;
                    $('#rmsImport-stop').val("Stopping...");
                });

                // Called after inserting each product
                function rmsProductUpdateStatus( product ) {                    
                    progress( Math.round( ( rms_count / rms_total ) * 1000 ) / 10 );
                    rms_count = rms_count + 1;

                    if( product ){
                        $("#rmsproduct-list").append("<li>" + product.name + "...done</li>");
                    }                  
                }

                function rmsProductFinishUp(){

                    rms_count = rms_count - 1;

                    $("#rmsproduct-list").append("<li>Finished!</li> <br>");
                    $("#rmsproduct-list").append("<li>Total number of products imported: "+rms_count+"</li>");
                }


                function InsertRmsProducts( product ){
                    $.ajax({
                        type: 'POST',
                        url: myAjax.ajaxurl,
                        data: { action: "processes_product_data", product_data: product, nonce: rms_nonce },
                        success: function( response ) {

                            rmsProductUpdateStatus( product );

                            if( rms_loop == false){
                            
                                rms_import_stop_btn.val("Aborted");
                                rms_submit_btn.attr('disabled',false);

                                $("#rmsproduct-list").append("<li>Aborted!</li>");
                            
                            }
                            else if ( rms_products.length && rms_loop ) {

                                InsertRmsProducts( rms_products.shift() );

                            }
                            else {
                                rms_submit_btn.attr('disabled',false);
                                rms_import_stop_btn.fadeOut( "slow" );
                                
                                rmsProductFinishUp();
                            }
                        },
                        error: function( response ) {
                            alert( "Import process interupted!" );                            
                        }
                    });
                }

                InsertRmsProducts( rms_products.shift() );

            });

        </script>

        <?php   
    }
   
?>

