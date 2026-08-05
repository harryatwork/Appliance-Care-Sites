<?php
$pageTitle = 'Microwave & Oven Repair in Bengaluru | Sure Fix';
$pageDescription = 'Microwave and oven repair in Bengaluru — solo, grill & convection. Heating faults, sparking and turntable issues fixed fast.';

$applianceName = 'Microwave Oven';
$applianceIconClass = 'fa-fire-burner';
$heroTagline = 'Solo, grill or convection — heating faults, sparking and turntable issues fixed fast by certified technicians.';

$typeOptions = ['Solo', 'Grill', 'Convection'];

$problemOptions = [
    'Not Heating', 'Turntable Not Rotating', 'Sparking Inside', 'Display / Buttons Not Working',
    'Door Not Closing Properly', 'Unusual Noise', 'Other',
];

$commonIssues = [
    ['icon' => 'fa-temperature-high', 'title' => 'Not Heating', 'desc' => 'Magnetron, diode and capacitor faults diagnosed and repaired safely.'],
    ['icon' => 'fa-bolt', 'title' => 'Sparking Inside', 'desc' => 'Waveguide covers and internal damage identified and fixed — don\'t keep using it until checked.'],
    ['icon' => 'fa-arrows-spin', 'title' => 'Turntable Not Rotating', 'desc' => 'Motor and coupler issues resolved to get your turntable spinning again.'],
    ['icon' => 'fa-door-closed', 'title' => 'Door Not Closing Properly', 'desc' => 'Door latch and safety switch repairs so your microwave operates safely.'],
];

$faqs = [
    ['q' => 'Is it safe to keep using a sparking microwave?', 'a' => 'No — stop using it immediately and book a repair. Sparking can indicate a damaged waveguide cover or internal fault that needs prompt attention.'],
    ['q' => 'Do you repair both solo and convection microwaves?', 'a' => 'Yes, our technicians service solo, grill and convection microwave ovens across all major brands.'],
    ['q' => 'Can you fix a microwave that won\'t heat at all?', 'a' => 'In most cases, yes — this is usually a magnetron or diode issue, which our technicians can diagnose and repair on-site.'],
];

require __DIR__ . '/includes/appliance-template.php';
