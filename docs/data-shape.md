# Enovak — Phase 1 content (landing + products)

Source of truth for copy. `docs/data-shape.js` is the same data as a static object (Phase 1 Laravel file will mirror it).

`[brackets]` = Enovak must confirm. Everything else is draft, rewritten from competitor IA (Precisa / DREKS / Saka) — not their sentences.

**Routes:** `/` · `/products` · `/products/{slug}`  
**Nav (all pages):** Home · About · Services · Products · Showcase · Contact

---

## LANDING `/`

### Nav
- Home → `#home`
- About → `#about`
- Services → `#services`
- Products → `/products`
- Showcase → `#showcase`
- Contact → `#contact`

### Hero (`#home`)
**Eyebrow:** Est. 2007 · Bangladesh

**Headline:** Engineering solutions for Bangladesh’s pharmaceutical and industrial sector

**Sub:** Enovak supplies production equipment and delivers turnkey engineering — from plant design through installation, commissioning, and after-sales support.

**CTAs:** [Our Services → `#services`] [Get in Touch → `#contact`]

### About (`#about`)
**Heading:** A trusted engineering partner since 2007

Enovak was established in 2007 to bring international process technology and engineering standards to manufacturers in Bangladesh. We supply, install, commission, and support equipment for pharmaceutical and industrial plants, working with overseas manufacturers so clients get proven systems at a workable cost.

Work covers the full project cycle: conceptual and detailed design, equipment specification and supply, clean room and HVAC, process water, installation, commissioning, and ongoing service.

**Leadership**
*[Name]* — *[Title, e.g. Managing Director]*  
[1–2 sentences: background, years in the industry.]

**Values**
- **Quality** — Equipment and systems sourced from controlled manufacturers, specified to the process, not the brochure.
- **Reliability** — Installation, commissioning, and after-sales handled by the same team that sold the job.
- **Partnership** — Long-running support: spares, servicing, and the next line expansion.

### Services (`#services`)
**Intro:** End-to-end engineering for regulated and industrial plants in Bangladesh — design through validation, plus the equipment to run the line.

Cards (no separate service URLs; optional “Enquire” jumps to `#contact`):

1. **Plant Design & Engineering** — Layout, process flow, and specification before civil work starts.
2. **Process Consulting** — Equipment and workflow selection against the actual URS, not a catalog default.
3. **Clean Room & HVAC** — Panels, AHUs, controls: design, supply, install, commission, validate.
4. **Process Water Systems** — Generation, storage, and distribution for purified water and related utilities.
5. **Equipment Supply** — Production, packaging, and laboratory equipment from established overseas makers.
6. **Installation & Commissioning** — On-site install by trained engineers until the line is production-ready.
7. **Testing & Validation** — Performance and compliance checks after install.
8. **After-Sales Support** — Servicing, spares, and call-out so downtime stays short.

### Featured products
**Heading:** Equipment we supply  
**Link:** View all products → `/products`

Three category teasers (same slugs as the catalog):
- Production Equipment
- Clean Room & HVAC
- Laboratory Equipment

### Client Showcase (`#showcase`)
This is **project history / achievements**, not logos.

**Heading:** Selected work  
**Intro:** Completed projects across pharmaceutical and industrial plants. Named clients only where Enovak has permission; otherwise describe by industry and scope.

**Entries** (replace with real jobs — 4–8). Shape copied from Precisa’s process lines + Saka’s “contributions” list:

1. **OSD / tablet line support** — [Year] · Pharmaceutical  
   [Dispensing through packing, or the actual Enovak scope.]

2. **Clean room & HVAC package** — [Year] · Pharmaceutical  
   [Design / supply / install / commission. Sqft or classification if shareable.]

3. **Process water (PW) system** — [Year] · [Industry]  
   [Generation + storage + distribution.]

4. **Packaging / end-of-line** — [Year] · [Industry]  
   [What was installed, what changed.]

If a job can’t be named: “Turnkey HVAC and clean room for a pharmaceutical manufacturer, Dhaka.”

### Contact (`#contact`)
**Heading:** Discuss a project

**Form:** Name*, Email*, Phone*, Message*  
Phase 1: POST → email Enovak, **do not store**.

**Company**
Enovak  
[Full office address]  
Phone: [phone]  
Email: [email]

**Map:** [Google Maps embed]

### Footer (site-wide)
Enovak — established 2007. Engineering solutions for industrial and pharmaceutical clients in Bangladesh.

Nav repeat. Social: [Facebook] [LinkedIn]  
© [year] Enovak. All rights reserved.

---

## PRODUCTS `/products`

**Heading:** Products  
**Intro:** Production, packaging, clean room, laboratory, and water-system equipment sourced for pharmaceutical and industrial plants in Bangladesh.

**Categories** (filter chips or card grid):
- Production Equipment
- Packaging Equipment
- Clean Room & HVAC
- Laboratory Equipment
- Water System Equipment

Each card: name, short description, “View details”.

---

## PRODUCT DETAIL `/products/{slug}`

- Name, category, short description
- Specs (key/value)
- Image gallery
- Brochure PDF (optional)
- CTA: Enquire → `/#contact`

---

## Confirm before this is treated as final

1. Service list — keep, cut, or rename the 8 above
2. Real products (name, category, specs, photos, brochure)
3. Leadership name + title + bio
4. 4–8 real showcase jobs (anonymized OK)
5. Address, phone, email, tagline
6. Social URLs
7. Whether to show a client-name list at all (showcase is projects first)
