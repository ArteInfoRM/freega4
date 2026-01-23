{*
**
*  2009-2023 Tecnoacquisti.com
*
*  For support feel free to contact us on our website at https://www.tecnoacquisti.com
*
*  @author    Tecnoacquisti.com <admin@arteinformatica.eu>
*  @copyright 2009-2023 Tecnoacquisti.com
*  @version   1.0
*  @license   One Paid Licence By WebSite Using This Module. No Rent. No Sell. No Share.
*
*}
<!-- Start GA4 PrestaShop Module by https://www.tecnoacquisti.com -->
{literal}
<script type="text/javascript">
gtag("event", "purchase", {
    transaction_id: "{/literal}{$order_id}{literal}",
    value: {/literal}{$total_paid}{literal},
    tax: {/literal}{$total_tax}{literal},
    shipping: {/literal}{$total_shipping}{literal},
    currency: "{/literal}{$currency.iso_code}{literal}",
    coupon: "{/literal}{$discounts}{literal}",
    items: [
    {/literal}
        {foreach from=$ord_details item=product name=foo}
        {if !$smarty.foreach.foo.last}
        {literal}
        {
            item_id: "{/literal}{$product['product_sku']}{literal}",
            item_name: "{/literal}{$product['product_name']}{literal}",
            currency: "{/literal}{$currency.iso_code}{literal}",
            item_brand: "{/literal}{$product['product_manufacturer']}{literal}",
            item_category: "{/literal}{$product['product_category']}{literal}",
            price: {/literal}{$product['product_price']}{literal},
            quantity: {/literal}{$product['product_quantity']}{literal}
        },
        {/literal}
        {else}
        {literal}
        {
            item_id: "{/literal}{$product['product_sku']}{literal}",
            item_name: "{/literal}{$product['product_name']}{literal}",
            currency: "{/literal}{$currency.iso_code}{literal}",
            item_brand: "{/literal}{$product['product_manufacturer']}{literal}",
            item_category: "{/literal}{$product['product_category']}{literal}",
            price: {/literal}{$product['product_price']}{literal},
            quantity: {/literal}{$product['product_quantity']}{literal}
        }]
    {/literal}
    {/if}
    {/foreach}
    {literal}
});
</script>
{/literal}
<!-- End GA4 PrestaShop Module by https://www.tecnoacquisti.com -->