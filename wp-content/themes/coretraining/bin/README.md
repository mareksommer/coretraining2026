# Demo seed

Naplnění lokální WordPress instance ukázkovým obsahem pro prezentaci.

```bash
# z kořene repo
docker compose up -d --build

# počkat až WP naběhne, pak:
docker compose exec wordpress wp eval-file \
  wp-content/themes/coretraining/bin/seed-demo.php --allow-root
```

Seed vytvoří:
- stránky (Úvod, Služby, Studio, Kontakt, O Martinovi, Ochrana údajů)
- menu + logo
- 10 referencí z CSV
- 5 kurzů (podzimní termíny 2026 z živého webu)
- 6 ukázkových článků (nebo live články přes sync níže)

Skript je idempotentní — při opětovném spuštění přeskočí existující seed položky.

Kurzy s thumbnaily z live webu (nahradí seed demo kurzy):

```bash
docker compose exec wordpress wp eval-file \
  wp-content/themes/coretraining/bin/sync-live-courses.php --allow-root
```

Nejnovější články z live webu:

```bash
docker compose exec wordpress wp eval-file \
  wp-content/themes/coretraining/bin/sync-live-articles.php --allow-root
```

Demo URL: http://localhost:8081

> Port je `WORDPRESS_PORT` (default 8080). Pokud je 8080 obsazený:
> `WORDPRESS_PORT=8081 docker compose up -d`
