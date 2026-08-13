<?php
$pageTitle = 'Dishwasher Repair in Bengaluru | Sure Fix';
$pageDescription = 'Dishwasher repair in Bengaluru — power issues, water leakage, dirty dishes and error codes fixed by certified technicians at your doorstep.';

$applianceName = 'Dishwasher';
$applianceIconClass = 'fa-utensils';
$heroTagline = 'Freestanding or built-in — power, leakage and cleaning issues diagnosed and fixed by certified technicians.';

// Not specified by the client — best-fit defaults, flag for review.
$typeOptions = ['Freestanding', 'Built-in', 'Not Sure'];

$problemOptions = [
    'Unknown Issue', 'Power Issue', 'Dirty Dishes', 'Water Leakage', 'Noise Issue', 'Error Code',
];

$commonIssues = [
    ['icon' => 'fa-utensils', 'title' => 'Dishes Not Coming Out Clean', 'desc' => 'Clogged spray arms, filters or detergent dispenser issues diagnosed and fixed.'],
    ['icon' => 'fa-droplet', 'title' => 'Water Leakage', 'desc' => 'Door seals, hoses and pump connections checked to stop leaks at the source.'],
    ['icon' => 'fa-plug', 'title' => 'Power Issue', 'desc' => 'Power supply, control board and door-latch sensor faults diagnosed and repaired.'],
    ['icon' => 'fa-microchip', 'title' => 'Error Code', 'desc' => 'Error codes read and diagnosed to pinpoint the exact fault before any repair.'],
];

$faqs = [
    ['q' => 'Why are my dishes coming out dirty or with residue?', 'a' => 'This is usually a clogged spray arm, filter, or a detergent dispenser issue — our technician can diagnose and fix this in a single visit.'],
    ['q' => 'Do you repair both freestanding and built-in dishwashers?', 'a' => 'Yes, our technicians service freestanding and built-in dishwashers across all major brands.'],
    ['q' => 'Is a leaking dishwasher urgent?', 'a' => 'We treat leakage as a priority repair since it can damage your kitchen flooring or cabinetry if left unaddressed.'],
];

require __DIR__ . '/includes/appliance-template.php';
