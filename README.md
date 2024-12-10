# Google Docs Markdown Viewer for WordPress

A WordPress plugin that allows you to display Google Docs content as markdown in your WordPress site using shortcodes. This plugin provides a seamless way to maintain your content in Google Docs while displaying it on your WordPress site with proper markdown formatting.

## Features

- Easy integration with Google Docs
- Simple shortcode implementation
- Secure markdown parsing with safe mode enabled
- Clean content display without image clutter
- Support for standard markdown syntax
- Automatic content updates when Google Doc is modified

## Installation

1. Download the plugin files
2. Upload the plugin folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

1. Create a Google Doc and write your content using standard Google Docs formatting
2. Make sure your Google Doc is publicly accessible (or at least viewable by anyone with the link)
3. Get your Google Doc ID from the URL:
   - Example URL: `https://docs.google.com/document/d/1234567890abcdef/edit`
   - The ID is: `1234567890abcdef`
4. Use the shortcode in your WordPress posts or pages:
   ```
   [gdocs_markdown id="YOUR_GOOGLE_DOC_ID"]
   ```
The plugin will automatically convert your Google Docs formatting to markdown when displaying the content on your WordPress site.

## Supported Markdown Elements

The plugin supports standard markdown syntax including:

- Headers (h1-h6)
- Paragraphs
- Bold and italic text
- Blockquotes
- Ordered and unordered lists
- Links
- And more...

## Security Features

- Safe Mode enabled by default
- HTML sanitization
- Restricted HTML tags
- Protected against XSS attacks

## Technical Details

The plugin uses:
- Parsedown library for markdown parsing
- WordPress HTTP API for fetching Google Docs content
- WordPress Shortcode API for implementation
- Content sanitization through wp_kses

## Requirements

- WordPress 4.7 or higher
- PHP 5.6 or higher
- Access to Google Docs

## Limitations

- Images from Google Docs are not supported (they are automatically removed)
- Direct HTML in Google Docs may be stripped for security
- Google Doc must be publicly accessible

## Error Handling

The plugin provides clear error messages for common issues:
- Missing Google Doc ID
- Inaccessible documents
- Missing Parsedown library
- Failed markdown parsing

## License

This plugin is licensed under the MIT License - see the LICENSE file for details.

## Author

Vincent Rozenberg
- Website: [https://vincentrozenberg.com](https://vincentrozenberg.com)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Changelog

### 1.0.0
- Initial release
- Basic markdown support
- Google Docs integration
- Security features implementation
- Error handling
- Content cleaning functionality

## Acknowledgments

- [Parsedown](https://github.com/erusev/parsedown) - Markdown Parser in PHP
- [WordPress Plugin Development](https://developer.wordpress.org/plugins/)
