<?php
/**
 * Free GA4 PrestaShop Module
 *
 * For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 * @author    Tecnoacquisti.com <helpdesk@tecnoacquisti.com>
 * @copyright 2009-2026 Tecnoacquisti.com
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   1.0.9
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Freega4 extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'freega4';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.9';
        $this->author = 'Tecnoacquisti.com';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Free GA4 Integration');
        $this->description = $this->l('Free GA4 (Google Analytics 4) integration module in prestashop');

        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('FREEGA4_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('orderConfirmation') &&
            Configuration::updateValue('FREEGA4_GTAG_ID', '') &&
            Configuration::updateValue('FREEGA4_ACTIVE', 0) &&
            Configuration::updateValue('FREEGA4_ECOMMERCE', 0) &&
            Configuration::updateValue('FREEGA4_VANILLAJS', 1) &&
            Configuration::updateValue('FREEGA4_CONSENT_MANAGER', 'disabled') &&
            Configuration::updateValue('FREEGA4_LG_PURPOSE', 3);
    }

    public function uninstall()
    {
        Configuration::deleteByName('FREEGA4_LIVE_MODE');
        Configuration::deleteByName('FREEGA4_ACTIVE');
        Configuration::deleteByName('FREEGA4_GTAG_ID');
        Configuration::deleteByName('FREEGA4_ECOMMERCE');
        Configuration::deleteByName('FREEGA4_VANILLAJS');
        Configuration::deleteByName('FREEGA4_CONSENT_MANAGER');
        Configuration::deleteByName('FREEGA4_LG_PURPOSE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $output = '';
        $useSsl = (bool)Configuration::get('PS_SSL_ENABLED_EVERYWHERE') || (bool)Configuration::get('PS_SSL_ENABLED');
        $shop_base_url = $this->context->link->getBaseLink((int)$this->context->shop->id, $useSsl);

        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitFreega4Module')) == true) {
            $output .= $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign([
            'shop_base_url' => $shop_base_url,
        ]);

        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
        $output .= $this->renderForm();
        $output .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/copyright.tpl');

        return $output;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFreega4Module';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Active'),
                        'name' => 'FREEGA4_ACTIVE',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Ecommerce tracking (beta)'),
                        'name' => 'FREEGA4_ECOMMERCE',
                        'desc' => $this->l('Requires PrestaShop > 1.7.6.X'),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Use vanilla JS'),
                        'name' => 'FREEGA4_VANILLAJS',
                        'desc' => $this->l('Use Vanilla JS instead of jQuery for the add_to_cart event (beta).'),
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ],
                        ],
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter the GA4 Measurement ID, for example G-XXXXXXXXXX.'),
                        'name' => 'FREEGA4_GTAG_ID',
                        'label' => $this->l('GA4 ID'),
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Consent manager integration'),
                        'name' => 'FREEGA4_CONSENT_MANAGER',
                        'desc' => $this->l('Choose which consent banner should control GA4 analytics consent.'),
                        'options' => [
                            'query' => [
                                [
                                    'id' => 'disabled',
                                    'name' => $this->l('Disabled'),
                                ],
                                [
                                    'id' => 'lg',
                                    'name' => $this->l('LG Cookies Law (Linea Grafica)'),
                                ],
                                [
                                    'id' => 'artcookie',
                                    'name' => $this->l('Art Cookie Choices Pro'),
                                ],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'col' => 1,
                        'type' => 'text',
                        'label' => $this->l('LG Cookies Law purpose ID'),
                        'name' => 'FREEGA4_LG_PURPOSE',
                        'class' => 'fixed-width-xs',
                        'form_group_class' => 'freega4-lg-purpose-row',
                        'desc' => $this->l('Numeric ID of the LG Cookies Law Analytics purpose. Default is 3.'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return [
            'FREEGA4_ACTIVE' => Tools::getValue('FREEGA4_ACTIVE', Configuration::get('FREEGA4_ACTIVE')),
            'FREEGA4_GTAG_ID' => Tools::getValue('FREEGA4_GTAG_ID', Configuration::get('FREEGA4_GTAG_ID')),
            'FREEGA4_ECOMMERCE' => Tools::getValue('FREEGA4_ECOMMERCE', Configuration::get('FREEGA4_ECOMMERCE')),
            'FREEGA4_VANILLAJS' => Tools::getValue('FREEGA4_VANILLAJS', Configuration::get('FREEGA4_VANILLAJS')),
            'FREEGA4_CONSENT_MANAGER' => Tools::getValue(
                'FREEGA4_CONSENT_MANAGER',
                $this->getConsentManagerMode()
            ),
            'FREEGA4_LG_PURPOSE' => Tools::getValue(
                'FREEGA4_LG_PURPOSE',
                (int)Configuration::get('FREEGA4_LG_PURPOSE') > 0 ? (int)Configuration::get('FREEGA4_LG_PURPOSE') : 3
            ),
        ];
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $output = '';
        $this->_errors = [];
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            if ($key === 'FREEGA4_GTAG_ID') {
                $measurementId = strtoupper(trim((string)Tools::getValue($key)));

                if ($measurementId !== '' && !preg_match('/^G-[A-Z0-9]{4,32}$/', $measurementId)) {
                    $this->_errors[] = $this->l('GA4 ID format is invalid.');
                    continue;
                }

                Configuration::updateValue($key, pSQL($measurementId));
            } elseif ($key === 'FREEGA4_CONSENT_MANAGER') {
                $manager = (string)Tools::getValue($key);
                $allowedManagers = ['disabled', 'lg', 'artcookie'];

                if (!in_array($manager, $allowedManagers, true)) {
                    $this->_errors[] = $this->l('Consent manager integration is invalid.');
                    continue;
                }

                Configuration::updateValue($key, $manager);
            } elseif ($key === 'FREEGA4_LG_PURPOSE') {
                $purpose = (int)Tools::getValue($key);

                if ($purpose <= 0 || $purpose > 999) {
                    $this->_errors[] = $this->l('LG Cookies Law purpose ID must be a positive number.');
                    continue;
                }

                Configuration::updateValue($key, $purpose);
            } else {
                Configuration::updateValue($key, (int)Tools::getValue($key));
            }
        }

        // If we collected any errors, build and return them immediately
        if (!empty($this->_errors)) {
            $errors_message = '';
            foreach ($this->_errors as $error) {
                if (!empty($errors_message)) {
                    $errors_message .= "\n";
                }
                $errors_message .= $error . ' ' . $this->l('Settings failed');
            }

            return $this->displayError($errors_message);
        }

        // No errors: display confirmation
        $output .= $this->displayConfirmation($this->l('Settings updated'));
        return $output;
    }

    /**
     * Return the configured consent manager mode.
     *
     * @return string
     */
    protected function getConsentManagerMode()
    {
        $manager = (string)Configuration::get('FREEGA4_CONSENT_MANAGER');

        if (in_array($manager, ['disabled', 'lg', 'artcookie'], true)) {
            return $manager;
        }

        return 'disabled';
    }

    /**
     * Register front-office consent bridge scripts.
     *
     * @param array $params Hook parameters
     *
     * @return void
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        if ((int)Configuration::get('FREEGA4_ACTIVE') !== 1) {
            return;
        }

        $manager = $this->getConsentManagerMode();

        if ($manager === 'lg') {
            $purpose = (int)Configuration::get('FREEGA4_LG_PURPOSE');
            $purpose = $purpose > 0 ? $purpose : 3;

            if (class_exists('Media') && method_exists('Media', 'addJsDef')) {
                Media::addJsDef(['freega4LgPurpose' => $purpose]);
            }

            $this->registerFrontJavascript(
                'module-' . $this->name . '-lg-bridge',
                'views/js/freega4-lg-bridge.js'
            );
        } elseif ($manager === 'artcookie') {
            $this->registerFrontJavascript(
                'module-' . $this->name . '-artcookie-bridge',
                'views/js/freega4-artcookie-bridge.js'
            );
        }
    }

    /**
     * Register back-office assets.
     *
     * @param array $params Hook parameters
     *
     * @return void
     */
    public function hookDisplayBackOfficeHeader($params)
    {
        if (Tools::getValue('configure') !== $this->name) {
            return;
        }

        $this->registerBackJavascript('module-' . $this->name . '-back', 'views/js/back.js');
    }

    /**
     * Register a front-office JavaScript file with legacy fallback.
     *
     * @param string $id Asset identifier
     * @param string $relativePath Module-relative asset path
     *
     * @return void
     */
    protected function registerFrontJavascript($id, $relativePath)
    {
        $controller = $this->context->controller;

        if (method_exists($controller, 'registerJavascript')) {
            $controller->registerJavascript(
                $id,
                $this->_path . $relativePath,
                [
                    'position' => 'head',
                    'priority' => 50,
                    'attributes' => 'defer',
                ]
            );

            return;
        }

        if (method_exists($controller, 'addJS')) {
            $controller->addJS($this->_path . $relativePath);
        }
    }

    /**
     * Register a back-office JavaScript file with legacy fallback.
     *
     * @param string $id Asset identifier
     * @param string $relativePath Module-relative asset path
     *
     * @return void
     */
    protected function registerBackJavascript($id, $relativePath)
    {
        $controller = $this->context->controller;

        if (method_exists($controller, 'addJS')) {
            $controller->addJS($this->_path . $relativePath);

            return;
        }

        if (method_exists($controller, 'registerJavascript')) {
            $controller->registerJavascript(
                $id,
                $this->_path . $relativePath,
                [
                    'position' => 'bottom',
                    'priority' => 50,
                ]
            );
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader()
    {
        $active = (int)Configuration::get('FREEGA4_ACTIVE');
        $gtag_id = (string)Configuration::get('FREEGA4_GTAG_ID');
        $manager = $this->getConsentManagerMode();
        $lgPurpose = (int)Configuration::get('FREEGA4_LG_PURPOSE');

        $this->smarty->assign([
            'gtag_id' => $gtag_id,
            'freega4_consent_manager' => $manager,
            'freega4_lg_purpose' => $lgPurpose > 0 ? $lgPurpose : 3,
        ]);

        if ($active === 1 && $gtag_id !== '') {
            return $this->display(__FILE__, 'gtag.tpl');
        }
    }

    public function hookDisplayFooterProduct()
    {
        $active = (int)Configuration::get('FREEGA4_ACTIVE');
        $ecommerce = (int)Configuration::get('FREEGA4_ECOMMERCE');
        $gtag_id = (string)Configuration::get('FREEGA4_GTAG_ID');

        if ($active === 1 && $ecommerce === 1 && $gtag_id !== '') {
            $id_product = (int)Tools::getValue('id_product');
            $lang_id = (int)Configuration::get('PS_LANG_DEFAULT');
            $product = new Product($id_product, false, $lang_id);
            $attribute_id = (int)Tools::getValue('id_product_attribute');
            $product_sku = $id_product . 'v' . $attribute_id;
            $product_category = $product->category;
            if ($product_category == '') {
                $product_category = $this->l('Not detected');
            }
            $id_manufacturer = $product->id_manufacturer;
            $manufacturer_name = Manufacturer::getNameById((int)$id_manufacturer);

            $this->smarty->assign([
                'product_sku' => $product_sku,
                'product_name' => $product->name,
                'product_category' => $product_category,
                'manufacturer_name' => $manufacturer_name,
            ]);

            return $this->display(__FILE__, 'productview.tpl');
        }
    }

    public function hookOrderConfirmation($params)
    {
        $active = (int)Configuration::get('FREEGA4_ACTIVE');
        $ecommerce = (int)Configuration::get('FREEGA4_ECOMMERCE');
        $gtag_id = (string)Configuration::get('FREEGA4_GTAG_ID');

        if ($active === 1 && $ecommerce === 1 && $gtag_id !== '') {
            $order = $params['order'];
            $order_id = $order->reference;
            $total_paid = $order->total_paid;
            $total_paid_tax_excl = $order->total_paid_tax_excl;
            $total_tax = $total_paid - $total_paid_tax_excl;
            $total_shipping = $order->total_shipping;
            $total_discounts = (float)$order->total_discounts;
            $discounts = '';
            if ($total_discounts > 0) {
                $sql = 'SELECT `name` FROM `' . _DB_PREFIX_ . 'order_cart_rule` WHERE `id_order` = ' . (int)$order->id . ';';
                if ($results = Db::getInstance()->ExecuteS($sql)) {
                    foreach ($results as $row) {
                        $name = $row['name'];
                        $discounts = $discounts . '#' . $name;
                    }
                }
            }

            $order_list_details = [];
            $ord_details = $order->getOrderDetailList();
            foreach ($ord_details as $detail) {
                $product_id = $detail['product_id'];
                $product_attribute_id = $detail['product_attribute_id'];
                $product_sku = $product_id . 'v' . $product_attribute_id;
                $lang_id = (int)Configuration::get('PS_LANG_DEFAULT');
                $product = new Product($product_id, false, $lang_id);
                $product_name = $product->name;
                $product_category = $product->category;
                $id_manufacturer = $product->id_manufacturer;
                $manufacturer_name = Manufacturer::getNameById((int)$id_manufacturer);
                if ($product_category == '') {
                    $product_category = $this->l('Not detected');
                }
                $product_price = $detail['product_price'];
                $product_quantity = $detail['product_quantity'];
                $order_list_details[] = [
                    'product_sku' => $product_sku,
                    'product_name' => $product_name,
                    'product_category' => $product_category,
                    'product_manufacturer' => $manufacturer_name,
                    'product_price' => (float)$product_price,
                    'product_quantity' => $product_quantity
                ];
            }
            $this->smarty->assign([
                'ecommerce' => $ecommerce,
                'order_id' => $order_id,
                'total_paid' => (float)$total_paid,
                'total_paid_tax_excl' => (float)$total_paid_tax_excl,
                'total_tax' => (float)$total_tax,
                'total_shipping' => (float)$total_shipping,
                'discounts' => $discounts,
                'ord_details' => $order_list_details,
            ]);

            return $this->display(__FILE__, 'orderconfirmation.tpl');
        }
    }

    public function hookDisplayFooter()
    {
        $active = (int)Configuration::get('FREEGA4_ACTIVE');
        $gtag_id = (string)Configuration::get('FREEGA4_GTAG_ID');
        $ecommerce = (int)Configuration::get('FREEGA4_ECOMMERCE');
        $vanilla_js = (int)Configuration::get('FREEGA4_VANILLAJS');

        if ($active === 1 && $ecommerce === 1 && $vanilla_js === 1 && $gtag_id !== '') {
            return $this->display(__FILE__, 'ga4_jscart_vanilla.tpl');
        } elseif ($active === 1 && $ecommerce === 1 && $gtag_id !== '') {
            return $this->display(__FILE__, 'ga4_jscart.tpl');
        }
    }
}
