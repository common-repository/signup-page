<?php
/*
  Plugin Name: Signup Page
  Plugin URI: http://gamon.org/blog/2012/04/23/signup-page-a-wordpress-plugin/
  Description: interface for signing up for stuff
  Author: Scott Gamon
  Version: 1.0
  Author URI: http://gamon.org/
 */

// [signup_page]
function signup_page($atts) {
  extract( shortcode_atts( array(
		'list_title' => 'List',
		'field_title' => 'Signup',
	), $atts ) );
  
  
  global $post;
  
  /*
   * $signups hash is structured as so:
   * 
   * [
   *   {
   *    pageid:xxx, // integer
   *    signup:'abc' // string
   *   },
   *   {
   *    pageid:yyy, // integer
   *    signup:'def' // string
   *   }
   * ]
   */
  $option_key = signup_page_str_to_option_key($post->post_title);
  $signups = get_option($option_key);
  if ($signups) {
    $signups = json_decode($signups);
  } else {
    $signups = array();
  }
  
  
  $pages = get_pages(array('child_of' => $post->ID));
  
  $rows = '';
  foreach( $pages as $page ) {
    $href = get_page_link( $page->ID );
    $field = signup_page_get_signup_field($page->ID, $signups);
    $rows .= <<<EOR
  <tr>
    <td><a href="{$href}">{$page->post_title}</a></td>
    <td>{$field}</td>
  </tr>
EOR;
  }

  $form = <<<EOT
<form name="signup" id="signup_form">
<table>
  <tr><th>{$list_title}</th><th>{$field_title}</th></tr>
  $rows
  <tr><td></td><td align="right"><input type="submit" id="signup_form_save_btn" value="Save"></td></tr>
</table>
</form>

<script>
  if (!window.jQuery) {
    document.write('<scr' + 'ipt src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js""></scr' + 'ipt>');
  }
</script>

<script>
  jQuery('#signup_form').submit(function(e){
    e.preventDefault(); 
  
    console.log('you clicked save');
  
    var els = [];
    jQuery.each(document.signup.elements, function(i, el) {
      if (el.value && (el.type!='submit')) {
        var pageid = parseInt(el.name.replace(/pageId/, ''), 10);
        var val = el.value.replace(/'/g, "\\'");
        els.push({pageid:pageid, signup:val});
      }
    });
    
    var signups = JSON.stringify(els);
    console.log(signups);

    var data = {
      action:'signup_page_update',
      signups:signups,
      option_key:'{$option_key}'
    };

    jQuery.post(
      "/wp-admin/admin-ajax.php", 
      data,
      function(str) {
        console.log('results: ' + str);
        location.reload();
      }
    );
  });
</script>
EOT;

  return $form;
}
add_shortcode('signup_page', 'signup_page');


/**
 * Converts a string to underscore delimited.
 * 
 * @param <string> $title
 * @return <string> 
 */
function signup_page_str_to_option_key($title) {
  $title = strtolower($title);
  $title = preg_replace('/ +/', '_', $title);
  $title = preg_replace('/[!_a-z]/', '', $title);
  return $title  . '_signups';
}


/**
 * Returns markup for the signup field.
 * 
 * @param <integer> $pageid
 * @param <array> $signups
 * @return <string> 
 */
function signup_page_get_signup_field($pageid, $signups) {
  $field = "<input type=\"text\" name=\"pageId{$pageid}\" value=\"\">"; // default
  
  foreach ($signups as $signup) {
    if ($signup->pageid == $pageid) {
      if (current_user_can('manage_options')) {
        // admins
        $field = "<input type=\"text\" name=\"pageId{$pageid}\" value=\"{$signup->signup}\">";
      } else {
        // normal people
        $field = $signup->signup . "<input type=\"hidden\" name=\"pageId{$pageid}\" value=\"{$signup->signup}\">";
      }
    }
  }
  
  return $field;
}



// ajax update
add_action('wp_ajax_signup_page_update', 'signup_page_update');
add_action('wp_ajax_nopriv_signup_page_update', 'signup_page_update');

function signup_page_update() {

  if ($_POST['signups']) {
    $signups = preg_replace('/\\\\/', '', $_POST['signups']);
    
    $option_key = $_POST['option_key'];
    if (!$option_key) {
      error_log('missing $option_key');
      return;
    }
    
    if (!get_option($option_key)) {
      add_option($option_key, $signups);
      print "ok - " . $signups;
    } else {
      update_option($option_key, $signups);
      print "ok - " . $signups;
    }
  } else {
    print 'failed';
  }
  
}


?>