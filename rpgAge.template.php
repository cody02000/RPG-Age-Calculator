<?php
/**
* @package RPG Age and School Year Calculator
*
* @author Cody Williams
* @copyright 2015
* @version 1.1
* @license BSD 3-clause
*/

function template_main()
{

	// Make sure we have all the global variables we need
	global $context, $txt;

	// Catbg header
	echo '<div class="cat_bar">
   			<h3 class="catbg">', $txt['rpg_age'], '</h3>
	</div>';

	// Windowbg2 Content
	echo '<div class="windowbg2">
        <span class="topslice"><span></span></span>
          <div class="content">
		  <form onSubmit="rpgAgeCalculateAge(); return false;">
            <p>'.$txt['rpg_age_current_year'] . $context['rpg_age_school_year']['start'].'-'.$context['rpg_age_school_year']['end'].'.</p>
            <p>'.$txt['rpg_age_calculator_form_description'].'</p>
            <p>'.$txt['rpg_age_birthday_input'].' <input type="text" id="birthYear" maxlength="4" size="6" /> / <input type="text" id="birthMonth" maxlength="2" size="4" /> / <input type="text" id="birthDay" maxlength="2" size="4" /> <input type="submit" value="', $txt['rpg_age_button_calc'], '" class="button_submit" /></p>
			
			<div id="rpg-error" class="error"></div>

            <div id="birthInfo">
              <p>'.$txt['rpg_age_age'].' <span id="ageDisplay"></span></p>
              <p><span id="yearDisplay"></span></p>
            </div>
  		  </form>
  		  </div>
  			<span class="botslice"><span></span></span>
	</div><br />';
	
	// Catbg header
  echo '<div class="cat_bar">
         <h3 class="catbg">', $txt['rpg_age_year_breakdown'], '</h3>
  </div>';

  // Windowbg2 Content
  echo '<div id="detailedinfo" style="width:100%;float:none;">
      <div class="windowbg2">
        <span class="topslice"><span></span></span>
          <div class="content">
          '.$context['rpg_age_year_info'].'
          </div>
        <span class="botslice"><span></span></span>
  </div><br /></div>';

}

function template_general_settings()
 {
   //  Show the confiq_vars.
   template_show_settings();
   
   
   //  Put in a spacer to make it look better.
   echo '
   <br />';

 
 }
 
function template_menu_settings()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  // First section is for adding/removing words from the censored list.
  echo '
  <div id="admincenter">
    <form action="', $scripturl, '?action=admin;area=rpg_age;sa=menu" method="post" accept-charset="', $context['character_set'], '">
      <div class="cat_bar">
        <h3 class="catbg">'.$txt['rpg_age_menu_settings_header'].'</h3>
      </div>
      <div class="windowbg2">
        <span class="topslice"><span></span></span>
        <div class="content">
          <p></p>
    <dl class="settings">
    <dt>
      <a id="setting_button_enabled"></a> <span><label for="button_title">'.$txt['rpg_age_enable_button'].'</label></span>
    </dt>
    <dd>
      <input type="checkbox" name="button_enabled" value="1" id="button_enabled"', empty($context['rpg_age_button_settings']['button_enabled']) ? '' : ' checked="checked"', ' class="input_check" />
      </dd>
      <dt>
        <a id="setting_button_title"></a> <span><label for="button_title">'.$txt['rpg_age_button_title'].'</label></span>
      </dt>
      <dd>
        <input type="text" name="button_title" id="button_title" value="'.$context['rpg_age_button_settings']['button_title'].'" size="10" class="input_text" />
        </dd>
      <dt>
        <a id="setting_button_sub_button"></a> <span><label for="button_sub_button">'.$txt['rpg_age_sub_button'].'</label></span>
      </dt>
      <dd>
<select name="button_sub_button" id="display_simple">
<option value="standalone"', $context['rpg_age_button_settings']['button_sub_button']=='standalone' ? ' selected="selected"' : '', '>'.$txt['rpg_age_standalone'].'</option>';

foreach ($context['menu_buttons'] as $button_id => $button_array)
  echo '
  <option value="', $button_id, '"', $button_id==$context['rpg_age_button_settings']['button_sub_button'] ? ' selected="selected"' : '', '>', $button_array['title'], '</option>';

echo '
      </select>
        </dd>
    </dl>';


      echo '<input type="submit" name="save_buttons" value="', $txt['save'], '" class="button_submit" />
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
    </form>
  </div>
  <br class="clear" />';
} 
?>