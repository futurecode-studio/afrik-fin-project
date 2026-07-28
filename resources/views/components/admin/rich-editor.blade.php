@props([
    'model',
    'value' => '',
    'label' => 'Contenu',
    'required' => false,
    'placeholder' => 'Rédigez votre texte ici…',
    'hint' => 'Utilisez la barre d’outils : gras, titres, listes, liens… Aucun code à écrire.',
    'minHeight' => '240px',
    'simple' => false,
])

@php
    $toolbar = $simple
        ? [
            ['bold', 'italic', 'underline'],
            [['list' => 'ordered'], ['list' => 'bullet']],
            ['link'],
            ['clean'],
        ]
        : [
            ['bold', 'italic', 'underline', 'strike'],
            [['header' => [2, 3, false]]],
            [['list' => 'ordered'], ['list' => 'bullet']],
            [['indent' => '-1'], ['indent' => '+1']],
            [['align' => []]],
            ['link'],
            ['blockquote'],
            ['clean'],
        ];
@endphp

<div {{ $attributes->class('adf-rich-editor space-y-1.5') }}>
    @if ($label)
        <label class="text-xs font-bold uppercase text-[#757683]">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div
        wire:ignore
        x-data="{
            quill: null,
            init() {
                if (this.quill || typeof Quill === 'undefined') {
                    if (typeof Quill === 'undefined') console.error('Quill non chargé');
                    return;
                }
                // Évite les barres d’outils dupliquées si le nœud a déjà été initialisé
                const host = this.$refs.editor;
                const parent = host.parentElement;
                parent?.querySelectorAll(':scope > .ql-toolbar').forEach((el) => el.remove());
                host.classList.remove('ql-container', 'ql-snow');
                host.innerHTML = '';

                this.quill = new Quill(host, {
                    theme: 'snow',
                    placeholder: @js($placeholder),
                    modules: { toolbar: @js($toolbar) },
                });
                const initial = @js($value ?? '');
                if (initial && initial !== '<p><br></p>') {
                    this.quill.root.innerHTML = initial;
                }
                this.quill.on('text-change', () => {
                    let html = this.quill.root.innerHTML;
                    if (html === '<p><br></p>') html = '';
                    $wire.set(@js($model), html);
                });
            }
        }"
        class="rounded-xl border border-[#c5c5d4] overflow-hidden bg-white focus-within:ring-2 focus-within:ring-[#001a61]/25 focus-within:border-[#001a61]"
    >
        <div
            x-ref="editor"
            class="adf-quill-editor bg-white text-sm text-[#131c2a]"
            style="min-height: {{ $minHeight }};"
        ></div>
    </div>

    <textarea wire:model="{{ $model }}" class="hidden" aria-hidden="true"></textarea>

    @if ($hint)
        <p class="text-[11px] text-[#757683]">{{ $hint }}</p>
    @endif

    @error($model)
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
