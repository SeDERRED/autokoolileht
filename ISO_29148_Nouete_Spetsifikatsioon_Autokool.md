# Tarkvaranõuete Spetsifikatsioon (SRS)
## Standard: ISO/IEC/IEEE 29148:2018
### Projekt: Autokool - Veebipõhine infosüsteem ja haldusplatvorm

---

**Dokumendi versioon:** 2.0  
**Kuupäev:** 19. november 2026  
**Autor:** JPTV24 Arendusmeeskond  
**Õppeaine:** Tarkvara arendusmeetodid  
**Staatus:** Heaks kiidetud / Lõplik  

---

## 1. Sissejuhatus (Introduction)

### 1.1 Eesmärk (Purpose)
Käesolev dokument sätestab tarkvaranõuete spetsifikatsiooni (Software Requirements Specification - SRS) veebipõhisele infosüsteemile **«Autokool»** kooskõlas rahvusvahelise standardiga **ISO/IEC/IEEE 29148:2018** (Systems and software engineering — Life cycle processes — Requirements engineering). 

Dokument on aluseks süsteemi arhitektuursele disainile, realiseerimisele, testimisele ning vastuvõtule.

### 1.2 Süsteemi ulatus (Scope)
Süsteem «Autokool» on terviklik veebirakendus, mis automatiseerib:
- Autokooli koolituskursuste kataloogi haldamist ja avalikustamist.
- Õpilaste registreerumist ja taotluste vastuvõtmist interneti teel.
- Õpilaste isiklikku kabinetti («Minu konto») esitatud taotluste staatuste reaalajas jälgimiseks.
- Administraatori juhtpaneeli kursuste, avalduste ja äristatistika reaalajas haldamiseks.
- Turvalist autentimist ja kaitset brute-force rünnakute vastu (automaatne 30-sekundiline lukustus pärast 3 ebaõnnestunud katset).
- Kohanduvat reageerivat kasutajaliidest (Responsive Web Design), mis tagab tõrgeteta mobiilikasutuse ilma horisontaalse kerimiseta.

### 1.3 Mõisted, lühendid ja definitsioonid (Definitions & Acronyms)
| Mõiste / Lühend | Tähendus |
| :--- | :--- |
| **SRS** | Software Requirements Specification (Tarkvaranõuete spetsifikatsioon vastavalt ISO 29148). |
| **MVC** | Model-View-Controller tarkvaraarhitektuuri muster. |
| **PDO** | PHP Data Objects – turvaline andmebaasi abstraktsioonikiht. |
| **FR** | Functional Requirement (Funktsionaalne nõue). |
| **NFR** | Non-Functional Requirement (Mittefunktsionaalne nõue). |
| **Brute-Force Lockout** | Autentimise turvalukustus, mis piirab korduvaid ebaõnnestunud sisselogimisi teatud ajavahemikuks. |
| **RWD** | Responsive Web Design – veebidisain, mis skaleerub ekraani suurusele ilma külgkerimiseta. |
| **ERD** | Entity Relationship Diagram (Olemite-suhete diagramm). |

### 1.4 Viited (References)
1. **ISO/IEC/IEEE 29148:2018:** Systems and software engineering — Life cycle processes — Requirements engineering.
2. **ISO/IEC/IEEE 26514:2018:** Requirements for designers and developers of user documentation.
3. PHP Standard Recommendations (PSR-1, PSR-12).
4. OWASP Top 10 veebirakenduste turvalisuse standard.

---

## 2. Üldine kirjeldus (Overall Description)

### 2.1 Toote vaade ja kontekst (Product Perspective)
«Autokool» infosüsteem on iseseisev konteineriseeritud veebirakendus, mis toimib LAMP (Linux, Apache, MySQL, PHP) infrastruktuuris Docker Compose abil.

```text
[ Klient: Nutitelefon / Tahvelarvuti / PC ]
                   │
                   ▼ (HTTP / HTTPS pordil 8080)
┌──────────────────────────────────────────────────────────┐
│ Docker konteiner: lamp-apache (PHP 8.2 + Apache)        │
│ ┌──────────────────────────────────────────────────────┐ │
│ │ Front Controller (index.php) + mod_rewrite (.htaccess)│ │
│ ├──────────────────────────────────────────────────────┤ │
│ │ MVC Kontrollerid: Home, Course, Auth, User, Admin     │ │
│ ├──────────────────────────────────────────────────────┤ │
│ │ Mudelid ja Ärilogika (UserModel, CourseModel jne)    │ │
│ └─────────────────────────┬────────────────────────────┘ │
└───────────────────────────┼──────────────────────────────┘
                            │ (PDO ühendus pordil 3306)
                            ▼
┌──────────────────────────────────────────────────────────┐
│ Docker konteiner: lamp-mysql (MySQL 8.0 / autokool DB)  │
│ [ Tabelid: users, courses, applications ]                │
└──────────────────────────────────────────────────────────┘
```

