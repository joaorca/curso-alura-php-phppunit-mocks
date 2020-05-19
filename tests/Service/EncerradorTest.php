<?php

namespace Alura\Leilao\Tests\Enerrador;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use PHPUnit\Framework\TestCase;

class EncerradorTest extends TestCase
{
    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $fiat147 = new Leilao(
            'Fiat 147 0Km',
            new \DateTimeImmutable('8 days ago')
        );

        $variant = new Leilao(
            'Variant 1972 0Km',
            new \DateTimeImmutable('10 days ago')
        );

        $leilaoDao = $this->createMock(LeilaoDao::class);
        $leilaoDao->method('recuperarNaoFinalizados')
            ->willReturn([$fiat147, $variant]);
        $leilaoDao->method('recuperarFinalizados')
            ->willReturn([$fiat147, $variant]);

        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        $leiloes = [$fiat147, $variant];

        $this->assertTrue($leiloes[0]->estaFinalizado());
        $this->assertTrue($leiloes[1]->estaFinalizado());
        $this->assertEquals('Fiat 147 0Km', $leiloes[0]->recuperarDescricao());
        $this->assertEquals('Variant 1972 0Km', $leiloes[1]->recuperarDescricao());
    }

}