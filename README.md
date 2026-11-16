# Electronic Press Kit (PHPA5)

Webapplicatie waarmee bands en artiesten hun eigen EPK-pagina beheren. Gebouwd met Laravel 12, Bootstrap 5 en MySQL/MariaDB (XAMPP).

## De site starten

1. Start **MySQL** in het XAMPP Control Panel.
2. Open een terminal in deze map en start de server:

   ```
   php artisan serve
   ```

3. Ga in je browser naar **http://localhost:8000**.

Stoppen doe je met `Ctrl + C` in de terminal.

### CSS of JavaScript aangepast?

```
npm run build
```

Of laat tijdens het werken in een **tweede** terminal `npm run dev` draaien. Dan worden wijzigingen direct zichtbaar.

## Testaccounts

Na `php artisan migrate:fresh --seed` (let op: dit leegt de hele database en vult hem opnieuw met testdata):

| E-mailadres          | Wachtwoord | Beheert                          |
|----------------------|------------|----------------------------------|
| test@example.com     | password   | De Gedeelde Band, Test Band      |
| tweede@example.com   | password   | De Gedeelde Band                 |

## Handige commands

| Command                              | Wat het doet                                      |
|--------------------------------------|---------------------------------------------------|
| `php artisan serve`                  | Site starten op http://localhost:8000             |
| `php artisan test`                   | Alle tests draaien                                |
| `php artisan migrate`                | Nieuwe migrations uitvoeren                       |
| `php artisan migrate:fresh --seed`   | Database leegmaken en opnieuw vullen met testdata |
| `php artisan route:list --except-vendor` | Alle routes bekijken                          |

## Eerste keer installeren (op een andere computer)

```
php composer.phar install
npm install
npm run build
copy .env.example .env
php artisan key:generate
php artisan storage:link
```

Maak in phpMyAdmin een database `phpa5` aan en voer daarna `php artisan migrate --seed` uit.
