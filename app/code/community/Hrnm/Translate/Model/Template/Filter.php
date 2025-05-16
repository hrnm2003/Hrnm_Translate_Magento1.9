<?php
/*
 * Hrnm_Translate Module

 * @category   Hrnm
 * @package    Hrnm_Translate
 * @version    1.0.0
 * @Magneto    1.9
 * @make       Create at 2016 and published in 2025 by Hamid Reza Nadi Moghadam
 * @copyright  Copyright (c) by Hrnm
 * @license    GPL-3.0

 */
class Hrnm_Translate_Model_Template_Filter extends Mage_Widget_Model_Template_Filter
{
    /**
     * The name used in the {{trans}} directive
     */
    const TRANS_DIRECTIVE_NAME = 'trans';

    /**
     * The regex to match interior portion of a {{trans "foo"}} translation directive
     */
    const TRANS_DIRECTIVE_REGEX = '/^\s*([\'"])([^\1]*?)(?<!\\\)\1(\s.*)?$/si';

    /**
     * Trans directive for localized strings support
     *
     * Usage:
     *
     *   {{trans "string to translate"}}
     *   {{trans "string to %var" var="$variable"}}
     *
     * The |escape modifier is applied by default, use |raw to override
     *
     * @param string[] $construction
     * @return string
     */
    public function transDirective($construction)
    {
        list($directive, $modifiers) = $this->explodeModifiers($construction[2], 'escape');

        list($text, $params) = $this->getTransParameters($directive);
        if (empty($text)) {
            return '';
        }

        $text = __($text, $params);
        return $this->applyModifiers($text, $modifiers);
    }

    /**
     * Parses directive construction into a multipart array containing the text value and key/value pairs of parameters
     *
     * @param string $value raw parameters
     * @return array always a two-part array in the format [value, [param, ...]]
     */
    protected function getTransParameters($value)
    {
        if (preg_match(self::TRANS_DIRECTIVE_REGEX, $value, $matches) !== 1) {
            return ['', []];  // malformed directive body; return without breaking list
        }

        $text = stripslashes($matches[2]);

        $params = [];
        if (!empty($matches[3])) {
            $params = $this->getParameters($matches[3]);
        }

        return [$text, $params];
    }

    

    /**
     * Explode modifiers out of a given string
     *
     * This will return the value and modifiers in a two-element array. Where no modifiers are present in the passed
     * value an array with a null modifier string will be returned
     *
     * Syntax: some text value, etc|modifier string
     *
     * Result: ['some text value, etc', 'modifier string']
     *
     * @param string $value
     * @param string $default assumed modifier if none present
     * @return array
     */
    protected function explodeModifiers($value, $default = null)
    {
        $parts = explode('|', $value, 2);
        if (2 === count($parts)) {
            return $parts;
        }
        return [$value, $default];
    }

    /**
     * Apply modifiers one by one, with specified params
     *
     * Modifier syntax: modifier1[:param1:param2:...][|modifier2:...]
     *
     * @param string $value
     * @param string $modifiers
     * @return string
     */
    protected function applyModifiers($value, $modifiers)
    {
        foreach (explode('|', $modifiers) as $part) {
            if (empty($part)) {
                continue;
            }
            $params = explode(':', $part);
            $modifier = array_shift($params);
            if (isset($this->_modifiers[$modifier])) {
                $callback = $this->_modifiers[$modifier];
                if (!$callback) {
                    $callback = $modifier;
                }
                array_unshift($params, $value);
                $value = call_user_func_array($callback, $params);
            }
        }
        return $value;
    }

    /**
     * Check if given variable is available for directive "Config"
     *
     * @param string $variable
     * @return bool
     */
    private function isAvailableConfigVariable($variable)
    {
        return in_array(
            $variable,
            array_column($this->configVariables->getData(), 'value')
        );
    }
}
