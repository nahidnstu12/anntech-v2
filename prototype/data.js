/**
 * Enovak — Phase 1 static content (SAMPLE)
 * Product photos are real equipment shots for you to verify/replace:
 * - HVAC gallery from precisabd.com WP uploads (panels, AHU, chiller, dehumidifier, duct)
 * - Tablet press / autoclave: Wikimedia Commons
 * - Granulator / fluid-bed / tableting line: GEA catalog
 * - Blister line: Uhlmann (MedicalExpo)
 * - Stability chamber: BINDER
 * Copied/related shots (verify): heat-exchanger←chiller, filters/fans←AHU, cartoner/filling←blister line
 * Empty images: PW / WFI / pure steam / serialization / inspection — drop files into
 * images/products/{slug}/ and add paths here.
 */
window.ENOVAK = {
  nav: [
    { label: "Home", href: "index.html#home" },
    { label: "About", href: "index.html#about" },
    { label: "Services", href: "index.html#services" },
    { label: "Products", href: "products.html" },
    { label: "Showcase", href: "index.html#showcase" },
    { label: "Contact", href: "index.html#contact" },
  ],

  company: {
    name: "Enovak",
    founded: 2007,
    tagline:
      "Engineering solutions for industrial and pharmaceutical clients in Bangladesh",
    summary:
      "An engineering firm supplying industrial equipment to Bangladeshi pharmaceutical companies, with technology from manufacturers worldwide — design, supply, installation, commissioning, and validation.",
    address: "74/B, 11th Floor, R H Home Center, 1 Green Rd, Dhaka 1215",
    phone: "+880 1717 1717 1717",
    email: "support@enovak.com",
    mapEmbed:
      "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.044999045275!2d90.38318177506036!3d23.74577468895446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8ba3b565e7b%3A0x1122630e6540f292!2sEnovak%20Industrial%20Solver!5e0!3m2!1sen!2sbd!4v1787326356236!5m2!1sen!2sbd",
    mapsUrl:
      "https://maps.google.com/?q=Enovak+Industrial+Solver,+1+Green+Rd,+Dhaka+1215",
    social: { facebook: null, linkedin: null },
  },

  landing: {
    hero: {
      eyebrow: "Est. 2007 · Bangladesh",
      headline: "Engineering for Bangladesh’s pharmaceutical industry",
      sub: "We supply, install, commission, document and validate machinery — bringing proven process technology from manufacturers worldwide to plants across Bangladesh.",
      primaryCta: { label: "Our Services", href: "#services" },
      secondaryCta: { label: "Get in Touch", href: "#contact" },
      image:
        "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1920&q=80",
    },
    about: {
      heading: "A trusted engineering partner since 2007",
      body: [
        "Enovak was established in 2007 to bring international process technology and engineering standards to manufacturers in Bangladesh. We supply, install, commission, document and validate machinery for pharmaceutical and industrial plants.",
        "Source countries span India, Singapore, Taiwan, China, Korea, Germany, Italy, Spain, the UK and the United States. Our engineers train with the manufacturers so after-sales support on the local market is the same team that delivered the line.",
      ],
      leadership: {
        name: "Foyjul Alam",
        title: "Managing Director",
        bio: "Foyjul Alam is the Managing Director of Enovak. He has been in the pharmaceutical engineering industry for over 10 years and has a deep understanding of the industry. He is a graduate of the University of Dhaka and has a Master's degree in Pharmaceutical Engineering from the University of Dhaka.",
        image: "images/employee/director.jpg",
      },
      values: [
        {
          title: "Global manufacturers",
          text: "A network of international partnerships bringing process technology to Bangladesh.",
        },
        {
          title: "Leading technology",
          text: "Access to current equipment and systems — specified to the URS, not a catalogue default.",
        },
        {
          title: "Responsive service",
          text: "On-call support from engineers trained by the manufacturers of the machines we supply.",
        },
        {
          title: "Industry support",
          text: "Close collaboration with local plants through design, install, validation, and the next expansion.",
        },
      ],
    },
    services: [
      {
        slug: "plant-design-engineering",
        name: "New Plant Design & Engineering",
        summary:
          "Conceptual and detailed engineering for new production facilities — layout, process flow, load calculation, and system specification before construction begins.",
      },
      {
        slug: "process-consulting",
        name: "Process Consulting",
        summary:
          "Technical consulting on manufacturing processes: equipment and workflow selection against the actual user requirement specification.",
      },
      {
        slug: "clean-room-hvac",
        name: "Clean Room & HVAC Systems",
        summary:
          "Full-scope clean room panels, HVAC and controls — plan, design, install, commission and validate, plus after-sales and spare parts.",
      },
      {
        slug: "pharma-water",
        name: "Pharma Water Systems",
        summary:
          "Turnkey water systems: generation equipment plus complete storage and distribution for purified water, water for injection, and pure steam.",
      },
      {
        slug: "production-process",
        name: "Production & Process Equipment",
        summary:
          "Sourcing and supply of production-line machinery across solid, liquid, sterile, and specialty dosage forms.",
      },
      {
        slug: "packaging",
        name: "Packaging Equipment",
        summary:
          "Primary and secondary packaging — blister, cartoning, sachet, serialization, and end-of-line systems.",
      },
      {
        slug: "laboratory",
        name: "Laboratory Equipment",
        summary:
          "QC and testing instruments including stability chambers, incubators, and inspection systems.",
      },
      {
        slug: "testing-validation",
        name: "Testing, Commissioning & Validation",
        summary:
          "On-site installation, commissioning, documentation and validation until the line is production-ready and compliant.",
      },
    ],
    featuredProductSlugs: [
      "clean-room-panel",
      "air-handling-unit",
      "process-chiller",
      "high-shear-granulator",
      "tablet-press",
      "blister-line",
    ],
  },

  showcase: [
    {
      slug: "solid-production",
      title: "Solid production (OSD)",
      year: null,
      industry: "Pharmaceutical",
      process:
        "Dispensing → Sieving → Blending → Granulation → Tabletting → Coating → Packing",
      summary:
        "OSD lines including MUPS, segmented high-output tabletting, effervescent production, isolator-based closed granulation for OEB5, and liquid-in-hard-gelatin.",
    },
    {
      slug: "semi-solid",
      title: "Semi-solid production",
      year: null,
      industry: "Pharmaceutical",
      process: "Dispensing → Mixing & manufacturing → Filling → Packing",
      summary:
        "Suppository, cream and ointment lines — including turnkey suppository solutions.",
    },
    {
      slug: "liquid-filling",
      title: "High-speed liquid filling",
      year: null,
      industry: "Pharmaceutical",
      process:
        "Dispensing → Mixing & manufacturing → Filling → Sealing → Labeling → Packing",
      summary:
        "Mono-block liquid filling lines, including E-fill technology for high-speed output.",
    },
    {
      slug: "soft-gel",
      title: "Soft-gel production",
      year: null,
      industry: "Pharmaceutical",
      process:
        "Dispensing → Mixing → Filling → Sealing → Encapsulation → Drying → Aging → Packing",
      summary:
        "Turnkey soft-gel production and encapsulation suites.",
    },
    {
      slug: "potent-drug",
      title: "Potent & hormone production",
      year: null,
      industry: "Pharmaceutical",
      process: "Solid and injectable · OEB 1–5",
      summary:
        "Containment-led suites for potent and hormone products, solid and injectable.",
    },
    {
      slug: "biotech-insulin",
      title: "Biotech / insulin filling",
      year: null,
      industry: "Biotech",
      process: "Vials · Cartridges · Pre-filled syringes",
      summary:
        "Combi filling lines through coding, cap, labelling and inspection for insulin formats.",
    },
    {
      slug: "sterile-production",
      title: "Sterile production",
      year: null,
      industry: "Pharmaceutical",
      process:
        "Dispensing → Mixing → Filling → Sealing → Packing",
      summary:
        "Sterile vessels and high-speed vial lines; eye drops, nasal drops, ampoules and vials.",
    },
    {
      slug: "lyophilisation",
      title: "Lyophilisation",
      year: null,
      industry: "Pharmaceutical",
      process:
        "Filling → Half stoppering → LYO → Full stoppering → Labelling → Packing",
      summary:
        "Lyophilisation technology for sterile freeze-dried products.",
    },
    {
      slug: "pulmonary",
      title: "Pulmonary (DPI / MDPI)",
      year: null,
      industry: "Pharmaceutical",
      process: "DPI: Blending → Encapsulation → Packing · MDPI: Blending → Blister → Secondary pack",
      summary:
        "Low-dose drum-dosing DPI production and blister-based MDPI with applicator devices.",
    },
    {
      slug: "sterilization",
      title: "Sterilization",
      year: null,
      industry: "Pharmaceutical",
      process: "Autoclave · DHS · Utensil washer · CIP/SIP",
      summary:
        "Lab, pilot and production-scale autoclaves, dry-heat sterilizers, washers and CIP/SIP.",
    },
    {
      slug: "qc-equipment",
      title: "QC equipment",
      year: null,
      industry: "Quality control",
      process: "Stability · BOD · Cooling",
      summary:
        "Stability chambers, BOD incubators and cooling cabinets for QC labs.",
    },
    {
      slug: "inspection",
      title: "High-speed inspection",
      year: null,
      industry: "Pharmaceutical",
      process: "Cartridges · Vials · Ampoules · PFS · LYO · Tablet · Capsule",
      summary:
        "Inspection of insulin cartridges, liquid vials/ampoules, PFS, lyophilized product, and 360° tablet/capsule inspection.",
    },
    {
      slug: "packing",
      title: "Packing & end-of-line",
      year: null,
      industry: "Pharmaceutical",
      process: "Blister · Cartoning · Sachet · Serialization",
      summary:
        "Wallet packing, assembly (MDPI, pen, syringe), continuous-motion blister/cartoner/case packer, bottle cartoner with dropper insert, sachet, serialization, bag filler, blister-in-pouch, oral film, transdermal patch.",
    },
  ],

  productCategories: [
    {
      slug: "clean-room-hvac",
      name: "Clean Room & HVAC",
      description:
        "Clean room panels, air handling, chillers, controls, filtration, ducting and insulation for regulated production spaces.",
    },
    {
      slug: "water-systems",
      name: "Pharma Water",
      description:
        "Generation, storage and distribution for purified water, WFI and pure steam.",
    },
    {
      slug: "production-equipment",
      name: "Production & Process",
      description:
        "Machinery for core manufacturing — granulation, compression, coating, filling and related line equipment.",
    },
    {
      slug: "packaging-equipment",
      name: "Packaging",
      description:
        "Primary and secondary packaging: blister, cartoning, labelling, serialization and end-of-line.",
    },
    {
      slug: "laboratory-equipment",
      name: "Laboratory & QC",
      description:
        "QC and testing instruments — stability, incubation, inspection and related lab systems.",
    },
  ],

  products: [
    {
      slug: "clean-room-panel",
      category: "clean-room-hvac",
      name: "Modular Clean Room Panel",
      shortDescription:
        "Sandwich panels for GMP clean rooms — walls, ceilings and returns, supplied as part of a designed envelope.",
      specs: {
        Application: "ISO classified / GMP production",
        Scope: "Panel, doors, windows, coving",
        Finish: "HPL / GI / SS options",
      },
      images: [
        "images/products/clean-room-panel/01.jpg",
        "images/products/clean-room-panel/02.png",
        "images/products/clean-room-panel/03.jpg",
        "images/products/clean-room-panel/04.jpg"
      ],
      brochureUrl: null,
      featured: true,
    },
    {
      slug: "air-handling-unit",
      category: "clean-room-hvac",
      name: "Air Handling Unit (AHU)",
      shortDescription:
        "Process AHUs for classified spaces — filtration, cooling, heating and humidity control specified to the room load.",
      specs: {
        Type: "Double-skin AHU",
        Filtration: "Pre + fine + HEPA (as designed)",
        Controls: "BMS / local",
      },
      images: [
        "images/products/air-handling-unit/01.png",
        "images/products/air-handling-unit/02.png",
        "images/products/air-handling-unit/03.jpg"
      ],
      brochureUrl: null,
      featured: true,
    },
    {
      slug: "process-chiller",
      category: "clean-room-hvac",
      name: "Process Chiller",
      shortDescription:
        "Chilled-water plant for HVAC and process loads, selected against calculated demand.",
      specs: {
        Medium: "Chilled water",
        Use: "HVAC + process",
      },
      images: [
        "images/products/process-chiller/01.png",
        "images/products/process-chiller/02.jpg",
        "images/products/process-chiller/03.png"
      ],
      brochureUrl: null,
      featured: true,
    },
    {
      slug: "desiccant-dehumidifier",
      category: "clean-room-hvac",
      name: "Desiccant Dehumidifier",
      shortDescription:
        "Low-dew-point dehumidification for coating, soft-gel, and other humidity-critical rooms.",
      specs: {
        Type: "Desiccant wheel",
        Application: "Low RH process rooms",
      },
      images: [
        "images/products/desiccant-dehumidifier/01.jpg",
        "images/products/desiccant-dehumidifier/02.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "hvac-filters",
      category: "clean-room-hvac",
      name: "HEPA & Process Filtration",
      shortDescription:
        "HEPA / ULPA and pre-filters for AHUs, terminals and clean-room returns.",
      specs: {
        Grades: "G4–H14 / U15 as specified",
      },
      images: [
        "images/products/hvac-filters/01.png"
      ],
      brochureUrl: null,
    },
    {
      slug: "hvac-controls",
      category: "clean-room-hvac",
      name: "HVAC Controls",
      shortDescription:
        "Room-pressure, temperature and humidity control with BMS integration for classified areas.",
      specs: {
        Scope: "Sensors, valves, DDC, BMS",
      },
      images: [
        "images/products/hvac-controls/01.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "ductwork-insulation",
      category: "clean-room-hvac",
      name: "Ductwork & Insulation",
      shortDescription:
        "Fabricated ducting and insulation packages executed with the HVAC install.",
      specs: {
        Scope: "GI / SS duct, insulation, supports",
      },
      images: [
        "images/products/ductwork-insulation/01.jpg",
        "images/products/ductwork-insulation/02.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "process-fans-pumps",
      category: "clean-room-hvac",
      name: "Process Fans & Pumps",
      shortDescription:
        "Fans, pumps, valves and pipe fittings selected as part of HVAC and utility skids.",
      specs: {
        Includes: "Fans, pumps, valves, fittings",
      },
      images: [
        "images/products/process-fans-pumps/01.png"
      ],
      brochureUrl: null,
    },
    {
      slug: "pw-generation",
      category: "water-systems",
      name: "Purified Water Generation",
      shortDescription:
        "PW generation skids for pharmaceutical utilities, designed to the pharmacopoeial grade required.",
      specs: {
        Grade: "Purified Water",
        Scope: "Generation skid",
      },
      images: [],
      brochureUrl: null,
    },
    {
      slug: "wfi-distribution",
      category: "water-systems",
      name: "WFI Storage & Distribution",
      shortDescription:
        "Water for Injection storage and loop distribution, including generation where specified.",
      specs: {
        Grade: "WFI",
        Scope: "Storage + distribution",
      },
      images: [],
      brochureUrl: null,
    },
    {
      slug: "pure-steam",
      category: "water-systems",
      name: "Pure Steam Generator",
      shortDescription:
        "Pure steam generation for sterilization and process use, integrated with the water system.",
      specs: {
        Use: "Sterilization / process",
      },
      images: [],
      brochureUrl: null,
    },
    {
      slug: "heat-exchanger",
      category: "water-systems",
      name: "Heat Exchanger Skid",
      shortDescription:
        "Process heat exchangers for utility and water-system duty.",
      specs: {
        Type: "Shell & tube / plate as specified",
      },
      images: [
        "images/products/heat-exchanger/01.png",
        "images/products/heat-exchanger/02.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "high-shear-granulator",
      category: "production-equipment",
      name: "High-Shear Granulator",
      shortDescription:
        "Granulation suite equipment for OSD — including closed / isolator options for potent product.",
      specs: {
        Line: "Solid oral (OSD)",
        Containment: "Up to OEB5 (as specified)",
      },
      images: [
        "images/products/high-shear-granulator/01.jpg",
        "images/products/high-shear-granulator/02.jpg",
        "images/products/high-shear-granulator/03.jpg",
        "images/products/high-shear-granulator/04.jpg",
        "images/products/high-shear-granulator/05.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "tablet-press",
      category: "production-equipment",
      name: "Tablet Compression Press",
      shortDescription:
        "High-output tablet presses, including segmented tooling for higher throughput.",
      specs: {
        Line: "OSD / effervescent",
      },
      images: [
        "images/products/tablet-press/01.jpg",
        "images/products/tablet-press/02.jpg",
        "images/products/tablet-press/03.jpg",
        "images/products/tablet-press/04.jpg",
        "images/products/tablet-press/05.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "film-coater",
      category: "production-equipment",
      name: "Film Coating System",
      shortDescription:
        "Coating equipment for tablets, specified against batch size and process time.",
      specs: {
        Line: "OSD coating",
      },
      images: [
        "images/products/film-coater/01.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "liquid-filling-line",
      category: "production-equipment",
      name: "Liquid Filling Line",
      shortDescription:
        "High-speed mono-block liquid filling — mixing, filling, sealing, labelling.",
      specs: {
        Line: "Oral liquid / sterile as specified",
      },
      images: [
        "images/products/liquid-filling-line/01.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "blister-line",
      category: "packaging-equipment",
      name: "Blister Packaging Line",
      shortDescription:
        "Continuous-motion blister machines with cartoning — Alu/Alu and thermoform.",
      specs: {
        Formats: "Alu/Alu · PVC/Alu",
      },
      images: [
        "images/products/blister-line/01.jpg",
        "images/products/blister-line/02.jpg",
        "images/products/blister-line/03.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "cartoner",
      category: "packaging-equipment",
      name: "Cartoning Machine",
      shortDescription:
        "High-speed cartoners, including bottle cartoning with automatic dropper insertion.",
      specs: {
        Type: "Continuous motion",
      },
      images: [
        "images/products/cartoner/01.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "serialization",
      category: "packaging-equipment",
      name: "Serialization System",
      shortDescription:
        "Track-and-trace serialization for regulated markets, integrated at packing.",
      specs: {
        Scope: "Print, verify, aggregate",
      },
      images: [],
      brochureUrl: null,
    },
    {
      slug: "stability-chamber",
      category: "laboratory-equipment",
      name: "Stability Chamber",
      shortDescription:
        "ICH stability chambers for QC — temperature and humidity controlled.",
      specs: {
        Use: "ICH stability",
      },
      images: [
        "images/products/stability-chamber/01.jpg",
        "images/products/stability-chamber/02.jpg",
        "images/products/stability-chamber/03.jpg"
      ],
      brochureUrl: null,
    },
    {
      slug: "inspection-machine",
      category: "laboratory-equipment",
      name: "Visual Inspection Machine",
      shortDescription:
        "High-speed inspection for vials, ampoules, cartridges, tablets and capsules.",
      specs: {
        Formats: "Vial · Ampoule · PFS · Tablet · Capsule",
      },
      images: [],
      brochureUrl: null,
    },
    {
      slug: "autoclave",
      category: "laboratory-equipment",
      name: "Autoclave / Sterilizer",
      shortDescription:
        "Lab, pilot and production-scale autoclaves, DHS and utensil washers.",
      specs: {
        Scale: "Lab · Pilot · Production",
      },
      images: [
        "images/products/autoclave/01.jpg",
        "images/products/autoclave/02.jpg",
        "images/products/autoclave/03.jpg"
      ],
      brochureUrl: null,
    },
  ],
};
