<?php

/**
 * Free GA4 PrestaShop Module
 *
 * For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 * @author    Tecnoacquisti.com <helpdesk@tecnoacquisti.com>
 * @copyright 2009-2026 Tecnoacquisti.com
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @version   1.1.0
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Upgrade module to version 1.0.9.
 *
 * @param Freega4 $module Module instance
 *
 * @return bool
 */
function upgrade_module_1_0_9($module)
{
    $hooks = [
        'displayBackOfficeHeader',
        'actionFrontControllerSetMedia',
    ];

    foreach ($hooks as $hook) {
        if (!$module->registerHook($hook)) {
            return false;
        }
    }

    $manager = (string) Configuration::get('FREEGA4_CONSENT_MANAGER');

    if (!in_array($manager, ['disabled', 'lg', 'artcookie'], true)) {
        $manager = 'disabled';
    }

    $purpose = (int) Configuration::get('FREEGA4_LG_PURPOSE');

    Configuration::updateValue('FREEGA4_CONSENT_MANAGER', $manager);
    Configuration::updateValue('FREEGA4_LG_PURPOSE', $purpose > 0 ? $purpose : 3);

    return true;
}
