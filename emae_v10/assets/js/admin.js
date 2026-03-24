document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-copy-url]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const value = btn.getAttribute('data-copy-url');
      try {
        await navigator.clipboard.writeText(value);
        btn.textContent = 'Copié';
        setTimeout(() => btn.textContent = 'Copier', 1500);
      } catch (e) {}
    });
  });
});
