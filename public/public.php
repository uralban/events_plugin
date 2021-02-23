<?php
add_action('wp_enqueue_scripts','events_register_public_assets');

function events_load_public_assets(){
    wp_enqueue_style('events_public_styles');
}

add_action('wp_enqueue_scripts', 'events_load_public_assets');

add_filter( 'the_content', 'events_the_content_filter' );



function events_the_content_filter($content, $eventList_content = 'Event List', $singleEvent_content = 'Single Event'){

    $current_slug = $GLOBALS['post']->post_name;

    if( $current_slug == 'event-list' ){

        $eventList_content = events_show_all_events(true);
        return $eventList_content;

    } else if( $current_slug == 'single-event' ){

        $singleEvent_content = events_show_single_event($_GET['id']);
        return $singleEvent_content;

    } else {

        return $content;        
    }
}