<?php
/**
 * HappyBase functions and definitions
 *
 * @package HappyBase
 * @since HappyBase 1.0
 */

/**
 * Set the content width in pixels, happybased on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function happypress_content_width() {
	$GLOBALS['content_width'] = apply_filters('happypress_content_width', 806);
}
add_action('after_setup_theme', 'happypress_content_width', 0);


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_setup')) {
	function happybase_setup() {
		global $content_width;

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme happybased on HappyBase, use a find and replace
		 * to change 'happybase' to the name of your theme in all the template files
		 */
		load_theme_textdomain('happybase', trailingslashit(get_template_directory()) . 'languages');

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to head
		add_theme_support('automatic-feed-links');

		// Enable support for Post Thumbnails
		add_theme_support('post-thumbnails');

		// Create an extra image size for the Post featured image
		add_image_size('happypress_happybase_theme_post_feature_full_width', 806, 300, true);

		// This theme uses wp_nav_menu() in one location
		register_nav_menus(array(
				'menu-1' => esc_html__('Primary Menu', 'happybase')
			));

		// This theme supports a variety of post formats
		add_theme_support('post-formats', array(
			'aside',
			'audio',
			'chat',
			'gallery',
			'image',
			'link',
			'quote',
			'status',
			'video'
		));

		// Add theme support for HTML5 markup for the search forms, comment forms, comment lists, gallery, and caption
		add_theme_support('html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption'
		));

		// Enable support for widget sidebars selective refresh in the Customizer.
		add_theme_support('customize-selective-refresh-widgets');

		// Enable support for Custom Backgrounds
		add_theme_support('custom-background', array(
				// Background color default
				'default-color' => 'fff'
			));

		// Enable support for Custom Headers (or in our case, a custom logo)
		add_theme_support('custom-header', array(
				// Header text display default
				'header-text' => false,
				// Header text color default
				'default-text-color' => 'fff',
				// Flexible width
				'flex-width' => true,
				// Header image width (in pixels)
				'width' => 1160,
				// Flexible height
				'flex-height' => true,
				// Header image height (in pixels)
				'height' => 280
			));

		// Enable support for Theme Logos
		add_theme_support('custom-logo', array(
			'width'       => 300,
			'height'      => 80,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array('site-title', 'site-description'),
			));

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		// Enable support for WooCommerce
		add_theme_support('woocommerce');
	}
}
add_action('after_setup_theme', 'happybase_setup');



/**
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of Rubik and Comfortaa by default is localized. For languages that use characters not supported by the fonts, the fonts can be disabled.
 *
 * @since HappyBase 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
if ( ! function_exists('happybase_fonts_url')) {
	function happybase_fonts_url() {
		$fonts_url = '';
		$subsets = 'latin';

		/* translators: If there are characters in your language that are not supported by Rubik, translate this to 'off'.
		 * Do not translate into your own language.
		 */
		$bodyFont = _x('on', 'Rubik font: on or off', 'happybase');

		/* translators: To add an additional Rubik character subset specific to your language, translate this to 'greek', 'cyrillic' or 'vietnamese'.
		 * Do not translate into your own language.
		 */
		$subset = _x('no-subset', 'Rubik font: add new subset (cyrillic)', 'happybase');

		if ('cyrillic' == $subset) {
			$subsets .= ',cyrillic';
		}

		/* translators: If there are characters in your language that are not supported by Comfortaa, translate this to 'off'.
		 * Do not translate into your own language.
		 */
		$headerFont = _x('on', 'Comfortaa font: on or off', 'happybase');

		if ('off' !== $bodyFont || 'off' !== $headerFont) {
			$font_families = array();

			if ('off' !== $bodyFont)
				$font_families[] = 'Rubik:400,400i,700,700i';

			if ('off' !== $headerFont)
				$font_families[] = 'Comfortaa:700';

			$query_args = array(
				'family' => implode('|', $font_families),
				'subset' => $subsets,
			);
		  $fonts_url = add_query_arg($query_args, "//fonts.googleapis.com/css");
		}

		return $fonts_url;
	}
}

/**
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @since HappyBase 1.0
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string The filtered CSS paths list.
 */
function happybase_mce_css($mce_css) {
	$fonts_url = happybase_fonts_url();

	if (empty($fonts_url)) {
		return $mce_css;
	}

	if ( ! empty($mce_css)) {
		$mce_css .= ',';
	}

	$mce_css .= esc_url_raw(str_replace(',', '%2C', $fonts_url));

	return $mce_css;
}
add_filter('mce_css', 'happybase_mce_css');

