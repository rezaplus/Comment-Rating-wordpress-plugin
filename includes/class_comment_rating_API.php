<?php
/**
 * api connection
 * insert data to third-party
 * 
 * @since    1.0.0
 */
class class_comment_rating_API
{
    public static function send_data_to_server($data)
    {
        $url = get_option('cm_rating_server_url');
        if (!empty($url)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $json = curl_exec($ch);

            if (!$json) {
                curl_error($ch);
            }
            curl_close($ch);
            update_option('test',$json);
        }
    }
}
