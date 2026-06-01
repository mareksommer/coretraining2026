# RFC-260601 — Migrace arenacallup.cz do WordPressu

## Záměr

Přenést funkcionalitu ze stávajícího webu arenacallup.cz (Vue/Nuxt, repozitář `arenacallup-web`) do nového projektu postaveného na WordPressu.

Vizuální design odpovídá stávajícímu webu arenacallup.cz.

---

## Architektura — WordPress

Implementace jako **custom WordPress theme** (ne plugin). Zdůvodnění:
- Web má specifický vizuální design vyžadující plnou kontrolu nad HTML strukturou a CSS
- Všechny stránky jsou custom PHP šablony bez nutnosti obecné pluginové vrstvy
- Plugin bez vlastního theme by stejně vyžadoval další theme pro rendering

Theme se pojmenuje `arenacallup`.

---

## Stránky k přenesení

- **Landing page** — Výpis inzerátů
- **Detail inzerátu** — Detail jednoho inzerátu
- **Odpověď na inzerát** — Formulář pro odpověď na inzerát
- **Registrace** — Formulář pro vytvoření účtu
- **Úspěšná registrace** — Potvrzovací stránka po registraci
- **Naše dovednosti** — Statická informační stránka
- **Podmínky používání služeb** — Statická stránka (obsah přenesen z původního webu)

---

## URL struktura

- `/` — Landing page — výpis inzerátů
- `/inzerat/{id}/` — Detail inzerátu (id z API)
- `/mam-zajem/{id}/` — Odpověď na inzerát (id z API)
- `/registrace/` — Registrace
- `/uspesna-registrace/` — Potvrzení úspěšné registrace
- `/nase-dovednosti/` — Naše dovednosti (statická WP stránka)
- `/podminky-pouzivani-sluzeb/` — Podmínky používání služeb (statická WP stránka)

Stránky `/inzerat/{id}/` a `/mam-zajem/{id}/` jsou implementovány jako WordPress rewrite rules — ID z URL je předáno PHP šabloně jako query var.

> **Požadavek:** WordPress musí mít nastavenou permalink strukturu `/%postname%/` (ne výchozí "Plain") — jinak rewrite rules nefungují. Nastavení automaticky provede WP-CLI setup (viz devel doc).

---

## Architektura — API integrace

Komunikace s backendem probíhá **serverside přes PHP vrstvu** (WordPress šablona volá API při renderování stránky). Klient nedostane JWT token a nevolá API přímo.

Backend: PostgREST API na `https://alpha.stafio.cz/api/`.

### Získání JWT tokenu

PHP získá veřejný JWT token voláním:

```
POST https://alpha.stafio.cz/api/rpc/proxy_jwt_token_generate8
Content-Type: application/json
Content-Profile: platform
Prefer: params=single-object

{
  "app_domain": "prilezitosti.stafio.cz"
}
```

Odpověď: `{ "success": true, "token": "<jwt>" }`

Získaný token se použije jako `Authorization: Bearer <token>` pro následná volání API.

Token se cachuje pomocí **WordPress Transients API** (`set_transient`) s TTL = expirace JWT **mínus 60 sekund** (rezerva pro zamezení edge-case, kdy by token expiroval mezi cachováním a skutečným voláním API).

Pokud se nepodaří token získat (HTTP chyba nebo `"success": false`), PHP zobrazí uživateli chybovou zprávu z odpovědi funkce.

### Volané endpointy

| Endpoint | Metoda | Content-Profile | Použití |
|---|---|---|---|
| `rpc/clp_page_get_home` | POST | `work_agency` | Výpis inzerátů (landing page) |
| `rpc/clp_job_get_detail` | POST | `work_agency` | Detail inzerátu |
| `rpc/clp_page_get` | POST | `work_agency` | LOV costCenter (registrace) |
| `rpc/clp_job_response` | POST | `work_agency` | Odeslání odpovědi na inzerát |
| `rpc/clp_person_reg` | POST | `work_agency` | Odeslání registrace |

---

## Cachování

Stránky s dynamickým obsahem (landing page, detail inzerátu, odpověď na inzerát) **musí být vyloučeny z full-page cache** (WP Super Cache, LiteSpeed Cache apod.), aby se zobrazoval aktuální obsah z API.

