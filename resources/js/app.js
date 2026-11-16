// Bootstrap's JS bundle is loaded from the CDN in layouts/partials/scripts.blade.php.

// Re-open a modal after a failed submit, e.g. a wrong password in "Delete Account".
document.querySelectorAll('[data-show-on-load]').forEach((el) => {
    window.bootstrap.Modal.getOrCreateInstance(el).show();
});
