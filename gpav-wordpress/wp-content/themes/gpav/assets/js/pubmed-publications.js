(async () => {
  const root = document.getElementById("pubmed-publications");
  if (!root) return;

  // Configuration
  const PAGE_SIZE = 10;
  const CFG = window.GPAV_PUBMED;
  const TERM = CFG.TERM;
  const EUTILS = CFG.EUTILS;
  const DB = CFG.DB;
  const TOOL = CFG.TOOL;
  const EMAIL = CFG.EMAIL;

  // Simple state
  const state = {
    page: 1,
    count: 0,
    loading: false,
    lang: root.dataset.lang || "en",
  };

  const t = (pt, en) => (state.lang === "pt" ? pt : en);

  function setHTML(html) {
    root.innerHTML = html;
  }

  function esc(s) {
    return String(s ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;");
  }

  function parsePageFromURL() {
    const url = new URL(window.location.href);
    const p = parseInt(url.searchParams.get("pubpage") || "1", 10);
    state.page = Number.isFinite(p) && p > 0 ? p : 1;
  }

  function setPageInURL(p) {
    const url = new URL(window.location.href);
    url.searchParams.set("pubpage", String(p));
    window.history.pushState({}, "", url.toString());
  }

  function renderSkeleton() {
    setHTML(`
      <h2 class="section-title">${t("Publicações", "Publications")}</h2>
      <p class="muted">${t("Carregando…", "Loading…")}</p>
    `);
  }

  function renderError(msg) {
    setHTML(`
      <h2 class="section-title">${t("Publicações", "Publications")}</h2>
      <p class="error">${esc(msg)}</p>
    `);
  }

  function renderList(items) {
    const totalPages = Math.max(1, Math.ceil(state.count / PAGE_SIZE));
    const page = Math.min(state.page, totalPages);

    const cards = items.map((it) => {
      const doiLink = it.doi ? `https://doi.org/${encodeURIComponent(it.doi)}` : "";
      const doiHTML = it.doi
        ? `<div><strong>DOI:</strong> <a href="${doiLink}">${esc(it.doi)}</a></div>`
        : `<div><strong>DOI:</strong> ${t("—", "—")}</div>`;

      return `
        <article class="pub-item">
          <h3 class="pub-title">${esc(it.title)}</h3>
          <div class="pub-meta">
            <div><strong>${t("Autores", "Authors")}:</strong> ${esc(it.authors)}</div>
            <div><strong>${t("Periódico", "Journal")}:</strong> ${esc(it.journal)}</div>
            <div><strong>${t("Edição", "Edition")}:</strong> ${esc(it.edition)}</div>
            ${doiHTML}
            <div class="pub-links">
              <a href="https://pubmed.ncbi.nlm.nih.gov/${esc(it.pmid)}/" aria-label="PubMed">PubMed</a>
            </div>
          </div>
        </article>
      `;
    }).join("");

    const pager = renderPager(page, totalPages);

    setHTML(`
      <h2 class="section-title">${t("Publicações", "Publications")}</h2>
      <p class="muted">
        ${t("Resultados", "Results")}: ${state.count} ·
        ${t("Página", "Page")} ${page}/${totalPages}
      </p>
      <div class="pub-list">${cards || `<p>${t("Nenhum resultado.", "No results.")}</p>`}</div>
      ${pager}
    `);

    wirePager(totalPages);
  }

  function renderPager(page, totalPages) {
    // Compact pager (Prev / Next + a few page buttons)
    const windowSize = 5;
    const start = Math.max(1, page - Math.floor(windowSize / 2));
    const end = Math.min(totalPages, start + windowSize - 1);
    const pages = [];
    for (let p = start; p <= end; p++) pages.push(p);

    const btn = (p, label, disabled = false, current = false) => `
      <button
        type="button"
        class="pager-btn ${current ? "current" : ""}"
        data-page="${p}"
        ${disabled ? "disabled" : ""}
        aria-current="${current ? "page" : "false"}"
      >${label}</button>
    `;

    return `
      <nav class="pager" aria-label="${t("Paginação", "Pagination")}">
        ${btn(page - 1, t("Anterior", "Prev"), page <= 1)}
        ${pages.map((p) => btn(p, String(p), false, p === page)).join("")}
        ${btn(page + 1, t("Próxima", "Next"), page >= totalPages)}
      </nav>
    `;
  }

  function wirePager(totalPages) {
    document.querySelectorAll(".pager-btn[data-page]").forEach((b) => {
      b.addEventListener("click", () => {
        const p = parseInt(b.getAttribute("data-page"), 10);
        if (!Number.isFinite(p)) return;
        if (p < 1 || p > totalPages) return;
        state.page = p;
        setPageInURL(p);
        load();
      });
    });
  }

  async function esearchCount() {
    // Count + paging via retstart/retmax, sorted by publication date (most recent first)
    // retstart/retmax are official paging params for ESearch. 
    const retstart = (state.page - 1) * PAGE_SIZE;

    const url = new URL(`${EUTILS}/esearch.fcgi`);
    url.searchParams.set("db", DB);
    url.searchParams.set("term", TERM);
    url.searchParams.set("retmode", "json");
    url.searchParams.set("retstart", String(retstart));
    url.searchParams.set("retmax", String(PAGE_SIZE));
    url.searchParams.set("sort", "pub_date");
    url.searchParams.set("tool", TOOL);
    url.searchParams.set("email", EMAIL);

    const res = await fetch(url.toString());
    if (!res.ok) throw new Error(`ESearch failed (${res.status})`);
    return res.json();
  }

  async function efetchDetails(pmids) {
    if (!pmids.length) return [];

    const url = new URL(`${EUTILS}/efetch.fcgi`);
    url.searchParams.set("db", DB);
    url.searchParams.set("id", pmids.join(","));
    url.searchParams.set("retmode", "xml");
    url.searchParams.set("tool", TOOL);
    url.searchParams.set("email", EMAIL);

    const res = await fetch(url.toString());
    if (!res.ok) throw new Error(`EFetch failed (${res.status})`);
    const xmlText = await res.text();

    const doc = new DOMParser().parseFromString(xmlText, "text/xml");
    const articles = [...doc.querySelectorAll("PubmedArticle")];

    return articles.map((a) => {
      const pmid = a.querySelector("PMID")?.textContent?.trim() || "";

      const title =
        a.querySelector("ArticleTitle")?.textContent?.replace(/\s+/g, " ").trim() || "";

      const journal =
        a.querySelector("Journal > Title")?.textContent?.trim() ||
        a.querySelector("ISOAbbreviation")?.textContent?.trim() ||
        "";

      const year =
        a.querySelector("PubDate > Year")?.textContent?.trim() ||
        a.querySelector("PubDate > MedlineDate")?.textContent?.trim() ||
        "";

      const volume = a.querySelector("JournalIssue > Volume")?.textContent?.trim() || "";
      const issue = a.querySelector("JournalIssue > Issue")?.textContent?.trim() || "";
      const pages = a.querySelector("Pagination > MedlinePgn")?.textContent?.trim() || "";

      const editionParts = [];
      if (year) editionParts.push(year);
      if (volume) editionParts.push(issue ? `${volume}(${issue})` : volume);
      if (pages) editionParts.push(pages);
      const edition = editionParts.join("; ");

      const authors = [...a.querySelectorAll("AuthorList > Author")]
        .map((au) => {
          const last = au.querySelector("LastName")?.textContent?.trim() || "";
          const initials = au.querySelector("Initials")?.textContent?.trim() || "";
          const collective = au.querySelector("CollectiveName")?.textContent?.trim() || "";
          if (collective) return collective;
          return [last, initials].filter(Boolean).join(" ");
        })
        .filter(Boolean)
        .join(", ");

      // DOI lives in ArticleIdList with IdType="doi"
      const doi =
        [...a.querySelectorAll("ArticleIdList > ArticleId")]
          .find((id) => (id.getAttribute("IdType") || "").toLowerCase() === "doi")
          ?.textContent?.trim() || "";

      return { pmid, title, authors, journal, edition, doi };
    });
  }

  async function load() {
    if (state.loading) return;
    state.loading = true;
    renderSkeleton();

    try {
      parsePageFromURL();

      const search = await esearchCount();
      const count = parseInt(search.esearchresult?.count || "0", 10);
      const ids = search.esearchresult?.idlist || [];

      state.count = Number.isFinite(count) ? count : 0;

      const items = await efetchDetails(ids);
      renderList(items);
    } catch (e) {
      renderError(e?.message || String(e));
    } finally {
      state.loading = false;
    }
  }

  window.addEventListener("popstate", () => load());
  load();
})();
