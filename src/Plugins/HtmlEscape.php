<?php
namespace EngineWorks\Templates\Plugins;

use EngineWorks\Templates\Plugin;

class HtmlEscape implements Plugin
{
    /** @var int flags as used in htmlspecialchars php function */
    private $defaultHtmlFlags;

    public function getCallablesTable()
    {
        return [
            'e' => 'html',
            'js' => 'javascript',
            'ejs' => 'javascriptInHtml',
            'uri' => 'uri',
            'url' => 'url',
            'qry' => 'query',
        ];
    }

    public function __construct($defaultHtmlFlags = ENT_COMPAT | ENT_HTML5)
    {
        $this->setDefaultHtmlFlags($defaultHtmlFlags);
    }

    public function html($string, $flags = null)
    {
        if (null === $flags) {
            $flags = $this->getDefaultHtmlFlags();
        }
        return htmlspecialchars($string, $flags);
    }

    public function javascript($string)
    {
        return str_replace(
            ['\\', "'", '"', "\r", "\n", "\t", "\f"],
            ['\\\\', "\\'", '\\"', '\\r', '\\n', '\\t', '\\f'],
            $string
        );
    }

    public function javascriptInHtml($string)
    {
        return $this->javascript($this->html($string));
    }

    public function uri($string)
    {
        return rawurlencode($string);
    }

    public function query(array $vars)
    {
        return http_build_query($vars, '', '&', PHP_QUERY_RFC3986);
    }

    public function url($url, array $vars = [])
    {
        // validate path
        $url = (null === $url || empty($url)) ? '' : $url;
        if (! is_string($url)) {
            throw new \InvalidArgumentException('The url is not a string');
        }
        // get query and fragment
        $qrystr = (string) parse_url($url, PHP_URL_QUERY);
        $fragstr = (string) parse_url($url, PHP_URL_FRAGMENT);
        $qrylen = strlen($qrystr);
        $fraglen = strlen($fragstr);
        $parts = ($qrylen > 0) + ($fraglen > 0);
        // exit if there are any qrystring, fragment and does not include new vars
        if (! count($vars) && 0 == $parts) {
            return $url;
        }
        // get the path without query string and fragment
        if ($parts > 0) {
            $url = substr($url, 0, strlen($url) - $qrylen - $fraglen - $parts);
        }
        // put the current query string into an array
        parse_str($qrystr, $qryvars);
        // merge new properties to the array
        $qryvars = array_merge($qryvars, $vars);
        // return with new query string and fragment
        return $url
            . ((count($qryvars)) ? '?' . $this->query($qryvars) : '')
            . (($fraglen > 0) ? '#' . $fragstr : '');
    }

    /**
     * Get default html flags as used in htmlspecialchars php function
     * @return int
     */
    public function getDefaultHtmlFlags()
    {
        return $this->defaultHtmlFlags;
    }

    /**
     * Set default html flags as used in htmlspecialchars php function
     * @param int $defaultHtmlFlags
     */
    public function setDefaultHtmlFlags($defaultHtmlFlags)
    {
        if (! is_int($defaultHtmlFlags)) {
            throw new \InvalidArgumentException('The default html flags value is not valid');
        }
        $this->defaultHtmlFlags = $defaultHtmlFlags;
    }
}
