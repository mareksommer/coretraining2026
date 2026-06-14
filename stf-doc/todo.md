# TODO — arenacallup-web-wordpress

## Před prvním nasazením na produkci

- [ ] **Hesla MySQL** — v `infrsastructure-ds/cust/pri.yml` nahradit `changeme` a `changeme_root` reálnými hesly pro `callup-db`
- [ ] **GTM container ID** — v `pri.yml` odkomentovat a doplnit `GTM_CONTAINER_ID: GTM-XXXXXXX`
- [ ] **GitHub secrets** — přidat `DOCKERHUB_USERNAME` a `DOCKERHUB_TOKEN` do repozitáře `arenacallup-web-wordpress` (potřebuje `.github/workflows/build-push.yml`)
- [ ] **Adresáře na stfworker02** — vytvořit na serveru:
  ```bash
  mkdir -p /mnt/callup_db
  mkdir -p /mnt/callup_web_uploads
  ```
- [ ] **DNS** — nastavit A záznam `www.callup.stafio.cz` → IP adresa tcpro (77.78.90.63)
- [ ] **Rate limiting + proxy** — v `functions.php` je rate limiting postaven na `HTTP_X_FORWARDED_FOR` (první IP z headeru). Ověřit, jestli Traefik na tcpro posílá tento header a jestli je důvěryhodný (Traefik by měl přepisovat, nikoli pouze přidávat)

---

## Po prvním spuštění WordPressu (jednorázový setup)

Po prvním `docker compose up` (lokálně) nebo po prvním nasazení (produkce) je nutné dokončit WordPress instalaci a konfiguraci:

```bash
# Lokální vývoj:
docker compose exec wordpress wp theme activate arenacallup --allow-root
docker compose exec wordpress wp rewrite structure '/%postname%/' --allow-root
docker compose exec wordpress wp rewrite flush --allow-root
```

### Stránky k vytvoření ve WP administraci

Vytvořit jako **Stránky** (Pages) s přesnými sloxy (slug):

| Název stránky          | Slug                          | Poznámka |
|------------------------|-------------------------------|----------|
| Registrace             | `registrace`                  | šablona `page-registrace.php` se použije automaticky |
| Úspěšná registrace     | `uspesna-registrace`          | šablona `page-uspesna-registrace.php` se použije automaticky |
| Naše dovednosti        | `nase-dovednosti`             | obsah viz RFC sekce „Naše dovednosti" |
| Podmínky používání služeb | `podminky-pouzivani-sluzeb` | obsah zkopírovat ručně z původního webu (viz níže) |

### Nastavení domovské stránky

V administraci **Nastavení → Čtení** nastavit „Úvodní stránka zobrazuje" → **Statická stránka** a vybrat existující stránku jako Úvodní stránku, nebo ponechat výchozí nastavení (téma pak používá `front-page.php` automaticky).

---

## Obsah — ruční přenos

- [ ] **Podmínky používání služeb** — stránka `arenacallup.cz/podminky-pouzivani-sluzeb` je renderována Nuxtem, obsah nelze stáhnout automaticky. Otevřít v prohlížeči, zkopírovat text a vložit do WordPress editoru na stránce `podminky-pouzivani-sluzeb`
- [ ] **Naše dovednosti** — obsah je v RFC (sekce „Naše dovednosti"), vložit do WordPress stránky `nase-dovednosti`

---

## Callup admin nastavení

- [ ] **Cost centers** — v administraci **Nastavení → Callup** vyplnit JSON s hodnotami pro pole „Chci pracovat jako" ve formuláři registrace. Formát:
  ```json
  [
    {"id": "...", "label": "..."},
    {"id": "...", "label": "..."}
  ]
  ```
  Hodnoty zjistit z původní aplikace nebo z backendu.

---

## Cookie consent

- [ ] Po nasazení aktivovat plugin **Cookie Notice & Compliance for GDPR** (je součástí Docker image)
- [ ] Nakonfigurovat plugin v consent mode pro Google Tag Manager

---

## Drobné zbývající věci

- [ ] Logo — `header.php` zobrazuje textové „Callup". Pokud existuje SVG/PNG logo, nahradit v `header.php` za `<img>`
- [ ] Favicon — přidat do `header.php` nebo přes WP administraci
