# CLAUDE.md - AI-assisterad Utvecklingsdokumentation

Detta dokument beskriver AI-assisterad utveckling och underhåll av Anslagstavla-modulen.

## Historik

### 2026-01-15 - Omstrukturering och Kodorganisation

**Genomförda ändringar:**

1. **Flyttning till dedikerad extensions-katalog**
   - Flyttade modulen från `/home/httpd/fbg-intranet/dev-intra.falkenberg.se/modules/mod_fbg_anslagstavla`
   - Till: `/home/httpd/fbg-intranet/joomlaextensions/anslagstavla/mod_fbg_anslagstavla`
   - Skapade symlink från ursprunglig plats för bakåtkompatibilitet

2. **Konsolidering av JavaScript-filer**
   - Flyttade `anslagstavla.js` från `/fbg_apps/js/modules/` till modulens egen katalog
   - Uppdaterade referens i `mod_fbg_anslagstavla.php:22` från `/fbg_apps/js/modules/anslagstavla.js` till `/modules/mod_fbg_anslagstavla/anslagstavla.js`
   - Lade till `anslagstavla.js` i moduldefinitionen (`mod_fbg_anslagstavla.xml:17`)

3. **Dokumentation**
   - Skapade `README.md` med användar- och utvecklardokumentation
   - Skapade detta dokument (`CLAUDE.md`) för att dokumentera AI-assisterad utveckling

4. **Versionshantering**
   - Initierade git-repository i `/home/httpd/fbg-intranet/joomlaextensions/anslagstavla`
   - Möjliggör versionskontroll och samarbete

**Syfte:**
- Förbättra kodorganisation genom att samla all modulkod på ett ställe
- Underlätta underhåll och vidareutveckling
- Separera extensions från huvudapplikationen
- Möjliggöra oberoende versionshantering av modulen

### 2026-01-15 - Backend Migration till Repository

**Genomförda ändringar:**

1. **Backend-flytt till api/-katalog**
   - Flyttade `anslagstavla.php` från `/fbg_apps/services/content/` till `api/`
   - Flyttade `postAcceptor.php` från `/fbg_apps/services/content/` till `api/`
   - Skapade symlink: `/dev-intra.falkenberg.se/api/anslagstavla/` → `/joomlaextensions/anslagstavla/api/`
   - Nya URL:er: `/api/anslagstavla/anslagstavla.php` och `/api/anslagstavla/postAcceptor.php`

2. **Säkerhet**
   - Skapade `.htaccess` i `api/` för att:
     - Blockera directory listing
     - Tillåta endast .php-filer
     - Förhindra åtkomst till känsliga filer
     - Sätta säkerhets-headers (X-Content-Type-Options, X-Frame-Options)

3. **Frontend-uppdateringar**
   - Uppdaterade `anslagstavla.js:1` - ändrade API-URL till `/api/anslagstavla/anslagstavla.php`
   - Uppdaterade `tmpl/default.php:180` - ändrade upload-URL till `/api/anslagstavla/postAcceptor.php`

4. **Dokumentation**
   - Skapade `api/README.md` med komplett API-dokumentation (endpoints, requests, responses)
   - Uppdaterade huvud-README.md med ny struktur och API-integration
   - Uppdaterade CLAUDE.md med denna migreringshistorik

5. **Beroenden**
   - Bibehöll externa beroenden i `/fbg_apps/include/`:
     - `include1.php` (Joomla bootstrap)
     - `migrera.php` (REST API wrappers)
     - `pdo_db.php` (databaskoppling)
   - Include-sökvägar i API-filerna är absoluta och behövde inga ändringar
   - Dessa delas mellan flera system och ska INTE kopieras in i repot

**Syfte:**
- Konsolidera all modulkod (frontend + backend) i ett repository
- Förbättra underhållbarhet och versionshantering
- Modernare API-URL-struktur (`/api/...`)
- Förbättrad säkerhet med dedikerad `.htaccess`
- Tydligare separation mellan frontend och backend

**Arkitektur efter migration:**

```
Web-accessible paths:
├── /modules/mod_fbg_anslagstavla/        → Frontend (symlink)
└── /api/anslagstavla/                    → Backend (symlink)

Physical repository:
/joomlaextensions/anslagstavla/
├── api/                                  → Backend (nytt)
│   ├── anslagstavla.php
│   ├── postAcceptor.php
│   ├── .htaccess
│   └── README.md
└── mod_fbg_anslagstavla/                 → Frontend
```

## Teknisk Översikt

### Arkitektur

