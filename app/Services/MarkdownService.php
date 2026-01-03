<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownService
{
    protected MarkdownConverter $converter;

    public function __construct()
    {
        $config = [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Parse and sanitize markdown content
     */
    public function parse(string $markdown): string
    {
        $html = $this->converter->convert($markdown)->getContent();

        // Additional sanitization
        return $this->sanitizeHtml($html);
    }

    /**
     * Sanitize HTML output
     */
    protected function sanitizeHtml(string $html): string
    {
        // Allow only safe tags
        $allowedTags = '<p><br><strong><em><ul><ol><li><a><code><pre><h1><h2><h3><h4><h5><h6><blockquote>';
        
        $html = strip_tags($html, $allowedTags);

        // Remove any event handlers and javascript
        $html = preg_replace('/on\w+="[^"]*"/i', '', $html);
        $html = preg_replace('/on\w+=\'[^\']*\'/i', '', $html);
        $html = preg_replace('/javascript:/i', '', $html);

        return $html;
    }
}
