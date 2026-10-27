
<?php

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

class MissoesTest extends TestCase
{
    public function testListarMissoes(): void
    {
        $app = require __DIR__ . '/../src/app.php';

        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/missoes');

        $response = $app->handle($request);

        $this->assertSame(200, $response->getStatusCode());

        $dados = json_decode(
            (string) $response->getBody(),
            true
        );

        $this->assertIsArray($dados);
        $this->assertNotEmpty($dados);
        $this->assertSame('Apollo 11', $dados[0]['nome']);
    }
}
