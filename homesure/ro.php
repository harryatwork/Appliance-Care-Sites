<?php
$pageTitle = 'RO Water Purifier Repair & Service in Bengaluru | Home Sure';
$pageDescription = 'RO water purifier repair and service in Bengaluru — filter replacement, installation, uninstallation and check-ups for every major brand.';

$applianceName = 'RO Water Purifier';
$applianceIconClass = 'fa-filter';
$heroTagline = 'RO, RO+UV or RO+UV+UF — filter changes, check-ups and installation from certified technicians, at your doorstep.';

// Not specified by the client — best-fit defaults, flag for review.
$typeOptions = ['RO', 'RO+UV', 'RO+UV+UF', 'Alkaline', 'Not Sure'];

$problemOptions = [
    'Repair Check-up', 'Filter Check-up', 'Filter Replacement', 'Installation', 'Uninstallation',
];

$commonIssues = [
    ['icon' => 'fa-filter', 'title' => 'Filter Replacement', 'desc' => 'Sediment, carbon and RO membrane filters changed with genuine cartridges.'],
    ['icon' => 'fa-magnifying-glass', 'title' => 'Repair Check-up', 'desc' => 'Low flow, odd taste or noisy operation diagnosed and fixed on the spot.'],
    ['icon' => 'fa-droplet', 'title' => 'Leakage / Low Water Flow', 'desc' => 'Tubing, valves and membrane checked to restore full flow and stop leaks.'],
    ['icon' => 'fa-toolbox', 'title' => 'New Installation', 'desc' => 'Wall-mounted or under-sink installation, fitted and tested before we leave.'],
];

$faqs = [
    ['q' => 'How often should RO filters be replaced?', 'a' => 'Typically every 6–12 months depending on your water source and usage — our technician can check your filter condition during a visit and advise.'],
    ['q' => 'Do you install new RO purifiers as well as repair them?', 'a' => 'Yes, we handle both new installations and repairs for RO, RO+UV and RO+UV+UF purifiers across all major brands.'],
    ['q' => 'My purifier\'s water tastes odd or the flow is very slow — what could it be?', 'a' => 'This is usually a clogged filter or membrane. Our technician diagnoses the exact cause during the check-up and replaces only what\'s needed.'],
];

require __DIR__ . '/includes/appliance-template.php';
