<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package BS
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

$block = 'block-bs-plain-card';

// Hook server side rendering into render callback
register_block_type('bonseo/' . $block,
	array(
		'attributes' => array(
			'max_entries' => array(
				'type' => 'string',
			),
			'type' => array(
				'type' => 'string',
			),
			'className' => array(
				'type' => 'string',
			)

		),
		'render_callback' => 'render_bs_plain_card',
	)
);

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function bs_plain_card_editor_assets()
{ // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'bs_plain_card-block-js', // Handle.
		plugins_url('/dist/blocks.build.js', dirname(__FILE__)), // Block.build.js: We register the block here. Built with Webpack.
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);
}

function render_bs_plain_card_entries($authors)
{
	$html = '';
	while ($authors->have_posts()) : $authors->the_post();
		$title = get_the_title();
		$image = esc_url(get_the_post_thumbnail_url(get_the_ID()));
		$link = esc_url(get_the_permalink());
		$html .= '
			<div class="ml-card-sample l-flex l-flex--direction-column l-column--1-3 ml-card-sample--small a-pad">
				<a href="' . $link . '" class="ml-card-sample__title a-bg--dark l-column--1-1">
					<h3 class="a-text  a-text--secondary a-text--center a-pad--y">
						' . $title . '
					</h3>    
				</a>
				<div class="ml-card-sample__container a-bg l-column--1-1">
					<picture class="l-column--1-1 a-pad-0">
						<img class="a-image l-column--1-1 a-pad--y lazy" data-src="' . $image . '">
					</picture>   
				</div>
			</div>';
		unset($post);
	endwhile;
	return $html;
}

function render_bs_plain_card($attributes)
{
	$class = isset($attributes['className']) ? ' ' . $attributes['className'] : '';
	$entries = isset($attributes['max_entries']) ? $attributes['max_entries'] : 3;
	$type = isset($attributes['type']) ? $attributes['type'] : 'posts';
	$args = array(
		'post_type' => $type,
		'post_status' => 'publish',
		'posts_per_page' => $entries
	);
	$posts = new WP_Query($args);
	if (empty($posts)) {
		return "";
	}
	return '
		<div class="og-block-samples l-flex l-flex--wrap a-pad l-flex--justify-center ' . $class . ' ">
			' . render_bs_plain_card_entries($posts) . '
        </div>';
}

add_action('enqueue_block_editor_assets', 'bs_plain_card_editor_assets');
