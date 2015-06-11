<?php
/**
* @package RPG Age and School Year Calculator
*
* @author Cody Williams
* @copyright 2015
* @version 1.1
* @license BSD 3-clause
*/

// If SSI.php is in the same place as this file, and SMF isn't defined, this is being run standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
	
global $smcFunc, $db_prefix, $modSettings, $sourcedir, $boarddir, $settings, $db_package_log, $package_cache;

date_default_timezone_set('GMT');
$defaultdate=date('Y-m-d');
$nextdate = date('Y-m-d',(mktime(0, 0, 0, date("m")+1, date("d"),   date("Y"))));
  
  $defaults = array(
    'rpg_start_date'=> $defaultdate,
    'rpg_end_date'=> $nextdate,
    'rpg_age_school_year_start'=> '09-01',
    'rpg_age_school_year_end'=> '06-31',
    'rpg_age_number_school_years'=> '7',
    'rpg_age_school_year_names'=> 'First Year, Second Year, Third Year, Fourth Year, Fifth Year, Sixth Year, Seventh Year',
    'rpg_age_school_start_age'=> '11',
    'rpg_age_school_age_cut_off'=> '08-31',
    'rpg_age_profile_age'=> '1',
    'rpg_age_menu_settings'=>'0,Age &amp; Year Calculator,profile'
  );
  
  $updates = array(
    'rpg_age_version' => '1.1',
  );
  
  foreach ($defaults as $index => $value)
    if (!isset($modSettings[$index]))
      $updates[$index] = $value;
  
  updateSettings($updates);
  
?>