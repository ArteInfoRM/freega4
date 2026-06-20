<?php
/**
 * Free GA4 PrestaShop Module
 *
 * For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 * @author    Tecnoacquisti.com <helpdesk@tecnoacquisti.com>
 * @copyright 2009-2026 Tecnoacquisti.com
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   1.0.1
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * This function updates your module from previous versions to the version 1.1,
 * usefull when you modify your database, or register a new hook ...
 * Don't forget to create one file per version.
 */
function upgrade_module_1_0_1($module)
{
    /*
     * 17/02/2023 - Giordano Bruno Update
     * PrestaShop 8.0 compatibility and added ecommerce events.
     */

    $module->unregisterHook('header');
    $module->unregisterHook('backOfficeHeader');
    $module->registerHook('displayHeader');
    $module->registerHook('displayFooterProduct');
    $module->registerHook('displayFooter');
    $module->registerHook('orderConfirmation');
    return true;
}
