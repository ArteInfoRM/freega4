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

(function () {
  var consentCookieName = 'displayCookieConsent';
  var preferencesCookieName = 'displayCookieConsentPreferences';
  var lastGranted = null;

  function ensureGtag() {
    window.dataLayer = window.dataLayer || [];

    if (typeof window.gtag !== 'function') {
      window.gtag = function () {
        window.dataLayer.push(arguments);
      };
    }
  }

  function getCookieValue(name) {
    var parts = document.cookie ? document.cookie.split(';') : [];

    for (var i = 0; i < parts.length; i++) {
      var part = parts[i].replace(/^\s+|\s+$/g, '');
      var separator = part.indexOf('=');

      if (separator < 0) {
        continue;
      }

      if (part.substring(0, separator) === name) {
        return decodeURIComponent(part.substring(separator + 1));
      }
    }

    return null;
  }

  function getStoredPreferences() {
    var raw = getCookieValue(preferencesCookieName);

    if (!raw) {
      return null;
    }

    try {
      return JSON.parse(raw);
    } catch (e) {
      return null;
    }
  }

  function hasAnalyticsConsent() {
    var preferences = getStoredPreferences();
    var legacyConsent;

    if (preferences) {
      return !!preferences.analytics || !!preferences.performance;
    }

    legacyConsent = getCookieValue(consentCookieName);

    return legacyConsent === 'y';
  }

  function updateConsent() {
    var granted = hasAnalyticsConsent();

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

  function delayedUpdate() {
    window.setTimeout(updateConsent, 0);
    window.setTimeout(updateConsent, 250);
  }

  delayedUpdate();

  document.addEventListener('click', function (event) {
    var target = event.target;

    if (!target) {
      return;
    }

    if (
      target.id === 'InformativaAccetto' ||
      target.id === 'InformativaReject' ||
      target.id === 'InformativaSavePreferences' ||
      target.id === 'close_cookie_block' ||
      (
        typeof target.closest === 'function' &&
        target.closest('#InformativaAccetto, #InformativaReject, #InformativaSavePreferences, #close_cookie_block')
      )
    ) {
      delayedUpdate();
    }
  }, false);
})();
