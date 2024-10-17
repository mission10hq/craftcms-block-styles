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

        /* Render field */
        return Cp::selectizeHtml([
            'id'               => $this->getInputId(),
            'describedBy'      => $this->describedBy,
            'name'             => $this->handle,
            'value'            => $value,
            'options'          => $this->getOptions( $element ),
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
        $config = Craft::$app->config->getConfigFromFile('block-styles');

        /* Set default options */
        $options = $config['default'] ?? 2;

        $field = Craft::$app->getFields()->getFieldById( $element->fieldId );

        if( $field instanceof craft\fields\Matrix )
        {
            /* Get field handle */
            $fieldHandle = $field->handle ?? 'default';

            /* Get block handle */
            $blockHandle = $element->getType()->handle ?? 'default';

            /* Get fieldHandle => blockHandle OR fieldHandle => default OR defaultOptions */
            $options = $config[ $fieldHandle ][ $blockHandle ] ?? ( $config[ $fieldHandle ]['default'] ?? $options );

        }

        return $this->formatOptions( $options );

    }

    private function formatOptions( $options )
    {
        if( is_array( $options ) )
        { 
            return $options; 
        }
        elseif( is_int( $options ) )
        {

            $newOptions = [];
            $formatter  = new NumberFormatter("en", NumberFormatter::SPELLOUT);

            for( $i = 1; $i <= $options; $i++ )
            {
                $word  = strtolower( $formatter->format($i) );
                $label = ucfirst( $word );
                $value = lcfirst( str_replace( " ", "", ucwords( str_replace( "-", " ", $word ) ) ) );
                array_push( $newOptions, [
                    'label' => $label, 
                    'value' => $value
                ]);
            }

            return $newOptions;

        }
        return [];
    }

}
