# Tarkvara arendusmeetodid - Projekti Dokumentatsioon

## 1. Projekti olemus ja eesmärk

**Projekti nimetus:** Autokool Infosüsteem ja Veebileht  
**Sihtrühm:** Juhiloa taotlejad (õpilased) ning autokooli administratiivpersonal (administraatorid / õpetajad).  
**Projekti eesmärk:** Luua kaasaegne, turvaline ja modulaarne veebipõhine infosüsteem, mis automatiseerib õpilaste registreerumist juhiloa koolitustele ning pakub autokooli juhtkonnale mugavat tööriista kursuste ja laekunud avalduste haldamiseks.

---

## 2. Projektihaldustarkvara kasutus (Git & GitHub)

Arendusprotsessi haldamiseks ja versioonihalduseks kasutatakse **Giti** ja **GitHubi**:
- **Hoidla (Repository):** `https://github.com/SeDERRED/autokoolileht`
- **Harude strateegia (Branching):** Põhiharu `main`, mis sisaldab alati stabiilset ja testitud versiooni.
- **Commitide struktuur:** Iga muudatus dokumenteeritakse selge sõnumiga (nt `feat: admin MVC module`, `fix: auth verification`, `docs: project specification`).

---

## 3. Lähteülesanne ja nõuete analüüs

### 3.1. Kasutajarollid
1. **Anonüümne külastaja:** saab tutvuda autokooli teenuste ja kursuste nimekirjaga, luua endale kasutajakonto või esitada kursusele registreerumise taotluse.
2. **Õpilane (Tavakasutaja):** saab sisse logida, vaadata oma esitatud taotluste staatust (`Ootel`, `Töös`, `Kinnitatud`) ja hallata oma registreerumisi.
3. **Administraator:** omab täielikku ligipääsu juhtpaneelile (`Juhtpaneel`), saab lisada/kustutada kursusi ning muuta õpilaste taotluste olekuid.

### 3.2. Funktsionaalsed nõuded (Functional Requirements - FR)
- **FR1 (Autentimine):** Süsteem peab võimaldama kasutajatel registreeruda ja turvaliselt sisse logida.
- **FR2 (Paroolide turvalisus):** Paroolid tuleb andmebaasis krüpteerida (`password_hash` / BCRYPT).
- **FR3 (Kursuste kuvamine):** Pealehel ja kataloogis kuvatakse aktiivsed kursused koos hinna, kestuse ja kategooriaga.
- **FR4 (Taotluse esitamine):** Kasutaja saab esitada avalduse kursusele (nimi, e-post, telefoninumber).
- **FR5 (Õpilase profiil):** Sisselogitud kasutajale kuvatakse tema isiklikud taotlused ja nende reaalajas olekud.
- **FR6 (Juhtpaneeli statistika):** Administraator näeb avalduste koguarvu, uute taotluste arvu ja aktiivseid kursusi.
- **FR7 (Kursuste haldamine):** Administraator saab luua uusi koolitusi ja kustutada vanu.
- **FR8 (Avalduste olekute muutmine):** Administraator saab muuta avalduse olekut (`new` -> `in_progress` -> `approved` / `rejected`).
- **FR9 (Juurdepääsukontroll):** Juhtpaneelile pääsevad ligi ainult `admin` rolliga kasutajad.
- **FR10 (Navigatsioon):** Menüü kohandub vastavalt kasutaja rollile (Sisselogimata / Õpilane / Admin).

### 3.3. Mittefunktsionaalsed nõuded (Non-Functional Requirements - NFR)
- **NFR1 (Turvalisus):** SQL-süstimiste (SQL Injection) ennetamine parameetriseeritud päringute kaudu (PDO Prepared Statements). XSS rünnakute ennetamine andmete väljastamisel (`htmlspecialchars`).
- **NFR2 (Arhitektuur):** Range **MVC (Model-View-Controller)** mustri järgimine koos Front Controlleriga (`index.php`) ja puhta URL-i toega (`.htaccess`).
- **NFR3 (Platvormist sõltumatus):** Süsteem peab olema konteineriseeritud **Docker Compose** abil, tagades ühesuguse töö mistahes operatsioonisüsteemis (Windows, Linux, macOS).
- **NFR4 (Kasutatavus):** Reageeriv veebidisain (Responsive CSS), mis kohandub mobiilseadmetele ja lauaarvutitele.

---

## 4. Süsteemi disain ja arhitektuur

### 4.1. Arhitektuurne lahendus (MVC + Modulaarne Admin)
Rakendus on üles ehitatud kahe taseme MVC mustrile:
1. **Avalik MVC (`/`):**
   - `controller/`: `HomeController`, `CourseController`, `AuthController`, `UserController`
   - `model/`: `CourseModel`, `UserModel`, `ApplicationModel`
   - `view/`: `home.php`, `courses.php`, `profile.php`, `login.php`, `register.php`, `404.php`