| URL pattern | Full-page cache |
|---|---|
| `/` | vyloučena |
| `/inzerat/*` | vyloučena |
| `/mam-zajem/*` | vyloučena |
| `/registrace/` | vyloučena |
| `/uspesna-registrace/` | vyloučena |
| `/nase-dovednosti/` | povolena |
| `/podminky-pouzivani-sluzeb/` | povolena |

API response pro landing page (`clp_page_get_home`) se cachuje na serveru přes Transients API s TTL 5 minut, aby se snížilo zatížení backendu při vyšší návštěvnosti.

---

## Výpis inzerátů (landing page)

Endpoint: `POST rpc/clp_page_get_home`

```json
{
  "limit_": 99,
  "offset_": 0
}
```

- Zobrazí všechny aktivní inzeráty (limit 99 je záměrné — stránkování není plánováno). Pokud inzerátů bude více než 99, zbývající se nezobrazí.
- Každý inzerát zobrazuje: název, krátký popis.
- Klik na inzerát → detail `/inzerat/{id}/`.

---

## Detail inzerátu

Endpoint: `POST rpc/clp_job_get_detail`

```json
{
  "job_id_": "<id>"
}
```

Zobrazuje:
- Název inzerátu
- Logo partnera
- Název partnera
- Typy práce
- Popis
- Region a adresa
- Tlačítka:
  - **Mám zájem** → `/mam-zajem/{id}/`
  - **Přihlásit se** → `https://smeny.callup.stafio.cz` (otevírá se v novém tabu)

### SEO

- `<title>` — název inzerátu
- `<meta name="description">` — krátký popis inzerátu
- `<meta property="og:title">` — název inzerátu
- `<meta property="og:description">` — krátký popis inzerátu

---

## Odpověď na inzerát

Formulář s poli:
- Jméno (povinné)
- Příjmení (povinné)
- Email (povinné) — validace formátu
- Telefon (povinné) — validace formátu (viz sekce Validace)
- Heslo (nepovinné) — uchazeč si může rovnou vytvořit účet
- Zpráva / poznámka (nepovinné)
- **Souhlas se zpracováním osobních údajů** (checkbox, povinné) — GDPR, ověřuje se pouze client-side a server-side před odesláním, do API se neposílá

Endpoint: `POST rpc/clp_job_response` (`Content-Profile: work_agency`)

```json
{
  "job_id_": "<id z URL>",
  "first_name_": "...",
  "last_name_": "...",
  "mobile_": "...",
  "email_": "...",
  "password_": "...",
  "response_text_": "..."
}
```

Po odeslání: PHP zobrazí potvrzovací zprávu z API (`info_text`).

---

## Registrace

Formulář s poli:
- Jméno (povinné)
- Příjmení (povinné)
- Email (povinné) — validace formátu
- Telefon (povinné) — validace formátu (viz sekce Validace)
- Chci pracovat jako (radio, povinné) — hodnoty načteny z API (viz níže)
- Heslo (povinné)
- Heslo znovu (povinné)
- **Souhlas se zpracováním osobních údajů** (checkbox, povinné) — GDPR

Pod formulářem: odkaz na obchodní podmínky (`/podminky-pouzivani-sluzeb/`).

### LOV pro "Chci pracovat jako"

Hodnoty jsou uloženy jako WordPress option `callup_cost_centers` (pole JSON `[{"id": "...", "label": "..."}]`) a editovatelné v administraci WordPressu (Nastavení → Callup). PHP čte hodnoty přes `get_option('callup_cost_centers')`.

Administrátor seznam hodnot spravuje ručně — data se nemění automaticky z API.

### Odeslání registrace

Endpoint: `POST rpc/clp_person_reg` (`Content-Profile: work_agency`)

```json
{
  "first_name_": "...",
  "last_name_": "...",
  "mobile_": "...",
  "email_": "...",
  "password_": "...",
  "cost_center_": "..."
}
```

Po úspěchu: PHP nastaví krátkodobou cookie pomocí `setcookie()` a přesměruje na `/uspesna-registrace/`.

Cookie parametry:

| Parametr | Hodnota |
|---|---|
| Název | `callup_reg_success` |
| Hodnota | `1` |
| TTL | 5 minut |
| `secure` | `true` |
| `httponly` | `true` |
| `samesite` | `Strict` |

