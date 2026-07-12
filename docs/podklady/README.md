# CoreTraining — Jak dodat podklady

Struktura složek pro assety a obsah, které dodává zadavatel (Martin / Marek).
Vývojář načítá soubory z této složky při implementaci.

## Kam co uložit

```
docs/podklady/
├── README.md                 ← tento soubor
├── 01-vizual/
│   ├── logo/                 ← SVG nebo PNG (světlé + tmavé pozadí)
│   ├── fotografie/           ← viz seznam níže
│   └── font.txt              ← „Inter“ nebo „Manrope“
├── 02-texty/
│   ├── homepage.md           ← statistiky, Pro koho, Služby (4 bloky)
│   ├── sluzby.md             ← Diagnostika + Individuální spolupráce
│   ├── studio.md             ← CORE Centrum
│   ├── o-martinovi-timeline.md
│   └── ochrana-udaju.md      ← GDPR + cookies (schválený text)
├── 03-reference/
│   └── reference.csv         ← 6 citací (šablona níže)
├── 04-projekty/
│   ├── projekty.csv          ← další projekty (název, URL, logo)
│   └── loga/                 ← 1.jpg, 2.jpg, 3.png, 4.png
├── 05-kontakt/
│   └── udaje.md              ← ověřené údaje, sociální sítě
└── 06-technicke/
    └── gtm-id.txt            ← GTM-XXXXXXX (volitelné před launchí)
```

## Formáty souborů

| Typ | Formát | Poznámka |
|-----|--------|----------|
| Logo | **SVG** (preferováno) nebo PNG 2× | min. výška 40 px, verze na světlé pozadí |
| Fotografie | **JPG/WebP**, min. **1920 px** šířka | s právy k použití na webu |
| Loga projektů | JPG nebo PNG | max. výška ~80 px, v `04-projekty/loga/` |
| Texty | **Markdown** (.md) nebo Google Doc odkaz | čeština, finální copy |
| Reference / projekty | **CSV** podle šablon níže | UTF-8 |

---

## Šablona: reference.csv

```csv
jmeno,text,hodnoceni,foto
Jan Novák,"Skvělý kurz, praktické informace.",5,
Marie Svobodová,"Konečně rozumím pohybu v souvislostech.",5,marie.jpg
```

- `hodnoceni` = 1–5 (hvězdičky)
- `foto` = volitelný název souboru v `03-reference/fotografie/`

---

## Šablona: projekty.csv

Sekce **„Další projekty“** na homepage a stránce Kontakt. Nejsou to externí partneři.

```csv
nazev,url,logo
CoreTraining,https://www.coretraining.cz/,1.jpg
CORE Centrum,https://www.corecentrum.cz/,2.jpg
Funkční diagnostika,https://funkcnidiagnostika.cz/,3.png
SleepCoach,https://sleepcoach.cz/,4.png
```

Soubory log v `04-projekty/loga/`.

---

## Fotografie — přiřazení

Detailní mapa: **`01-vizual/fotografie.md`**

| Účel | Soubor | Stav |
|------|--------|------|
| Homepage hero | `8.jpg` | ✅ |
| O Martinovi hero | `14.jpg` | ✅ |
| Galerie O Martinovi | `1, 2, 5, 6, 7, 9, 10, 11, 13.jpg` | ✅ výběr z existujících |
| Studio | — | ❌ nemáme — text + mapa ve v1 |
| Kurzy (náhledy) | migrace | — |


---

## Texty — co připravit

### Už hotové (netřeba psát znovu)

- Homepage hero — viz `docs/rfc/CoreTraining_Redesign_Zadani_v1.md`
- O Martinovi — celé texty ve zadání v1 (Můj příběh, Filozofie, Certifikace…)
- Kontakt — většina údajů z live webu (ověřit)

### Je potřeba doplnit

| Soubor | Obsah |
|--------|-------|
| `homepage.md` | Statistiky (potvrdit čísla), 4× Pro koho, 4× Služby |
| `sluzby.md` | Diagnostika + Individuální spolupráce, CTA | ✅ |
| `studio.md` | Popis CORE Centra, adresa, CTA | ✅ |
| `o-martinovi-timeline.md` | Časová osa + certifikace | ✅ návrh — roky (?) ověřit |
| `ochrana-udaju.md` | GDPR text ke schválení |
| `udaje.md` | DIČ, sídlo, sociální sítě |

> Projekty (CoreTraining, CORE Centrum…) — viz `04-projekty/projekty.csv`, ne `udaje.md`.

---

## Kontaktní údaje — ověřit a doplnit

Z live webu (k 7/2026), **potřebujeme potvrzení**:

| Pole | Hodnota (pracovní) | Stav |
|------|-------------------|------|
| Telefon | +420 777 131 078 | ověřit |
| E-mail | info@coretraining.cz | ověřit |
| IČO | 716 478 56 | ověřit |
| DIČ | ? | **chybí** |
| Účet | Mbank 670100-2211277834/6210 | ověřit |
| Sídlo | V Uličce 2291, Brandýs nad Labem | ověřit |
| Studio | Rýmařovská 561, Praha–Letňany | ověřit |
| Instagram | ? | **chybí** |
| Facebook | ? | **chybí** |
| LinkedIn | ? | **chybí** |
| YouTube | ? | **chybí** |

### Další projekty

Kompletní seznam v **`04-projekty/projekty.csv`** — zobrazuje se na homepage i Kontaktu.

---

## Co může vývojář rozhodnout sám (netřeba dodávat)

- Typografická škála, spacing, breakpointy (768 / 1024)
- Ikony (lze použít open-source sadu)
- Podmenu — ve v1 **bez dropdownu**
- Mobilní menu — hamburger + overlay
- Breadcrumbs — ve v1 **ne**
- Footer menu — odvodit z hlavního menu + Ochrana údajů

---

## Priorita dodání

### Teď (neblokuje start kódování, ale brzy potřeba)

1. `01-vizual/font.txt` — Inter nebo Manrope
2. `01-vizual/logo/`
3. `01-vizual/fotografie/hero.jpg`

### Před dokončením hlavních stránek (fáze 2)

4. `02-texty/homepage.md`
5. `02-texty/sluzby.md`
6. `02-texty/studio.md`
7. `02-texty/o-martinovi-timeline.md`
8. Fotografie studio + O Martinovi

### Před launchiem (fáze 5)

9. `03-reference/reference.csv` + fotky
10. `04-projekty/` — hotovo
11. `02-texty/ochrana-udaju.md` — schválený text
12. `05-kontakt/udaje.md` — kompletní ověřené údaje
13. `06-technicke/gtm-id.txt`

### Nemusíte dodávat (řeší migrace / vývojář)

- Články (~340) — migrace ze stávajícího webu
- Kurzy (~42) — migrace + ruční kontrola
- Media library — export ze starého WP

---

## Jak odevzdat

1. **Git** — commit do `docs/podklady/` v tomto repozitáři *(preferováno)*
2. **Sdílená složka** — Google Drive / Dropbox odkaz v issue nebo chatu
3. **E-mail** — jen pro menší soubory; velké fotky raději Drive

Po dodání napište, které soubory jste přidali — projdeme je společně.
