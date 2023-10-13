<?php

namespace App\Plugins\EventConstants;


/**
 * All page frames are filters that accept a html string param (actual html), and return the same or new html.
 *
 * These page frames events are called from the resources/views/layouts which frame the pages. There are two kinds of layouts here: guest and logged in.
 * The guest filters have the word guest in them.
 *
 * example:
 * ```php
 * Eventy::addFilter(Plugin::FILTER_FRAME_SIDE_PANEL, function($html) { return $html. "<p>this is a message</p>"}
 * ```
 */
interface PageFrames {


    /**
     * Adds extra items into the last of the head part of the page.
     * This is useful for adding in scripts or stylesheets from the plugin's assets.
     * Can also be used to add in meta tags to the head section.
     */
    const FILTER_FRAME_EXTRA_HEAD = 'scm-view-filter-frame-app-head';

    /**
     * Adds extra items to the very bottom of the body element.
     * This is useful for adding scripts
     */
    const FILTER_FRAME_EXTRA_FOOT = 'scm-view-filter-frame-app-foot-scripts';

    /**
     * Adds extra items after the copy-write text.
     * This is in the visible footer area.
     */
    const FILTER_FRAME_EXTRA_FOOTER_ELEMENTS = 'scm-view-filter-frame-app-footer';


    /**
     * Put html into the top panel of the page.
     * This will appear any page content, but below the menu
     */
    const FILTER_FRAME_TOP_PANEL = 'scm-view-filter-frame-app-top';

    /**
     * Put html into the bottom panel of the page
     * This is below any page content, but above the footer.
     */
    const FILTER_FRAME_BOTTOM_PANEL = 'scm-view-filter-frame-app-bottom';

    /**
     * Put html into the bottom panel of the page.
     * On smaller screens this is below the main content. On larger screens this is the right of the page content.
     */
    const FILTER_FRAME_SIDE_PANEL = 'scm-view-filter-frame-app-sidebar';

    /**
     * Put html into inside the top menu at the end.
     * needs to match the li, a pattern used by the other menu items
     *
     * Example:
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_FRAME_END_TOP_MENU, function( string $extra_menu_stuff) {
     *      $item = view(ScmSampleTheming::getBladeRoot().'::content-for-filters.top-menu-item')->render();
     *      return $extra_menu_stuff."\n". $item;
     *  }
     * );
     * ```
     */
    const FILTER_FRAME_END_TOP_MENU = 'scm-view-filter-frame-app-top-menu';

    /**
     * Put html into inside the user menu at the end.
     * Needs to match the li, a pattern used by the other menu items
     */
    const FILTER_FRAME_END_USER_MENU = 'scm-view-filter-frame-app-user-menu';

    /**
     * Adds extra items into the last of the head part of the guest page.
     * This is useful for adding in scripts or stylesheets from the plugin's assets.
     * Can also be used to add in meta tags to the head section.
     * This is similar to the FILTER_FRAME_EXTRA_HEAD
     *
     * @see PageFrames::FILTER_FRAME_EXTRA_HEAD
     */
    const FILTER_GUEST_EXTRA_HEAD = 'scm-view-filter-frame-guest-head';

    /**
     * Adds extra items to the very bottom of the body element.
     *  This is useful for adding scripts
     *
     *  This is similar to the FILTER_FRAME_EXTRA_FOOT
     *
     * @see PageFrames::FILTER_FRAME_EXTRA_FOOT
     */
    const FILTER_GUEST_EXTRA_FOOT = 'scm-view-filter-frame-guest-foot-scripts';


    /**
     * Put html into the top panel of the page of the guest pages (pages not used for logged in stuff)
     * This will be above any form, such as the login form
     */
    const FILTER_GUEST_TOP_PANEL = 'scm-view-filter-frame-guest-top';

    /**
     * Put html into the bottom panel of the page of the guest pages (pages not used for logged in stuff)
     * This will be below any form, such as the login form, but above any footer
     */
    const FILTER_GUEST_BOTTOM_PANEL = 'scm-view-filter-frame-guest-bottom';

    /**
     * Put html into the side panel of the page of the guest pages (pages not used for logged in stuff)
     * On smaller pages this will be below and not to the side
     * But above any bottom panels
     */
    const FILTER_GUEST_SIDE_PANEL = 'scm-view-filter-frame-guest-sidebar';


    /**
     * Adds extra items after the copy-write text of the guest pages (pages not used for logged in stuff)
     * This adds visible content in the footers
     */
    const FILTER_GUEST_EXTRA_FOOTER_ELEMENTS = 'scm-view-filter-frame-guest-app-footer';

    /**
     * The site name, should just return a name or name in an inline element
     */
    const FILTER_SITE_NAME = 'filter-csm-site-name';


    /**
     * Put more links into the admin menu
     * needs to match the li, a pattern used by the other menu items
     *
     * Example:
     *
     * ```php
     * Eventy::addFilter(Plugin::FILTER_FRAME_ADMIN_LINKS, function( string $extra_admin_menu_stuff) {
     *      $item = '<li class="list-group-item"><a href="{{route('admin.logs')}}">Logs</a></li>';
     *      return $extra_admin_menu_stuff."\n". $item;
     *  }
     * );
     * ```
     */
    const FILTER_FRAME_ADMIN_LINKS = 'scm-view-filter-admin-links';

    const ALL_FILTERS_FOR_FRAMES = [
        self::FILTER_FRAME_BOTTOM_PANEL,
        self::FILTER_FRAME_END_TOP_MENU,
        self::FILTER_FRAME_END_USER_MENU,
        self::FILTER_FRAME_EXTRA_FOOT,
        self::FILTER_FRAME_EXTRA_FOOTER_ELEMENTS,
        self::FILTER_FRAME_EXTRA_HEAD,
        self::FILTER_FRAME_SIDE_PANEL,
        self::FILTER_FRAME_TOP_PANEL,
        self::FILTER_GUEST_BOTTOM_PANEL,
        self::FILTER_GUEST_EXTRA_FOOT,
        self::FILTER_GUEST_EXTRA_FOOTER_ELEMENTS,
        self::FILTER_GUEST_EXTRA_HEAD,
        self::FILTER_GUEST_SIDE_PANEL,
        self::FILTER_GUEST_TOP_PANEL,
        self::FILTER_SITE_NAME,
    ];
}