---

## Úspěšná registrace

WordPress šablona — potvrzovací stránka.

**Guard:** Pokud uživatel přistoupí na `/uspesna-registrace/` přímo (bez dokončení registrace), PHP zkontroluje přítomnost cookie `callup_reg_success`. Pokud cookie chybí, přesměruje na `/registrace/`.

---

## Validace formulářů

Validace probíhá **client-side (JavaScript)** před odesláním formuláře i **server-side (PHP)** jako záloha.

### Hesla

- Minimální délka: **8 znaků**
- Pole „Heslo znovu" musí odpovídat poli „Heslo" — validace při opuštění pole i při odeslání formuláře
- Pokud heslo není vyplněno (volitelné pole ve formuláři odpovědi), validace hesel se přeskočí

### Email

- Validace formátu (client-side: `type="email"` + JS, server-side: `filter_var` s `FILTER_VALIDATE_EMAIL`)

### Telefon

- Přijímány **evropská čísla ve formátu E.164** — `+` a 7–15 číslic
- Příklady platných čísel: `+420XXXXXXXXX`, `+421XXXXXXXXX`, `+380XXXXXXXXX`, `+49XXXXXXXXXX`
- Přijímán také formát bez předvolby: 9 číslic (interpretuje se jako české číslo)
- Client-side: regex `/^\+?[0-9]{7,15}$/`
- Server-side: stejný regex přes `preg_match`

---

## Anti-spam ochrana

Veřejné formuláře (odpověď na inzerát, registrace) jsou chráněny proti spamu:

### Honeypot pole

Každý formulář obsahuje skryté pole (CSS `display:none`), které legitimní uživatel nevyplní. Pokud je pole vyplněno, PHP request tiše odmítne (vrátí úspěch, ale data se neodešlou do API).

```html
<input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off">
```

### Rate limiting

PHP přes Transients API omezuje počet odeslaných formulářů z jedné IP adresy:
- Max **5 odeslání za 10 minut** na IP
- Při překročení limitu se zobrazí chybová zpráva, data se neodešlou do API

---

## CSRF ochrana

Všechny formuláře jsou chráněny pomocí **WordPress nonce** (`wp_nonce_field` / `wp_verify_nonce`).

Nonce se vkládá jako skryté pole do každého formuláře. PHP při zpracování POST requestu nonce ověří — pokud chybí nebo je neplatné, request se odmítne.

---

## Loading stavy

Formuláře odesílají data přes **AJAX** na WordPress REST API endpoint (`/wp-json/callup/v1/`). Během odesílání:

- Tlačítko Odeslat se **deaktivuje** (`disabled`) a zobrazí inline spinner (SVG, bez závislostí na externích knihovnách)
- Po dokončení se tlačítko znovu aktivuje a zobrazí výsledek (potvrzení nebo chyba)

Příklad tlačítka ve stavu odesílání:

```html
<button disabled>
  <svg class="spinner" ...>...</svg>
  Odesílám…
</button>
```

Spinner je CSS animace (`animation: spin 0.8s linear infinite`), bez Javascriptových závislostí.

---

## Error handling

### Chyby API

Pokud API vrátí chybovou odpověď (HTTP 4xx / 5xx, nebo `"success": false`), PHP zobrazí chybovou zprávu přímo z odpovědi API (pole `message` nebo `info_text`).

Příklad zobrazení chyby: inline nad formulářem (červený alert box).

### Inzerát neexistuje

Pokud `rpc/clp_job_get_detail` vrátí prázdný výsledek nebo chybu, PHP zobrazí standardní WordPress **404 stránku**.

Stejné chování platí pro `/mam-zajem/{id}/` — pokud ID neexistuje, zobrazí se 404.

### Chyba načítání landing page

Pokud selže volání `clp_page_get_home` (nebo získání JWT), PHP zobrazí chybovou zprávu namísto výpisu inzerátů.

---

## SEO

- `<title>` stránek je nastaven dle obsahu (název inzerátu, název stránky)
- `<meta name="description">` je nastaven pro dynamické stránky (detail inzerátu, landing page)
- `<meta property="og:title">` a `<meta property="og:description">` jsou nastaveny pro detail inzerátu
- Statické stránky (`/nase-dovednosti/`, `/podminky-pouzivani-sluzeb/`) mají title a description nastaveny v administraci WordPressu

