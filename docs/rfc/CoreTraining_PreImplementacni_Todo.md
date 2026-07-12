# CoreTraining — Pre-implementační to-do

Checklist položek, které je potřeba doplnit nebo rozhodnout **před zahájením vývoje WP šablony**.
Postupně projít sekce shora dolů; u každé položky buď doplnit odpověď, nebo označit jako „neřešit ve v1“.

**Stav:** 🟡 sekce 1 hotová; sekce 2 (vizuál/assety) čeká na doplnění  
**Navázání na:** [CoreTraining_Redesign_Zadani_v1.md](./CoreTraining_Redesign_Zadani_v1.md)

---

## Jak s dokumentem pracovat

1. Projít sekci po sekci s Martinem / zadavatelem.
2. Odpovědi zapisovat přímo pod položku nebo do sloupce „Rozhodnutí“.
3. Položky označené **MVP** jsou nutné pro první verzi webu.
4. Položky označené **v2** lze odložit, ale měly by být explicitně vyřazeny.

---

## 1. Rozsah v1 (MVP)

**Vyřešeno 12. 7. 2026.**

### Klíčová rozhodnutí

| Otázka | Rozhodnutí |
|--------|------------|
| Termín launchu | **Iterativní rollout** — šablona + migrace → schválení Martinem → launch |
| Jazyk | **Pouze čeština** |
| Migrace | **Ano** — ~340 článků, ~42 kurzů, celá media library *(viz sekce 11)* |
| Redirecty | **Ano** — 301 pro všechny staré URL článků a kurzů |
| Vyhledávání | **Ano** — články + kurzy |

### Stránky ve v1 — finální seznam

| Stránka | URL | Priorita | Šablona | Poznámka |
|---------|-----|----------|---------|----------|
| Homepage | `/` | **MVP** | `front-page.php` | všechny sekce dle zadání kromě newsletteru |
| Přehled kurzů | `/kurzy/` | **MVP** | `archive-kurz.php` | grid karet |
| Detail kurzu | `/kurzy/{slug}/` | **MVP** | `single-kurz.php` | sidebar + přihlašovací formulář |
| Archiv článků | `/clanky/` | **MVP** | `home.php` / `archive.php` | grid, 12 na stránku |
| Detail článku | `/clanky/{slug}/` | **MVP** | `single.php` | TOC, bloky, CTA na kurzy |
| O Martinovi | `/o-martinovi/` | **MVP** | `page-o-martinovi.php` | texty + timeline + galerie + reference grid |
| Služby | `/sluzby/` | **MVP** | `page-sluzby.php` | Diagnostika + Individuální spolupráce |
| Studio | `/studio/` | **MVP** | `page-studio.php` | CORE Centrum landing |
| Kontakt | `/kontakt/` | **MVP** | `page-kontakt.php` | formulář, mapa, fakturační údaje |
| Ochrana údajů | `/ochrana-udaju/` | **MVP** | WP page | GDPR + cookies |
| Vyhledávání | `/?s=` | **MVP** | `search.php` | články + kurzy |
| 404 | — | **MVP** | `404.php` | |

### Menu (header)

```
Kurzy · Články · Služby · Studio · O Martinovi · Kontakt
                                    [Přihlásit se na kurz → /kurzy/]
```

### Homepage — sekce ve v1

| Sekce | MVP | Poznámka |
|-------|-----|----------|
| Hero | ✅ | |
| Důvěryhodnost (statistiky) | ✅ | zaokrouhlená čísla — potvrdit s Martinem |
| Pro koho (4 karty) | ✅ | texty doplnit |
| Služby (4 bloky) | ✅ | texty doplnit |
| O Martinovi (zkráceně) | ✅ | |
| Aktuální kurzy | ✅ | 4–6 nejbližších |
| Nejnovější články | ✅ | 6 článků |
| Reference (carousel) | ✅ | 6 referencí od Martina |
| Další projekty | ✅ | `04-projekty/projekty.csv` + loga |
| Newsletter | ❌ v2 | |
| Footer | ✅ | |

### Co je ve v2 (explicitně vyřazeno z launchu)

| Položka | Důvod |
|---------|-------|
| Newsletter | rozhodnutí sekce 13 |
| Samostatné stránky Diagnostika / Individuální spolupráce | sloučeno do `/sluzby/` |
| Angličtina | pouze CZ |
| Online platba kurzů | převod na účet |
| Kapacita / stav kurzu | sekce 6 |
| Doba čtení u článků | sekce 7 |
| Filtry v archivu kurzů | sekce 6 |
| Související články na detailu | sekce 7 |
| Staging server | jen lokální docker-compose |
| WooCommerce / legacy stránky | nepřenášet |

### Fáze implementace (iterativní rollout)

```
Fáze 1 — Základ šablony
  CPT kurz + reference, header/footer, CSS tokens, 404, search

Fáze 2 — Hlavní stránky
  Homepage, O Martinovi, Kontakt, Služby, Studio

Fáze 3 — Archivy a detaily
  /kurzy/, /clanky/, single šablony, formuláře (REST)

Fáze 4 — Migrace
  Skript článků + kurzů, media, redirecty, Yoast meta

Fáze 5 — Obsah a QA
  Martin doplní reference, fotky, schválí texty
  → launch na produkci (deploy.ps1)
```