### 2.2 Kasutajarollid ja omadused (User Characteristics)
Süsteem eristab kolme peamist sihtgruppi:
1. **Anonüümne külastaja (Külaline):**
   - Saab vaadata avalehte ja koolituskursuste nimekirja koos kestuse ja hindadega.
   - Saab esitada kursusele registreerumise taotluse.
   - Saab luua uue õpilaskonto.
2. **Registreeritud õpilane (Tavakasutaja, roll `user`):**
   - Saab sisse logida oma kasutajanime ja parooliga.
   - Saab ligipääsu lehele «Minu konto» (`/profile`), kus näeb kõiki oma esitatud taotlusi ja nende staatust (`Ootel`, `Töös`, `Kinnitatud`, `Tagasi lükatud`).
3. **Administraator (Autokooli personal, roll `admin`):**
   - Omab ligipääsu administreerimispaneelile (`/login/admin` ja `/admin`).
   - Haldab kursuste nimekirja (kursuste lisamine ja kustutamine).
   - Haldab laekunud avaldusi ja muudab nende olekuid.
   - Jälgib reaalajas finants- ja avalduste statistikat (KPI).

### 2.3 Üldised piirangud (General Constraints)
- Süsteem peab toimima suvalises kaasaegses veebibrauseris (Chrome, Firefox, Safari, Edge) ilma lisaprogrammideta.
- Kasutajaliides peab olema 100% kohanduv ekraanilaiustele alates 320px kuni 2560px ilma horisontaalse kerimisribata.
- Andmebaasina kasutatakse MySQL 8 serverit (fallback toega SQLite3 failibaasile).
- Rakendus peab olema käivitatav üheainsa käsuga `docker compose up -d`.

### 2.4 Eeldused ja sõltuvused (Assumptions and Dependencies)
- Kasutaja seadmes on lubatud küpsised (PHP Session Cookie `PHPSESSID`).
- Host-masinas on paigaldatud ja käivitatud Docker Desktop / Docker Engine.

---

## 3. Spetsiifilised nõuded (Specific Requirements)

### 3.1 Välisliideste nõuded (External Interface Requirements)

#### 3.1.1 Kasutajaliides (User Interface - UI)
- **UI-01:** Reageeriv disain (RWD) peab tagama, et ükski element ei ulatu ekraanist välja (`overflow-x: hidden`).
- **UI-02:** Mobiilseadmetes (ekraanilaius ≤ 768px) teisendatakse laiad andmetabelid ülevaatlikeks vertikaalseteks teabekaartideks (`.mobile-card`), välistades vajaduse lehte horisontaalselt kerida.
- **UI-03:** Navigatsioonimenüü peab kohanduma: kitsastel ekraanidel paigutuvad lingid mitmele reale või veeruna, säilitades klikitavuse.
- **UI-04:** Vormid (kursuse lisamine, registreerumine, sisselogimine) peavad mobiilseadmetes lülituma üherealisele paigutusele (`grid-template-columns: 1fr`).

#### 3.1.2 Riistvaraliidesed (Hardware Interfaces)
Puuduvad erinõuded; toetatud on nii puuteekraanid kui ka hiir/klaviatuur.

#### 3.1.3 Tarkvaraliidesed (Software Interfaces)
- **PHP 8.2** Apache mooduliga (`mod_rewrite`).
- **MySQL 8.0** andmebaasiserver `utf8mb4` kodeeringuga.
- **phpMyAdmin** andmebaasi visuaalseks monitooringuks.

---

### 3.2 Funktsionaalsed nõuded (Functional Requirements - FR)

#### Moodul 1: Autentimine ja turvalisus
- **FR-01 (Kasutaja registreerimine):** Süsteem peab võimaldama külastajal luua konto unikaalse kasutajanime ja parooliga.
- **FR-02 (Paroolide räsimine):** Süsteem peab krüpteerima kõik paroolid tööstusliku `BCRYPT` algoritmiga (`password_hash`), toortekstina paroole ei talletata.
- **FR-03 (Sisselogimine):** Kasutaja saab siseneda süsteemi õigete volitustega. Administraator suunatakse juhtpaneeli, õpilane oma profiilile.
- **FR-04 (Brute-Force kaitse / Turvalukustus):** Kui kasutaja sisestab **3 korda järjest vale parooli**, peab süsteem blokeerima sisselogimise täpselt **30 sekundiks**. Blokeeringu ajal kuvatakse selge hoiatus loenduriga ning sisselogimispäringuid tagasi lükatakse.
- **FR-05 (Väljalogimine):** Kasutaja saab igal ajal sessiooni turvaliselt lõpetada (`/logout`).