---

## Google Tag Manager

GTM se implementuje standardním způsobem:
- GTM snippet (head + body) vložen do šablony theme
- Cookie consent plugin je nakonfigurován v **consent mode** — GTM spouští tagy (Analytics, Ads) až po udělení souhlasu uživatelem

GTM container ID se předává jako environment variable `GTM_CONTAINER_ID`. PHP šablona ho čte pomocí `getenv('GTM_CONTAINER_ID')`.

---

## Podmínky používání služeb

Stránka `/podminky-pouzivani-sluzeb/` je **statická WordPress stránka**.

Obsah se přenáší z původního webu `arenacallup.cz/podminky-pouzivani-sluzeb`. Původní stránka je renderována JavaScriptem (Nuxt app), proto obsah nelze extrahovat automaticky — je nutné ho zkopírovat ručně přímo v prohlížeči a vložit do WordPress editoru.

---

## Naše dovednosti

Stránka `/nase-dovednosti/` je **statická WordPress stránka** s následujícím obsahem přeneseným z původního webu:

---

**Naše dovednosti**

*O2 arena místo na zážitky. Callup místo plné brigád v O2 areně.*

O2 arena a O2 universum se řadí mezi nejmodernější objekty celosvětového charakteru a hostí desítky zajímavých sportovních a kulturních akcí ročně. Jednotlivá patra představují originální koncept s netradičním přístupem k gastronomii a obsluze.

Společnost úspěšně zorganizovala prestižní akce jako Laver Cup, Global Champions Prague Playoffs, mistrovství v ledním hokeji, Fed Cup Finals, Davis Cup a NHL Premiere.

Personál pracuje s kvalitními surovinami a využívá moderní technologické vybavení. Hledáme nejen zkušené kolegy, ale i parťáky, kteří mají zápal a chuť. Nabízíme jasné podmínky, kvalitní zázemí, parkování, profesní a finanční růst. Cílem je vytvořit dlouhodobé partnerství s respektem k individuálním potřebám.

---

## Autentizace

- **Přihlásit se** — Flutter app `https://smeny.callup.stafio.cz` (otevírá se v novém tabu)
- **Zapomenuté heslo** — Flutter app, součást přihlašovací obrazovky
- **Registrace** — součást tohoto webu (viz výše)

---

## Docker image

Pro nasazení do produkce se vytváří custom Docker image.

Základ: `wordpress:php8.3-apache`

Image obsahuje:
- WordPress core (z base image)
- Custom theme `arenacallup` (zkopírován do `/var/www/html/wp-content/themes/arenacallup/`)
- Plugin Cookie Notice (stažen z `downloads.wordpress.org` při buildu — verze pinována jako `ARG` v `Dockerfile` pro reprodukovatelné buildy)

Konfigurace přes environment variables (předány při spuštění kontejneru, ne baked do image):

| Proměnná | Popis |
|---|---|
| `WORDPRESS_DB_HOST` | Host MySQL databáze |
| `WORDPRESS_DB_NAME` | Název databáze |
| `WORDPRESS_DB_USER` | Uživatel databáze |
| `WORDPRESS_DB_PASSWORD` | Heslo databáze |
| `WORDPRESS_TABLE_PREFIX` | Prefix tabulek (výchozí `wp_`) |
| `GTM_CONTAINER_ID` | Google Tag Manager container ID (čte PHP šablona) |

Build:

```bash
docker build -t arenacallup-web .
```

Pro lokální vývoj se použije `docker compose up` — viz [devel-arenacallup-web-wordpress.md](../devel-arenacallup-web-wordpress.md).

---

## Cookie consent

Implementace pomocí WordPress pluginu **[Cookie Notice & Compliance for GDPR / CCPA](https://wordpress.org/plugins/cookie-notice/)** (česká lokalizace dostupná).

- Lišta se zobrazí při první návštěvě v dolní části obrazovky
- Uživatel potvrdí souhlas kliknutím na tlačítko
- Souhlas se uloží do cookie — lišta se opakovaně nezobrazuje
- Plugin je nakonfigurován v consent mode pro Google Tag Manager

---

## Důležité

- **Mobile first** — web je určen primárně pro mobilní telefony.
- **Jazyk** — čeština (`cs_CZ`).
