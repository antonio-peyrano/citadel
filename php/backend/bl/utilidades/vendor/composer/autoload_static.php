<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite18037f44c4d40c765732afacd404d38
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Spipu\\Html2Pdf\\' => 15,
        ),
        'C' => 
        array (
            'Com\\Tecnick\\Unicode\\Data\\' => 25,
            'Com\\Tecnick\\Unicode\\' => 20,
            'Com\\Tecnick\\Pdf\\Page\\' => 21,
            'Com\\Tecnick\\Pdf\\Image\\' => 22,
            'Com\\Tecnick\\Pdf\\Graph\\' => 22,
            'Com\\Tecnick\\Pdf\\Font\\' => 21,
            'Com\\Tecnick\\Pdf\\Encrypt\\' => 24,
            'Com\\Tecnick\\Pdf\\' => 16,
            'Com\\Tecnick\\File\\' => 17,
            'Com\\Tecnick\\Color\\' => 18,
            'Com\\Tecnick\\Barcode\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Spipu\\Html2Pdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/spipu/html2pdf/src',
        ),
        'Com\\Tecnick\\Unicode\\Data\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-unicode-data/src',
        ),
        'Com\\Tecnick\\Unicode\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-unicode/src',
        ),
        'Com\\Tecnick\\Pdf\\Page\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-pdf-page/src',
        ),
        'Com\\Tecnick\\Pdf\\Image\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-pdf-image/src',
        ),
        'Com\\Tecnick\\Pdf\\Graph\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-pdf-graph/src',
        ),
        'Com\\Tecnick\\Pdf\\Font\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-pdf-font/src',
        ),
        'Com\\Tecnick\\Pdf\\Encrypt\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-pdf-encrypt/src',
        ),
        'Com\\Tecnick\\Pdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-pdf/src',
        ),
        'Com\\Tecnick\\File\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-file/src',
        ),
        'Com\\Tecnick\\Color\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-color/src',
        ),
        'Com\\Tecnick\\Barcode\\' => 
        array (
            0 => __DIR__ . '/..' . '/tecnickcom/tc-lib-barcode/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'TCPDF' => 
            array (
                0 => __DIR__ . '/../..' . '/src',
            ),
        ),
    );

    public static $classMap = array (
        'Datamatrix' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/datamatrix.php',
        'PDF417' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/pdf417.php',
        'QRcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/barcodes/qrcode.php',
        'TCPDF' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf.php',
        'TCPDF2DBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_2d.php',
        'TCPDFBarcode' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_barcodes_1d.php',
        'TCPDF_COLORS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_colors.php',
        'TCPDF_FILTERS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_filters.php',
        'TCPDF_FONTS' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_fonts.php',
        'TCPDF_FONT_DATA' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_font_data.php',
        'TCPDF_IMAGES' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_images.php',
        'TCPDF_IMPORT' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_import.php',
        'TCPDF_PARSER' => __DIR__ . '/..' . '/tecnickcom/tcpdf/tcpdf_parser.php',
        'TCPDF_STATIC' => __DIR__ . '/..' . '/tecnickcom/tcpdf/include/tcpdf_static.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite18037f44c4d40c765732afacd404d38::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite18037f44c4d40c765732afacd404d38::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInite18037f44c4d40c765732afacd404d38::$prefixesPsr0;
            $loader->classMap = ComposerStaticInite18037f44c4d40c765732afacd404d38::$classMap;

        }, null, ClassLoader::class);
    }
}