# Autokooli Veebipõhise Infosüsteemi Kasutusjuhend
## Standard: ISO/IEC/IEEE 26514:2018
### (Requirements for designers and developers of user documentation)

---

**Toode:** Autokool Veebiplatvorm ja Haldussüsteem  
**Dokumendi kood:** KJ-ISO-26514-AUTOKOOL-V2  
**Versioon:** 2.0  
**Väljaandmise kuupäev:** 19. november 2026  
**Sihtrühm:** Autokooli õpilased, registreerujad ja administraatorid  
**Keeleversioon:** Eesti keel  

---

## 1. Dokumendi ülevaade ja sissejuhatus

### 1.1 Eesmärk
Käesolev kasutusjuhend on koostatud vastavalt rahvusvahelisele standardile **ISO/IEC/IEEE 26514:2018** ning selle eesmärk on anda lõppkasutajale ja administraatorile selged, samm-sammulised juhised infosüsteemi **«Autokool»** paigaldamiseks, seadistamiseks ning igapäevaseks kasutamiseks.

### 1.2 Dokumendi konventsioonid ja tähised
Juhendis kasutatakse järgmisi tähiseid tähelepanu juhtimiseks:

> **[MÄRKUS]** Täiendav kasulik teave või süsteemi taustakirjeldus.  
> **[TÄHTIS]** Oluline nõue või samm, mida peab kindlasti järgima.  
> **[HOIATUS]** Tegevus, mis võib põhjustada andmete kustumise või juurdepääsu ajutise blokeerimise.

---

## 2. Süsteeminõuded ja eeldused

### 2.1 Nõuded kliendiseadmele (Lõppkasutaja)
Süsteem on veebipõhine ning ei nõua spetsiaalse tarkvara allalaadimist:
- **Operatsioonisüsteem:** Windows, macOS, Linux, Android või iOS.
- **Veebibrauser:** Google Chrome, Mozilla Firefox, Microsoft Edge, Safari või Opera (versioonid alates 2022. aastast).
- **Ekraan:** Toetatud on nii lauaarvutid kui ka mobiiltelefonid (ekraanilaius alates 320 px). Süsteem on täielikult kohanduv (Responsive Web Design) ning ei nõua horisontaalset kerimist.

### 2.2 Nõuded serverkeskkonnale (Paigaldaja)
- **Docker Desktop** või **Docker Engine** koos Docker Compose pluginaga.
- Vabad võrgupordid:
  - `8080` – Veebirakendus (Apache + PHP 8.2)
  - `8082` – phpMyAdmin andmebaasi liides
  - `3388` (või `3306`) – MySQL 8.0 andmebaas

---

## 3. Süsteemi käivitamine (Kiirjuhend administraatorile)

### 3.1 Konteinerite käivitamine
1. Ava käsurida (PowerShell või Terminal).
2. Liigu projekti juurkausta:
   ```bash
   cd D:\JPTV24\docker
   ```
3. Käivita teenused taustal:
   ```bash
   docker compose up -d
   ```
4. Kontrolli konteinerite staatust:
   ```bash
   docker ps
   ```
   Peavad töötama konteinerid: `lamp-apache`, `lamp-mysql`, `lamp-phpmyadmin`.

