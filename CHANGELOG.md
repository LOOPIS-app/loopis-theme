# 📋 Changelog for "LOOPIS Theme"

## 1.04 (2026-08-25)
- Major revision of footer and addition of page-area.php
- Former CPT 'forum' renamed to 'news'
- Former CPT 'support' replaced by 'forum'

## 1.03 (2026-06-26)
- Onboarding improved, redirecting to member_pending to main site.
- Ledger views added to post log, author log and admin area.

## 1.02 (2026-06-25)
- Bugs and small fixes
- Some admin panels moved to "LOOPIS Theme HQ"
- Privacy page added

## 1.01 (2026-06-22)
- Bugs fixes
- Page content for /user and /shop moved to "LOOPIS Theme HQ"

## 1.00 (2026-06-16)
- Deployment on new multisite!

## 0.90 (2026-06-15)
- NOT SINGLE SITE COMPATIBLE!
- Theme constants definition moved to new mu-plugin "LOOPIS Constants"
- Content of pages /shop and /user are now shared to "LOOPIS Theme HQ"
- Output with php logic moved from `templates` to `includes/output` and shared to "LOOPIS Theme HQ"

## 0.89 (2026-06-11)
- NOT SINGLE SITE COMPATIBLE!
- Single site stuff placed in folder `deprecated`
- New redirects to mainsite for login, signup and FAQ.

## 0.88 (2026-06-10)
- Now sharing styles to "LOOPIS Theme HQ" (introducing common `base.css`)

## 0.87 (2026-06-08)
- `style.css` cleaned up, with `forms.css` and `wpum.css` separated
- New template `gift-form.php`, replacing plugin WPUM Frontend Posting 
- Layout changes for `single.php` with support for three post images

## 0.86 (2026-06-01)
- Changes preparing for multisite migration:
- Stripe session payments after login instead of payment links on signup
- Moved the users posts tab from WPUM profile to activity page

## 0.85 (2026-05-18)
- Added templates for 404 + go back
- Adjusted styling for messages and payments
- Updated favicon code in headers
- Environment loader moved from theme to mu-plugins

## 0.84 (2026-05-06)
- WPUM post form for support posts replaced with our custom one
- Archive pages added for CPT's support and forum
- Bug fixes for swish buttons

## 0.83 (2026-04-24)
- All relative paths adjusted to work with Multisite
- Improved UI for post lists on profile and author page
- Role names and capabilites for "loopis_storage" revised

## 0.82 (2026-03-30)
- All FAQ content moved from pages to posts
- All previous snippets in WP-admin integrated
- All hardcoded category ID's replaced using loopis_cats()
- Category naming update: first > old, booked_locker > booked

## 0.81 (2026-03-19)
- Added folder `includes` with sub folders for better file structure
- Revised WPUM tabs and content on `/profile`

## 0.80 (2026-03-09)
- Preparing for migration of FAQ pages to FAQ posts
- Replacing all occurences of ACF funtion get_field()
- Blocking forwarding of already forwarded posts

## 0.79 (2026-02-10)
- Added frontend admin toggle for locker full warning

## 0.78 (2026-02-09)
- Improved handling of secrets (loading from wp-config or .env)
- Moved changelog to CHANGELOG.md

## 0.77 (2026-02-04)
- Comment Mention Pro plugin removed, replaced with cronjob-mailing
- Output of user/author info for admins improved

## 0.76 (2026-02-03)
- Simple version of Stripe payments implemented

## 0.75 (2026-01-14)
- Work in progress: Making live app match "LOOPIS Config" installations

## 0.7 (2025-12-03)
- Major structural changes
- Simplified use of page templates
- Dynamic fetching of post categories
- All content moved from snippets to php files
- GitHub repo made public

## 0.5 (2025-10-21)
- Removed all borrow and booking functionality

## 0.4 (2025-08-26)
- All admin dashboard snippet functions moved to php files

## 0.3 (2025-06-30)
- More content moved from snippets to php files
- New structure for admin templates

## 0.2 (2025-05-19)
- More content moved from snippets to php files
- Handling of cron jobs improved

## 0.1 (2025-04-04)
- First version of the theme live on loopis.app
- Functions moved from plugin to theme
- Development now assisted by GitHub Copilot instead of Poe