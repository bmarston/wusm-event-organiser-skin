<?php
/*
	Template Name: Calendar Page
*/
    get_header();

    if (have_posts()) :
        while (have_posts()) :
            the_post();
            $class = 'calendar';
            if (get_the_post_thumbnail() != '') {
                $class .= ' notch';
                echo '<div id="featured-image">';
                the_post_thumbnail();
                echo '</div>';
            }
?>

<div id="main" class="clearfix">

    <div id="page-background"></div>

    <div class="wrapper">

        <?php get_sidebar( 'left' ); ?>

        <article class="<?php echo $class; ?>">

			<?php echo events_header(); ?>
            	<?php
                    echo do_shortcode( "[eo_fullcalendar headerLeft='today' headerCenter='prev,title,next' headerRight='month,agendaWeek,agendaDay']" );

					echo '<aside class="subscribe">';
						echo '<strong>Subscribe</strong> ';
						echo do_shortcode('[eo_subscribe title="Outlook" type="Webcal"]Outlook[/eo_subscribe]') . ' | ';
						echo do_shortcode('[eo_subscribe title="iCal" type="iCal"]iCal[/eo_subscribe]') . ' | ';
						echo do_shortcode('[eo_subscribe title="Google Calendar" type="google"]Google Calendar[/eo_subscribe]');
					echo '</aside>';
                endwhile;
            endif;
            ?>
        </article>

        <!-- No right sidebar -->

    </div>

</div>


<?php get_footer(); ?>