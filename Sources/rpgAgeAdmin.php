<?php
/**
* @package RPG Age and School Year Calculator
*
* @author Cody Williams
* @copyright 2015
* @version 1.1
* @license BSD 3-clause
*/

if (!defined('SMF'))
  die('Hacking attempt...');

function rpgAgeAdminMain()
{
  global $context, $txt, $scripturl, $modSettings;

  isAllowedTo('admin_forum');

  // Default text.
  $context['explain_text'] = $txt['rpg_age_desc'];

  // Little short on the ground of functions here... but things can and maybe will change...
  $subActions = array(
    'general' => 'rpgAgeGeneralSettings',
    'menu' => 'rpgAgeMenuSettings',
  );

  $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'events';

  // Set up the two tabs here...
  $context[$context['admin_menu_name']]['tab_data'] = array(
    'title' => $txt['rpg_age'],
    'help' => 'rpg_age_help',
    'description' => $txt['rpg_age_desc'],
    'tabs' => array(
      'general' => array(
        'description' => $txt['rpg_age_general_desc'],
      ),
      'menu' => array(
        'description' => $txt['rpg_age_menu_desc'],
      ),
    ),
  );

  $subActions[$_REQUEST['sa']]();
}

function rpgAgeGeneralSettings($return_config = '')
{
  global $smcFunc, $context, $scripturl, $txt, $sourcedir;
  
  isAllowedTo('rpg_age_edit_settings');

  require_once($sourcedir . '/ManageServer.php');

  loadTemplate('rpgAge');


  $config_vars = array(
      array('text', 'rpg_start_date', 10),
      array('text', 'rpg_end_date', 10),
      '',
      array('text', 'rpg_age_school_year_start', '10', 'help'=>$txt['rpg_age_school_year_start_help']),
      array('text', 'rpg_age_school_year_end', '10', 'help'=>$txt['rpg_age_school_year_end_help']),
      '',
      array('int', 'rpg_age_number_school_years', '3', 'help'=>$txt['rpg_age_number_school_years_help']),
      array('large_text', 'rpg_age_school_year_names', '3', 'help'=>$txt['rpg_age_school_year_names_help']),
      '',
      array('int', 'rpg_age_school_start_age', '3', 'help'=>$txt['rpg_age_school_start_age_help']),
      array('text', 'rpg_age_school_age_cut_off', '10', 'help'=>$txt['rpg_age_school_age_cut_off_help']),
      '',
      array('check', 'rpg_age_profile_age', 'help'=>$txt['rpg_age_profile_age_help']),

      
  );

  if ($return_config)
    return $config_vars;

  if (isset($_GET['save']))
  {
    checkSession();

    saveDBSettings($config_vars);
    redirectexit('action=admin;area=rpg_age;sa=general');
  }

  $context['post_url'] = $scripturl . '?action=admin;area=rpg_age;sa=general;save';
  $context['settings_title'] = $txt['rpg_age_admin_title'];
  $context['page_title'] = $txt['rpg_age_admin_title'];
  $context['sub_template'] = 'general_settings';

  prepareDBSettingContext($config_vars);
}


function rpgAgeMenuSettings()
{
global $txt, $modSettings, $context, $scripturl;
loadTemplate('rpgAge');

if (!empty($_POST['save_buttons']))
{
  // Make sure censoring is something they can do.
  checkSession();

  $button_settings = array(
    'button_enabled' => $_POST['button_enabled'],
    'button_title' => $_POST['button_title'],
    'button_sub_button' => $_POST['button_sub_button'],
    );
    
  $updates = array (
    'rpg_age_menu_settings' => implode(',',$button_settings)
    );


  updateSettings($updates);
}

// Set everything up for the template to do its thang.

list ($button_enabled,$button_title,$sub_button) = explode(',',$modSettings['rpg_age_menu_settings']);

$context['rpg_age_button_settings'] = array(
  'button_enabled' => $button_enabled,
  'button_title' => $button_title,
  'button_sub_button' => $sub_button,
);


$context['post_url'] = $scripturl . '?action=admin;area=rpg_age;sa=menu;save';
$context['settings_title'] = $txt['rpg_age_admin_title'];
$context['page_title'] = $txt['rpg_age_admin_title'];
$context['sub_template'] = 'menu_settings';
}

?>