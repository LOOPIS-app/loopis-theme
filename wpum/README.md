# What is WPUM?

LOOPIS uses the free open-source WordPress plugin **WP User Manager** for some user-related functionality. WPUM should be used only when needed and modified to suit our needs.
**WP User Manager Documentation**: https://docs.wpusermanager.com/

# Whats is WPUM addons?

We currently use some paid WPUM addons (separate plugins) with a lifetime license for maximum 5 sites. We want to minimize the use of them – and if possible in the future skip them.
**WP User Manager addons**: https://wpusermanager.com/addons/

# WPUM Template Overrides

The folder `wpum/` contains template overrides for the **WP User Manager** plugin(s).

## How It Works

WPUM looks for custom templates in the active theme before using its own default templates. This allows LOOPIS to customize the appearance and functionality of WPUM without modifying the original plugin files.

### Template Hierarchy

When WPUM needs to use a template, it searches in this order:

1. **Theme folder**: `/wp-content/themes/loopis-theme/wpum/` ← Our custom templates
2. **Plugin folder**: `/wp-content/plugins/wp-user-manager/templates/` ← Plugin defaults

If a template exists in our theme's `wpum/` folder, it will be used instead of the plugin's default.

## Adding New Overrides

To override additional WPUM templates:

1. Find the template in `/wp-content/plugins/wp-user-manager/templates/`
2. Copy the file to this folder, maintaining the same subfolder structure
3. Modify the copied file as needed
4. Test thoroughly

## Important Notes

⚠️ **Version Compatibility**
- These overrides are based on WP User Manager version 2.9.9 (11th March 2024)
- When updating WPUM, check if template structure has changed
- Compare our overrides with new plugin templates after updates

⚠️ **Testing**
- Test all overridden templates after WPUM plugin updates
- Verify that forms and emails still work correctly
- Check that custom styling is maintained
- Test both logged-in and logged-out states