<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasefields\services;

use barrelstrength\sproutbasefields\SproutBaseFields;
use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use yii\base\Component;

/**
 * Class Url
 */
class Url extends Component
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
            'sprout-base-fields/_components/fields/formfields/url/settings',
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
     * @throws \yii\base\Exception
     * @throws \yii\base\Exception
     */
    public function getInputHtml(Field $field, $value, ElementInterface $element = null): string
    {
        $name = $field->handle;
        $inputId = Craft::$app->getView()->formatInputId($name);
        $namespaceInputId = Craft::$app->getView()->namespaceInputId($inputId);

        $fieldContext = SproutBaseFields::$app->utilities->getFieldContext($field, $element);

        return Craft::$app->getView()->renderTemplate('sprout-base-fields/_components/fields/formfields/url/input', [
                'namespaceInputId' => $namespaceInputId,
                'id' => $inputId,
                'name' => $name,
                'value' => $value,
                'fieldContext' => $fieldContext,
                'placeholder' => $field->placeholder,
                'element' => $element
            ]
        );
    }

    /**
     * Validates a phone number against a given mask/pattern
     *
     * @param                  $value
     * @param Field            $field
     *
     * @return bool
     */
    public function validate($value, Field $field = null): bool
    {
        if ($field) {
            $customPattern = $field->customPattern;
            $checkPattern = $field->customPatternToggle;

            if ($customPattern && $checkPattern) {
                // Use backtick as delimiters as they are invalid characters for emails
                $customPattern = '`'.$customPattern.'`';

                if (!preg_match($customPattern, $value)) {
                    return false;
                }
            }
        }

        $path = parse_url($value, PHP_URL_PATH);
        $encodedPath = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encodedPath), $value);

        return !(filter_var($url, FILTER_VALIDATE_URL) === false);
    }

    /**
     * @param Field $field
     *
     * @return string
     */
    public function getErrorMessage(Field $field): string
    {
        /** @var Field $field */
        if ($field->customPatternToggle && $field->customPatternErrorMessage) {
            return Craft::t('sprout-base-fields', $field->customPatternErrorMessage);
        }

        return Craft::t('sprout-base-fields', $field->name.' must be a valid URL.');
    }
}
