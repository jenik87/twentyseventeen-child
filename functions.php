<?php
/**
 * Child theme functions
 */

//Load the parent style.css file
function ts_child_enqueue_parent_theme_style() {

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), filemtime(get_template_directory().'/style.css') );
	wp_enqueue_style( 'ts-google-font', 'https://fonts.googleapis.com/css2?family=Lobster&display=swap', false );

	wp_enqueue_script( 'ts-custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array( 'jquery' ),'',true );

}
add_action( 'wp_enqueue_scripts', 'ts_child_enqueue_parent_theme_style' );

//Option Page
class TwentySeventeenChildOptionPage {
	private $ts_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ts_add_option_page' ) );
		add_action( 'admin_init', array( $this, 'ts_option_page_init' ) );
	}

	public function ts_add_option_page() {
		add_menu_page(
			'Theme Options',
			'Theme Options',
			'manage_options',
			'theme-options',
			array( $this, 'ts_create_option_page' ),
			'dashicons-admin-generic',
			76
		);
	}

	public function ts_create_option_page() {
		$this->ts_options = get_option( 'ts_options' ); ?>

		<div class="wrap">
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'ts_options_group' );
					do_settings_sections( 'ts_options_title' );
					submit_button();
				?>
			</form>
			
		</div>
	<?php }

	public function ts_option_page_init() {
		register_setting(
			'ts_options_group',
			'ts_options',
			array( $this, 'ts_options_sanitize' )
		);

		add_settings_section(
			'ts_setting_section',
			esc_html__( 'Settings', 'twentyseventeen' ),
			array( $this, 'ts_options_section_info' ),
			'ts_options_title'
		);

		add_settings_field(
			'ts_title',
			esc_html__( 'Title (Replaces the title in the site header)', 'twentyseventeen' ),
			array( $this, 'ts_title_callback' ),
			'ts_options_title',
			'ts_setting_section'
		);
	}

	public function ts_options_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['ts_title'] ) ) {
			$sanitary_values['ts_title'] = sanitize_text_field( $input['ts_title'] );
		}

		return $sanitary_values;
	}

	public function ts_options_section_info() {
		
	}

	public function ts_title_callback() {
		printf(
			'<input class="regular-text" type="text" name="ts_options[ts_title]" id="ts_title" value="%s">',
			isset( $this->ts_options['ts_title'] ) ? esc_attr( $this->ts_options['ts_title']) : ''
		);
	}

}
if ( is_admin() )
	$ts_options_page = new TwentySeventeenChildOptionPage();
