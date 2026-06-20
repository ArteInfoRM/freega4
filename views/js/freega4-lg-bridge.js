/**
 * Free GA4 PrestaShop Module
 *
 * For support feel free to contact us on our website at https://www.tecnoacquisti.com
 *
 * @author    Tecnoacquisti.com <helpdesk@tecnoacquisti.com>
 * @copyright 2009-2026 Tecnoacquisti.com
 * @license   https://opensource.org/licenses/MIT MIT License
 * @version   1.1.0
 */

(function () {
  var purposeKey = 'lgcookieslaw_purpose_' + (window.freega4LgPurpose || 3);
  var lastGranted = null;

  function ensureGtag() {
    window.dataLayer = window.dataLayer || [];

    if (typeof window.gtag !== 'function') {
      window.gtag = function () {
        window.dataLayer.push(arguments);
      };
    }
  }

  function hasAnalyticsConsent() {
    var values = window.lgcookieslaw_cookie_values;

    return typeof values === 'object' && values && values[purposeKey] === true;
  }

  function updateConsent() {
    var granted = !!hasAnalyticsConsent();

    if (lastGranted === granted) {
      return;
    }

    lastGranted = granted;
    ensureGtag();

    window.gtag('consent', 'update', {
      ad_personalization: 'denied',
      ad_storage: 'denied',
      ad_user_data: 'denied',
      analytics_storage: granted ? 'granted' : 'denied',
    });
  }

  var tries = 0;
  var maxTries = 40;
  var poll = window.setInterval(function () {
    tries++;

    if (typeof window.lgcookieslaw_cookie_values === 'object' || tries >= maxTries) {
      window.clearInterval(poll);
      updateConsent();
    }
  }, 500);

  [
    'lgcookieslaw_onaccept',
    'lgcookieslaw_onchange',
    'lgcookieslaw_onrevoke',
    'lgConsentGiven',
    'lgConsentUpdated',
    'lgConsentRevoked',
  ].forEach(function (eventName) {
    window.addEventListener(eventName, updateConsent, false);
  });
})();
