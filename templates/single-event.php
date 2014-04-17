<?php get_header(); ?>

<div id="main" class="clearfix">
    <div id="page-background"></div>
    <div class="wrapper">
        <?php get_sidebar( 'left' ); ?>


        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <a class="back" href="/calendar/">Back to Calendar</a>
                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="entry-content">
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
                    <?php
                    if (eo_get_venue_name()) {
                        $address = eo_get_venue_address();
                        echo '<p>'.eo_get_venue_name().'<br />'.$address['address'].', '.$address['city'].', '.$address['state'].' '.$address['postcode'].'</p>';
                    }
                    ?>

                    <?php the_content(); ?>

                    <?php

                    $categories_list = get_the_term_list( get_the_ID(), 'event-category', 'Category: ', ' | ','');
                    print_r($categories_list);
                    //eo_get_template_part('event-meta','event-single');

                    echo '</div>';

                    if (eo_get_venue_map()) {
                        echo '<div class="map">'.do_shortcode('[eo_venue_map]').'</div>';
                    }

                    echo '<aside class="subscribe">';
                    echo '<strong>Add to</strong> ';
                    //echo '<a href="'.eo_get_events_feed().'">iCal</a>'.' | ';
                    echo '<a href="'.eo_get_add_to_google_link().'" target="_blank">Google Calendar</a>';
                    echo '</aside>';
                    ?>

            </article><!-- #post-<?php the_ID(); ?> -->
        <?php endwhile; // end of the loop. ?>

    </div>
</div>
<?php get_footer(); ?>
