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
├── mod_fbg_anslagstavla/          # Modulens huvudkatalog
│   ├── mod_fbg_anslagstavla.php   # Huvudfil för modulen
│   ├── mod_fbg_anslagstavla.xml   # Moduldefinition och konfiguration
│   ├── anslagstavla.js            # JavaScript för DataTables och formulärhantering
│   └── tmpl/
│       └── default.php            # Mallvy för modulen
├── README.md                       # Detta dokument
└── CLAUDE.md                       # AI-assisterad utvecklingsdokumentation
```

## Installation

Modulen installeras i Joomlas modulkatalog via en symbolisk länk:

```bash
/home/httpd/fbg-intranet/dev-intra.falkenberg.se/modules/mod_fbg_anslagstavla
  -> /home/httpd/fbg-intranet/joomlaextensions/anslagstavla/mod_fbg_anslagstavla
```

## Beroenden

Modulen kräver följande externa bibliotek:
- jQuery
- DataTables (med svenska språkfilen)
- UIkit (för modaler och notifikationer)

## Backend-integration

Modulen kommunicerar med följande backend-tjänster:
- `/fbg_apps/services/content/anslagstavla.php` - CRUD-operationer för anslag
- `/fbg_apps/services/content/postAcceptor.php` - Filuppladdning

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