Modulen följer Joomla 3.x modularkitektur med:
- **PHP-backend**: Laddar ramverk och mall
- **Template-layer**: Rendera UI med UIkit-komponenter
- **JavaScript-frontend**: DataTables för datahantering, AJAX för backend-kommunikation
- **REST API**: JSON-baserad kommunikation med backend-tjänster

### Dataflöde

```
1. Användare öppnar sida med modul
2. mod_fbg_anslagstavla.php laddar JavaScript och CSS
3. anslagstavla.js initierar DataTable
4. AJAX-anrop hämtar anslag från anslagstavla.php
5. DataTable renderar data med filter och sökfunktion
6. Användare kan skapa/redigera via modal-formulär
7. Sparning sker via POST/PATCH till anslagstavla.php
8. Tabell uppdateras automatiskt
```

### JavaScript-funktioner

**anslagstavla.js** innehåller:
- `defer()` - Väntar på jQuery-laddning
- `ladda()` - Initierar DataTable med konfiguration
- DataTable-konfiguration med:
  - Svenska språkfilen
  - Excel-export
  - Kolumnval
  - Sökfilter (SearchPanes)
  - Custom rendering för ikoner och datum
- Formulärhantering för CRUD-operationer
- Event handlers för knappar och radklick
- Datumberäkning (automatisk nedtagning 21 dagar efter uppsättning)

### Beroenden

**Externa bibliotek:**
- jQuery
- DataTables + Svenska språkfilen
- DataTables UIkit-integration
- UIkit (modaler, ikoner, notifikationer)

**Backend-tjänster:**
- `anslagstavla.php` - REST API för anslag (GET, POST, PATCH)
- `postAcceptor.php` - Filuppladdning

## Utvecklingsriktlinjer

### För framtida AI-assisterad utveckling

När du arbetar med denna modul:

1. **Bevara Joomla-kompatibilitet**
   - Behåll `defined('_JEXEC') or die;` i alla PHP-filer
   - Använd Joomla-ramverket (Factory, ModuleHelper)
   - Följ Joomla XML-schema för moduldefinition

2. **JavaScript-hantering**
   - All modul-specifik JS ska ligga i modulkatalogen
   - Externa ramverk kan refereras från /fbg_apps/js/framework/
   - Använd `defer()` för att säkerställa jQuery är laddat

3. **Styling**
   - Använd UIkit-klasser för konsistent utseende
   - Modul-specifika stilar kan läggas inline eller i separat CSS-fil

4. **API-kommunikation**
   - Använd Fetch API för moderna AJAX-anrop
   - Hantera både success och error-fall
   - Visa användarfeedback via UIkit.notification

5. **Dokumentation**
   - Uppdatera detta dokument vid större ändringar
   - Dokumentera API-ändringar
   - Kommentera komplexa funktioner

### Vanliga uppgifter

**Lägga till nytt fält:**
1. Uppdatera databas-schema (via backend)
2. Lägg till fält i `tmpl/default.php` formulär
3. Lägg till kolumn i DataTable-konfiguration i `anslagstavla.js`
4. Uppdatera formulär-populering i row-click handler
5. Testa create/update-flödet

**Uppdatera styling:**
1. Modifiera CSS i `tmpl/default.php` `<style>`-block
2. Eller skapa separat CSS-fil och referera i `mod_fbg_anslagstavla.php`
3. Uppdatera `mod_fbg_anslagstavla.xml` om ny fil läggs till

## Felsökning

### Vanliga problem

**JavaScript körs inte:**
- Kontrollera att jQuery laddas före modulens JS
- Verifiera att sökvägen till anslagstavla.js är korrekt
- Öppna browser console för felmeddelanden

**DataTable initierar inte:**
- Kontrollera att DataTables CSS/JS är laddade
- Verifiera AJAX-URL till anslagstavla.php
- Kontrollera nätverkstrafik i Developer Tools

**Formulär sparar inte:**
- Verifiera att POST/PATCH når backend
- Kontrollera JSON-format i request body
- Se över error-hantering i fetch-anropet

## Kontakt

**Utvecklare:**
Tomas Bolling Nilsson
tomas.bollingnilsson@falkenberg.se
Utvecklingsavdelningen, Falkenbergs kommun

**Repository:**
`/home/httpd/fbg-intranet/joomlaextensions/anslagstavla`

**Produktionsmiljö:**
Via symlink i `/home/httpd/fbg-intranet/dev-intra.falkenberg.se/modules/mod_fbg_anslagstavla`