/**
 * Register widgetized areas
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_widgets_init')) {
	function happybase_widgets_init() {
		register_sidebar(array(
				'name' => esc_html__('Main Sidebar', 'happybase'),
				'id' => 'sidebar-1',
				'description' => esc_html__('Appears in the sidebar on all posts and pages', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));

		register_sidebar(array(
				'name' => esc_html__('Blog Sidebar', 'happybase'),
				'id' => 'sidebar-2',
				'description' => esc_html__('Appears in the sidebar on the blog and archive pages only', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));

		register_sidebar(array(
				'name' => esc_html__('Single Post Sidebar', 'happybase'),
				'id' => 'sidebar-3',
				'description' => esc_html__('Appears in the sidebar on single posts only', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));

		register_sidebar(array(
				'name' => esc_html__('Page Sidebar', 'happybase'),
				'id' => 'sidebar-4',
				'description' => esc_html__('Appears in the sidebar on pages only', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));

		register_sidebar(array(
				'name' => esc_html__('First Footer Widget Area', 'happybase'),
				'id' => 'sidebar-footer1',
				'description' => esc_html__('Appears in the footer sidebar', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));

		register_sidebar(array(
				'name' => esc_html__('Second Footer Widget Area', 'happybase'),
				'id' => 'sidebar-footer2',
				'description' => esc_html__('Appears in the footer sidebar', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));

		register_sidebar(array(
				'name' => esc_html__('Third Footer Widget Area', 'happybase'),
				'id' => 'sidebar-footer3',
				'description' => esc_html__('Appears in the footer sidebar', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));

		register_sidebar(array(
				'name' => esc_html__('Fourth Footer Widget Area', 'happybase'),
				'id' => 'sidebar-footer4',
				'description' => esc_html__('Appears in the footer sidebar', 'happybase'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));
	}
}
add_action('widgets_init', 'happybase_widgets_init');

/**
 * Enqueue scripts and styles
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_scripts_styles')) {
	function happybase_scripts_styles() {

		/**
		 * Register and enqueue our stylesheets
		 */

		// Start off with a clean happybase by using normalise.
		wp_enqueue_style('normalize', trailingslashit(get_template_directory_uri()) . 'css/normalize.css', array(), '4.1.1', 'all');

		// Register and enqueue our icon font
		// We're using the awesome Font Awesome icon font. http://fortawesome.github.io/Font-Awesome
		wp_enqueue_style('fontawesome', trailingslashit(get_template_directory_uri()) . 'css/font-awesome.min.css', array('normalize'), '4.6.3', 'all');

		// Our styles for setting up the grid. We're using Unsemantic. http://unsemantic.com
		wp_enqueue_style('unsemanticgrid', trailingslashit(get_template_directory_uri()) . 'css/unsemantic.css', array('fontawesome'), '1.0.0', 'all');

		/*
		 * Load our Google Fonts.
		 *
		 * To disable in a child theme, use wp_dequeue_style()
		 * function mytheme_dequeue_fonts() {
		 *     wp_dequeue_style( 'happypress-fonts' );
		 * }
		 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
		 */
		$fonts_url = happybase_fonts_url();
		if ( ! empty($fonts_url)) {
			wp_enqueue_style('happypress-fonts', esc_url_raw($fonts_url), array(), null);
		}

		// If using a child theme, auto-load the parent theme style.
		// Props to Justin Tadlock for this recommendation - http://justintadlock.com/archives/2014/11/03/loading-parent-styles-for-child-themes
		if (is_child_theme()) {
			wp_enqueue_style('happybase-parent-style', trailingslashit(get_template_directory_uri()) . 'style.css');
		}

		// Enqueue the default WordPress stylesheet
		wp_enqueue_style('happybase-style', get_stylesheet_uri());

		/**
		 * Register and enqueue our scripts
		 */


		// Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use)
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		//vanilla javascript to create the responsive menu.
		wp_enqueue_script('happybase-vendors', trailingslashit(get_template_directory_uri()) . 'js/vendors.min.js', array('jquery'), '1.0.0', false);

	}
}
add_action('wp_enqueue_scripts', 'happybase_scripts_styles');


