<?php

namespace mission10\blockstyles\conditions;

use craft\base\conditions\BaseMultiSelectConditionRule;
use craft\fields\conditions\FieldConditionRuleInterface;
use craft\fields\conditions\FieldConditionRuleTrait;

/**
 * Condition rule for the Block Styles selectize fields (theme / pattern /
 * background / style). They store a single string from a config-driven option
 * set but don't extend BaseOptionsField, so Craft's OptionsFieldConditionRule
 * throws for them. This mirrors it, sourcing options from the field itself.
 */
class BlockStyleConditionRule extends BaseMultiSelectConditionRule implements FieldConditionRuleInterface
{
    use FieldConditionRuleTrait;

    protected bool $includeEmptyOperators = true;

    protected function options(): array
    {
        $field = $this->field();

        return method_exists($field, 'getConditionOptions') ? $field->getConditionOptions() : [];
    }

    protected function elementQueryParam(): string|array|null
    {
        return null;
    }

    protected function matchFieldValue($value): bool
    {
        return $this->matchValue($value);
    }
}
