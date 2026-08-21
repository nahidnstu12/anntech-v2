(function () {
  const D = window.ENOVAK;
  if (!D) return;

  const page = document.body.dataset.page;
  const $app = document.getElementById("app");

  const catName = (slug) =>
    D.productCategories.find((c) => c.slug === slug)?.name || slug;

  const productBySlug = (slug) => D.products.find((p) => p.slug === slug);

  const pad = (n) => String(n).padStart(2, "0");

  function navHtml() {
    const links = D.nav
      .map((item) => {
        const active =
          (page === "landing" && item.label === "Home") ||
          ((page === "products" || page === "product") &&
            item.label === "Products");
        const extra =
          item.label === "Contact" ? " btn btn-ghost nav-cta" : "";
        return `<li><a class="${extra}${active ? " is-active" : ""}" href="${item.href}">${item.label}</a></li>`;
      })
      .join("");

    return `
      <header class="nav" id="nav">
        <div class="wrap nav-inner">
          <a class="logo" href="index.html">${D.company.name}</a>
          <button class="nav-toggle" type="button" data-toggle>Menu</button>
          <ul class="nav-links">${links}</ul>
        </div>
      </header>`;
  }

  function footerHtml() {
    const links = D.nav
      .map((i) => `<a href="${i.href}">${i.label}</a>`)
      .join("");
    return `
      <footer class="footer">
        <div class="wrap">
          <div class="footer-top">
            <a class="logo" href="index.html">${D.company.name}</a>
            <nav class="footer-nav">${links}</nav>
          </div>
          <p class="footer-copy">© ${new Date().getFullYear()} ${D.company.name} — established ${D.company.founded}. ${D.company.tagline}</p>
        </div>
      </footer>`;
  }

  function landingHtml() {
    const { hero, about, services, featuredProductSlugs } = D.landing;
    const featured = featuredProductSlugs
      .map(productBySlug)
      .filter(Boolean);

    const values = about.values
      .map(
        (v, i) => `
        <article class="card">
          <div class="index">${pad(i + 1)}</div>
          <h3>${v.title}</h3>
          <p>${v.text}</p>
        </article>`
      )
      .join("");

    const serviceCards = services
      .map(
        (s, i) => `
        <article class="card">
          <div class="index">${pad(i + 1)}</div>
          <h3>${s.name}</h3>
          <p>${s.summary}</p>
        </article>`
      )
      .join("");

    const productCards = featured.map(productCard).join("");

    const showcaseCards = D.showcase
      .slice(0, 8)
      .map(
        (s, i) => `
        <article class="card showcase-card">
          <div class="index">${pad(i + 1)} · ${s.industry}</div>
          <h3>${s.title}</h3>
          <p class="process">${s.process}</p>
          <p>${s.summary}</p>
        </article>`
      )
      .join("");

    return `
      ${navHtml()}
      <section class="hero" id="home">
        <div class="hero-media">
          <img src="${hero.image}" alt="" />
        </div>
        <div class="wrap hero-copy">
          <p class="eyebrow reveal">${hero.eyebrow}</p>
          <h1 class="reveal reveal-d1">${hero.headline}</h1>
          <p class="reveal reveal-d2">${hero.sub}</p>
          <div class="hero-actions reveal reveal-d3">
            <a class="btn btn-primary" href="${hero.primaryCta.href}">${hero.primaryCta.label}</a>
            <a class="btn btn-ghost" href="${hero.secondaryCta.href}">${hero.secondaryCta.label}</a>
          </div>
        </div>
      </section>

      <section class="section" id="about">
        <div class="wrap split">
          <div>
            <p class="eyebrow">About</p>
            <h2>${about.heading}</h2>
            ${about.body.map((p) => `<p class="lead muted">${p}</p>`).join("")}
          </div>
          <img src="https://images.unsplash.com/photo-1582719471384-894fbb16e074?auto=format&fit=crop&w=1200&q=80" alt="Laboratory and process environment" />
        </div>
        <div class="wrap">
          <article class="leader">
            <img src="${about.leadership.image}" alt="${about.leadership.name}, ${about.leadership.title}" />
            <div>
              <span>${about.leadership.title}</span>
              <strong>${about.leadership.name}</strong>
              <p class="muted">${about.leadership.bio}</p>
            </div>
          </article>
        </div>
        <div class="wrap">
          <div class="values">${values}</div>
        </div>
      </section>

      <section class="section section-surface" id="services">
        <div class="wrap">
          <div class="section-head">
            <p class="eyebrow">Services</p>
            <h2>Design through validation</h2>
            <p class="muted">End-to-end engineering for regulated plants in Bangladesh — supply, install, commission, document, validate.</p>
          </div>
          <div class="services">${serviceCards}</div>
        </div>
      </section>

      <section class="section" id="products-teaser">
        <div class="wrap">
          <div class="section-head">
            <p class="eyebrow">Products</p>
            <h2>Equipment we supply</h2>
            <p class="muted">Clean room and HVAC, process water, production machinery, packaging and QC — specified to the plant, not a catalogue default.</p>
          </div>
          <div class="products">${productCards}</div>
          <p style="margin-top:32px"><a class="btn btn-line" href="products.html">View all products</a></p>
        </div>
      </section>

      <section class="section section-surface" id="showcase">
        <div class="wrap">
          <div class="section-head">
            <p class="eyebrow">Showcase</p>
            <h2>Project history & achievements</h2>
            <p class="muted">Selected process lines and plant work across solid, liquid, sterile and specialty dosage forms.</p>
          </div>
          <div class="showcase">${showcaseCards}</div>
        </div>
      </section>

      <section class="band">
        <div class="wrap band-inner">
          <h2>Looking for a reliable engineering partner?</h2>
          <a class="btn btn-ghost" href="#contact">Get in touch</a>
        </div>
      </section>

      ${contactHtml()}
      ${footerHtml()}
    `;
  }

  function contactHtml() {
    const c = D.company;
    return `
      <section class="section" id="contact">
        <div class="wrap contact-grid">
          <div>
            <p class="eyebrow">Contact</p>
            <h2>Discuss a project</h2>
            <form class="form" id="contact-form">
              <label>Name <input name="name" required /></label>
              <label>Email <input type="email" name="email" required /></label>
              <label>Phone <input name="phone" required /></label>
              <label>Message <textarea name="message" required></textarea></label>
              <button class="btn btn-primary" type="submit">Send</button>
              <p class="ok" hidden id="form-ok">Message captured locally — wire Laravel Mail in Phase 1 deploy.</p>
            </form>
          </div>
          <div>
            <p class="eyebrow">Office</p>
            <h2>${c.name}</h2>
            <ul class="info-list">
              <li><span>Address</span>${c.address}</li>
              <li><span>Phone</span>${c.phone}</li>
              <li><span>Email</span>${c.email}</li>
            </ul>
            <iframe
              class="map"
              src="${c.mapEmbed}"
              allowfullscreen
              loading="lazy"
              referrerpolicy="strict-origin-when-cross-origin"
              title="Enovak office on Google Maps"
            ></iframe>
            <p class="map-link"><a href="${c.mapsUrl}" target="_blank" rel="noopener">Open in Google Maps →</a></p>
          </div>
        </div>
      </section>`;
  }

  function productCard(p) {
    const media = p.images?.[0]
      ? `<img src="${p.images[0]}" alt="${p.name}" />`
      : `<div class="ph">${p.name}</div>`;
    return `
      <a class="card product-card" href="product.html?slug=${p.slug}">
        ${media}
        <div class="body">
          <div class="cat">${catName(p.category)}</div>
          <h3>${p.name}</h3>
          <p>${p.shortDescription}</p>
          <div class="more">View details →</div>
        </div>
      </a>`;
  }

  function productsHtml() {
    const chips = [
      `<button class="chip is-on" data-cat="all" type="button">All</button>`,
      ...D.productCategories.map(
        (c) =>
          `<button class="chip" data-cat="${c.slug}" type="button">${c.name}</button>`
      ),
    ].join("");

    return `
      ${navHtml()}
      <section class="page-hero">
        <div class="wrap">
          <p class="eyebrow">Catalog</p>
          <h1>Products</h1>
          <p class="muted" style="margin-top:16px;max-width:54ch">Production, packaging, clean room, laboratory and water-system equipment sourced for pharmaceutical plants in Bangladesh.</p>
          <div class="filters">${chips}</div>
        </div>
      </section>
      <section class="section" style="padding-top:32px">
        <div class="wrap">
          <div class="products" id="catalog">${D.products.map(productCard).join("")}</div>
        </div>
      </section>
      ${footerHtml()}
    `;
  }

  function productHtml() {
    const slug = new URLSearchParams(location.search).get("slug");
    const p = productBySlug(slug) || D.products[0];
    const specs = Object.entries(p.specs || {})
      .map(
        ([k, v]) =>
          `<div><dt>${k}</dt><dd>${v}</dd></div>`
      )
      .join("");
    const imgs = p.images || [];
    const hero = imgs[0]
      ? `<img id="hero-shot" src="${imgs[0]}" alt="${p.name}" />`
      : `<div class="ph" style="height:480px">${p.name}</div>`;
    const thumbs = imgs
      .map(
        (src, i) =>
          `<button type="button" class="thumb${i === 0 ? " is-on" : ""}" data-src="${src}"><img src="${src}" alt="" /></button>`
      )
      .join("");

    return `
      ${navHtml()}
      <section class="page-hero" style="padding-bottom:24px">
        <div class="wrap">
          <p class="eyebrow">${catName(p.category)}</p>
          <h1>${p.name}</h1>
        </div>
      </section>
      <section class="wrap detail">
        <div>
          ${hero}
          ${imgs.length > 1 ? `<div class="thumbs">${thumbs}</div>` : ""}
        </div>
        <div>
          <p class="muted">${p.shortDescription}</p>
          <dl class="specs">${specs}</dl>
          <a class="btn btn-primary" href="index.html#contact">Enquire</a>
          <a class="btn btn-line" href="products.html" style="margin-left:8px">All products</a>
        </div>
      </section>
      ${footerHtml()}
    `;
  }

  const html =
    page === "products"
      ? productsHtml()
      : page === "product"
        ? productHtml()
        : landingHtml();

  $app.innerHTML = html;

  const nav = document.getElementById("nav");
  const onHero = page === "landing";

  function setNav() {
    const solid = !onHero || window.scrollY > 40;
    nav.classList.toggle("is-solid", solid);
  }
  setNav();
  window.addEventListener("scroll", setNav, { passive: true });

  document.querySelector("[data-toggle]")?.addEventListener("click", () => {
    nav.classList.toggle("is-open");
    nav.classList.add("is-solid");
  });

  document.getElementById("contact-form")?.addEventListener("submit", (e) => {
    e.preventDefault();
    document.getElementById("form-ok").hidden = false;
    e.target.reset();
  });

  const heroShot = document.getElementById("hero-shot");
  document.querySelectorAll(".thumb").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (!heroShot) return;
      heroShot.src = btn.dataset.src;
      document
        .querySelectorAll(".thumb")
        .forEach((t) => t.classList.toggle("is-on", t === btn));
    });
  });

  document.querySelectorAll("[data-cat]").forEach((chip) => {
    chip.addEventListener("click", () => {
      document
        .querySelectorAll("[data-cat]")
        .forEach((c) => c.classList.toggle("is-on", c === chip));
      const cat = chip.dataset.cat;
      const list =
        cat === "all"
          ? D.products
          : D.products.filter((p) => p.category === cat);
      document.getElementById("catalog").innerHTML = list
        .map(productCard)
        .join("");
    });
  });
})();
