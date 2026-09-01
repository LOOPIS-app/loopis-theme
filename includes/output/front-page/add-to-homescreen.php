<?php
/**
 * Add-to-homescreen prompt for mobile browsers.
 * 
 * Included in front-page.php for logged-in users
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div id="homescreen"
     style="display: none; position: fixed; height: 35px; width: 100%;
            bottom: 80px; text-align: center; background-color: #fff;
            border-top: 1px solid #e5e5e5; padding: 5px; z-index: 9998;">

    <p class="small">💡 <a href="#" onclick="installApp(); return false;"> Lägg LOOPIS på hemskärmen</a> så liknar det en app!</p>
</div>

<div id="arrow"
    style="display: none; position: fixed; bottom: 0; left: 50%;
            transform: translateX(-50%); z-index: 9999;
          text-align: left; background-color: #fff; padding: 15px;
          flex-direction: row; align-items: center; gap: 15px;
            width: 100%; box-sizing: border-box;">

    <div style="flex: 1;">
        <h5>Instruktion för iPhone</h5>
        <hr>
        <p>Tryck på <b>Dela</b> i <b>Safari</b> och scrolla ner till<br><b><i class="far fa-plus-square"></i>Lägg till på hemskärmen</b></p>
        <p><a href="#" onclick="hideArrow(); return false;"><i class="fas fa-times"></i>Stäng</a></p>
    </div>
    <img style="width: 85px;" src="<?php echo get_template_directory_uri(); ?>/assets/img/homescreen-iphone.png" alt="Lägg till på hemskärmen"/>
</div>

<script src="<?php echo esc_url(get_template_directory_uri() . '/assets/js/add-to-homescreen.js'); ?>"></script>
