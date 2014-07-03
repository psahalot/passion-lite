<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to passion_comment() which is
 * located in the functions.php file.
 *
 * @package Passion
 * @since Passion 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<section id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			echo _e('Comments', 'passion');
			?>
                </h2><hr>

		<ol class="commentlist">
			<?php wp_list_comments( array( 'callback' => 'passion_comment', 'style' => 'ol' ) ); ?>
		</ol> <!-- /.commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav id="comment-nav-below" class="navigation" role="navigation">
				<h1 class="assistive-text section-heading"><?php esc_html_e( 'Comment navigation', 'passion' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'passion' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'passion' ) ); ?></div>
			</nav>
		<?php endif; // check for comment navigation ?>

	<?php // If comments are closed and there are comments, let's leave a little note.
	elseif ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'passion' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</section> <!-- /#comments.comments-area -->