### Jak dodat podklady

Viz **[docs/podklady/README.md](../podklady/README.md)** — struktura složek, šablony CSV, priority.


- [x] Fotografie — hero + galerie přiřazeny (`01-vizual/fotografie.md`); studio bez fotek
- [ ] 6 referencí v adminu
- [x] **Loga dalších projektů** — `docs/podklady/04-projekty/`
- [ ] Texty: statistiky (potvrdit), timeline, GDPR
- [x] Texty: homepage (Pro koho, Služby), `/sluzby/`, `/studio/`
- [ ] Text „Ochrana údajů a cookies“ — schválení Martinem
- [ ] Timeline body pro O Martinovi
- [ ] Potvrzení statistik na homepage

### Kontaktní údaje (z live webu — ověřit před launchí)

- Tel: +420 777 131 078
- E-mail: info@coretraining.cz
- IČO: 716 478 56
- Účet: Mbank 670100-2211277834/6210
- Studio: CORE Centrum, Rýmařovská 561, Praha–Letňany

---

## 2. Vizuální návrh

Zadání popisuje pocit, ne layout. Bez vizuálního návrhu vznikne nekonzistentní implementace.

- [ ] **Wireframe nebo mockup** — homepage + detail kurzu + detail článku + O Martinovi
  - Rozhodnutí: _Figma / PDF / jinak_
- [ ] **Logo** — SVG/PNG, světlé i tmavé pozadí, minimální velikost
  - Soubory: _doplnit cestu nebo odkaz_
- [x] **Fotografie** — přiřazení v `01-vizual/fotografie.md`; studio bez fotek
  - Soubory: _viz fotografie.md_
- [ ] **Font** — finální volba: Inter **nebo** Manrope?
  - Rozhodnutí: _doplnit_
- [ ] **Typografická škála** — velikosti H1–H6, body text, line-height
  - Rozhodnutí: _doplnit nebo nechat na vývojáři dle mockupu_
- [ ] **Spacing systém** — základní krok (např. 4/8/16/24/32/48/64 px)
  - Rozhodnutí: _doplnit_
- [ ] **Breakpointy** — mobil / tablet / desktop (konkrétní px)
  - Rozhodnutí: _doplnit nebo standard 768 / 1024_
- [ ] **Komponenty** — vzhled tlačítek, karet, formulářů, stavů (hover, focus, disabled)
  - Rozhodnutí: _v mockupu_
- [ ] **Ikony** — sada (SVG), styl (outline/filled)
  - Rozhodnutí: _doplnit_
- [x] **Reference slider** — 3 viditelné na desktopu, 1 na mobilu, šipky, bez autoplay
  - Rozhodnutí: _viz sekce 8_

---

## 3. Informační architektura a navigace

- [x] **Finální menu** — Kurzy, Články, **Služby**, Studio, O Martinovi, Kontakt
  - Rozhodnutí: _viz sekce 13 — Diagnostika a Individuální spolupráce pod Služby_
- [ ] **Podmenu** — má některá položka dropdown?
  - Rozhodnutí: _doplnit_
- [ ] **Mobilní navigace** — hamburger, full-screen overlay, accordion?
  - Rozhodnutí: _doplnit_
- [x] **Primární CTA v headeru** — „Přihlásit se na kurz“ → přehled kurzů `/kurzy/`
  - Rozhodnutí: _odkaz na archiv kurzů; přihláška až na detailu_
- [ ] **Footer menu** — které odkazy (mimo hlavní menu)?
  - Rozhodnutí: _doplnit_
- [ ] **Breadcrumbs** — ano/ne, na kterých stránkách?
  - Rozhodnutí: _doplnit_

### URL struktura

| Stránka | Navrhovaná URL | Potvrzeno |
|---------|----------------|-----------|
| Homepage | `/` | ☐ |
| Kurzy — přehled | `/kurzy/` | ☐ |
| Kurz — detail | `/kurzy/{slug}/` | ☐ |
| Články — archiv | `/clanky/` | ☑ |
| Článek — detail | `/clanky/{slug}/` | ☑ |
| O Martinovi | `/o-martinovi/` | ☐ |
| Kontakt | `/kontakt/` | ☐ |
| Studio | `/studio/` | ☑ |
| Služby | `/sluzby/` | ☑ |

---

## 4. Obsah — homepage

Texty pro hero a O Martinovi jsou ve zadání. Chybí konkrétní copy pro ostatní sekce.

- [ ] **Statistiky (důvěryhodnost)** — přesná čísla místo „stovky / tisíce“
  - 20+ let praxe — potvrdit přesné číslo: _
  - Počet článků: _
  - Počet seminářů: _
  - Počet účastníků: _
  - Evidence-based — text nebo ikona?
- [ ] **Pro koho — 4 karty** — nadpis + krátký popis pro každou cílovou skupinu
  - Trenéři: _
  - Fyzioterapeuti: _
  - Sportovci: _
  - Aktivní veřejnost: _
- [x] **Služby — 4 bloky** — texty v `homepage.md`; detail v `sluzby.md`
- [ ] **Reference** — 3–6 reálných citací (jméno, text)
  - _doplnit_
