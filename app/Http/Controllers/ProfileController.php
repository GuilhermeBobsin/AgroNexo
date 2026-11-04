<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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

        $erroExclusao = DB::transaction(function () use ($user): ?string {
            if ($user->perfil === 'admin') {
                $administradoresAtivos = $user->newQuery()
                    ->where('perfil', 'admin')
                    ->where('status', 'ativo')
                    ->lockForUpdate()
                    ->get();
                if ($administradoresAtivos->count() <= 1) {
                    return 'A conta é o último administrador ativo e não pode ser excluída.';
                }
            }

            if ($user->tarefas()->exists() || $user->aplicacoes()->exists() || $user->recomendacoes()->exists()) {
                return 'Esta conta possui registros operacionais no histórico. Peça a um administrador para desativar o acesso em vez de excluir a conta.';
            }

            // A sessão pode atualizar o remember_token no logout; faça isso antes da exclusão.
            $user->propriedades()->detach();
            Auth::logout();
            $user->delete();

            return null;
        });

        if ($erroExclusao !== null) {
            return Redirect::route('profile.edit')->withErrors([
                'password' => $erroExclusao,
            ], 'userDeletion');
        }

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
