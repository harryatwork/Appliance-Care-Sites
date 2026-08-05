<?php
$pageTitle = 'Refrigerator Repair in Bengaluru | Sure Fix';
$pageDescription = 'Expert refrigerator repair in Bengaluru — single door, double door & side-by-side. Cooling issues, gas refilling, compressor repair and more.';

$applianceName = 'Refrigerator';
$applianceIconClass = 'fa-snowflake';
$heroTagline = 'Single door, double door or side-by-side — genuine gas refilling, compressor and cooling repairs, done right the first time.';

$typeOptions = ['Single Door', 'Double Door', 'Side-by-Side', 'Mini / Bar Fridge'];

$problemOptions = [
    'Not Cooling', 'Excessive Frost / Ice Build-up', 'Water Leakage', 'Unusual Noise',
    'Door Seal Damaged', 'Compressor Not Running', 'Display / Error Code', 'Other',
];

$commonIssues = [
    ['icon' => 'fa-temperature-low', 'title' => 'Not Cooling Properly', 'desc' => 'Gas refilling, thermostat and compressor diagnostics to restore cooling.'],
    ['icon' => 'fa-icicles', 'title' => 'Excess Frost Build-up', 'desc' => 'Faulty defrost timers or heaters identified and replaced.'],
    ['icon' => 'fa-droplet', 'title' => 'Water Leakage', 'desc' => 'Blocked drain tubes and damaged seals fixed to stop leaks at the source.'],
    ['icon' => 'fa-plug', 'title' => 'Compressor Issues', 'desc' => 'Compressor diagnostics, repair or genuine replacement with warranty.'],
];

$faqs = [
    ['q' => 'How quickly can you fix a refrigerator that stopped cooling?', 'a' => 'This is treated as an urgent repair — we prioritize same-day visits for no-cooling complaints wherever possible.'],
    ['q' => 'Do you refill refrigerant gas on-site?', 'a' => 'Yes, our technicians carry the equipment and genuine refrigerant needed to refill gas during the same visit.'],
    ['q' => 'Is there a warranty on refrigerator repairs?', 'a' => 'Every repair — parts and labour — is backed by our standard service warranty.'],
];

require __DIR__ . '/includes/appliance-template.php';
