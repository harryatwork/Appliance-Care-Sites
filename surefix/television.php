<?php
$pageTitle = 'TV Repair in Bengaluru | Sure Fix';
$pageDescription = 'Television repair in Bengaluru — LED, Smart TV & OLED. Panel faults, sound issues and smart software problems fixed at your doorstep.';

$applianceName = 'Television';
$applianceIconClass = 'fa-tv';
$heroTagline = 'LED, Smart TV or OLED — panel, sound and software issues diagnosed and fixed by certified technicians.';
$bannerImage = 'television.jpg';

$typeOptions = ['LED', 'Smart TV', 'OLED', 'LCD'];

$problemGroups = [
    'Repair' => [
        'Display Issue', 'Power Issue', 'Sound Issue', 'Remote Issue', 'Unknown Issue',
    ],
    'Service' => [
        'Installation', 'Uninstallation', 'Both Installation & Uninstallation',
    ],
];

$commonIssues = [
    ['icon' => 'fa-tv', 'title' => 'No Display / Black Screen', 'desc' => 'Backlight, panel and power-board faults diagnosed and repaired.'],
    ['icon' => 'fa-volume-xmark', 'title' => 'No Sound', 'desc' => 'Speaker and audio board issues fixed to restore clear sound.'],
    ['icon' => 'fa-satellite-dish', 'title' => 'Smart Features Not Working', 'desc' => 'Software glitches, connectivity and app issues on Smart TVs resolved.'],
    ['icon' => 'fa-bolt', 'title' => 'Not Turning On', 'desc' => 'Power supply and internal component checks to bring your TV back on.'],
];

$faqs = [
    ['q' => 'Do you repair Smart TVs and their software issues?', 'a' => 'Yes — our technicians handle both hardware repairs and common software/connectivity issues on Smart TVs.'],
    ['q' => 'Can a TV panel be repaired or does it need replacement?', 'a' => 'It depends on the damage. Minor panel issues can often be repaired; major screen damage may need a panel replacement, which we\'ll quote upfront.'],
    ['q' => 'Do you offer doorstep TV repair for large screens?', 'a' => 'Yes, our technicians carry the equipment needed to safely diagnose and repair TVs of all sizes at your home.'],
];

require __DIR__ . '/includes/appliance-template.php';
