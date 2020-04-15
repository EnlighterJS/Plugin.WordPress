<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2020 Andi Dittrich
// -- https://github.com/AndiDittrich/WP-Skeleton
// --
// ---------------------------------------------------------------------------------------------------------------
// --
// -- This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
// -- If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// ---------------------------------------------------------------------------------------------------------------

// Environment Notifications

namespace Enlighter\skltn;

class EnvironmentCheck{

    public function __construct($cheManager){
    }

    // external triggered as admin_notices
    public function throwNotifications(){
        // trigger check
        $results = $this->check();

        // show errors
        foreach ($results['errors'] as $err){
            // styling
            echo '<div class="notice notice-error enlighter-notice"><p><strong>Enlighter Plugin Error: </strong>', $err, '</p></div>';
        }

        // show warnings
        foreach ($results['warnings'] as $err){
            // styling
            echo '<div class="notice notice-warning enlighter-notice"><p><strong>Enlighter Plugin Warning: </strong>', $err, '</p></div>';
        }
    }

    // perform the checks
    // @override
    public function check(){}
}