/**
 * Displays the optional custom logo. If no logo is available, it displays the Site Title
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_the_custom_logo')) {
	function happybase_the_custom_logo() {
		$siteTitleStr = "";

		if (function_exists('the_custom_logo') && has_custom_logo()) {
			the_custom_logo();
		}
		else {
			$siteTitleStr .= '<h1><a href="' . esc_url(home_url('/')) . '" rel="home">';
			$siteTitleStr .= get_bloginfo('name');
			$siteTitleStr .= '</a></h1>';
			echo $siteTitleStr;
		}
	}
}

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own happybase_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 * (Note the lack of a trailing </li>. WordPress will add it itself once it's done listing any children and whatnot)
 *
 * @since HappyBase 1.0
 *
 * @param array Comment
 * @param array Arguments
 * @param integer Comment depth
 * @return void
 */
if ( ! function_exists( 'happybase_comment' ) ) {
	function happybase_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) {
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments ?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="pingback">
					<p><?php esc_html_e( 'Pingback:', 'happybase' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'happybase' ), '<span class="edit-link">', '</span>' ); ?></p>
				</article> <!-- #comment-##.pingback -->
			<?php
				break;
			default :
				// Proceed with normal comments.
				global $post; ?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
					<header class="comment-meta comment-author vcard">
						<?php
							echo get_avatar( $comment, 44 );
							printf( '<cite class="fn">%1$s %2$s</cite>',
								get_comment_author_link(),
								// If current post author is also comment author, make it known visually.
								( $comment->user_id === $post->post_author ) ? '<span> ' . esc_html__( 'Post author', 'happybase' ) . '</span>' : '' );
							printf( '<a href="%1$s"><time itemprop="datePublished" datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* Translators: 1: date, 2: time */
								sprintf( esc_html__( '%1$s at %2$s', 'happybase' ), get_comment_date(), get_comment_time() )
							);
							?>
						</header> <!-- .comment-meta -->

					<?php if ( '0' == $comment->comment_approved ) { ?>
							<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'happybase' ); ?></p>
					<?php } ?>

					<section class="comment-content comment">
						<?php comment_text(); ?>
							<?php edit_comment_link( esc_html__( 'Edit', 'happybase' ), '<p class="edit-link">', '</p>' ); ?>
					</section> <!-- .comment-content -->

					<div class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => wp_kses( __( 'Reply <span>&darr;</span>', 'happybase' ), array( 'span' => array() ) ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div> <!-- .reply -->
				</article> <!-- #comment-## -->
			<?php
				break;
		} // end comment_type check
	}
}

/**
 * Update the Comments form so that the 'required' span is contained within the form label.
 *
 * @since HappyBase 1.0
 *
 * @param string Comment form fields html
 * @return string The updated comment form fields html
 */
if ( ! function_exists('happybase_comment_form_default_fields')) {
	function happybase_comment_form_default_fields($fields) {

		$commenter = wp_get_current_commenter();
		$req = get_option('require_name_email');
		$aria_req = ($req ? ' aria-required="true"' : "");

		$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . esc_html__('Name', 'happybase') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>';

		$fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__('Email', 'happybase') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' . '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>';

		$fields['url'] = '<p class="comment-form-url"><label for="url">' . esc_html__('Website', 'happybase') . '</label>' . '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>';

		return $fields;

	}
}
add_action('comment_form_default_fields', 'happybase_comment_form_default_fields');

/**
 * Update the Comments form to add a 'required' span to the Comment textarea within the form label, because it's pointless
 * submitting a comment that doesn't actually have any text in the comment field!
 *
 * @since HappyBase 1.0
 *
 * @param string Comment form textarea html
 * @return string The updated comment form textarea html
 */
if ( ! function_exists('happybase_comment_form_field_comment')) {
	function happybase_comment_form_field_comment($field) {
		if ( ! happybase_is_woocommerce_active() || (happybase_is_woocommerce_active() && ! is_product())) {
			$field = '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun', 'happybase') . ' <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
		}
		return $field;

	}
}
add_action('comment_form_field_comment', 'happybase_comment_form_field_comment');

