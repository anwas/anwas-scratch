# Anwas Scratch tema
Anwas Scratch – tema kūrimui nuo pradžios. Prisilaikoma OOP programavimo principų, o CSS klasės maksimaliai stengiamasi išlaikyti BEM principus.

**Svarbu**

Tema nepritaikyta naudojimui kaip tėvinė, todėl keiskite šią temą, o ne kurkite iš jos dukterines temas.

## Temos pavadinimo keitimas naudojant Linux komandinę eilutę

**LABAI SVARBU**: šios komandos pateiktos pažintiniais tikslais. Autorius neprisiima **JOKIOS atsakomybės** dėl sugadintų ar prarastų duomenų. Jei nežinote ką daro šios komandos ar nesate tikri dėl savo atliekamų veiksmų, geriau nenaudokite šių komandų. **Visada naudinga turėti svarbių duomenų atsargines kopijas.**

Tarkime, kad norite pavadinti temą „Nauja Tema“, komandinėje eilute pereikite į temos aplanką ir suveskite šias komandas (*didžiosios ir mažiosios raidės bei komandų eiliškumas yra **svarbu***).

1.
```
find ./ -type f -exec sed -i 's/Anwas_Scratch_/Nauja_Tema_/g' {} \;
```
2.
```
find ./ -type f -exec sed -i 's/anwas_scratch/nauja_tema/g' {} \;
```
3.
```
find ./ -type f -exec sed -i 's/Anwas_Scratch/Nauja_Tema/g' {} \;
```
4.
```
find ./ -type f -exec sed -i 's/anwas-scratch/nauja-tema/g' {} \;
```
5.
```
find ./ -type f -exec sed -i 's/Anwas Scratch/Nauja tema/g' {} \;
```

Jei norite pakeisti temos versijos numerį visuose failuose pavyzdžiui iš 1.0.0 į 1.1.0, įveskite šią komandą:

```
find ./ -type f -exec sed -i 's/1.0.0/1.1.0/g' {} \;
```

Ankstesnė komanda pakeis visas versijas ir tose eilutėse, kurios turi tekstą „**@since 1.0.0**“, o tai nėra logiška. Todėl, norint pakeitimo metu praleisti visas eilutes, kurios turi žodį „**@since**“, galima naudoti sekančią komandą vietoj aukščiau esančios:

```
find ./ -type f -exec sed -i -E '/(@since)/!s/1.0.0/1.1.0/g' {} \;
```

Dar gali reikti pakeisti failų ir aplankų pavadinimus (visuose pavadinimuose teksto dalį „anwas-scratch“ pakeisti į „nauja-tema“) – komanda įvedama vis dar būnant komandinėje eilutėje temos aplanke:

```
find . -name "anwas-scratch*" -type f -print0 | xargs -0 -I {} sh -c 'mv "{}" "$(dirname "{}")/`echo $(basename "{}") | sed 's/^anwas-scratch/nauja-tema/g'`"'
```

Ir po šio veiksmo belieka rankiniu būdu pakeisti pagrindinio temos aplanko pavadinimą iš „anwas-scratch“ į „nauja-tema“ ir style.css faile Autoriaus ir Temos nuorodas pakeisti į norimas.
