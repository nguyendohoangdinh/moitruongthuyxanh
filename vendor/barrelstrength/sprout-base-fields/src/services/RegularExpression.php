<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasefields\services;

use barrelstrength\sproutbasefields\SproutBaseFields;
use barrelstrength\sproutbasefields\web\assets\regularexpression\RegularExpressionFieldAsset;
use barrelstrength\sproutfields\fields\RegularExpression as RegularExpressionField;
use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\FieldInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class RegularExpression
 *
 */
class RegularExpression extends Component
{
    /**
     * @param Field $field
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \yii\base\Exception
     * @throws \yii\base\Exception
     */
    public function getSettingsHtml(Field $field): string
    {
        return Craft::$app->getView()->renderTemplate(
            'sprout-base-fields/_components/fields/formfields/regularexpression/settings',
            [
                'field' => $field,
            ]
        );
    }

    /**
     * @param Field                 $field
     * @param                       $value
     * @param ElementInterface|null $element
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     * @throws \yii\base\Exception
     */
    public function getInputHtml(Field $field, $value, ElementInterface $element = null): string
    {
        $view = Craft::$app->getView();
        $view->registerAssetBundle(RegularExpressionFieldAsset::class);

        $name = $field->handle;
        $inputId = Craft::$app->getView()->formatInputId($name);
        $namespaceInputId = Craft::$app->getView()->namespaceInputId($inputId);

        $fieldContext = SproutBaseFields::$app->utilities->getFieldContext($field, $element);

        return Craft::$app->getView()->renderTemplate(
            'sprout-base-fields/_components/fields/formfields/regularexpression/input',
            [
                'id' => $namespaceInputId,
                'field' => $field,
                'name' => $name,
                'value' => $value,
                'fieldContext' => $fieldContext
            ]
        );
    }

    /**
     * @param                                       $value
     * @param FieldInterface|RegularExpressionField $field
     *
     * @return bool
     */
    public function validate($value, FieldInterface $field): bool
    {
        $customPattern = $field->customPattern;

        if (!empty($customPattern)) {
            // Use backtick as delimiters
            $customPattern = '`'.$customPattern.'`';

            if (!preg_match($customPattern, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return error message
     *
     * @param mixed $field
     *
     * @return string
     */
    public function getErrorMessage($field): string
    {
        if ($field->customPattern && $field->customPatternErrorMessage) {
            return Craft::t('sprout-base-fields', $field->customPatternErrorMessage);
        }

        return Craft::t('sprout-base-fields', $field->name.' must be a valid pattern.');
    }

}
