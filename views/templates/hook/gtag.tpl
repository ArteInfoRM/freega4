{*
 * Free GA4 PrestaShop Module
 *
 * For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 * @author    Tecnoacquisti.com <helpdesk@tecnoacquisti.com>
 * @copyright 2009-2026 Tecnoacquisti.com
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   1.1.1
 *}
<!-- Start Free GA4 PrestaShop Module by https://www.tecnoacquisti.com -->
{literal}
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{/literal}{$gtag_id|escape:'htmlall':'UTF-8'}{literal}');
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id={/literal}{$gtag_id|escape:'htmlall':'UTF-8'}{literal}"></script>
{/literal}
<!-- End Free GA4 PrestaShop Module -->
