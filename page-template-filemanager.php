<?php
/*
Template Name: Área do Cliente
*/

/**
 * Scripts do File Manager Plugin
 * Security check. No one can access without Wordpress itself
 * 
 * */
 defined('ABSPATH') or die();
 global $FileManager;
 //auto::  pr($FileManager->options);
 if(!is_array($FileManager->options['file_manager_settings']['language'])) $language_settings = unserialize(stripslashes($FileManager->options['file_manager_settings']['language']));
	 else $language_settings = $FileManager->options['file_manager_settings']['language'];
 //auto::  pr($language_settings);
 if($language_settings['code'] != 'LANG'){
	 $language_code = $language_settings['code'];
	 $lang_file_url = $language_settings['file-url'];
 }
 
 //auto::  if( !current_user_can('manage_options') ) die();
 wp_enqueue_style( 'fmp-jquery-ui-css' );
 wp_enqueue_style( 'fmp-elfinder-css' );
 wp_enqueue_style( 'fmp-elfinder-theme-css' );
 
 wp_enqueue_script('fmp-elfinder-script');
 // Loading lanugage file
 if( isset($lang_file_url) ) wp_enqueue_script('fmp-elfinder-lang', $lang_file_url, array('fmp-elfinder-script'));


get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="entry-title main_title"><?php the_title(); ?></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content">
					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>


<div id='file-manager'>

</div>

<script>

PLUGINS_URL = '<?php echo plugins_url();?>';

jQuery(document).ready(function(){
	jQuery('#file-manager').elfinder({
		url: ajaxurl,
		customData:{action: 'connector', file_manager_security_token: '<?php echo wp_create_nonce( "file-manager-security-token" ); ?>'},
		lang: '<?php if( isset($language_code) ) echo $language_code?>',
		requestType: 'post',
		width: '<?php if(isset($FileManager->options['file_manager_settings']['size']['width'])) echo $FileManager->options['file_manager_settings']['size']['width']; ?>',
		height: '<?php if(isset($FileManager->options['file_manager_settings']['size']['height'])) echo $FileManager->options['file_manager_settings']['size']['height']; ?>',
	});
});

</script>

<?php 

if( isset( $FileManager->options->options['file_manager_settings']['show_url_path'] ) && !empty( $FileManager->options->options['file_manager_settings']['show_url_path']) && $FileManager->options->options['file_manager_settings']['show_url_path'] == 'hide' ){
	
?>
<style>
.elfinder-info-tb > tbody:nth-child(1) > tr:nth-child(2),
.elfinder-info-tb > tbody:nth-child(1) > tr:nth-child(3)
{
	display: none;
}
</style>
<?php
	
}

?>
