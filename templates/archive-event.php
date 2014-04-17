<?php
    get_header();
	$dates = '';
?>

<div id="main" class="clearfix">
    <div id="page-background"></div>
    <div class="wrapper">

        <?php get_sidebar( 'left' ); ?>

		<article class="calendar list">
				<?php echo events_header(); ?>
				
				<div id="elist">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
					<div class="eventslist">
					<?php
						$postdate = eo_get_the_start('F Y');
						$endpostdate = eo_get_the_end('M d, Y');
						if ($postdate !== $dates) {
							$dates = $postdate;
							echo '<h2 class="sep">'.$dates.'</h2>';
						}
						//echo 'postdate: '.$postdate;
					?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php
		 						if ( eo_is_all_day() ) {
									$microformat = 'Y-m-d';
									$f = ($endpostdate !== $postdate) ? eo_get_the_start('M d, Y').' &ndash; '.$endpostdate : eo_get_the_start('M d, Y'); 
								} else{
									$microformat = 'c';
									$end = (eo_get_the_end(get_option('time_format')) != '') ? ' &ndash; '.eo_get_the_end(get_option('time_format')) : '';
									$f = eo_get_the_start('M d, Y | '.get_option('time_format')).$end;
								}
							?>
							<time itemprop="startDate" datetime="<?php eo_the_start($microformat); ?>"><?php echo $f; ?></time>
							<h3 class="entry-title" style="display: inline;"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
							<?php if (eo_get_venue_name()) echo '<p>'.eo_get_venue_name().'</p>'; ?>
					</div><!-- #post-<?php the_ID(); ?> -->

					</div>
		    		<?php endwhile; ?>

				<?php else : ?>
					<!-- If there are no events -->
					<div id="post-0" class="post no-results not-found">
						<h3 class="entry-title"><?php _e( 'Nothing Found', 'eventorganiser' ); ?></h3>
						<p><?php _e( 'Apologies, but no results were found for the requested archive. ', 'eventorganiser' ); ?></p>
					</div>

					<?php endif; ?>
				</div>
					<?php
						echo '<aside class="subscribe">';
							if ( $wp_query->max_num_pages > 1 ) : ?>
								<nav id="nav-below">
									<div class="nav-next events-nav-newer"><?php next_posts_link( __( 'Load more' , 'eventorganiser' ) ); ?></div>
									<div class="nav-previous events-nav-newer"><?php previous_posts_link( __( 'Earlier events', 'eventorganiser' ) ); ?></div>
								</nav><!-- #nav-below -->
							<?php endif;
							echo '<strong>Subscribe</strong> ';
							echo do_shortcode('[eo_subscribe title="Outlook" type="Webcal"]Outlook[/eo_subscribe]') . ' | ';
							echo do_shortcode('[eo_subscribe title="iCal" type="iCal"]iCal[/eo_subscribe]') . ' | ';
							echo do_shortcode('[eo_subscribe title="Google Calendar" type="google"]Google Calendar[/eo_subscribe]');
						echo '</aside>';
					?>
		</article>
    </div>
</div>
<?php get_footer(); ?>