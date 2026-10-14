# Validation record

Validated on 14 October 2026 using WordPress **7.1.3**, PHP **8.3.6** and MariaDB **10.11.14**. The SQL uses standard WordPress table definitions, `utf8mb4_unicode_520_ci` and MySQL-compatible statements. A separate MySQL server was not used for testing.

## Passed

- PHP syntax checks for the complete theme, content plugin and importer.
- JavaScript syntax checks for the slideshow and publication scripts.
- Actual WordPress import: **20 pages, six news articles, nine team profiles, 19 original media records**.
- A second import preserves those counts, including the same media entries; generated thumbnails are not imported as separate media records.
- All **26 public page/article URLs** return HTTP 200 in the test installation.
- Correct document language, reciprocal language switching and working image files on each route.
- News homepage cards display the three newest articles in the correct language.
- Team pages display all nine records with the appropriate language biographies.
- News title edits update homepage cards through the actual WordPress APIs.
- Metadata saves enforce nonce/capability checks and sanitize biographies and CV links.
- Translation unlink/relink updates both sides of the pairing.
- Root redirects to the Portuguese homepage.
- Slideshow click and keyboard play/pause.
- Original Formspree endpoint and six required form fields, including privacy acknowledgement.
- PubMed XML parsing and publication pagination using deterministic API fixtures in Chromium. Pagination uses `pubpage`, avoiding WordPress's reserved `p` query parameter.
- A direct request to the real NCBI ESearch endpoint returned HTTP 200. Browser tests use fixtures so they do not depend on changing publication records.
- Desktop rendering inspected; mobile layout inspected and horizontal overflow corrected. Home, team, contact and English publications pages pass width checks at 390 px.
- External API failure displays an error without breaking the rest of the page.
- SQL imported successfully into a separate fresh test database. All migrated content counts were restored, and the preexisting administrator account and administrator email were preserved.
- Final SQL has no user-table DDL/data, local testing URLs, embedded passwords or testing administrator credentials.

## Host-side checks still required

- Configure the actual domain, TLS, database credentials and web-server permalinks.
- Install WordPress core and create the host's administrator before SQL import.
- Confirm actual email delivery through the existing Formspree account. No message was submitted during testing.
- Confirm live PubMed loading, Google Fonts access and external-service availability in visitors' browsers.
- Add your own search, collaborator content and Instagram embed when ready; these remain placeholders by agreement.

WordPress core is not redistributed in this package. The custom theme/plugin, database, source content and all required media are included.