#### Moodul 2: Avalik veebileht ja kursused
- **FR-06 (Avaleht):** Kuvatakse autokooli tutvustus, eelised ja 3 populaarseimat kursust koos hindadega.
- **FR-07 (Kursuste kataloog):** Lehel `/courses` kuvatakse kõik aktiivsed kursused (nimetus, kategooria tähis, kestus, hind, kirjeldus).
- **FR-08 (Kursusele registreerumine):** Iga kursuse juures peab olema nupp «Registreeru kursusele», mis avab ankeedi (õpilase nimi, e-post, telefoninumber). Avaldus salvestatakse andmebaasi staatusega `new`.

#### Moodul 3: Õpilase isiklik kabinet («Minu konto»)
- **FR-09 (Oma avalduste kuvamine):** Sisselogitud õpilasele kuvatakse lehel `/profile` ainult temaga seotud avaldused.
- **FR-10 (Staatuse jälgimine):** Kuvatakse avalduse hetkeseis värvilise märgistusena: `Ootel (Uus)`, `Töös`, `Kinnitatud`, `Tagasi lükatud`.

#### Moodul 4: Administraatori juhtpaneel
- **FR-11 (Juurdepääsukontroll):** Juhtpaneelile (`/admin`, `/login/admin`) on lubatud ligipääs ainult rolliga `admin`.
- **FR-12 (Reaalajas statistika):** Juhtpaneeli ülaosas kuvatakse 4 KPI mõõdikut: Uued avaldused, Avaldusi kokku, Aktiivseid kursusi ja Kinnitatud tulu (€), samuti staatuste visuaalne jaotusriba.
- **FR-13 (Kursuse loomine):** Administraator saab lisada uue koolituse (nimetus, kategooria, kestus, hind, kirjeldus).
- **FR-14 (Kursuse kustutamine):** Administraator saab vanu kursusi süsteemist eemaldada kinnitusküsitlusega.
- **FR-15 (Avalduste oleku muutmine):** Administraator saab rippmenüüst muuta taotluse olekut (`new` → `in_progress` → `approved` / `rejected`), mis kajastub koheselt õpilase profiilis ja finantsaruandes.

---

### 3.3 Mittefunktsionaalsed nõuded (Non-Functional Requirements - NFR)

#### 3.3.1 Turvalisus (Security - NFR-SEC)
- **NFR-SEC-01 (SQL Injection kaitse):** Kõik andmebaasipäringud peavad kasutama PDO ettevalmistatud lauseid (Prepared Statements) koos parameetrite sidumisega.
- **NFR-SEC-02 (XSS ennetus):** Kõik kasutaja sisestatud andmed peavad vaadetes läbima `htmlspecialchars()` töötluse abifunktsiooni `e()` kaudu.
- **NFR-SEC-03 (Sessiooniturve):** Sessiooniandmed talletatakse serveripoolselt; identifikaatorid regenereeritakse.

#### 3.3.2 Kasutatavus ja mobiilsus (Usability - NFR-USE)
- **NFR-USE-01 (Horisontaalse kerimise puudumine):** Ühelgi ekraanisuurusel (sh 360px ja 375px nutitelefonid) ei tohi tekkida terve lehe horisontaalset kerimisriba.
- **NFR-USE-02 (Kohanduvad tabelid):** Nii administraatori juhtpaneelis kui ka õpilase profiilis peavad tabelid mobiilivaates esituma mugavate kaartidena.

#### 3.3.3 Jõudlus ja töökindlus (Performance & Reliability - NFR-REL)
- **NFR-REL-01:** Lehe laadimisaeg lokaalses võrgus ei tohi ületada 200 ms.
- **NFR-REL-02 (Andmebaasi fallback):** Kui MySQL server ei ole kättesaadav, lülitub süsteem automaatselt SQLite andmebaasile ilma vealehte kuvamata.

---

### 3.4 Andmemudel ja olemid (Data Model & Schema)

```sql
-- Kasutajad
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kursused
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    duration VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'course-default.jpg'
);

-- Registreerimisavaldused
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
);
```

---

## 4. Nõuete verifitseerimismaatriks (Verification Matrix)

