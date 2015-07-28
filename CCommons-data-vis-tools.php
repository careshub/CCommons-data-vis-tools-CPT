<?php

/*
Plugin Name: Community Commons Data Vis Tools CPT
Plugin URI: 
Description: Creates and enables Data Vis Tools for Community Commons
Author: David Cavins
Version: 0.2
*/

/*Some Set-up*/
define( 'CCDVT_PATH', plugin_dir_url( __FILE__ ) );
define('CCDVT_NAME', "Community Commons Data Vis Tools CPT");
define ("CCDVT_VERSION", "0.2");

/*Files to Include*/
// Definition of custom post type
require_once('cpt-data-vis-tools.php');

/*Add the Javascript/CSS Files!*/
function ccdvt_enqueue_scripts() {
// wp_enqueue_script('flexslider', EFS_PATH.'jquery.flexslider-min.js', array('jquery'));
// wp_enqueue_style('ccommons_flexslider_css', EFS_PATH.'ccommons-flexslider.css');
}
// add_action('wp_enqueue_scripts', 'ccdvt_enqueue_scripts');

function ccdvt_enqueue_admin_styles() {
// wp_enqueue_style('ccommons_flexslider_admin_css', EFS_PATH.'ccommons-flexslider-admin.css');
}
// add_action('admin_print_styles', 'ccdvt_enqueue_admin_styles');


function ccdvt_get_tools() {
	//Get an array of all categories
	$args = array(
		'taxonomy' => 'data_vis_tool_categories'
	);
	$categories = get_categories( $args );

	// Set up an array to remember the posts we've already used.
	$do_not_duplicate = array();

	$ccdtv_tool_group = '';

	foreach ( $categories as $cat ) {
		//What color should this block be?
		// New terms: economics, education, environment, equity, food, health
		switch ( $cat->slug ) {
			case 'community-context':
			case 'education':
			case 'equity': // Replaces "community context"
				$color = 'accent-yellow';
				break;
			case 'economics':
				$color = 'accent-green';
				break;
			case 'environment':
			case 'health-2':
			case 'health':
				$color = 'accent-blue';
				break;
			case 'food':
				$color = 'accent-red';
				break;
			default:
				$color = 'acccent-blue';
				break;
		}

		?>
		<div id="<?php echo $cat->slug; ?>" class="tool-group <?php echo $color; ?>">
			<header class="entry-header clear">
				<h1 class="entry-title"><?php echo $cat->name;	?></h1>
				<?php 
					//Put them all together for the Taxonomy Images plugin
					$combined_term_args = array(
						'term_args' => array( 'slug' => $cat->slug ),
						'taxonomy' => 'data_vis_tool_categories'
						);
					?>
				<div class="tool-group-header clear">
					<?php 
					$terms = apply_filters( 'taxonomy-images-get-terms', '', $combined_term_args );  
					if ( ! empty( $terms ) ) {
						echo wp_get_attachment_image( current( $terms )->image_id, 'full' );
					}
					?>
					<p class="data-vis-tool-description">
						<?php echo $cat->description; ?>
					</p>
				</div>
			</header>
			<?php
			//Get the featured tools for a category

		    $featured_args =  array( 
				'post_type' => 'data_vis_tool',
				// 'posts_per_page' => $max_number_of_featured,
				'data_vis_tool_categories' => $cat->slug,
				'meta_query' => array(
					array(
						'key' => 'ccdvt_check_featured',
						'value' => 'on',
						'compare' => '=',
						)
					)
				);

			$ccdtv_featured_tool = new WP_Query( $featured_args );
			if ( $ccdtv_featured_tool->have_posts() ) :		
				while ( $ccdtv_featured_tool->have_posts() ) : $ccdtv_featured_tool->the_post();
					global $post;
					$do_not_duplicate[] = $post->ID;
					ccdvt_the_dvt_item( $post );
				endwhile;		
			wp_reset_query();
		    endif;

			//Next, get the other tools in the category
		    $args =  array( 
				'post_type' => 'data_vis_tool',
				// 'posts_per_page' => $max_number_of_featured,
				'data_vis_tool_categories' => $cat->slug,
				'post__not_in' => $do_not_duplicate,
				);

			$ccdtv_tools = new WP_Query( $args );
			if ( $ccdtv_tools->have_posts() ) :			
				while ( $ccdtv_tools->have_posts() ) : $ccdtv_tools->the_post();
					global $post;
					$do_not_duplicate[] = $post->ID;
					ccdvt_the_dvt_item( $post );
				endwhile;		
			wp_reset_query();
		    endif;
	    	?>
	    </div><!-- End data-vis-tool-group -->
    <?php  
    }

} //end ccdvt_get_tools

function ccdvt_the_dvt_item( $post ){
	$values = get_post_custom( $post->ID );
	$tool_link = isset( $values['ccdvt_link'] ) ? ( $values['ccdvt_link'][0] ) : '';
	$tool_type = isset( $values['ccdvt_tool_type'] ) ? esc_attr( $values['ccdvt_tool_type'][0] ) : '';
	?>
	<div class="data-vis-tool quarter-block <?php echo $tool_type; ?>">
		<header class="entry-header clear">
			<span class="icon <?php echo $tool_type . 'x24'; ?>"></span>
			<h3 class="entry-title"><a href="<?php echo $tool_link; ?>" title="Link to the map tool" rel="bookmark"><?php the_title(); ?></a></h3>
		</header>
		<div class="entry-content">
		<?php the_content(); ?>
		</div>
	</div>
	<?php 
}


/** Admin UI Area *************************************/
add_action( 'add_meta_boxes', 'ccdvt_meta_box_add' );
function ccdvt_meta_box_add() {
	add_meta_box( 'ccdvt-meta-box', 'Data Vis Tool Info', 'data_vis_tool_meta_box', 'data_vis_tool', 'normal', 'high' );
}

