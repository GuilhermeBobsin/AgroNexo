<section class="card" aria-labelledby="profile-information-title">
    <div class="card-header">
        <div>
            <h2 id="profile-information-title" class="card-title">Informações pessoais</h2>
            <div class="card-subtitle">Sua foto e seus dados aparecem nas áreas de usuário do AgroNexo.</div>
        </div>
    </div>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    @php($fotoAtual = $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) : null)
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                        @if ($fotoAtual)
                            <span id="perfil-foto-preview" class="avatar avatar-xl rounded-circle flex-shrink-0" style="background-image: url('{{ $fotoAtual }}')" role="img" aria-label="Prévia da foto de perfil"></span>
                        @else
                            <span id="perfil-foto-preview" class="avatar avatar-xl rounded-circle bg-primary-lt text-primary flex-shrink-0" aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                        @endif
                        <div class="flex-fill">
                            <label for="profile_photo" class="form-label">Foto de perfil</label>
                            <input id="profile_photo" name="profile_photo" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('profile_photo') is-invalid @enderror">
                            <div class="form-hint">JPG, PNG ou WebP · até 2 MB · máximo de 4000 × 4000 px.</div>
                            @error('profile_photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @if ($user->profile_photo_path)
                                <label class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_profile_photo" id="remove_profile_photo" value="1" @checked(old('remove_profile_photo'))>
                                    <span class="form-check-label">Remover foto atual</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label">Nome completo</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" autocomplete="name" maxlength="255" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" maxlength="255" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
        </div>
    </form>
</section>

<script>
    (() => {
        const input = document.getElementById('profile_photo');
        const preview = document.getElementById('perfil-foto-preview');
        const remove = document.getElementById('remove_profile_photo');
        if (!input || !preview) return;

        input.addEventListener('change', () => {
            const file = input.files?.[0];
            if (!file || !file.type.startsWith('image/')) return;
            if (remove) remove.checked = false;
            const reader = new FileReader();
            reader.onload = () => {
                preview.style.backgroundImage = `url("${reader.result}")`;
                preview.textContent = '';
                preview.classList.remove('bg-primary-lt', 'text-primary');
            };
            reader.readAsDataURL(file);
        });

        remove?.addEventListener('change', () => {
            if (remove.checked) {
                input.value = '';
                preview.style.backgroundImage = '';
                preview.textContent = @json(mb_strtoupper(mb_substr($user->name, 0, 1)));
                preview.classList.add('bg-primary-lt', 'text-primary');
            } else if (@json($fotoAtual)) {
                preview.style.backgroundImage = `url("${@json($fotoAtual)}")`;
                preview.textContent = '';
                preview.classList.remove('bg-primary-lt', 'text-primary');
            }
        });
    })();
</script>
