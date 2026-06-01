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
