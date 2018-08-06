<?php
declare(strict_types=1);

namespace App\Service\Rest\Decoder;

class JsonDecoder implements DecoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function decode($data)
    {
        return @json_decode($data, true);
    }
}
