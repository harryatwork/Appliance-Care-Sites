<?php
$pageTitle = 'AC Repair & Service in Bengaluru | Sure Fix';
$pageDescription = 'Air conditioner repair and servicing in Bengaluru — split, window & cassette AC. Gas top-up, deep cleaning, PCB and compressor repair.';

$applianceName = 'Air Conditioner';
$applianceIconClass = 'fa-wind';
$heroTagline = 'Split, window or cassette AC — gas top-up, deep cleaning, PCB and compressor repair from our most-booked service team.';
$bannerImage = 'air-conditioner.jpg';

$typeOptions = ['Split AC', 'Tower AC', 'Window AC', 'Central AC', 'Not Sure'];

$problemGroups = [
    'Repair' => [
        'Unknown Issue', 'Not Cooling', 'Power Issue', 'Water Leakage',
        'Making Noise', 'Bad Smell', 'Remote Issue',
    ],
    'Service' => [
        'Installation', 'Uninstallation', 'Both Installation & Uninstallation',
        'Cleaning', 'Gas Refill', 'General Service', 'Not Sure',
    ],
];

$commonIssues = [
    ['icon' => 'fa-temperature-low', 'title' => 'Not Cooling', 'desc' => 'Gas top-up, filter cleaning and compressor checks to restore full cooling.'],
    ['icon' => 'fa-droplet', 'title' => 'Water Leakage', 'desc' => 'Blocked drain lines and condensation issues fixed at the source.'],
    ['icon' => 'fa-broom', 'title' => 'Deep Cleaning & Servicing', 'desc' => 'Filter, coil and blower cleaning for better air quality and efficiency.'],
    ['icon' => 'fa-microchip', 'title' => 'PCB & Remote Issues', 'desc' => 'Circuit board diagnostics and repair for units that won\'t respond.'],
];

$faqs = [
    ['q' => 'How often should my AC be serviced?', 'a' => 'We recommend deep cleaning and servicing at least once every 6 months, and before the start of summer for best performance.'],
    ['q' => 'Do you top up AC gas on the same visit?', 'a' => 'Yes — our technicians carry genuine refrigerant and can usually complete a gas top-up in the same visit.'],
    ['q' => 'Do you service both split and window ACs?', 'a' => 'Yes, our technicians are trained on split, window, cassette and portable AC units across all major brands.'],
];

require __DIR__ . '/includes/appliance-template.php';
