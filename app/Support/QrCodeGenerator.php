<?php

namespace App\Support;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * QrCodeGenerator — wraps endroid/qr-code so our controllers / views don't
 * need to know the vendor API. Produces a base64-encoded data URI that
 * embeds safely in a DomPDF-rendered Blade template.
 */
class QrCodeGenerator
{
    public static function dataUri(string $content, int $size = 180): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($content)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->size($size)
            ->margin(8)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        return $result->getDataUri();
    }
}
