# BUTOLOG - Strona serwisu zdalnej naprawy butów

Profesjonalna strona internetowa dla serwisu zajmującego się zdalną naprawą obuwia przez paczkomaty InPost.

## Funkcjonalności

### 🎨 Design i UX
- Nowoczesny, responsywny design
- Płynne animacje i efekty hover
- Optymalizacja dla urządzeń mobilnych
- Intuicyjna nawigacja z smooth scrolling

### 📝 Formularze
- **Formularz wyceny** - z możliwością przesyłania zdjęć
- **Formularz kontaktowy** - do ogólnych zapytań
- Walidacja po stronie klienta i serwera
- Automatyczne generowanie ID użytkownika
- Wysyłka e-maili na adres kontakt@picabela.pl

### 📧 System e-mailowy
- Wysyłka e-maili z załącznikami (zdjęcia)
- HTML templates dla lepszej prezentacji
- Backup do plików log
- Obsługa błędów i fallback

### 🔧 Sekcje strony
- **Hero** - główna sekcja powitalna
- **O serwisie** - korzyści z usługi
- **Proces** - 7 kroków naprawy
- **Dlaczego my** - wyróżniki serwisu
- **Wycena** - główny formularz
- **FAQ** - najczęściej zadawane pytania
- **Kontakt** - dane kontaktowe i formularz

## Wymagania techniczne

### Serwer
- PHP 7.4 lub nowszy
- Apache z mod_rewrite
- Funkcja mail() lub SMTP
- Obsługa przesyłania plików (min. 10MB)

### Uprawnienia
- Katalog `uploads/` - 755 lub 777
- Pliki PHP - 644
- Możliwość tworzenia plików .log

## Instalacja

1. **Wgraj pliki na serwer**
   ```
   index.html
   assets/css/style.css
   assets/js/script.js
   send_quote.php
   send_contact.php
   .htaccess
   ```

2. **Ustaw uprawnienia**
   ```bash
   chmod 755 uploads/
   chmod 644 *.php
   chmod 644 .htaccess
   ```

3. **Sprawdź konfigurację PHP**
   - `upload_max_filesize = 10M`
   - `post_max_size = 12M`
   - `max_execution_time = 30`
   - Funkcja `mail()` włączona

4. **Testuj formularze**
   - Wyślij testowe zgłoszenie wyceny
   - Sprawdź czy e-maile docierają
   - Zweryfikuj logi w plikach .log

## Konfiguracja e-mail

### Podstawowa (funkcja mail())
Domyślnie używana jest funkcja `mail()` PHP. Sprawdź czy:
- Serwer ma skonfigurowany SMTP
- Funkcja mail() nie jest zablokowana
- Nagłówki e-maili są poprawne

### Zaawansowana (SMTP)
Dla lepszej dostarczalności można zintegrować:
- PHPMailer
- SwiftMailer  
- Zewnętrzne API (SendGrid, Mailgun)

## Struktura plików

```
/
├── index.html              # Główna strona
├── assets/
│   ├── css/
│   │   └── style.css      # Style CSS
│   └── js/
│       └── script.js      # JavaScript
├── send_quote.php         # Obsługa formularza wyceny
├── send_contact.php       # Obsługa formularza kontaktowego
├── uploads/               # Katalog na zdjęcia
├── .htaccess             # Konfiguracja Apache
├── quote_submissions.log  # Logi zgłoszeń wyceny
├── contact_submissions.log # Logi wiadomości kontaktowych
└── README.md             # Ta dokumentacja
```

## Bezpieczeństwo

### Zaimplementowane zabezpieczenia
- Walidacja typów plików
- Limit rozmiaru plików (10MB)
- Sanityzacja danych wejściowych
- Ochrona plików .log przed dostępem
- Headers bezpieczeństwa w .htaccess

### Dodatkowe zalecenia
- Regularne aktualizacje PHP
- Monitoring logów serwera
- Backup bazy danych (jeśli używana)
- SSL/HTTPS dla produkcji

## Optymalizacja

### Performance
- GZIP compression
- Browser caching
- Minifikacja CSS/JS (opcjonalnie)
- Optymalizacja obrazów

### SEO
- Semantyczny HTML
- Meta tagi
- Structured data (do dodania)
- Sitemap.xml (do dodania)

## Wsparcie

### Logi i debugging
- `quote_submissions.log` - wszystkie zgłoszenia wyceny
- `contact_submissions.log` - wszystkie wiadomości kontaktowe
- Logi serwera Apache/PHP

### Częste problemy
1. **E-maile nie docierają**
   - Sprawdź funkcję mail() PHP
   - Zweryfikuj konfigurację SMTP serwera
   - Sprawdź folder SPAM

2. **Błędy przesyłania plików**
   - Zwiększ limity PHP
   - Sprawdź uprawnienia katalogu uploads/
   - Zweryfikuj dostępne miejsce na dysku

3. **Problemy z formularzami**
   - Sprawdź JavaScript w konsoli przeglądarki
   - Zweryfikuj ścieżki do plików PHP
   - Sprawdź logi błędów serwera

## Kontakt techniczny

W przypadku problemów technicznych:
- Sprawdź logi serwera
- Zweryfikuj konfigurację PHP
- Skontaktuj się z administratorem hostingu

---

**BUTOLOG** - Profesjonalna naprawa obuwia na odległość
© 2025 Wszystkie prawa zastrzeżone