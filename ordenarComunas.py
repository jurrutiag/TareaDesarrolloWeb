# -*- coding: utf-8 -*-

import pandas as pd
import json

df = pd.read_csv('cut_2010_v02_0.csv', encoding='latin1')

regiones = {}
for i, region in enumerate(df['Nombre Región']):
    if region not in regiones.keys():
        regiones[region] = []
    
    regiones[region].append(list(df['Nombre Comuna'])[i])

print(regiones)