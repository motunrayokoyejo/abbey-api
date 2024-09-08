<?php

function maskId($id): string
{
    return base64_encode($id);
}

function unmaskId($id): string
{
    return base64_decode($id);
}

/**
 * @throws \Random\RandomException
 */
function generateAccountNumber(): string
{
    return str_pad((string)random_int(0, 99999999999), 11, '0', STR_PAD_LEFT);
}

function getRandomBank(): string
{
    $banks = [
        'Access Bank',
        'Citibank',
        'Ecobank Nigeria',
        'Fidelity Bank',
        'First Bank of Nigeria',
        'First City Monument Bank (FCMB)',
        'Guaranty Trust Bank (GTBank)',
        'Heritage Bank',
        'Keystone Bank',
        'Polaris Bank',
        'Providus Bank',
        'Stanbic IBTC Bank',
        'Standard Chartered Bank',
        'Sterling Bank',
        'Union Bank',
        'United Bank for Africa (UBA)',
        'Unity Bank',
        'Wema Bank',
        'Zenith Bank'
    ];

    return $banks[array_rand($banks)];
}

