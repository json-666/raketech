<?php
/*
 * Plugin Name: Display JSON file over Shortcode
 * Description: A plugin that works with a shortcode to display data from a JSON file.
 * Version:     1.0
 */

// Stop if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

//Add stylesheets
function raketech_include_styles()
{
    wp_enqueue_style('raketech-table-style', plugin_dir_url(__FILE__) . 'front/raketech.css');
}
add_action('wp_enqueue_scripts', 'raketech_include_styles');


// Register shortcode
add_shortcode('custom_shortcode', 'custom_shortcode_handler');

// Shortcode handler function
function custom_shortcode_handler($atts)
{

    // Parse shortcode attributes
    $args = shortcode_atts(
        array(
            'sorting' => 'a',
        ),
        $atts
    );

    // Load JSON data from file
    $json_file = plugin_dir_path(__FILE__) . 'data.json';
    $json_data = file_get_contents($json_file);

    // Decode JSON data
    $data = json_decode($json_data, true);

    //Check if errors occoured on Decoding JSON
    if (json_last_error() != 0) {
        //Define Error Message for Error prompt
        $error_message = "JSON Parse Error: " . json_last_error_msg();

        //Include front-end for Error prompt
        include plugin_dir_path(__FILE__) . "front/errors-template.php";

        //Return Error prompt in HTML and end program
        return $error;
    }

    //Create simple Array with all brands for convinience while sorting
    $all_brands = array();
    foreach ($data['toplists'] as $toplists) {
        foreach ($toplists as $singleBrand) {
            array_push($all_brands, $singleBrand);
        }
    }

    // Sort data by position or natural sort order
    if ($args['sorting'] == 0) {
        //Sort data by position ascending
        //We are using PHP ver. >= 7.4, therefor we can use such a syntax.
        //Basically, we are comparing between values of $a['position'] and $b['position'] and passing proper value(1,-1,0) to usort function, simple and elegant solution :)

        usort($all_brands, fn($a, $b) => $a['position'] <=> $b['position']);

    } elseif ($args['sorting'] == 1) {
        //Sort data by position descending
        //So, before PHP v. 7.4 I'd do soemthng like that:

        usort($all_brands, function ($a, $b) {
            if ($b['position'] < $a['position']) {
                return -1;
            } elseif ($b['position'] > $a['position']) {
                return 1;
            } else {
                return 0;
            }
        });

    } elseif ($args['sorting'] == 'a') {
        //Sort data by natural sorting on bonus
        //Just inserting proper array fields here 

        usort($all_brands, fn($a, $b) => $a['info']['bonus'] <=> $b['info']['bonus']);

    } else {
        //If improper argument value was provided reutrn HTML Error prompt

        //Define Error Message for Error prompt
        $error_message = "Improper sorting argument. We accept only: <i>0</i> or <i>1</i> or <i>a</i>";

        //Include front-end for Error prompt
        include plugin_dir_path(__FILE__) . "front/errors-template.php";

        //Return Error in HTML and end program
        return $error;
    }

    // Build table HTML
    $html = '
    <table class="table">
        <thead>
            <tr>
                <th>Casino</th>
                <th>Bonus</th>
                <th>Features</th>
                <th>Play</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    ';

    //Loop thru sordet array od brands
    foreach ($all_brands as $single_brand) {

        //Merge features array into list elements string
        $features_text = "";
        foreach ($single_brand['info']['features'] as $single_feature) {
            $features_text .= "<li>" . $single_feature . "</li>";
        }

        //Process HTML Code and fill with proper data
        $html .= '
            <tr class="table__single-row">
                <td class="text-center">
                    <img src="' . $single_brand['logo'] . '" style="width: 100%">
                    <a href="' . get_home_url() . '/' . $single_brand['brand_id'] . '">Review</a>
                </td>
                <td class="text-center table__stars">
                    <link rel="stylesheet"
                        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star-o"></span>
                    <span class="fa fa-star-o"></span>
                    <p>' . $single_brand['info']['bonus'] . '</p>
                </td>
                <td>
                    <ul>
                        ' . $features_text . '
                    </ul>
                </td>
                <td class="text-center">
                    <a href="' . $single_brand['play_url'] . '" class="table__playnow">Play now</a>
                    <p class="table__terms">' . $single_brand['terms_and_conditions'] . '</p>
                </td>
            </tr>
        ';
    }

    $html .= '</table>';

    // Return table HTML
    return $html;
}


// [custom_shortcode sorting="a"]
// [custom_shortcode sorting="0"]