- [x] **Další projekty** — 4 projekty s logy a URL *(viz `04-projekty/projekty.csv`)*
  - Zobrazují se na **homepage** (sekce pod referencemi) a na **Kontaktu**
- [ ] **Newsletter** — finální nadpis, text, CTA label
  - _doplnit_

---

## 5. Obsah — stránky

### O Martinovi (texty hotové ve zadání)

- [x] **Fotografie hero** — `8.jpg` (homepage), `14.jpg` (O Martinovi)
- [x] **Časová osa zkušeností** — `02-texty/o-martinovi-timeline.md` *(roky CoreTraining/CORE ověřit)*
- [x] **Reference na stránce** — stejné jako homepage (grid všech 6+)

### Kontakt

- [ ] **Telefon** — _
- [ ] **E-mail** — _
- [ ] **Adresa studia** — přesná adresa pro mapu
- [ ] **Google Maps** — embed URL nebo API klíč
- [ ] **Fakturační údaje** — IČO, DIČ, adresa, bankovní účet
- [ ] **Sociální sítě** — URL (Instagram, Facebook, LinkedIn, YouTube…)
- [x] **Karty „Další projekty“** — viz `04-projekty/projekty.csv`

### Studio *(chybí ve zadání)*

- [x] **Účel stránky** — landing CORE Centra (prostor, adresa, odkaz na corecentrum.cz)
- [x] **Fotky** — **nemáme**; ve v1 text + mapa, bez fotogalerie
- [x] **Obsah** — texty v `02-texty/studio.md`

