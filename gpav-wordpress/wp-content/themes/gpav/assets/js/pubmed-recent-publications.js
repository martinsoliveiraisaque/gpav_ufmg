(async () => {
  const root = document.getElementById("recent-publications");
  if (!root) return;

  const lang = root.dataset.lang || "en";
  const t = (pt, en) => (lang === "pt" ? pt : en);

  // Same author query as your Publications page (adjust later if needed)
  const CFG = window.GPAV_PUBMED;
  const TERM = CFG.TERM;
  const EUTILS = CFG.EUTILS;
  const DB = CFG.DB;
  const TOOL = CFG.TOOL;
  const EMAIL = CFG.EMAIL;

  const esc = (s) =>
    String(s ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;");

  const setHTML = (html) => (root.innerHTML = html);

  async function esearchLatest3() {
    const url = new URL(`${EUTILS}/esearch.fcgi`);
    url.searchParams.set("db", DB);
    url.searchParams.set("term", TERM);
    url.searchParams.set("retmode", "json");
    url.searchParams.set("retstart", "0");
    url.searchParams.set("retmax", "3");
    url.searchParams.set("sort", "pub_date");
    url.searchParams.set("tool", TOOL);
    url.searchParams.set("email", EMAIL);

    const res = await fetch(url.toString());
    if (!res.ok) throw new Error(`ESearch failed (${res.status})`);
    const data = await res.json();
    return data?.esearchresult?.idlist || [];
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

    return [...doc.querySelectorAll("PubmedArticle")].map((a) => {
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

      const doi =
        [...a.querySelectorAll("ArticleIdList > ArticleId")]
          .find((id) => (id.getAttribute("IdType") || "").toLowerCase() === "doi")
          ?.textContent?.trim() || "";

      return { pmid, title, journal, year, doi };
    });
  }

  function renderCards(items) {
    if (!items.length) {
      setHTML(`<p class="muted">${t("Em breve.", "Coming soon.")}</p>`);
      return;
    }

    // Reuse your card layout style (similar to news cards)
    const cards = items
      .map((it) => {
        const pubUrl = `https://pubmed.ncbi.nlm.nih.gov/${encodeURIComponent(it.pmid)}/`;
        const doiUrl = it.doi ? `https://doi.org/${encodeURIComponent(it.doi)}` : "";

        return `
          <article class="pub-card">
            <div class="pub-card-link">
              <div class="pub-card-body">
                <h3 class="pub-card-title"><a href="${pubUrl}">${esc(it.title)}</a></h3>
                <p class="pub-card-meta">${esc(it.journal)}${it.year ? " · " + esc(it.year) : ""}</p>
                ${
                  it.doi
                    ? `<p class="pub-card-doi"><strong>DOI:</strong> <a href="${doiUrl}">${esc(it.doi)}</a></p>`
                    : ""
                }
              </div>
            </div>
          </article>
        `;
      })
      .join("");

    setHTML(`<div class="pub-cards-grid">${cards}</div>`);
  }

  try {
    const ids = await esearchLatest3();
    const items = await efetchDetails(ids);
    renderCards(items);
  } catch (e) {
    setHTML(`<p class="error">${esc(e?.message || String(e))}</p>`);
  }
})();
