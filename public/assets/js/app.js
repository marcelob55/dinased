(function () {
  // Toggle password visibility
  document.querySelectorAll('[data-toggle-password]').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = document.querySelector(btn.getAttribute('data-toggle-password'));
      if (!target) return;
      target.type = target.type === 'password' ? 'text' : 'password';
      btn.classList.toggle('is-on');
    });
  });

  // Auto-focus primer input visible
  const firstInput = document.querySelector('input:not([type=hidden])');
  if (firstInput) firstInput.focus({ preventScroll: true });
})();
