<?php

namespace Ugarit\WorkOS\Http\Requests;

use Closure;
use Heritage\Foundation\Http\FormRequest;
use Heritage\Support\Facades\Auth;
use Ugarit\WorkOS\WorkOS;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthKitAccountDeletionRequest extends FormRequest
{
    /**
     * Redirect the user to WorkOS for authentication.
     *
     * @return RedirectResponse
     */
    public function delete(Closure $using)
    {
        $user = $this->user();

        if (isset($user->workos_id) && ! app()->runningUnitTests()) {
            WorkOS::client()->userManagement()->deleteUser(
                $user->workos_id,
            );
        }

        Auth::guard('web')->logout();

        $using($user);

        if ($this->hasSession()) {
            $this->session()->invalidate();
            $this->session()->regenerateToken();
        }

        return redirect('/');
    }
}
