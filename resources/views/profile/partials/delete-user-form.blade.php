<section class="card border-danger-lt" aria-labelledby="profile-delete-title">
    <div class="card-header">
        <div>
            <h2 id="profile-delete-title" class="card-title text-danger">Excluir conta</h2>
            <div class="card-subtitle">A exclusão encerra seu acesso ao AgroNexo.</div>
        </div>
    </div>
    <div class="card-body">
        <p class="text-secondary mb-3">Para preservar o histórico operacional, contas vinculadas a tarefas, aplicações ou recomendações não podem ser excluídas. Nesses casos, peça ao administrador para desativar seu acesso.</p>
        @if ($errors->userDeletion->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->userDeletion->all() as $mensagem)
                    <div>{{ $mensagem }}</div>
                @endforeach
            </div>
        @endif
        <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Confirma a exclusão permanente da sua conta?')">
            @csrf
            @method('delete')
            <div class="row align-items-end g-3">
                <div class="col-md-7">
                    <label for="delete-account-password" class="form-label">Confirme sua senha</label>
                    <input id="delete-account-password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" autocomplete="current-password" required>
                    @error('password', 'userDeletion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-5 d-grid d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-outline-danger">Excluir minha conta</button>
                </div>
            </div>
        </form>
    </div>
</section>