| Nõude ID | Nõude lühikirjeldus | Verifitseerimise meetod | Testilugu (TC) | Tulemus |
| :--- | :--- | :---: | :---: | :---: |
| **FR-01** | Kasutaja registreerimine | Testimine | TC-02 | **NÕUETELE VASTAV** |
| **FR-02** | Paroolide krüpteerimine BCRYPT | Ülevaatus / Test | TC-02 | **NÕUETELE VASTAV** |
| **FR-03** | Sisselogimine ja suunamine | Testimine | TC-01, TC-06 | **NÕUETELE VASTAV** |
| **FR-04** | Turvalukustus (3 katset / 30 sek) | Testimine | TC-09 | **NÕUETELE VASTAV** |
| **FR-06** | Avalehe kursuste kuvamine | Testimine | TC-01 | **NÕUETELE VASTAV** |
| **FR-08** | Kursusele registreerumine | Testimine | TC-04 | **NÕUETELE VASTAV** |
| **FR-09** | Õpilase profiil ja staatused | Testimine | TC-05 | **NÕUETELE VASTAV** |
| **FR-11** | Admin õiguste kontroll | Testimine | TC-06 | **NÕUETELE VASTAV** |
| **FR-12** | KPI statistika ja tulu arvutus | Testimine / Ülevaatus | TC-08 | **NÕUETELE VASTAV** |
| **FR-13** | Kursuse lisamine administraatori poolt | Testimine | TC-07 | **NÕUETELE VASTAV** |
| **FR-15** | Avalduse staatuse muutmine | Testimine | TC-08 | **NÕUETELE VASTAV** |
| **NFR-USE-01** | Horisontaalse kerimise puudumine | Testimine mobiilis | TC-10 | **NÕUETELE VASTAV** |
| **NFR-SEC-01** | SQL-süstimise kaitse (PDO) | Koodi analüüs | TC-03 | **NÕUETELE VASTAV** |

---

## 5. Testilugude raport (Test Case Report)

### TC-01: Avalehe laadimine ja kursuste kuvamine
- **Eeldus:** Docker konteinerid töötavad.
- **Sammud:** Ava veebileht `http://localhost:8080/autokool/`.
- **Oodatud tulemus:** Leht avaneb veakoodita (HTTP 200), kuvatakse päis, kangelasbänner ja kursused.
- **Tegelik tulemus:** Edukas (**PASS**).

### TC-04: Kursusele registreerumine
- **Sammud:** Ava leht `/courses`, kliki «Registreeru kursusele», sisesta nimi, e-post, telefon ja kliki «Esita avaldus».
- **Oodatud tulemus:** Vorm suunab tagasi teatega «Aitäh registreerumise eest!», andmebaasi tekib kirje staatusega `new`.
- **Tegelik tulemus:** Edukas (**PASS**).

### TC-08: Administraatori statistika ja staatuste uuendamine
- **Sammud:** Logi sisse administraatorina (`admin` / `admin123`), ava juhtpaneel, muuda õpilase avalduse rippmenüüst staatus `Kinnitatud`.
- **Oodatud tulemus:** Staatus muutub andmebaasis, KPI plokis suureneb kinnitatud tulu summa vastavalt kursuse hinnale.
- **Tegelik tulemus:** Edukas (**PASS**).

### TC-09: Autentimise turvalukustus (3 vale parooli katset)
- **Sammud:** 
  1. Ava leht `/login`.
  2. Sisesta kasutajanimi `admin` ja vale parool 3 korda järjest.
  3. Proovi koheselt 4. korda sisse logida.
- **Oodatud tulemus:** Pärast 3. katset kuvatakse teade: «Vale kasutajanimi või parool. Liiga palju katseid — konto on blokeeritud 30 sekundiks». 4. katsel ligipääs lükatakse tagasi enne andmebaasipäringut. 30 sekundi möödudes sisselogimisvõimalus taastub.
- **Tegelik tulemus:** Süsteem loendab sekundeid korrektselt ja avab ligipääsu pärast 30s möödumist (**PASS**).

### TC-10: Mobiilivaate kohandumine (horisontaalse kerimise puudumine)
- **Sammud:** 
  1. Ava brauseri arendustööriistad (DevTools, `F12`).
  2. Lülita sisse seadme emuleerimine laiusele 375px (iPhone SE / Android).
  3. Ava avaleht, `/courses`, `/profile` ja `/login/admin`.
- **Oodatud tulemus:** Ühelgi lehel ei teki akna horisontaalset kerimisriba. Tabelid kuvatakse loetavate vertikaalsete kaartidena (`.mobile-card`).
- **Tegelik tulemus:** Edukas (**PASS**).

---
*Dokument koostatud ja verifitseeritud vastavalt standardile ISO/IEC/IEEE 29148:2018.*
