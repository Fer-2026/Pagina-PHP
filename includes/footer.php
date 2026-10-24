    <section class="cta-band">
      <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h2 class="h4 mb-0">¿Querés que tu hijo o hija estudie con nosotros?</h2>
        <a class="btn btn-accent btn-lg" href="inscripcion.php">Consultá la inscripción</a>
      </div>
    </section>
    <footer class="footer">
      <div class="container d-flex flex-wrap justify-content-between gap-2">
        <p class="mb-0"><?= e(APP_NAME) ?> · Educación Secundaria · Salta</p>
        <p class="mb-0">
          <a href="mailto:<?= e(APP_EMAIL) ?>"><?= e(APP_EMAIL) ?></a>
          <?php if (APP_ENV === 'local'): ?> · <span class="badge text-bg-warning">Entorno: local</span><?php endif; ?>
        </p>
      </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
