<?php

namespace jordanbeattie\blockstyles\fields;

use benf\neo\elements\Block as NeoBlock;
use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\elements\db\ElementQueryInterface;
use craft\fields\conditions\OptionsFieldConditionRule;
use craft\helpers\Cp;
use craft\helpers\Html;
use craft\helpers\StringHelper;
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

    public static function valueType(): string
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

    public function normalizeValue(mixed $value, ElementInterface $element = null): mixed
    {
        return $value;
    }

    protected function inputHtml(mixed $value, ElementInterface $element = null): string
    {

        /* Get config */
        $config = Craft::$app->config->getConfigFromFile('block-styles');

        /* Set default options */
        $options = $config['default'] ?? [
            [ 'label' => 'One', 'value' => 'one' ],
            [ 'label' => 'Two', 'value' => 'two' ],
        ];

        /* Get block options */
        if( $element instanceof NeoBlock )
        {

            /* Get field handle */
            $fieldHandle = Craft::$app->getFields()->getFieldById( $element->getType()->fieldId )->handle ?? 'default';

            /* Get block handle */
            $blockHandle = $element->getType()->handle ?? 'default';

            /* Get fieldHandle => blockHandle OR fieldHandle => default OR defaultOptions */
            $options = $config[ $fieldHandle ][ $blockHandle ] ?? ( $config[ $fieldHandle ]['default'] ?? $options );

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

}
