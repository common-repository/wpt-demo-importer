<?php
/**
 * The plugin page view - the "settings" page of the plugin.
 *
 * @package PADEPRE
 */

namespace PADEPRE;

$predefined_themes = $this->import_files;

if ( ! empty( $this->import_files ) && isset( $_GET['import-mode'] ) && 'manual' === $_GET['import-mode'] ) {
	$predefined_themes = array();
}

$theme = wp_get_theme();
if ( 'PrimaApp' == $theme->name || 'PrimaApp' == $theme->parent_theme ) {

	// update 1.1
	do_action( 'pt-PADEPRE/plugin_page_header' );

?>

<div class="PADEPRE  wrap  about-wrap">

	<?php ob_start(); ?>
		<h1 class="PADEPRE__title  dashicons-before"><?php esc_html_e( 'WPT Demo Importer', 'pt-PADEPRE' ); ?></h1>
	<?php
	$plugin_title = ob_get_clean();

	// Display the plugin title (can be replaced with custom title text through the filter below).
	echo wp_kses_post( apply_filters( 'pt-PADEPRE/plugin_page_title', $plugin_title ) );

	// Display warrning if PHP safe mode is enabled, since we wont be able to change the max_execution_time.
	if ( ini_get( 'safe_mode' ) ) {
		printf(
			esc_html__( '%sWarning: your server is using %sPHP safe mode%s. This means that you might experience server timeout errors.%s', 'pt-PADEPRE' ),
			'<div class="notice  notice-warning  is-dismissible"><p>',
			'<strong>',
			'</strong>',
			'</p></div>'
		);
	}

	// Start output buffer for displaying the plugin intro text.
	ob_start();
	?>


	<?php
	$plugin_intro_text = ob_get_clean();

	// Display the plugin intro text (can be replaced with custom text through the filter below).
	echo wp_kses_post( apply_filters( 'pt-PADEPRE/plugin_intro_text', $plugin_intro_text ) );
	?>

	<?php if ( empty( $this->import_files ) ) : ?>

	<?php endif; ?>

	<?php if ( empty( $predefined_themes ) ) : ?>

	<?php elseif ( 0 === count( $predefined_themes ) ) : ?>

	<?php else : ?>

		<!-- PADEPRE grid layout -->
		<div class="PADEPRE__gl  js-PADEPRE-gl">
		<?php
			// Prepare navigation data.
			$categories = Helpers::get_all_demo_import_categories( $predefined_themes );
		?>

			<br><br>
			<div class="PADEPRE__gl-item-container  wp-clearfix  js-PADEPRE-gl-item-container">
				<?php foreach ( $predefined_themes as $index => $import_file ) : ?>
					<?php
						// Prepare import item display data.
						$img_src = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';
						// Default to the theme screenshot, if a custom preview image is not defined.
						if ( empty( $img_src ) ) {
							$theme = wp_get_theme();
							$img_src = $theme->get_screenshot();
						}

					?>
					<div class="PADEPRE__gl-item js-PADEPRE-gl-item" data-categories="<?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file ) ); ?>" data-name="<?php echo esc_attr( strtolower( $import_file['import_file_name'] ) ); ?>">
						<div class="PADEPRE__gl-item-image-container">
							<?php if ( ! empty( $img_src ) ) : ?>
								<img class="PADEPRE__gl-item-image" src="<?php echo esc_url( $img_src ) ?>">
							<?php else : ?>
								<div class="PADEPRE__gl-item-image  PADEPRE__gl-item-image--no-image"><?php esc_html_e( 'No preview image.', 'pt-PADEPRE' ); ?></div>
							<?php endif; ?>
						</div>
						<div class="PADEPRE__gl-item-footer<?php echo ! empty( $import_file['preview_url'] ) ? '  PADEPRE__gl-item-footer--with-preview' : ''; ?>">
							<h4 class="PADEPRE__gl-item-title" title="<?php echo esc_attr( $import_file['import_file_name'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></h4>
							<button class="PADEPRE__gl-item-button  button  button-primary  js-PADEPRE-gl-import-data" value="<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Import', 'pt-PADEPRE' ); ?></button>
							<?php if ( ! empty( $import_file['preview_url'] ) ) : ?>
								<a class="PADEPRE__gl-item-button  button" href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'pt-PADEPRE' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div id="js-PADEPRE-modal-content"></div>

	<?php endif; ?>

	<p class="PADEPRE__ajax-loader  js-PADEPRE-ajax-loader">
		<span class="spinner"></span> <?php esc_html_e( 'Importing, please wait!', 'pt-PADEPRE' ); ?>
	</p>

	<div class="PADEPRE__response  js-PADEPRE-ajax-response"></div>
</div>
<?php
}
else{
	?>
	<div class="wrap">
		<h1>Your active theme is NOT supported for this plugin. Please Activate one of supported Themes</h1>
	</div>
	<?php
}

?>