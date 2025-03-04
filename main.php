<?php

//Add your code here 

add_filter('rank_math/snippet/rich_snippet_product_entity', function($entity) {
    // فقط توی صفحات تک‌محصول اجرا بشه
    if (!is_product()) {
        return $entity;
    }

    global $product;

    // چک کردن وجود محصول
    if (!$product || !is_a($product, 'WC_Product')) {
        return $entity;
    }

    // تنظیم پیش‌فرض برای offers اگه وجود نداشته باشه
    if (!isset($entity['offers'])) {
        $entity['offers'] = [];
    }

    if ($product->is_downloadable()) {
        // محصولات دانلودی
        $entity['offers']['shippingDetails'] = [
            '@type' => 'OfferShippingDetails',
            'shippingRate' => [
                '@type' => 'MonetaryAmount',
                'value' => 0,
                'currency' => 'IRR'
            ],
            'shippingDestination' => [
                '@type' => 'DefinedRegion',
                'addressCountry' => 'IR'
            ],
            'deliveryTime' => [
                '@type' => 'ShippingDeliveryTime',
                'handlingTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 0,
                    'maxValue' => 0,
                    'unitCode' => 'DAY' // تحویل فوری
                ],
                'transitTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 0,
                    'maxValue' => 0,
                    'unitCode' => 'DAY'
                ]
            ]
        ];

        $entity['offers']['hasMerchantReturnPolicy'] = [
            '@type' => 'MerchantReturnPolicy',
            'applicableCountry' => 'IR',
            'returnPolicyCategory' => 'https://schema.org/MerchantReturnNotPermitted',
            'merchantReturnDays' => 0
        ];
    } else {
        // محصولات فیزیکی
        $entity['offers']['shippingDetails'] = [
            '@type' => 'OfferShippingDetails',
            'shippingRate' => [
                '@type' => 'MonetaryAmount',
                'value' => 50000, // قابل تغییر
                'currency' => 'IRR'
            ],
            'shippingDestination' => [
                '@type' => 'DefinedRegion',
                'addressCountry' => 'IR'
            ],
            'deliveryTime' => [
                '@type' => 'ShippingDeliveryTime',
                'handlingTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 1,
                    'maxValue' => 2,
                    'unitCode' => 'DAY'
                ],
                'transitTime' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 1,
                    'maxValue' => 5,
                    'unitCode' => 'DAY'
                ]
            ]
        ];

        $entity['offers']['hasMerchantReturnPolicy'] = [
            '@type' => 'MerchantReturnPolicy',
            'applicableCountry' => 'IR',
            'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
            'merchantReturnDays' => 7,
            'returnMethod' => 'https://schema.org/ReturnByMail',
            'returnFees' => 'https://schema.org/ReturnShippingFees'
        ];
    }

    return $entity;
});
