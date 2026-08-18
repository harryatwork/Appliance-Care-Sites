<?php
$pageTitle = 'Washing Machine Repair in Bengaluru | Home Sure';
$pageDescription = 'Same-day washing machine repair in Bengaluru — front load, top load & semi-automatic. Certified technicians, genuine parts, transparent pricing.';

$applianceName = 'Washing Machine';
$applianceIconClass = 'fa-shirt';
$heroTagline = 'Front load, top load or semi-automatic — our certified technicians fix it at your doorstep, usually the same day.';
$bannerImage = 'washing-machine.jpg';

$typeOptions = ['Front Load', 'Top Load', 'Semi Automatic'];

$problemOptions = [
    'Unknown Issue', 'No Power', 'No Spin', 'Draining Issue', 'Not Filling',
    'Water Leakage', 'Noise/Vibration', 'Error Code', 'General Service',
];

$commonIssues = [
    ['icon' => 'fa-power-off', 'title' => 'Machine Not Starting', 'desc' => 'Power supply, control board or door-lock sensor faults diagnosed and fixed.'],
    ['icon' => 'fa-rotate', 'title' => 'Drum Not Spinning', 'desc' => 'Motor, belt or capacitor issues — a common fix across all machine types.'],
    ['icon' => 'fa-droplet', 'title' => 'Water Leakage', 'desc' => 'Damaged hoses, door seals or drain pump replaced with genuine parts.'],
    ['icon' => 'fa-volume-high', 'title' => 'Excessive Noise / Vibration', 'desc' => 'Worn bearings, unbalanced drums and loose parts diagnosed and repaired.'],
];

$faqs = [
    ['q' => 'Do you repair both front-load and top-load machines?', 'a' => 'Yes — our technicians are trained on front load, top load, and semi-automatic machines across every major brand.'],
    ['q' => 'How long does a typical repair take?', 'a' => 'Most washing machine repairs are completed in a single visit, usually within 45–60 minutes once the technician arrives.'],
    ['q' => 'Do you carry spare parts with you?', 'a' => 'Our technicians carry commonly needed parts. For less common parts, we source genuine components and schedule a quick follow-up visit.'],
];

require __DIR__ . '/includes/appliance-template.php';
