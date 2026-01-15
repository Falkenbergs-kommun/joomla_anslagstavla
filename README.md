# Anslagstavla - Joomla Module

En Joomla-modul för att hantera digitala anslag på Falkenbergs kommuns intranät.

## Beskrivning

Modulen tillhandahåller ett gränssnitt för att skapa, redigera och hantera olika typer av anslag:
- **Protokoll** - Mötesprotokoll och sammanträdeshandlingar
- **Kungörelser** - Officiella meddelanden och kungörelser
- **Underrättelser** - Allmänna underrättelser och information

## Funktioner

- Skapa och redigera anslag med omfattande metadata
- Filtrera och söka anslag via DataTables-gränssnitt
- Hantera anslagsstatus (Publicerad, Arkiverad, Avpublicerad)
- Ladda upp och bifoga dokument
- Ange datum för uppsättning och nedtagning
- Kontaktinformation och länkar
- Export till Excel
- Responsivt gränssnitt med UIkit

## Struktur

```
anslagstavla/
├── api/                            # Backend REST API
│   ├── anslagstavla.php            # CRUD endpoints (GET, POST, PATCH)
│   ├── postAcceptor.php            # PDF upload endpoint
│   ├── .htaccess                   # Security configuration
│   └── README.md                   # API documentation
├── mod_fbg_anslagstavla/           # Frontend Joomla module
│   ├── mod_fbg_anslagstavla.php    # Module entry point
│   ├── mod_fbg_anslagstavla.xml    # Module definition
│   ├── anslagstavla.js             # DataTables and form handling
│   └── tmpl/
│       └── default.php             # Template with form UI
├── README.md                        # This document
└── CLAUDE.md                        # Development history
```

## Installation

Modulen installeras via symboliska länkar:

```bash
# Frontend module
/modules/mod_fbg_anslagstavla/
  -> /home/httpd/fbg-intranet/joomlaextensions/anslagstavla/mod_fbg_anslagstavla/

# Backend API
/api/anslagstavla/
  -> /home/httpd/fbg-intranet/joomlaextensions/anslagstavla/api/
```

## Beroenden

Modulen kräver följande externa bibliotek:
- jQuery
- DataTables (med svenska språkfilen)
- UIkit (för modaler och notifikationer)

## Backend-integration

Modulen kommunicerar med sin egen backend-API:
- `/api/anslagstavla/anslagstavla.php` - CRUD-operationer (GET, POST, PATCH)
- `/api/anslagstavla/postAcceptor.php` - PDF-filuppladdning

Se [api/README.md](api/README.md) för detaljerad API-dokumentation.

### Externa Beroenden

API:et kräver shared libraries från `/fbg_apps/include/`:
- `include1.php` - Joomla bootstrapping och session
- `migrera.php` - REST API wrapper-funktioner
- `pdo_db.php` - Databaskonfiguration

Dessa filer delas mellan flera system och ligger kvar i sin ursprungliga plats.

## Användning

1. Aktivera modulen i Joomla-administrationen
2. Placera modulen på önskad position
3. Konfigurera domän-parameter vid behov
4. Användare kan skapa nya anslag via knappen "Skapa nytt anslag"
5. Klicka på befintliga rader i tabellen för att redigera

## Utveckling

### Version
1.0 (2022)

### Författare
Tomas Bolling Nilsson
tomas.bollingnilsson@falkenberg.se
Utvecklingsavdelningen, Falkenbergs kommun

## Licens
NA (Intern utveckling för Falkenbergs kommun)
