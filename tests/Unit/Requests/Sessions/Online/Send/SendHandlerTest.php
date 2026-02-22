<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Requests\Sessions\Online\Send\SendRequest;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaDaneNabywcyFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaPozaKsefFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaKorygujacaUniwersalnaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaRR\FakturaSprzedazyTowaruRolniczegoFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFpTpFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruWithFloatsFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyUslugLeasinguOperacyjnegoFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaUproszczonaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaVatMarzaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaWWalucieObcejFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZaliczkowaZDodatkowymNabywcaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZVatUEFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZwolnienieVatFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZalacznikiemFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaZZaplataCzesciowaFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Send\SendRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Send\SendResponseFixture;
use N1ebieski\KSEFClient\Tests\Unit\AbstractTestCase;
use N1ebieski\KSEFClient\Validator\Rules\Xml\SchemaRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\FormCode;
use N1ebieski\KSEFClient\ValueObjects\SchemaPath;

/** @var AbstractTestCase $this */

/**
 * @return array<string, array{SendRequestFixture, SendResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $requests = [
        (new SendRequestFixture())->withFakturaFixture(new FakturaSprzedazyTowaruFixture())->withName('faktura sprzedaży towaru'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaSprzedazyTowaruFpTpFixture())->withName('faktura sprzedaży towaru FP TP'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaSprzedazyTowaruWithFloatsFixture())->withName('faktura sprzedaży towaru z floatami'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaKorygujacaDaneNabywcyFixture())->withName('faktura korygująca dane nabywcy'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaKorygujacaUniwersalnaFixture())->withName('faktura korygująca uniwersalna'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaSprzedazyUslugLeasinguOperacyjnegoFixture())->withName('faktura sprzedaży usług leasingu operacyjnego'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaZaliczkowaZDodatkowymNabywcaFixture())->withName('faktura zaliczkowa z dodatkowym nabywcą'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaUproszczonaFixture())->withName('faktura uproszczona'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaVatMarzaFixture())->withName('faktura VAT marża'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaWWalucieObcejFixture())->withName('faktura w walucie obcej'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaZZalacznikiemFixture())->withName('faktura z załącznikiem'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaZVatUEFixture())->withName('faktura z VAT UE'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaZZaplataCzesciowaFixture())->withName('faktura z zapłatą częściową'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaZwolnienieVatFixture())->withName('faktura zwolnięcie VAT'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaKorygujacaPozaKsefFixture())->withName('faktura korygująca poza KSEF'),
        (new SendRequestFixture())->withFakturaFixture(new FakturaSprzedazyTowaruRolniczegoFixture())->withFormCode(FormCode::FaRr1)->withName('faktura sprzedaży towaru rolniczego'),
    ];

    $responses = [
        new SendResponseFixture(),
    ];

    $combinations = [];

    foreach ($requests as $request) {
        foreach ($responses as $response) {
            $combinations["{$request->name}, {$response->name}"] = [$request, $response];
        }
    }

    /** @var array<string, array{SendRequestFixture, SendResponseFixture}> */
    return $combinations;
});

test('valid response', function (SendRequestFixture $requestFixture, SendResponseFixture $responseFixture): void {
    /** @var AbstractTestCase $this */
    $clientStub = $this->createClientStub($responseFixture);

    $request = SendRequest::from($requestFixture->data);

    Validator::validate($request->toXml(), [
        new SchemaRule(SchemaPath::from($request->formCode->getSchemaPath()))
    ]);

    expect($request)->toBeFixture($requestFixture->data);

    $response = $clientStub->sessions()->online()->send($requestFixture->data)->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider')->only();

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        /** @var AbstractTestCase $this */
        /** @var SendRequestFixture $requestFixture */
        $requestFixture = (new SendRequestFixture())
            ->withFakturaFixture(new FakturaSprzedazyTowaruFixture())->withName('faktura sprzedaży towaru');

        $clientStub = $this->createClientStub($responseFixture);

        $clientStub->sessions()->online()->send($requestFixture->data);
    })->toBeExceptionFixture($responseFixture->data);
});
