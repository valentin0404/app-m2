import os
import sys

ruta = os.path.dirname(__file__)

fitxerDadesAlumnes = "UF3C1-2024-dadesAlumnes.csv"
rutaDadesAlumnes = os.path.join(ruta,fitxerDadesAlumnes)

fitxerNotesAlumnes = "UF3C1-2024-notes.csv"
rutaNotesAlumnes = os.path.join(ruta,fitxerNotesAlumnes)

fitxerLog = "UF3C1-2024-errors"
rutafitxerLog = os.path.join(ruta,fitxerLog)
logW = open(rutaDadesAlumnes, 'r')
grupAlumnes = {
    # 1ASIX : [2,4,7,9,....]
    # 1DAW : [1,3,5,6,....]
}

llistaAlumnes = {
    # 1: Juan Martinez,
    # 2: Ana Gonzalez,
    # ...
}
notesAlumnes = {
    # 1 : {M1 : 9.75, M2 : 6.50},
    # 2 : {M1 : 8.00, M2 : 6.75 , M3 : 7.50}
    # ...
}

grupos = ["1ASIX", "1DAW"]

for grup in grupos:
    grupAlumnes[grup] = []



dadesR = open(rutaDadesAlumnes, 'r')
for linia_dades in dadesR:
    id, nom, cognom, grup = linia_dades.strip().split(',')
    if grup in grupAlumnes:
        grupAlumnes[grup].append(id)
    llistaAlumnes[id]=f"{nom} {cognom}"
dadesR.close()

notesR = open(rutaNotesAlumnes, 'r')
for linia_notes in notesR:
    id, modulo, nota = linia_notes.strip().split(',')
    if id not in notesAlumnes:
        notesAlumnes[id] = {}
    notesAlumnes[id][modulo] = nota
notesR.close()

opcions="1. Mostrar els alumnes d’un grup\n2. Mostrar les notes d’un alumne\n3. Sortir"


if len(sys.argv) == 2:
    nom_grup=sys.argv[1]
    if nom_grup in grupos:
        for num in grupAlumnes[nom_grup]:
            if llistaAlumnes[num]:
                print(f"- {llistaAlumnes[num]}")
    else:
        print("No has introduït un grup vàlid.")
else:
    try:
        status=True
        while status:
            print()
            print(opcions)
            opcio = int(input("\nEscull l'opció desitjada: "))
            if opcio == 1:
                print("\nTens disponibles aquests grups: ")
                for grup in grupos:
                    print(grup)
                nom_grup=input("\nIntrodueix el nom del grup a mostrar: ")
                for num in grupAlumnes[nom_grup]:
                    if llistaAlumnes[num]:
                        print(f"- {llistaAlumnes[num]}")
            elif opcio == 2:
                for id, nom in llistaAlumnes.items():
                    print(f"{id}. {nom}")
                id_alumne = input("Introdueix el numero del alumne a mostrar: ")
                if id_alumne in llistaAlumnes:
                    print(f"L'alumne {llistaAlumnes[id_alumne]} té les següents notes: ")
                    for modulo, nota in notesAlumnes[id_alumne].items():
                        print(f"{modulo}: {nota}")
            elif opcio == 3:
                status=False
                print("\nSortint...")
            else:
                print("\nOpció incorrecta, escull un número vàlid.")
    except ValueError:
        print("\nS'ha d'introduïr un valor númeric.")
    except:
        print("\nError desconegut")
    finally:
        input("\nPulsa enter para continuar.")