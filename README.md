# PHP Native MVC (Sederhana) — Pariwisata
Alur: Controller → View (tanpa framework).

## Jalankan (Dev)
1) Buat DB `pariwisata`, import `schema.sql`.
2) Edit `config/db.php` jika perlu.
3) php -S localhost:8000 -t public
4) Buka /destinasi dan /destinasi/1

## Alur Request
Request → public/index.php (router) → DestinasiController → query DB → render(view) → layout.php
