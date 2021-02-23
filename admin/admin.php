<?php

add_action('admin_menu','events_admin_menu');
add_action('wp_ajax_events_add_envent', 'wp_ajax_events_add_envent');

add_action('admin_enqueue_scripts','events_register_admin_assets');

function events_load_admin_assets($hook){
    if($hook != 'toplevel_page_events-options'){
        return;
    }
    wp_enqueue_style('events_admin_styles');
    wp_enqueue_script('events_admin_scripts');
}

add_action('admin_enqueue_scripts','events_load_admin_assets');

function events_show_content(){
?>
<div class="events-admin-wrap">
    <h1 class="events-admin-header">
        <?php echo esc_html__( 'Events', 'events' ); ?>
    </h1>
    <p>
        
    </p>
    <div class="events-container">
        <div class="events-col-left">
            <div class="events-col-left-wraper">
                <h2>
                    <?php echo esc_html__( 'Add new event', 'events' ); ?>
                </h2>
                <form action="events_test" id="events-add-new-form">
                    <label for="event_location">
                        <?php echo esc_html__( 'Location', 'events' ); ?>
                    </label>
                    <input type="text" name="event_location" required>

                    <label for="event_startDate">
                        <?php echo esc_html__( 'Satrt Date', 'events' ); ?>
                    </label>
                    <input type="date" name="event_startDate" required>

                    <label for="event_endDate">
                        <?php echo esc_html__( 'End Date', 'events' ); ?>
                    </label>
                    <input type="date" name="event_endDate" required>

                    <input type="submit" value="<?php echo esc_html__( 'Create Event', 'events' ); ?>">
                </form>
                <div class="events-add-result-message"></div>
            </div>            
        </div>
        <div class="events-col-right">
            <div class="events-col-right-wraper">
                <?php events_show_all_events() ?>
            </div>            
        </div>
    </div>
</div>


<?php
}