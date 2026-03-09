
<div class="col-xl-7 contact-card-wrapper">
    <div class="quote-form-box">
        <div class="form-divider">
            <hr>
            <span>{{ config('app.name') }}</span>
            <hr>
        </div>

        <form wire:submit.prevent="submit" class="contact-form" enctype="multipart/form-data">
            <div class="row g-0">
                @guest
                <div class="form-group col-md-12">
                    <div class="field-wrapper">
                        <input type="text" class="form-control" wire:model.defer="name" placeholder="{{ __('Full Name') }}">
                        <i class="fas fa-user field-icon"></i>
                    </div>
                    @error('name')
                    <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-12">
                    <div class="field-wrapper">
                        <input type="email" class="form-control" wire:model.defer="email" placeholder="{{ __('Email Address') }}">
                        <i class="fas fa-envelope field-icon"></i>
                    </div>
                    @error('email')
                    <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <div class="field-wrapper">
                        <input type="tel" class="form-control" wire:model.defer="number" placeholder="{{ __('Phone Number') }}">
                        <i class="fas fa-phone field-icon"></i>
                    </div>
                    @error('number')
                    <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                    @enderror
                </div>
                @endguest
                @auth
                <div class="auth-identity-card col-12">
                    <div class="auth-identity-avatar">
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="auth-identity-info">
                        <div class="auth-identity-name">{{ $name }}</div>

                        <div class="auth-identity-meta">
                            <span>
                                <i class="fas fa-envelope" style="font-size:0.7rem;opacity:0.6;"></i>
                                {{ $email }}
                            </span>

                            @if($number)
                            <span>
                                <i class="fas fa-phone" style="font-size:0.7rem;opacity:0.6;"></i>
                                {{ $number }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="auth-identity-badge">
                        {{ __('Logged in') }}
                    </div>
                </div>
                @endauth
                <div class="form-group @guest col-md-6 @endguest @auth col-md-12 @endauth">
                    <div class="field-wrapper">
                        <select wire:model.defer="subject" class="form-select">
                            <option value="" disabled hidden>{{ __('Select Subject') }}</option>

                            @foreach($subjects as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    @error('subject')
                    <span class="text-danger">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    <textarea wire:model.defer="message"
                              class="form-control"
                              rows="4"
                              placeholder="{{ __('Write your message here…') }}"></textarea>

                    @error('message')
                    <span class="text-danger">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </span>
                    @enderror
                </div>
                <div class="form-group col-12">

                    <div class="file-upload-zone" id="fileDropZone">

                        <input
                            type="file"
                            id="attachmentInput"
                            multiple
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            hidden
                        >

                        <div class="file-upload-icon-wrap">
                            <i class="fas fa-cloud-arrow-up"></i>
                        </div>

                        <div class="file-upload-title">
                            {{ __('Drop your files here, or') }}
                            <span>{{ __('browse') }}</span>
                        </div>

                        <div class="file-upload-hint">
                            {{ __('PDF, DOC, DOCX, JPG, PNG — max 5MB each') }}
                        </div>

                    </div>
                    <div id="uploadProgressWrap" style="display:none;" wire:ignore>
                        <div class="file-upload-progress">
                            <div class="file-upload-progress-bar" id="uploadProgressBar" style="width:0%"></div>
                        </div>
                        <div class="file-uploading-label">
                            {{ __('Uploading files…') }}
                        </div>
                    </div>
                    @if($attachments)
                    <div class="mt-3">
                        @foreach ($attachments as $file)
                        <div class="file-selected-chip">
                            <div class="file-selected-chip-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="file-selected-chip-name">
                                {{ $file->getClientOriginalName() }}
                            </div>
                            <i class="fas fa-check-circle file-selected-chip-check"></i>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @error('attachments')
                    <span class="mt-1 text-danger d-block">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </span>
                    @enderror

                    @error('attachments.*')
                    <span class="mt-1 text-danger d-block">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </span>
                    @enderror

                </div>
                <div class="form-btn col-12">
                    <button id="submitBtn" class="th-btn btn-lg w-100" type="submit">
                        <span id="submitBtnLabel">
                            {{ __('Send Message') }}
                            <i class="fas fa-arrow-up-right ms-2"></i>
                        </span>
                    </button>
                </div>

            </div>
        </form>
        @if (session()->has('success'))
        <div class="mt-3 alert alert-success">
            {{ session('success') }}
        </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {

    const accumulatedFiles = new Map();

    const fileKey = (f) => `${f.name}|${f.size}|${f.lastModified}`;
    const btn   = document.getElementById('submitBtn');
    const label = document.getElementById('submitBtnLabel');

    function setBtnState(state) {
        if (!btn || !label) return;

        const states = {
            idle:      '<i class="fas fa-arrow-up-right ms-2"></i>',
            uploading: '<i class="fas fa-spinner fa-spin me-2"></i>',
            sending:   '<i class="fas fa-spinner fa-spin me-2"></i>',
        };

        const texts = {
            idle:      '{{ addslashes(__('Send Message')) }}',
            uploading: '{{ addslashes(__('Uploading files…')) }}',
            sending:   '{{ addslashes(__('Sending…')) }}',
        };

        label.innerHTML = texts[state] + ' ' + states[state];
        btn.disabled    = state !== 'idle';
    }
    const progressWrap = document.getElementById('uploadProgressWrap');
    const progressBar  = document.getElementById('uploadProgressBar');

    function showProgress(pct) {
        if (!progressWrap || !progressBar) return;
        progressWrap.style.display = 'block';
        progressBar.style.width    = Math.min(pct, 100) + '%';
    }

    function hideProgress() {
        if (!progressWrap || !progressBar) return;
        progressWrap.style.display = 'none';
        progressBar.style.width    = '0%';
    }
    Livewire.hook('commit', ({ commit, succeed, fail }) => {
        const calls    = commit.calls ?? [];
        const isSubmit = calls.some(c => c.method === 'submit');
        if (!isSubmit) return;

        setBtnState('sending');

        succeed(() => {
            setBtnState('idle');
            accumulatedFiles.clear();
        });

        fail(() => {
            setBtnState('idle');
        });
    });
    function getComponent(el) {
        const root = el.closest('[wire\\:id]');
        if (!root) return null;
        return Livewire.find(root.getAttribute('wire:id'));
    }

    function addAndUpload(newFiles, zone) {
        if (!newFiles || !newFiles.length) return;

        newFiles.forEach(f => accumulatedFiles.set(fileKey(f), f));

        const allFiles  = Array.from(accumulatedFiles.values());
        const component = getComponent(zone);

        if (!component) {
            console.error('[FileUpload] Could not resolve Livewire component.');
            return;
        }
        setBtnState('uploading');
        showProgress(0);

        component.uploadMultiple(
            'attachments',
            allFiles,
            () => {
                setBtnState('idle');
                hideProgress();
            },
            (err) => {
                console.error('[FileUpload] Error:', err);
                setBtnState('idle');
                hideProgress();
            },
            (progressEvent) => {
                const pct = progressEvent?.detail?.progress ?? progressEvent ?? 0;
                showProgress(typeof pct === 'number' ? pct : 0);
            }
        );
    }

    function initDropZone() {
        const zone  = document.getElementById('fileDropZone');
        const input = document.getElementById('attachmentInput');

        if (!zone || !input) return;

        if (!zone._uploadBound) {
            zone._uploadBound = true;

            const prevent     = (e) => { e.preventDefault(); e.stopPropagation(); };
            const highlight   = () => zone.classList.add('drag-over');
            const unhighlight = () => zone.classList.remove('drag-over');

            ['dragenter', 'dragover'].forEach(evt =>
                zone.addEventListener(evt, e => { prevent(e); highlight(); })
            );
            ['dragleave', 'drop'].forEach(evt =>
                zone.addEventListener(evt, e => { prevent(e); unhighlight(); })
            );

            zone.addEventListener('drop', (e) => {
                addAndUpload(Array.from(e.dataTransfer.files), zone);
            });

            zone.addEventListener('click', () => {
                const freshInput = document.getElementById('attachmentInput');
                if (!freshInput) return;
                freshInput.value = '';
                freshInput.click();
            });
        }
        const onInputChange = () => {
            const freshInput = document.getElementById('attachmentInput');
            if (!freshInput) return;
            addAndUpload(Array.from(freshInput.files), zone);
            freshInput.value = '';
        };

        if (input._changeBound) {
            input.removeEventListener('change', input._changeBound);
        }
        input._changeBound = onInputChange;
        input.addEventListener('change', onInputChange);
    }

    initDropZone();

    Livewire.hook('morph.updated', () => { initDropZone(); });

});
</script>
