<?php $server_url = get_option('cm_rating_server_url'); ?>
<form>
    <input type="hidden" name="page" value="cmr">
    <h1 class="wp-heading-inline">Comment rate config</h1>
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="server_url">Server url</label></th>
                <td><input name="server_url" type="text" id="server_url" 
                value="<?php echo isset($server_url) ?  $server_url  : '' ?>" class="regular-text code"></td>
            </tr>
        </tbody>
    </table>
    <input type="submit" class=" button-primary" value="Save">
</form>