
# Symfony projekt

![Symfony](/public/img/code.jpg)

## Beskrivning

Detta är mitt Symfony-projekt som jag utvecklar för kursen MVC i BTH, detta repo innehåller flera olika projekt.
Bland annat så innehåller den ett 21 spelet där man kan spela mot datorn. Repot innehåller även ett påhittat biblotetk där användaren kan 
skapa olika böcker och ladda upp information och bilder om själva boken. Det finns även API motsvarigheter till sidan som är strukturear i json format så
att man kan även se vad som sker i backenden. Slutligen så innehåller repot också ett slutprojekt, denna slutprojekt har en egen sida där man kan kontrollera vilka märkes reservdelar för bilar håller bra kvalitet och informationen är samlad på ett och samma ställe. Även olika kategorier finns där 
allting är fint strukturerat så att användaren är informerad om vilka reservdelar som är bäst beroende på vilket märke man väljer, datan är sparad i en databas och ORM används för att rendera innehållet i templaten.

## Hur man kommer igång

För att börja använda och bidra till projektet, följ stegen nedan:

## Scrutinizer badges

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rebahama/bth-mvc-1/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/rebahama/bth-mvc-1/?branch=main)

[![Code Coverage](https://scrutinizer-ci.com/g/rebahama/bth-mvc-1/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/rebahama/bth-mvc-1/?branch=main)


[![Build Status](https://scrutinizer-ci.com/g/rebahama/bth-mvc-1/badges/build.png?b=main)](https://scrutinizer-ci.com/g/rebahama/bth-mvc-1/build-status/main)

### Kloning av repository

Använd `git clone` för att klona det här projektet till din lokala maskin:

```bash
git clone https://github.com/rebahama/bth-mvc-1

cd projektnamn/app

composer install

För att starta projeket på lokala maskinen skriv in:
php -S localhost:8888 -t public
