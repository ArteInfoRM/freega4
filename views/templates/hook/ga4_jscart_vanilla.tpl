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
        document.addEventListener('DOMContentLoaded', function () {
            prestashop.on(
                'updateCart',
                function (event) {
                    if (event && event.reason && typeof event.resp !== 'undefined' && !event.resp.hasError) {
                        if (event.reason.linkAction =="add-to-cart" && event.reason.cart) {
                            for (var {id_product: i, id_product_attribute: a, name: n, quantity: q, category: c, price: p} of event.reason.cart.products) {
                                gtag("event", "add_to_cart", {
                                    currency: "{$currency.iso_code}",
                                    value: parseFloat(p),
                                    items: [
                                        {
                                            item_id: "" + i + "v" + a + "",
                                            item_name: "" + n + "",
                                            item_category: "" + c + "",
                                            price: parseFloat(p),
                                            quantity: q
                                        }
                                    ]
                                });
                            }
                            //console.log(event.reason.cart.products);
                        }
                    }
                }
            );
        });

    </script>
{/literal}
<!-- End GA4 PrestaShop Module by https://www.tecnoacquisti.com -->
