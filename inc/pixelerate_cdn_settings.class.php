<?php

/**
 * Pixelerate_CDN_Settings
 *
 * @since 1.0.0
 */
class Pixelerate_CDN_Settings
{
    /**
     * register settings
     *
     * @since   1.0.0
     */
    public static function register_settings()
    {
        register_setting(
            'pixelerate_cdn',
            'pixelerate_cdn',
            [
                __CLASS__,
                'validate_settings',
            ]
        );
    }

    /**
     * validation of settings
     *
     * @since   1.0.0
     *
     * @param   array  $data  array with form data
     * @return  array         array with validated values
     */
    public static function validate_settings($data)
    {
        if (!isset($data['pixelerate_endpoint_key'])) {
            $data['pixelerate_endpoint_key'] = "";
        }

        return [
            'url'             => esc_url($data['url']),
            'pixelerate_endpoint_key'  => esc_attr($data['pixelerate_endpoint_key']),
        ];
    }

    /**
     * add settings page
     *
     * @since   1.0.0
     */
    public static function add_settings_page()
    {
        $page = add_options_page(
            'Pixelerate CDN',
            'Pixelerate CDN',
            'manage_options',
            'pixelerate_cdn',
            [
                __CLASS__,
                'settings_page',
            ]
        );
    }

    /**
     * settings page
     *
     * @since   1.0.0
     *
     * @return  void
     */
    public static function settings_page()
    {
        $options = Pixelerate_CDN::get_options()

      ?>
        <div class="wrap">
           <h2>
               <?php _e("Pixelerate CDN Settings", "pixelerate-cdn"); ?>
           </h2>

            <?php
                if (( ! array_key_exists('pixelerate_endpoint_key', $options)
                        or strlen($options['pixelerate_endpoint_key']) < 1 ))
                {
                    printf(__('
           <div class="notice notice-info">
               <p>Make sure you go to <b><a href="%s">%s</a></b> to create an account to improve the performance of your WordPress site.</p>
           </div>'), 'https://www.pixelerate.io/pricing?utm_source=wp-admin&utm_medium=plugins&utm_campaign=pixelerate-cdn', 'Pixelerate');
                }
            ?>

           <form method="post" action="options.php">
               <?php settings_fields('pixelerate_cdn') ?>

               <table class="form-table">

                   <tr valign="top">
                       <th scope="row">
                           <?php _e("Pixelerate CDN Url", "pixelerate-cdn"); ?>
                       </th>
                       <td>
                           <fieldset>
                               <label for="pixelerate_cdn_url">
                                   <input type="text" name="pixelerate_cdn[url]" id="pixelerate_cdn_url" value="<?php echo $options['url']; ?>" size="64" class="regular-text code" />
                               </label>

                               <p class="description">
                                   Enter the Pixelerate CDN Url when you're account has been setup: <code>https://cdn.pixelerate.io/img/</code>
                               </p>
                           </fieldset>
                       </td>
                   </tr>

                   <tr valign="top">
                       <th scope="row">
                           <?php _e("Pixelerate Endpoint Key", "pixelerate-cdn"); ?>
                       </th>
                       <td>
                           <fieldset>
                               <label for="pixelerate_endpoint_key">
                                   <input type="text" name="pixelerate_cdn[pixelerate_endpoint_key]" id="pixelerate_endpoint_key" value="<?php echo $options['pixelerate_endpoint_key']; ?>" size="64" class="regular-text code" />
                               <p class="description">
                                   Generate your endpoint key at <a href="https://www.pixelerate.io/settings">Pixelerate</a>
                               </p>
                               </label>
                           </fieldset>
                       </td>
                   </tr>
               </table>

               <?php submit_button() ?>
           </form>
        </div><?php
    }
}
