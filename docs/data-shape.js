// Enovak — Phase 1 static content
// Laravel: copy this shape into resources/data/content.php and include() from Blade.
// No DB in Phase 1. Phase 2 maps these keys onto MySQL 1:1.
//
// [confirm] = Enovak fact, not competitor copy. Services/categories are
// industry-standard for BD pharma-equipment firms (Precisa / DREKS / Saka),
// rewritten. Do not publish as Enovak offerings until confirmed.

const nav = [
  { label: "Home", href: "/#home" },
  { label: "About", href: "/#about" },
  { label: "Services", href: "/#services" },
  { label: "Products", href: "/products" },
  { label: "Showcase", href: "/#showcase" },
  { label: "Contact", href: "/#contact" },
];

const company = {
  name: "Enovak",
  founded: 2007,
  tagline:
    "Engineering solutions for industrial and pharmaceutical clients in Bangladesh", // [confirm]
  summary:
    "Enovak supplies production equipment and delivers turnkey engineering to manufacturing and pharmaceutical plants in Bangladesh — design, supply, installation, commissioning, and after-sales support.", // [confirm]
  address: "[office address]",
  phone: "[phone number]",
  email: "[contact email]",
  social: {
    facebook: null,
    linkedin: null,
  },
};

const landing = {
  hero: {
    eyebrow: "Est. 2007 · Bangladesh",
    headline:
      "Engineering solutions for Bangladesh’s pharmaceutical and industrial sector",
    sub:
      "Enovak supplies production equipment and delivers turnkey engineering — from plant design through installation, commissioning, and after-sales support.",
    primaryCta: { label: "Our Services", href: "/#services" },
    secondaryCta: { label: "Get in Touch", href: "/#contact" },
  },

  about: {
    heading: "A trusted engineering partner since 2007",
    body: [
      "Enovak was established in 2007 to bring international process technology and engineering standards to manufacturers in Bangladesh. We supply, install, commission, and support equipment for pharmaceutical and industrial plants, working with overseas manufacturers so clients get proven systems at a workable cost.",
      "Work covers the full project cycle: conceptual and detailed design, equipment specification and supply, clean room and HVAC, process water, installation, commissioning, and ongoing service.",
    ],
    leadership: {
      name: "[Name]",
      title: "[Title, e.g. Managing Director]",
      bio: "[1–2 sentences: background, years in the industry.]",
    },
    values: [
      {
        title: "Quality",
        text: "Equipment and systems sourced from controlled manufacturers, specified to the process, not the brochure.",
      },
      {
        title: "Reliability",
        text: "Installation, commissioning, and after-sales handled by the same team that sold the job.",
      },
      {
        title: "Partnership",
        text: "Long-running support: spares, servicing, and the next line expansion.",
      },
    ],
  },

  services: [
    {
      slug: "plant-design-engineering",
      name: "Plant Design & Engineering",
      summary:
        "Conceptual and detailed engineering for new production facilities — layout, process flow, and system specification before construction begins.",
    },
    {
      slug: "process-consulting",
      name: "Process Consulting",
      summary:
        "Technical consulting on manufacturing processes: equipment and workflow selection against the actual URS.",
    },
    {
      slug: "clean-room-hvac",
      name: "Clean Room & HVAC Systems",
      summary:
        "Design, supply, installation, commissioning, and validation of clean room panels, HVAC, and environmental controls for regulated plants.",
    },
    {
      slug: "water-systems",
      name: "Process Water Systems",
      summary:
        "Turnkey water systems — generation, storage, and distribution for purified water and related process utilities.",
    },
    {
      slug: "equipment-supply",
      name: "Equipment Supply",
      summary:
        "Sourcing and supply of production, packaging, and laboratory equipment from established overseas manufacturers.",
    },
    {
      slug: "installation-commissioning",
      name: "Installation & Commissioning",
      summary:
        "On-site installation and commissioning by trained engineers until systems are production-ready.",
    },
    {
      slug: "validation-testing",
      name: "Testing & Validation",
      summary:
        "Post-installation testing and validation support to confirm performance and compliance requirements.",
    },
    {
      slug: "after-sales-support",
      name: "After-Sales Support",
      summary:
        "Servicing, spare parts, and technical call-out after handover, to keep production running.",
    },
  ],

  featuredProductCategorySlugs: [
    "production-equipment",
    "clean-room-hvac-systems",
    "laboratory-equipment",
  ],
};

// Client showcase = project history / achievements (not logos).
const showcase = [
  {
    slug: "osd-tablet-line",
    title: "[OSD / tablet line — replace with real job]",
    year: null, // [confirm]
    industry: "Pharmaceutical",
    summary:
      "[Scope: e.g. dispensing through packing, or the actual Enovak slice of the line.]",
  },
  {
    slug: "clean-room-hvac-package",
    title: "[Clean room & HVAC package — replace with real job]",
    year: null,
    industry: "Pharmaceutical",
    summary:
      "[Design / supply / install / commission. Classification or area if shareable.]",
  },
  {
    slug: "process-water-system",
    title: "[Process water system — replace with real job]",
    year: null,
    industry: "[Industry]",
    summary: "[Generation, storage, distribution — what was delivered.]",
  },
  {
    slug: "packaging-end-of-line",
    title: "[Packaging / end-of-line — replace with real job]",
    year: null,
    industry: "[Industry]",
    summary: "[What was installed and what changed for the client.]",
  },
];

const productCategories = [
  {
    slug: "production-equipment",
    name: "Production Equipment",
    description:
      "Machinery for core manufacturing stages — mixing, filling, processing, and related line equipment.",
  },
  {
    slug: "packaging-equipment",
    name: "Packaging Equipment",
    description:
      "Primary and secondary packaging: filling, sealing, labeling, cartoning.",
  },
  {
    slug: "clean-room-hvac-systems",
    name: "Clean Room & HVAC",
    description:
      "Clean room panels, air handling units, chillers, and environmental controls for regulated production spaces.",
  },
  {
    slug: "laboratory-equipment",
    name: "Laboratory Equipment",
    description:
      "QC and testing instruments — stability chambers, inspection, and related lab systems.",
  },
  {
    slug: "water-systems-equipment",
    name: "Water System Equipment",
    description:
      "Generation, storage, and distribution equipment for process-grade water systems.",
  },
];

// Replace with real Enovak products. One category per product; images[] for gallery.
const products = [
  {
    slug: "air-handling-unit",
    category: "clean-room-hvac-systems",
    name: "[Product name — e.g. Air Handling Unit]",
    shortDescription: "[1–2 sentence description]",
    specs: {
      // capacity: "[value]",
      // powerRating: "[value]",
    },
    images: [],
    brochureUrl: null,
  },
  {
    slug: "stability-chamber",
    category: "laboratory-equipment",
    name: "[Product name — e.g. Stability Chamber]",
    shortDescription: "[1–2 sentence description]",
    specs: {},
    images: [],
    brochureUrl: null,
  },
];

module.exports = {
  nav,
  company,
  landing,
  showcase,
  productCategories,
  products,
};
