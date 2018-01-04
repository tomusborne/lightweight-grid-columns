<?php
/*
Plugin Name: Lightweight Grid Columns
Plugin URI: https://generatepress.com
Description: Add columns to your content using easy to use shortcodes.
Version: 1.0
Author: Tom Usborne
Author URI: https://tomusborne.com
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lightweight-grid-columns
*/

define( 'LGC_VERSION', '1.0' );

add_action( 'plugins_loaded', 'lgc_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 0.1
 */
function lgc_load_textdomain() {
	load_plugin_textdomain( 'lightweight-grid-columns' );
}

if ( ! function_exists( 'lgc_shortcodes_register_shortcode' ) ) {
	add_action( 'init', 'lgc_shortcodes_register_shortcode' );
	/*
	 * Declare our shortcode
	 */
	function lgc_shortcodes_register_shortcode() {
		add_shortcode( 'lgc_column', 'lgc_columns_shortcode' );
	}
}

if ( ! function_exists( 'lgc_add_shortcode_button' ) ) {
	add_action( 'admin_init', 'lgc_add_shortcode_button' );
	/*
	 * Set it up so we can register our TinyMCE button
	 */
	function lgc_add_shortcode_button() {
		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// check if WYSIWYG is enabled
		if ( get_user_option( 'rich_editing' ) == 'true') {
			add_filter( 'mce_external_plugins', 'lgc_shortcodes_add_tinymce_plugin' );
			add_filter( 'mce_buttons', 'lgc_shortcodes_register_button' );
		}
	}
}

if ( ! function_exists( 'lgc_shortcodes_add_tinymce_plugin' ) ) {
	/*
	 * Register our tinyMCE button javascript
	 */
	function lgc_shortcodes_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['lgc_shortcodes_button'] = plugins_url( '/js/button.js', __FILE__ );
		return $plugin_array;
	}
}

if ( ! function_exists( 'lgc_shortcodes_register_button' ) ) {
	/*
	 * Register our TinyMCE button
	 */
	function lgc_shortcodes_register_button( $buttons ) {
		array_push( $buttons, 'lgc_shortcodes_button' );
		return $buttons;
	}
}

if ( ! function_exists( 'lgc_translatable_strings' ) ) {
	add_action( 'admin_head','lgc_translatable_strings', 0 );
	/*
	 * Add translatable strings.
	 */
	function lgc_translatable_strings() {
		?>
		<script type="text/javascript">
			var lgc_add_columns = '<?php esc_html_e( 'Add columns', 'lightweight-grid-columns' ); ?>';
			var lgc_columns = '<?php esc_html_e( 'Columns', 'lightweight-grid-columns' ); ?>';
			var lgc_desktop = '<?php esc_html_e( 'Desktop grid percentage', 'lightweight-grid-columns' ); ?>';
			var lgc_tablet = '<?php esc_html_e( 'Tablet grid percentage', 'lightweight-grid-columns' ); ?>';
			var lgc_mobile = '<?php esc_html_e( 'Mobile grid percentage', 'lightweight-grid-columns' ); ?>';
			var lgc_content = '<?php esc_html_e( 'Content', 'lightweight-grid-columns' ); ?>';
			var lgc_last = '<?php esc_html_e( 'Last column in row?', 'lightweight-grid-columns' ); ?>';
		</script>
		<?php
	}
}

if ( ! function_exists( 'lgc_shortcodes_admin_css' ) ) {
	add_action( 'admin_enqueue_scripts', 'lgc_shortcodes_admin_css' );
	/*
	 * Add our admin CSS
	 */
	function lgc_shortcodes_admin_css() {
		wp_enqueue_style( 'lgc-columns-admin', plugins_url('/css/admin.css', __FILE__) );
	}
}

if ( ! function_exists( 'lgc_shortcodes_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'lgc_shortcodes_css', 99 );
	/*
	 * Add the unsemantic framework
	 */
	function lgc_shortcodes_css() {
		wp_enqueue_style( 'lgc-unsemantic-grid-responsive-tablet', plugins_url('/css/unsemantic-grid-responsive-tablet.css', __FILE__), array(), LGC_VERSION, 'all' );
		wp_register_script( 'lgc-matchHeight', plugins_url('/js/jquery.matchHeight-min.js', __FILE__), array( 'jquery' ), LGC_VERSION, true );
	}
}

if ( ! function_exists( 'lgc_columns_shortcode' ) ) {
	/*
	 * Create the output of the columns shortcode
	 */
	function lgc_columns_shortcode( $atts , $content = null ) {
		extract( shortcode_atts(
			array(
				'grid' => '50',
				'tablet_grid' => '50',
				'mobile_grid' => '100',
				'last' => '',
				'class' => '',
				'style' => '',
				'equal_heights' => 'true',
				'id' => ''
			), $atts )
		);

		if ( 'true' == $equal_heights ) {
			wp_enqueue_script( 'lgc-matchHeight' );
		}

		$content = sprintf(
			'<div %9$s class="lgc-column lgc-grid-parent %1$s %2$s %3$s %4$s %5$s"><div %6$s class="inside-grid-column">%7$s</div></div>%8$s',
			'lgc-grid-' . intval( $grid ),
			'lgc-tablet-grid-' . intval( $tablet_grid ),
			'lgc-mobile-grid-' . intval( $mobile_grid ),
			( 'true' == $equal_heights ) ? 'lgc-equal-heights' : '',
			esc_attr( $class ),
			( '' !== $style ) ? ' style="' . esc_attr( $style ) . '"' : '',
			do_shortcode( $content ),
			( 'true' == $last ) ? '<div class="lgc-clear"></div>' : '',
			( '' !== $id ) ? 'id="' . esc_attr( $id ) . '"' : ''
		);

		return force_balance_tags( $content );
	}
}

if ( ! function_exists( 'lgc_columns_helper' ) ) {
	add_filter( 'the_content', 'lgc_columns_helper' );
	/*
	 * Fix the WP paragraph and <br /> issue with shortcodes
	 */
	function lgc_columns_helper( $content ) {
	    $array = array (
	        '<p>[lgc_column' => '[lgc_column',
	        'lgc_column]</p>' => 'lgc_column]',
			'<br />[lgc_column' => '[lgc_column',
	        'lgc_column]<br />' => 'lgc_column]'
	    );

	    return strtr( $content, $array );
	}
}

if ( ! function_exists( 'lgc_ie_compatibility' ) ) {
	add_action( 'wp_head', 'lgc_ie_compatibility' );
	/**
	 * Add compatibility for IE8 and lower
	 * @since 0.3
	 */
	function lgc_ie_compatibility() {
	?>
		<!--[if lt IE 9]>
			<link rel="stylesheet" href="<?php echo plugins_url('/css/ie.min.css', __FILE__); ?>" />
		<![endif]-->
	<?php
	}
}
