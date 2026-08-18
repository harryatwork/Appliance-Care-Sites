    </div><!-- /.admin-content -->
  </main>
</div><!-- /.admin-wrap -->
<script>
(function () {
  var sidebar = document.getElementById('sidebar');
  var overlay = document.getElementById('sidebarOverlay');
  var toggle = document.getElementById('sidebarToggle');
  function close() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }
  if (toggle) toggle.addEventListener('click', function () {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
  });
  if (overlay) overlay.addEventListener('click', close);

  // Copy-to-clipboard buttons (leads/contact fields)
  document.querySelectorAll('.copy-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var text = btn.getAttribute('data-copy') || '';
      var done = function () {
        var icon = btn.querySelector('i');
        var prevClass = icon.className;
        btn.classList.add('copied');
        icon.className = 'fa-solid fa-check';
        setTimeout(function () { btn.classList.remove('copied'); icon.className = prevClass; }, 1200);
      };
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(done);
      } else {
        var ta = document.createElement('textarea');
        ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta);
        done();
      }
    });
  });
})();
</script>
</body>
</html>
