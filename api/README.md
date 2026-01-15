# Anslagstavla API

Backend REST API för anslagstavla-modulen.

## Endpoints

### GET /api/anslagstavla/anslagstavla.php
Hämtar alla anslag från databasen (kategori 14, senaste året).

**Response:**
```json
{
  "data": [
    {
      "id": 123,
      "title": "Rubrik",
      "state": 1,
      "noticetype": "1",
      "noticedatemeeting": "2025-01-15",
      "noticedateadjusted": "2025-01-16",
      "noticedateposted": "2025-01-17",
      "noticedateremoval": "2025-02-07",
      "noticedocumentlocation": "Plats",
      "noticecontactperson": "Namn",
      "noticecontactemail": "email@example.com",
      "noticeattachment": "https://...",
      "noticelink": "https://...",
      "created": "2025-01-15 10:00:00",
      "modified": "2025-01-15 11:00:00"
    }
  ]
}
```

### POST /api/anslagstavla/anslagstavla.php
Skapar nytt anslag via Joomla REST API.

**Request:**
```json
{
  "form-title": "Rubrik",
  "form-type": 1,
  "form-published": 1,
  "form-noticedatemeeting": "2025-01-15",
  "form-noticedateadjusted": "2025-01-16",
  "form-noticedateposted": "2025-01-17",
  "form-noticedateremoval": "2025-02-07",
  "form-noticedocumentlocation": "Plats",
  "form-noticecontactperson": "Namn",
  "form-noticecontactemail": "email@example.com",
  "form-noticeattachment": "https://...",
  "form-noticelink": "https://..."
}
```

**Response:**
```json
{
  "result": {
    "id": 123,
    "version": "1",
    "state": 1
  }
}
```

### PATCH /api/anslagstavla/anslagstavla.php
Uppdaterar befintligt anslag.

**Request:**
```json
{
  "noticeID": 123,
  "form-title": "Ny rubrik",
  ...
}
```

**Response:** Samma som POST

### POST /api/anslagstavla/postAcceptor.php
Laddar upp PDF-dokument.

**Request:** multipart/form-data med fil

**Response:**
```json
{
  "status": true,
  "url": "https://kommun.falkenberg.se/images/anslagstavla/notice1234567890.pdf"
}
```

eller vid fel:

```json
{
  "status": false,
  "message": "Otillåten filtyp"
}
```

## Anslags-typer

- `1` = Protokoll
- `2` = Kungörelse
- `3` = Underrättelse

## Status-värden

- `0` = Avpublicerad
- `1` = Publicerad
- `2` = Arkiverad

## Beroenden

API:et kräver shared libraries från `/fbg_apps/include/`:
- `include1.php` - Joomla bootstrap, PDO-databas, session
- `migrera.php` - Joomla REST API wrapper-funktioner
- `pdo_db.php` - Databaskonfiguration

Dessa filer delas mellan flera system och ligger kvar i sin ursprungliga plats.

## Databas

API:et använder följande Joomla-tabeller:
- `a70hd_content` - Artikelinnehåll (huvudtabell)
- `a70hd_fields_values` - Custom fields för anslag-specifika data

**Kategori:** 14 (anslagstavla)

**Custom field IDs:**
- 8 = noticedatemeeting
- 9 = noticedateadjusted
- 10 = noticedateposted
- 12 = noticedocumentlocation
- 13 = noticecontactperson
- 14 = noticecontactemail
- 15 = noticedateremoval
- 25 = noticetype
- 26 = noticeattachment
- 33 = noticelink

## Filuppladdning

Filer sparas till: `/home/httpd/fbg-intranet/kommun.falkenberg.se/images/anslagstavla/`

Åtkomst via: `https://kommun.falkenberg.se/images/anslagstavla/`

**Tillåtna filtyper:** PDF (application/pdf)

**Filnamnsmönster:** `notice{timestamp}.pdf`

## Säkerhet

- Directory browsing är inaktiverat
- Endast .php-filer är tillgängliga
- .htaccess, README.md och andra filer blockeras
- Security headers (X-Content-Type-Options, X-Frame-Options)
- Kräver Joomla-autentisering (via include1.php)
- MIME-type validering för uppladdade filer
- Filstorlek-validering

## Utveckling

För att lägga till nya endpoints:
1. Skapa PHP-fil i denna katalog
2. Include `include1.php` för Joomla bootstrap
3. Include `migrera.php` för REST API-funktioner
4. Använd `jsonHeader()` för korrekt response-header
5. Följ befintligt mönster för error handling
6. Dokumentera här i README.md

## Felsökning

**API returnerar 403:**
- Kontrollera att .htaccess tillåter .php-filer
- Verifiera att användaren är inloggad i Joomla

**Include-fel:**
- Verifiera absoluta sökvägar till `/fbg_apps/include/`
- Kontrollera filrättigheter

**Databas-fel:**
- Kontrollera Joomla bootstrap i include1.php
- Verifiera databaskoppling i pdo_db.php

**Filuppladdning misslyckas:**
- Kontrollera katalog-rättigheter: `/kommun.falkenberg.se/images/anslagstavla/`
- Verifiera MIME-type (måste vara application/pdf)
- Kontrollera filstorlek
