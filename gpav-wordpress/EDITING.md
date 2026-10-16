# Editing the website in WordPress

Login: `https://gpav.icb.ufmg.br/wp-admin/`

## Pages and translations

Use **Pages / Páginas**. The parent pages named Início and Home correspond to `/pt/` and `/en/`. Their child pages retain the original addresses. Use the **GPAV: idioma e tradução** box to select the language and link the other-language version; the pairing is saved in both directions. Translation is manual.

The plugin uses WordPress's native classic editor for imported pages to preserve existing HTML, with no separate Classic Editor plugin required. Use its Text/HTML tab for structural edits. Existing page HTML is stored in a WordPress HTML block wrapper; preserve the opening/closing `wp:html` comments. Pages containing shortcodes render their associated live content; keep the shortcode when editing surrounding text.

Shortcodes:

| Shortcode | Purpose |
|---|---|
| `[gpav_latest_news]` | Three newest news articles in the page's language |
| `[gpav_news_list]` | All news in the page's language, 12 per page |
| `[gpav_team]` | Team members grouped by role, with the appropriate biography language |
| `[gpav_recent_publications]` | Three recent PubMed publications |
| `[gpav_publications]` | PubMed list with pagination |
| `[gpav_contact_form]` | Original bilingual Formspree contact form |
| `[gpav_parcerias]` | The Partnerships page (see "Partnerships page" below) |

Use each PubMed shortcode at most once per page, because the original scripts target a single container ID. Imported pages already meet this condition.

## News

Use **GPAV Notícias / News**, not the ordinary WordPress Posts menu. Add/edit the title and body, set the publication date, choose a featured image and set the language. Add the English/Portuguese version as a separate news article, then select it in the translation box. Uploaded attachments can be selected using the **Anexos / Attachments** button.

The homepage and news page update automatically, filtered by language and sorted by publication date. An article's address is `/LANG/news/SLUG/`. Do not change existing slugs unless you also arrange redirects. Dates are displayed as `YYYY-MM-DD` as in the original site.

## Team

Use **GPAV Equipe / Team**. One record holds the name, role, optional title, Portuguese and English biographies, CV Lattes URL and order within the role. Set the photograph using **Featured image / Imagem destacada**. Publish the record to show it; draft records stay hidden. Role headings retain the original English labels.

## Branding, banners and navigation

Use **Appearance → Customize → GPAV: identidade e banner** for the logo, four slideshow images, titles/subtitles in both languages and footer address text. Empty/unset settings use the imported defaults. The footer ICB/UFMG logos can also be changed here, using images in the Media Library.

Default navigation preserves the original links and dropdowns. To customize it through WordPress, create separate menus in **Appearance → Menus**, assigning them to **GPAV Português** and **GPAV English**. Add Team and Collaborators as children of About, and Instagram as a child of News. Without assigned menus, the theme uses its original navigation.

## PubMed and contact settings

Use **Settings → GPAV** to edit the PubMed query, tool identifier and contact email, or the Formspree endpoint. The imported query selects publications in which Vivian Costa or Celso Queiroz-Junior is first or last author. The browser fetches publications from NCBI; no manually maintained publication table is required. The API contact email is retained from the source repository and can be changed here.

The contact form keeps all original fields, required validation, privacy acknowledgement and language metadata. Formspree manages recipients and delivery in its own account. The WordPress database stores the endpoint/settings, not received messages.

## Your three placeholders

- **Search / Buscar:** edit the page body; its original “coming soon” text remains.
- **Collaborators / Colaboradores:** edit the page body and add the content you want.
- **Instagram:** replace the placeholder text with the desired embed/content. The profile link is already present. Whether an external embed works depends on that provider and WordPress's HTML permissions.

There is no Decap CMS admin directory, OAuth service or GitHub login in the converted package. All site content editing uses ordinary WordPress administration.

## Partnerships page (Parcerias)

The page `/pt/parcerias/` is created automatically, once, the first time the site is visited after the plugin is installed or updated. It appears under **Pages** as *Parcerias*, as a child of *Início*, and its content is only the shortcode `[gpav_parcerias]`. Everything else lives in the plugin folder `wp-content/plugins/gpav-content/parcerias/`:

| File | What it controls |
|---|---|
| `parcerias.html` | All texts, sections, event captions and contact links. Comments in the file explain each block. |
| `parcerias.css` | Colours and layout. It reuses the site's own variables, so the page follows the site design. |
| `parcerias.js` | Fato ou Fake questions (`Q`), plans and prices (`T`, `R`), partnership e-mail and WhatsApp number (`MAIL`, `WA`). |
| `img/` | The photographs and logos used by the page. |

Notes:

- Only a Portuguese page exists. The English menu links to it with the label "Partnerships (PT)".
- If you replaced the default navigation in **Appearance → Menus**, add a link to `/pt/parcerias/` manually. The default menus already include it.
- If the page is deleted in WordPress it is not recreated automatically.
- The page stores nothing in the database; the e-mail and WhatsApp buttons open the visitor's own e-mail or WhatsApp.
