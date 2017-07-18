<?php

namespace App\AdminLte\Http\ViewComposers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class CurrentUserComposer
{
    /**
     * @var User
     */
    private $currentUser;

    public function __construct() {
        $this->currentUser = Auth::user();
    }

    public function compose(View $view)
    {
        $view->with('currentUser', $this->currentUser);
    }
}
