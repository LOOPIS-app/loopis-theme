# 📋 Changelog for "LOOPIS Theme"

## 0.82 (2026-XX-XX)
- FAQ content moved from pages to post (New plugin "LOOPIS Content" replaces "ACF")
- All previous snippets in WP-admin integrated
- All hardcoded category ID's replaced using loopis_cats()
- Category slug update: first > old, booked_locker > booked
- All relative paths adjusted to work with Multisite

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