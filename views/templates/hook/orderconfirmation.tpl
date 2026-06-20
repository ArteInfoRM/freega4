{*
 * Free GA4 PrestaShop Module
 *
 * For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 * @author    Tecnoacquisti.com <helpdesk@tecnoacquisti.com>
 * @copyright 2009-2026 Tecnoacquisti.com
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   1.1.0
 *}
<!-- Start GA4 PrestaShop Module by https://www.tecnoacquisti.com -->
{literal}
<script type="text/javascript">
if (typeof gtag === 'function') {
gtag("event", "purchase", {
    transaction_id: "{/literal}{$order_id|escape:'javascript':'UTF-8'}{literal}",
    value: {/literal}{$total_paid|floatval}{literal},
    tax: {/literal}{$total_tax|floatval}{literal},
    shipping: {/literal}{$total_shipping|floatval}{literal},
    currency: "{/literal}{$currency.iso_code|escape:'javascript':'UTF-8'}{literal}",
    coupon: "{/literal}{$discounts|escape:'javascript':'UTF-8'}{literal}",
    items: [
    {/literal}
        {foreach from=$ord_details item=product name=foo}
        {if !$smarty.foreach.foo.last}
        {literal}
        {
            item_id: "{/literal}{$product['product_sku']|escape:'javascript':'UTF-8'}{literal}",
            item_name: "{/literal}{$product['product_name']|escape:'javascript':'UTF-8'}{literal}",
            currency: "{/literal}{$currency.iso_code|escape:'javascript':'UTF-8'}{literal}",
            item_brand: "{/literal}{$product['product_manufacturer']|escape:'javascript':'UTF-8'}{literal}",
            item_category: "{/literal}{$product['product_category']|escape:'javascript':'UTF-8'}{literal}",
            price: {/literal}{$product['product_price']|floatval}{literal},
            quantity: {/literal}{$product['product_quantity']|intval}{literal}
        },
        {/literal}
        {else}
        {literal}
        {
            item_id: "{/literal}{$product['product_sku']|escape:'javascript':'UTF-8'}{literal}",
            item_name: "{/literal}{$product['product_name']|escape:'javascript':'UTF-8'}{literal}",
            currency: "{/literal}{$currency.iso_code|escape:'javascript':'UTF-8'}{literal}",
            item_brand: "{/literal}{$product['product_manufacturer']|escape:'javascript':'UTF-8'}{literal}",
            item_category: "{/literal}{$product['product_category']|escape:'javascript':'UTF-8'}{literal}",
            price: {/literal}{$product['product_price']|floatval}{literal},
            quantity: {/literal}{$product['product_quantity']|intval}{literal}
        }]
    {/literal}
    {/if}
    {/foreach}
    {literal}
});
}
</script>
{/literal}
<!-- End GA4 PrestaShop Module by https://www.tecnoacquisti.com -->