function data_vis_tool_meta_box( $post )
{
	$values = get_post_custom( $post->ID );
	$link = isset( $values['ccdvt_link'] ) ? esc_attr( $values['ccdvt_link'][0] ) : '';
	$widget = isset( $values['ccdvt_widget'] ) ? esc_attr( $values['ccdvt_widget'][0] ) : '';
	$check_featured = isset( $values['ccdvt_check_featured'] ) ? esc_attr( $values['ccdvt_check_featured'][0] ) : '';
	$tool_type = isset( $values['ccdvt_tool_type'] ) ? esc_attr( $values['ccdvt_tool_type'][0] ) : '';
	wp_nonce_field( 'data-vis-tool-meta-box', 'meta_box_nonce' );
	?>

	<p style="margin-top:.2em;">
		<input type="checkbox" name="ccdvt_check_featured" id="ccdvt_check_featured" <?php checked( $check_featured, 'on' ); ?> />
		<label for="ccdvt_check_featured">Feature this tool in its category group</label>
	</p>
	<p style="margin-top:2em;">
		<label for="ccdvt_link">Link to open map</label>
		<input type="text" name="ccdvt_link" id="ccdvt_link" value="<?php echo $link; ?>" style="width:100%"/>
		<em>This value should look like http://datavis...</em>
	</p>
	<p style="margin-top:2em;">
		<label for="ccdvt_widget">Widget to show map image</label>
		<input type="text" name="ccdvt_widget" id="ccdvt_widget" value="<?php echo $widget; ?>" style="width:100%"/>
		<em>This value should look like &lt;script&gt;... </em>
	</p>
	<fieldset style="margin-top:2em;">
		<legend style="margin-bottom:.5em">Type of tool</legend>
		<input type="radio" name="ccdvt_tool_type" id="ccdvt_tool_type_map" value="map"<?php checked( 'map' == $tool_type); ?> /><label for="ccdvt_tool_type_map">Map </label><br />
		<input type="radio" name="ccdvt_tool_type" id="ccdvt_tool_type_report" value="report"<?php checked( 'report' == $tool_type); ?> /><label for="ccdvt_tool_type_report">Report </label><br />
		<input type="radio" name="ccdvt_tool_type" id="ccdvt_tool_type_collaboration" value="collaboration"<?php checked( 'collaboration' == $tool_type); ?> /><label for="ccdvt_tool_type_collaboration">Collaborative tool </label>
	</fieldset>
		<?php	
}


add_action( 'save_post', 'data_vis_tool_meta_box_save' );
function data_vis_tool_meta_box_save( $post_id )
{
	// // Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
// 	
	// // if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'data-vis-tool-meta-box' ) ) 
		return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) 
		return;
	
	// now we can actually save the data
	$allowed = array( 
		'a' => array( // only allow a tags
			'href' => array() // and those anchors can only have href attribute
		),
		'br' => array(),
		'em' => array(),
		'strong' => array(),
		'script' => array(
			'src' =>array()
			)
	);

	// Probably a good idea to make sure your data is set
	if( isset( $_POST['ccdvt_link'] ) )
		update_post_meta( $post_id, 'ccdvt_link', esc_url( $_POST['ccdvt_link'] ) );
	if( isset( $_POST['ccdvt_widget'] ) )
		update_post_meta( $post_id, 'ccdvt_widget', $_POST['ccdvt_widget'] );		
	/*if( isset( $_POST['my_meta_box_select'] ) )
		update_post_meta( $post_id, 'my_meta_box_select', esc_attr( $_POST['my_meta_box_select'] ) );
		*/
		
	// Saving checkboxes
	$chk = ( isset( $_POST['ccdvt_check_featured'] ) && $_POST['ccdvt_check_featured'] ) ? 'on' : 'off';
	update_post_meta( $post_id, 'ccdvt_check_featured', $chk );

	//Saving radio buttons
    $allowed_radio_options = array('map','report','collaboration');

    if( isset( $_POST['ccdvt_tool_type'] ) && in_array($_POST['ccdvt_tool_type'], $allowed_radio_options))
        update_post_meta( $post_id, 'ccdvt_tool_type',  $_POST['ccdvt_tool_type'] );
}

function data_vis_table_display() {
    // add our filter and action on admin_init
    add_filter( 'manage_edit-data_vis_tool_columns', 'ccdvt_edit_columns' );
    add_action( 'manage_data_vis_tool_posts_custom_column', 'ccdvt_custom_columns', 10, 2 );
}
add_action( 'admin_init' , 'data_vis_table_display' );

function ccdvt_edit_columns($columns){

$columns = array(
	'cb' => '<input type="checkbox" />',
	'title' => __( 'Title' ),
    'data_vis_tool_categories' => __( 'Categories' ),
    'featured' => __( 'Featured' ),
    'date' => __( 'Date' )
    );

	return $columns;

}

function ccdvt_custom_columns($column){
        global $post;
        $values = get_post_custom();
        switch ($column) {
            case "featured":
				$check_sticky = isset( $values['ccdvt_check_featured'] ) ? esc_attr( $values['ccdvt_check_featured'][0] ) : '';
				$sticky_status = ( $check_sticky == 'on' ) ? 'Featured' : '';
				echo $sticky_status;              
				break;
			case "data_vis_tool_categories":
				$terms = get_the_terms( get_the_id(), 'data_vis_tool_categories' );
						
				if ( $terms && ! is_wp_error( $terms ) ) {
					$data_vis_terms = array();
					foreach ( $terms as $term ) {
						$data_vis_terms[] = $term->name;
					}
					$cat_header = join( ", ", $data_vis_terms );
					echo $cat_header;
				}
				              
				break;
        }
}