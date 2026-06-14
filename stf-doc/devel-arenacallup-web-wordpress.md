# arenacallup-web-wordpress

Webová prezentace **www.callup.stafio.cz** postavená na WordPressu. Jde o migraci původního webu (Vue/Nuxt, repozitář `arenacallup-web`) do WordPressu při zachování stávajícího vizuálního designu.

Web je určen primárně pro mobilní telefony (**mobile first**), jazyk je čeština (`cs_CZ`).

---

## Funkcionalita

- **Výpis inzerátů** — landing page se seznamem aktivních pracovních nabídek
- **Detail inzerátu** — název, popis, region, logo a název partnera, typy práce
- **Odpověď na inzerát** — formulář pro zaslání zájmu o pracovní nabídku (jméno, příjmení, email, telefon, volitelná zpráva a heslo pro vytvoření účtu)
- **Registrace** — vytvoření nového účtu uchazeče
- **Úspěšná registrace** — potvrzovací stránka po dokončení registrace
- **Podmínky používání služeb** — statická WordPress stránka
- **Naše dovednosti** — statická informační stránka

Přihlášení a správa směn probíhá v samostatné **Flutter aplikaci** (`smeny.callup.stafio.cz`), na kterou web odkazuje.

---

## Architektura

Komunikace s backendem probíhá **serverside přes PHP** — WordPress šablona volá PostgREST API (`https://alpha.stafio.cz/api/`) při renderování stránky. Klient JWT token nedostane a API nevolá přímo.

PHP získává veřejný JWT token voláním `rpc/proxy_jwt_token_generate8` a cachuje ho pomocí **WordPress Transients API**, aby se předešlo zbytečným HTTP requestům.

Formuláře (odpověď na inzerát, registrace) se odesílají přes **AJAX** s CSRF ochranou pomocí **WordPress nonce**.

---

## Lokální vývoj

Veškerý vývoj běží v Dockeru přes Compose — žádné lokální PHP/MySQL není potřeba. Repozitář obsahuje **pouze custom téma** (`wp-content/themes/arenacallup/`); WordPress core, defaultní témata, Akismet apod. přicházejí z base image `wordpress:php8.3-apache`, Cookie Notice plugin je instalován v `Dockerfile`.

### Požadavky

- Docker Desktop (Windows/macOS) nebo Docker Engine + Compose plugin (Linux)

### První spuštění

```bash
docker compose up -d --build
```

Po prvním startu je nutné dokončit WordPress instalaci a aktivovat téma + plugin:

```bash
docker compose exec wordpress wp core install \
  --url=http://localhost:8080 --title=Callup \
  --admin_user=admin --admin_password=admin \
  --admin_email=dev@stafio.cz --allow-root
docker compose exec wordpress wp theme activate arenacallup --allow-root
docker compose exec wordpress wp plugin activate cookie-notice --allow-root
docker compose exec wordpress wp rewrite structure '/%postname%/' --allow-root
docker compose exec wordpress wp rewrite flush --allow-root
```

Web pak běží na `http://localhost:8080`, administrace na `http://localhost:8080/wp-admin/`.

### Vývojový cyklus

Adresář `wp-content/themes/arenacallup/` je bind-mountovaný do kontejneru — změny v PHP / CSS / JS jsou okamžitě vidět po refreshi prohlížeče, **rebuild image není potřeba**.

Image se rebuilduje pouze když:

- měníte `Dockerfile` (např. bump verze Cookie Notice pluginu nebo wp-cli),
- bumpujete base image (`wordpress:php8.3-apache`),
- přidáváte další pinovaný plugin do `Dockerfile`.

V tom případě:

```bash
docker compose up -d --build
```

### Persistence dat

- `uploads` (named volume) — `wp-content/uploads`, přežívá `docker compose down`
- `db` (named volume) — MariaDB data, přežívá `docker compose down`

Kompletní vyčištění (smaže DB i nahrané obrázky):

```bash
docker compose down -v
```

### wp-cli

`wp-cli` je nainstalovaný přímo v image, takže `docker compose exec wordpress wp <command> --allow-root` funguje v dev i v produkci (na tcpro přes `docker exec` do běžícího kontejneru).

---

## Nasazení

### Požadavky

- Docker
- GitHub CLI (`gh`) přihlášený k účtu s přístupem do `stafiocz/*`

### Publikování na produkci (tcpro)

```powershell
.\stf-scripts\deploy.ps1
```

Skript provede tři kroky:
1. Spustí GitHub Actions workflow `build-push.yml` v tomto repozitáři — buildí image `stafio/arenacallup-web:<yyMMdd>` a pushne ho na Docker Hub
2. Aktualizuje tag v `infrsastructure-ds/cust/pri.yml` a pushne změnu
3. Spustí `deploy.yml` na `infrsastructure-ds`, který nasadí stack `cust-pri` na Docker Swarm (tcpro)

Pokud byl image dnes už buildován, krok 1 se přeskočí automaticky. Build lze přeskočit i ručně:

```powershell
.\stf-scripts\deploy.ps1 -SkipBuild
```

Web běží na `https://www.callup.stafio.cz` (stack `cust-pri`, service `callup-web`).

### Manuální build image

```bash
docker build -t arenacallup-web .
```

---

## Dokumentace

- [RFC-260601 — Migrace arenacallup.cz do WordPressu](rfc/RFC-260601-callup.md)
