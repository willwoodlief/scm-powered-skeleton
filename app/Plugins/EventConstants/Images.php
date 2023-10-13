<?php

namespace App\Plugins\EventConstants;

/**
 * All Images are filters that accept an image url in the param, and return the same or new url.
 * A valid image url needs to be returned, or return the original
 *
 * example:
 *
 * ```php
 * Eventy::addFilter(Plugin::FILTER_LOGO_IMAGE, function( string $relative_image_path)  {
 *      Utilities::markUnusedVar($relative_image_path);
 *      return ScmSampleTheming::getPluginRef()->getResourceUrl('images/loading-coffee.gif');
 *
 * }, 20, 1);
 * ```
 */
interface Images {
    /**
     * If an employee is missing their picture, this default is used instead
     */
    const FILTER_EMPLOYEE_ICON = 'filter-csm-image-employee-icon';

    /**
     * If a user is missing their picture, this default is used instead
     */
    const FILTER_USER_ICON = 'filter-csm-image-user-icon';

    /**
     * The image for the menu logo
     */
    const FILTER_LOGO_IMAGE = 'filter-csm-image-logo';

    /**
     * This is the background image for the guest pages, by default will use the scm ribbon background image
     */
    const FILTER_LOGIN_BACKGROUND_IMAGE = 'filter-csm-image-login-background';

    /**
     * If a contractor is missing their picture, this default is used instead
     */
    const FILTER_CONTRACTOR_IMAGE = 'filter-csm-image-contractor-icon';

    /**
     * The site favicon
     */
    const FILTER_FAVICON = 'filter-csm-image-favicon';



    const ALL_FILTER_IMAGES = [
      self::FILTER_EMPLOYEE_ICON,
      self::FILTER_USER_ICON,
      self::FILTER_LOGO_IMAGE,
      self::FILTER_CONTRACTOR_IMAGE,
      self::FILTER_FAVICON,
      self::FILTER_LOGIN_BACKGROUND_IMAGE
    ];
}
