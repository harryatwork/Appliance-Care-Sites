<?php
$pageTitle = 'Geyser / Water Heater Repair in Bengaluru | Home Sure';
$pageDescription = 'Geyser and water heater repair in Bengaluru — electric, gas & instant. Installation, thermostat and element replacement.';

$applianceName = 'Geyser';
$applianceIconClass = 'fa-droplet';
$heroTagline = 'Electric, gas or instant geysers — installation, thermostat and heating element replacement from certified technicians.';
$bannerImage = 'geyser.jpg';

$typeOptions = ['Electric', 'Gas', 'Instant', 'Storage'];

$problemOptions = [
    'Not Heating', 'Water Leakage', 'No Power', 'Water Quality Issue',
    'Installation', 'Uninstallation', 'Other',
];

$commonIssues = [
    ['icon' => 'fa-temperature-low', 'title' => 'No Hot Water', 'desc' => 'Heating element and thermostat faults diagnosed and repaired.'],
    ['icon' => 'fa-droplet', 'title' => 'Water Leakage', 'desc' => 'Tank corrosion, valve and pipe connection issues fixed to stop leaks.'],
    ['icon' => 'fa-bolt', 'title' => 'Tripping the Circuit', 'desc' => 'Electrical faults identified and repaired safely — a safety-first priority.'],
    ['icon' => 'fa-screwdriver-wrench', 'title' => 'New Installation', 'desc' => 'Safe, code-compliant installation for electric, gas or instant geysers.'],
];

$faqs = [
    ['q' => 'My geyser is tripping the electrical circuit — is that dangerous?', 'a' => 'It can be. Switch it off at the mains and book a repair — our technicians treat electrical faults as a safety-first priority.'],
    ['q' => 'Do you install new geysers as well as repair them?', 'a' => 'Yes, we handle both new installations and repairs for electric, gas and instant geysers.'],
    ['q' => 'How long does a geyser repair usually take?', 'a' => 'Most repairs — like a heating element or thermostat replacement — are completed within a single visit.'],
];

require __DIR__ . '/includes/appliance-template.php';
