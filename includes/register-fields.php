<?php
/**
* @package JRWDEV Daily Specials
* @since version 1.0
*/

/* ------------------------------------------------------------------
* Do Not Allow Direct Script Access
* --------------------------------------------------------------- */
if (!function_exists ('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

/**
* Register field groups
* The register_field_group function accepts 1 array which holds the relevant data to register a field group
* You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
* This code must run every time the functions.php file is read
*/

if(function_exists("register_field_group")) {
    register_field_group(array (
        'id' => '4fdbc65d0f402',
        'title' => 'Daily Specials',
        'fields' => 
        array (
            0 => 
            array (
                'key' => 'field_4fd8275945a47',
                'label' => 'Weekday',
                'name' => 'weekday',
                'type' => 'select',
                'instructions' => 'Which day(s) of the week does this special run?',
                'required' => '0',
                'choices' => 
                array (
                    'Sunday' => 'Sunday',
                    'Monday' => 'Monday',
                    'Tuesday' => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday',
                    'Friday' => 'Friday',
                    'Saturday' => 'Saturday',
                    ),
                'default_value' => '',
                'allow_null' => '0',
                'multiple' => '1',
                'order_no' => '0',
                ),
            1 => 
            array (
                'key' => 'field_4fd8275947064',
                'label' => 'Price',
                'name' => 'price',
                'type' => 'text',
                'instructions' => 'Enter the sale price if the special is a fixed price. Leave it blank if you want to use the "Deal" field below.',
                'required' => '0',
                'default_value' => '',
                'formatting' => 'none',
                'order_no' => '1',
                ),
            2 => 
            array (
                'key' => 'field_4fd8275943a28',
                'label' => 'Deal',
                'name' => 'deal',
                'type' => 'text',
                'instructions' => 'Enter the deal here if the price field does not fit your needs (e.g. 50% off, BOGO, $3 off, Buy an entree get the appetizer free!).',
                'required' => '0',
                'default_value' => '',
                'formatting' => 'none',
                'order_no' => '2',
                ),
            3 => 
            array (
                'key' => 'field_4fd8275948f84',
                'label' => 'Description',
                'name' => 'description',
                'type' => 'wysiwyg',
                'instructions' => 'Enter detailed information about the item and the special you are running.',
                'required' => '0',
                'toolbar' => 'full',
                'media_upload' => 'yes',
                'order_no' => '3',
                ),
            ),
        'location' => 
        array (
            'rules' => 
            array (
                0 => 
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'daily_specials',
                    'order_no' => '0',
                    ),
                ),
            'allorany' => 'all',
            ),
        'options' => 
        array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' => 
            array (
                0 => 'the_content',
                1 => 'excerpt',
                2 => 'custom_fields',
                3 => 'discussion',
                4 => 'comments',
                5 => 'slug',
                6 => 'author',
                7 => 'format',
                8 => 'featured_image',
                ),
            ),
        'menu_order' => 0,
    ));
}