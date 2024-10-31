<?php

/**
 * Pixelerate_CDN_Rewriter
 *
 * @since 1.0.0
 */
class Pixelerate_CDN_Rewriter
{
    var $blog_url       = null;    // origin URL
    var $cdn_url        = null;    // CDN URL

    var $pixelerate_endpoint_key = null;    // required endpoint key for Pixelerate serivces

    /**
     * constructor
     *
     * @since   1.0.0
     */
    function __construct(
        $blog_url,
        $cdn_url,
        $pixelerate_endpoint_key
    ) {
        $this->blog_url       = $blog_url;
        $this->cdn_url        = $cdn_url;
        $this->pixelerate_endpoint_key = $pixelerate_endpoint_key;
    }

    /**
     * relative url
     *
     * @since   1.0.0
     *
     * @param   string  $url a full url
     * @return  string  protocol relative url
     */
    protected function relative_url($url) {
        return substr($url, strpos($url, '//'));
    }

    /**
     * rewrite url
     *
     * @since   1.0.0
     *
     * @param   string  $asset  current asset
     * @return  string  pixelerate url
     */
    protected function rewrite_url(&$asset) {
        // rewrite using the pixelerate url
        return $this->cdn_url . $this->pixelerate_endpoint_key . $asset[1];
    }

    /**
     * rewrite url
     *
     * @since   1.0.0
     *
     * @param   string  $html  current raw HTML doc
     * @return  string  updated HTML with pixelerate links
     */
    public function rewrite($html) {
        $blog_url = str_replace("/", ".", $this->relative_url($this->blog_url));

        $regex_rule = '(https?:' . $blog_url . '(.+?(png|jpg|jpeg)))';

        // call the cdn rewriter callback
        $cdn_html = preg_replace_callback($regex_rule, [$this, 'rewrite_url'], $html);

        return $cdn_html . '<div>' . $regex_rule . '</div>'. '<div>' . $this->blog_url . '</div>'; 
    }
}