### 3.2 Süsteemi veebiaadressid
- **Avalik veebileht:** [http://localhost:8080/autokool/](http://localhost:8080/autokool/)
- **Juhtpaneeli sisselogimine:** [http://localhost:8080/autokool/login/admin](http://localhost:8080/autokool/login/admin)
- **Andmebaasi phpMyAdmin:** [http://localhost:8082](http://localhost:8082)

### 3.3 Testkontod süsteemi testimiseks
| Roll | Kasutajanimi | Parool | Sihtkoht süsteemis |
| :--- | :--- | :--- | :--- |
| **Administraator** | `admin` | `admin123` | Administraatori juhtpaneel (`/login/admin`) |
| **Õpilane (Tavakasutaja)** | `user` | `user123` | Õpilase profiil (`/profile`) |

---

## 4. Kasutusjuhend: Õpilane ja külastaja

### 4.1 Veebilehe sirvimine ja kursuste valik
1. Ava brauseris aadress [http://localhost:8080/autokool/](http://localhost:8080/autokool/).
2. Avalehel kuvatakse autokooli põhilised eelised ning populaarseimad kursused.
3. Täieliku koolituskursuste nimekirja vaatamiseks kliki päises olevale lingile **«Kursused ja hinnad»** või nupule **«Vali kategooria»**.

### 4.2 Kursusele registreerumise taotluse esitamine
1. Vali lehel `/courses` sobiv kursus (nt *B-kategooria*, *A-kategooria* vms).
2. Kliki nupule **«Registreeru kursusele»**.
3. Ekraanile ilmub hüpikaken (modaalaken):
   - **Teie ees- ja perekonnanimi:** Sisesta oma täisnimi.
   - **E-posti aadress:** Sisesta kehtiv e-post tagasiside saamiseks.
   - **Telefoninumber:** Sisesta oma kontakttelefon.
4. Kliki nupule **«Esita avaldus»**.
5. Süsteem salvestab taotluse ja kuvab rohelise kinnitusteate:  
   *«Aitäh registreerumise eest! Võtame teiega peagi ühendust.»*

### 4.3 Kasutajakonto loomine (Registreerumine)
1. Kliki päise paremas nurgas olevale lingile **«Loo konto»**.
2. Täida registreerimisvorm:
   - Kasutajanimi (unikaalne)
   - Parool ja parooli kordus
3. Kliki nupule **«Loo konto»**. Pärast edukat registreerumist suunatakse teid otse isiklikku kabinetti.

### 4.4 Sisselogimine ja parooli turvalukustus
1. Kliki nupule **«Logi sisse»** (`/login`).
2. Sisesta oma kasutajanimi ja parool.
3. Kliki **«Logi sisse»**.

> **[TÄHTIS: BRUTE-FORCE KAITSE]**  
> Süsteem kaitseb teie kontot automaatselt:  
> - Kui sisestate **3 korda järjest vale parooli**, lukustab süsteem sisselogimise **30 sekundiks**.  
> - Ekraanile ilmub punane hoiatus loenduriga:  
>   *«Vale kasutajanimi või parool. Liiga palju katseid — konto on blokeeritud 30 sekundiks.»*  
> - Oodake 30 sekundit ja proovige uuesti õige parooliga.

### 4.5 Isiklik kabinet («Minu konto»)
1. Pärast sisselogimist suunatakse õpilane lehele **«Minu konto»** (`/profile`).
2. Siin kuvatakse teie esitatud taotlused ja nende reaalajas olekud:
   - **Ootel (Uus):** Avaldus on registreeritud ja ootab administraatori läbivaatamist.
   - **Töös:** Taotlus on töösse võetud, koostatakse lepingut või õppegraafikut.
   - **Kinnitatud:** Olete edukalt kursusele vastu võetud.
   - **Tagasi lükatud:** Taotlust ei rahuldatud (nt kohtade puudumisel).

> **[MÄRKUS MOBIILSEADMETE KASUTAJATELE]**  
> Nutitelefonis kuvatakse teie kursused selgete vertikaalsete kaartidena. Te ei pea kerima ekraani paremale ega vasakule!

---

## 5. Kasutusjuhend: Administraator

### 5.1 Juhtpaneeli sisselogimine
1. Ava aadress [http://localhost:8080/autokool/login/admin](http://localhost:8080/autokool/login/admin).
2. Sisesta administraatori andmed:
   - **Kasutajanimi:** `admin`
   - **Parool:** `admin123`
3. Kliki **«Logi sisse juhtpaneeli»**.

### 5.2 Juhtpaneeli ülevaade ja reaalajas statistika
Juhtpaneeli ülaosas kuvatakse reaalajas analüütika plokk:
- **Uued avaldused (ootel):** Läbivaatamist ootavate taotluste arv.
- **Avaldusi kokku:** Kõigi laekunud taotluste koguarv.
- **Aktiivseid kursusi:** Autokoolis pakutavate koolituste arv.
- **Kinnitatud tulu (€):** Kinnitatud staatusega avalduste rahaline kogusumma.
- **Staatuste visuaalne jaotusriba:** Värviline ülevaade (Uus / Töös / Kinnitatud / Tagasi lükatud).

### 5.3 Uue kursuse lisamine
1. Kliki juhtpaneeli päises olevale nupule **«+ Lisa uus kursus»**.
2. Avaneb kursuse lisamise vorm:
   - **Kursuse nimetus:** Nt *D-kategooria (Bussid)*
   - **Kategooria:** Nt *D*
   - **Õppe kestus:** Nt *8-10 nädalat*
   - **Hind (€):** Nt *790.00*
   - **Kursuse kirjeldus:** Sisesta koolituse lühikirjeldus.
3. Kliki **«Salvesta kursus»**. Uus koolitus ilmub koheselt nii juhtpaneeli tabelisse kui ka avalikule veebilehele.

### 5.4 Kursuse kustutamine
1. Vali juhtpaneeli jaotises «Kursuste haldamine» kursus, mida soovid eemaldada.
2. Kliki punasele nupule **«Kustuta kursus»**.
3. Kinnita toiming brauseri dialoogiaknas («Kas olete kindel...»). Kursus eemaldatakse andmebaasist.

### 5.5 Laekunud avalduste haldamine ja staatuse muutmine
1. Jaotises «Saabunud registreerimisavaldused» näeb administraator kõiki õpilaste esitatud ankeete (nimi, telefon, e-post, kursus, kuupäev).
2. Avalduse staatuse muutmiseks kliki rippmenüüle **«Muuda olekut»**:
   - Vali loetelust: `Uus`, `Töös`, `Kinnitatud` või `Tagasi lükatud`.
3. Süsteem salvestab muudatuse koheselt ning uuendab statistikat ja finantsnäitajaid.

---

## 6. Tõrkeotsing ja KKK (Troubleshooting & FAQ)

### K1: Sisestasin parooli 3 korda valesti ja süsteem ei lase sisse logida. Mida teha?
**Vastus:** See on turvafunktsioon (brute-force kaitse). Oodake ekraanile kuvatud 30 sekundit ja proovige uuesti õige parooliga (`admin` / `admin123` või `user` / `user123`).

### K2: Brauser kuvab veateadet "Konteiner ei tööta" või leht ei lae.
**Vastus:** Veendu, et Docker Desktop töötab arvutis. Käivita PowerShellis käsk:
```bash
cd D:\JPTV24\docker
docker compose up -d
```

### K3: Mobiiltelefonis ei mahu tabel ekraanile?
**Vastus:** Süsteem kasutab automaatset kaardivaadet (`.mobile-card`), mis kohandub iga nutitelefoni ekraaniga. Värskendage lehte (Refresh / `Ctrl + F5`), et laadida uusim `style.css` stiilifail.

### K4: Kuidas vaadata andmebaasi sisu otse?
**Vastus:** Ava brauseris aadress [http://localhost:8081](http://localhost:8081) (või pordil 8082), logi sisse kasutajaga `root` ja parooliga `root` ning vali andmebaas `autokool`.

---

## 7. Tehniline tugi ja hooldus

- **Arendaja:** JPTV24 Tarkvara arendusmeeskond  
- **Tugi ja tagasiside:** `admin@autokool.ee`  
- **Projektihoidla:** `https://github.com/SeDERRED/autokoolileht`  

---
*Dokument koostatud vastavalt rahvusvahelisele standardile ISO/IEC/IEEE 26514:2018.*
