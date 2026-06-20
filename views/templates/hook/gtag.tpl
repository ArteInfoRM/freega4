{*
 * Free GA4 PrestaShop Module
 *
 * For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 * @author    Tecnoacquisti.com <helpdesk@tecnoacquisti.com>
 * @copyright 2009-2026 Tecnoacquisti.com
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   1.0.9
 *}
<!-- Start Free GA4 PrestaShop Module by https://www.tecnoacquisti.com -->
{literal}
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        {/literal}
        {if $freega4_consent_manager != 'disabled'}
        {literal}
        gtag('consent', 'default', {
            ad_personalization: 'denied',
            ad_storage: 'denied',
            ad_user_data: 'denied',
            analytics_storage: 'denied',
            wait_for_update: 500
        });
        {/literal}
        {/if}
        {if $freega4_consent_manager == 'lg'}
        window.freega4LgPurpose = {$freega4_lg_purpose|intval};
        {/if}
        {literal}
        gtag('js', new Date());

        gtag('config', '{/literal}{$gtag_id|escape:'htmlall':'UTF-8'}{literal}');
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id={/literal}{$gtag_id|escape:'htmlall':'UTF-8'}{literal}"></script>
{/literal}
<!-- End Free GA4 PrestaShop Module -->
