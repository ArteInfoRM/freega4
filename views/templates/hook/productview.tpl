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
<!-- Start GA4 PrestaShop Module - view_item - by https://www.tecnoacquisti.com -->
{if $product.id > 0}
    {if is_numeric($product.price_amount)}
        {assign var="product_price" value=$product.price_amount}
    {else}
        {assign var="product_price" value=0}
    {/if}
{literal}
<script type="text/javascript">
if (typeof gtag === 'function') {
    gtag("event", "view_item", {
        currency: "{/literal}{$currency.iso_code|escape:'javascript':'UTF-8'}{literal}",
        value: {/literal}{$product_price|floatval}{literal},
        items: [
            {
                item_id: "{/literal}{$product_sku|escape:'javascript':'UTF-8'}{literal}",
                item_name: "{/literal}{$product_name|escape:'javascript':'UTF-8'}{literal}",
                discount: {/literal}{$product.reduction|floatval}{literal},
                item_brand: "{/literal}{$manufacturer_name|escape:'javascript':'UTF-8'}{literal}",
                item_category: "{/literal}{$product_category|escape:'javascript':'UTF-8'}{literal}",
                price: {/literal}{$product.regular_price_amount|floatval}{literal},
                quantity: {/literal}{$product.quantity_wanted|intval}{literal}
            }
        ]
    });
}
    </script>
{/literal}
{/if}
<!-- End GA4 PrestaShop Module by https://www.tecnoacquisti.com -->
