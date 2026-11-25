<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'email' => 'Informe um endereço de e-mail válido.',
    'image' => 'O arquivo enviado deve ser uma imagem.',
    'mimes' => 'O arquivo :attribute deve ser do tipo: :values.',
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file' => 'O arquivo :attribute não pode ser maior que :max kilobytes.',
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
        'array' => 'O campo :attribute não pode ter mais de :max itens.',
    ],
    'min' => [
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
        'file' => 'O arquivo :attribute deve ter no mínimo :min kilobytes.',
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
        'array' => 'O campo :attribute deve ter no mínimo :min itens.',
    ],
    'numeric' => 'O campo :attribute deve ser um número.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'date' => 'O campo :attribute deve ser uma data válida.',
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'unique' => 'Este :attribute já está cadastrado.',
    'confirmed' => 'A confirmação do campo :attribute não corresponde.',
    'current_password' => 'A senha informada está incorreta.',
    'dimensions' => 'As dimensões da imagem enviada são inválidas.',
    'exists' => 'A opção selecionada para :attribute é inválida.',
    'in' => 'A opção selecionada para :attribute é inválida.',
    'uploaded' => 'Não foi possível enviar o arquivo :attribute.',
    'attributes' => [
        'name' => 'nome', 'email' => 'e-mail', 'password' => 'senha',
        'profile_photo' => 'foto de perfil', 'remove_profile_photo' => 'remover foto de perfil',
        'nome' => 'nome', 'propriedade_id' => 'propriedade', 'talhao_id' => 'talhão',
        'produto_id' => 'produto', 'recurso_id' => 'recurso', 'data_prevista' => 'data prevista',
        'hora_prevista' => 'hora prevista', 'latitude' => 'latitude', 'longitude' => 'longitude',
    ],
];
