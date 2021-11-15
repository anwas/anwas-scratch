# Anwas Scratch tema
Anwas Scratch – tema kūrimui nuo pradžios. Prisilaikoma OOP programavimo principų, o CSS klasės maksimaliai stengiamasi išlaikyti BEM principus.

**Svarbu**

Tema nepritaikyta naudojimui kaip tėvinė, todėl keiskite šią temą, o ne kurkite iš jos dukterines temas.

## Temos pavadinimo keitimas naudojant Linux komandinę eilutę

**LABAI SVARBU**: šios komandos pateiktos pažintiniais tikslais. Autorius neprisiima **JOKIOS atsakomybės** dėl sugadintų ar prarastų duomenų. Jei nežinote ką daro šios komandos ar nesate tikri dėl savo atliekamų veiksmų, geriau nenaudokite šių komandų. **Visada naudinga turėti svarbių duomenų atsargines kopijas.**

Tarkime, kad norite pavadinti temą „Nauja Tema“, komandinėje eilute pereikite į temos aplanką ir suveskite šias komandas (*didžiosios ir mažiosios raidės bei komandų eiliškumas yra **svarbu***).

1.
```
find ./ -type f -exec sed -i 's/Anwas_Scratch_/Nauja_Tema/g' {} \;
```
2.
```
find ./ -type f -exec sed -i 's/anwas_scratch/nauja_tema/g' {} \;
```
3.
```
find ./ -type f -exec sed -i 's/anwas-scratch/nauja-tema/g' {} \;
```
4.
```
find ./ -type f -exec sed -i 's/Anwas Scratch/Nauja tema/g' {} \;
```

5. *Nebūtina komanda (šią komandą galima vykdyti ir bet kada vėliau, kai keičiama temos versija)*. Jei norite pakeisti temos versijos numerį visuose failuose pavyzdžiui iš 1.0.0 į 1.1.0, įveskite šią **ARBA** sekančią komandą:

```
find ./ -type f -exec sed -i -E '/(@since)/!s/1.0.0/1.1.0/g' {} \;
```

Ankstesnė komanda pakeis visas versijas tose eilutėse, kurios **neturi** teksto „**@since 1.0.0**“. Norint pakeitimo metu pakeisti visas eilutes, net ir tas, kurios turi žodį „**@since**“, galima naudoti sekančią komandą vietoj aukščiau esančios:

```
find ./ -type f -exec sed -i 's/1.0.0/1.1.0/g' {} \;
```

6. Dar gali reikti pakeisti failų ir aplankų pavadinimus (visuose pavadinimuose teksto dalį „anwas-scratch“ pakeisti į „nauja-tema“) – komanda įvedama vis dar būnant komandinėje eilutėje temos aplanke.

- Pakeisti visų **failų** pavadinimus, kurie prasideda „anwas-scratch“ į failų pavadinimus su pradžia „nauja-tema“:

```
find . -name "anwas-scratch*" -type f -print0 | xargs -0 -I {} sh -c 'mv "{}" "$(dirname "{}")/`echo $(basename "{}") | sed 's/^anwas-scratch/nauja-tema/g'`"'
```

- Pakeisti visų **failų** pavadinimus, kurie prasideda „anwas_scratch“ į failų pavadinimus su pradžia „nauja_tema“:

```
find . -name "anwas_scratch*" -type f -print0 | xargs -0 -I {} sh -c 'mv "{}" "$(dirname "{}")/`echo $(basename "{}") | sed 's/^anwas_scratch/nauja_tema/g'`"'
```

- Analogiškai ir **aplankams**, kurie prasideda „anwas-scratch“ į aplankų pavadinimus su pradžia „nauja-tema“ (pakeistas -type iš „f“ į „d“):

```
find . -name "anwas-scratch*" -type d -print0 | xargs -0 -I {} sh -c 'mv "{}" "$(dirname "{}")/`echo $(basename "{}") | sed 's/^anwas-scratch/nauja-tema/g'`"'
```

- Pakeisti visų **aplankų** pavadinimus, kurie prasideda „anwas_scratch“ į aplankų pavadinimus su pradžia „nauja_tema“:

```
find . -name "anwas_scratch*" -type d -print0 | xargs -0 -I {} sh -c 'mv "{}" "$(dirname "{}")/`echo $(basename "{}") | sed 's/^anwas_scratch/nauja_tema/g'`"'
```

Ir po šio veiksmo belieka rankiniu būdu pakeisti pagrindinio temos aplanko pavadinimą iš „anwas-scratch“ į „nauja-tema“ ir style.css faile Autoriaus ir Temos nuorodas pakeisti į norimas.

## Composer autoload naudojimas

**LABAI SVARBU**: temoje naudojamas Composer PSR-4 klasių automatinis įtraukimas (autoload). Todėl, pirmą kartą prieš pradedant dirbti su tema (**po to, kai jau visi tekstai ir failai pervardinti**), reikia įvykdyti sekančią komandą:

```
composer install
```
