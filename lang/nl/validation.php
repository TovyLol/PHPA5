<?php

// validatie en andere messages in het nederlands
return [
    'accepted' => ':Attribute moet geaccepteerd worden.',
    'active_url' => ':Attribute is geen geldige URL.',
    'after' => ':Attribute moet een datum na :date zijn.',
    'alpha' => ':Attribute mag alleen letters bevatten.',
    'alpha_dash' => ':Attribute mag alleen letters, cijfers, - en _ bevatten.',
    'alpha_num' => ':Attribute mag alleen letters en cijfers bevatten.',
    'array' => ':Attribute moet een lijst zijn.',
    'before' => ':Attribute moet een datum voor :date zijn.',
    'between' => [
        'array' => ':Attribute moet tussen :min en :max items bevatten.',
        'file' => ':Attribute moet tussen :min en :max kilobytes zijn.',
        'numeric' => ':Attribute moet tussen :min en :max liggen.',
        'string' => ':Attribute moet tussen :min en :max tekens zijn.',
    ],
    'boolean' => ':Attribute moet waar of onwaar zijn.',
    'confirmed' => 'De twee wachtwoorden zijn niet hetzelfde.',
    'current_password' => 'Het wachtwoord is onjuist.',
    'date' => ':Attribute is geen geldige datum.',
    'different' => ':Attribute en :other moeten verschillend zijn.',
    'email' => 'Dit is geen geldig e-mailadres.',
    'exists' => 'De gekozen :attribute bestaat niet.',
    'file' => ':Attribute moet een bestand zijn.',
    'image' => ':Attribute moet een afbeelding zijn.',
    'in' => 'De gekozen :attribute is ongeldig.',
    'integer' => ':Attribute moet een geheel getal zijn.',
    'max' => [
        'array' => ':Attribute mag niet meer dan :max items bevatten.',
        'file' => ':Attribute mag niet groter zijn dan :max kilobytes.',
        'numeric' => ':Attribute mag niet groter zijn dan :max.',
        'string' => ':Attribute mag maximaal :max tekens zijn.',
    ],
    'mimes' => ':Attribute moet een bestand zijn van het type: :values.',
    'min' => [
        'array' => ':Attribute moet minimaal :min items bevatten.',
        'file' => ':Attribute moet minimaal :min kilobytes zijn.',
        'numeric' => ':Attribute moet minimaal :min zijn.',
        'string' => ':Attribute moet minimaal :min tekens zijn.',
    ],
    'numeric' => ':Attribute moet een getal zijn.',
    'regex' => 'Het formaat van :attribute is ongeldig.',
    'required' => 'Vul :attribute in.',
    'same' => ':Attribute en :other moeten hetzelfde zijn.',
    'string' => ':Attribute moet tekst zijn.',
    'unique' => 'Dit :attribute is al in gebruik.',
    'uploaded' => 'Het uploaden van :attribute is mislukt.',
    'url' => ':Attribute is geen geldige URL.',

    // persoonlijkere teksten voor de inlog- en registratievelden
    'custom' => [
        'name' => ['required' => 'Vul je naam in.'],
        'email' => ['required' => 'Vul je e-mailadres in.'],
        'password' => [
            'required' => 'Vul je wachtwoord in.',
            'min' => 'Je wachtwoord moet minimaal :min tekens zijn.',
        ],
    ],

    // nette namen voor de velden in de meldingen hierboven
    'attributes' => [
        'name' => 'naam',
        'email' => 'e-mailadres',
        'password' => 'wachtwoord',
        'password_confirmation' => 'wachtwoordbevestiging',
        'current_password' => 'huidige wachtwoord',
    ],
];
