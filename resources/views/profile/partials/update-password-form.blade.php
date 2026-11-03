<section class="card" aria-labelledby="profile-password-title">
    <div class="card-header">
        <div>
            <h2 id="profile-password-title" class="card-title">Segurança da conta</h2>
            <div class="card-subtitle">Use uma senha forte e diferente das suas outras contas.</div>
        </div>
    </div>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label for="update_password_current_password" class="form-label">Senha atual</label>
                    <input id="update_password_current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password" required>
                    @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="update_password_password" class="form-label">Nova senha</label>
                    <input id="update_password_password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password" required>
                    @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="update_password_password_confirmation" class="form-label">Confirme a nova senha</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password" required>
                    @error('password_confirmation', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-outline-primary">Atualizar senha</button>
        </div>
    </form>
</section>
