# Free GA4 Integration (freega4)

![Built for PrestaShop](https://img.shields.io/badge/Built%20for-PrestaShop-DF0067?logo=prestashop&logoColor=white)

A free module for integrating **Google Analytics 4 (GA4)** into PrestaShop.

It injects the GA4 `gtag.js` snippet and (optionally) sends basic **e-commerce events** using PrestaShop’s front events.

## Features

- Add the GA4 **Global Site Tag** (`gtag.js`) to your storefront.
- Back Office configuration:
  - Enable/disable the module output.
  - Set your **GA4 Measurement ID** (e.g. `G-XXXXXXXXXX`).
  - Enable **E-commerce tracking (beta)**.
  - Choose **Vanilla JS** implementation for the `add_to_cart` event (beta).
  - Select a consent manager integration for Google Consent Mode:
    - Disabled
    - LG Cookies Law
    - Art Cookie Choices Pro
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

- **Ecommerce tracking (beta)**
  - Enables additional GA4 e-commerce events.

- **Use vanilla JS**
  - Uses a Vanilla JS listener instead of jQuery for the `add_to_cart` event.

- **Consent manager integration**
  - When disabled, the module loads GA4 normally.
  - With LG Cookies Law or Art Cookie Choices Pro, the module starts Google Consent Mode with analytics storage denied and updates it only when analytics consent is detected.

- **LG Cookies Law purpose ID**
  - Numeric purpose ID used by LG Cookies Law for analytics consent.
  - Default value: `3`.
  - This field is used only when **LG Cookies Law** is selected and is hidden for other consent manager modes.

## What the module outputs

### Hooks used

The module registers and uses these hooks:

- `displayHeader` → outputs the GA4 global tag (`views/templates/hook/gtag.tpl`).
- `actionFrontControllerSetMedia` → loads the consent bridge script when a consent manager integration is enabled.
- `displayBackOfficeHeader` → loads the configuration helper script in the module configuration page.
- The configuration helper script is also loaded from the module configuration template for better compatibility with PrestaShop 9 back-office rendering.
- `displayFooterProduct` → outputs `view_item` event (`views/templates/hook/productview.tpl`).
- `displayFooter` → outputs `add_to_cart` tracking (`views/templates/hook/ga4_jscart.tpl` or `ga4_jscart_vanilla.tpl`).
- `orderConfirmation` → outputs `purchase` event (`views/templates/hook/orderconfirmation.tpl`).

### Events details

- **Global Tag** (`gtag.tpl`)
  - Loads `https://www.googletagmanager.com/gtag/js?id=G-...`
  - Calls `gtag('config', 'G-...')`
  - Initializes Google Consent Mode with denied analytics storage when a consent manager integration is enabled.

- **Product page** (`productview.tpl`)
  - Sends the `view_item` event.

- **Add to cart** (`ga4_jscart.tpl` / `ga4_jscart_vanilla.tpl`)
  - Listens to PrestaShop’s JS event `prestashop.on('updateCart', ...)`.
  - Sends `add_to_cart` when `event.reason.linkAction == 'add-to-cart'`.

- **Order confirmation** (`orderconfirmation.tpl`)
  - Sends `purchase` including order totals and item lines.

## Notes / Troubleshooting

- Make sure the GA4 ID is correct and the module is **Active**.
- If you don’t see events in GA4:
  - Use GA4 **DebugView**.
  - Check the browser console for JS errors.
  - Verify that `gtag` is defined on the page (the global tag must load).
- If your theme doesn’t load jQuery and you want `add_to_cart` tracking:
  - Enable **Use vanilla JS**.

## License

MIT.

## Author / Support

- Author: Tecnoacquisti.com
- Website: https://www.tecnoacquisti.com