/**
 * Prints HTML with meta information for current post: author and date
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_posted_on')) {
	function happybase_posted_on() {
		$post_icon = '';
		switch (get_post_format()) {
			case 'aside':
				$post_icon = 'fa-file-o';
				break;
			case 'audio':
				$post_icon = 'fa-volume-up';
				break;
			case 'chat':
				$post_icon = 'fa-comment';
				break;
			case 'gallery':
				$post_icon = 'fa-camera';
				break;
			case 'image':
				$post_icon = 'fa-picture-o';
				break;
			case 'link':
				$post_icon = 'fa-link';
				break;
			case 'quote':
				$post_icon = 'fa-quote-left';
				break;
			case 'status':
				$post_icon = 'fa-user';
				break;
			case 'video':
				$post_icon = 'fa-video-camera';
				break;
			default:
				$post_icon = 'fa-calendar';
				break;
		}

		// Translators: 1: Icon 2: Permalink 3: Post date and time 4: Publish date in ISO format 5: Post date
			$date = sprintf('<span class="publish-date"><i class="fa %1$s" aria-hidden="true"></i> <a href="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" itemprop="datePublished">%4$s</time></a></span>',
			$post_icon,
			esc_url(get_permalink()),
			esc_attr(get_the_date('c')),
			esc_html(get_the_date())
		);

		// Translators: 1: Date link 2: Author link 3: Categories 4: No. of Comments
		$author = sprintf('<span class="publish-author"><i class="fa fa-pencil" aria-hidden="true"></i> <address class="author vcard"><a class="url fn n" href="%1$s" rel="author">%2$s</a></address></span>',
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			get_the_author()
		);

		// Return the Categories as a list
		$categories_list = get_the_category_list(esc_html__(' ', 'happybase'));

		// Translators: 1: Permalink 2: Title 3: No. of Comments
		$comments = sprintf('<span class="comments-link"><i class="fa fa-comment" aria-hidden="true"></i> <a href="%1$s">%2$s</a></span>',
			esc_url(get_comments_link()),
			(get_comments_number() > 0 ? sprintf(_n('%1$s Comment', '%1$s Comments', get_comments_number(), 'happybase'), get_comments_number()) : esc_html__('No Comments', 'happybase'))
		);

		// Translators: 1: Date 2: Author 3: Categories 4: Comments
		printf(wp_kses(__('<div class="header-meta">%1$s%2$s<span class="post-categories">%3$s</span>%4$s</div>', 'happybase'), array(
			'div' => array(
				'class' => array() ),
			'span' => array(
				'class' => array() ) )),
			$date,
			$author,
			$categories_list,
			(is_search() ? '' : $comments)
		);
	}
}

/**
 * Prints HTML with meta information for current post: categories, tags, permalink
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_entry_meta')) {
	function happybase_entry_meta() {
		// Return the Tags as a list
		$tag_list = "";
		if (get_the_tag_list()) {
			$tag_list = get_the_tag_list('<span class="post-tags">', esc_html__(' ', 'happybase'), '</span>');
		}

		// Translators: 1 is tag
		if ($tag_list) {
			printf(wp_kses(__('<i class="fa fa-tag" aria-hidden="true"></i> %1$s', 'happybase'), array('i' => array('class' => array()))), $tag_list);
		}
	}
}

/**
 * Adjusts content_width value for full-width templates and attachments
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_content_width')) {
	function happybase_content_width() {
		if (is_page_template('full-width.php') || is_attachment()) {
			global $content_width;
			$content_width = 1160;
		}
	}
}
add_action('template_redirect', 'happybase_content_width');

/**
 * Change the "read more..." link so it links to the top of the page rather than part way down
 *
 * @since HappyBase 1.0
 *
 * @param string The 'Read more' link
 * @return string The link to the post url without the more tag appended on the end
 */
function happybase_remove_more_jump_link($link) {
	$offset = strpos($link, '#more-');
	if ($offset) {
		$end = strpos($link, '"', $offset);
	}
	if ($end) {
		$link = substr_replace($link, '', $offset, $end - $offset);
	}
	return $link;
}
add_filter('the_content_more_link', 'happybase_remove_more_jump_link');

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since HappyBase 1.0
 *
 * @return string The 'Continue reading' link
 */
