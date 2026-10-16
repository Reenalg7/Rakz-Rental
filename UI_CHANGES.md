# UI/UX changes

Scope: Blade views only (`resources/views`). No controllers, routes, models, migrations,
validation, form field names, `data-testid` hooks or JavaScript behaviour were changed
(one exception: the theme toggle, see "Decisions to review").

## Palette
| Role | Value |
|---|---|
| Page background | `#FFFFFF` |
| Subtle surface (table heads, footer, admin sidebar) | `#FAFAFA` |
| Tint (hero, badges, hover/active states, compare bar) | `#E8F1F5` |
| Primary (buttons, links, active nav, prices) | `#005691` |
| Primary dark (hover, headings accents, plate text) | `#004A7C` |

Supporting neutrals derived from the palette for legibility: text `#14232F`, muted text `#506576`,
borders `#D4E2EB`, input borders `#738EA2`. Status messages (success/warning/error) keep
muted green / amber / red tints because those meanings need to stay distinguishable.

## Global
- Dark/gold theme replaced with a single white, blue-palette theme (both layouts).
- Fonts: Inter + Space Grotesk replaced with IBM Plex Sans (UI) and IBM Plex Mono (registration numbers).
- Emoji removed (the car emoji in the return-reminder email). Decorative Font Awesome icons removed from buttons, links, cards and stat tiles.
- Removed: backdrop blur, hover lift/glow on buttons and cards, gradient/gold glow shadows, ALL-CAPS table headers and labels, decorative arrows on links.
- Registration numbers (the product's identifier) now render as a "plate" chip across the site.
- Consistent components: cards, inputs, labels, buttons, badges, alerts, tables.
- Accessibility: skip-to-content link, visible keyboard focus, `aria-current` on active nav, labelled icon buttons, live region for availability check, reduced-motion respected. All text pairings meet WCAG AA contrast.
- Responsive: customer site gets a mobile menu; admin gets a scrollable section nav on small screens (previously both disappeared with no replacement); tables scroll horizontally instead of overflowing.

## By area
- Customer layout: new nav with active-page indicator, text "Notifications" link (was an unlabeled bell), simplified footer.
- Admin layout: lighter sidebar with active state, nav now generated from one list, logout available on mobile.
- Home: split hero (text + photo, photo no longer dimmed), plain headline, simplified category cards.
- Vehicle card / list / detail: plate chip, text specs instead of icons, tinted compare bar, cleaner price/action area.
- Booking flow: same behaviour; status text recoloured for white background; replacement notice was unreadable on white and is now a proper alert.
- Favorites / compare / notifications / profile: re-themed; "New" badge on unread notifications; unfavorite button now says "Remove"; fixed an unclosed `</div>` in the profile page.
- Admin dashboard: restructured into revenue banner + two labelled stat groups + recent bookings; rainbow icon tiles removed.
- Admin tables: coloured status/payment badges on bookings, action buttons no longer break table layout (`<td class="flex">` fixed), search buttons are primary.
- Reports: icon-free summary cards, Chart.js recoloured to the blue palette.
- Audit log: badge colours simplified to semantic groups.
- Emails (3) and PDFs (invoice, datasheet): moved from dark/gold to white/blue.

## Decisions to review
1. **Dark mode removed.** The palette is light and the brief asks for a white background, so the customer and admin theme toggles (`theme-toggle`, `admin-theme-toggle`) and the Shift+T shortcut are gone. If you have tests that click those, remove them or tell me and I will restore a toggle.
2. Sidebar and a few controls keep Font Awesome icons where they aid scanning (admin sidebar, heart favorite, hamburger).
3. `resources/css/app.css` / Vite are untouched: the layouts load Tailwind from the CDN as before, so the new design tokens live in each layout's `<style>` and `tailwind.config`.
