<?php

namespace mission10\blockstyles\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\elements\db\ElementQueryInterface;
use craft\elements\Entry;
use craft\fields\conditions\OptionsFieldConditionRule;
use craft\helpers\Cp;
use craft\helpers\Html;
use craft\helpers\StringHelper;
use yii\db\Schema;

/**
 * Block Theme field type
 */
class BlockTheme extends Field
{

    public static function displayName(): string
    {
        return Craft::t('block-styles', 'Block Theme');
    }

    public static function phpType(): string
    {
        return 'mixed';
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            // ...
        ]);
    }

    protected function defineRules(): array
    {
        return array_merge(parent::defineRules(), [
            // ...
        ]);
    }

    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate( 'block-styles/_settings-themes' );
    }

    public function getContentColumnType(): array|string
    {
        return Schema::TYPE_STRING;
    }

    public function normalizeValue(mixed $value, ?\craft\base\ElementInterface $element = null): mixed
    {
        return $value;
    }

    protected function inputHtml(mixed $value, ElementInterface $element = null, bool $inline = false): string
    {

        /* Get options for this block */
        $options = $this->getOptions( $element );

        /* If no options (themes disabled for this block), don't show the field */
        if( empty( $options ) )
        {
            return '';
        }

        /* Render field */
        return Cp::selectizeHtml([
            'id'               => $this->getInputId(),
            'describedBy'      => $this->describedBy,
            'name'             => $this->handle,
            'value'            => $value,
            'options'          => $options,
            'selectizeOptions' => [
                'allowEmptyOption' => false,
            ],
        ]);

    }

    public function getElementValidationRules(): array
    {
        return [];
    }

    protected function searchKeywords(mixed $value, ElementInterface $element): string
    {
        return StringHelper::toString($value, ' ');
    }

    public function getElementConditionRuleType(): array|string|null
    {
        return OptionsFieldConditionRule::class;
    }

    public function modifyElementsQuery(ElementQueryInterface $query, mixed $value): void
    {
        parent::modifyElementsQuery($query, $value);
    }

    private function getOptions( $element  )
    {
        /* Get config */
        $config = Craft::$app->config->getConfigFromFile('block-themes');

        /* Get default themes - empty array if not defined */
        $defaultThemes = $config['default'] ?? [];

        /* Default: themes are disabled (return empty array) */
        $enabled = false;

        $field = Craft::$app->getFields()->getFieldById( $element->fieldId );

        if( $field instanceof craft\fields\Matrix )
        {
            /* Get field handle */
            $fieldHandle = $field->handle ?? null;

            /* Get block handle */
            $blockHandle = $element->getType()->handle ?? null;

            /* Check if themes are explicitly enabled for this field/block combination */
            if( $fieldHandle && $blockHandle && isset($config[ $fieldHandle ][ $blockHandle ]) )
            {
                $enabled = $config[ $fieldHandle ][ $blockHandle ] === true;
            }

        }

        /* Return default themes only if enabled, otherwise return empty array */
        return $enabled ? $defaultThemes : [];

    }

}
