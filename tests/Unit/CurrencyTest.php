<?php

namespace Tests\Unit;

use App\Helpers\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function test_format_receives_reais(): void
    {
        $this->assertSame('R$ 140,41', Currency::format('140.41'));
        $this->assertSame('R$ 1.196,52', Currency::format(1196.52));
        $this->assertSame('R$ 50,00', Currency::format(50));
    }

    public function test_to_centavos_parses_brazilian_format(): void
    {
        $this->assertSame(119652, Currency::toCentavos('1.196,52'));
        $this->assertSame(-19300, Currency::toCentavos('-193,00'));
    }
}
