<?php

	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php esc_attr_e('This post is password protected. Enter the password to view comments.','traveline'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<div class="comment_part">
	<h4><?php	printf( _n( 'One Comment to %2$s', '%1$s Coments%2$s', get_comments_number() ),
									number_format_i18n( get_comments_number() ), '' ); ?></h4></div>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<ul class="col-sm-12 blg-sin-post-cmntpart2">
	<?php wp_list_comments('callback=mi_comment');?>
	</ul>

	
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.','traveline'); ?></p>

	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div class="comments-form">

<h3 class="block-title"><?php comment_form_title( __('Leave a Comments','traveline'), __('Leave A Comment to %s','traveline' ) ); ?></h3>

<div id="cancel-comment-reply">
	<small><?php cancel_comment_reply_link() ?></small>
</div>

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url( get_permalink() )); ?></p>
<?php else : ?>

<form action="<?php echo site_url(); ?>/wp-comments-post.php" method="post" class="contact-form" id="commentform">

<?php if ( is_user_logged_in() ) : ?>

<p><?php printf(__('Logged in as <a href="%1$s">%2$s</a>.'), get_edit_user_link(), $user_identity); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php esc_attr_e('Log out of this account'); ?>"><?php _e('Log out &raquo;','traveline'); ?></a></p>

<?php else : ?>

	<div class="form-group">
<input class="form-control"  placeholder="Name" type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
</div>	
	
<div class="form-group">
<input class="form-control"  placeholder="E-mail" type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
</div>
	


<div class="form-group">	
<input class="form-control"  placeholder="Website" type="text" name="url" id="url" value="<?php echo  esc_attr($comment_author_url); ?>" size="22" tabindex="3" />

	</div>


<?php endif; ?>

<!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>','traveline'), allowed_tags()); ?></small></p>-->

	<div class="form-group">
<textarea class="form-control" rows="6" name="comment" id="comment" cols="100" tabindex="4" placeholder="<?php echo __('Message...', 'traveline'); ?>"></textarea>
</div>	



	<div class="form-group">
<input class="button" name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e('Send Comment'); ?>" />
<?php comment_id_fields(); ?>

	</div>


<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>
