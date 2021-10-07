<?php

use PHPUnit\Framework\TestCase;
use Prowebcraft\Signature;

class SignatureTest extends TestCase
{

    /**
     * Test Signature and check
     * @dataProvider sets
     */
    public function testCheckSignature(string $title, string $path, array $params, string $expectedSignature)
    {
        $sender = new Signature('TEST');
        $generatedSignature = $sender->sign($path, $params);
        self::assertEquals($expectedSignature, $generatedSignature, $title);
        $receiver = new Signature('TEST');
        $receiverParams = json_decode(json_encode($params), true);
        self::assertTrue($receiver->check($expectedSignature, $path, $receiverParams));
    }

    /**
     * Test Salt integrity
     */
    public function testSalt()
    {
        $sender = new Signature('TEST');
        $path = '/test';
        $params = [];
        $senderSignature = $sender->sign($path, $params);
        $receiver = new Signature('BROKEN_SALT');
        self::assertFalse($receiver->check($senderSignature, $path, $params));
    }

    /**
     * Dataset for test
     * @return array[]
     */
    public function sets(): array
    {
        return [
            [ 'simple string param (no path)', '', [ 'simple' => 'hello' ], '381879132F73837C5A23FA908D42A8AE332E730B'],
            [ 'simple string param (with path)', '/test/path', [ 'simple' => 'hello' ], '875258845E685F9EA5B0559042EDF8326B71B365'],
            [ 'complex array', '/test/path', [
                'simple' => 'hello',
                'nested_array' => [
                    'hello' => [
                        'world'
                    ]
                ],
                'a' => null,
                'b' => false,
                0 => true
            ], '69CA087C84A299DD04B85769A026B6F9F54DB7EF'],
        ];
    }
}