- [x] **Popis služby** — viz `02-texty/sluzby.md` (#diagnostika)
- [x] **CTA** — kontaktní formulář → `/kontakt/`
- [x] **CTA** — kontaktní formulář → `/kontakt/`
- [x] **Samostatná stránka** — sekce na `/sluzby/`

### Individuální spolupráce

- [x] **Popis služby** — viz `02-texty/sluzby.md` (#spoluprace)
- [x] **CTA a flow** — kontaktní formulář + tel. + e-mail → `/kontakt/`

---

## 6. Kurzy — datový model a flow

Nejdůležitější technická mezera ve zadání. **Vyřešeno 6. 7. 2026.**

### Obecné rozhodnutí

- [x] **Kurzy nejsou e-shop** — žádná online platba ve v1
  - Rozhodnutí: _platba převodem, pokyny na detailu + v potvrzovacím e-mailu_
- [x] **Přihláška na kurz** — formulář na detailu kurzu
  - Rozhodnutí: _e-mail adminovi (info@coretraining.cz) + automatické potvrzení účastníkovi + GDPR + honeypot_

### CPT `kurz` — finální specifikace polí

| Pole | WP klíč (návrh) | Typ | Povinné | Poznámka |
|------|-----------------|-----|---------|----------|
| Název | `post_title` | text | ✅ | |
| Slug / URL | `post_name` | text | ✅ | `/kurzy/{slug}/` |
| Krátký popis | `post_excerpt` | textarea | ✅ | karty na homepage a archivu |
| Plný popis | `post_content` | Gutenberg editor | ✅ | program, „Na kurzu se dozvíte“, hostující lektor |
| Obrázek | `featured_image` | image | ✅ | |
| Termín — datum od | `course_date` | date | ✅ | řazení archivu |
| Termín — datum do | `course_date_end` | date | — | volitelné (vícedenní kurzy) |
| Čas | `course_time` | text | ✅ | např. „9:00 – 16:00“ |
| Místo | `course_location` | text | ✅ | volný text, např. „CORE Centrum, Praha–Letňany“ |
| Cena | `course_price` | text | ✅ | jedna cena, např. „5 500 Kč“ |
| Lektor | — | — | — | **ve v1 bez pole** — vždy Martin; hosté v popisu |
| Kapacita / stav | — | — | — | **ve v1 vynechat** |
| Externí odkaz | — | — | — | **ve v1 vynechat** |
| Program (repeater) | — | — | — | součást Gutenberg obsahu |
| Galerie | — | — | — | v2 |

### Taxonomie `typ_kurzu`

- [x] **Typ kurzu** — taxonomie s hodnotami:
  - `seminar` — Seminář
  - `workshop` — Workshop
  - `webinar` — Webinář
  - `prednaska-hosta` — Přednáška hosta
  - Rozhodnutí: _zobrazit jako badge na kartě a detailu; bez filtrů ve v1_
- [x] **Místo / region** — bez taxonomie, volný text v poli `course_location`
- [x] **Filtry na přehledu** — ve v1 **bez filtrů** (grid všech kurzů)

### Přihlašovací formulář

Pole formuláře na detailu kurzu:

| Pole | Povinné |
|------|---------|
| Jméno a příjmení | ✅ |
| E-mail | ✅ |
| Telefon | ✅ |
| Adresa (ulice, město, PSČ) | ✅ |
| Poznámka | — |
| GDPR souhlas | ✅ |
| Honeypot | (skryté, technické) |

**Po odeslání:**
1. E-mail na `info@coretraining.cz` s názvem kurzu + údaji přihlášky
2. Automatické potvrzení účastníkovi na zadaný e-mail
3. V potvrzení: platební pokyny (převod na účet, splatnost 7 dní před kurzem — dle stávající praxe)
4. Zobrazení success zprávy na stránce (bez redirectu)

**Platební údaje** (globální, z live webu — ověřit před launchí):
- Účet: Mbank 670100-2211277834/6210
- Zobrazit na detailu kurzu v sidebaru + v potvrzovacím e-mailu

### Šablony kurzů

- [x] **Přehled `/kurzy/`** — grid karet (obrázek, termín, název, místo, cena, badge typu)
  - Řazení: **budoucí kurzy nahoře** (nejbližší termín první), pod nimi ukončené (nejnovější ukončené první)
  - Filtry: ve v1 žádné
- [x] **Detail kurzu** — layout **sidebar**:
  - **Levá strana:** plný popis (Gutenberg)
  - **Pravý sidebar (sticky):** termín, čas, místo, cena, typ, platební info, přihlašovací formulář
- [x] **Ukončené kurzy** — **zobrazovat** v archivu (ne skrývat); budoucí kurzy nahoře díky řazení podle data
- [x] **Homepage sekce „Aktuální kurzy“** — max 4–6 nejbližších budoucích kurzů (stejná karta jako archiv)

### Migrace kurzů ze stávajícího webu

- Zdroj: WP příspěvky v kategoriích `seminare` (23) + `nove-kurzy` (19)
- Akce: převod do CPT `kurz`, parsování polí z HTML obsahu (termín, čas, místo, cena)
- URL: `/kurzy/{slug}/` + 301 redirect ze staré URL příspěvku
- Typ kurzu: odvodit z názvu/obsahu (webinář → `webinar`, host → `prednaska-hosta`, …)

### Implementační poznámky (pro vývojáře)

```
CPT:        kurz (slug: kurzy)
Taxonomie:  typ_kurzu (hierarchical: false)
Meta box:   course_date, course_date_end, course_time, course_location, course_price
REST:       POST /wp-json/coretraining/v1/course-registration
Šablony:    archive-kurz.php, single-kurz.php
```

---

## 7. Články

**Vyřešeno 6. 7. 2026.**

### Obecná rozhodnutí

| Otázka | Rozhodnutí |
|--------|------------|
| URL prefix | `/clanky/{slug}/` + 301 redirecty ze `/YYYY/MM/slug/` *(viz sekce 13)* |
| Kategorie | **Jedna kategorie** „Články“ — bez tematického členění ve v1 |
| Tagy | **Nepoužívat** |
| Autor na webu | Vždy **Martin Snášel** (bez ohledu na WP autora) |
| Doba čtení | **Ve v1 vynechat** |
| TOC (obsah) | **Automatický** z nadpisů H2/H3 |
| Citace + info box | **Vlastní Gutenberg bloky** |
| Související články | 3 články **ze stejné kategorie** (= nejnovější ostatní, jedna kategorie) |
| Zobrazení kategorie | **Nezobrazovat** na kartách ani v metadatech |
| Newsletter na detailu | **Ve v1 vynechat** *(viz sekce 13)* |

### Archiv `/clanky/`

- [x] Layout: **grid karet** (obrázek, datum, nadpis, excerpt)
- [x] Stránkování: **12 článků** na stránku
- [x] Řazení: nejnovější nahoře
- [x] Filtry / kategorie v archivu: **ve v1 žádné**

### Detail článku `single.php`

Layout podle zadání:

| Sekce | Obsah |
|-------|-------|
| Hero | Nadpis článku (+ volitelně featured image) |
| Metadata | Datum · Martin Snášel *(kategorie a doba čtení ne)* |
| TOC | Automaticky generovaný z H2/H3 (postranní nebo pod metadaty) |
| Obsah | Gutenberg — typografie, obrázky, vlastní bloky Citace + Info box |
| Patička článku | **Jen CTA** „Prohlédnout kurzy“ → `/kurzy/` |

*Ve v1 vynecháno oproti zadání:* author box, související články, newsletter.

> **Poznámka:** Související články byly zvoleny jako `rel_category`, ale v patičce ve v1 nejsou (footer_minimal). Logiku souvisejících připravit až pokud se přidají ve v2, nebo přidat do v1 na přání.

### Vlastní Gutenberg bloky (články)

| Blok | Účel |
|------|------|
| `coretraining/quote` | Odborná citace (text, autor citace volitelně) |
| `coretraining/info-box` | Zvýrazněný box (tip, upozornění, shrnutí) |
| `coretraining/toc` | Automatický obsah z H2/H3 *(nebo generováno v šabloně bez bloku)* |

> TOC lze generovat přímo v `single.php` z obsahu — blok není nutný, pokud je vždy na stejném místě.

### WordPress struktura

```
Post type:  post (standardní WP příspěvky)
Kategorie:  clanky (slug) — jediná kategorie pro články
Tagy:       nepoužívat
Permalink:  /clanky/%postname%/
```

### Oddělení článků od kurzů

- Kurzy → CPT `kurz` *(sekce 6)*
- Články → standardní `post` v kategorii `clanky`
- Při migraci: příspěvky z kategorií `seminare` / `nove-kurzy` **ne** migrovat jako články (jdou do CPT kurz)

### Migrace ze stávajícího webu

| Položka | Hodnota |
|---------|---------|
| Počet článků | ~340–350 (z 387 celkem po odfiltrování kurzů) |
| Zdrojová kategorie | `coretraining` (ID 16) |
| URL | `/2024/08/slug/` → `/clanky/slug/` (301) |
| Autoři v DB | Zachovat; na frontendu vždy „Martin Snášel“ |
| Obrázky | Přenést featured images + inline z obsahu |
| Obsah | Gutenberg/klasický HTML — vyčistit při migraci (odstranit vložené CF7 z kurzů) |

### Homepage sekce „Nejnovější články“

- **6 nejnovějších** článků
- Karta: obrázek, datum, nadpis, excerpt
- Bez kategorie a bez doby čtení

---

## 8. Reference (CPT)

**Vyřešeno 6. 7. 2026.**

### Zobrazení

| Místo | Formát | Poznámka |
|-------|--------|----------|
| **Homepage** | Carousel — **3 citace** vedle sebe (desktop), **1** na mobilu | šipky, bez autoplay |
| **O Martinovi** | **Statický grid** všech referencí | stejný obsah jako homepage, jiný layout |

- [x] Stejné reference na obou místech (bez odděleného výběru)
- [x] Při launchi: **6 referencí**
- [x] Správa: **ruční zadání v WP adminu**
- [x] Na live webu reference **neexistují** — obsah dodá Martin před launchí

### CPT `reference` — pole

| Pole | WP klíč (návrh) | Typ | Povinné | Poznámka |
|------|-----------------|-----|---------|----------|
| Název (interní) | `post_title` | text | ✅ | pro admin, např. „Jan Novák — seminar 2024“ |
| Text citace | `post_content` | textarea | ✅ | |
| Jméno | `reference_name` | text | ✅ | zobrazeno na webu |
| Hodnocení | `reference_rating` | number (1–5) | ✅ | hvězdičky |
| Foto | `featured_image` | image | — | volitelné |
| Pořadí | `menu_order` | number | — | řazení ve slideru/gridu |

**Veřejná URL reference není** — CPT bez archivu a bez single stránky.

### Vizuál

- Citace: uvozovky, text, jméno
- Hvězdičky: 1–5 podle `reference_rating`
- Foto: kulatý avatar vlevo od jména, pokud je vyplněno; jinak bez avatara
- Homepage carousel: šipky prev/next, bez autoplay, bez teček nebo s tečkami podle implementace

### Implementační poznámky

```
CPT:        reference (public: false, show_in_rest: true)
Meta:       reference_name, reference_rating
Šablony:    žádné single/archive — data čerpá homepage + page-o-martinovi
Blok/PHP:   sekce v front-page.php + template stránky O Martinovi
```

### Akce před launchí

- [ ] Martin doplní **6 referencí** v adminu (jméno, text, hodnocení, volitelně foto)

---

## 9. WordPress — technická specifikace

**Vyřešeno 6. 7. 2026.**

### Architektura šablony

- [x] **Přístup:** **fixní PHP šablony** pro hlavní stránky
- [x] **Klient edituje v adminu:** články, kurzy (CPT), reference (CPT)
- [x] **Fixní PHP šablony:** homepage, O Martinovi, Kontakt, Služby, Studio, archivy, 404
- [x] **Právní stránky** (GDPR, cookies): WP stránky s Gutenberg editorem *(obsah editovatelný)*

### Gutenberg bloky — ve v1

| Blok | MVP | Kde | Poznámka |
|------|-----|-----|----------|
| Hero | — | PHP | `front-page.php` |
| Statistiky | — | PHP | homepage |
| Karty (pro koho / služby) | — | PHP | homepage |
| CTA sekce | — | PHP | homepage, patička článků |
| Reference slider | — | PHP | homepage + JS carousel |
| Další projekty (loga) | — | PHP | homepage + kontakt, data z `projekty.csv` |
| Newsletter | — | — | **ve v1 vynechat** |
| Timeline | — | — | v2 |
| Galerie | — | — | v2 |
| **Citace** | ✅ | články | `coretraining/quote` |
| **Info box** | ✅ | články | `coretraining/info-box` |
| TOC | — | PHP | generováno v `single.php` z H2/H3 |

- [x] **Block patterns** — ve v1 **nepotřeba** (stránky jsou PHP šablony)
- [x] **Editovatelnost** — viz výše

### Pluginy a integrace

| Oblast | Rozhodnutí |
|--------|------------|
| **Pluginy (whitelist)** | **Yoast SEO** + **Cookie Notice** (součást Docker image) — nic dalšího |
| **Kontaktní formulář** | Vlastní **REST endpoint** + `wp_mail()` |
| **Přihláška kurzu** | Vlastní **REST endpoint** + `wp_mail()` *(sekce 6)* |
| **Příjemce e-mailů** | `info@coretraining.cz` |
| **Newsletter** | **Ve v1 vynechat** |
| **SEO** | **Yoast SEO** plugin |
| **Cookie lišta** | **Cookie Notice** 2.4.17 *(už v Dockerfile)* |
| **Analytics** | **GTM** přes env `GTM_CONTAINER_ID` — ID doplnit před launchí |
| **Mapa** | **OpenStreetMap / Leaflet** embed na Kontaktu (bez API klíče) |

### REST API endpointy (theme)

```
POST /wp-json/coretraining/v1/contact              — kontaktní formulář
POST /wp-json/coretraining/v1/course-registration    — přihláška na kurz
```

Společné vlastnosti (jako stávající endpoints v theme):
- WP REST nonce
- honeypot pole
- rate limiting (5 req / 10 min / IP)
- GDPR checkbox

> **Poznámka:** Stávající endpoints pro brigády (`/job-response`, `/register`) odstranit při refaktoru theme.

### Výkon a kvalita

| Oblast | Rozhodnutí |
|--------|------------|
| **Lighthouse** | Bez pevného cíle — optimalizovat co jde (zadání zmiňuje >90 jako aspiraci) |
| **Hosting** | Docker image → produkce **tcpro / Docker Swarm** *(viz docker-compose.yml)* |
| **Obrázky** | WP native lazy load + `srcset`; WebP pokud umožní hosting/plugin |
| **Theme assets** | CSS/JS verzované query stringem, minifikace ve v2 |
| **Cache** | Na produkci řeší infrastruktura (Swarm) — theme `nocache_headers` jen pro dynamické stránky |

### Docker / build

```
Image:     wordpress:php8.3-apache
Theme:     wp-content/themes/coretraining (COPY do image)
Pluginy:   cookie-notice (pinned 2.4.17 v Dockerfile)
           yoast-seo (doplnit do Dockerfile nebo instalace při deploy)
Env:       GTM_CONTAINER_ID
```

### Implementační checklist (vývojář)

- [ ] Registrovat CPT `kurz`, `reference`
- [x] Přepsat `front-page.php` (odstranit legacy brigády)
- [ ] Implementovat bloky `quote`, `info-box`
- [ ] REST: contact + course-registration
- [ ] Yoast SEO do Dockerfile nebo deploy skriptu
- [ ] Cookie Notice aktivovat + nastavit texty
- [ ] Permalink struktura: `/clanky/%postname%/`, `/kurzy/%postname%/`
- [ ] Leaflet mapa na Kontaktu (souřadnice CORE Letňany)
- [x] Odstranit legacy: rewrite rules inzerát/mam-zajem, job API volání

---

## 10. Právní a GDPR

**Vyřešeno 12. 7. 2026.**

| Položka | Rozhodnutí |
|---------|------------|
| Zásady ochrany osobních údajů | **Nový text** — připravíme šablonu, Martin schválí před launchí |
| Cookie policy | **Kombinovaná stránka** „Ochrana údajů a cookies“ (jedna WP stránka) |
| Cookie lišta | **Cookie Notice** plugin odkazuje na tuto stránku |
| GDPR checkbox | Standardní: *„Souhlasím se zpracováním osobních údajů dle [zásad ochrany osobních údajů](URL).“* |
| Správce údajů | **Martin Snášel**, IČO 716 478 56, info@coretraining.cz — bez externího DPO |

### Stránky ve v1

| Stránka | URL (návrh) | Obsah |
|---------|-------------|-------|
| Ochrana údajů a cookies | `/ochrana-udaju/` | GDPR + cookies v jednom dokumentu |
| *(volitelně v2)* | `/obchodni-podminky/` | Podmínky účasti na kurzech — doplnit pokud potřeba |

### Formuláře — povinný souhlas

Platí pro:
- kontaktní formulář (`/kontakt/`)
- přihláška na kurz (detail kurzu)

Bez zaškrtnutí GDPR checkboxu formulář neodešle (422).

### Akce před launchí

- [ ] Připravit šablonu textu „Ochrana údajů a cookies“
- [ ] Martin schválí finální wording
- [ ] Nastavit Cookie Notice (text lišty, odkaz na `/ochrana-udaju/`)
- [ ] Ověřit IČO a kontaktní údaje správce

---

## 11. Migrace a obsah ze stávajícího webu

**Vyřešeno 12. 7. 2026.**

### Audit live webu (k 6. 7. 2026)

| Typ obsahu | Počet | Cíl na novém webu |
|------------|-------|-------------------|
| Příspěvky celkem | 387 | — |
| Články (`coretraining`) | ~340 | `post` v kategorii `clanky` |
| Kurzy (`seminare` + `nove-kurzy`) | ~42 | CPT `kurz` *(překryv v obou kategoriích)* |
| WP stránky | ~25 | jen vybrané jako podklad textů |
| Reference | 0 | nový obsah od Martina |
| WooCommerce stránky | ~8 | **nepřenášet** (legacy e-shop) |

### Metoda migrace — hybridní

| Oblast | Metoda |
|--------|--------|
| **Články** | **Migrační skript / WP export** — všech ~340 článků |
| **Kurzy** | **Všech ~42** do CPT `kurz` — skript s parsováním HTML polí + ruční kontrola |
| **Stránky** | **Vybrané** — texty jako podklad pro nové PHP šablony |
| **Media** | **Celá media library** (uploads) — export + import |
| **SEO** | **Import Yoast** meta title/description kde existují |
| **Redirecty** | **301 pro všechny** staré URL článků a kurzů |

> Uživatel zvolil „ruční migraci“ pro klíčový obsah — v praxi: stránky a kontrola ručně, články a kurzy skriptem (340 + 42 ks ručně není reálné).

### Stránky k využití (podklad textů)

| Stará stránka (live) | Nová stránka | Akce |
|----------------------|--------------|------|
| `kontakt` | `/kontakt/` | vytáhnout údaje, nová PHP šablona |
| `martin-snasel` | `/o-martinovi/` | texty doplněny ve zadání v1 |
| `sluzby` | `/sluzby/` | podklad pro Diagnostika + Individuální spolupráce |
| `komplexni-funkcni-diagnostika` | `/sluzby/` | sekce Diagnostika |
| `individualni-lekce` / `sportovci-spoluprace` | `/sluzby/` | sekce Individuální spolupráce |
| `projekty` | `/kontakt/` | karty „Další projekty“ |
| `kurzy-seminare` | `/kurzy/` | reference pro strukturu archivu |

### Stránky k záměrnému vynechání

- WooCommerce: `kosik`, `pokladna`, `checkout`, `muj-ucet`, `thank-you`, `cancelled-order`…
- Legacy obsah: `bederni-pater`, `hrudni-pater`, `krcni-pater`, `elite-foam-roller`, `fsu`
- `nevyzadane-reference` — nahrazeno CPT reference

### URL a redirecty

| Starý formát | Nový formát | Redirect |
|--------------|-------------|----------|
| `/2024/08/{slug}/` | `/clanky/{slug}/` | 301 |
| `/2026/06/{slug}/` | `/clanky/{slug}/` | 301 |
| `/{slug}/` (kurz jako post) | `/kurzy/{slug}/` | 301 |
| `/kontakt/` | `/kontakt/` | beze změny |
| `/martin-snasel/` | `/o-martinovi/` | 301 |

**Implementace redirectů:** redirect mapa v **nginx / .htaccess** nebo **Redirection** plugin *(Yoast SEO free nemá redirects — zvážit plugin Redirection)*

### Migrační postup (kroky)

1. Export DB + uploads ze stávajícího WP
2. Čistá instalace nové šablony (Docker image)
3. Import media library
4. Spustit migrační skript:
   - post (cat 16) → post (cat `clanky`), nový permalink
   - post (cat 18/23) → CPT `kurz`, parsování meta polí
   - import Yoast meta kde existuje
5. Vygenerovat redirect mapu (stará URL → nová URL) ze slugů
6. Ručně zkontrolovat 5–10 kurzů a top článků
7. Naplnit nové stránky (Služby, Studio) texty
8. QA: odkazy, obrázky, formuláře

### Co se nemigruje automaticky

- [ ] Reference (6 ks) — Martin zadá v adminu
- [x] Další projekty — `04-projekty/projekty.csv`
- [x] Fotografie hero — `8.jpg`, `14.jpg` (viz `fotografie.md`)
- [ ] Text „Ochrana údajů a cookies“ — nový dokument

---

## 12. Provoz a správa po launchi

**Vyřešeno 12. 7. 2026.**

| Položka | Rozhodnutí |
|---------|------------|
| Správce obsahu | **Martin Snášel** — sám spravuje kurzy, články, reference v WP adminu |
| Školení | **Bez formálního školení** — admin má být intuitivní (CPT kurz/reference) |
| Zálohování | **Infrastruktura tcpro / Docker Swarm** — zálohy na hostingu |
| Staging | **Jen lokální** `docker-compose` (localhost:8080) — produkce přímo po schválení |

### Deploy workflow (z repozitáře)

```
Vývoj:     docker-compose up (lokálně, port 8080)
Build:     GitHub Actions → stafio/coretraining-web:YYMMDD
Deploy:    stf-scripts/deploy.ps1 → infrsastructure-ds (Swarm)
Produkce:  www.coretraining.cz (tcpro)
```

- Theme se bind-mountuje lokálně; v produkci je **součást Docker image**
- Před deployem: build image → aktualizace tagu v `cust/pri.yml` → `deploy.yml`

### Odpovědnosti po launchi

| Oblast | Kdo |
|--------|-----|
| Kurzy, články, reference | Martin |
| Přihlášky z formulářů | Martin (e-mail na info@coretraining.cz) |
| Technika, deploy, migrace | Stafio / vývojář |
| Právní texty, fotky | Martin / zadavatel |
| Pluginy, zálohy, uptime | Infrastruktura (tcpro) |

### Doporučení (neblokující pro v1)

- [ ] Po launchi jednorázově ověřit, že zálohy na tcpro fungují
- [ ] Před migrací: manuální snapshot DB + uploads starého webu
- [ ] Krátká poznámka v adminu u CPT kurz (nápověda k polím) místo externího školení

---

## 13. Otevřené otázky k rozhodnutí

Seznam věcí, které zadání záměrně nechává otevřené — **vyřešeno 6. 7. 2026**.

| # | Otázka | Rozhodnutí |
|---|--------|------------|
| 1 | **Studio** | Landing page **CORE Centra** — prostor, vybavení, mapa, odkaz na corecentrum.cz |
| 2 | **Přihláška na kurz** | **Přihlašovací formulář** na detailu kurzu (jméno, e-mail, tel., adresa) → e-mail na info@coretraining.cz |
| 3 | **Diagnostika + individuální spolupráce** | Jedna stránka **„Služby“** se sekcemi pro obě služby |
| 4 | **Mockupy** | **Design přímo v kódu** (bez Figma) — iterace se zadavatelem |
| 5 | **Fotografie** | **Ano**, profesionální fotky k dispozici — dodá zadavatel |
| 6 | **Další projekty** | 4 projekty v `04-projekty/projekty.csv` — CoreTraining, CORE Centrum, Funkční diagnostika, SleepCoach |
| 7 | **Statistiky** | Zaokrouhlené hodnoty (viz níže) |
| 8 | **Newsletter** | **Ve v1 vynechat** |
| 9 | **Migrace** | Nová URL struktura `/clanky/{slug}/` + **301 redirecty** ze starých URL |

### Dopad na architekturu (z rozhodnutí výše)

**Menu ve v1** — upravit oproti původnímu zadání:
- Kurzy · Články · **Služby** · Studio · O Martinovi · Kontakt
- *(Diagnostika a Individuální spolupráce nejsou v menu samostatně)*

**URL struktura** — potvrzeno:
| Typ | URL |
|-----|-----|
| Článek | `/clanky/{slug}/` |
| Kurz | `/kurzy/{slug}/` |
| Služby | `/sluzby/` |
| Studio | `/studio/` |

**Migrace** — live web (k 6. 7. 2026):
- 387 příspěvků celkem (články + kurzy jako WP posty v kategoriích `seminare`, `nove-kurzy`)
- Kurzy dnes nejsou CPT → ve v1 zavedeme CPT a přeneseme obsah
- Redirect mapa: staré `/2024/08/slug/` → `/clanky/slug/`; kurzy dle nové struktury

**Statistiky homepage** — pracovní hodnoty (zaokrouhlené z live webu):
- **25+** let praxe
- **350+** odborných článků
- **40+** seminářů a kurzů
- **1000+** účastníků vzdělávání
- Evidence-based přístup *(text, ne číslo)*

**Kontaktní údaje** — z live webu (ověřit před launchí):
- Tel: +420 777 131 078
- E-mail: info@coretraining.cz
- IČO: 716 478 56
- Účet: Mbank 670100-2211277834/6210

### Co ještě dodat (akce na zadavateli)

- [x] Profesionální fotografie — hero + galerie (`fotografie.md`); studio bez fotek
- [x] Další projekty — `04-projekty/projekty.csv` + loga
- [x] Texty sekcí Služby — `02-texty/sluzby.md`
- [ ] Texty stránky Studio (CORE Centrum)
- [ ] Potvrzení statistik Martinem

---

## 14. Definition of Done — v1 šablony

Šablona je připravena k launchi, když:

- [ ] Všechny položky označené **MVP** v sekcích 1–10 jsou vyplněné
- [ ] Mockupy schválené zadavatelem
- [ ] CPT Kurzy a Reference fungují v adminu
- [ ] Homepage, O Martinovi, Kontakt, archiv + detail článku, přehled + detail kurzu jsou hotové
- [ ] Formuláře odesílají / newsletter funguje
- [ ] Responzivní na mobilu, tabletu, desktopu
- [ ] Lighthouse skóre ≥ 90 (dohodnuté metriky)
- [ ] GDPR / cookies vyřešeno
- [ ] Redirecty z live webu nastavené (pokud relevantní)
- [ ] Obsah naplněn a schválen zadavatelem

---

## Log doplnění

| Datum | Sekce | Co bylo doplněno | Kdo |
|-------|-------|------------------|-----|
| 6. 7. 2026 | 13 — Otevřené otázky | Všech 9 rozhodnutí, dopad na menu/URL/migraci | Marek |
| 6. 7. 2026 | 6 — Kurzy | CPT spec, taxonomie, formulář, šablony | Marek |
| 6. 7. 2026 | 7 — Články | Archiv, detail, bloky, migrace | Marek |
| 6. 7. 2026 | 8 — Reference | CPT, slider, grid O Martinovi | Marek |
| 6. 7. 2026 | 9 — WP technická spec | PHP šablony, bloky, pluginy, REST | Marek |
| 12. 7. 2026 | 10 — Právní/GDPR | Kombinovaná stránka, checkbox, správce | Marek |
| 12. 7. 2026 | 11 — Migrace | Audit, hybridní migrace, redirecty | Marek |
| 12. 7. 2026 | 12 — Provoz | Martin = obsah, lokální staging, infra zálohy | Marek |
| 12. 7. 2026 | 1 — MVP rozsah | 12 stránek, fáze implementace, v2 seznam | Marek |
| 12. 7. 2026 | Podklady | Partneři → Další projekty, `04-projekty/` | Marek |

---

*Dokument vytvořen jako pracovní checklist. Po doplnění odpovědí slouží jako podklad pro RFC v2 a zahájení implementace.*
