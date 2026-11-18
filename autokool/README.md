# Autokool - Veebipõhine infosüsteem (PHP MVC & Docker)

Koolitöö projekt aines **«Tarkvara arendusmeetodid»**.

Projekt katab tarkvara arenduse elutsükli (SDLC): lähteülesannete analüüsi, arhitektuurse disaini (MVC), realiseerimise PHP-s, testimise ja konteineriseeritud juurutamise (Docker).

Põhjalik analüüs ja spetsifikatsioon asub failis **[DOKUMENTATSIOON.md](DOKUMENTATSIOON.md)**.

---

## 📁 Projekti struktuur

Struktuur järgib rangelt ettenähtud näidist:

```text
autokool/
├── admin/                         # Administraatori eraldiseisev MVC moodul
│   ├── controller/AdminController.php
│   ├── model/AdminModel.php
│   ├── view/ (dashboard.php, header.php, footer.php, login.php)
│   └── index.php
├── controller/                    # Peamised kontrollerid
│   ├── HomeController.php         # Avalehe loogika
│   ├── CourseController.php       # Kursuste kuvamine ja registreerumine
│   ├── AuthController.php         # Sisselogimine ja registreerimine
│   └── UserController.php         # Õpilase isiklik kabinet (profiil)
├── images/ (imeges/)              # Staatilised graafikafailid
├── inc/                           # Tuum ja konfiguratsioon
│   ├── db.php                     # Andmebaasi ühendus (PDO MySQL & SQLite)
│   └── functions.php              # Globaalsed abifunktsioonid
├── js/
│   └── main.js                    # Kliendipoolsed skriptid (modaalaknad, sündmused)
├── model/                         # Andmemudelid
│   ├── CourseModel.php            # Kursuste CRUD
│   ├── UserModel.php              # Kasutajate haldus ja paroolide verifitseerimine
│   └── ApplicationModel.php       # Kursuste avalduste haldus
├── route/
│   └── routing.php                # Keskne URL-ruuter (GET ja POST)
├── view/                          # Avalikud vaated (HTML-mallid)
│   ├── header.php, footer.php
│   ├── home.php, courses.php
│   ├── profile.php (Minu konto)
│   ├── login.php, register.php
│   └── 404.php
├── .dockerignore                  # Dockeri ignoreerimisfail
├── .htaccess                      # Apache ümbersuunamine (Front Controller)
├── Dockerfile                     # PHP 8.2 + Apache konteineri definitsioon
├── docker-compose.yml             # Süsteemi orkestreerimine (PHP + MySQL 8 + phpMyAdmin)
├── index.php                      # Peamine sisenemispunkt (Front Controller)
├── style.css                      # Kogu rakenduse stiilileht (Responsive)
├── database.sql                   # MySQL andmebaasi skeem ja algandmed
├── DOKUMENTATSIOON.md             # Täielik projektidokumentatsioon ja testimisaruanne
└── README.md                      # Projekti lühitutvustus
```

---

## 🚀 Kiirkäivitus (Docker Compose)

### 1. Käivitamine
Veendu, et Docker Desktop töötab, ja käivita projekti kaustas:
```bash
docker compose up -d --build
```

### 2. Süsteemi aadressid
- **Veebileht:** [http://localhost:8080](http://localhost:8080)
- **Juhtpaneel:** [http://localhost:8080/login/admin](http://localhost:8080/login/admin)
- **phpMyAdmin (Andmebaas):** [http://localhost:8081](http://localhost:8081)

---

## 🔑 Testkontod

| Roll | Kasutajanimi | Parool | Õigused |
| :--- | :--- | :--- | :--- |
| **Administraator** | `admin` | `admin123` | Juurdepääs juhtpaneelile, kursuste ja avalduste täielik haldamine |
| **Õpilane (Tavakasutaja)** | `user` | `user123` | Isiklik kabinet (`Minu konto`), oma avalduste jälgimine |

---

## 🧪 Testimine

Kõik funktsionaalsed nõuded on testitud. Testilugude raport (TC-01 kuni TC-08) on toodud failis **[DOKUMENTATSIOON.md](DOKUMENTATSIOON.md)**.
