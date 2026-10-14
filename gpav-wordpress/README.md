# GPAV UFMG — WordPress conversion

Target address: **https://gpav.icb.ufmg.br/**

This package contains the custom theme, companion plugin, uploaded media, migrated bilingual content and a MySQL-compatible SQL export. WordPress core is installed separately using the official distribution. No Decap CMS, GitHub authentication or administrator credentials are included.

## Package contents

- `wp-content/themes/gpav/`: the original design converted to a WordPress theme.
- `wp-content/plugins/gpav-content/`: news, team profiles, translation links, Formspree and PubMed integration.
- `wp-content/uploads/gpav/`: original media, including logos, banners, team photographs and news images.
- `database/gpav.sql`: site database export, **excluding user and usermeta tables** so the host's WordPress administrator survives the import.
- `INSTALLATION.md`: installation instructions and server configuration.
- `EDITING.md`: editing through the standard WordPress administration panel.
- `migration/content.json` and `migration/import.php`: an alternative content import route using WP-CLI. Use this OR the SQL import, not both.
- `VALIDATION.md`: what was checked and what requires verification on the live host.

The website contains 20 ordinary pages (10 per language), six news articles (three translations in each language), nine bilingual team records and all original media. Search, collaborators and the Instagram feed remain editable placeholders. The existing Instagram profile link is retained.

The root address redirects to `/pt/`. English content lives under `/en/`. Original news URLs are preserved, including the slightly different English symposium slug.

**Give the host the whole package.** The SQL file contains site content and configuration; it does not contain the theme, plugin or image bytes. Install WordPress core separately. Do not upload the documentation, migration scripts or SQL files into the public web directory.
