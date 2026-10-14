// Shared PubMed query settings for the site (Publications page + Home widgets)

window.GPAV_PUBMED = {
  TOOL: "gpav_ufmg_website",
  EMAIL: "secchimribeiro@gmail.com",
  DB: "pubmed",
  EUTILS: "https://eutils.ncbi.nlm.nih.gov/entrez/eutils",

  // First OR last author only (Vivian + Celso)
  TERM: [
    '"Costa VV"[1au]',
    '"Costa VV"[lastau]',
    '"Queiroz-Junior CM"[1au]',
    '"Queiroz-Junior CM"[lastau]',
    '"Queiroz Junior CM"[1au]',
    '"Queiroz Junior CM"[lastau]'
  ].join(" OR ")
};
