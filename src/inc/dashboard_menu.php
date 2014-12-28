<?php

if(isset($_POST)) {
    if(isset($_POST['main-text'])) {
        $main_text = $_POST['main-text'];
        update_option( 'resubscribe-main-text', $main_text );
    }
    if(isset($_POST['main-title'])) {
        $main_title = $_POST['main-title'];
        update_option( 'resubscribe-main-title', $main_title );
    }
    if(isset($_POST['footer-box-text'])) {
        $footer_box_text = $_POST['footer-box-text'];
        update_option( 'resubscribe-footer-box-text', $footer_box_text );
    }
}
else {
    $main_title      = isset($main_title)      ? $main_title      : get_option('resubscribe-main-title', Resubscribe::$main_title);
    $main_text       = isset($main_text)       ? $main_text       : get_option('resubscribe-main-text', Resubscribe::$main_text);
    $footer_box_text = isset($footer_box_text) ? $footer_box_text : get_option('resubscribe-footer-box-text', Resubscribe::$footer_box_text);
}

?>
<div class="wrap">
    <h2>Re-Subscribe Options</h2>
    <form action="">
        <table class="form-table">
            <tr>
                <th><label for="main-text">Modal title</label></th>
                <td><input type="text" name="main-title" value="<?= $main_title;?>"></td>
            </tr>
            <tr>
                <th><label for="main-text">Main text in the modal</label></th>
                <td><input type="text" name="main-text" value="<?= $main_text;?>"></td>
            </tr>
            <tr>
                <th><label for="footer-box-text">Footer box text</label></th>
                <td><input type="text" name="footer-box-text" value="<?= $footer_box_text;?>"></td>
            </tr>
        </table>

        <input type="submit" class="button button-primary" value="Save">
    </form>
</div>