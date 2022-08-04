<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Plugins;

use EngineWorks\Templates\Plugin;

class HtmlEscape implements Plugin
{
    /** @var int flags as used in htmlspecialchars php function */
    private $defaultHtmlFlags;

    /**
     * @return array{e: string, js: string, ejs: string, uri: string, url: string, qry: string}
     */
    public function getCallablesTable(): array
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

    public function __construct(int $defaultHtmlFlags = ENT_COMPAT | ENT_HTML5)
    {
        $this->setDefaultHtmlFlags($defaultHtmlFlags);
    }

    public function html(string $string, int $flags = null): string
    {
        return htmlspecialchars($string, $flags ?? $this->getDefaultHtmlFlags());
    }

    public function javascript(string $string): string
    {
        return str_replace(
            ['\\', "'", '"', "\r", "\n", "\t", "\f"],
            ['\\\\', "\\'", '\\"', '\\r', '\\n', '\\t', '\\f'],
            $string
        );
    }

    public function javascriptInHtml(string $string): string
    {
        return $this->javascript($this->html($string));
    }

    public function uri(string $string): string
    {
        return rawurlencode($string);
    }

    /** @param array<string, (scalar|null)|(scalar|null)[]> $vars */
    public function query(array $vars): string
    {
        return http_build_query($vars, '', '&', PHP_QUERY_RFC3986);
    }

    /** @param array<string, mixed> $vars */
    public function url(string $url, array $vars = []): string
    {
        // get query and fragment
        $qrystr = (string) parse_url($url, PHP_URL_QUERY);
        $fragstr = (string) parse_url($url, PHP_URL_FRAGMENT);
        $qrylen = strlen($qrystr);
        $fraglen = strlen($fragstr);
        $parts = intval($qrylen > 0) + intval($fraglen > 0);
        // exit if there are any qrystring, fragment and does not include new vars
        if ([] === $vars && 0 === $parts) {
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
            . (([] !== $qryvars) ? '?' . $this->query($qryvars) : '')
            . (($fraglen > 0) ? '#' . $fragstr : '');
    }

    /**
     * Get default html flags as used in htmlspecialchars php function
     */
    public function getDefaultHtmlFlags(): int
    {
        return $this->defaultHtmlFlags;
    }

    /**
     * Set default html flags as used in htmlspecialchars php function
     */
    public function setDefaultHtmlFlags(int $defaultHtmlFlags): void
    {
        $this->defaultHtmlFlags = $defaultHtmlFlags;
    }
}
