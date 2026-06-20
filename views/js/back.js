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
  if (window.freega4BackOfficeReady) {
    return;
  }

  window.freega4BackOfficeReady = true;

  function findFormGroup(field) {
    var current = field;

    if (typeof field.closest === 'function') {
      return field.closest('.freega4-lg-purpose-row, .form-group, .mb-3, .row');
    }

    while (current && current.parentNode) {
      if (
        current.className &&
        typeof current.className === 'string' &&
        (
          current.className.indexOf('freega4-lg-purpose-row') !== -1 ||
          current.className.indexOf('form-group') !== -1 ||
          current.className.indexOf('mb-3') !== -1 ||
          current.className.indexOf('row') !== -1
        )
      ) {
        return current;
      }

      current = current.parentNode;
    }

    return null;
  }

  function setupConsentManagerFields() {
    var managerField = document.querySelector('[name="FREEGA4_CONSENT_MANAGER"]');
    var purposeField = document.querySelector('[name="FREEGA4_LG_PURPOSE"]');
    var purposeRow = document.querySelector('.freega4-lg-purpose-row');

    if (!managerField || !purposeField) {
      return;
    }

    if (!purposeRow) {
      purposeRow = findFormGroup(purposeField);
    }

    if (!purposeRow) {
      return;
    }

    function syncPurposeRow() {
      purposeRow.style.display = managerField.value === 'lg' ? '' : 'none';
    }

    syncPurposeRow();
    managerField.addEventListener('change', syncPurposeRow, false);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupConsentManagerFields, false);
  } else {
    setupConsentManagerFields();
  }
})();
