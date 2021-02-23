<?php
function events_register_admin_assets(){
    wp_register_style('events_admin_styles', plugins_url('admin/css/admin.css', __DIR__));
    wp_register_script('events_admin_scripts', plugins_url('admin/js/admin.js', __DIR__));
}
function events_register_public_assets(){
    wp_register_style('events_public_styles', plugins_url('public/css/public.css', __DIR__));
}

function events_create_db_table(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'events';

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE " . $table_name . " (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              event_location tinytext NOT NULL,
              event_startDate tinytext NOT NULL,
              event_endDate tinytext NOT NULL,
              UNIQUE KEY id (id)
            );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
function events_read_db_table($id = 0){
    global $wpdb;
    $table_name = $wpdb->prefix . 'events';

    $query = "SELECT * FROM " . $table_name;
    if ($id){
        $query .= " WHERE id=" . $id;
    }
    $result = $wpdb->get_results($query);

    return $result;
}
function events_read_location_db_table($location){
    global $wpdb;
    $table_name = $wpdb->prefix . 'events';

    $query = "SELECT * FROM " . $table_name . " WHERE event_location='" . $location . "'";
    $result = $wpdb->get_results($query);

    return count($result);
}

function events_add_to_db_table($location, $startDate, $endDate){
    global $wpdb;
    $table_name = $wpdb->prefix . 'events';

    $data = ['event_location' => $location, 'event_startDate' => $startDate, 'event_endDate' => $endDate];
    return $wpdb->insert($table_name, $data);
}

function events_admin_menu(){
    add_menu_page(
        esc_html__( 'Welcome to events page', 'events' ),
        esc_html__('Events', 'events'),
        'manage_options',
        'events-options',
        'events_show_content',
        'dashicons-calendar-alt',
        26
    );
}

function wp_ajax_events_add_envent(){
    if (isset($_POST['event_location'])){
        $location = trim($_POST['event_location']);
        $startDate = trim($_POST['event_startDate']);
        $endDate = trim($_POST['event_endDate']);
        if (events_read_location_db_table($location) != 0){
            wp_die('Location must be unique');
        } else {
            if (events_add_to_db_table($location, $startDate, $endDate)){
                wp_die('ok');
            } else {
                wp_die('Error db');
            }
        }
    } else {
        echo 'error';
    }    
}

function events_create_eventList_page(){
    $eventList = array(
        'post_title' => wp_strip_all_tags( 'Event List' ),
        'post_content'  => 'Default content',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'page',
    );
    wp_insert_post($eventList);
}
function events_create_singleEvent_page(){
    $eventList = array(
        'post_title' => wp_strip_all_tags( 'Single Event' ),
        'post_content'  => 'Default content',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'page',
    );
    wp_insert_post($eventList);
}

function events_show_all_events($filter = false){
    $content = "<h2>" . esc_html__( 'All events', 'events' ) . "</h2>                
                <table class='events_table'>
                    <thead>
                        <tr>
                            <td>" . esc_html__( 'Location', 'events' ) . "</td>
                            <td>" . esc_html__( 'Start Data', 'events' ) . "</td>
                            <td>" . esc_html__( 'End Data', 'events' ) . "</td>";
    if ($filter){
        $content .= "<td></td>";
    }

    $content .= "</tr></thead><tbody>";

    $events_read_datas = events_read_db_table();

    $current_date = current_time('Y-m-d', 1);
    $current_date = explode('-', $current_date);
    foreach ($events_read_datas as $events_read_data) {

        if ($filter){
            $event_startDate = strtotime($events_read_data->event_startDate);
            $event_startDate = explode('-', $events_read_data->event_startDate);

            if ($current_date[0] > $event_startDate[0]){
                continue;
            } elseif ($current_date[0] == $event_startDate[0] & $current_date[1] > $event_startDate[1]) {
                continue;
            } elseif ($current_date[0] == $event_startDate[0] & $current_date[1] == $event_startDate[1] & $current_date[2] > $event_startDate[2]) {
                continue;
            }
        }
        $content .= '<tr>';
        $content .= '<td>' . $events_read_data->event_location. '</td>';
        $content .= '<td>' . $events_read_data->event_startDate . '</td>';
        $content .= '<td>' . $events_read_data->event_endDate . '</td>';
        if ($filter){
            $content .= "<td><a target='_blank' href='/single-event?id=". $events_read_data->id ."'>". esc_html__( 'More', 'events' ) ."</a></td>";
        }
        $content .= '</tr>';
    }

    $content .= "</tbody></table>";

    echo $content;
}

function events_show_single_event($id){

    $event_read_data = events_read_db_table($id);
    if(count($event_read_data) == 0){
        $content =  "<p>Data not found</p>";
    } else if (count($event_read_data) > 1){
        $content =  "<p>Please, set event id</p>";
    } else {
        $content = "<h3>" . esc_html__( 'Location', 'events' ) . "</h3> ";
        $content .= "<p>" . $event_read_data[0]->event_location . "</p> ";
        $content .= "<h3>" . esc_html__( 'Start Date', 'events' ) . "</h3> ";
        $content .= "<p>" . $event_read_data[0]->event_startDate . "</p> ";
        $content .= "<h3>" . esc_html__( 'End Date', 'events' ) . "</h3> ";
        $content .= "<p>" . $event_read_data[0]->event_endDate . "</p> ";
        $content .= "<p>More information can be place here</p>";
    }   
    
    echo $content;
}