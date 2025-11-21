# Block Styles

Style and Theme dropdowns for Matrix blocks

## Requirements

This plugin requires Craft CMS 5.0.0 or later, and PHP 8.0.2 or later.

## Installation
Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project

# tell Composer to load the plugin
composer require mission10/craftcms-block-styles

# tell Craft to install the plugin
./craft plugin/install block-styles
```
## Use

This plugin provides two field types for Matrix blocks:

### Block Style Field

Upon install, a `block-styles.php` file will be created in your project's `config` directory.

Simply update this file with the example format to provide the dropdown options for your field.

By providing an integer, the dropdown will display an option for each option between 1 and your given integer. (e.g. if you input 3, the dropdown will show "one", "two" and "three" as options). Or you can provide an array of options per block, each with a label and value parameter.

Create a field in Craft settings and select the "Block Style" option and add it to your Matrix blocks.

**Config structure:**
```php
return [
    'default' => 2,  // Default: creates "One" and "Two" options

    'matrix-field-handle' => [
        'block-handle' => 3,  // Creates "One", "Two", "Three" options
        'another-block' => [
            ['label' => 'Left', 'value' => 'left'],
            ['label' => 'Right', 'value' => 'right'],
        ],
    ]
];
```

**Behavior:**
- Each block can have different style options
- The field is hidden if there are less than 2 options
- Options can be defined per block, per field, or globally

### Block Theme Field

Upon install, a `block-themes.php` file will be created in your project's `config` directory.

Block Themes work differently from Block Styles - themes are defined once globally, and you simply enable/disable them per block.

Create a field in Craft settings and select the "Block Theme" option and add it to your Matrix blocks.

**Config structure:**
```php
return [
    // Define themes once globally
    'default' => [
        ['label' => 'Light', 'value' => 'light'],
        ['label' => 'Dark', 'value' => 'dark'],
    ],

    // Enable themes per block with true/false
    'matrix-field-handle' => [
        'block-with-themes' => true,  // Shows all default themes
        // Other blocks are disabled by default
    ]
];
```

**Behavior:**
- Themes are defined once in the `default` array
- Per block, you only enable (true) or disable (omit/false)
- When enabled, ALL default themes are available
- The field is hidden if themes are not enabled for a block
- **Themes are disabled by default** - only show when explicitly set to `true`

### Key Differences

| Feature | Block Style | Block Theme |
|---------|-------------|-------------|
| **Options per block** | Can be different for each block | Same options everywhere (from default) |
| **Configuration** | Define options per block | Define once, enable/disable per block |
| **Default behavior** | Shows 2 options (from default) | Hidden (disabled by default) |
| **Use case** | Blocks need different layout options | Consistent theming across blocks | 
