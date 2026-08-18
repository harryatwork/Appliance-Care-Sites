<?php
$pageTitle = 'Clothes Dryer Repair in Bengaluru | Home Sure';
$pageDescription = 'Clothes dryer repair in Bengaluru — heating, draining, noise and error-code issues fixed by certified technicians at your doorstep.';

$applianceName = 'Dryer';
$applianceIconClass = 'fa-wind';
$heroTagline = 'Vented, condenser or heat pump dryers — heating, draining and noise issues diagnosed and fixed by certified technicians.';

// Not specified by the client — best-fit defaults, flag for review.
$typeOptions = ['Vented', 'Heat Pump', 'Condenser', 'Not Sure'];

// This list was given identical to Washing Machine's (with "No Heating"
// added) — worth double-checking with the client that draining/filling
// symptoms genuinely apply to a clothes dryer rather than being carried
// over from the washing machine list by mistake.
$problemOptions = [
    'Unknown Issue', 'No Power', 'No Spin', 'Draining Issue', 'No Heating',
    'Not Filling', 'Water Leakage', 'Noise/Vibration', 'Error Code', 'General Service',
];

$commonIssues = [
    ['icon' => 'fa-fire-burner', 'title' => 'Not Heating', 'desc' => 'Heating element, thermostat and thermal fuse faults diagnosed and repaired.'],
    ['icon' => 'fa-rotate', 'title' => 'Drum Not Spinning', 'desc' => 'Motor, belt or door-switch issues resolved to get your dryer running again.'],
    ['icon' => 'fa-volume-high', 'title' => 'Noise / Vibration', 'desc' => 'Worn drum bearings, rollers and loose parts diagnosed and repaired.'],
    ['icon' => 'fa-microchip', 'title' => 'Error Code', 'desc' => 'Error codes read and diagnosed to pinpoint the exact fault before any repair.'],
];

$faqs = [
    ['q' => 'Do you repair vented, condenser and heat pump dryers?', 'a' => 'Yes, our technicians are trained across vented, condenser and heat pump dryers from every major brand.'],
    ['q' => 'My dryer runs but doesn\'t heat — what\'s the likely cause?', 'a' => 'This is usually a faulty heating element, thermostat or thermal fuse — our technician can diagnose and replace the exact part on-site.'],
    ['q' => 'How long does a typical dryer repair take?', 'a' => 'Most dryer repairs are completed in a single visit, usually within 45–60 minutes once the technician arrives.'],
];

require __DIR__ . '/includes/appliance-template.php';
