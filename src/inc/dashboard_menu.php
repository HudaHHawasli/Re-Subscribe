<?php

$newsletters = get_option('resubscribe-newsletters', []);

if(isset($_POST) and count($_POST)) {
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
    // Handle newsletter data
    if(isset($_POST['newsletter_key']) and isset($_POST['newsletter_key'])) {
        // check than newsletter key doesn't exist
        if(! array_key_exists($_POST['newsletter_key'], $newsletters)) {
            $newsletters[$_POST['newsletters_key']] = [
                'name' => $_POST['newsletter_name'],
                'list_id' => $_POST['newsletter_list_id']
            ];

            update_option('resubscribe-newsletters', $newsletters);
        }
    }
}

$main_title      = isset($main_title)      ? $main_title      : get_option('resubscribe-main-title', Resubscribe::$main_title);
$main_text       = isset($main_text)       ? $main_text       : get_option('resubscribe-main-text', Resubscribe::$main_text);
$footer_box_text = isset($footer_box_text) ? $footer_box_text : get_option('resubscribe-footer-box-text', Resubscribe::$footer_box_text);

?>
<div class="wrap">
    <h2>Re-Subscribe Options</h2>
    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><label for="main-title">Modal title</label></th>
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

    <hr>

    <h2>Newsletters</h2>

    <table class="widefat">
        <thead>
            <tr>
                <th>Key</th>
                <th>Name</th>
                <th>List_id</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($newsletters as $key => $item): ?>
            <tr>
                <td><?= $key;?></td>
                <td><?= $item['name'];?></td>
                <td><?= $item['list_id'];?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>

    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th><label for="newsletter_name">Name</label></th>
                <td><input type="text" name="newsletter_name" value="<?= $newsletter_name;?>"></td>
            </tr>
            <tr>
                <th><label for="newsletter_key">Key</label></th>
                <td><input type="text" name="newsletter_key" value="<?= $newsletter_key;?>"></td>
            </tr>
            <tr>
                <th><label for="newsletter_list_id">MailChimp list id</label></th>
                <td><input type="text" name="newsletter_list_id" value="<?= $newsletter_list_id;?>"></td>
            </tr>
        </table>

        <input type="submit" class="button button-primary" value="Save">
    </form>
</div>