# EPK

Een webapplicatie waarin je kan inloggen. Meerdere bands onder zijn/haar beheer kan hebben. Dashboard, foto's en videos. Kortom is het een website waarin je muziek kan ontdekken.

Dit project zag ik ook meer als een show voor mijn skills die ik heb geleerd bij Tenaz, mijn tweede stage. Ze hebben me veel geleerd over namingconventions, file ordering en Testing.

---

## Functionaliteiten


**Bezoeker (niet ingelogd)**
- Bands zoeken op naam
    - Zoekresultaten worden weergeven met naam, foto en beschrijving
- Openbare home pagina

**Ingelogde gebruiker**
- Registreren, inloggen en uitloggen
- Dashboard met de eigen bands
- Profiel bekijken en aanpassen
- Band aanmaken

**Bandbeheerder**
- Naam, korte beschrijving en biografie aanpassen
- Foto uploaden, vervangen of verwijderen
- Youtube videos toevoegen en verwijderen (mag zoveel als je wilt)
- Achtergrond en tekstkleur van de EPK kiezen, met live voorbeeld en waarschuwing voor contrast en kleurencombos :)
- Andere gebruikers als beheerder toevoegen of verwijderen (min 1 beheerder)
- Band verwijderen met bevestiging

---=


## Handige commando's

| Commando | Wat het doet |
|---|---|
| `php artisan serve` | Start de site |
| `php artisan test` | Doet alle tests|
| `php artisan migrate:fresh --seed` | Leeg de database helemaal en maak nieuwe testdata (deze data is hardcoded) |
| `php artisan db:seed --class=RealBandsSeeder` | Voeg echte bands toe zonder iets te verwijderen (ook hardcoded) |

---

## Database

| Tabel | Inhoud |
|---|---|
| `users` | Accounts  |
| `bands` | Naam, URL, korte beschrijving, biografie, foto, achtergrond en tekstkleur |
| `band_user` | Koppeling tussen gebruikers en bands |
| `videos` | De videos voor de band |

Als je een band verwijdert gaan alle koppelingen mee ook al staan ze ergens anders


