# Free GA4 Integration (freega4)

![Built for PrestaShop](https://img.shields.io/badge/Built%20for-PrestaShop-DF0067?logo=prestashop&logoColor=white)

A free module for integrating **Google Analytics 4 (GA4)** into PrestaShop.

It injects the GA4 `gtag.js` snippet and (optionally) sends basic **e-commerce events** using PrestaShop’s front events.

## Features

- Add the GA4 **Global Site Tag** (`gtag.js`) to your storefront.
- Back Office configuration:
  - Enable/disable the module output.
  - Set your **GA4 Measurement ID** (e.g. `G-XXXXXXXXXX`).
  - Enable **E-commerce tracking**.
- GA4 events implemented (when e-commerce tracking is enabled):
  - `view_item` on product page
  - `add_to_cart` when cart is updated via PrestaShop events
  - `purchase` on order confirmation

## Requirements

- PrestaShop **1.6+** (as declared by the module).  
- For e-commerce tracking the module notes it “Requires PrestaShop > 1.7.6.x”.
- A GA4 property and a valid Measurement ID.

## Installation

1. Copy the module folder to:
   
   `modules/freega4/`

2. In Back Office go to **Modules > Module Manager**.
3. Find **Free GA4 Integration** and click **Install**.

## Configuration

Go to **Modules > Module Manager > Free GA4 Integration > Configure**.

### Settings

- **Active**
  - If disabled, the module won’t output any tracking code.

- **GA4 ID**
  - Your GA4 Measurement ID (example: `G-XXXXXXXXXX`).

- **Ecommerce tracking**
  - Enables additional GA4 e-commerce events.

### Google Consent Mode

`freega4` does not include a consent manager integration selector.

Google Consent Mode v2 is now widely supported by modern cookie banners and CMPs, and is required for many Google Ads use cases in regions where Google requires consent signals. For this reason, consent should be configured in the cookie solution itself, not duplicated in this module.

Before enabling GA4 tracking, configure Google Consent Mode in your cookie banner or CMP. Examples of supported solutions that can manage Consent Mode directly include:

- **LG Cookies Law** / Linea Grafica
- **Art Cookie Choices Pro**
- **iubenda**
- **Cookiebot**
- Any equivalent CMP with Google Consent Mode v2 support

Only one system should update Google consent signals such as `analytics_storage`, `ad_storage`, `ad_user_data` and `ad_personalization`. If your cookie banner already manages Consent Mode, do not add a second bridge in the theme or in another module.

## What the module outputs

### Hooks used

The module registers and uses these hooks:

- `displayHeader` → outputs the GA4 global tag (`views/templates/hook/gtag.tpl`).
- `displayFooterProduct` → outputs `view_item` event (`views/templates/hook/productview.tpl`).
- `displayFooter` → outputs `add_to_cart` tracking (`views/templates/hook/ga4_jscart_vanilla.tpl`).
- `orderConfirmation` → outputs `purchase` event (`views/templates/hook/orderconfirmation.tpl`).

### Events details

- **Global Tag** (`gtag.tpl`)
  - Loads `https://www.googletagmanager.com/gtag/js?id=G-...`
  - Calls `gtag('config', 'G-...')`
  - Does not update Consent Mode. Consent signals must be managed by the configured cookie banner or CMP.

- **Product page** (`productview.tpl`)
  - Sends the `view_item` event.

- **Add to cart** (`ga4_jscart_vanilla.tpl`)
  - Listens to PrestaShop’s JS event `prestashop.on('updateCart', ...)`.
  - Sends `add_to_cart` when `event.reason.linkAction == 'add-to-cart'`.

- **Order confirmation** (`orderconfirmation.tpl`)
  - Sends `purchase` including order totals and item lines.

## Notes / Troubleshooting

- Make sure the GA4 ID is correct and the module is **Active**.
- Make sure Google Consent Mode is configured in your cookie banner or CMP before relying on GA4 or Google Ads measurement.
- If you don’t see events in GA4:
  - Use GA4 **DebugView**.
  - Check the browser console for JS errors.
  - Verify that `gtag` is defined on the page (the global tag must load).
- The `add_to_cart` listener uses Vanilla JavaScript and does not require jQuery.

## License

MIT.

## Author / Support

- Author: Tecnoacquisti.com
- Website: https://www.tecnoacquisti.com
