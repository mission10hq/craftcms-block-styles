# Block Styles

Style dropdown for Neo blocks

## Requirements

This plugin requires Craft CMS 4.5.0 or later, and PHP 8.0.2 or later.

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
Upon install, a `block-styles.php` file will be created in your project's `config` directory. 

Simply update this file with the example format to provide the dropdown options for your field. 

By providing an integer, the dropdown will display an option for each option between 1 and your given integer. (e.g. if you input 3, the dropdown will show "one", "two" and "three" as options). Or you can provide an array of options per block, each with a label and value parameter. 

Create a field in Craft settings and select the "Block Style" option and add it to your Neo blocks. 
