<?php
return [
    // Fiscalía (listas editables)
    'fiscalias_nombres' => [
        'FISCALÍA PROVINCIAL',
        'FISCALÍA MULTICOMPETENTE',
        'FISCALÍA ESPECIALIZADA EN PERSONAS',
        'FISCALÍA FLAGRANCIA',
        'OTRA',
    ],
    'fiscalias_numeros' => ['01','02','03','04','05','OTRO'],

    // Fiscales delegados (muestra algunos + OTRO)
    'fiscales_delegados' => [
        'ABG. INTRIAGO MARTINEZ WILMER JEAN CARLOS',
        'ABG. JUAN PÉREZ',
        'ABG. MARÍA LÓPEZ',
        'OTRO',
    ],

    // Tipo penal (formulación)
    'tipos_penales' => [
        'ASESINATO','HOMICIDIO','FEMICIDIO','LESIONES',
        'ROBO','TENENCIA ILICITA','OTRO',
    ],

    // Medidas cautelares + detalle (catálogo simple)
    'medidas_cautelares' => [
        'PRISIÓN PREVENTIVA','PRESENTACIÓN PERIÓDICA','PROHIBICIÓN DE SALIDA',
        'DISPOSITIVO ELECTRÓNICO','ARRESTO DOMICILIARIO','OTRA',
    ],
    'detalles_medidas' => [
        'MEDIDAS CAUTELARES','SIN MEDIDAS','OTRO',
    ],

    // Vinculación
    'vinculacion' => ['SI','NO'],

    // Situación jurídica
    'situaciones_juridicas' => [
        'INVESTIGACIÓN',
        'EVALUACIÓN Y PREPARATORIA DE JUICIO',
        'ETAPA DE JUICIO',
        'SOBRESEIMIENTO',
        'SENTENCIA',
        'ARCHIVO',
        'OTRA',
    ],

    // Requerimientos
    'requerimientos' => [
        'PERICIA TELEFÓNICA','PERICIA OPERADORAS','PERICIA BALÍSTICA',
        'INFORME CRIMINALÍSTICA','OFICIO FISCALÍA','CADENA DE CUSTODIA',
        'OTRO',
    ],

    // Escenas (si ya usas estas en otro módulo, mantén)
    'escenas' => ['DOMICILIO','VÍA PÚBLICA','COMERCIO','BALDÍO','VEHÍCULO','RÍO/CANAL','ÁREA RURAL','OTRA'],
];
