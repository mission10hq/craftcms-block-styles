<?php

namespace mission10\blockstyles;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use mission10\blockstyles\fields\BlockStyle;
use mission10\blockstyles\models\Settings;
use yii\base\Event;

/**
 * Block Styles plugin
 *
 * @method static BlockStyles getInstance()
 * @method Settings getSettings()
 * @author mission10 <jordan@mission10.com>
 * @copyright mission10
 * @license MIT
 */
class BlockStyles extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = false;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {

            $this->attachEventHandlers();
            
            $this->setComponents([
                'config' => [
                    'class' => 'craft\services\Config', 
                    'defaultConfig' => include __DIR__ . '/config/block-styles.php'
                ]
            ]);

            $this->createConfigFile();
            
        });
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('block-styles/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
            $event->types[] = BlockStyle::class;
        });
    }

    /*
     * Create block-styles config file
     */
    private function createConfigFile()
    {
        
        /* Template */
        $source      = __DIR__ . "/config/block-styles.php";

        /* Project config file */
        $destination = Craft::$app->getPath()->getConfigPath() . '/block-styles.php'; 

        /* Copy template to config directory */
        if( file_exists( $source ) && !file_exists( $destination ) )
        {
            copy( $source, $destination );
        }

    }

}
