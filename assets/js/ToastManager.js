/**
 * ToastManager is a utility class to show Bootstrap toasts dynamically.
 * 
 * Usage:
 *   ToastManager.show("Your message here", "success");
 */
class ToastManager {
  /**
   * Shows a toast notification.
   * @param {string} message - The text to display inside the toast.
   * @param {string} type - Bootstrap color type: "success", "danger", "info", "warning". Defaults to "success".
   */
  static show(message, type = 'success') {
      const toastHtml = ToastManager.getToastHtml(message, type);
      const toast = $(toastHtml);
      $('body').append(toast);
      const bsToast = new bootstrap.Toast(toast[0]);
      bsToast.show();
      toast.on('hidden.bs.toast', () => toast.remove());
  }

  /**
   * Returns the HTML string for the toast.
   * You can move this to a separate HTML file and fetch it via AJAX if needed.
   * @param {string} message 
   * @param {string} type 
   * @returns {string}
   */
  static getToastHtml(message, type) {
      return `
      <div class="toast align-items-center text-white bg-${type} border-0" 
           role="alert" aria-live="assertive" aria-atomic="true"
           style="position: fixed; bottom: 20px; right: 20px; z-index: 10000;">
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                  data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
      `;
  }
}
