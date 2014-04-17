<?php
/*
Plugin Name: WUSM Event Organiser Skin
Plugin URI: 
Description: WUSM skin for Event Organiser calendar plugin
Author: Brian H. Marston
Version: 2014.04.16.0
Author URI: http://medicine.wustl.edu/
*/


/*
This plugin assumes the following permalink structure:
Event (single):   calendar/event
Events page:      calendar/list
Venues:           calendar/venues
Event Categories: calendar/category
Event Tags:       calendar/tag

It also assumes the calendar grid is to be shown on a page at /calendar .
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

add_action( 'init', 'github_plugin_updater_wusm_event_organiser_skin_init' );
function github_plugin_updater_wusm_event_organiser_skin_init() {

		if( ! class_exists( 'WP_GitHub_Updater' ) )
			include_once 'updater.php';

		if( ! defined( 'WP_GITHUB_FORCE_UPDATE' ) )
			define( 'WP_GITHUB_FORCE_UPDATE', true );

		if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin

				$config = array(
						'slug' => plugin_basename( __FILE__ ),
						'proper_folder_name' => 'wusm-event-organiser-skin',
						'api_url' => 'https://api.github.com/repos/bmarston/wusm-event-organiser-skin',
						'raw_url' => 'https://raw.github.com/bmarston/wusm-event-organiser-skin/master',
						'github_url' => 'https://github.com/bmarston/wusm-event-organiser-skin',
						'zip_url' => 'https://github.com/bmarston/wusm-event-organiser-skin/archive/master.zip',
						'sslverify' => true,
						'requires' => '3.0',
						'tested' => '3.8',
						'readme' => 'README.md',
						'access_token' => '',
				);

				new WP_GitHub_Updater( $config );
		}

}


class wusm_event_organiser_skin_plugin {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wusm_event_organiser_skin_enqueue' ) );
        add_filter( 'eventorganiser_event_tooltip', array( $this, 'wusm_event_organiser_skin_tooltip' ) );
        // Need to set the priority to beat the Event Organiser plugin to the punch
        add_filter( 'template_include', array( $this, 'get_calendar_template' ), 5 );

        update_option('')
    }

	function wusm_event_organiser_skin_enqueue() {
		wp_enqueue_style( 'wusm-event-organiser-skin-styles', plugins_url('css/style.css', __FILE__) );
		wp_enqueue_script( 'wusm-event-organiser-skin-script', plugins_url('js/functions.js', __FILE__), array( 'jquery' ) );
	}

    function wusm_event_organiser_skin_tooltip( $description, $event_id ) {
        //Change first value and return it
        return $description.'<br><a href="'.get_permalink(get_the_ID()).'">Read more</a>';
    }

    function get_calendar_template( $template ) {
        $calendar_templates_dir = WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__), "", plugin_basename(__FILE__) ) . 'templates/';
        $new_template = '';

        if ( is_page( 'calendar' )  ) {
            $new_template = $calendar_templates_dir . 'page-calendar.php';
        } elseif ( get_post_type() == 'event' && is_single() ) {
            $new_template = $calendar_templates_dir . 'single-event.php';
        } elseif ( is_post_type_archive('event') ) {
            $new_template = $calendar_templates_dir . 'archive-event.php';
        } elseif ( is_tax('event-category') ) {
            $new_template = $calendar_templates_dir . 'taxonomy-event-category.php';
        }

        if ( '' != $new_template ) {
            return $new_template ;
        }

        return $template;
    }

}
new wusm_event_organiser_skin_plugin();





// Used in archive-event.php, page-calendar.php, and taxonomy-event-category.php templates
function events_header() {
    if (is_page('calendar')) {
        $grid = ' class="active"';
        $list = '';
    } else {
        $list = ' class="active"';
        $grid = '';
    }
    ?>
    <header class="events">
        <div class="toggle_links">
            <a href="/calendar/"<?php echo $grid; ?>>Grid <span class="mobilehide">View</span></a>
            <a href="/calendar/list/"<?php echo $list; ?>>List <span class="mobilehide">View</span></a>
        </div>
        <h1>Calendar</h1>
        <aside class="refine">
            <?php
            // event category dropdown
            $categories_list = get_terms( 'event-category');

            //single_cat_title();

            if ($categories_list) {
                echo '<ul class="dd">';
                foreach($categories_list as $key => $cat) {

                    $title = (single_cat_title( '', false ) != '') ? single_cat_title( '', false ) : 'Choose a Category';
                    if ($key == 0) echo '<li class="toggler"><span class="choose">'.$title.'</span><ul>';

                    if (single_cat_title( '', false ) != $cat->name) {
                        echo '<li><a href="/calendar/category/'.$cat->slug.'">'.$cat->name.'</a></li>';
                    } else {
                        echo '<li><a class="close" href="/calendar/list/">'.$cat->name.'</a></li>';
                    }
                }
                echo '</ul></li>';
                echo '</ul><span class="or"> OR </span>';
            }
            ?>
            <form action="<?php echo home_url( '/' ); ?>" method="get" role="search" accept-charset="utf-8" id="event_search_form">
                <input type="hidden" value="event" name="post_type" id="post_type" />
                <p><input type="text" name="s" value="Search calendar" id="s" onfocus="if (this.value == 'Search calendar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search calendar';}" placeholder="Search calendar"><input type="submit" value="Search" id="searchsubmit"></p>
                <a id="mobile_search" href="#">Expand</a>
            </form>
        </aside>
    </header>
<?php
}