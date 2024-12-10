<?php
/**
 * Plugin Name: Google Docs Markdown Viewer
 * Plugin URI: https://vincentrozenberg.com/
 * Description: Display Google Docs content as markdown in WordPress using shortcodes. Usage: Create a Google Doc, add your content using markdown syntax, then use [gdocs_markdown id="YOUR_GOOGLE_DOC_ID"] in any post or page. To get the document ID, look at your Google Doc URL: it's the long string between /d/ and /edit, for example in https://docs.google.com/document/d/1234567890abcdef/edit the ID is 1234567890abcdef.
 * Version: 1.0.0
 * Author: Vincent Rozenberg
 * Author URI: https://vincentrozenberg.com
 * License: MIT
 * Text Domain: gdocs-markdown
 * Domain Path: /languages
 *
 * @package GDocsMarkdown
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Main plugin class
 */
class GDocsMarkdown {
    /**
     * Initialize the plugin
     */
    public function __construct() {
        add_shortcode('gdocs_markdown', array($this, 'render_markdown'));
    }

    /**
     * Clean markdown content by removing image references
     *
     * @param string $markdown The markdown content
     * @return string Cleaned markdown without images
     */
    private function clean_markdown($markdown) {
        // Remove image references from the bottom of the document
        $parts = explode("\n\nSources\n\n", $markdown);
        $content = $parts[0];

        // Remove inline image references
        $content = preg_replace('/!\[([^\]]*)\]\[([^\]]*)\]/', '', $content);
        
        // Remove any remaining image syntax
        $content = preg_replace('/!\[([^\]]*)\]\(([^)]*)\)/', '', $content);

        // Clean up multiple blank lines that might be left after removing images
        $content = preg_replace("/[\r\n]+/", "\n\n", $content);

        return trim($content);
    }

    /**
     * Render markdown content from Google Docs
     *
     * @param array $atts Shortcode attributes
     * @return string Rendered HTML content
     */
    public function render_markdown($atts) {
        // Parse attributes
        $atts = shortcode_atts(
            array(
                'id' => '',
            ),
            $atts,
            'gdocs_markdown'
        );

        // Validate document ID
        if (empty($atts['id'])) {
            return '<p>' . esc_html__('Error: Google Doc ID is required.', 'gdocs-markdown') . '</p>';
        }

        // Build the Google Docs export URL
        $url = sprintf(
            'https://docs.google.com/document/d/%s/export?format=md',
            esc_attr($atts['id'])
        );

        // Fetch the markdown content
        $response = wp_remote_get($url);

        // Check for errors
        if (is_wp_error($response)) {
            return '<p>' . esc_html__('Error: Unable to fetch document content.', 'gdocs-markdown') . '</p>';
        }

        // Get the response body
        $markdown = wp_remote_retrieve_body($response);

        // Check if content is empty
        if (empty($markdown)) {
            return '<p>' . esc_html__('Error: Document is empty or not accessible.', 'gdocs-markdown') . '</p>';
        }

        // Clean markdown by removing images
        $markdown = $this->clean_markdown($markdown);

        // Include Parsedown if it exists
        $parsedown_path = plugin_dir_path(__FILE__) . 'Parsedown.php';
        if (!file_exists($parsedown_path)) {
            return '<p>' . esc_html__('Error: Parsedown library not found.', 'gdocs-markdown') . '</p>';
        }

        require_once $parsedown_path;

        try {
            // Create Parsedown instance and convert markdown to HTML
            $parsedown = new Parsedown();
            $parsedown->setSafeMode(true);
            $html = $parsedown->text($markdown);

            // Define allowed HTML tags
            $allowed_html = array(
                'div' => array(
                    'class' => array(),
                ),
                'p' => array(),
                'h1' => array(),
                'h2' => array(),
                'h3' => array(),
                'h4' => array(),
                'h5' => array(),
                'h6' => array(),
                'strong' => array(),
                'em' => array(),
                'blockquote' => array(),
                'ul' => array(),
                'ol' => array(),
                'li' => array(),
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                ),
            );

            // Return the processed content
            return wp_kses(
                sprintf('<div class="gdocs-markdown-content">%s</div>', $html),
                $allowed_html
            );

        } catch (Exception $e) {
            return '<p>' . esc_html__('Error: Failed to parse markdown content.', 'gdocs-markdown') . '</p>';
        }
    }
}

// Initialize the plugin
new GDocsMarkdown();

// Activation hook
register_activation_hook(__FILE__, 'gdocs_markdown_activate');

/**
 * Plugin activation callback
 */
function gdocs_markdown_activate() {
    // Check if Parsedown exists
    $parsedown_path = plugin_dir_path(__FILE__) . 'Parsedown.php';
    if (!file_exists($parsedown_path)) {
        wp_die(
            esc_html__('Parsedown library is required but not found. Please install Parsedown.php in the plugin directory.', 'gdocs-markdown'),
            esc_html__('Plugin Activation Error', 'gdocs-markdown'),
            array('back_link' => true)
        );
    }
}