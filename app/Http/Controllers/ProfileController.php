<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $photo = $validated['profile_photo'] ?? null;
        $removePhoto = (bool) ($validated['remove_profile_photo'] ?? false);
        unset($validated['profile_photo'], $validated['remove_profile_photo']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $fotoAnterior = $user->profile_photo_path;
        if ($photo) {
            $user->profile_photo_path = $photo->storePublicly('profile-photos', 'public');
        } elseif ($removePhoto) {
            $user->profile_photo_path = null;
        }

        $user->save();

        if ($fotoAnterior && $fotoAnterior !== $user->profile_photo_path) {
            Storage::disk('public')->delete($fotoAnterior);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($user): void {
            if ($user->perfil === 'admin') {
                $administradoresAtivos = $user->newQuery()
                    ->where('perfil', 'admin')
                    ->where('status', 'ativo')
                    ->lockForUpdate()
                    ->get();
                if ($administradoresAtivos->count() <= 1) {
                    throw ValidationException::withMessages([
                        'password' => 'A conta é o último administrador ativo e não pode ser excluída.',
                    ])->errorBag('userDeletion');
                }
            }

            if ($user->tarefas()->exists() || $user->aplicacoes()->exists() || $user->recomendacoes()->exists()) {
                throw ValidationException::withMessages([
                    'password' => 'Esta conta possui registros operacionais no histórico. Peça a um administrador para desativar o acesso em vez de excluir a conta.',
                ])->errorBag('userDeletion');
            }

            // A sessão pode atualizar o remember_token no logout; faça isso antes da exclusão.
            $user->propriedades()->detach();
            Auth::logout();
            $user->delete();
        });

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
