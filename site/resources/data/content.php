<?php

/**
 * Phase 1 static content. Ported from prototype/data.js.
 * Edit here; Blade reads this file. Image paths are public URLs.
 */

return array (
  'nav' => 
  array (
    0 => 
    array (
      'label' => 'Home',
      'href' => '/#home',
    ),
    1 => 
    array (
      'label' => 'About',
      'href' => '/#about',
    ),
    2 => 
    array (
      'label' => 'Services',
      'href' => '/#services',
    ),
    3 => 
    array (
      'label' => 'Products',
      'href' => '/products',
    ),
    4 => 
    array (
      'label' => 'Showcase',
      'href' => '/#showcase',
    ),
    5 => 
    array (
      'label' => 'Contact',
      'href' => '/#contact',
    ),
  ),
  'company' => 
  array (
    'name' => 'Enovak',
    'founded' => 2007,
    'tagline' => 'Engineering solutions for industrial and pharmaceutical clients in Bangladesh',
    'summary' => 'An engineering firm supplying industrial equipment to Bangladeshi pharmaceutical companies, with technology from manufacturers worldwide — design, supply, installation, commissioning, and validation.',
    'address' => '74/B, 11th Floor, R H Home Center, 1 Green Rd, Dhaka 1215',
    'phone' => '+880 1717 1717 1717',
    'email' => 'support@enovak.com',
    'logo' => '/images/brand/logo.svg',
    'ogImage' => '/images/brand/og.jpg',
    'mapEmbed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.044999045275!2d90.38318177506036!3d23.74577468895446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8ba3b565e7b%3A0x1122630e6540f292!2sEnovak%20Industrial%20Solver!5e0!3m2!1sen!2sbd!4v1787326356236!5m2!1sen!2sbd',
    'mapsUrl' => 'https://maps.google.com/?q=Enovak+Industrial+Solver,+1+Green+Rd,+Dhaka+1215',
    'social' => 
    array (
      'facebook' => NULL,
      'linkedin' => NULL,
    ),
  ),
  'seo' =>
  array (
    'title' => 'Enovak | Pharmaceutical engineering & equipment, Bangladesh',
    'description' => 'Enovak (est. 2007) supplies, installs, commissions and validates pharmaceutical and industrial equipment in Bangladesh — clean room, HVAC, process water, production and packaging lines.',
    'keywords' => 'Enovak, pharmaceutical equipment Bangladesh, clean room HVAC Dhaka, AHU, process chiller, purified water WFI, tablet press, high shear granulator, blister packing, turnkey plant engineering, GMP validation',
  ),
  'landing' => 
  array (
    'hero' => 
    array (
      'eyebrow' => 'Est. 2007 · Bangladesh',
      'headline' => 'Engineering for Bangladesh’s pharmaceutical industry',
      'sub' => 'We supply, install, commission, document and validate machinery — bringing proven process technology from manufacturers worldwide to plants across Bangladesh.',
      'primaryCta' => 
      array (
        'label' => 'Our Services',
        'href' => '#services',
      ),
      'secondaryCta' => 
      array (
        'label' => 'Get in Touch',
        'href' => '#contact',
      ),
      'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80',
    ),
    'about' => 
    array (
      'heading' => 'A trusted engineering partner since 2007',
      'body' => 
      array (
        0 => 'Enovak was established in 2007 to bring international process technology and engineering standards to manufacturers in Bangladesh. We supply, install, commission, document and validate machinery for pharmaceutical and industrial plants.',
        1 => 'Source countries span India, Singapore, Taiwan, China, Korea, Germany, Italy, Spain, the UK and the United States. Our engineers train with the manufacturers so after-sales support on the local market is the same team that delivered the line.',
      ),
      'leadership' => 
      array (
        'name' => 'Foyjul Alam',
        'title' => 'Managing Director',
        'bio' => 'Foyjul Alam is the Managing Director of Enovak. He has been in the pharmaceutical engineering industry for over 10 years and has a deep understanding of the industry. He is a graduate of the University of Dhaka and has a Master\'s degree in Pharmaceutical Engineering from the University of Dhaka.',
        'image' => '/images/employee/director.jpg',
      ),
      'values' => 
      array (
        0 => 
        array (
          'title' => 'Global manufacturers',
          'text' => 'A network of international partnerships bringing process technology to Bangladesh.',
        ),
        1 => 
        array (
          'title' => 'Leading technology',
          'text' => 'Access to current equipment and systems — specified to the URS, not a catalogue default.',
        ),
        2 => 
        array (
          'title' => 'Responsive service',
          'text' => 'On-call support from engineers trained by the manufacturers of the machines we supply.',
        ),
        3 => 
        array (
          'title' => 'Industry support',
          'text' => 'Close collaboration with local plants through design, install, validation, and the next expansion.',
        ),
      ),
    ),
    'services' => 
    array (
      0 => 
      array (
        'slug' => 'plant-design-engineering',
        'name' => 'New Plant Design & Engineering',
        'summary' => 'Conceptual and detailed engineering for new production facilities — layout, process flow, load calculation, and system specification before construction begins.',
      ),
      1 => 
      array (
        'slug' => 'process-consulting',
        'name' => 'Process Consulting',
        'summary' => 'Technical consulting on manufacturing processes: equipment and workflow selection against the actual user requirement specification.',
      ),
      2 => 
      array (
        'slug' => 'clean-room-hvac',
        'name' => 'Clean Room & HVAC Systems',
        'summary' => 'Full-scope clean room panels, HVAC and controls — plan, design, install, commission and validate, plus after-sales and spare parts.',
      ),
      3 => 
      array (
        'slug' => 'pharma-water',
        'name' => 'Pharma Water Systems',
        'summary' => 'Turnkey water systems: generation equipment plus complete storage and distribution for purified water, water for injection, and pure steam.',
      ),
      4 => 
      array (
        'slug' => 'production-process',
        'name' => 'Production & Process Equipment',
        'summary' => 'Sourcing and supply of production-line machinery across solid, liquid, sterile, and specialty dosage forms.',
      ),
      5 => 
      array (
        'slug' => 'packaging',
        'name' => 'Packaging Equipment',
        'summary' => 'Primary and secondary packaging — blister, cartoning, sachet, serialization, and end-of-line systems.',
      ),
      6 => 
      array (
        'slug' => 'laboratory',
        'name' => 'Laboratory Equipment',
        'summary' => 'QC and testing instruments including stability chambers, incubators, and inspection systems.',
      ),
      7 => 
      array (
        'slug' => 'testing-validation',
        'name' => 'Testing, Commissioning & Validation',
        'summary' => 'On-site installation, commissioning, documentation and validation until the line is production-ready and compliant.',
      ),
    ),
    'featuredProductSlugs' => 
    array (
      0 => 'clean-room-panel',
      1 => 'air-handling-unit',
      2 => 'process-chiller',
      3 => 'high-shear-granulator',
      4 => 'tablet-press',
      5 => 'blister-line',
    ),
  ),
  'showcase' => 
  array (
    0 => 
    array (
      'slug' => 'solid-production',
      'title' => 'Solid production (OSD)',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Dispensing → Sieving → Blending → Granulation → Tabletting → Coating → Packing',
      'summary' => 'OSD lines including MUPS, segmented high-output tabletting, effervescent production, isolator-based closed granulation for OEB5, and liquid-in-hard-gelatin.',
    ),
    1 => 
    array (
      'slug' => 'semi-solid',
      'title' => 'Semi-solid production',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Dispensing → Mixing & manufacturing → Filling → Packing',
      'summary' => 'Suppository, cream and ointment lines — including turnkey suppository solutions.',
    ),
    2 => 
    array (
      'slug' => 'liquid-filling',
      'title' => 'High-speed liquid filling',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Dispensing → Mixing & manufacturing → Filling → Sealing → Labeling → Packing',
      'summary' => 'Mono-block liquid filling lines, including E-fill technology for high-speed output.',
    ),
    3 => 
    array (
      'slug' => 'soft-gel',
      'title' => 'Soft-gel production',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Dispensing → Mixing → Filling → Sealing → Encapsulation → Drying → Aging → Packing',
      'summary' => 'Turnkey soft-gel production and encapsulation suites.',
    ),
    4 => 
    array (
      'slug' => 'potent-drug',
      'title' => 'Potent & hormone production',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Solid and injectable · OEB 1–5',
      'summary' => 'Containment-led suites for potent and hormone products, solid and injectable.',
    ),
    5 => 
    array (
      'slug' => 'biotech-insulin',
      'title' => 'Biotech / insulin filling',
      'year' => NULL,
      'industry' => 'Biotech',
      'process' => 'Vials · Cartridges · Pre-filled syringes',
      'summary' => 'Combi filling lines through coding, cap, labelling and inspection for insulin formats.',
    ),
    6 => 
    array (
      'slug' => 'sterile-production',
      'title' => 'Sterile production',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Dispensing → Mixing → Filling → Sealing → Packing',
      'summary' => 'Sterile vessels and high-speed vial lines; eye drops, nasal drops, ampoules and vials.',
    ),
    7 => 
    array (
      'slug' => 'lyophilisation',
      'title' => 'Lyophilisation',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Filling → Half stoppering → LYO → Full stoppering → Labelling → Packing',
      'summary' => 'Lyophilisation technology for sterile freeze-dried products.',
    ),
    8 => 
    array (
      'slug' => 'pulmonary',
      'title' => 'Pulmonary (DPI / MDPI)',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'DPI: Blending → Encapsulation → Packing · MDPI: Blending → Blister → Secondary pack',
      'summary' => 'Low-dose drum-dosing DPI production and blister-based MDPI with applicator devices.',
    ),
    9 => 
    array (
      'slug' => 'sterilization',
      'title' => 'Sterilization',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Autoclave · DHS · Utensil washer · CIP/SIP',
      'summary' => 'Lab, pilot and production-scale autoclaves, dry-heat sterilizers, washers and CIP/SIP.',
    ),
    10 => 
    array (
      'slug' => 'qc-equipment',
      'title' => 'QC equipment',
      'year' => NULL,
      'industry' => 'Quality control',
      'process' => 'Stability · BOD · Cooling',
      'summary' => 'Stability chambers, BOD incubators and cooling cabinets for QC labs.',
    ),
    11 => 
    array (
      'slug' => 'inspection',
      'title' => 'High-speed inspection',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Cartridges · Vials · Ampoules · PFS · LYO · Tablet · Capsule',
      'summary' => 'Inspection of insulin cartridges, liquid vials/ampoules, PFS, lyophilized product, and 360° tablet/capsule inspection.',
    ),
    12 => 
    array (
      'slug' => 'packing',
      'title' => 'Packing & end-of-line',
      'year' => NULL,
      'industry' => 'Pharmaceutical',
      'process' => 'Blister · Cartoning · Sachet · Serialization',
      'summary' => 'Wallet packing, assembly (MDPI, pen, syringe), continuous-motion blister/cartoner/case packer, bottle cartoner with dropper insert, sachet, serialization, bag filler, blister-in-pouch, oral film, transdermal patch.',
    ),
  ),
  'productCategories' => 
  array (
    0 => 
    array (
      'slug' => 'clean-room-hvac',
      'name' => 'Clean Room & HVAC',
      'description' => 'Clean room panels, air handling, chillers, controls, filtration, ducting and insulation for regulated production spaces.',
    ),
    1 => 
    array (
      'slug' => 'water-systems',
      'name' => 'Pharma Water',
      'description' => 'Generation, storage and distribution for purified water, WFI and pure steam.',
    ),
    2 => 
    array (
      'slug' => 'production-equipment',
      'name' => 'Production & Process',
      'description' => 'Machinery for core manufacturing — granulation, compression, coating, filling and related line equipment.',
    ),
    3 => 
    array (
      'slug' => 'packaging-equipment',
      'name' => 'Packaging',
      'description' => 'Primary and secondary packaging: blister, cartoning, labelling, serialization and end-of-line.',
    ),
    4 => 
    array (
      'slug' => 'laboratory-equipment',
      'name' => 'Laboratory & QC',
      'description' => 'QC and testing instruments — stability, incubation, inspection and related lab systems.',
    ),
  ),
  'products' => 
  array (
    0 => 
    array (
      'slug' => 'clean-room-panel',
      'category' => 'clean-room-hvac',
      'name' => 'Modular Clean Room Panel',
      'shortDescription' => 'Sandwich panels for GMP clean rooms — walls, ceilings and returns, supplied as part of a designed envelope.',
      'specs' => 
      array (
        'Application' => 'ISO classified / GMP production',
        'Scope' => 'Panel, doors, windows, coving',
        'Finish' => 'HPL / GI / SS options',
      ),
      'images' => 
      array (
        0 => '/images/products/clean-room-panel/01.jpg',
        1 => '/images/products/clean-room-panel/02.png',
        2 => '/images/products/clean-room-panel/03.jpg',
        3 => '/images/products/clean-room-panel/04.jpg',
      ),
      'brochureUrl' => NULL,
      'featured' => true,
    ),
    1 => 
    array (
      'slug' => 'air-handling-unit',
      'category' => 'clean-room-hvac',
      'name' => 'Air Handling Unit (AHU)',
      'shortDescription' => 'Process AHUs for classified spaces — filtration, cooling, heating and humidity control specified to the room load.',
      'specs' => 
      array (
        'Type' => 'Double-skin AHU',
        'Filtration' => 'Pre + fine + HEPA (as designed)',
        'Controls' => 'BMS / local',
      ),
      'images' => 
      array (
        0 => '/images/products/air-handling-unit/01.png',
        1 => '/images/products/air-handling-unit/02.png',
        2 => '/images/products/air-handling-unit/03.jpg',
      ),
      'brochureUrl' => NULL,
      'featured' => true,
    ),
    2 => 
    array (
      'slug' => 'process-chiller',
      'category' => 'clean-room-hvac',
      'name' => 'Process Chiller',
      'shortDescription' => 'Chilled-water plant for HVAC and process loads, selected against calculated demand.',
      'specs' => 
      array (
        'Medium' => 'Chilled water',
        'Use' => 'HVAC + process',
      ),
      'images' => 
      array (
        0 => '/images/products/process-chiller/01.png',
        1 => '/images/products/process-chiller/02.jpg',
        2 => '/images/products/process-chiller/03.png',
      ),
      'brochureUrl' => NULL,
      'featured' => true,
    ),
    3 => 
    array (
      'slug' => 'desiccant-dehumidifier',
      'category' => 'clean-room-hvac',
      'name' => 'Desiccant Dehumidifier',
      'shortDescription' => 'Low-dew-point dehumidification for coating, soft-gel, and other humidity-critical rooms.',
      'specs' => 
      array (
        'Type' => 'Desiccant wheel',
        'Application' => 'Low RH process rooms',
      ),
      'images' => 
      array (
        0 => '/images/products/desiccant-dehumidifier/01.jpg',
        1 => '/images/products/desiccant-dehumidifier/02.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    4 => 
    array (
      'slug' => 'hvac-filters',
      'category' => 'clean-room-hvac',
      'name' => 'HEPA & Process Filtration',
      'shortDescription' => 'HEPA / ULPA and pre-filters for AHUs, terminals and clean-room returns.',
      'specs' => 
      array (
        'Grades' => 'G4–H14 / U15 as specified',
      ),
      'images' => 
      array (
        0 => '/images/products/hvac-filters/01.png',
      ),
      'brochureUrl' => NULL,
    ),
    5 => 
    array (
      'slug' => 'hvac-controls',
      'category' => 'clean-room-hvac',
      'name' => 'HVAC Controls',
      'shortDescription' => 'Room-pressure, temperature and humidity control with BMS integration for classified areas.',
      'specs' => 
      array (
        'Scope' => 'Sensors, valves, DDC, BMS',
      ),
      'images' => 
      array (
        0 => '/images/products/hvac-controls/01.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    6 => 
    array (
      'slug' => 'ductwork-insulation',
      'category' => 'clean-room-hvac',
      'name' => 'Ductwork & Insulation',
      'shortDescription' => 'Fabricated ducting and insulation packages executed with the HVAC install.',
      'specs' => 
      array (
        'Scope' => 'GI / SS duct, insulation, supports',
      ),
      'images' => 
      array (
        0 => '/images/products/ductwork-insulation/01.jpg',
        1 => '/images/products/ductwork-insulation/02.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    7 => 
    array (
      'slug' => 'process-fans-pumps',
      'category' => 'clean-room-hvac',
      'name' => 'Process Fans & Pumps',
      'shortDescription' => 'Fans, pumps, valves and pipe fittings selected as part of HVAC and utility skids.',
      'specs' => 
      array (
        'Includes' => 'Fans, pumps, valves, fittings',
      ),
      'images' => 
      array (
        0 => '/images/products/process-fans-pumps/01.png',
      ),
      'brochureUrl' => NULL,
    ),
    8 => 
    array (
      'slug' => 'pw-generation',
      'category' => 'water-systems',
      'name' => 'Purified Water Generation',
      'shortDescription' => 'PW generation skids for pharmaceutical utilities, designed to the pharmacopoeial grade required.',
      'specs' => 
      array (
        'Grade' => 'Purified Water',
        'Scope' => 'Generation skid',
      ),
      'images' => 
      array (
      ),
      'brochureUrl' => NULL,
    ),
    9 => 
    array (
      'slug' => 'wfi-distribution',
      'category' => 'water-systems',
      'name' => 'WFI Storage & Distribution',
      'shortDescription' => 'Water for Injection storage and loop distribution, including generation where specified.',
      'specs' => 
      array (
        'Grade' => 'WFI',
        'Scope' => 'Storage + distribution',
      ),
      'images' => 
      array (
      ),
      'brochureUrl' => NULL,
    ),
    10 => 
    array (
      'slug' => 'pure-steam',
      'category' => 'water-systems',
      'name' => 'Pure Steam Generator',
      'shortDescription' => 'Pure steam generation for sterilization and process use, integrated with the water system.',
      'specs' => 
      array (
        'Use' => 'Sterilization / process',
      ),
      'images' => 
      array (
      ),
      'brochureUrl' => NULL,
    ),
    11 => 
    array (
      'slug' => 'heat-exchanger',
      'category' => 'water-systems',
      'name' => 'Heat Exchanger Skid',
      'shortDescription' => 'Process heat exchangers for utility and water-system duty.',
      'specs' => 
      array (
        'Type' => 'Shell & tube / plate as specified',
      ),
      'images' => 
      array (
        0 => '/images/products/heat-exchanger/01.png',
        1 => '/images/products/heat-exchanger/02.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    12 => 
    array (
      'slug' => 'high-shear-granulator',
      'category' => 'production-equipment',
      'name' => 'High-Shear Granulator',
      'shortDescription' => 'Granulation suite equipment for OSD — including closed / isolator options for potent product.',
      'specs' => 
      array (
        'Line' => 'Solid oral (OSD)',
        'Containment' => 'Up to OEB5 (as specified)',
      ),
      'images' => 
      array (
        0 => '/images/products/high-shear-granulator/01.jpg',
        1 => '/images/products/high-shear-granulator/02.jpg',
        2 => '/images/products/high-shear-granulator/03.jpg',
        3 => '/images/products/high-shear-granulator/04.jpg',
        4 => '/images/products/high-shear-granulator/05.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    13 => 
    array (
      'slug' => 'tablet-press',
      'category' => 'production-equipment',
      'name' => 'Tablet Compression Press',
      'shortDescription' => 'High-output tablet presses, including segmented tooling for higher throughput.',
      'specs' => 
      array (
        'Line' => 'OSD / effervescent',
      ),
      'images' => 
      array (
        0 => '/images/products/tablet-press/01.jpg',
        1 => '/images/products/tablet-press/02.jpg',
        2 => '/images/products/tablet-press/03.jpg',
        3 => '/images/products/tablet-press/04.jpg',
        4 => '/images/products/tablet-press/05.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    14 => 
    array (
      'slug' => 'film-coater',
      'category' => 'production-equipment',
      'name' => 'Film Coating System',
      'shortDescription' => 'Coating equipment for tablets, specified against batch size and process time.',
      'specs' => 
      array (
        'Line' => 'OSD coating',
      ),
      'images' => 
      array (
        0 => '/images/products/film-coater/01.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    15 => 
    array (
      'slug' => 'liquid-filling-line',
      'category' => 'production-equipment',
      'name' => 'Liquid Filling Line',
      'shortDescription' => 'High-speed mono-block liquid filling — mixing, filling, sealing, labelling.',
      'specs' => 
      array (
        'Line' => 'Oral liquid / sterile as specified',
      ),
      'images' => 
      array (
        0 => '/images/products/liquid-filling-line/01.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    16 => 
    array (
      'slug' => 'blister-line',
      'category' => 'packaging-equipment',
      'name' => 'Blister Packaging Line',
      'shortDescription' => 'Continuous-motion blister machines with cartoning — Alu/Alu and thermoform.',
      'specs' => 
      array (
        'Formats' => 'Alu/Alu · PVC/Alu',
      ),
      'images' => 
      array (
        0 => '/images/products/blister-line/01.jpg',
        1 => '/images/products/blister-line/02.jpg',
        2 => '/images/products/blister-line/03.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    17 => 
    array (
      'slug' => 'cartoner',
      'category' => 'packaging-equipment',
      'name' => 'Cartoning Machine',
      'shortDescription' => 'High-speed cartoners, including bottle cartoning with automatic dropper insertion.',
      'specs' => 
      array (
        'Type' => 'Continuous motion',
      ),
      'images' => 
      array (
        0 => '/images/products/cartoner/01.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    18 => 
    array (
      'slug' => 'serialization',
      'category' => 'packaging-equipment',
      'name' => 'Serialization System',
      'shortDescription' => 'Track-and-trace serialization for regulated markets, integrated at packing.',
      'specs' => 
      array (
        'Scope' => 'Print, verify, aggregate',
      ),
      'images' => 
      array (
      ),
      'brochureUrl' => NULL,
    ),
    19 => 
    array (
      'slug' => 'stability-chamber',
      'category' => 'laboratory-equipment',
      'name' => 'Stability Chamber',
      'shortDescription' => 'ICH stability chambers for QC — temperature and humidity controlled.',
      'specs' => 
      array (
        'Use' => 'ICH stability',
      ),
      'images' => 
      array (
        0 => '/images/products/stability-chamber/01.jpg',
        1 => '/images/products/stability-chamber/02.jpg',
        2 => '/images/products/stability-chamber/03.jpg',
      ),
      'brochureUrl' => NULL,
    ),
    20 => 
    array (
      'slug' => 'inspection-machine',
      'category' => 'laboratory-equipment',
      'name' => 'Visual Inspection Machine',
      'shortDescription' => 'High-speed inspection for vials, ampoules, cartridges, tablets and capsules.',
      'specs' => 
      array (
        'Formats' => 'Vial · Ampoule · PFS · Tablet · Capsule',
      ),
      'images' => 
      array (
      ),
      'brochureUrl' => NULL,
    ),
    21 => 
    array (
      'slug' => 'autoclave',
      'category' => 'laboratory-equipment',
      'name' => 'Autoclave / Sterilizer',
      'shortDescription' => 'Lab, pilot and production-scale autoclaves, DHS and utensil washers.',
      'specs' => 
      array (
        'Scale' => 'Lab · Pilot · Production',
      ),
      'images' => 
      array (
        0 => '/images/products/autoclave/01.jpg',
        1 => '/images/products/autoclave/02.jpg',
        2 => '/images/products/autoclave/03.jpg',
      ),
      'brochureUrl' => NULL,
    ),
  ),
);
