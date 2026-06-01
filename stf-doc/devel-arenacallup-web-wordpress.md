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

## Spuštění

### Požadavky

- Docker a Docker Compose

### Lokální vývoj

```bash
docker compose up
```

`docker compose up` spustí dva kontejnery:
- **wordpress** — WordPress na `http://localhost:8080`
- **db** — MySQL databáze (port 3306, přístupná pouze interně mezi kontejnery)

WordPress běží na `http://localhost:8080`.

Při prvním spuštění je nutné dokončit instalaci WordPressu na `http://localhost:8080/wp-admin/install.php`:
1. Zvolit jazyk: **Čeština**
2. Vyplnit název webu, uživatelské jméno a heslo administrátora
3. Po instalaci spustit setup přes WP-CLI:

```bash
docker compose exec wordpress wp theme activate arenacallup --allow-root
docker compose exec wordpress wp rewrite structure '/%postname%/' --allow-root
docker compose exec wordpress wp rewrite flush --allow-root
```

Administrace: `http://localhost:8080/wp-admin`

Custom theme se nachází v `wp-content/themes/arenacallup/` — při lokálním vývoji je složka mountována jako volume (změny se projeví ihned bez rebuildu).

### Produkční build

```bash
docker build -t arenacallup-web .
```

Produkce vyžaduje dva kontejnery: **WordPress** a **MySQL**.

> **TODO:** Vytvořit `docker-compose.prod.yml` pro produkční nasazení.

Prozatím lze spustit oba kontejnery ručně:

Spuštění MySQL kontejneru:

```bash
docker run -d \
  --name arenacallup-db \
  --network arenacallup-net \
  -e MYSQL_DATABASE=<db> \
  -e MYSQL_USER=<user> \
  -e MYSQL_PASSWORD=<password> \
  -e MYSQL_ROOT_PASSWORD=<root-password> \
  -v arenacallup-db-data:/var/lib/mysql \
  mysql:8.0
```

Spuštění WordPress kontejneru:

```bash
docker network create arenacallup-net

docker run -d \
  --name arenacallup-web \
  --network arenacallup-net \
  -e WORDPRESS_DB_HOST=arenacallup-db \
  -e WORDPRESS_DB_NAME=<db> \
  -e WORDPRESS_DB_USER=<user> \
  -e WORDPRESS_DB_PASSWORD=<password> \
  -e GTM_CONTAINER_ID=<GTM-XXXXXX> \
  -p 80:80 \
  arenacallup-web
```

MySQL data jsou persistována do named volume `arenacallup-db-data`.

---

## Dokumentace

- [RFC-260601 — Migrace arenacallup.cz do WordPressu](rfc/RFC-260601-callup.md)