2. **Administraatori eraldiseisev MVC (`admin/`):**
   - `admin/controller/`: `AdminController`
   - `admin/model/`: `AdminModel`
   - `admin/view/`: `dashboard.php`, `login.php`, `header.php`, `footer.php`
3. **Tuum ja tugifailid (`inc/`, `route/`):**
   - `route/routing.php`: Keskne ruuter, mis seob HTTP meetodid ja teed kontrolleritega.
   - `inc/db.php`: PDO andmebaasiühendus (MySQL / SQLite automaatse tuvastusega).
   - `inc/functions.php`: Globaalsed abifunktsioonid (`renderView`, `redirect`, `isAdmin`, `e`).

### 4.2. Andmebaasi skeem (ERD)

```text
+-------------------+       +-----------------------+
|      users        |       |        courses        |
+-------------------+       +-----------------------+
| id (PK, INT)      |       | id (PK, INT)          |
| username (VARCHAR)|       | title (VARCHAR)       |
| password (VARCHAR)|       | category (VARCHAR)    |
| role (VARCHAR)    |       | description (TEXT)    |
| created_at (TIME) |       | duration (VARCHAR)    |
+-------------------+       | price (DECIMAL)       |
                            | image (VARCHAR)       |
                            +-----------------------+
                                        | 1
                                        |
                                        | N
                            +-----------------------+
                            |     applications      |
                            +-----------------------+
                            | id (PK, INT)          |
                            | course_id (FK, INT)   |
                            | name (VARCHAR)        |
                            | email (VARCHAR)       |
                            | phone (VARCHAR)       |
                            | status (VARCHAR)      |
                            | created_at (TIME)     |
                            +-----------------------+
```

---

## 5. Testimine ja kvaliteedi tagamine

Süsteemile viidi läbi funktsionaalne testimine (Black-box testing).

| Test ID | Testitav funktsionaalsus | Testi sammud | Oodatud tulemus | Tegelik tulemus | Olek |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-01** | Kursuste kuvamine avalehel | Ava brauseris `http://localhost:8080/` | Kuvatakse populaarsed kursused koos hindade ja kategooriatega | Kursused kuvatakse korrektselt | **PASS** |
| **TC-02** | Uue konto registreerimine | Täida vorm lehel `/register` korrektsete andmetega | Kasutaja luuakse andmebaasi ja suunatakse lehele `/profile` | Konto loodud, suunamine õnnestus | **PASS** |
| **TC-03** | Vale parooliga sisselogimine | Sisesta `/login` lehel vigane parool | Kuvatakse punane hoiatus: "Vale kasutajanimi või parool." | Teade kuvatud, ligipääs blokeeritud | **PASS** |
| **TC-04** | Kursusele registreerumine | Ava `/courses`, kliki "Registreeru kursusele", täida kontaktvorm | Avaldus salvestub tabelisse `applications` staatusega `new` | Avaldus salvestatud, kinnitusteade ekraanil | **PASS** |
| **TC-05** | Õpilase profiili kuvamine | Logi sisse õpilasena, ava `/profile` | Kuvatakse ainult selle õpilase esitatud avaldused ja nende staatused | Isiklikud avaldused kuvatakse tabelina | **PASS** |
| **TC-06** | Tavakasutaja ligipääs adminile | Logi sisse rolliga `user` ja ava `/login/admin` | Süsteem keelab ligipääsu teatega "Juurdepääs piiratud" | Ligipääs keelatud, juhtpaneeli ei näidata | **PASS** |
| **TC-07** | Admin: uue kursuse lisamine | Logi sisse `admin`, ava Juhtpaneel, täida kursuse vorm | Uus kursus lisandub andmebaasi ja ilmub tabelisse | Kursus lisatud ja nähtav kohe | **PASS** |
| **TC-08** | Admin: avalduse staatuse muutmine | Muuda rippmenüüst staatuseks `Kinnitatud` | Staatus uueneb reaalajas andmebaasis ilma lehe krahhita | Staatus uuendatud roheliseks "approved" | **PASS** |

---

## 6. Juurutamine (Deployment)

Projekt on juurutatud kasutades **Docker Compose** infrastruktuuri:

1. **`app` konteiner (`php:8.2-apache`):**
   - Aktiveeritud `mod_rewrite` Apache moodul `.htaccess` ruutimiseks.
   - Installitud `pdo` ja `pdo_mysql` laiendused.
   - Lähtekood on seotud `volume` abil, mis võimaldab arendusel koheseid uuendusi.
2. **`db` konteiner (`mysql:8.0`):**
   - Automaatne andmebaasi initsialiseerimine failist `database.sql`.
   - Püsiv andmete salvestus `named volume` (`autokool_db_data`) kaudu.
3. **`phpmyadmin` konteiner:**
   - Graafiline veebiliides andmebaasi haldamiseks pordil `8081`.

### Käivitamine:
```bash
docker compose up -d --build
```
- Veebileht: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`