if ( ! function_exists('happybase_continue_reading_link')) {
	function happybase_continue_reading_link() {
		return '&hellip;<p><a class="more-link" href="' . esc_url(get_permalink()) . '">' . wp_kses(__('Continue reading <span class="meta-nav">&rarr;</span>', 'happybase'), array('span' => array(
				'class' => array() ))) . '</a></p>';
	}
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with the happybase_continue_reading_link().
 *
 * @since HappyBase 1.0
 *
 * @param string Auto generated excerpt
 * @return string The filtered excerpt
 */
if ( ! function_exists('happybase_auto_excerpt_more')) {
	function happybase_auto_excerpt_more($more) {
		return happybase_continue_reading_link();
	}
}
add_filter('excerpt_more', 'happybase_auto_excerpt_more');

/**
 * Return a string containing the footer credits & link
 *
 * @since HappyBase 1.0
 *
 * @return string Footer credits & link
 */
if ( ! function_exists('happybase_get_credits')) {
	function happybase_get_credits() {
		$output = '';
		$output = sprintf('<p>%1$s <a href="%2$s">%3$s</a></p>',
			esc_html__('Proudly powered by', 'happybase'),
			esc_url(esc_html__('http://wordpress.org/', 'happybase')),
			esc_html__('WordPress', 'happybase')
		);

		return $output;
	}
}

/**
 * Unhook the WooCommerce Wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Outputs the opening container div for WooCommerce
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_before_woocommerce_wrapper')) {
	function happybase_before_woocommerce_wrapper() {
		echo '<div id="maincontentcontainer">';
		echo '<div id="primary" class="grid-container site-content" role="main">';
	}
}

/**
 * Outputs the closing container div for WooCommerce
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_after_woocommerce_wrapper')) {
	function happybase_after_woocommerce_wrapper() {
		echo '</div> <!-- /#primary.grid-container.site-content -->';
		echo '</div> <!-- /#maincontentcontainer -->';
	}
}

/**
 * Check if WooCommerce is active
 *
 * @since HappyBase 1.0
 *
 * @return boolean
 */
function happybase_is_woocommerce_active() {
	return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
}

/**
 * Check if WooCommerce is active and a WooCommerce template is in use and output the containing div
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_setup_woocommerce_wrappers')) {
	function happybase_setup_woocommerce_wrappers() {
		if (happybase_is_woocommerce_active() && is_woocommerce()) {
				add_action('happybase_before_woocommerce', 'happybase_before_woocommerce_wrapper', 10, 0);
				add_action('happybase_after_woocommerce', 'happybase_after_woocommerce_wrapper', 10, 0);
		}
	}
}
add_action('template_redirect', 'happybase_setup_woocommerce_wrappers', 9);

/**
 * Outputs the opening wrapper for the WooCommerce content
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_woocommerce_before_main_content')) {
	function happybase_woocommerce_before_main_content() {
		if (is_product()) {
			echo '<div class="grid-100">';
		} else {
			echo '<div class="grid-70">';
		}
	}
}
add_action('woocommerce_before_main_content', 'happybase_woocommerce_before_main_content', 10);

/**
 * Outputs the closing wrapper for the WooCommerce content
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_woocommerce_after_main_content')) {
	function happybase_woocommerce_after_main_content() {
		echo '</div>';
	}
}
add_action('woocommerce_after_main_content', 'happybase_woocommerce_after_main_content', 10);

/**
 * Remove the sidebar from the WooCommerce product page
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_remove_woocommerce_sidebar')) {
	function happybase_remove_woocommerce_sidebar() {
		if (is_product()) {
			remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
		}
	}
}
add_action('woocommerce_before_main_content', 'happybase_remove_woocommerce_sidebar');

/**
 * Set the number of products to display on the WooCommerce shop page
 *
 * @since HappyBase 1.0
 *
 * @return void
 */
if ( ! function_exists('happybase_shop_product_count')) {
	function happybase_shop_product_count($numprods) {
		return 12;
	}
}
add_filter('loop_shop_per_page', 'happybase_shop_product_count', 20);

/**
 * Filter the WooCommerce pagination so that it matches the theme pagination
 *
 * @since HappyBase 1.0
 *
 * @return array Pagination arguments
 */
if ( ! function_exists('happybase_woocommerce_pagination_args')) {
	function happybase_woocommerce_pagination_args($paginationargs) {
		$paginationargs['prev_text'] = wp_kses(__('<i class="fa fa-angle-left"></i> Previous', 'happybase'), array('i' => array(
			'class' => array() )));
		$paginationargs['next_text'] = wp_kses(__('Next <i class="fa fa-angle-right"></i>', 'happybase'), array('i' => array(
			'class' => array() )));
		return $paginationargs;
	}
}
add_filter('woocommerce_pagination_args', 'happybase_woocommerce_pagination_args', 10);
