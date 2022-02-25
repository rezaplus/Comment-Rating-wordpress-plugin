<?php

/**
 * register admin page 
 * get api link
 * 
 * @since    1.0.0
 */
class comment_rating_system
{

    public static $instance = null;

    public function __construct()
    {
        // Enqueue the plugin's styles.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_style_cmr'));

        // Create the rating input.
        add_action('comment_form_logged_in_after', array($this, 'view_cmr_comment_rate_input'));
        add_action('comment_form_after_fields', array($this, 'view_cmr_comment_rate_input'));

        // Save the rating submitted by the user.
        add_action('comment_post', array($this, 'save_comment_rate'), 10, 3);

        // Display the rating on a submitted comment.
        add_filter('comment_text', array($this, 'comment_rating_display'));

        // Display the average rating above the content.
        add_filter('the_content', array($this, 'display_average_rating'));
    }

    public function enqueue_style_cmr()
    {
        wp_register_style('ci-comment-rating-styles', plugin_dir_url(__dir__) . 'public/css/style.css');
        wp_enqueue_style('dashicons');
        wp_enqueue_style('ci-comment-rating-styles');
    }


    function view_cmr_comment_rate_input()
    {
?>
        <label for="rating">Rating<span class="required">*</span></label>
        <fieldset class="comments-rating">
            <span class="rating-container">
                <?php for ($i = 5; $i >= 1; $i--) : ?>
                    <input type="radio" id="rating-<?php echo esc_attr($i); ?>" name="rating" value="<?php echo esc_attr($i); ?>" /><label for="rating-<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></label>
                <?php endfor; ?>
                <input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0" /><label for="rating-0">0</label>
            </span>
        </fieldset>
<?php
    }


    function save_comment_rate($comment_id, $comment_approved, $commentdata)
    {
        if ((isset($_POST['rating'])) && ('' !== $_POST['rating']))
            $rating = intval($_POST['rating']);

        add_comment_meta($comment_id, 'rating', $rating);

        // send comment rate to server
        $this->send_comment_rate_API($comment_id, $commentdata);
    }

    public function send_comment_rate_API($comment_id, $commentdata)
    {
        $arg = array(
            'post_id' => $commentdata['comment_post_ID'],
            'comment_id' => $comment_id,
            'comment_author' => $commentdata['comment_author'],
            'content' => $commentdata['comment_content'],
            'rate'    => $_POST['rating'],
            'post_title' => wp_strip_all_tags(get_the_title($commentdata['comment_post_ID'])),

        );
        include plugin_dir_path(__dir__) . 'includes/class_comment_rating_API.php';
        $api = new class_comment_rating_API;
        $api->send_data_to_server($arg);
    }


    function comment_rating_display($comment_text)
    {

        if ($rating = get_comment_meta(get_comment_ID(), 'rating', true)) {
            $stars = '<p class="stars">';
            for ($i = 1; $i <= $rating; $i++) {
                $stars .= '<span class="dashicons dashicons-star-filled"></span>';
            }
            $stars .= '</p>';
            $comment_text = $comment_text . $stars;
            return $comment_text;
        } else {
            return $comment_text;
        }
    }


    // Get the average rating of a post.
    function get_average_ratings($id)
    {
        $comments = get_approved_comments($id);

        if ($comments) {
            $i = 0;
            $total = 0;
            foreach ($comments as $comment) {
                $rate = get_comment_meta($comment->comment_ID, 'rating', true);
                if (isset($rate) && '' !== $rate) {
                    $i++;
                    $total += $rate;
                }
            }

            if (0 === $i) {
                return false;
            } else {
                return round($total / $i, 1);
            }
        } else {
            return false;
        }
    }


    function display_average_rating($content)
    {

        global $post;

        if (false === $this->get_average_ratings($post->ID)) {
            return $content;
        }

        $stars   = '';
        $average = $this->get_average_ratings($post->ID);

        for ($i = 1; $i <= $average + 1; $i++) {

            $width = intval($i - $average > 0 ? 20 - (($i - $average) * 20) : 20);

            if (0 === $width) {
                continue;
            }

            $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';

            if ($i - $average > 0) {
                $stars .= '<span style="overflow:hidden; position:relative; left:-' . $width . 'px;" class="dashicons dashicons-star-empty"></span>';
            }
        }

        $custom_content  = '<p class="average-rating">This post\'s average rating is: ' . $average . ' ' . $stars . '</p>';
        $custom_content .= $content;

        return $custom_content;
    }



    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}

comment_rating_system::get_instance();
