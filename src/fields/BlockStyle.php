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
use NumberFormatter;
use yii\db\Schema;

/**
 * Block Style field type
 */
class BlockStyle extends Field
{
    /**
     * Config cache to avoid repeated file reads
     */
    private static ?array $configCache = null;

    /**
     * Whether empty values are allowed
     */
    public bool $allowEmpty = false;

    public static function displayName(): string
    {
        return Craft::t('block-styles', 'Block Style');
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
        return Craft::$app->getView()->renderTemplate( 'block-styles/_settings' );
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
        /* Validate element exists */
        if ($element === null) {
            return '';
        }

        /* Get options for this block */
        $options = $this->getOptions($element);

        /* If less than 2 options, don't show the field */
        if (count($options) < 2) {
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
                'allowEmptyOption' => $this->allowEmpty,
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

    /**
     * Get options for the given element
     *
     * @param ElementInterface|null $element The element being edited
     * @return array The formatted options array
     */
    private function getOptions(?ElementInterface $element): array
    {
        /* Validate element and required properties */
        if ($element === null || !isset($element->fieldId)) {
            return [];
        }

        /* Get config with caching */
        if (self::$configCache === null) {
            self::$configCache = Craft::$app->config->getConfigFromFile('block-styles');
        }
        $config = self::$configCache;

        /* Set default options */
        $options = $config['default'] ?? 2;

        /* Get field and validate */
        $field = Craft::$app->getFields()->getFieldById($element->fieldId);
        if ($field === null) {
            return $this->formatOptions($options);
        }

        if ($field instanceof craft\fields\Matrix) {
            /* Get field handle */
            $fieldHandle = $field->handle ?? 'default';

            /* Get block type and handle */
            $blockType = $element->getType();
            if ($blockType === null) {
                return $this->formatOptions($options);
            }
            $blockHandle = $blockType->handle ?? 'default';

            /* Get fieldHandle => blockHandle OR fieldHandle => default OR defaultOptions */
            $options = $config[$fieldHandle][$blockHandle] ?? ($config[$fieldHandle]['default'] ?? $options);
        }

        return $this->formatOptions($options);
    }

    /**
     * Format options from config into standardized array format
     *
     * @param mixed $options Options from config (array or int)
     * @return array Formatted options with label and value
     */
    private function formatOptions(mixed $options): array
    {
        if (is_array($options)) {
            return $options;
        } elseif (is_int($options)) {
            $newOptions = [];
            $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);

            for ($i = 1; $i <= $options; $i++) {
                $word = strtolower($formatter->format($i));
                $label = ucfirst($word);
                $value = lcfirst(str_replace(" ", "", ucwords(str_replace("-", " ", $word))));
                $newOptions[] = [
                    'label' => $label,
                    'value' => $value
                ];
            }

            return $newOptions;
        }
        return [];
    }

}
