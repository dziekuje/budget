<?php

namespace Tests\Unit\Repositories;

use App\Models\ConversionRate;
use App\Models\Currency;
use App\Models\Space;
use App\Repositories\CurrencyRepository;
use Tests\TestCase;

class CurrencyRepositoryTest extends TestCase
{
    private $currencyRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->currencyRepository = new CurrencyRepository();
    }

    public function testGetIfConversionRatePresentMethod(): void
    {
        // Prepare
        $firstCurrency = factory(Currency::class)->create();
        $secondCurrency = factory(Currency::class)->create();

        $firstSpace = factory(Space::class)->create(['currency_id' => $firstCurrency->id]);
        $secondSpace = factory(Space::class)->create(['currency_id' => $secondCurrency->id]);

        // Select first currency, assuming there are no conversion rates yet, the method should return 1 currency
        $this->session(['space_id' => $firstSpace->id]);

        $this->assertCount(1, $this->currencyRepository->getIfConversionRatePresent());

        // Create conversion rate, the method should return 2 currencies
        factory(ConversionRate::class)->create([
            'base_currency_id' => $secondCurrency,
            'target_currency_id' => $firstCurrency
        ]);

        $this->assertCount(2, $this->currencyRepository->getIfConversionRatePresent());

        // Switch to the other currency, the method should return 1 currency
        $this->session(['space_id' => $secondSpace->id]);

        $this->assertCount(1, $this->currencyRepository->getIfConversionRatePresent());
    }
}
