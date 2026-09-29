<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// ==========================================
// DADOS INICIAIS
// ==========================================

$missoes = [
    [
        'id' => 1,
        'nome' => 'Apollo 11',
        'ano' => 1969,
        'agencia' => 'NASA',
        'status' => 'Concluída'
    ],
    [
        'id' => 2,
        'nome' => 'Voyager 1',
        'ano' => 1977,
        'agencia' => 'NASA',
        'status' => 'Em operação'
    ],
    [
        'id' => 3,
        'nome' => 'Artemis II',
        'ano' => 2026,
        'agencia' => 'NASA',
        'status' => 'Planejada'
    ],
    [
        'id' => 4,
        'nome' => 'Mars 2020',
        'ano' => 2020,
        'agencia' => 'NASA',
        'status' => 'Em operação'
    ],
    [
        'id' => 5,
        'nome' => 'Sputnik 1',
        'ano' => 1957,
        'agencia' => 'URSS',
        'status' => 'Concluída'
    ]
];

// ==========================================
// GET /status
// ==========================================

$app->get('/status', function ($request, $response) {

    $response->getBody()->write(
        json_encode(['status' => 'ok'])
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// ==========================================
// GET /missoes
// Listar todas as missões
// ==========================================

$app->get('/missoes', function ($request, $response) use (&$missoes) {

    $response->getBody()->write(
        json_encode($missoes)
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

// ==========================================
// GET /missoes/{id}
// Buscar missão por ID
// ==========================================

$app->get('/missoes/{id}', function ($request, $response, $args) use (&$missoes) {

    $id = (int) $args['id'];

    foreach ($missoes as $missao) {

        if ($missao['id'] === $id) {

            $response->getBody()->write(
                json_encode($missao)
            );

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }
    }

    $response->getBody()->write(
        json_encode(['erro' => 'Missão não encontrada'])
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
});

// ==========================================
// POST /missoes
// Cadastrar nova missão
// ==========================================

$app->post('/missoes', function ($request, $response) use (&$missoes) {

    $dados = $request->getParsedBody();

    $novoId = count($missoes) + 1;

    $novaMissao = [
        'id' => $novoId,
        'nome' => $dados['nome'],
        'ano' => $dados['ano'],
        'agencia' => $dados['agencia'],
        'status' => $dados['status']
    ];

    $missoes[] = $novaMissao;

    $response->getBody()->write(
        json_encode($novaMissao)
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(201);
});

// ==========================================
// PUT /missoes/{id}
// Atualizar missão
// ==========================================

$app->put('/missoes/{id}', function ($request, $response, $args) use (&$missoes) {

    $id = (int) $args['id'];

    $dados = $request->getParsedBody();

    foreach ($missoes as &$missao) {

        if ($missao['id'] === $id) {

            $missao['nome'] = $dados['nome'];
            $missao['ano'] = $dados['ano'];
            $missao['agencia'] = $dados['agencia'];
            $missao['status'] = $dados['status'];

            $response->getBody()->write(
                json_encode($missao)
            );

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        }
    }

    $response->getBody()->write(
        json_encode(['erro' => 'Missão não encontrada'])
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
});

// ==========================================
// DELETE /missoes/{id}
// Remover missão
// ==========================================

$app->delete('/missoes/{id}', function ($request, $response, $args) use (&$missoes) {

    $id = (int) $args['id'];

    foreach ($missoes as $indice => $missao) {

        if ($missao['id'] === $id) {

            unset($missoes[$indice]);

            return $response->withStatus(204);
        }
    }

    $response->getBody()->write(
        json_encode(['erro' => 'Missão não encontrada'])
    );

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
});

// ==========================================
// INICIAR API
// ==========================================

$app->run();